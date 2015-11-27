<?php
/**
 * Forca os DAO que implementarem a terem todos o mesmo padrao
 *
 * @author maikysilva
 *
 */

interface DAOInterfaceJson{
	/**
	 * Abre o arquivo
	 */
	function abreArquivo();
	
	/**
	 * Le todos os dados de um arquivo
	 */
	function leArquivo();
	
	/**
	 * Fecha o arquivo
	 */
	function fechaArquivo();
}