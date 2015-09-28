<?php
//CONNECT TO THE FRC API
include("read_ini.php");
	$auth = "Authorization: Basic " . base64_encode($ini['api-encoder']);
	
	$opts = array(
		'http'=>array(
			'method'=>"GET",
			'header'=> $auth . "\r\n" .
						"Accept: application/json"
			)
		);
		
	$context = stream_context_create($opts);

?>