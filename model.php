<?php
class ModelFrame {

	/**
	 * Chama o método que cria constantes com as variáveis definidas no BD
	 */
	public function __construct() {
		//$this->geraConstantes();
	}

	/**
	 * Cria constantes com as variáveis definidas no BD
	 */
	private function geraConstantes() {
		$variaveis = Query::sql ("SELECT nome, valor FROM variavel WHERE status=1;");

		if (!empty($variaveis)) {
			foreach ($variaveis as $variavel) {
				define($variavel["nome"], $variavel["valor"]);
			}
		}
	}

	/**
	 * Verifica o erro passado.
	 * O erro padrao e 500 (Erro no servidor)
	 *
	 * @param String $erro Recebe um erro e valida ele pra apresentar sua tela.
	 * @return String
	 */
	public function validaErro($erro) {
		$telaErro;

		switch ($erro) {
			case '400' :
				$telaErro = '400';
				break;
			case '401' :
				$telaErro = '401';
				break;
			case '403' :
				$telaErro = '403';
				break;
			case '404' :
				$telaErro = '404';
				break;
			default :
				$telaErro = '500';
				break;
		}

		return $telaErro;
	}
}