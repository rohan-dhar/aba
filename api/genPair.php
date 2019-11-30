<?php 
	require '../core/conf.php';
	require '../core/Clothes.php';


	$clothes = new Clothes();
	exit(json_encode($clothes->genPair()));
	

?>