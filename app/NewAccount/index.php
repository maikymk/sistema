<?php
/**
 * Faz autoload nas pastas passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = [
	INTERFACE_APP => [],
	APP . 'NewAccount' . DS => []
];
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerNewAccount implements InterfaceController {
	//caminho para os templates desse componente
	// private $templates  = APP . 'NewAccount' . DS . 'templates' . DS;
	private $defaultShow = APP . 'NewAccount' . DS . 'templates' . DS . 'new-account.php';
	
	private $model;
	private $view;

	public function __construct() {
		$this->model = new ModelNewAccount();
		$this->view  = new ViewNewAccount();
	}

	/**
	 * 
	 * {@inheritDoc}
	 * @see InterfaceController::handle()
	 */
	public function handle() {
		if (isset($_POST['submitNewAccount'])) {
			//envio de dados para cadastro via ajax
			$success = $this->model->validateNewAccount($_POST);
		
			// se todos os dados estiverem corretos retorna o usuário para a tela padrão
			if ($success === true) {
				header("Location: " . BASE);
				exit;
			}
			
			$this->view->setErrorr($success);
		}
		
		$this->view->setContainer($this->defaultShow);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see InterfaceController::show()
	 */
	public function show() {
		return $this->view->getContainer();
	}
}