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
		
		$caching = false;
		if ( Fundead::$module->loaded('Cache') ) {
			$caching = true;
			$cache =& Fundead::$module->Cache;
			$query_exists = $cache->get('query_'.$query.'_'.$page);
		}

		
		if ( !$caching || ($caching && !$query_exists) ) {
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

			if ( $caching ) {
				$cache->store('query_'.$query.'_'.$page,$sanitized);
			}
		}
		else { $sanitized = $query_exists['data']; }

		return $sanitized;
	}

	function getMovieInfo($movieId=null)
	{
		if ( is_null($movieId) || !preg_match('/^[\d]+$/',$movieId) ) { return array(); }

		$caching = false;
		if ( Fundead::$module->loaded('Cache') ) {
			$caching = true;
			$cache =& Fundead::$module->Cache;
			$movie_exists = $cache->get('movie_'.$movieId);
		}

		if ( !$caching || ($caching && !$movie_exists) ) {
			$url = "http://api.rottentomatoes.com/api/public/v1.0/movies/{$movieId}.json?apikey={$this->apikey}";
			$result = json_decode(@file_get_contents($url),true);

			if ( is_null($result) ) { return array(); }
			$sanitized = array(
				'id' => $result['id'],
				'title' => $result['title'],
				'year' => $result['year'],
				'genres' => $result['genres'],
				'runtime' => $result['runtime'],
				'ratings' => array(
					'critics_score' => $result['ratings']['critics_score'],
					'audience_score' => $result['ratings']['audience_score']
				),
				'poster' => $result['posters']['original'],
				'cast' => $result['abridged_cast'],
				'directors' => $result['abridged_directors'],
				'studio' => $result['studio'],
				'link' => $result['links']['alternate']
			);

			if ( $caching ) { $cache->store('movie_'.$movieId,$sanitized); }		
		}
		else { $sanitized = $movie_exists['data']; }


		echo '<pre>';
		var_dump($sanitized);
	}
}