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
        if (!$this->verificaErro()) {
            $this->verificaPage();
        }
        
        /**
         * Se tiver uma tela para ser exibida, envia ela para a view
         * Se nao tiver, pode ser uma requisicao ajax, ai nao exibe a tela
         */
        if ($this->container) {
            //coloca o conteudo que o usuario acessou para ser exibido
            $this->view->setContainer($this->container);
            //monta a tela a ser exibida ao usuario
            $this->view->montaTela();
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
            $this->setErro($erro);
            return true;
        }
        return false;
    }
    
    /**
     * Seta a tela de login e passa o erro ocorrido se existir
     */
    private function setTelaLogin() {
        $this->setContainer($this->view->telaLogin($this->erroLogin));
    }

    /**
     * Verifica a pagina que o usuario esta tentando acessar, se nao tiver nenhuma seta a padrao
     * 
     * @return bool
     */
    private function verificaPage() {
        if (isset($_GET['page'])) {
            $this->existePage();
        } else {
            $this->naoExistePage();
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
            $this->acoesUsuarioLogado($page);
        } else {
            //usuario sem login so pode criar conta
            $this->acoesUsuarioSemLogin($page);
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
            $this->setTelaLogin();
        } else {
            //Passa a pagina padrao de acesso
            $this->validaAcesso(PAGINA_PADRAO);
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
            //valida a pagina que o usuario esta tentando acessar, se nao existir seta a padrao
            if (!$this->validaAcesso($page)) {
                //mostra o erro 404
                $this->setErro('404');
            }
        }
    }
    
    /**
     * Valida a cao que o usuario que nao tem login esta executando
     */
    private function acoesUsuarioSemLogin($page) {
        // se nao tiver login, o usuario so pode fazer a acao de nova conta
        if ($page != 'new-account') {
            $this->setTelaLogin();
        } else {
            $this->novaConta();
        }
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
        } else {
            if ((isset($_POST['submitNovaConta']))) {
                //envio de dados para cadastro sem ajax
                $this->model->validaNovaConta($_POST);
            }
            //tela de criacao de conta
            $this->setContainer($this->view->telaNovaConta($erroNovaConta));
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
     * Valida a pagina que o usuario esta tentando acessar, se nao encontrar passa a pagina padrao 'Usuario'
     * 
     * @param String $acesso Nome do componente que o usuario esta tentando acessar
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
            
            //se tiver um retorno de tela, passa para a variavel responsavel usando o metodo
            if ($tela = $obj->mostraTela()) {
                $this->setContainer($tela);
            }
            
            return true;
        }
        return false;
    }

    /**
     * Seta a pagina que sera mostrada ao usuario na variavel $container
     * 
     * @param String $container Tela a ser exibida
     */
    private function setContainer($container) {
        $this->container = $container;
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
        $this->setContainer($this->view->telaErro($telaErro));
    }
}

$controller = new ControllerFrame();
$controller->handle();