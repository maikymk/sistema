<?php
/**
 * Faz autoload nas pastas passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = [
	INTERFACE_APP => [],
	APP . 'NovaConta' . DS => []
];
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerNovaConta implements InterfaceController {
	//caminho para os templates desse componente
	// private $templates  = APP . 'NovaConta' . DS . 'templates' . DS;
	private $telaPadrao = APP . 'NovaConta' . DS . 'templates' . DS . 'nova-conta.php';
	
	private $model;
	private $view;

	public function __construct() {
		$this->model = new ModelNovaConta();
		$this->view  = new ViewNovaConta();
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see InterfaceController::handle()
	 */
	public function handle() {
		if (isset($_POST['submitNovaConta'])) {
			//envio de dados para cadastro via ajax
			$sucesso = $this->model->validaNovaConta($_POST);
		
			// se todos os dados estiverem corretos retorna o usuário para a tela padrão
			if ($sucesso === true) {
				header("Location: " . BASE);
				exit;
			}
			
			$this->view->setErros($sucesso);
		}
		
		$this->view->setContainer($this->telaPadrao);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see InterfaceController::getTela()
	 */
	public function getTela() {
		return $this->view->getContainer();
	}
}