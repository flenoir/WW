<html>
    <head>
    <link rel="stylesheet" href="css/style_ephemeride.css" type="text/css" charset="utf-8"/>
        <title>Google Weather API</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
 
 <!-- on place le php avant le header pour recuperer la variable de date -->
 
<?
//passage en format de date /heure en francais
setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');



// affichage de la date en français, ajouter ',time()+86400' après %Y" pour la date du lendemain ou ', strtotime("+1 day")'
//encodage en utf8 pour l'accent du mois d'aout
$day = utf8_encode(strftime("%A %d %B %Y", strtotime("+1 day")));

//recuperation des données
$URL = "http://xml.weather.com/weather/local/FRXX0068?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
?>
       
 
 
        
        
<div id="header" class="bkg">

<span class="datemeteo">
            <?= $day ?>
</span>
    
<h1>Ephéméride</h1>

</div> 
        
        
<div id="conteneur">  
    
<div id="fond_datemeteo"></div> <!-- fond de texte semi opaque , placé ici pour etre positionné plus facilement via la postion absolute et des margin negatifs -->        
    
    <div id="lever_soleil">
        
<?
//lever soleil
$sunr = $xml->xpath("/weather/loc/sunr");

//tronquation pour elever AM
$substrsunr = $sunr[0];
?>    
        		
          <!-- Tronquation lever soleil -->	
          <h4><?echo substr($substrsunr,0,5)?></h4>               
             
 </div>   



  <div id="coucher_soleil">
        
         		
          		                         
           <h4><?php
           //coucher soleil
                $suns = $xml->xpath("/weather/loc/suns");
                //Reformatage de la date pour mettre sur 24h
                $heure = $suns[0];
                echo date('H:i', strtotime($heure));
            ?></h4>
             
             
        
 </div>     
  
     <div id="phase_lune">
        
<?
//indication de la phase de lune
$moonicon = $xml->xpath("/weather/cc/moon/t");

?>    
        		
          <!-- affichage de la phase de lune, remplacement de la variable en nom de l'image -->		
           
             <img src="<?= 'http://7ltv.free.fr/img/' . $moonicon[0]. '.png' ?>" alt="weather"?>   
                                     
 </div>  
 
  
  
       
   <div id="saint">
        
<?
//extraction des jour et mois pour calcul des saints
$jour = strftime("%d", strtotime("+1 day"));
$mois = strftime("%m", strtotime("+1 day"));

//récupération du saint du jour
$URLsaint = "http://fetedujour.fr/api/xml-normal-$jour-$mois";
$saint = file_get_contents($URLsaint);
$xmlsaint = simplexml_load_string($saint);

//recuperation de la valeur de saint
$valeursaint = $xmlsaint->xpath("/holiday/name");

?>    
        		
				
				<!-- affichage du saint du jour -->		
            <h3>Nous fêtons : <?= $valeursaint[0];?></h3>
			
			
        
     
 </div>  
   
  
  
  
  
    
    </body>
</html>