<?php
	//TEST FIRST API v1.0
	include("read_ini.php");
	include("api_connect.php");
	
	$url = "https://frc-api.usfirst.org/v2.0/2015/awards/txda";
	$response=file_get_contents($url,false,$context);

var_dump($response);

?>
<pre>
<?php
echo $response;
?>
</pre>