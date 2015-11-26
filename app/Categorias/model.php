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
		$sql = "SELECT * FROM categoria WHERE ".(!empty($id) ? "id = ".$id." AND status=1" : "status=1");
		if( $dados = Query::query($sql) ){
			return $dados;
		}
		return array();
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
	 * Seta o status da categoria como 0, assim ela nao e mais exibida
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @return number|boolean
	 */
	function removerCategoria($id){
		$sql = "UPDATE categoria SET status=0 WHERE id=".$id."";
		return Query::query($sql);
	}
}