<?php

class Cache
{
	private static $instance;
	public static $cache;

	private function __construct()
	{
		if ( !isset($config['cache']) ) {
			require_once BASEDIR.'/_config/cache.php';
			foreach($config['cache'] as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	public function getInstance()
	{
		if ( !isset(self::$instance) ) {
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}
}