<?php

/**
 * Fundead engine main class
 */
class Fundead {

    private static $instance;
    private static $routes = array();
    public static $module;

    /**
     * It defines the FUNDEAD constant which is checked in other files for
     * preventing direct access, then inits the modules.
     */
    private function __construct() {
		define('FUNDEAD', true);
		self::_initModules();
    }

    /**
     * Fires up the engine.
     * @return Fundead instance of the engine
     */
    public static function init() {
		if (!isset(self::$instance)) {
		    $c = __CLASS__;
		    self::$instance = new $c();
		}

		return self::$instance;
    }

    /**
     * Registers a get route
     * @param  string $route the route to be registered
     * @param  function $func  the function to be called
     */
    public static function get($route, $func) {
		self::route('get', $route, $func);
    }

    /**
     * Registers a post route
     * @param  string $route the route to be registered
     * @param  function $func  the function to be called
     */
    public static function post($route, $func) {
		self::route('post', $route, $func);
    }

    /**
     * Low level function for registering routes.
     * @param  string $method request method, can be get or post.
     * @param  string $route  the route to be registered
     * @param  function $func   the function to be called
     */
    public static function route($method='get', $route, $func) {
		$search = array(
		    '#d',
		    '#s',
		    '/'
		);
		$replace = array(
		    '([0-9]+?)',
		    '([a-zA-Z0-9\-_+%]+?)',
		    '\/'
		);
		$regex = '/^' . str_replace($search, $replace, $route) . '$/';

		self::$routes[$regex] = array('method' => $method, 'func' => $func);
    }

    /**
     * Runs the engine, trying to match a registered route with the current REQUEST_URI
     */
    public static function run() {
		$uri = $_SERVER['REQUEST_URI'];
		$filename = 'index.php';
		if (strpos($uri, $filename) !== false) {
		    $uri = substr($uri, strpos($uri, $filename) + strlen($filename));
		} else {
		    $uri = '/';
		}
		if (strpos($uri, '?') !== false) {
		    $uri = substr($uri, 0, strpos($uri, '?'));
		}
		if (!$uri)
		    $uri = '/';
		$routes = array_keys(self::$routes);
		$funcshun = null;
		$i = 0;
		$count = count($routes);
		$matches = null;
		if ($count > 0) {
		    while ($i < $count && is_null($funcshun)) {
			if (preg_match_all($routes[$i], $uri, $matches))
			    $funcshun = self::$routes[$routes[$i]];
			$i++;
		    }

		    if (!is_null($funcshun)) {
			$rFunc = new ReflectionFunction($funcshun['func']);
			$required = $rFunc->getNumberOfParameters();
			$params = array();
			if ($required > 0) {
			    if ($funcshun['method'] == 'get') {
					array_shift($matches);
					if (count($matches) > 0) {
					    	foreach ($matches as $p) {
							$params[] = $p[0];
					    }
					}

					if (count($params) != $required)
					    throw new Exception('Not enough parameters given.');
				    }
					elseif ($funcshun['method'] == 'post') {
						$rParams = $rFunc->getParameters();
						$error = false;
						$max = count($rParams);
						$k = 0;
						if (count($_POST) < $max) {
						    throw new Exception('Not enough parameters given.');
						}
						while (!$error && $k < $max) {
						    $key = $rParams[$k]->name;
						    if (!in_array($key, array_keys($_POST)) || (!$rParams[$k]->isOptional() && strlen($_POST[$key]) == 0 ))
								$error[$key] = true;
						    else
								$params[] = $_POST[$key];
						    $k++;
						}
						if ($error) {
						    throw new Exception('Required parameters missing.');
						}
				    }
				}
				call_user_func_array($funcshun['func'], $params);
		    }
		    else
				throw new Exception('404');
		}
    }

    /**
     * Initializes the module subsystem
     */
    private static function _initModules() {
		if (!class_exists('Module'))
		    require_once BASEDIR . '/lib/core/module.php';
		self::$module = new Module();
    }

}

?>
