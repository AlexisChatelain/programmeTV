<?php	
require_once("db_file_TV.php");
if (isset($_POST["chaine"]) && isset($_POST["heure"]) && isset($_POST["titre"]) && isset($_POST["lien"]) && isset($_POST["image"])  && isset($_POST["genre"]) && isset($_POST["resume"])){
	$chaine = $_POST['chaine'];
	$nom =  htmlspecialchars(urldecode($_POST['nom']), ENT_QUOTES);
	$icone =  htmlspecialchars(urldecode($_POST['icone']), ENT_QUOTES);
	$heure =  htmlspecialchars(urldecode($_POST['heure']), ENT_QUOTES);
	$titre = htmlspecialchars(urldecode($_POST['titre']), ENT_QUOTES);
	$lien = htmlspecialchars(urldecode($_POST['lien']), ENT_QUOTES);
	$image = htmlspecialchars(urldecode($_POST['image']), ENT_QUOTES);
	$genre = htmlspecialchars(urldecode($_POST['genre']), ENT_QUOTES);
	$resume = htmlspecialchars(urldecode($_POST['resume']), ENT_QUOTES);
	
	if ($_POST["demain"]=='true')
		$heure="'".date('Y-m-d', strtotime('+1 day'))." ".substr($heure,0,2).":".substr($heure, -2).":00'";	
	else
		$heure="'".date("Y-m-d")." ".substr($heure,0,2).":".substr($heure, -2).":00'";
	if ($nom!=""){
		$nom="'".$nom."'";
	}else{
		$nom="null";
	}
	if ($icone!=""){
		$icone="'".$icone."'";
	}else{
		$icone="null";
	}
	if ($titre!=""){
		$titre="'".$titre."'";
	}else{
		$titre="null";
	}
	if ($lien!=""){
		$lien="'".$lien."'";
	}else{
		$lien="null";
	}
	if ($image!=""){
		$image="'".$image."'";
	}else{
		$image="null";
	}
	if ($genre!=""){
		$genre="'".$genre."'";
	}else{
		$genre="null";
	}
	if ($resume!=""){
		$resume="'".$resume."'";
	}else{
		$resume="null";
	}
	echo $db->query("INSERT INTO TV (chaine, nom, icone, heure, titre, lien, image, genre, resume) 
	VALUES ($chaine,$nom,$icone,$heure,$titre,$lien,$image,$genre,$resume)");
}
