<?php

class ViewLogin extends AbstractView {
	public function __construct() {}
	
	/**
	 * Monta e presenta a tela para o usuario
	 */
	public function montaTela() {
		require_once 'templates' . DS . 'login.php';
	}
}