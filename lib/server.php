<?php
/**
 * Classe que trabalha com a constante $_SERVER
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class Server {
	public static function lastPage() {
		return ($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false;
	}
}