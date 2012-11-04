<?php

class View
{
	private $twig;

	function __construct()
	{
		$templates = BASEDIR . '/templates';
		$cache = BASEDIR . '/_cache';

		if ( !class_exists('Twig_Autoloader') ) {
			require_once BASEDIR . '/lib/third_party/twig/lib/Twig/Autoloader.php';
		}

		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem($templates);
		if ( !defined('ENVIRONMENT') || ENVIRONMENT == 'development' ) {
			$options = array(
				'cache' => false,
				'debug' => true
			);
		}
		else {
			$options = array(
				'cache' => $cache,
				'debug' => false
			);
		}
		$this->twig = new Twig_Environment($loader,$options);
	}

	function render($template,$data=array(),$standalone=true)
	{
		if ( !is_array($data) ) { $data = array(); }
		$data = $data + $this->baseconf();
		$twig =& $this->twig;
		$tpl = $twig->loadTemplate($template);
		if ( !$standalone ) {
			$header = $twig->loadTemplate('global/header.html');
			$footer = $twig->loadTemplate('global/footer.html');
		}

		$output = $header->render($data) . $tpl->render($data) . $footer->render($data);

		return $output;
	}

	function display($template,$data=array(),$standalone=true)
	{
		echo $this->render($template,$data,$standalone);
	}

	function baseconf()
	{
		return array(
			'basedir' => BASEDIR,
			'baseurl' => 'http://php.dev/movies/',
			'site_name' => 'Movie checklist'
		);
	}
}