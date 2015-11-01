<?php

$api_key = $_GET['key'];
$lat = $_GET['lat'];
$long = $_GET['long'];

$datetime = date('');

$config = '<?php
    $name = "' . $name . '";
	$lat = "' . $lat . '";
    $long = "' . $long . '";
    $datetime = "' . $datetime . '";
    ?>';
    
$config2 = '$lat = "' . $lat . '";
    $long = "' . $long . '";
    $datetime = "' . $datetime . '";';

  /* Write Variables to file */
  $fp = fopen("location.php", "w");
  fwrite($fp, $config);
  fclose($fp);
  
  echo $config2;
  
?>