<?php 
	
	/* 
		TODO:
		Sets up basic settings and object and include core files to be autoloaded
	*/

	// General Settins	
	define('APP_NAME', 'Aba');
	define('ROOT', substr(__DIR__, 0, strlen(__DIR__) - 5));// Used to include PHP code
	define("HTML_ROOT", "http://localhost/pis"); // Used to include front-end files


	// Database Settings
	define('DB_NAME', 'pis');
	define('DB_HOST', '127.0.0.1:8889');
	define('DB_USER', 'root');
	define('DB_PASS', 'root');

	define('MAX_CLOTHES', 6);


	// Error reporting settings		
	error_reporting(E_ALL);

	// Inlcuding autoload/* files
	require 'autoload/db.php';

	function initSession($opt = false){
		session_start();
		session_regenerate_id();
		if($opt === false){
			session_write_close();
		}
	}

	$colors = [
		[
			"name" => "Light",
			'hex' => '#bbb'
		],
		[
			"name" => "Blues",
			'hex' => '#6975ff'
		],
		[
			"name" => "Dark",
			'hex' => '#333'
		],
		[
			"name" => "Brights",
			'hex' => '#f95077'
		]
	];

	$clothes = [
		[
			'name' => 'Shirt',
			'upLow' => 1,
			'colors' => [0, 1, 2, 3]
		],
		[
			'name' => 'Jeans',
			'colors' => [0, 1, 2],
			'upLow' => 2
		],
		[
			'name' => 'Tops and T-Shirts',
			'colors' => [0, 1, 2, 3],
			'upLow' => 1
		],
		[
			'name' => 'Trousers',
			'colors' => [0, 1, 2],
			'upLow' => 2
		],
		[
			'name' => 'Shorts and Skirts',
			'colors' => [0, 1, 2, 3],
			'upLow' => 2
		],
		[
			'name' => 'Jackets and Blazers',
			'colors' => [0, 1, 2, 3],
			'upLow' => 1
		],

	];

?>