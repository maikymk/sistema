<?php
class ModelNewAccount {
	private $data = [];
	private $errors = [];
	
	public function __construct() { }

	/**
	 * Valida os dados do novo cadastro
	 * se estiver tudo certo ja salva no bd e na sessao
	 *
	 * @param array $data Dados a serem validados
	 * @return array|bool
	 */
	public function validateNewAccount($data) {
		// valida os campos de cadastro de uma nova conta
		$this->validateFields($data);
		
		$errors = $this->getErrors ();
		
		return ($errors) ? $errors : $this->createNewAccount ($data);
	}
	
	/**
	 * Valida os dados que o usuar passou no formulario de cadastro
	 *
	 * @param array() $dataUser
	 */
	public function validateFields($dataUser) {
		$this->verifyFieldFilled('nameNewAccount', $dataUser, 'nome');
		$this->verifyFieldFilled('secondNameNewAccount', $dataUser, 'sobrenome');
		$this->verifyFieldFilled('birthDateNewAccount', $dataUser, 'data de nascimento');
		$this->verifyFieldFilled('emailNewAccount', $dataUser, 'e-mail');
		$this->verifyFieldFilled('passwordNewAccount', $dataUser, 'senha');
	
		// removido validacao dos campos de senha
		//$this->verifyFieldFilled('senha2NewAccount', $dataUser, 'repita a senha');
	
		//se a validacao de campo preenchido e nao nulo deu certo
		if (empty($this->errors)) {
			//valida se o email que o usuario esta tentando cadastrar ja existe no BD. Email nao pode ser duplicado
			$this->verifyEmail($dataUser['emailNewAccount']);
			//valida a data de nascimento que o usuario passou, verifica se ele e maior de idade
			$this->validateData($dataUser['birthDateNewAccount']);
			//verifica se a senha e aconfirmacao de senha sao iguais
			//$this->comparePasswords($dataUser['passwordNewAccount'], $dataUser['senha2NewAccount']);
		}
	}
	
	/**
	 * Verifica se o campo foi setado e esta preenchido
	 *
	 * @param String $fieldName Nome do campo a ser verificado no array
	 * @param array $data Array com os campos
	 * @param String $fieldNameError Nome dos campo para que seja apresentado ao usuario se der algum erro
	 */
	private function verifyFieldFilled($fieldName, $dataUser, $fieldNameError) {
		//valida se foi setado o campo e ele nao esta vazio
		if (isset($dataUser[$fieldName]) && ! empty(trim($dataUser[$fieldName]))) {
			$this->data[] = htmlentities($dataUser[$fieldName]);
		} else {
			$this->errors[] = 'Preencha o campo: ' . $fieldNameError;
		}
	}
	
	/**
	 * Verifica se a data e uma data valida
	 *
	 * @param String $data Data a ser validada
	 */
	private function validateData($data) {
		$year = substr($data, 0, 4);
		$month = substr($data, 5, 2);
		$day = substr($data, 8, 2);
	
		if (! checkdate($month, $day, $year)) {
			$this->errors[] = 'Preencha a data corretamente';
		} elseif (($data > MINIMUM_AGE_NEW_ACCOUNT)) {
			$this->errors[] = 'A idade minima para cadastro &eacute; 18 anos';
		}
	}
	
	/**
	 * Verifica se o email passado ja existe no banco
	 *
	 * @param String $email Email a ser verificado
	 */
	private function verifyEmail($email) {
		$email = strtolower($email);
		$result = Query::sql("SELECT id FROM usuario WHERE email = ?", [$email]);
	
		if (! empty($result)) {
			$this->errorss[] = 'E-mail já existente.';
		}
	}
	
	/**
	 * Compara as duas senhas que o usuario digitou pra saber se esta iguais
	 *
	 * @param String $password
	 * @param String $password2
	 */
	private function comparePasswords($password, $password2) {
		if ($password == $password2) {
			return true;
		}
		$this->errors[] = 'As senhas precisam ser iguais';
		return false;
	}
	
	/**
	 * Cria uma nova conta para o usuario
	 *
	 * @param array() $data Dados que o usuario envio
	 * @return bool
	 */
	public function createNewAccount($dataUser) {
		$password = md5($dataUser['passwordNewAccount']);
	
		$array = [
					$dataUser['emailNewAccount'],
					$password,
					$dataUser['nameNewAccount'],
					$dataUser['secondNameNewAccount'],
					$dataUser['birthDateNewAccount']
				 ];
	
		$sql = "INSERT INTO usuario(email, senha, nome, ultimo_nome, data_nascimento) VALUES ( ?, ?, ?, ?, ? )";
	
		if (Query::sql($sql, $array) > 0) {
			Session::addSession(array('email' => $dataUser['emailNewAccount'], 'password' => $password));
			//salva o tempo de duracao da sessao
			Session::setSessionTime();
			return true;
		}
	}
	
	/**
	 * Retorna os erros encontrados
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * Retorna os dados que o usuario preencheu nos campos
	 */
	public function getData() {
		return $this->data;
	}
}