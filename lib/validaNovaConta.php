<?php

/**
 * Classe que valida os dados do usuario que esta cadastrando no site
 *
 * Valida os dados do usuario que esta tentando cadastrar no sistema,
 * se tudo ocorrer bem, armazena os dados, se nao devolve um array com os erros
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */
class ValidaNovaConta {
    private $dados = array();
    private $erros = array();

    /**
     * Valida os dados que o usuar passou no formulario de cadastro
     * 
     * @param array() $dadosUser
     */
    public function validaCampos($dadosUser) {
        $this->verificaCampoPreenchido('nomeNovaConta', $dadosUser, 'nome');
        $this->verificaCampoPreenchido('sobrenomeNovaConta', $dadosUser, 'sobrenome');
        $this->verificaCampoPreenchido('dataNascimentoNovaConta', $dadosUser, 'data de nascimento');
        $this->verificaCampoPreenchido('emailNovaConta', $dadosUser, 'e-mail');
        $this->verificaCampoPreenchido('senhaNovaConta', $dadosUser, 'senha');
        $this->verificaCampoPreenchido('senha2NovaConta', $dadosUser, 'repita a senha');
        
        //se a validacao de campo preenchido e nao nulo deu certo
        if (empty($this->erros)) {
            //valida se o email que o usuario esta tentando cadastrar ja existe no BD. Email nao pode ser duplicado
            $this->verificaEmail($dadosUser['emailNovaConta']);
            //valida a data de nascimento que o usuario passou, verifica se ele e maior de idade
            $this->validaData($dadosUser['dataNascimentoNovaConta']);
            //verifica se a senha e aconfirmacao de senha sao iguais
            $this->comparaSenhas($dadosUser['senhaNovaConta'], $dadosUser['senha2NovaConta']);
        }
    }

    /**
     * Verifica se o campo foi setado e esta preenchido
     * 
     * @param String $nomeCampo Nome do campo a ser verificado no array
     * @param array $dados Array com os campos
     * @param String $nomeCampoErro Nome dos campo para que seja apresentado ao usuario se der algum erro
     */
    private function verificaCampoPreenchido($nomeCampo, $dadosUser, $nomeCampoErro) {
        //valida se foi setado o campo e ele nao esta vazio
        if (isset($dadosUser[$nomeCampo]) && ! empty(trim($dadosUser[$nomeCampo]))) {
            $this->dados[] = htmlentities($dadosUser[$nomeCampo]);
        } else {
            $this->erros[] = 'Preencha o campo: ' . $nomeCampoErro;
        }
    }

    /**
     * Verifica se a data e uma data valida
     * 
     * @param String $data Data a ser validada
     */
    private function validaData($data) {
        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, 2);
        $dia = substr($data, 8, 2);
        
        if (! checkdate($mes, $dia, $ano)) {
            $this->erros[] = 'Preencha a data corretamente';
        } elseif (($data > DATA_MINIMA_NOVA_CONTA)) {
            $this->erros[] = 'A idade minima para cadastro &eacute; 18 anos';
        }
    }

    /**
     * Verifica se o email passado ja existe no banco
     * 
     * @param String $email Email a ser verificado
     */
    private function verificaEmail($email) {
        $email = strtolower($email);
        $result = Query::sql("SELECT id FROM usuario WHERE email = ?", array(
            $email));
        
        if (! empty($result)) {
            $this->erros[] = 'E-mail j&aacute; existente.';
        }
    }

    /**
     * Compara as duas senhas que o usuario digitou pra saber se esta iguais
     * 
     * @param String $senha
     * @param String $senha2
     */
    private function comparaSenhas($senha, $senha2) {
        if ($senha == $senha2) {
            return true;
        }
        $this->erros[] = 'As senhas precisam ser iguais';
        return false;
    }

    /**
     * Cria uma nova conta para o usuario
     * 
     * @param array() $dados Dados que o usuario envio
     * @return int
     */
    public function criaNovaConta($dadosUser) {
        $senha = md5($dadosUser['senhaNovaConta']);
        
        $array = array(
            $dadosUser['emailNovaConta'], 
            $senha, 
            $dadosUser['nomeNovaConta'], 
            $dadosUser['sobrenomeNovaConta'], 
            $dadosUser['dataNascimentoNovaConta']);
        
        $sql = "INSERT INTO usuario(email, senha, nome, ultimo_nome, data_nascimento) VALUES ( ?, ?, ?, ?, ? )";
        
        if (Query::sql($sql, $array) > 0) {
            Sessao::adicionaSessao(array('email' => $dadosUser['emailNovaConta'], 'senha' => $senha));
            //salva o tempo de duracao da sessao
            Sessao::setTempoSessao();
            return 1;
        }
        return 0;
    }

    /**
     * Retorna os erros encontrados
     */
    public function getErros() {
        return $this->erros;
    }

    /**
     * Retorna os dados que o usuario preencheu nos campos
     */
    public function getDados() {
        return $this->dados;
    }
}