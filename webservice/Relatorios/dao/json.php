<?php
class DAOJsonRelatorios extends DAOAbstractJson implements DAOInterfaceRelatorios {
	private $dadosLancamento = array();
	/**
	 * Busca todos os dados de lancamento e monta um relatorio por categorias
	 *
	 * @param String $tipo Tipo da receita a ser exibida
	 * @return $array
	 */
	function relatoriosPorCategoria($tipo=null){
		$this->abreArquivo();
		$this->leArquivo();
		
		if( $this->existeDados('lancamentos') ){
			if( !empty($tipo) ) {
				foreach( $this->dadosArquivo['lancamentos'] as $key=>$dado ){				
					if( $tipo == $dado['tipo'] ){
						$this->setDadosLancamento($dado);
					}
				}
			} else{
				foreach( $this->dadosArquivo['lancamentos'] as $key=>$dado ){
					$this->setDadosLancamento($dado);
				}
			}
		}
		
		$this->fechaArquivo();
		
		return $this->dadosLancamento;
	}
	
	/**
	 * Seta os dados dos lancamentos
	 * 
	 * @param array() $dado Dados a serem setados
	 */
	private function setDadosLancamento($dado){
		if( ($categoria = $this->getNome('categoria', $dado['categoria'])) && ($nomeTipo = $this->getNome('tipo_lancamento', $dado['tipo'])) ){
			$this->dadosLancamento[$categoria][] = array(
				'id' => $dado['id'],
				'descricao' => $dado['descricao'],
				'valor' => number_format($dado['valor'], 2, ",","."),
				'data' => date('d/m/Y', strtotime($dado['data'])),
				'tipo' => $nomeTipo,
				'usuario' => Usuario::getNomePorId($dado['usuario']),
			);
		}
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
		if( $this->existeDados('tipo_lancamento') ){
			$dados = $this->dadosArquivo['tipo_lancamento'];
		}
		
		$this->fechaArquivo();
		return $dados;
	}
}