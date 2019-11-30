<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php if(@isset($title)){echo $title;}?></title>

<script type="text/javascript">
	window.allColors = <?php echo json_encode($colors); ?>;
	window.allClothes = <?php echo json_encode($clothes); ?>;
	window.maxClothes = <?php echo MAX_CLOTHES ?>;
</script>

<script type="text/javascript" src= <?php echo '"'.HTML_ROOT.'/js/jquery.min.js"' ?> ></script>
<script type="text/javascript" src= <?php echo '"'.HTML_ROOT.'/js/swal.min.js"' ?> ></script>
<script type="text/javascript" src= <?php echo '"'.HTML_ROOT.'/js/ui.js"' ?> ></script>

<link rel="stylesheet" type="text/css" href= <?php echo '"'.HTML_ROOT.'/css/swal.min.css"' ?>>
<link rel="stylesheet" type="text/css" href= <?php echo '"'.HTML_ROOT.'/css/ui.css"' ?>>


<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900|Montserrat:300,400,500,600,700,800|Nunito:300,400,600,700,800|Questrial&display=swap" rel="stylesheet">


<?php 
	if(@isset($styles)){
		$html = '';	
		foreach($styles as $s){
			$html .= '<link rel="stylesheet" type="text/css" href= "'.HTML_ROOT.'/css/'.$s.'">';
		}
		echo $html;
	}
	if(@isset($scripts)){
		$html = "";
		foreach($scripts as $s){
			$html .= '<script type="text/javascript" src= "'.HTML_ROOT.'/js/'.$s.'"></script>';
		}
		echo $html;
	}
?>