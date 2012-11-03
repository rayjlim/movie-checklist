<?php

class Database extends Mongo
{
	function __construct(Array $options)
	{
		if ( !is_null($host) ) {
			$dsn = "mongodb://";
			if ( !is_null($user) ) {
				$dsn .= $user;
				if ( !is_null($pass) ) { $dsn .= ":$pass@"; }
			}
			$dsn .= $host;
			if ( !is_null($port) )
		}


	}
}