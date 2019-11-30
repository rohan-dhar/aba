<?php 
	require '../core/conf.php';
	require '../core/Clothes.php';


	if(!@isset($_POST["type"]) || !@isset($_POST["color"])){
		exit(json_encode([false, "No clothes or color selected", "NO_ARG"]));
	}

	$clothes = new Clothes();

	exit(json_encode($clothes->manualRemove($_POST["type"], $_POST["color"])));

?>