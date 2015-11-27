<?php
class ModelFrame{
	function __construct(){}
	
	/**
	 * Valida os dados do novo cadastro, se estiver tudo certo ja salva no bd e na sessao
	 *
	 * @param array $dados Dados a serem validados
	 * @return array|bool
	 */
	function validaNovaConta($dados){
		$novaConta = new ValidaNovaConta;
		$novaConta->validaCampos($dados);
		if( $erros = $novaConta->getErros() ){
			return $erros;
		}
		//retorna 1 se incluir com sucesso, ou 0 se der erro
		return $novaConta->criaNovaConta($dados);
	}
	
	/**
	 * Verifica o erro passado
	 *
	 * @param String $erro Recebe um erro e valida ele pra apresentar sua tela. O erro padrao e 500 (Erro no servidor)
	 * @return String
	 */
	function validaErro($erro){
		$telaErro;
		
		switch( $erro ){
			case '400':
				$telaErro = '400';
				break;
			case '401':
				$telaErro = '401';
				break;
			case '403':
				$telaErro = '403';
				break;
			case '404':
				$telaErro = '404';
				break;
			case '500':
				$telaErro = '500';
				break;
		}
		
		return $telaErro;
	}
}