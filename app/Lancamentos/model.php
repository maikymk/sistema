<?php
require_once WEB_SERVICE.'Lancamentos'.DS.'index.php';

class ModelLancamentos{
	private $webService;
	
	function __construct(){
		$this->webService = new ControllerWebserviceLancamentos;
	}
	
	/**
	 * Busca as categorias
	 * 
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarLancamentos($tipo=null){
		return $this->webService->visualizarLancamentos($tipo);
	}
	
	/**
	 * Retorna as categorias com status de visivel
	 * 
	 * @return array|boolean|1
	 */
	function getCategorias(){
		return $this->webService->getCategorias();
	}
	
	/**
	 * Retorna as receitas com status de visivel
	 *
	 * @return array|boolean|1
	 */
	function getReceitas(){
		return $this->webService->getReceitas();
	}
	
	/**
	 * Verifica se o usuario ja fez um lancamento com a mesma descricao nessa data
	 *
	 * @param String $data Data a verificar a descricao
	 * @param String $descricao Descricao a ser verificada juntamente com a data
	 * @return boolean
	 */
	function verificaLancamentoUsuario($data, $descricao){
		return $this->webService->verificaLancamentoUsuario($data, $descricao);
	}
	
	/**
	 * Valida os dados enviados pelo formulario
	 * Se nao tiver erro retorna um array com o valores validos
	 * Se tiver erro retorna false
	 *
	 * @param array $dados Dados para validar
	 * @return bool|array
	 */
	function validaDados($dados){
		return $this->webService->validaDados($dados);
	}
	
	/**
	 * Retorna todos os erros
	 * 
	 * @return array
	 */
	function getErros(){
		return $this->webService->getErros();
	}
	
	/**
	 * Cria um nova categorias
	 *
	 * @param int $idUsuario Id do usuario a ser salvo 
	 * @param String $nome Nome da categoria a ser criada
	 * @return int
	 */
	function adicionarLancamentos($dados){
		return $this->webService->adicionarLancamentos($dados);
	}
}