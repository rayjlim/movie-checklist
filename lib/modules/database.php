<?php

class Database
{
	private $db;

	function __construct()
	{
		if ( !isset($config['mongodb']) ) {
			require_once BASEDIR . '/_config/mongodb.php';
			foreach($config['mongodb'] as $key => $value) {
				$this->$key = $value;
			}
		}

		$this->connect();
	}

	function connect()
	{
		$dbs = 'mongodb://#u#pa#k#h#po';
		$search = array('#u','#pa','#k','#h','#po');
		$replace = array(
			!property_exists($this,'user') || is_null($this->user) ? '' : $this->user,
			!property_exists($this,'user') || is_null($this->user) || !property_exists($this,'pass') || is_null($this->pass) ? '' : ":{$this->pass}",
			!property_exists($this,'user') || is_null($this->user) ? '' : "@",
 			!property_exists($this,'host') || is_null($this->host) ? 'localhost' : $this->host,
			!property_exists($this,'port') || is_null($this->port) ? '' : ":{$this->port}"
		);

		$dbs = str_replace($search,$replace,$dbs);

		$this->db =& new Mongo($dbs);
	}

	function login($user,$pass)
	{
		$user = $this->db->selectDb('movies')->selectCollection('users')->findOne(array('name'=>$user,'pass'=>sha1($pass)));
		return $user;
	}

	function status($hash)
	{
		$user = $this->db->selectDb('movies')->selectCollection('users')->findOne(array('hash'=>$hash));
		return !is_null($user);
	}
}