<?php
/**
 * Classe que valida os dados do usuario que esta tentando acessar o site
 *
 * Valida os dados do usuario que esta tentando logar no sistema, 
 * seja por formulario ou sessao. Verifica se ele existe no BD.
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class ValidaAcesso {
    private $acesso = false;

    public function __construct() {}

    /**
     * Valida o login, verifica se existe um $_POST com 
     * os dados do usuário se não existir, 
     * verifica se tem sessao e tenta logar pela sessao
     * 
     * @return String|boolean 
     * Retorna a variavel global $erro, 
     * se tiver erro ela retorna uma msg, 
     * se nao tiver ela retorna false
     */
    public function validaLogin() {
        //verifica o usuario que esta tentando logar
        if (isset($_POST['submitTelaLogin'])) {
            if ($this->validaLoginTelaLogin()) {
                Sessao::setTempoSessao();
                $this->acesso = true;
            }
        } else {
            //tenta validar o usuario se ele tiver sessao
            if ($this->validaLoginSessao()) {
                Sessao::setTempoSessao();
                $this->acesso = true;
            }
        }
        return $this->acesso;
    }

    /**
     * Valida o login do ususario pela tela de login, 
     * se o usuario conseguir logar, salva na sessao
     */
    private function validaLoginTelaLogin() {
        $email = htmlentities($_POST['emailTelaLogin']);
        $senha = htmlentities($_POST['passwordTelaLogin']);
        $senha = $this->encriptSenha($senha);
        
        if ($this->validaBd($email, $senha)) {
            Sessao::adicionaSessao(array('email' => $email, 'senha' => $senha));
            
            $this->acesso = true;
            return true; 
        } else {
            $this->acesso = 'Erro no usu&aacute;rio ou senha';
        }
        return false;
    }

    /**
     * Valida o login do ususario pela sessao dele
     */
    private function validaLoginSessao() {
        if (($email = Sessao::buscaSessao('email')) && ($senha = Sessao::buscaSessao('senha'))) {
            if ($this->validaBd($email, $senha)) {
                $this->acesso = true;
                return true;
            } else {
                $this->acesso = 'Erro! A sessao de login e senha est&aacute; diferente do BD';
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
        return Query::sql($sql, array($email, $senha));
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
}