<?php
//Faz autoload nas pasta passadas como key para o array, e busca os arquivos que estao como chave, passando vazio busca todos da pasta
$array_autoLoad = array(
	INTERFACE_APP => array(),
	APP.'Relatorios'.DS => array('model.php', 'view.php')
);

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerRelatorios implements InterfaceController{
	private $view;
	private $model;
	private $telaUser;
	private $templates = APP.'Relatorios'.DS.'templates'.DS;
	private $acoes = array('categoria');
	private $acao;
	
	function __construct() {
		$this->view  = new ViewRelatorios();
		$this->model = new ModelRelatorios();
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
				case 'categoria':
					$metodo = 'categoria';
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
		return 0;
	}
	
	/**
	 * Metodo que monta e exibe a pagina home do usuario
	 */
	private function home(){
		//verifica se foi setado um filtro
		$tipo = (isset($_GET['filtro']) ? (int) htmlentities($_GET['filtro']) : null );
		
		//busca os lancamentos
		$dados = $this->model->relatoriosPorCategoria($tipo);
		//busca os tipos
		$tipos = $this->model->buscaTipos();
		//seta as categorias para a view
		$this->view->setDados($dados);
		//seta os tipos para a view
		$this->view->setTipo($tipos);
		//passa o arquivo home
		$file = $this->templates.'home.php';
		
		//retorna o conteudo da tela home para o usuario
		$this->telaUser = $this->view->retornaTela($file);
	}
}