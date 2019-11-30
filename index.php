<?php 
	require 'core/conf.php';
	require 'core/Clothes.php';

	$cloth = new Clothes();
	$userClothes = $cloth->getAll();

	$title = APP_NAME." | Home";		

	$styles = ['index.css'];
	$scripts = ['index.js'];

	$total = count($userClothes);
	$washNum = 0;
	$availNum = 0;
	$washHTML = '';
	$availHTML = '';
	$warnHTML = '';

	foreach ($userClothes as $c){
		$html = '';
		$c = explode('-', $c);
		
		$html .= '<div class="cloth">';
		$html .= '<img src="img/'.$c[0].','.$c[1].'.png">';
		$html .= '<div class="cloth-name">'.$clothes[(int)$c[0]]['name'].'</div>';
		$html .= '<div class="ui-tag cloth-color" style="background-color: '.$colors[(int)$c[1]]['hex'].';">'.$colors[(int)$c[1]]['name'].'</div>';					
		$html .= '</div>';

		if((int)$c[2] == 0){
			$availHTML .= $html;
			$availNum++;
		}else{
			$washHTML .= $html;
			$washNum++;			
		}
	}

	if($availNum < 1){
		$availHTML = '<div class="no-clothes">You have no clothes available</div>';
	}else if($availNum <= 2){
		$availHTML = '<div class="ui-complete-base"><div class="ui-complete-bar" style="width: '.($availNum/$total*500).'px">'.$availNum.' / '.$total.'</div></div>' . $availHTML . '<div id="low-clothes">You have very few clothes available! <br> Try and return clothes out for washing.</div>'; 
	}else{
		$availHTML = '<div class="ui-complete-base"><div class="ui-complete-bar" style="width: '.($availNum/$total*500).'px">'.$availNum.' / '.$total.'</div></div>' . $availHTML; 
	}

	if($washNum < 1){
		$washHTML = '<div class="no-clothes">No clothes are out for washing</div>';
	}else{
		$washHTML = '<div class="ui-complete-base"><div class="ui-complete-bar" style="width: '.($washNum/$total*500).'px">'.$washNum.' / '.$total.'</div></div>' . $washHTML; 
	}


?>
<!DOCTYPE html>
<html>
	<head>
		<?php require ROOT.'/struct/head.php'; ?>
	</head>
	<body>
		<div class="clothes-holder" id="avail-clothes-holder">
			<h2 class="clothes-holder-head">Available clothes</h2>
			<?php echo $availHTML; ?>
		</div>	
		<div class="clothes-holder" id="washing-clothes-holder">
			<h2 class="clothes-holder-head">Out for washing</h2>
			<?php echo $washHTML; ?>
		</div>	
		<div id="hide-all"></div>
	</body>
</html>