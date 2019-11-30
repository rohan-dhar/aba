<?php 
	require '../core/conf.php';
	require '../core/Clothes.php';


	$clothes = new Clothes(true);
	exit(json_encode($clothes->removePair()));
	
?>