<?php
/**
 * Controller principal, todas as solicitacoes passam por ele
 */

require_once 'system'.DIRECTORY_SEPARATOR.'config.php';
require_once LIB.DS.'autoload.php';

$array = array(
	DIR_RAIZ => array('model.php', 'view.php'),
	SYSTEM => array('query.php'),
	LIB => ''
);

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array);
$autoLoad->setExtensions(array('.php'));
$autoLoad->load();

class ControllerFrame{
	private $erroLogin;
	private $container;
	public  $model;
	public  $view;
	
	function __construct(){
		if( !Sessao::verificaSessao() ){
			Sessao::iniciaSessao();
		}
		
		$this->model = new ModelFrame;
		$this->view  = new ViewFrame;
	}
	
	/**
	 * Funcao que faz o frame iniciar seus processos
	 */
	function handle(){
		$this->trataUrl();//trata a requisicao do usuario pela URL
		
		$this->view->setContainer($this->container);//coloca o conteudo que o usuario acessou para ser exibido
		$this->view->montaTela();//monta a tela a ser exibida ao usuario
	}
	
	/**
	 * Faz tratamento da url que o usuario esta tentando acessar
	 */
	private function trataUrl(){
		if( isset($_GET['erro']) ){//verifica se ocorreu algum erro
			$erro = htmlentities($_GET['erro']);
			$this->verificaErro($erro);//verifica o erro que foi passado pelo $_GET via htaccess
		} else if( !$this->verificaPage() ){
			$this->setContainer($this->view->telaLogin($this->erroLogin));//seta a tela de login e passa o erro ocorrido
		}
	}
	
	/**
	 * Verifica a pagina que o usuario esta tentando acessar, se nao tiver nenhuma seta a padrao
	 * 
	 * @return bool
	 */
	private function verificaPage(){
		if( isset($_GET['page']) ){
			$page = htmlentities($_GET['page']);
			
			if( $this->verificaLogin() ){//se ja tiver login, o usuario pode fazer logout ou acessar alguma page
				if( $page == 'logout' ) {//verifica se esta fazendo logout
					Sessao::destroiSessao();
					header('Location: '.DIR_RAIZ);
					exit;
				} else {
					if( !$this->validaAcesso($page) ){ //valida a pagina que o usuario esta tentando acessar, se nao existir seta a padrao
						$this->verificaErro('404');//mostra o erro 404
					}
				}
			} else {// se nao tiver login, o usuario so pode fazer a acao de nova conta
				if( $page == 'new-account' ){
					$erroNovaConta = null;
					
					if( isset($_POST['submitNovaConta']) ){//sem ajax
						$this->model->validaNovaConta($_POST);
					}
					else if( isset($_POST['validaNovaConta']) ){//ajax
						$dados = $this->model->validaNovaConta($_POST);
						echo json_encode($dados);
						exit;
					}
					$this->setContainer($this->view->telaNovaConta($erroNovaConta));
				} else{
					$this->verificaErro('404');//mostra o erro 404
				}
			}
			return true;
		} else {//se nao existir page, tambem verifica se existe login
			if( $this->verificaLogin() ){//nao existe um page, mas o usuario tem login, mostra pagina home pra ele
				$this->validaAcesso(PAGINA_PADRAO);//Passa a pagina padrao de acesso
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Instancia a classe que valida acesso do usuario, e verifica se ele está logando
	 * 
	 * @return boolean
	 */
	private function verificaLogin(){
		$acesso = new ValidaAcesso;
		$this->erroLogin = $acesso->validaLogin();
		
		if( $this->erroLogin === true ){
			return true;
		}
		return false;
	}
	
	/**
	 * Valida a pagina que o usuario esta tentando acessar, se nao encontrar passa a pagina padrao 'Usuario'
	 * 
	 * @param String $acesso Nome do componente que o usuario esta tentando acessar
	 */
	private function validaAcesso($acesso){
		$componente = ucfirst($acesso);
		$arq = APP.$componente.DS.'index.php';//monta o caminho do arquivo a ser instanciado dentro do Componente
		
		if( is_file($arq) ){
			require_once $arq;
			
			$class = 'Controller'.$componente;//Monta o nome da classe a ser instanciada
			$obj = new $class;
			$obj->handle();
			
			$this->setContainer($obj->mostraTela());
			return true;
		} 
		return false;
	}
	
	/**
	 * Seta a pagina que sera mostrada ao usuario na variavel $container
	 * 
	 * @param String $container Tela a ser exibida
	 */
	private function setContainer($container){
		$this->container = $container;
	}
	
	/**
	 * Verifica o erro passado, e salva a tela com o erro correspondente na variavel $container e depois apresenta ao usuario
	 * 
	 * @param String $erro Recebe um erro e valida ele pra apresentar sua tela. O erro padrao e 500 (Erro no servidor)
	 */
	private function verificaErro($erro=ERRO_PADRAO){
		$telaErro = $this->model->validaErro($erro);
		//caminho das telas de erro.nome da tela de erro.extensao
		$telaErro = TELAS_ERRO.$telaErro.'.php';
		//salva no conteudo da pagina a tela de erro
		$this->setContainer($this->view->telaErro($telaErro));
	}
}

$controller = new ControllerFrame;
$controller->handle();