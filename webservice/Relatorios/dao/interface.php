<?php
interface DAOAbstractRelatorios{
	
	/**
	 * Busca todos os dados de lancamento e monta um relatorio por categorias
	 *
	 * @param String $tipo Tipo da receita a ser exibida
	 * @return $array
	 */
	function relatoriosPorCategoria($tipo=null);
	
	/**
	 * Busca as receitas que estao visiveis
	 *
	 * @return boolean|1
	 */
	function buscaTipos();
}