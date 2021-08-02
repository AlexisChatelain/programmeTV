
<?php
function heures($heure){
	for ($i=0;$i<24;$i+=2){
		if ($i<10)
			$chaine="0".strval($i).":00:00";
		else
			$chaine=strval($i).":00:00";
		if (strpos($heure, $chaine)!=false)
			return 1;
	}
	return 0;	
}

require_once("db_file_TV.php");
$ancienne_requete="
SELECT c.id, c.chaine as chaine1, c.nom as nom1, c.icone as icone1, c.heure as heure1, c.titre as titre1, c.lien as lien1, c.image as image1, c.genre as genre1, c.resume as resume1,
	   e.id as id2, e.chaine as chaine2, e.nom as nom2, e.icone as icone2, e.heure as heure2, e.titre as titre2, e.lien as lien2, e.image as image2, e.genre as genre2, e.resume as resume2
FROM TV e, (SELECT * FROM TV WHERE chaine=1) c WHERE 
e.chaine=c.chaine AND
e.id!=c.id AND TIMEDIFF(e.heure, c.heure) <= '03:00:00' AND TIMEDIFF(e.heure, c.heure) >= '-03:00:00' AND
(
(e.titre=c.titre AND e.titre!='...' AND c.titre !='...' AND e.titre IS NOT null AND c.titre IS NOT null ) OR
(e.lien=c.lien AND e.lien IS NOT null AND c.lien IS NOT null ) OR
(e.image=c.image AND e.image IS NOT null AND c.image IS NOT null ) OR
(e.resume=c.resume AND e.resume IS NOT null AND c.resume IS NOT null )
)  
ORDER BY chaine1 ASC, heure1 ASC";
$debut=time();
//echo "<table hidden=true>";
for ($item=1;$item<=112;$item++){
	$requete="
	SELECT e.id as id2, e.chaine as chaine2, e.nom as nom2, e.icone as icone2, e.heure as heure2, e.titre as titre2, e.lien as lien2, e.image as image2, e.genre as genre2, e.resume as resume2,
		   c.id, c.chaine as chaine1, c.nom as nom1, c.icone as icone1, c.heure as heure1, c.titre as titre1, c.lien as lien1, c.image as image1, c.genre as genre1, c.resume as resume1
	FROM TV e, (SELECT * FROM TV WHERE chaine=".$item.") c WHERE 
	e.chaine=c.chaine AND
	e.id!=c.id AND TIMEDIFF(e.heure, c.heure) <= '03:00:00' AND TIMEDIFF(e.heure, c.heure) >= '-03:00:00' AND
	(
	(e.titre=c.titre AND e.titre!='...' AND c.titre !='...' AND e.titre IS NOT null AND c.titre IS NOT null ) OR
	(e.lien=c.lien AND e.lien IS NOT null AND c.lien IS NOT null ) OR
	(e.image=c.image AND e.image IS NOT null AND c.image IS NOT null ) OR
	(e.resume=c.resume AND e.resume IS NOT null AND c.resume IS NOT null )
	)  
	ORDER BY chaine1 ASC, heure1 ASC";
	$result=$db->query($requete);
	while($grille=$result->fetch_array()){
		if (heures($grille[4])==1){
				$db->query("UPDATE TV SET heure='".$grille[14]."' where id=".$grille[0]);
				if ($grille[15]!="..." and $grille[5]=="...")
					$db->query("UPDATE TV SET titre='".$grille[15]."' where id=".$grille[0]);					
				if ($grille[16]!="..." and $grille[6]=="...")
					$db->query("UPDATE TV SET lien='".$grille[16]."' where id=".$grille[0]);					
				if ($grille[17]!="..." and $grille[7]=="...")
					$db->query("UPDATE TV SET image='".$grille[17]."' where id=".$grille[0]);
				if ($grille[18]!="..." and $grille[8]=="...")
					$db->query("UPDATE TV SET genre='".$grille[18]."' where id=".$grille[0]);
				if ($grille[19]!="..." and $grille[9]=="...")
					$db->query("UPDATE TV SET resume='".$grille[19]."' where id=".$grille[0]);
				$db->query("DELETE FROM TV where id=".$grille[10]);
			/*echo "<tr>";
			for ($i=0;$i<10;$i++){		
				echo "<td>".$grille[$i]."</td>";			
			}
			echo "</tr>";
			echo "<tr>";
			for ($i=0;$i<10;$i++){					
				echo "<td style='color:red;'>".$grille[$i+10]."</td>";			
			}			
			echo "</tr>";*/
		}
	}
}
//echo "</table>";
$fin=time();
//echo "Fin".$fin."<br>";
$duree=$fin-$debut;
echo "Durée : ".$duree." secondes<br>";
//echo "<script>table_game.hidden=true;</script>";
?>
