<?php

class Rottentomatoes
{
	function __construct()
	{
		require_once BASEDIR . '/_config/rottentomatoes.php';
		foreach($config['rottentomatoes'] as $key => $value) {
			$this->$key = $value;
		}
	}

	function searchMovie($query=null,$page=1)
	{
		if ( is_null($query) || strlen($query) <3 ) { return array(); }
		if ( !is_int($page) || $page < 1 ) { $page = 1; }
		
		$url = "http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey={$this->apikey}&q={$query}&page_limit={$this->movies_per_page}&page={$page}";
		$results = json_decode(file_get_contents($url),true);

		if ( $results['total'] == 0 ) { return array(); }
		
		$sanitized = array();
		$i = 0;
		foreach($results['movies'] as $movie) {
			$sanitized[$i]['id'] = $movie['id'];
			$sanitized[$i]['title'] = $movie['title'];
			$sanitized[$i]['year'] = $movie['year'];
			$sanitized[$i]['poster'] = $movie['posters']['detailed'];
			$castCount = count($movie['abridged_cast']);
			$sanitized[$i]['cast'] = array();
			if ($castCount > 3) { $castCount = 3; }
			for($j=0;$j<$castCount;$j++) {
				$sanitized[$i]['cast'][] = $movie['abridged_cast'][$j]['name'];
			}
			$i++;
		}

		return json_encode($sanitized);
	}
}