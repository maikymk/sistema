<?php
/**
 * Classe que trabalha a sessao
 * 
 * Faz o trabalho relacionado a sessao
 * Inicia, destroy, busca, salva, etc
 * 
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class Sessao {

    /**
     * Inicia a sessao
     */
    public static function iniciaSessao() {
        session_start();
    }
    
    /**
     * Verifica se a sessao foi iniciada e se ainda 
     * tem tempo para estar ativa, se nao tiver retorna false,
     * se tiver incrementa o tempo da sessao do usuario
     * 
     * @return boolean
     */
    public static function verificaTempoSessao() {
        //se nao existir sessao ou o usuario nao tiver mais tempo de login, retorna false
        if (!isset($_SESSION['time']) || $_SESSION['time'] < time()) {
            Sessao::destroiSessao();
            return false;
        }
        return true;
    }

    /**
     * Salva o tempo de duracao da sessao
     */
    public static function setTempoSessao() {
        $tempo = strtotime('+'.CACHE_USER_EXPIRES.' minute');
        //salva o tempo que a sessao foi iniciada na sessao
        static::adicionaSessao(array('time' => $tempo));
    }

    /**
     * Adiciona sessoes.
     * A funcao recebe um array contendo um nome de chave que sera o nome da sessao e o valor para essa sessao
     * 
     * @param array() $sessoes Array com nome da sessao e valor a ser setado na sessao
     */
    public static function adicionaSessao($sessoes) {
        foreach ($sessoes as $key => $ar) {
            $_SESSION[$key] = $ar;
        }
    }

    /**
     * Busca uma sessao
     * 
     * @param String $sessao Sessao a ser verificada se essa existe
     * @return String|false Valor da sessao buscada, se ela nao existir retorna false
     */
    public static function buscaSessao($sessao) {
        if (isset($_SESSION[$sessao])) {
            return $_SESSION[$sessao];
        }
        return false;
    }

    /**
     * Deleta uma sessao
     * 
     * @param array() $nome Nome da(s) sessao(oes) a ser(em) deletada(s)
     */
    public static function deletaSessao($nome = array()) {
        foreach ($nome as $n) {
            unset($_SESSION[$n]);
        }
    }

    /**
     * Destroi toda a sessao
     */
    public static function destroiSessao() {
        //se tiver setado alguma sessao, destroi ela
        if (isset($_SESSION) && !empty($_SESSION) && is_array($_SESSION)) {
            @session_destroy();
        }
    }

    /**
     * Valida se o usuario que esta acessando tem uma sessao
     * 
     * @param String $user usuario que esta acessando
     * @param String $senha senha do usuario que esta acessando
     * @return boolean Se ja tiver sessao retorna true se não tiver retorna false
     */
    public static function validaSessao($user, $senha) {
        if ((isset($_SESSION['usuario']) && $_SESSION['usuario'] == $user) && (isset($_SESSION['senha']) && $_SESSION['senha'] == $senha)) {
            return true;
        }
        return false;
    }
}