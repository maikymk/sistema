<?php
class DAOJsonRelatorios extends DAOAbstractJson implements DAOAbstractRelatorios {
	/**
	 * Busca todos os dados de lancamento e monta um relatorio por categorias
	 *
	 * @param String $tipo Tipo da receita a ser exibida
	 * @return $array
	 */
	function relatoriosPorCategoria($tipo=null){
		$this->abreArquivo();
		$this->leArquivo();
		
		$result = array();
		
		if( $this->existeDados('lancamentos') ){
			foreach( $this->dadosArquivo['lancamentos'] as $key=>$dado ){
				if( !empty($tipo) ) {
					if( $tipo == $dado['tipo'] ){
						$result = $this->setDadosLancamento($dado);
					}
				} else{
					$result = $this->setDadosLancamento($dado);
				}
			}
		}
		
		$this->fechaArquivo();
		
		return $result;
	}
	
	/**
	 * Seta os dados dos lancamentos
	 * 
	 * @param array() $dado Dados a serem setados
	 * @return array()
	 */
	private function setDadosLancamento($dado){
		$result = array();
		if( ($categoria = $this->getNome('categoria', $dado['categoria'])) && ($nomeTipo = $this->getNome('tipo_receita', $dado['tipo'])) ){
			$result[$categoria][] = array(
				'id' => $dado['id'],
				'descricao' => $dado['descricao'],
				'valor' => number_format($dado['valor'], 2, ",","."),
				'data' => date('d/m/Y', strtotime($dado['data'])),
				'tipo' => $nomeTipo,
				'usuario' => Usuario::getNomePorId($dado['usuario']),
			);
		}
		return $result;
	}
	
	/**
	 * Busca o(s) nome(s) de uma tabela especifica, se passar o id busca um nome especifico, se nao busca todos
	 * 
	 * @param String $nomeArray Nome do array que ira buscar pelo(s) nome(s)
	 * @param int $id Id para fazer a busca pelo nome
	 * @return String|array|bool
	 */
	private function getNome($nomeArray, $id=null){
		if( $this->existeDados($nomeArray) ){
			if( !empty($id) ){
				return $this->buscaNome($nomeArray, $id);
			}
			return $this->buscaNomes($nomeArray);
		}
		return false;
	}
	
	/**
	 * Verifica se existe categorias no arquivo
	 * 
	 * @return bool
	 */
	private function existeDados($nomeArray){
		if( !empty($this->dadosArquivo[$nomeArray]) ){
			return true;
		}
		return false;
	}
	
	/**
	 * Percorre o array de categorias e busca um nome da categoria usando seu id
	 * 
	 * @return String|bool
	 */
	private function buscaNome($nomeArray, $id){
		foreach( $this->dadosArquivo[$nomeArray] as $cat ){
			if( $id == $cat['id'] && $cat['status'] == 1 ){
				return $cat['nome'];
			}
		}
		return false;
	}
	
	/**
	 * Percorre o array de categorias e busca um nome da categoria usando seu id
	 *
	 * @return String|bool
	 */
	private function buscaNomes($nomeArray){
		$nomes = array();
		foreach( $this->dadosArquivo[$nomeArray] as $cat ){
			$nomes[] = $cat['nome'];
		}
		return $nomes;
	}
	
	/**
	 * Busca as receitas que estao visiveis
	 *
	 * @return boolean|1
	 */
	function buscaTipos(){
		$this->abreArquivo();
		$this->leArquivo();
		
		$dados = array();
		if( $this->existeDados('tipo_receita') ){
			$dados = $this->dadosArquivo['tipo_receita'];
		}
		
		$this->fechaArquivo();
		return $dados;
	}
}