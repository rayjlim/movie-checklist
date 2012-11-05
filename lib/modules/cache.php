<?php

class Cache extends Memcache
{
	function __construct()
	{
		require_once BASEDIR.'/_config/cache.php';
		foreach($config['cache'] as $key => $value) {
			$this->$key = $value;
		}

		$this->connected = $this->connect($this->host,$this->port);
	}

	function store($key,$value)
	{
		$data = array(
			'timestamp' => time(),
			'data' => $value
		);

		$this->set($key,$data);
		return true;
	}
}