<?php

session_start('movies');
define('BASEDIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
define('ENVIRONMENT','development');
require_once BASEDIR . '/lib/core/fundead.php';
Fundead::init();
Fundead::$module->load('View');

if ( Fundead::$module->exists('Cache') ) Fundead::$module->load('Cache',true);

Fundead::get('/teszt',function() {
	Fundead::$module->load('Database');
	Fundead::$module->Database->login('alfi','hackme!9925');
});

Fundead::get('/',function() {
	echo Fundead::$module->View->render('mainpage.html',array('testVar'=>'This is a test variable'),false);
});

Fundead::post('/login',function($user=null,$pass=null){
	Fundead::$module->load('Database');
	$result = Fundead::$module->Database->login($user,$pass);
	if ( is_null($result) ) { echo 0; return false; }
	else {
		$_SESSION['movieshash'] = $result['hash'];
		echo 1;
		return true;
	}
});

Fundead::post('/status',function() {
	if ( !isset($_SESSION['movieshash']) ) { echo 0; return false; }

	Fundead::$module->load('Database');
	$result = Fundead::$module->Database->status($_SESSION['movieshash']);
	echo $result;
	return true;
});

Fundead::post('/movieinfo',function($movie_id) {
	echo 'fetching info for #'.$movie_id;
});

Fundead::post('/searchmovie',function($search,$page=1) {
	Fundead::$module->load('Rottentomatoes');
	echo Fundead::$module->Rottentomatoes->searchMovie($search,$page);
});

Fundead::post('/newmovie',function($moviedata) {
	echo "addig movie";
	echo '<pre>';
	var_dump(json_decode($moviedata,true));
	echo '</pre>';
});

Fundead::run();
?>