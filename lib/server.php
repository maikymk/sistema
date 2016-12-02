<?php

class Server {
	public static function lastPage() {
		return ($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false;
	}
}