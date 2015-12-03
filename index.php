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
        if (! Sessao::verificaSessao()) {
            Sessao::iniciaSessao();
        }
        
        $this->model = new ModelFrame();
        $this->view = new ViewFrame();
    }

    /**
     * Funcao que faz o frame iniciar seus processos
     */
    public function handle() {
        //trata a requisicao do usuario pela URL
        $this->trataUrl();
        
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
     * Faz tratamento da url que o usuario esta tentando acessar
     */
    private function trataUrl() {
        //verifica se ocorreu algum erro
        if (isset($_GET['erro'])) {
            $erro = htmlentities($_GET['erro']);
            //verifica o erro que foi passado pelo $_GET via htaccess
            $this->verificaErro($erro);
        } elseif (!$this->verificaPage()) {
            //seta a tela de login e passa o erro ocorrido
            $this->setContainer($this->view->telaLogin($this->erroLogin));
        }
    }

    /**
     * Verifica a pagina que o usuario esta tentando acessar, se nao tiver nenhuma seta a padrao
     * 
     * @return bool
     */
    private function verificaPage() {
        $return = false;
        
        if (isset($_GET['page'])) {
            $page = htmlentities($_GET['page']);
            
            //se ja tiver login, o usuario pode fazer logout ou acessar alguma page
            if ($this->verificaLogin()) {
                //verifica se esta fazendo logout
                if ($page == 'logout') {
                    Sessao::destroiSessao();
                    header('Location: ' . DIR_RAIZ);
                    $return = true;
                } else {
                    //valida a pagina que o usuario esta tentando acessar, se nao existir seta a padrao
                    if (! $this->validaAcesso($page)) {
                        //mostra o erro 404
                        $this->verificaErro('404');
                    }
                }
            } else {
                // se nao tiver login, o usuario so pode fazer a acao de nova conta
                if ($page == 'new-account') {
                    $erroNovaConta = null;
                    
                    //envio de dados para cadastro sem ajax
                    if (isset($_POST['submitNovaConta'])) {
                        $this->model->validaNovaConta($_POST);
                        //tela de criacao de conta
                        $this->setContainer($this->view->telaNovaConta($erroNovaConta));
                    } elseif (isset($_POST['validaNovaConta'])) {
                        //envio de dados para cadastro via ajax
                        $dados = $this->model->validaNovaConta($_POST);
                        echo json_encode($dados);
                    } else {
                        //tela de criacao de conta
                        $this->setContainer($this->view->telaNovaConta($erroNovaConta));
                    }
                } else {
                    //se nao tiver login e tentar acessar alguma area do site, mostra o erro 404
                    $this->verificaErro('404');
                }
            }
            $return = true;
        } else {
            //se nao existir page, tambem verifica se existe login
            //nao existe um page, mas o usuario tem login, mostra pagina home pra ele
            if ($this->verificaLogin()) {
                //Passa a pagina padrao de acesso
                $this->validaAcesso(PAGINA_PADRAO);
                $return = true;
            }
        }
        return $return;
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
    private function verificaErro($erro = ERRO_PADRAO) {
        $telaErro = $this->model->validaErro($erro);
        //caminho das telas de erro.nome da tela de erro.extensao
        $telaErro = TELAS_ERRO . $telaErro . '.php';
        //salva no conteudo da pagina a tela de erro
        $this->setContainer($this->view->telaErro($telaErro));
    }
}

$controller = new ControllerFrame();
$controller->handle();