<?php

define('BASEDIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
define('ENVIRONMENT','development');
require_once BASEDIR . '/lib/core/fundead.php';
Fundead::init();
Fundead::$module->load('View');


Fundead::get('/',function() {
	echo Fundead::$module->View->render('mainpage.html',array('testVar'=>'This is a test variable'),false);
});

Fundead::post('/movieinfo',function($movie_id) {
	echo 'fetching info for #'.$movie_id;
});

Fundead::post('/searchmovie',function($search) {
	echo "searching for '$search'";
});

Fundead::post('/newmovie',function($moviedata) {
	echo "addig movie";
	echo '<pre>';
	var_dump(json_decode($moviedata,true));
	echo '</pre>';
});

Fundead::run();
?>