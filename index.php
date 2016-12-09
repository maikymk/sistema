<?php

/**
 * Controller principal, todas as requisições passam por ele
 */

require_once "system" . DIRECTORY_SEPARATOR . "config.php";
require_once SYSTEM . DS . "autoload.php";

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

    	Session::sessionStart();

        $this->model = new ModelFrame();
        $this->view  = new ViewFrame();
    }

    /**
     * Funcao que faz o frame iniciar seus processos
     */
    public function handle() {
        $error = $this->checkError();

        if ($error) {
            $container = $error;
        } else {
        	$container = $this->verifyPage();
        }

        //coloca o conteudo que o usuario acessou para ser exibido
        $this->view->container = $container;
        //monta a tela a ser exibida ao usuario
        $this->view->show();
    }

    /**
     * Verifica se e para exibir alguma pagina de erro, se for ja seta ela
     *
     * @return boolean
     */
    private function checkError() {
        //verifica se ocorreu algum erro
        if (isset($_GET['erro'])) {
            $error = htmlentities($_GET['erro']);
            //seta o erro que foi passado pelo $_GET via htaccess
            return $this->setError($error);
        }
        return false;
    }

    /**
     * Seta a tela de login
     */
    private function setLogin() {
    	return $this->validateAccess('Login');
    }

    /**
     * Verifica a pagina que o usuario esta tentando acessar, se nao tiver nenhuma seta a padrao
     *
     * @return bool
     */
    private function verifyPage() {
        if (isset($_GET['page'])) {
            return $this->existsPage();
        } else {
            return $this->notExistsPage();
        }
    }

    /**
     * Coisas que o usuario podera ver se passar uma pagina pela url
     */
    private function existsPage() {
        $page = htmlentities($_GET['page']);

        // verifica se o usuario ja tem login e a sessao dele nao expirou
        if ($this->validateUserAccess() && User::getId() > 0) {
            return $this->actionsUserLogged($page);
        } else {
            //usuario sem login so pode criar conta
            return $this->actionsUserNotLogged($page);
        }
    }

    /**
     * Coisas que o usuario podera ver se nao passar uma pagina pela url
     *
     * @return String Caminho do arquivo que será exibido ao usuário
     */
    private function notExistsPage() {
        /**
         * verifica se nao esta tentando logar e a sessao ja expirou,
         * ou se nao existe login do usuario
         * se acontecer algum dos casos mostra a tela de login
         */
        if (!$this->validateUserAccess() || !User::getId()) {
            return $this->setLogin();
        } else {
            //Passa o controller padrao de acesso
            return $this->validateAccess(CONTROLLER_PADRAO);
        }
    }

    /**
     * Valida se o usuario pode continuar acessando o site,
     * ou se ele precisa fazer login
     *
     * @return boolean
     */
    private function validateUserAccess() {
        if (isset($_POST['submitLogin'])) {
            return true;
        } elseif (!Session::checkSessionTime()) {
            return false;
        }
        return true;
    }

    /**
     * Verifica a pagina que o usuario logado esta acesssando
     * 
     * @param string $page Pagina que esta tentando acessar
     */
    private function actionsUserLogged($page) {
        //verifica se esta fazendo logout
        if ($page == 'logout') {
            Session::destroySession();
            header('Location: ' . DIR_RAIZ);
            return true;
        } else {
            //valida a pagina que o usuario esta tentando acessar, se nao existir retorna erro 404
            $pageUser = $this->validateAccess($page);

            if ($pageUser) {
                return $pageUser;
            }
            //mostra o erro 404
            return $this->setError('404');
        }
    }

    /**
     * Valida a acao que o usuario que nao tem login esta executando
     * 
     * @param string $page Pagina que esta tentando acessar
     */
    private function actionsUserNotLogged($page) {
        // se nao tiver login, o usuario so pode fazer a acao de nova conta
        if ($page == 'new-account') {
            return $this->newAccount();
        }
        return $this->setLogin();
    }

    /**
     * Verifica a acao do usuario que esta tentando criar um nova conta
     */
    private function newAccount() {
        //tela de criacao de conta
        return $this->validateAccess('NewAccount');
    }

    /**
     * Valida a pagina que o usuario esta tentando acessar,
     * se a pagina que o usuario acessou nao devolver uma tela,
     * seta o valor 1, assumindo assim que se trata de uma requisicao ajax,
     * mas se nao encontrar a pagina que o usuario solicitou retorna false
     *
     * @param String $access Nome do componente que o usuario esta tentando acessar
     * @return string|int|bool
     */
    private function validateAccess($access) {
        $component = ucfirst($access);
        //monta o caminho do arquivo a ser instanciado dentro do Componente
        $arq = APP . $component . DS . 'index.php';

        if (is_file($arq)) {
        	require_once $arq;

        	//Monta o nome da classe a ser instanciada
        	$class = 'Controller' . $component;
        	$obj   = new $class();
        	$obj->handle();

        	$tela = $obj->show();
        	if ($tela) {
        		return $tela;
        	}
        }
        //retorna o erro padrao
        return $this->setError('404');
    }

    /**
     * Verifica o erro passado e retorna a tela com o erro correspondente para depois apresentar ao usuario
     *
     * @param String $error Recebe um erro e valida ele pra apresentar sua tela. O erro padrao e 500 (Erro no servidor)
     * @return String Endereço da tela de erro que será exibida
     */
    private function setError($error = DEFAULT_ERROR) {
        $printError = $this->model->validateError($error);
        //caminho da tela de erro
        return TEMPLATES_ERROR . $printError . '.php';
    }
}

$controller = new ControllerFrame();
$controller->handle();