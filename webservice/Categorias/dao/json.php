<?php

class DAOJsonCategorias extends DAOAbstractJson implements DAOAbstractCategorias{
	/**
	 * Busca as categorias no BD
	 *
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarCategorias($id=null){
		$this->abreArquivo();
		$this->leArquivo();
		
		$result = array();
		
		if( $this->verificaExisteArrayCategoria() ){
			foreach( $this->dadosArquivo['categoria'] as $key=>$categoria ){
				if( $categoria['status'] == 1 ){
					$result[$key] = $this->dadosArquivo['categoria'][$key];
				}
			}
		}
		
		$this->fechaArquivo();
		return $result;
	}
	
	/**
	 * Verifica se existe um array de categorias
	 * 
	 * @return bool
	 */
	private function verificaExisteArrayCategoria(){
		if( !empty($this->dadosArquivo['categoria']) ){
			return true;
		}
		return false;
	}
	
	/**
	 * Cria um nova categorias no json
	 *
	 * @param String $nome Nome da categoria a ser criada
	 * @return array
	 */
	function adicionarCategoria($nome){
		$this->abreArquivo();
		$this->leArquivo();
		
		$ok = 0;
		if( $this->verificaExisteArrayCategoria() ){
			$lastArray = end($this->dadosArquivo['categoria']); 
			$lastId = $lastArray['id'] + 1;
			$this->dadosArquivo['categoria'][] = array('id'=>$lastId, 'nome'=>$nome, 'status'=>1);
			
			if( $this->salvaArquivo($this->dadosArquivo) ){
				$ok++;
			}
		}
		
		$this->fechaArquivo();
		return (($ok > 0) ? $lastId : '' );
		
		/*
		$result = array();
		$sql = "INSERT INTO categoria(nome) VALUES(?)";
		if( $id = Query::query($sql, $nome) ){
			return $id;
		}
		return 0;
		*/
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