<?php
class ModelLogin {
	private $erro = false;
	
	public function __construct() {}
	
	/**
	 * Valida o login, verifica se existe um $_POST com
	 * os dados do usuário se não existir,
	 * verifica se tem sessao e tenta logar pela sessao
	 *
	 * @return boolean
	 */
	public function validaLogin() {
		//verifica o usuario que esta tentando logar
		if (isset($_POST['submitTelaLogin'])) {
			if ($this->validaLoginTelaLogin()) {
				Sessao::setTempoSessao();
				return true;
			}
		} else {
			//tenta validar o usuario se ele tiver sessao
			if ($this->validaLoginSessao()) {
				Sessao::setTempoSessao();
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Valida o login do ususario pela tela de login,
	 * se o usuario conseguir logar, salva na sessao
	 *
	 * @return bool
	 */
	private function validaLoginTelaLogin() {
		$email = htmlentities($_POST['emailTelaLogin']);
		$senha = htmlentities($_POST['passwordTelaLogin']);
		$senha = $this->encriptSenha($senha);
	
		if ($this->validaBd($email, $senha)) {
			Sessao::adicionaSessao(['email' => $email, 'senha' => $senha]);
	
			return true;
		} else {
			$this->erro = 'Erro no usuário ou senha';
		}
		return false;
	}
	
	/**
	 * Valida o login do ususario pela sessao dele
	 *
	 * @return bool
	 */
	private function validaLoginSessao() {
		if (($email = Sessao::buscaSessao('email')) && ($senha = Sessao::buscaSessao('senha'))) {
			if ($this->validaBd($email, $senha)) {
				 
				return true;
			} else {
				$this->erro = 'Erro! A sessao de login e senha estão diferente do BD';
			}
		}
		return false;
	}
	
	/**
	 * Funcao para verificar se o usuario que esta
	 * tentando fazer login existe no BD
	 *
	 * @param String $login Login do usuario tentando acessar
	 * @param String $senha Senha do usuario tentando acessar
	 * @return String|0
	 */
	private function validaBd($email, $senha) {
		$sql = "SELECT email FROM usuario WHERE email=? AND senha=? AND status=1";
		return Query::sql($sql, [$email, $senha]);
	}
	
	/**
	 * Funcao para encriptar senha e manter ela no mesmo padrao do BD
	 *
	 * @param String $senha Senha a ser encriptada
	 * @return String
	 */
	private function encriptSenha($senha) {
		return md5($senha);
	}
	
	public function getErro() {
		return $this->erro;
	}
}