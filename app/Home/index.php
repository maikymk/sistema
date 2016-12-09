<?php
/**
 * Faz autoload nas pastas passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = array(
		INTERFACE_APP => [],
		APP . 'Home' . DS => []
);
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerHome implements InterfaceController {
	//caminho para os templates desse componente
	//private $templates   = APP . 'Home' . DS . 'templates' . DS;
	private $defaultShow = APP . 'Home' . DS . 'templates' . DS . 'home.php';

	private $view;
	
	public function __construct() {
		$this->view  = new ViewLogin();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::handle()
	 */
	public function handle() {
		$this->view->setContainer($this->defaultShow);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::show()
	 */
	public function show() {
		return $this->view->getContainer();
	}
}