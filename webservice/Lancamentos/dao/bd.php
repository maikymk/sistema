<?php
class DAOBdLancamentos implements DAOInterfaceLancamentos{
	private $erro = array();
	
	/**
	 * Busca as categorias no BD
	 *
	 * @param int $id Id de uma categoria especifica, se vazio traz todas
	 * @return array
	 */
	function visualizarLancamentos($tipo=null){
		$sql = "SELECT l.id, l.descricao, l.valor, l.data, c.nome categoria, r.nome tipo, u.nome usuario FROM lancamento l, categoria c, usuario u, tipo_lancamento r WHERE c.status=1 AND l.categoria=c.id AND l.usuario=u.id AND l.tipo=r.id";
		if( !empty($tipo) ){
			$sql .= "AND l.tipo='".$tipo."'";
		}
	
		if( $dados = Query::query($sql, $tipo) ){
			foreach( $dados as $key=>$dado ){
				$dados[$key]['data'] = date('d/m/Y', strtotime($dado['data']));
				$dados[$key]['valor'] = number_format($dado['valor'], 2, ",",".");
			}
			return $dados;
		}
		return array();
	}
	
	/**
	 * Retorna as categorias com status de visivel
	 *
	 * @return array|boolean|1
	 */
	function getCategorias(){
		$sql = "SELECT nome, id FROM categoria WHERE status=1 ORDER BY nome ASC";
		return Query::query($sql);
	}
	
	/**
	 * Retorna as receitas com status de visivel
	 *
	 * @return array|boolean|1
	 */
	function getReceitas(){
		$sql = "SELECT nome, id FROM tipo_lancamento WHERE status=1 ORDER BY nome ASC";
		return Query::query($sql);
	}
	
	/**
	 * Verifica se o usuario ja fez um lancamento com a mesma descricao nessa data
	 * 
	 * @param String $data Data a verificar a descricao
	 * @param String $descricao Descricao a ser verificada juntamente com a data
	 * @return boolean
	 */
	function verificaLancamentoUsuario($data, $descricao){
		$idUsuario = Usuario::getId();
		$sql = "SELECT data FROM lancamento WHERE data=? AND descricao=? AND usuario=?";
		$result = Query::query($sql, array($data, $descricao, $idUsuario));
		
		if( empty($result) ){
			return true;
		}
		$this->erros[] = "Erro! Voc&ecirc; n&atilde;o pode lan&ccedil;ar duas descri&ccedil;&otilde;es iguais no mesmo dia.";
		return false;
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
		$idUsuario = Usuario::getId();
	
		$valor = str_replace(array('.', ','), array('', '.'), $dados['valor']);
		$values = array(
			$dados['descricao'],
			$valor,
			$dados['data'],
			$dados['categoria'],
			$dados['tipo'],
			$idUsuario
		);
	
		$sql = "INSERT INTO lancamento(descricao, valor, data, categoria, tipo, usuario) VALUES (?, ?, ?, ?, ?, ?)";
	
		if ( $id = Query::query($sql, $values) ){
			return $id;
		}
		return 0;
	}
}