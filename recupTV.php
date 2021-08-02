<?php 
# le serveur uniquement peut charger les programmes du lendemain entre 0h et 2h seulement 
#(il faut donc prévoir une tâche planifiée sur le serveur) 
if (!($_SERVER['REMOTE_ADDR']=='ip_locale' && date("H")<2 )){
	echo "Accès refusé";
}else{
	if (isset($_POST["parametre"])){
		if ($_POST["parametre"]==2 || $_POST["parametre"]==0){		
			$creneau=intval($_POST["parametre"])+2;
			$adresse = "https://tv-programme.com/demain/".$_POST["parametre"]."h-".$creneau."h/";
			if ($_POST["parametre"]==2)
				$parametre=25;
			else 
				$parametre=2;
			
		}else{		
			$creneau=intval($_POST["parametre"])+2;
			if ($creneau==24)
				$parametre=0;
			else
				$parametre=$creneau;
			$adresse = "https://tv-programme.com/aujourd-hui/".$_POST["parametre"]."h-".$creneau."h/";
		}
	}else{
		$parametre=6;
		$adresse = "https://tv-programme.com/aujourd-hui/04h-06h/";
	}
	echo $adresse;
	echo "<form id='form' method='post' action='recupTV.php' > <input type='hidden' id='parametre' name='parametre' value=".$parametre." /></form>";	
	
	$page = file_get_contents($adresse);
	$pos = stripos($page, "<table class=\"lazygrille\""); // position du nom dans la page
	$fin= substr($page, $pos);	// chaine de toute la fin de la page à partir du début du tableau
	$pos1 = strpos($fin, "</table>"); // position du 1er </table> à partir du nom
	echo substr($page, $pos,$pos1+8);
	
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
container=document.getElementsByTagName("tbody");
container=container[0].getElementsByTagName("tr");
chaine=0;
var ladate=new Date();
for (j=0;j < container.length;j++){
	//if (j!=0 ){ //&& !(j==1 && ladate.getHours()< document.getElementById('parametre').value && ladate.getHours() >= document.getElementById('parametre').value-2 ) 
		var td = container[j].childNodes;
		for (i=0;i < td.length;i++){
			if (i % 2 == 0){
				chaine+=1;
				nom=td[i].getElementsByTagName("img")[0].alt;
				icone=td[i].getElementsByTagName("img")[0].src;
			}else{						
				var div1 = td[i].childNodes;
				var div = div1[0].childNodes;
				for (k=0;k < div.length;k++){
					resume=div[k].getAttribute("data-content");
					deb=resume.indexOf("<br/>");
					fin=resume.lastIndexOf("<br/>");
					resume=resume.substring(deb+5, fin);
					a=div[k].getElementsByTagName("a");
					if (a.length==2){
						if (a[0].getElementsByTagName("img")[0].src=="")
							image=a[0].getElementsByTagName("img")[0].getAttribute("data-original");
						else 
							image=a[0].getElementsByTagName("img")[0].src;
						lien=a[1].href;
						titre=a[1].getElementsByTagName("strong")[0].innerText;
					}else{					
						image="";
						lien=a[0].href;
						titre=a[0].innerText;
					}					
					heure=div[k].getElementsByTagName("small")[0].innerText;
					if (heure.indexOf('...')!=-1)
							heure=heure.replace('...','');	
					if (typeof div[k].getElementsByTagName("span")[0]!= "undefined")
						genre=div[k].getElementsByTagName("span")[0].innerText;
					else 
						genre="";
					if (document.getElementById('parametre').value==25 || document.getElementById('parametre').value==2)
						demain='true';
					else
						demain='false';					
					var data = 'chaine=' + chaine + '&nom=' + encodeURIComponent(nom) + '&icone=' + encodeURIComponent(icone) +
							 '&heure=' + encodeURIComponent(heure) + '&titre=' + encodeURIComponent(titre) +
							 '&lien=' + encodeURIComponent(lien) + '&image=' + encodeURIComponent(image) +
							 '&genre=' + encodeURIComponent(genre) + '&resume=' + encodeURIComponent(resume)+ "&demain=" + demain ;			
					$.ajax({
					  type: "POST",
					  url: "saveTV.php",
					  data: data,
					  success: function(msg) {
							if (msg != true) 
								console.log(msg);
						}
					})
				}
			}		
		}
	//}	
}


function redirection(){
	if (document.getElementById('parametre').value!=25)
		document.getElementById('form').submit();
	else
		window.location.href = "testTV.php";
}
setTimeout(redirection, 5000); //millisecondes
</script>

