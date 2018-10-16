<?php
ini_set("soap.wsdl_cache_enabled","0");
header('Content-Type: application/json');

try{

  $sClient = new SoapClient('http://api.chartlyrics.com/apiv1.asmx?WSDL');

  $param = new stdClass();
  $param->lyricText = $_GET["content"];
	
  $songs = $sClient->SearchLyricText($param);
	$result = $songs->SearchLyricTextResult->SearchLyricResult;
	echo json_encode($result);
}
catch(SoapFault $e){
  header(':', true, 500);
  echo json_encode($e);
}

function compare_some_objects($a, $b) { // Make sure to give this a more meaningful name!
  return $a->Song - $b->Song;
}
?>

