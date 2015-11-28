<?php
class DAOJsonLancamentos extends DAOAbstractJson implements DAOInterfaceLancamentos{
	
	/**
	 * Busca as categorias no json
	 *
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarLancamentos($tipo=null){
		$this->abreArquivo();
		$this->leArquivo();
		/*
		echo '<pre>';
		print_r($this->dadosArquivo);
		print_r($this->getCategorias());
		echo '</pre>';
		*/
		$result = array();
		if( $this->existeArrayCategorias() && $this->existeArrayLancamentos() ){//verifica se existe categoria e lancamento
			if( !empty($tipo) ){
				foreach( $this->dadosArquivo['lancamentos'] as $key=>$dados ){
					if( $dados['tipo'] == $tipo ){
						$result[$key] = $this->lancamentos($dados);//busca todos os lancamentos validos
					}
				}
			} else{
				foreach( $this->dadosArquivo['lancamentos'] as $key=>$dados ){
					if( $this->statusCatTipoUser($dados['categoria'], $dados['tipo'], $dados['usuario']) ){
						$result[$key]  = $this->lancamentos($dados);//busca todos os lancamentos validos
					}
				}
			}
		}
		
		$this->fechaArquivo();
		return $result;
	}
	
	/**
	 * Retorna um array com os dados passados
	 * 
	 * @param array() $dados Dados a serem usados para montarem o array
	 * @return array()
	 */
	private function lancamentos($dados){
		return array(
				'id' 		=> $dados['id'],
				'descricao' => $dados['descricao'],
				'categoria' => $this->getNome('categoria', $dados['categoria']),
				'usuario'   => $this->getNome('usuario', $dados['usuario']),
				'tipo'	    => $this->getNome('tipo_lancamento', $dados['tipo']),
				'data'	    => date('d/m/Y', strtotime($dados['data'])),
				'valor'	    => number_format($dados['valor'], 2, ",",".")
		);
	}
	
	/**
	 * Retorna o o valor da key nome do array passado usando o id passado pra comparacao
	 * 
	 * @param String $arrayNome Nome do array a fazer a busca
	 * @param int $id Id a comparar para pegar o retorno
	 * @return String 
	 */
	private function getNome($arrayNome, $id){
		foreach( $this->dadosArquivo[$arrayNome] as $dado ){
			if( $dado['id'] == $id ){
				return $dado['nome'];
			}
		}
	}
	
	/**
	 * Valida status dos arrays de Categorias, Tipo de lancamento e Usuarios
	 * 
	 * @param int $cat Id da categoria a ser validado
	 * @param int $tipo Id do tipo de lancamento a ser validado
	 * @param int $user Id do usuario a ser validado
	 * @return boolean
	 */
	private function statusCatTipoUser($cat, $tipo, $user){
		$ok = 0;
		if( $this->validaStatus('categoria', $cat) ){
			$ok++;
		}
		if( $this->validaStatus('tipo_lancamento', $tipo) ){
			$ok++;
		}
		if( $this->validaStatus('usuario', $user) ){
			$ok++;
		}
		
		return (($ok == 3) ? true: false );
	}
	
	/**
	 * Retorna o status do array passado usando o id pra comparar
	 *
	 * @param String $arrayNome Nome do array a fazer a busca
	 * @param int $id Id a comparar para pegar o retorno
	 * @return bool
	 */
	private function validaStatus($arrayNome, $id){
		foreach( $this->dadosArquivo[$arrayNome] as $dado ){
			if( $dado['id'] == $id  && $dado['status'] == 1 ){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Verifica se existe o array de categorias no arquivo json
	 * 
	 * @return bool
	 */
	private function existeArrayCategorias(){
		return $this->verificaExisteArray('categoria');
	}
	
	/**
	 * Verifica se existe o array de lancamentos no arquivo json
	 *
	 * @return bool
	 */
	private function existeArrayLancamentos(){
		return $this->verificaExisteArray('lancamentos');
	}
	
	/**
	 * Verifica se existe o array de usuarios no arquivo json
	 *
	 * @return bool
	 */
	private function existeArrayUsuario(){
		return $this->verificaExisteArray('usuario');
	}
	
	/**
	 * Verifica se existe o array de tipos e lancamento no arquivo json
	 *
	 * @return bool
	 */
	private function existeArrayTipoLancamento(){
		return $this->verificaExisteArray('tipo_lancamento');
	}
	
	/**
	 * Retorna as categorias com status de visivel
	 *
	 * @return array|boolean
	 */
	function getCategorias(){
		$this->verificaArquivoAberto();
		if( $this->existeArrayCategorias() ){
			return $this->dadosArquivo['categoria'];
		}
		return false;
	}
	
	/**
	 * Retorna as receitas com status de visivel
	 *
	 * @return array|boolean
	 */
	function getReceitas(){
		$this->verificaArquivoAberto();
		if( $this->existeArrayTipoReceita() ){
			return $this->dadosArquivo['tipo_receita'];
		}
		return false;
	}
	
	/**
	 * Verifica se o usuario ja fez um lancamento com a mesma descricao nessa data
	 *
	 * @param String $data Data a verificar a descricao
	 * @param String $descricao Descricao a ser verificada juntamente com a data
	 * @return boolean
	 */
	function verificaLancamentoUsuario($data, $descricao){}
	
	/**
	 * Cria um nova categoria
	 *
	 * @param array() $dados $dados a serem salvos
	 * @return int
	 */
	function adicionarLancamentos($dados){}
}