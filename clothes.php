<?php 
	require 'core/conf.php';
	require 'core/Clothes.php';

	$c = new Clothes();

	$userClothes = $c->getAll();
	
	$title = APP_NAME." | Add Clothes";		

	$styles = ['clothes.css'];
	$scripts = ['clothes.js']

?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript">
			window.userClothes = <?php echo(json_encode($userClothes)); ?>;
		</script>
		<?php require ROOT.'/struct/head.php'; ?>
	</head>
	<body>
		<div class="clothes-holder" id="clothes-holder-current"></div>

		<div class="clothes-holder" id="clothes-holder-washing"></div>


		<div class="clothes-holder" id="clothes-holder-add">
			<h2 class="clothes-holder-head">Add clothes<div class="clothes-holder-head-sep"></div></h2>			
		</div>
	</body>
</html>