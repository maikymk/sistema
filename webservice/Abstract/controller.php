<?php

/**
 * Forca os controllers que extenderem a terem todos o mesmo padrao
 * 
 * @author maikysilva
 *
 */
abstract class AbstractWebserviceController {

    /**
     * Monta o nome da classe onde sera salvo os dados
     * 
     * @param String $class Nome da classe a ser validada
     * @return bool|String
     */
    protected function classeDados($classe) {
        $class = 'DAO' . ucfirst(strtolower(SALVA_DADOS)) . $classe;
        
        if (class_exists($class)) {
            return $class;
        }
        return false;
    }
}