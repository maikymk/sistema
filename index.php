<?php

/**
 * Controller principal, todas as requisições passam por ele
 */

require_once "system" . DIRECTORY_SEPARATOR . "config.php";
require_once LIB . DS . "autoload.php";

$array = [
	ABSTRACT_CLASS => "",
    DIR_RAIZ 	   => ["model.php", "view.php"],
    SYSTEM   	   => ["query.php"],
    LIB 	 	   => "",
	HELPER   	   => ["css.php"]
];

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array);
$autoLoad->setExtensions([".php"]);
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
        $telaErro = $this->verificaErro();

        if ($telaErro) {
            $tela = $telaErro;
        } else {
        	$tela = $this->verificaPage();
        }

        //coloca o conteudo que o usuario acessou para ser exibido
        $this->view->container = $tela;
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
     * Seta a tela de login
     */
    private function setTelaLogin() {
    	return $this->validaAcesso('Login');
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

        // verifica se o usuario ja tem login e a sessao dele nao expirou
        if ($this->validaAcessoUser() && Usuario::getId() > 0) {
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
        if (!$this->validaAcessoUser() || !Usuario::getId()) {
            return $this->setTelaLogin();
        } else {
            //Passa o controller padrao de acesso
            return $this->validaAcesso(CONTROLLER_PADRAO);
        }
    }

    /**
     * Valida se o usuario pode continuar acessando o site,
     * ou se ele precisa fazer login
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
     * 
     * @param string $page Pagina que esta tentando acessar
     */
    private function acoesUsuarioLogado($page) {
        //verifica se esta fazendo logout
        if ($page == 'logout') {
            Sessao::destroiSessao();
            header('Location: ' . DIR_RAIZ);
            return true;
        } else {
            //valida a pagina que o usuario esta tentando acessar, se nao existir retorna erro 404
            $pageUser = $this->validaAcesso($page);

            if ($pageUser) {
                return $pageUser;
            }
            //mostra o erro 404
            return $this->setErro('404');
        }
    }

    /**
     * Valida a acao que o usuario que nao tem login esta executando
     * 
     * @param string $page Pagina que esta tentando acessar
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
        //tela de criacao de conta
        return $this->validaAcesso('NovaConta');
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

        	$tela = $obj->getTela();
        	if ($tela) {
        		return $tela;
        	}
        }
        //retorna o erro padrao
        return $this->setErro('404');
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