<?php
/**
 * Classe que trabalha os dados do usuario logado.
 *
 * Faz busca dos dados do usuario logado no BD.
 * Retorna dados do usuario logado, ex.: Nome, Id, etc.
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class Usuario {

    /**
     * busca o nome do usuario logado usando o email dele salvo na sessao
     * 
     * @return String|bool|null
     */
    public static function getNome() {
        if (! empty(Sessao::buscaSessao('email'))) {
            $nome = Query::sql("SELECT nome FROM usuario WHERE email=?", Sessao::buscaSessao('email'));
            
            if (isset($nome[0]['nome'])) {
                return $nome[0]['nome'];
            }
        }
        return null;
    }

    /**
     * busca o id do usuario logado usando o email dele salvo na sessao
     * 
     * @return int|bool|null
     */
    public static function getId() {
        if (! empty(Sessao::buscaSessao('email'))) {
            $id = Query::sql("SELECT id FROM usuario WHERE email=?", Sessao::buscaSessao('email'));
            return $id[0]['id'];
        }
        return null;
    }

    /**
     * busca o nome do usuario logado usando o id dele
     * 
     * @return String|bool|null
     */
    public static function getNomePorId($id = null) {
        if (! empty($id)) {
            $nome = Query::sql("SELECT nome FROM usuario WHERE id=?", $id);
            return $nome[0]['nome'];
        }
        return null;
    }
}