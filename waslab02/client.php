<?php

$URI = 'http://localhost:8080/waslab02/wall.php';
$resp = file_get_contents($URI);
echo $http_response_header[0], "\n"; // Print the first HTTP response header
/* New Echo */

$twsrebuts = new SimpleXMLElement($resp);

foreach ($twsrebuts->tweet as $tw) {
	$id = $tw["id"];
	$author = $tw->author;
	$text = $tw->text;
	$time = $tw->time;
	
	echo "[tweet #" . $id . "] " . $author . ": " . $text . " [" . $time . "]\n";
}

$postdata = '<?xml version="1.0"?><tweet><author>Test Author</author><text>Test Text</text></tweet>';
	
$opts = array('http' =>
    array(
        'method'  => 'PUT',
        'header'  => 'Content-type: text/xml',
        'content' => $postdata
    )
);

$context = stream_context_create($opts);

$result = file_get_contents($URI, false, $context);

echo $result;


$opts = array('http' =>
    array(
        'method'  => 'DELETE',
        'header'  => 'Content-type: text/xml'
    )
);

$context = stream_context_create($opts);

$result = file_get_contents($URI .'?twtid=16', false, $context);

echo $result;
?>
