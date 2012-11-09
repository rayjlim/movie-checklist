<?php

session_start('movies');
define('BASEDIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
define('ENVIRONMENT','development');
require_once BASEDIR . '/lib/core/fundead.php';
Fundead::init();
Fundead::$module->load('View');

if ( Fundead::$module->exists('Cache') && defined('ENVIRONMENT') && ENVIRONMENT == 'production' ) Fundead::$module->load('Cache');

Fundead::get('/teszt',function() {
	//Fundead::$module->Cache->set()
	Fundead::$module->load('Rottentomatoes');
	$results = Fundead::$module->Rottentomatoes->getMovieInfo(770782775);
	echo Fundead::$module->View->render('rottentomatoes/movie_details.html',array('movie' => $results));
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
	Fundead::$module->load('Rottentomatoes');
	$result = Fundead::$module->Rottentomatoes->getMovieInfo($movie_id);

});

Fundead::post('/searchmovie',function($search,$page=1) {
	Fundead::$module->load('Rottentomatoes');
	$results = Fundead::$module->Rottentomatoes->searchMovie($search,$page);
	echo Fundead::$module->View->render('rottentomatoes/movie_search.html',array('results' => $results));
});

Fundead::post('/newmovie',function($moviedata) {
	echo "addig movie";
	echo '<pre>';
	var_dump(json_decode($moviedata,true));
	echo '</pre>';
});

Fundead::run();
?>