<?php

if (!defined('FUNDEAD'))
    die('No direct access');

class Module extends Fundead {

    function __construct() {
	
    }

    function load($name) {
	$classname = ucwords(strtolower($name));
	$filename = BASEDIR . '/lib/modules/' . strtolower($name) . '.php';
	if (file_exists($filename) && is_readable($filename)) {
	    require_once $filename;
	    $this->$classname = new $classname();
	    return true;
	}
	else
	    return false;
    }

}