<?php
/**
 * Faz autoload nas pasta passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = array(
		INTERFACE_APP => [],
		ABSTRACT_APP => [],
		APP . 'Home' . DS => []
);
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerHome extends AbstractAppController implements InterfaceController {
	private $telaPadrao = 'home';
	private $telas      = ["home"];
	//caminho para os templates desse componente
	private $templates;
	private $telaAtual;

	public function __construct() {
		$this->templates = APP . 'Home' . DS . 'templates' . DS;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::handle()
	 */
	public function handle() {
		if (!empty($_GET["ac"]) && in_array($_GET["ac"], $this->telas)) {
			$this->telaAtual = htmlentities($_GET["ac"]) . '.php';
		} else {
			$this->telaAtual = $this->telaPadrao . '.php';
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::mostraTela()
	 */
	public function mostraTela() {
		return $this->templates . $this->telaAtual;
	}
}