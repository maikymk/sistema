<?php
/**
 * Forca as views que implementarem a terem todos o mesmo padrao
 *
 * @author maikysilva
 *
 */

interface InterfaceView{

	/**
	 * Recebe o caminho de um arquivo e retorna o seu conteudo
	 *
	 * @param String $arq Arquivo para pegar o conteudo
	 * @return HTML Retorna o conteudo do arquivo
	 */
	function retornaTela($arq);
}