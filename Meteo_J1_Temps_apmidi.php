<html>
    <head>
    <link rel="stylesheet" href="css/style.css" type="text/css" charset="utf-8"/>
        <title>Google Weather API</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
 
 <!-- on place le php avant le header pour recuperer la variable de date -->
 
<?
//Montpellier

$URL = "http://www.google.com/ig/api?weather=Montpellier,Languedoc&hl=fr";
$dataInISO = file_get_contents($URL);
$dataInUTF = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xml = simplexml_load_string($dataInUTF);




$information = $xml->xpath("/xml_api_reply/weather/forecast_information");
$current = $xml->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xml->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconData = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconData = str_replace(".gif",".png",$iconData);
  
//passage en format de date /heure en francais
setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');

//remplacement de la date du jour de google:
$iconDow = str_replace("ven.","Vendredi",$forecast_list[0]->day_of_week['data']);

// affichage de la date en français, ajouter ',time()+86400' après %Y" pour la date du lendemain ou ', strtotime("+1 day")'
//encodage en utf8 pour l'accent du mois d'aout
$day = utf8_encode(strftime("%A %d %B %Y", strtotime("+1 day")));

?>
       
        
        
<div id="header" class="bkg">

<span class="datemeteo">
            <?= $day ?>
</span>
    
<h1>Témpératures Après-midi</h1>

</div> 
        
        
<div id="conteneur">  
    
<div id="fond_datemeteo"></div> <!-- fond de texte semi opaque , placé ici pour etre positionné plus facilement via la postion absolute et des margin negatifs -->        
    
    <div id="montpellier">
        
<?
//Montpellier
$URLmtp = "http://www.google.com/ig/api?weather=Montpellier,Languedoc&hl=fr";
$dataInISO = file_get_contents($URLmtp);
$dataInUTFmtp = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlmtp = simplexml_load_string($dataInUTFmtp);
$information = $xmlmtp->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlmtp->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlmtp->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatamtp = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatamtp = str_replace(".gif",".png",$iconDatamtp);
?>    
        		
          <!-- température de Montpellier7 -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
        
 </div>        
  
    
    <div id="nimes">
<?
//Nîmes
$URLnimes = "http://www.google.com/ig/api?weather=nimes&hl=fr";
$dataInISO = file_get_contents($URLnimes);
$dataInUTFnimes = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlnimes = simplexml_load_string($dataInUTFnimes);
$information = $xmlnimes->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlnimes->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlnimes->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatanimes = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatanimes = str_replace(".gif",".png",$iconDatanimes);
?>
                  
            <!-- température de Nimes -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
           
        
  </div>  <!-- fin de div nimes -->
  
  
      <div id="arles">
<?
//Arles
$URLarles = "http://www.google.com/ig/api?weather=arles&hl=fr";
$dataInISO = file_get_contents($URLarles);
$dataInUTFarles = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlarles = simplexml_load_string($dataInUTFarles);
$information = $xmlarles->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlarles->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlarles->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDataarles = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDataarles = str_replace(".gif",".png",$iconDataarles);
?>
                  
            <!-- température de Arles -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
                  
           
        
  </div>  <!-- fin de div arles -->
 
  
  
      <div id="sete">
<?
//Sete
$URLsete = "http://www.google.com/ig/api?weather=sete,Languedoc&hl=fr";
$dataInISO = file_get_contents($URLsete);
$dataInUTFsete = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlsete = simplexml_load_string($dataInUTFsete);
$information = $xmlsete->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlsete->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlsete->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatasete = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatasete = str_replace(".gif",".png",$iconDatasete);
?>
         
            <!-- température de Sete -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
            
        
 </div> 
  
  
        <div id="ales">
<?
//Ales
$URLales = "http://www.google.com/ig/api?weather=sete,Languedoc&hl=fr";
$dataInISO = file_get_contents($URLales);
$dataInUTFales = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlales = simplexml_load_string($dataInUTFales);
$information = $xmlales->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlales->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlales->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDataales = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDataales = str_replace(".gif",".png",$iconDataales);
?>
         	
              <!-- température de Ales -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
            
        
 </div> 
  
  
  
  
<div id="saint_chinian">
<?
//Saint chinian
$URLstchinian = "http://www.google.com/ig/api?weather=saint-chinian&hl=fr";
$dataInISO = file_get_contents($URLstchinian);
$dataInUTFstchinian = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlstchinian = simplexml_load_string($dataInUTFstchinian);
$information = $xmlstchinian->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlstchinian->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlstchinian->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatastchinian = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatastchinian = str_replace(".gif",".png",$iconDatastchinian);
?>
               		
              <!-- température de Saint Chinian-->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>
  
  
<div id="narbonne">
<?
//Narbonne
$URLnarbonne = "http://www.google.com/ig/api?weather=narbonne&hl=fr";
$dataInISO = file_get_contents($URLnarbonne);
$dataInUTFnarbonne = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlnarbonne = simplexml_load_string($dataInUTFnarbonne);
$information = $xmlnarbonne->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlnarbonne->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlnarbonne->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatanarbonne= str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatanarbonne = str_replace(".gif",".png",$iconDatanarbonne);
?>
         
               <!-- température de Narbonne -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>
  
  
