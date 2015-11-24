<?php
class ViewCategoria implements InterfaceView{
	public $categorias = array();
	
	function __construct(){}
	
	/**
	 * Seta as categorias que serao exibidas na tela para o usuario
	 * 
	 * @param array $categorias
	 */
	function setCategorias($categorias){
		$this->categorias = $categorias;
	}
	
	/**
	 * Metodo implementado da interface
	 * 
	 * {@inheritDoc}
	 * @see InterfaceView::retornaTela()
	 */
	function retornaTela($arq){
		ob_start();
		require_once $arq;
		$html = ob_get_contents();
		ob_end_clean();
	
		return $html;
	}
}