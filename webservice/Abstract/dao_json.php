<?php
/**
 * Forca os DAO que extenderem a terem todos o mesmo padrao
 *
 * @author maikysilva
 *
 */

abstract class DAOAbstractJson{
	private $arquivo;
	private $dadosArquivo;
	
	/**
	 * Abre o arquivo
	 */
	protected function abreArquivo(){
		if( is_file(JSON) ){
			//abre somente para leitura
			return $this->arquivo = fopen(JSON, "r");
		} else{
			//tenta criar como leitura e gravacao
			fopen(JSON, "a+");
		}
	}
	
	/**
	 * Le todos os dados de um arquivo
	 * 
	 * @return array()
	 */
	protected function leArquivo(){
		$json = file_get_contents(JSON);
		return json_decode($json, true);
	}
	
	/**
	 * Fecha o arquivo
	 */
	protected function fechaArquivo(){
		fclose($this->arquivo);
	}
}