<?php
class ModelLogin {
	private $error = false;
	
	public function __construct() {}
	
	/**
	 * Valida o login, verifica se existe um $_POST com
	 * os dados do usuário se não existir,
	 * verifica se tem sessao e tenta logar pela sessao
	 *
	 * @return boolean
	 */
	public function validateLogin() {
		//verifica o usuario que esta tentando logar
		if (isset($_POST['submitLogin'])) {
			if ($this->validateLoginByPost()) {
				Session::setSessionTime();
				return true;
			}
		} else {
			//tenta validar o usuario se ele tiver sessao
			if ($this->validateLoginBySession()) {
				Session::setSessionTime();
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
	private function validateLoginByPost() {
		$email = htmlentities($_POST['emailLogin']);
		$password = htmlentities($_POST['passwordLogin']);
		$password = $this->encryptPassword($password);
	
		if ($this->validateDataBd($email, $password)) {
			Session::addSession(['email' => $email, 'password' => $password]);
	
			return true;
		} else {
			$this->error = 'Erro no usuário ou senha';
		}
		return false;
	}
	
	/**
	 * Valida o login do ususario pela sessao dele
	 *
	 * @return bool
	 */
	private function validateLoginBySession() {
		if (($email = Session::findSession('email')) && ($password = Session::findSession('password'))) {
			if ($this->validateDataBd($email, $password)) {
				 
				return true;
			} else {
				$this->error = 'Erro! A sessao de login e senha estão diferente do BD';
			}
		}
		return false;
	}
	
	/**
	 * Funcao para verificar se o usuario que esta
	 * tentando fazer login existe no BD
	 *
	 * @param String $login Login do usuario tentando acessar
	 * @param String $password Senha do usuario tentando acessar
	 * @return String|0
	 */
	private function validateDataBd($email, $password) {
		$sql = "SELECT email FROM usuario WHERE email=? AND senha=? AND status=1";
		return Query::sql($sql, [$email, $password]);
	}
	
	/**
	 * Funcao para encriptar senha e manter ela no mesmo padrao do BD
	 *
	 * @param String $password Senha a ser encriptada
	 * @return String
	 */
	private function encryptPassword($password) {
		return md5($password);
	}
	
	public function getErrors() {
		return $this->error;
	}
}