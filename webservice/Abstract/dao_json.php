<?php
/**
 * Forca os DAO que extenderem a terem todos o mesmo padrao
 *
 * @author maikysilva
 *
 */

abstract class DAOAbstractJson{
	protected $arquivo;
	protected $dadosArquivo='';
	private   $tipoAbreArquivo='r';//por padrao so abre o arquivo pra leitura
	
	/**
	 * Abre o arquivo
	 */
	protected function abreArquivo(){
		if( is_file(JSON) ){
			//abre somente para leitura
			return $this->arquivo = fopen(JSON, $this->tipoAbreArquivo);
		}
		//tenta criar como leitura e gravacao
		return $this->arquivo = fopen(JSON, "w+");
	}
	
	/**
	 * Le todos os dados de um arquivo
	 * 
	 * @return array()
	 */
	protected function leArquivo(){
		$json = file_get_contents(JSON);
		$this->dadosArquivo = json_decode($json, true);
	}
	
	/**
	 * Salva o array passado no arquivo
	 * 
	 * @param array() $array Array a ser salvo
	 * @return bool
	 */
	protected function salvaArquivo($array){
		$this->fechaArquivo();
		$this->tipoAbreArquivo = 'w+';//apaga o que ja existe no arquivo e coloca o ponteiro no inicio
		$this->abreArquivo();
		
		$json = json_encode($array);
		try{
			fwrite($this->arquivo, $json);
			return 1;
		} catch (Exception $ex){
			return 0;
		}
	}
	
	/**
	 * Fecha o arquivo
	 */
	protected function fechaArquivo(){
		fclose($this->arquivo);
	}
}