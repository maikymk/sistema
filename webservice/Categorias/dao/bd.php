<?php
class DAOBdCategorias implements DAOAbstractCategorias{
	/**
	 * Busca as categorias no BD
	 * 
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarCategorias($id=null){
		$id  = null;
		$sql = "SELECT * FROM categoria WHERE status=1";
		
		if( !empty($id) ){
			$sql .= " AND id = ?";
		}
		
		if( $dados = Query::query($sql, $id) ){
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
		$sql = "INSERT INTO categoria(nome) VALUES(?)";
		if( $id = Query::query($sql, $nome) ){
			return $id;
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
	function editarCategoria($nome, $id){
		$sql = "UPDATE categoria SET nome=? WHERE id=?";
		return Query::query($sql, array($nome, $id));
	}
	
	/**
	 * Seta o status da categoria como 0, assim ela nao e mais exibida
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @return number|boolean
	 */
	function removerCategoria($id){
		$sql = "UPDATE categoria SET status=0 WHERE id=?";
		return Query::query($sql, $id);
	}
}