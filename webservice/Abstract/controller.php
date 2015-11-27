<?php
/**
 * Forca os controllers que implementarem a terem todos o mesmo padrao
 * 
 * @author maikysilva
 *
 */

abstract class AbstractWebserviceController{
	/**
	 * Monta o nome da classe onde sera salvo os dados
	 */
	protected function classeDados($classe){
		$class = 'DAO'.ucfirst(strtolower(SALVA_DADOS)).$classe;
		
		if( class_exists($class) ){
			return $class;
		}
		return false;
	}
}