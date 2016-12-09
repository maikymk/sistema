<?php
/**
 * Classe que trabalha os dados do usuario logado.
 *
 * Faz busca dos dados do usuario logado no BD.
 * Retorna dados do usuario logado, ex.: Nome, Id, etc.
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class User {

    /**
     * busca o nome do usuario logado usando o email dele salvo na sessao
     * 
     * @return String|bool|null
     */
    public static function getName() {
        if (! empty(Session::findSession('email'))) {
            $name = Query::sql("SELECT nome FROM usuario WHERE email=?", Session::findSession('email'));
            
            if (isset($name[0]['nome'])) {
                return $name[0]['nome'];
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
        if (! empty(Session::findSession('email'))) {
            $id = Query::sql("SELECT id FROM usuario WHERE email=?", Session::findSession('email'));
            return $id[0]['id'];
        }
        return null;
    }

    /**
     * busca o nome do usuario logado usando o id dele
     * 
     * @return String|bool|null
     */
    public static function getNameById($id = null) {
        if (! empty($id)) {
            $name = Query::sql("SELECT nome FROM usuario WHERE id=?", $id);
            return $name[0]['nome'];
        }
        return null;
    }
}