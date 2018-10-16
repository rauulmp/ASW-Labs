<?php
ini_set("soap.wsdl_cache_enabled","0");
header('Content-Type: application/json');

try{

  $sClient = new SoapClient('http://api.chartlyrics.com/apiv1.asmx?WSDL');

  // Get the necessary parameters from the request
  // Use $sClient to call the operation GetLyric
  // echo the returned info as a JSON object
  $param = new stdClass();
  $param->lyricId = $_GET['lyricId'];
  $param->lyricCheckSum = $_GET['lyricCheckSum'];
	$lyrics = $sClient->getLyric($param);
  $result = $lyrics->GetLyricResult;
	echo json_encode($result);
}
catch(SoapFault $e){
  header(':', true, 500);
  echo json_encode($e);
}

