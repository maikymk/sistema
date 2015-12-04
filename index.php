<?php
/**
 * Controller principal, todas as solicitacoes passam por ele
 */

require_once 'system' . DIRECTORY_SEPARATOR . 'config.php';
require_once LIB . DS . 'autoload.php';

$array = array(
    DIR_RAIZ => array('model.php', 'view.php'), 
    SYSTEM => array('query.php'), 
    LIB => ''
);

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array);
$autoLoad->setExtensions(array('.php'));
$autoLoad->load();

class ControllerFrame {
    private $erroLogin;
    private $container;
    public $model;
    public $view;

    public function __construct() {
        Sessao::iniciaSessao();
        
        $this->model = new ModelFrame();
        $this->view = new ViewFrame();
    }

    /**
     * Funcao que faz o frame iniciar seus processos
     */
    public function handle() {
        if ($telaErro = $this->verificaErro()) {
            $this->container = $telaErro;
        } else {
            $this->container = $this->verificaPage();
        }
        
        /**
         * Se tiver um retono numerico, e requisicao ajax
         * Requisicao nao ajax monta a tela para o usuario
         */
        if (!is_numeric($this->container)) {
            //coloca o conteudo que o usuario acessou para ser exibido
            $this->view->setContainer($this->container);
            //monta a tela a ser exibida ao usuario
            $this->view->montaTela();
        } elseif ($this->container == 0) {
            //se for igual a zero, e uma requisicao ajax, mas o tempo de login esgotou
            echo 0;
        }
    }
    
    /**
     * Verifica se e para exibir alguma pagina de erro, se for ja seta ela
     * 
     * @return boolean
     */
    private function verificaErro() {
        //verifica se ocorreu algum erro
        if (isset($_GET['erro'])) {
            $erro = htmlentities($_GET['erro']);
            //seta o erro que foi passado pelo $_GET via htaccess
            return $this->setErro($erro);
        }
        return false;
    }
    
    /**
     * Seta a tela de login e passa o erro ocorrido se existir
     */
    private function setTelaLogin() {
        return $this->view->telaLogin($this->erroLogin);
    }

    /**
     * Verifica a pagina que o usuario esta tentando acessar, se nao tiver nenhuma seta a padrao
     * 
     * @return bool
     */
    private function verificaPage() {
        if (isset($_GET['page'])) {
            return $this->existePage();
        } else {
            return $this->naoExistePage();
        }
    }
    
    /**
     * Coisas que o usuario podera ver se passar uma pagina pela url
     */
    private function existePage() {
        $page = htmlentities($_GET['page']);
        
        /**
         * verifica se o usuario ja tem login e a sessao dele nao expirou,
         * assim o usuario pode fazer logout ou acessar alguma page
         */
        if ($this->validaAcessoUser() && $this->verificaLogin()) {
            return $this->acoesUsuarioLogado($page);
        } else {
            //usuario sem login so pode criar conta
            return $this->acoesUsuarioSemLogin($page);
        }
    }
    
    /**
     * Coisas que o usuario podera ver se nao passar uma pagina pela url
     */
    private function naoExistePage() {
        /**
         * verifica se nao esta tentando logar e a sessao ja expirou,
         * ou se nao existe login do usuario
         * se acontecer algum dos casos mostra a tela de login
         */
        if (!$this->validaAcessoUser() || !$this->verificaLogin()) {
            return $this->setTelaLogin();
        } else {
            //Passa a pagina padrao de acesso
            return $this->validaAcesso(PAGINA_PADRAO);
        }
    }
    
    /**
     * Valida se o usuario pode continuar acessando o site, 
     * ou se ele precisa fazer login novamente
     * 
     * @return boolean
     */
    private function validaAcessoUser() {
        if (isset($_POST['submitTelaLogin'])) {
            return true;
        } elseif (!Sessao::verificaTempoSessao()) {
            return false;
        }
        return true;
    }

    /**
     * Verifica a pagina que o usuario logado esta acesssando
     */
    private function acoesUsuarioLogado($page) {
        //verifica se esta fazendo logout
        if ($page == 'logout') {
            Sessao::destroiSessao();
            header('Location: ' . DIR_RAIZ);
            return true;
        } else {
            //valida a pagina que o usuario esta tentando acessar, se nao existir retorna erro 404
            if ($pageUser = $this->validaAcesso($page)) {
                return $pageUser;
            }
            //mostra o erro 404
            return $this->setErro('404');
        }
    }
    
    /**
     * Valida a cao que o usuario que nao tem login esta executando
     */
    private function acoesUsuarioSemLogin($page) {
        // se nao tiver login, o usuario so pode fazer a acao de nova conta
        if ($page == 'new-account') {
            return $this->novaConta();
        } elseif (isset($_GET['aj'])) {
            //requisicao feita por ajax, nao mostra tela de login, so retorna 0
            return 0;
        }
        return $this->setTelaLogin();
    }
    
    /**
     * Verifica a acao do usuario que esta tentando criar um nova conta
     */
    private function novaConta() {
        $erroNovaConta = null;
        
        //envio de dados para cadastro via ajax
        if (isset($_POST['validaNovaConta'])) {
            $dados = $this->model->validaNovaConta($_POST);
            echo json_encode($dados);
            return 1;
        } else {
            if ((isset($_POST['submitNovaConta']))) {
                //envio de dados para cadastro sem ajax
                $this->model->validaNovaConta($_POST);
            }
            //tela de criacao de conta
            return $this->view->telaNovaConta($erroNovaConta);
        }
    }
    
    /**
     * Instancia a classe que valida acesso do usuario, e verifica se ele está logando
     * 
     * @return boolean
     */
    private function verificaLogin() {
        $acesso = new ValidaAcesso();
        $this->erroLogin = $acesso->validaLogin();
        
        if ($this->erroLogin === true) {
            return true;
        }
        return false;
    }

    /**
     * Valida a pagina que o usuario esta tentando acessar, 
     * se a pagina que o usuario acessou nao devolver uma tela,
     * seta o valor 1, assumindo assim que se trata de uma requisicao ajax,
     * mas se nao encontrar a pagina que o usuario solicitou retorna false
     * 
     * @param String $acesso Nome do componente que o usuario esta tentando acessar
     * @return string|int|bool
     */
    private function validaAcesso($acesso) {
        $componente = ucfirst($acesso);
        //monta o caminho do arquivo a ser instanciado dentro do Componente
        $arq = APP . $componente . DS . 'index.php';
        
        if (is_file($arq)) {
            require_once $arq;
            
            //Monta o nome da classe a ser instanciada
            $class = 'Controller' . $componente;
            $obj = new $class();
            $obj->handle();
            
            if ($tela = $obj->mostraTela()) {
                return $tela;
            }
            
            return 1;
        }
        return false;
    }

    /**
     * Verifica o erro passado, e salva a tela com o erro correspondente na variavel $container e depois apresenta ao usuario
     * 
     * @param String $erro Recebe um erro e valida ele pra apresentar sua tela. O erro padrao e 500 (Erro no servidor)
     */
    private function setErro($erro = ERRO_PADRAO) {
        $telaErro = $this->model->validaErro($erro);
        //caminho das telas de erro.nome da tela de erro.extensao
        $telaErro = TELAS_ERRO . $telaErro . '.php';
        //salva no conteudo da pagina a tela de erro
        return $this->view->telaErro($telaErro);
    }
}

$controller = new ControllerFrame();
$controller->handle();