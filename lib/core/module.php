<?php

if (!defined('FUNDEAD'))
    die('No direct access');

class Module extends Fundead {

    function __construct() {
	
    }

    function load($name,$singleton=false) {
	$classname = ucwords(strtolower($name));
	$filename = BASEDIR . '/lib/modules/' . strtolower($name) . '.php';
	if (file_exists($filename) && is_readable($filename)) {
	    require_once $filename;
	    if ( !$singleton ) $this->$classname = new $classname();
	    else $this->$classname =& $classname::getInstance();
	    return true;
	}
	else
	    return false;
    }

    function exists($name)
    {
		$filename = BASEDIR . '/lib/modules/' . strtolower($name) . '.php';
		if (file_exists($filename) && is_readable($filename)) {
			return true;
		}
		else {
			return false;
		}
    }

    function loaded($name)
    {
    	$classname = ucwords(strtolower($name));
    	if ( isset($this->$classname) && is_object($this->$classname) ) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }

}