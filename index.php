<!DOCTYPE html>
<html lang="fr">
<head>
<title>Mon programme TV</title>
<meta charset="UTF-8" />
</head>
<style>
html{
max-width: 100%;
}
body{
background-color: #FEE9D8;
}

iframe{
zoom:200%;
}

h1 {
border: double #DEB887 2pt;
text-align : center;
color : red;
/*margin-left:10%;*/
}

h2{
text-align : center;
}

table{
border: ridge #A52A2A 3pt;
background-color: #DEB887;
}

th,td{
border: solid #A52A2A 1pt;
}

img{
margin-top:2pt;
}
</style>
<body>
<?php 


///////////////////////////////////

$duree_tableau  = 3 ;



//////////////////////////////////////

setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
echo "
<h1>Programme TV du ".strftime("%A %d %B de %Hh%M à ").str_replace(":","h",substr(date("Y-m-d H:i:s", strtotime('+ '.$duree_tableau.' hours')), 11,5))."</h1>
<div style='width:100%'>
<h3 style='float:left; margin-left:70px'>".substr(date("Y-m-d H:i:s"), 11,5)."</h3>
<h2 style='float:left; margin:0 auto; text-align:center; width:90%'>D'après <a href='https://tv-programme.com/en-ce-moment'>tv-programme.com </a></h2>
<br><h2 hidden>En raison d'un différend technique avec tv-programme.com, ce programme TV était indisponible pour une durée indéterminée.<br>
Des tests seront réalisés avant 8h le 16/11/2020, le programme TV pourrait peut-être de nouveau être disponible à cette date. Si ce n'est pas le cas, 
vous pouvez vous rediriger vers l'ancienne version (qui ne fonctionne pas 24h/24) en cliquant <a href='http://chatelain03.free.fr/ProgrammeTV.html'>ici</a>.</h2>
<h3 style='float:right'>".substr(date("Y-m-d H:i:s", strtotime('+ '.$duree_tableau.' hours')), 11,5)."</h3>
</div> 
<table style='width:100%'>";
require_once("db_file_TV.php");
$result=$db->query("SELECT * FROM TV ORDER BY chaine, heure");
$old=0;
while($grille=$result->fetch_object()){
	if(isset($precedent)){
		if ($precedent["heure"] < date("Y-m-d H:i:s", strtotime('+ '.$duree_tableau.' hours'))
		&& (($precedent["chaine"]==$grille->chaine && $precedent["heure"]<= date("Y-m-d H:i:s") && $grille->heure >= date("Y-m-d H:i:s"))
		|| $precedent["heure"]>= date("Y-m-d H:i:s"))){		
			if ($precedent["image"] != null)
				$image="<img src='".$precedent["image"]."' width=127 height=101>";
			else
				$image="";
			if ($precedent["heure"] < date("Y-m-d H:i:s") && $grille->heure > date("Y-m-d H:i:s") )
				$debut=date("Y-m-d H:i:s");
			else 
				$debut=$precedent["heure"];
			if ($grille->heure > date("Y-m-d H:i:s", strtotime('+ '.$duree_tableau.' hours')) )
				$fin= date("Y-m-d H:i:s", strtotime('+ '.$duree_tableau.' hours'));
			else {
				$fin=$grille->heure;				
				//echo substr($grille->heure, 11,5)."->".substr(date("Y-m-d H:i:s", strtotime('+ '.$duree_tableau.' hours')), 11,5)."c<br>";
			}				
			$pourcentage=(strtotime($fin)-strtotime($debut))*100/(3600*$duree_tableau);
			//echo intval($pourcentage)." ";
			//echo substr($debut, 11,5)."->".substr($fin, 11,5)."<br>";
			
			echo "<div style='float:left; width:".intval($pourcentage)."%' onmouseover='document.getElementById(".$precedent["id"].").hidden=false' 
			onmouseout='document.getElementById(".$precedent["id"].").hidden=true'><div style='float:left'><strong>".substr($precedent["heure"], 11,5)."</strong><em> ".$precedent["genre"]."</em><br>
			<a href='".$precedent["lien"]."'>".$precedent["titre"]."<br>".$image."</a></div><div style='float:left' id=".$precedent["id"]." hidden>".$precedent["resume"]."</div></div>
			";	
		}	
	}
	if ($old!=$grille->chaine){
		echo "</td></tr><tr style='height:146px'><th><img alt='".$grille->nom."' src='".$grille->icone."' height=60 width=60></th><td>";
	}
	$old=$grille->chaine;
	$precedent=array("id"=>$grille->id,"chaine"=>$grille->chaine,"nom"=>$grille->nom,"icone"=>$grille->icone,"heure"=>$grille->heure,"titre"=>$grille->titre,"lien"=>$grille->lien,"image"=>$grille->image,"genre"=>$grille->genre,"resume"=>$grille->resume);
}
/*
		if ($old!=$grille->chaine){
			echo "</tr><tr><th><img alt='".$grille->nom."' src='".$grille->icone."' height=60 width=60></th>";
		}
		if ($grille->image != null)
			$image="<img src='".$grille->image."' width=127 height=101></a>";
		else
			$image="";
		echo "<td><strong>".substr($grille->heure, 11,5)."</strong><em> ".$grille->genre."</em><br><a href='".$grille->lien."'>".$grille->titre."<br>".$image."</td>";
		$old=$grille->chaine;	
		*/
?>
</td>
</tr>
</table>
</body>
<script>
	function redirection(){
		 document.location.href="http://chatelain03.free.fr/ProgrammeTV.html"; 
	}
		//setTimeout(redirection, 10000); //millisecondes
</script>
</html>