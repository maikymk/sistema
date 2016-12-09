<?php
/**
 * Faz autoload nas pastas passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = [
	INTERFACE_APP => [],
	APP . 'Login' . DS => []
];
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerLogin implements InterfaceController {
	//caminho para os templates desse componente
	// private $templates  = APP . 'Login' . DS . 'templates' . DS;
	private $defaultShow = APP . 'Login' . DS . 'templates' . DS . 'login.php';
	
	private $model;
	private $view;

	public function __construct() {
		$this->model = new ModelLogin();
		$this->view  = new ViewLogin();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::handle()
	 */
	public function handle() {
		$success = $this->model->validateLogin();
		
		if ($success) {
			$lastPage = Server::lastPage();
			
			if ($lastPage && strpos($lastPage, 'login')) {
				header("Location: " . $lastPage);
				exit;
			}
			
			header("Location: " . DIR_RAIZ);
			exit;
		} else {
			$error = $this->model->getErrors();
			
			$this->view->setErrorr($error);
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