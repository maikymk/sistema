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
     * Retorna o nome do arquivo que irá ser renderizado
     *
     * @return String Nome do arquivo
     */
    function getNomeTela();
}