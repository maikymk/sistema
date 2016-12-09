<?php
/**
 * Classe que trabalha a sessao
 *
 * Faz o trabalho relacionado a sessao
 * Inicia, destroy, busca, salva, etc
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class Session {

    /**
     * Inicia a sessao
     */
    public static function sessionStart() {
        session_start();
    }

    /**
     * Verifica se a sessao foi iniciada e se ainda
     * tem tempo para estar ativa, se nao tiver retorna false,
     * se tiver incrementa o tempo da sessao do usuario
     *
     * @return boolean
     */
    public static function checkSessionTime() {
        //se nao existir sessao ou o usuario nao tiver mais tempo de login, retorna false
        if (!static::validSession()) {
            Session::destroySession();
            return false;
        }
        return true;
    }

    /**
     * Salva o tempo de duracao da sessao
     */
    public static function setSessionTime() {
        $time = strtotime('+'.CACHE_USER_EXPIRES.' minute');
        //salva o tempo que a sessao foi iniciada na sessao
        static::addSession(array('time' => $time));
    }

    /**
     * Adiciona sessoes.
     * A funcao recebe um array contendo um nome de chave que sera o nome da sessao e o valor para essa sessao
     *
     * @param array() $sessoes Array com nome da sessao e valor a ser setado na sessao
     */
    public static function addSession($sessions) {
        foreach ($sessions as $key => $session) {
            $_SESSION[$key] = $session;
        }
    }

    /**
     * Busca uma sessao
     *
     * @param String $session Session a ser verificada se essa existe
     * @return String|false Valor da sessao buscada, se ela nao existir retorna false
     */
    public static function findSession($session) {
        if (isset($_SESSION[$session])) {
            return $_SESSION[$session];
        }
        return false;
    }

    /**
     * Deleta uma sessao
     *
     * @param array() $name Nome da(s) sessao(oes) a ser(em) deletada(s)
     */
    public static function deleteSession($name = array()) {
        foreach ($name as $n) {
            unset($_SESSION[$n]);
        }
    }

    /**
     * Destroi toda a sessao
     */
    public static function destroySession() {
        //se tiver setado alguma sessao, destroi ela
        if (!empty($_SESSION)) {
            @session_destroy();
        }
    }
    
    /**
     * Valida se existe um tempo de sessão e ele não expirou
     * 
     * @return boolean
     */
    public static function validSession() {
    	return (isset($_SESSION['time']) && $_SESSION['time'] >= time());
    }
}