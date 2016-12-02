<?php
/**
 * Faz autoload nas pastas passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = array(
		INTERFACE_APP => [],
		APP . 'NovaConta' . DS => []
);
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerNovaConta implements InterfaceController {
	//caminho para os templates desse componente
	private $templates  = APP . 'NovaConta' . DS . 'templates' . DS;
	private $telaPadrao = 'nova-conta';
	private $telaSolicitada;

	public function __construct() {}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::handle()
	 */
	public function handle() {
		$this->telaSolicitada = $this->telaPadrao . '.php';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::mostraTela()
	 */
	public function getNomeTela() {
		return $this->templates . $this->telaSolicitada;
	}
}