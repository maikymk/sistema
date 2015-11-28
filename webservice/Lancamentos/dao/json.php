<?php
class DAOJsonLancamentos extends DAOAbstractJson implements DAOInterfaceLancamentos{
	private $erro = array();
	
	/**
	 * Busca as categorias no json
	 *
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarLancamentos($tipo=null){
		$this->abreArquivo();
		$this->leArquivo();
		
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
		if( $this->existeArrayTipoLancamento() ){
			return $this->dadosArquivo['tipo_lancamento'];
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
	function verificaLancamentoUsuario($data, $descricao){
		$this->abreArquivo();
		$this->leArquivo();
		
		$nok=0;
		if( $this->existeArrayUsuario() ){
			$idUsuario = Usuario::getId();
			
			foreach( $this->dadosArquivo['lancamentos'] as $dados ){
				if( $dados['usuario'] == $idUsuario && $dados['data'] == $data && strcmp($dados['descricao'], $descricao) == 0 ){
					$nok++;
				}
			}
		}
		
		if( $nok > 0 ){
			$this->erros[] = "Erro! Voc&ecirc; n&atilde;o pode lan&ccedil;ar duas descri&ccedil;&otilde;es iguais no mesmo dia.";
		}
		
		$this->fechaArquivo();
		return (($nok > 0) ? false : true);
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
		$result = array();
	
		//validando o tamanho maximo do campo de descricao
		if( $this->validaDado($dados['descLancamento'], 'Erro no campo descri&ccedil;&aatilde;o.') ){
			if( $this->validaDescricao($dados['descLancamento']) ){
				$result['descricao'] = $dados['descLancamento'];
			}
		}
	
		$result['valor'] = $this->validaDado($dados['valorLancamento'], 'Erro no campo valor.');
		$result['categoria'] = $this->validaDado($dados['categLancamento'], 'Erro no campo categoria .');
		$result['tipo'] = $this->validaDado($dados['tipoLancamento'], 'Erro no campo tipo.');
	
		//validando a data
		if( $this->validaDado($dados['dataLancamento'], 'Erro na data. Preencha corretamente.') ){
			$result['data'] = $this->validaData($dados['dataLancamento']);
		}
	
		if( empty($this->erros) ){//se nao tiver nenhum erro retorna um array com os dados validados
			if( $this->verificaLancamentoUsuario($result['data'], $result['descricao']) ){
				return $result;
			}
			return false;
		}
		return false;
	}
	
	/**
	 * Valida o tamanho maximo da descricao do lancamento
	 *
	 * @param String $desc
	 * @return bool
	 */
	private function validaDescricao($desc){
		if( strlen($desc) > TAM_MAX_DESC ){
			$this->erros[] = 'Erro no campo descri&ccedil;&atilde;o. O tamanho m&aacute;ximo &eacute; '.TAM_MAX_DESC.' caracteres.';
			return false;
		}
		return true;
	}
	
	/**
	 * Recebe uma data e valida ela
	 *
	 * @param String $dado data a ser validada
	 * @return String|bool
	 */
	private function validaData($data){
		$data1 = str_replace(array('-', '/', '\\'), '', $data);
	
		$dia = substr($data1, 0, 2);
		$mes = substr($data1, 2, 2);
		$ano = substr($data1, 4, 4);
	
		$data1 = $ano.'-'.$mes.'-'.$dia;
		$data2 = date('Y-m-d', strtotime($data1));
	
		if( $data1 == $data2 && date('Y-m-d', strtotime($data1)) ){
			return htmlentities(strip_tags($data2));
		}
	
		$this->erros[] = 'Erro no campo data. Preencha corretamente.';
		return false;
	}
	
	/**
	 * Valida o dado enviado
	 *
	 * @param String|numeric $dado Dado do campo a ser validado
	 * @param String $msgErro Mensagem caso ocorra erro na validacao
	 * @return String|bool
	 */
	private function validaDado($dado, $msgErro){
		if( isset($dado) && !empty(trim($dado)) ){
			return htmlentities(strip_tags($dado));
		}
		$this->erros[] = $msgErro;
		return false;
	}
	
	/**
	 * Retorna todos os erros
	 *
	 * @return array
	 */
	function getErros(){
		return $this->erros;
	}
	
	/**
	 * Cria um nova categoria
	 *
	 * @param array() $dados $dados a serem salvos
	 * @return int
	 */
	function adicionarLancamentos($dados){
		$this->abreArquivo();
		$this->leArquivo();
		
		$ok = 0;
		if( $this->existeArrayLancamentos() ){
			$idUsuario = Usuario::getId();
			
			$lastArray = end($this->dadosArquivo['lancamentos']);
			$lastId = $lastArray['id'] + 1;
			
			$valor = str_replace(array('.', ','), array('', '.'), $dados['valor']);
			$this->dadosArquivo['lancamentos'][] = array(
				'id' 		=> $lastId, 
				'descricao' => $dados['descricao'],
				'valor' 	=> $valor,
				'data' 		=> $dados['data'],
				'categoria' => $dados['categoria'],
				'tipo' 		=> $dados['tipo'],
				'usuario' 	=> $idUsuario
			);
			
			if( $this->salvaArquivo($this->dadosArquivo) ){
				$ok++;
			}
		}
		
		$this->fechaArquivo();
		return (($ok > 0) ? $lastId : '' );
	}
}