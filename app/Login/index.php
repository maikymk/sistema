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
	private $telaPadrao = APP . 'Login' . DS . 'templates' . DS . 'login.php';
	
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
		$sucesso = $this->model->validaLogin();
		
		if ($sucesso) {
			$lastPage = Server::lastPage();
			
			if ($lastPage && strpos($lastPage, 'login')) {
				header("Location: " . $lastPage);
				exit;
			}
			
			header("Location: " . DIR_RAIZ);
			exit;
		} else {
			$erro = $this->model->getErro();
			
			$this->view->setErros($erro);
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