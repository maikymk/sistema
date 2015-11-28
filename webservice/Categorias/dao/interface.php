<?php
interface DAOInterfaceCategorias{
	
	/**
	 * Busca as categorias no BD
	 * 
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarCategorias($id=null);
	
	/**
	 * Cria um nova categorias no BD
	 *
	 * @param String $nome Nome da categoria a ser criada
	 * @return array
	 */
	function adicionarCategoria($nome);
	
	/**
	 * Edita uma categoria
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @param String $nome Nome para ser setado na categoria
	 * @return number|boolean
	 */
	function editarCategoria($nome, $id);
	
	/**
	 * Seta o status da categoria como 0, assim ela nao e mais exibida
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @return number|boolean
	 */
	function removerCategoria($id);
}