<?php

/**
 *
 * Classe para adicionar css no cabecalho <head>
 *
 * @author maikysilva
 *
 */
class Css {
	private static $css = [];
	
	/**
	 * Salva o nome do css que sera adicionado na tag <head>
	 * 
	 * @param string $css
	 */
	public static function addCss($css) {
		try {
			$file = CSS . $css . '.css';
			
			if (is_file($file)) {
				if (!in_array($css, self::$css)) {
					self::$css[] = $file;
				}
			}
		} catch (Exception $ex) {
			throw new $ex;
		}
	}
	
	public static function getCss() {
		return self::$css;
	}
}