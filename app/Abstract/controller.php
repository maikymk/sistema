<?php

/**
 * Forca os controllers que extenderem a terem todos o mesmo padrao
 *
 * @author maikysilva
 *
 */
abstract class AbstractAppController {

    /**
     * Verifica a parte do site que o usuario esta tentando acessar,
     * caso nao esteja tentando acessar nenhuma manda ele pra home do componente
     * 
     * @param array() $acesso Parte do site que o usuario esta tentando acessar
     * @param string $pagPadrao Pagina padrao que o usuario ira acessar caso de erro, ou se ele nao tiver solicitado um pag especifica
     * @return string
     */
    protected function verificaAcesso($acoes, $pagPadrao = 'home') {
        $acaoUser = (isset($_GET['ac']) ? htmlentities($_GET['ac']) : $pagPadrao);
        
        if (in_array($acaoUser, $acoes)) {
            $pagPadrao = $acaoUser;
        }
        
        return $pagPadrao;
    }
}