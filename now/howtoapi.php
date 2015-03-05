<?php
	//TEST FIRST API v1.0
	
	$auth = "Authorization: Basic " . base64_encode("bbqadmin:8DBE41CE-92B0-4A34-BBB2-D0A411276DD3");
	echo $auth;
try{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://frc-api.usfirst.org/api/v1.0/awards/2015/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json",$auth));
$response = curl_exec($ch);
//curl_close($ch);

if (FALSE === $response) {
    throw new Exception(curl_error($ch), curl_errno($ch));
    curl_close($ch);
  } else{
    curl_close($ch);
	echo json_decode($response);
  }
}
  catch(Exception $e) {
  trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
}
var_dump($response);

?>