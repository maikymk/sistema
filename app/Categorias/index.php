<?php
//Faz autoload nas pasta passadas como key para o array, e busca os arquivos que estao como chave, passando vazio busca todos da pasta
$array_autoLoad = array(
	INTERFACE_APP => array(),
	APP.'Categorias'.DS => array('model.php', 'view.php')
);
	
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerCategorias implements InterfaceController{
	private $view;
	private $model;
	private $telaUser;
	private $templates = APP.'Categorias'.DS.'templates'.DS;
	private $idCategoria = null;
	private $acoes = array('visualizar', 'adicionar', 'editar', 'remover');
	private $acao;
	
	function __construct() {
		$this->view  = new ViewCategoria();
		$this->model = new ModelCategoria();
	}
	
	/**
	 * Metodo implementado da interface define o comportamento do componente Categorias
	 * 
	 * {@inheritDoc}
	 * @see InterfaceController::handle()
	 */
	function handle() {
		if( isset($_GET['aj']) ) {//verifica se a requisicao veio por ajax
			if( $this->validaGet() ){
				echo json_encode($this->verificaAcao());
			}
			exit;
		} else {
			if( $this->validaGet() ){
				$this->verificaAcao();
			}
			
			$this->verificaAcesso();
		}
	}
	
	/**
	 * Verifica o GET que foi enviado
	 * 
	 * @return bool
	 */
	private function validaGet(){
		if( isset($_GET['ac']) && in_array($_GET['ac'], $this->acoes) ) {
			$this->acao = htmlentities($_GET['ac']);
			$this->idCategoria = (int) $_GET['id'];
			return true;
		}
		return false;
	}
	
	/**
	 * Metodo implementado da interface
	 * 
	 * {@inheritDoc}
	 * @see InterfaceController::mostraTela()
	 */
	function mostraTela() {
		return $this->telaUser;
	}
	
	/**
	 * Verifica a parte do site que o usuario esta tentando acessar, caso nao esteja tentando acessar nenhuma manda ele pra home de usuario
	 * 
	 * @param String $acesso Parte do site que o usuario esta tentando acessar
	 */
	private function verificaAcesso() {
		$metodo = 'home';
		
		if( isset($_GET['ac']) ){
			$acao = htmlentities($_GET['ac']);
			
			switch ($acao){
				case 'adicionar':
					$metodo = 'adicionar';
					break;
				case 'editar':
					$metodo = 'editar';
					break;
				case 'remover':
					$metodo = 'remover';
					break;
				default: 
					$metodo = 'home';
					break;
			}
		}
		
		$this->$metodo();
	}
	
	/**
	 * Verifica a acao que o usuario esta tentando fazer, e ja aciona se existir e ja envia a requisicao para a model
	 * 
	 * @return bool|int
	 */
	private function verificaAcao(){
		$return = 0;
		if( $this->acao == 'adicionar' ){
			$return = $this->verificaAdicionar();
		} else if( $this->acao == 'editar' ){
			$return = $this->verificaEditar();
		} else if( $this->acao == 'remover' ){
			$return = $this->verificaRemover();
		}
		return $return;
	}
	
	/**
	 * Metodo que exibe a pagina home do usuario
	 */
	private function home(){
		$categorias = $this->model->visualizarCategorias();//busca as categorias do bd
		$this->view->setCategorias($categorias);//seta as categorias para a view
		$file = $this->templates.'home.php';//passa o arquivo home
		
		$this->telaUser = $this->view->retornaTela($file);//retorna o conteudo da tela home para o usuario
	}
	private function adicionar(){}
	private function editar(){}
	private function remover(){}
	
	/**
	 * Verifica se foi setado o post de adicionar uma categoria, se tiver sido ja envia os dados da adicao pra model
	 *
	 * @return bool|int
	 */
	private function verificaAdicionar(){
		if( isset($_POST['adicionarCat']) ){
			$nomeCat = htmlentities(strip_tags($_POST['nomeCat'], '<p><h1>'));//remove qualquer insercao que o usuario tentou fazer e recupera o nome
			
			if( $ultimoId = $this->model->adicionarCategoria($nomeCat) ){
				return array('id'=>$ultimoId, 'nome'=>html_entity_decode($nomeCat));
			}
		}
		return 0;
	}
	
	/**
	 * Verifica se foi setado o post de editar uma categoria, se tiver sido ja envia os dados da alteracao pra model
	 * 
	 * @return bool|int
	 */
	private function verificaEditar(){
		if( isset($_POST['editarCat']) ){
			$nomeCat = htmlentities(strip_tags($_POST['nomeCat'], '<p><h1>'));//remove qualquer insercao que o usuario tentou fazer e recupera o nome
			
			if( $this->model->editarCategoria($nomeCat, $this->idCategoria) ){
				return array('id'=>$this->idCategoria, 'nome'=>html_entity_decode($nomeCat));
			}
		}
		return 0;
	}
	
	/**
	 * Verifica se foi setado o post de remover uma categoria, se tiver sido ja envia a exclusao pra model
	 *
	 * @return bool|int
	 */
	private function verificaRemover(){
		if( isset($_POST['removerCat']) ){
			return $this->model->removerCategoria($this->idCategoria);
		}
		return 0;
	}
}