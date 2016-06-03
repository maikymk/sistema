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
    public $model;
    public $view;

    public function __construct() {
    	error_reporting(E_ALL);
    	ini_set("display_errors", true);
    	ini_set("error_reporting", E_ALL & ~E_STRICT);

    	Sessao::iniciaSessao();

        $this->model = new ModelFrame();
        $this->view  = new ViewFrame();
    }

    /**
     * Funcao que faz o frame iniciar seus processos
     */
    public function handle() {
        if ($telaErro = $this->verificaErro()) {
            $arquivo = $telaErro;
        } else {
            $arquivo = $this->verificaPage();
        }

        //coloca o conteudo que o usuario acessou para ser exibido
        $this->view->setContainer($arquivo);
        //monta a tela a ser exibida ao usuario
        $this->view->montaTela();
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
        return TELA_LOGIN;
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
     *
     * @return String Caminho do arquivo que será exibido ao usuário
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
            //Passa o controller padrao de acesso
            return $this->validaAcesso(CONTROLLER_PADRAO);
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
        }
        return $this->setTelaLogin();
    }

    /**
     * Verifica a acao do usuario que esta tentando criar um nova conta
     */
    private function novaConta() {
        //envio de dados para cadastro via ajax
        if (isset($_POST['validaNovaConta'])) {
            $dados = $this->model->validaNovaConta($_POST);
            echo json_encode($dados);
            exit;
        }

        //tela de criacao de conta
        return TELA_NOVA_CONTA;
    }

    /**
     * Instancia a classe que valida acesso do usuario, e verifica se ele está logando
     *
     * @return boolean
     */
    private function verificaLogin() {
        $acesso = new ValidaAcesso();
        return $acesso->validaLogin();
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
        	$obj   = new $class();
        	$obj->handle();

        	$tela = $obj->mostraTela();
        	if ($tela) {
        		return $tela;
        	}
        }
        //retorna o erro padrao
        return $this->setErro();
    }

    /**
     * Verifica o erro passado e retorna a tela com o erro correspondente para depois apresentar ao usuario
     *
     * @param String $erro Recebe um erro e valida ele pra apresentar sua tela. O erro padrao e 500 (Erro no servidor)
     * @return String Endereço da tela de erro que será exibida
     */
    private function setErro($erro = ERRO_PADRAO) {
        $telaErro = $this->model->validaErro($erro);
        //caminho da tela de erro
        return TELAS_ERRO . $telaErro . '.php';
    }
}

$controller = new ControllerFrame();
$controller->handle();