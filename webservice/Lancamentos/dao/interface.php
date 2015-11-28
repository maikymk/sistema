<?php
interface DAOInterfaceLancamentos{
	
	/**
	 * Busca as categorias
	 *
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarLancamentos($tipo=null);
	
	/**
	 * Retorna as categorias com status de visivel
	 *
	 * @return array|boolean|1
	 */
	function getCategorias();
	
	/**
	 * Retorna as receitas com status de visivel
	 *
	 * @return array|boolean|1
	 */
	function getReceitas();
	
	/**
	 * Verifica se o usuario ja fez um lancamento com a mesma descricao nessa data
	 *
	 * @param String $data Data a verificar a descricao
	 * @param String $descricao Descricao a ser verificada juntamente com a data
	 * @return boolean
	 */
	function verificaLancamentoUsuario($data, $descricao);
	
	/**
	 * Cria um nova categoria
	 *
	 * @param array() $dados $dados a serem salvos
	 * @return int
	 */
	function adicionarLancamentos($dados);
}