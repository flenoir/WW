<html>
    <head>
    <link rel="stylesheet" href="css/style_temps.css" type="text/css" charset="utf-8"/>
        <title>Google Weather API</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
 
 <!-- on place le php avant le header pour recuperer la variable de date -->
 
<?
//Montpellier
$URL = "http://xml.weather.com/weather/local/FRXX0068?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);

$xml = simplexml_load_string($dataInISO);
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
$day = utf8_encode(strftime("%A %d %B %Y", strtotime("+2 day")));


?>
       
        
        
<div id="header" class="bkg">

  <span class="datemeteo">
            <?= $day ?>
  </span>
    
  <h1>Après-midi</h1>

</div> 
        
        
<div id="conteneur">  
 
<div id="fond_datemeteo"></div> <!-- fond de texte semi opaque , placé ici pour etre positionné plus facilement via la postion absolute et des margin negatifs -->    
    
    
        
    <div id="montpellier">
        
<?
//Montpellier
$URLmtp = "http://xml.weather.com/weather/local/FRXX0068?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URLmtp);
$xmlmtp = simplexml_load_string($dataInISO);
$currenttemp = $xmlmtp->xpath("/weather/dayf/day[@d='2']/hi");
?>    
        		            
           <h3> <?= $currenttemp[0] ?>&deg; </h3>
           
            
        
 </div>        
  
    
    <div id="nimes">
<?
//Nîmes
$URL = "http://xml.weather.com/weather/local/FRXX0165?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
           
        
  </div> 
  
  
      <div id="arles">
<?
//Arles
$URL = "http://xml.weather.com/weather/local/FRXX1805?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
                  
           
        
  </div>  <!-- fin de div arles -->
 
 
<div id="narbonne">
<?
//Narbonne
$URL = "http://xml.weather.com/weather/local/FRXX0013?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>
  
  
<div id="beziers">
<?
//Beziers
$URL = "http://xml.weather.com/weather/local/FRXX0338?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>  

  
      <div id="marseille">
<?
//Marseille
$URL = "http://xml.weather.com/weather/local/FRXX0059?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
              
        
 </div> 
  
  
        <div id="ajaccio">
<?
//Ajaccio
$URL = "http://xml.weather.com/weather/local/FRXX0129?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
         
        </div> 
  
  
  
  
<div id="nice">
<?
//Nice
$URL = "http://xml.weather.com/weather/local/FRXX0073?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>
  
  
 
  
<div id="frejus">
<?
//Gap
$URL = "http://xml.weather.com/weather/local/FRXX0040?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div> 
  

<div id="toulon">
<?
//Toulon
$URL = "http://xml.weather.com/weather/local/FRXX0098?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div> 
  
  
<div id="digne_les_bains">
<?
//Digne les Bains
$URL = "http://xml.weather.com/weather/local/FRXX0211?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>   
  
  
<div id="aix_en_provence">
<?
//Aix en Provence
$URL = "http://xml.weather.com/weather/local/FRXX0001?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>   
  
  
<div id="ales">
<?
//Ales
$URL = "http://xml.weather.com/weather/local/FRXX0427?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>   
  
  
<div id="perpignan">
<?
//Perpignan
$URL = "http://xml.weather.com/weather/local/FRXX0128?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>    
  

  <div id="avignon">
<?
//Perpignan
$URL = "http://xml.weather.com/weather/local/FRXX0270?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>  

<div id="bastia">
<?
//Perpignan
$URL = "http://xml.weather.com/weather/local/FRXX0130?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>  

<div id="carcassonne">
<?
//Perpignan
$URL = "http://xml.weather.com/weather/local/FRXX0025?cc=*&unit=m&dayf=5";
$dataInISO = file_get_contents($URL);
$xml = simplexml_load_string($dataInISO);
$currenttemp = $xml->xpath("/weather/dayf/day[@d='2']/hi");
?>
                  
            <h3> <?= $currenttemp[0] ?>&deg; </h3>
  
</div>  
    
    </body>
</html>