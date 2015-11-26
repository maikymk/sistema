<?php
class ViewRelatorios implements InterfaceView{
	public $dadosRelatorio = array();
	public $tipos = array();
	
	function __construct(){}
	
	/**
	 * Funcao para setar os dados do relatorio
	 *
	 * @param array $dados Dados passados pelo controller para serem exibidos ao usuario
	 */
	function setDados($dados=array()){
		$this->dadosRelatorio = $dados;
	}
	

	/**
	 * Funcao para setar os tipos do filtro do relatorio
	 *
	 * @param array $tipos Tipos passados pelo controller pra serem exibidos no filtro para o usuario
	 */
	function setTipo($tipos=array()){
		$this->tipos= $tipos;
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