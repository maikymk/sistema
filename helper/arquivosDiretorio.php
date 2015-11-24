<?php
class ArquivosDiretorio{
	private static $dir;
	private static $exts;
	private static $allFiles = array();
	
	/**
	 * Salva o diretorio atual para fazer busca dos arquivos nele
	 * 
	 * @param String $dir Caminho do diretorio
	 */
	static function setDir($dir){
		self::$dir = $dir;
	}
	
	/**
	 * Seta as extensaoes aceitas na variavel $exts. As extensoes precisam vir com o '.' antes do tipo. Ex: .php
	 * 
	 * @param array() $exts Extensoes de arquivos aceitas
	 */
	static function setExtensions($exts){
		if( !empty( $exts ) ){
			foreach( $exts as $ext ){
				self::$exts[] = strtolower($ext);
			}
		}
	}
	
	/**
	 * Busca todos os arquivos que existem dentro desse diretorio
	 */
	static function getFiles(){
		//pega todos os arquivos e pastas do diretório
		$files = scandir(self::$dir);
		$allFiles = null;
		
		if( empty(self::$exts) ){
			foreach ($files as $key => $file){
				$f = self::$dir.$file;//monta o caminho com o nome do arquivo
				
				if( self::validaArquivo($f) ){
					$allFiles[] = $file;
				}
			}
		} else{
			foreach ($files as $key => $file){
				$f = self::$dir.$file;//monta o caminho com o nome do arquivo
					
				if( self::validaArquivo($f) ){
					if( $fil = self::validaExtensao($file) ){
						$allFiles[] = $fil;
					}
				}
			}
		}
		
		return $allFiles;
	}
	
	/**
	 * Verifica se o diretorio passado existe
	 * 
	 * @param String $dir Nome do diretorio a ser verificado
	 */
	static function validaDiretorio($dir){
		if( is_dir($dir) ){
			return true;
		}
		return false;
	}
	
	/**
	 * Valida se o arquivo existe e se ele não é uma pasta oculta
	 * 
	 * @param String $file Caminho completo do arquivo
	 * @return boolean Arquivo valido ou invalido
	 */
	static function validaArquivo($file){
		if( is_file($file) && ( $file != '..' ) && ( $file != '.' ) ){
			return true;
		}
		return false;
	}
	
	/**
	 * Faz validacao se a extensao sendo passada e compativel com a(s) definida(s) pelo usuario
	 * 
	 * @param String $extFile Arquivo a ser verificada a extensao
	 * @return array() Todos os arquivos com extensao valida
	 */
	private static function validaExtensao($file){
		preg_match('@^[a-zA-Z]+\.([a-zA-z]+)@', $file, $extFile);
		
		if( !empty($extFile) )
			$extFile = '.'.strtolower($extFile[1]);
		
		if( in_array($extFile, self::$exts) ){
			return $file;
		} else{
			echo '<br><br>Extensao do arquivo incompativel com o aceito, arquivo: '.$file.'<br><br>';
			return false;
		}
	}
}