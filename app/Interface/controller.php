<?php

/**
 * Forca os controllers que implementarem a terem todos o mesmo padrao
 * 
 * @author maikysilva
 *
 */
interface InterfaceController {

    /**
     * Permite o proprio controller fazer suas requisicoes e funcionar da forma dele
     */
    function handle();

    /**
     * Retorna a tela que foi acessada
     * 
     * @return HTML Conteudo da tela
     */
    function mostraTela();
}