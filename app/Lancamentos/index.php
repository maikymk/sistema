<?php
//Faz autoload nas pasta passadas como key para o array, e busca os arquivos que estao como chave, passando vazio busca todos da pasta
$array_autoLoad = array(
	INTERFACE_APP => array(),
	APP.'Lancamentos'.DS => array('model.php', 'view.php')
);

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
//$autoLoad->setExtensions(array('.php'));
$autoLoad->load();

class ControllerLancamentos implements InterfaceController{
	private $view;
	private $model;
	private $telaUser;
	private $templates = APP.'Lancamentos'.DS.'templates'.DS;
	private $idLancamento = null;
	private $acoes = array('visualizar', 'adicionar');
	private $acao;
	private $msgSucesso = array();
	
	function __construct() {
		$this->view  = new ViewLancamentos();
		$this->model = new ModelLancamentos();
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
				if( $this->verificaAcao() ){
					$this->view->setSucessos($this->msgSucesso);
				}
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
		if( $this->acao == 'adicionar' ){
			return $this->verificaAdicionar();
		}
		return 0;
	}
	
	/**
	 * Metodo que monta e exibe a pagina home do usuario
	 */
	private function home(){
		//busca os lancamentos
		$lancamentos = $this->model->visualizarLancamentos();
		//seta as categorias para a view
		$this->view->setLancamentos($lancamentos);
		//passa o arquivo home
		$file = $this->templates.'home.php';
		
		//retorna o conteudo da tela home para o usuario
		$this->telaUser = $this->view->retornaTela($file);
	}
	
	/**
	 * Metodo que monta e exibe a tela de cadastro de novo lancamento
	 */
	private function adicionar(){
		//busca aos lancamentos
		$categorias = $this->model->getCategorias();
		//busca as receitas
		$receitas 	= $this->model->getReceitas();
		//seta as categorias para a view
		$this->view->setCategorias($categorias);
		//seta as categorias para a view
		$this->view->setReceitas($receitas);
		//passa a tela de adicionar
		$file = $this->templates.'adicionar.php';
		
		//retorna o conteudo da tela adicionar para o usuario
		$this->telaUser = $this->view->retornaTela($file);
	}
	
	/**
	 * Verifica se foi setado o post de adicionar uma categoria, se tiver sido ja envia os dados da adicao pra model
	 *
	 * @return bool|int
	 */
	private function verificaAdicionar(){
		if( isset($_POST['adicionarLancamento']) ){
			//valida os dados enviados pelo usuario
			$dados = $this->model->validaDados($_POST);
			
			//se a validacao der certo, invoca o metodo para adicionar os dados
			if( $dados ){
				if( $this->idLancamento = $this->model->adicionarLancamentos($dados) ){
					$this->msgSucesso[] = 'Dados salvos com sucesso!';
					return array('id'=>$this->idLancamento, 'dados'=>($dados));
				}
			} else{//se der erros, passa eles para a view
				$erros = $this->model->getErros();
				$this->view->setErros($erros);
			}
		}
		return 0;
	}
}