<div id="beziers">
<?
//Beziers
$URLbeziers = "http://www.google.com/ig/api?weather=beziers&hl=fr";
$dataInISO = file_get_contents($URLbeziers);
$dataInUTFbeziers = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlbeziers = simplexml_load_string($dataInUTFbeziers);
$information = $xmlbeziers->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlbeziers->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlbeziers->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatabeziers= str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatabeziers = str_replace(".gif",".png",$iconDatabeziers);
?>
         
               <!-- température de Beziers -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>  
  
  
<div id="lodeve">
<?
//lodeve
$URLlodeve = "http://www.google.com/ig/api?weather=lodeve&hl=fr";
$dataInISO = file_get_contents($URLlodeve);
$dataInUTFlodeve = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmllodeve = simplexml_load_string($dataInUTFlodeve);
$information = $xmllodeve->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmllodeve->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmllodeve->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatalodeve= str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatalodeve = str_replace(".gif",".png",$iconDatalodeve);
?>
         
               <!-- température de Lodeve -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div> 
  

<div id="clermont_lherault">
<?
//clermont_lherault
$URLclermont_lherault = "http://www.google.com/ig/api?weather=clermont-l'herault&hl=fr";
$dataInISO = file_get_contents($URLclermont_lherault);
$dataInUTFclermont_lherault = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlclermont_lherault = simplexml_load_string($dataInUTFclermont_lherault);
$information = $xmlclermont_lherault->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlclermont_lherault->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlclermont_lherault->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDataclermont_lherault= str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDataclermont_lherault = str_replace(".gif",".png",$iconDataclermont_lherault);
?>
         
               <!-- température de Clermont l'herault -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div> 
  
  
<div id="bagnols_sur_ceze">
<?
//bagnols_sur_ceze
$URLbagnols_sur_ceze = "http://www.google.com/ig/api?weather=bagnols-sur-ceze&hl=fr";
$dataInISO = file_get_contents($URLbagnols_sur_ceze);
$dataInUTFbagnols_sur_ceze= mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlbagnols_sur_ceze = simplexml_load_string($dataInUTFbagnols_sur_ceze);
$information = $xmlbagnols_sur_ceze->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlbagnols_sur_ceze->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlbagnols_sur_ceze->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatabagnols_sur_ceze= str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatabagnols_sur_ceze = str_replace(".gif",".png",$iconDatabagnols_sur_ceze);
?>
         
               <!-- température de Bagnols sur Ceze -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>   
  
  
<div id="lunel">
<?
//lunel
$URLlunel = "http://www.google.com/ig/api?weather=lunel&hl=fr";
$dataInISO = file_get_contents($URLlunel);
$dataInUTFlunel = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmllunel = simplexml_load_string($dataInUTFlunel);
$information = $xmllunel->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmllunel->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmllunel->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatalunel = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatalunel = str_replace(".gif",".png",$iconDatalunel);
?>
         
               <!-- température de Lunel -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>   
  
  
<div id="le_grau_du_roi">
<?
//le_grau_du_roi
$URLle_grau_du_roi = "http://www.google.com/ig/api?weather=le-grau-du-roi&hl=fr";
$dataInISO = file_get_contents($URLle_grau_du_roi);
$dataInUTFle_grau_du_roi = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlle_grau_du_roi= simplexml_load_string($dataInUTFle_grau_du_roi);
$information = $xmlle_grau_du_roi->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlle_grau_du_roi->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlle_grau_du_roi->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatale_grau_du_roi = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatale_grau_du_roi = str_replace(".gif",".png",$iconDatale_grau_du_roi);
?>
         
               <!-- température de La Grau du Roi -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>   
  
  
<div id="saintes_maries">
<?
//Saintes Maries de la mer
$URLsaintes_maries = "http://www.google.com/ig/api?weather=saintes-maries-de-la-mer&hl=fr";
$dataInISO = file_get_contents($URLsaintes_maries);
$dataInUTFsaintes_maries = mb_convert_encoding($dataInISO, "UTF-8", "ISO-8859-2");
$xmlsaintes_maries= simplexml_load_string($dataInUTFsaintes_maries);
$information = $xmlsaintes_maries->xpath("/xml_api_reply/weather/forecast_information");
$current = $xmlsaintes_maries->xpath("/xml_api_reply/weather/current_conditions");
$forecast_list = $xmlsaintes_maries->xpath("/xml_api_reply/weather/forecast_conditions");
//remplacement des pictos du jour de google:
$iconDatasaintes_maries = str_replace("/ig/images/weather/","/img/",$current[0]->icon['data']);
$iconDatasaintes_maries = str_replace(".gif",".png",$iconDatasaintes_maries);
?>
         
               <!-- température des Saintes Maries de la mer -->		
            <h3><?= $forecast_list[1]->high['data'] ?>&deg;</h3>
  
</div>    
  
    
    </body>
</html>