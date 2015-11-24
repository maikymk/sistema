<?php
class ModelCategoria{
	function __construct(){}
	
	/**
	 * Busca as categorias no BD
	 * 
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarCategorias($id=null){
		$sql = "SELECT * FROM categoria ".(!empty($id) ? "WHERE id = ".$id : "");
		return Query::query($sql);
	}
	
	/**
	 * Cria um nova categorias no BD
	 *
	 * @param String $nome Nome da categoria a ser criada
	 * @return array
	 */
	function adicionarCategoria($nome){
		$sql = "INSERT INTO categoria(nome) VALUES('".$nome."')";
		if( Query::query($sql) ){
			return QUERY::getUltimoId();
		}
		return 0;
	}
	
	/**
	 * Edita uma categoria
	 * 
	 * @param int $id Id da categoria a ser alterada
	 * @param String $nome Nome para ser setado na categoria
	 * @return number|boolean
	 */
	function editarCategoria($id, $nome){
		$sql = "UPDATE categoria SET nome = '".$nome."' WHERE id=".$id."";
		return Query::query($sql);
	}
	
	/**
	 * Remove uma categoria
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @return number|boolean
	 */
	function removerCategoria($id){
		$sql = "DELETE FROM categoria WHERE id=".$id."";
		return Query::query($sql);
	}
}