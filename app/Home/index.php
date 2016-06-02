<?php

/**
 * Faz autoload nas pasta passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = array(
		INTERFACE_APP => array(),
		ABSTRACT_APP => array(),
		APP . 'Home' . DS => array());
$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerHome extends AbstractAppController implements InterfaceController {

	/**
	 * Metodo implementado da interface
	 * define o comportamento do componente Categorias
	 *
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::handle()
	 */
	public function handle() {}

	/**
	 * Metodo implementado da interface
	 *
	 * {@inheritDoc}
	 *
	 * @see InterfaceController::mostraTela()
	 */
	public function mostraTela() {
		return true;
	}
}