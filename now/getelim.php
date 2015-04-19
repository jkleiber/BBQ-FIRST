<?php
	include("read_ini.php");
	$auth = "Authorization: Basic " . base64_encode($ini['api-encoder']);
	
	$key=$_GET['key'];
	
	$i=$_GET['i'];
	$info = array();

try{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://frc-api.usfirst.org/api/v1.0/matches/2015/".$key."?tournamentLevel=playoff");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth , "Accept: application/json"));
$response = curl_exec($ch);
//curl_close($ch);

if (FALSE === $response) {
    throw new Exception(curl_error($ch), curl_errno($ch));
    curl_close($ch);
  } else{
    curl_close($ch);
	//echo json_decode($response);
  }
}
  catch(Exception $e) {
  trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
}

$resp = json_encode($response);
$resp = str_replace('\\',"",$resp);
echo $resp;

$kill_array = true;

$resp = json_decode($response,true);
foreach($resp["Matches"] as $r)
{
	foreach ($r as $key => $value) {
	/*	echo $key;
		echo ":";
		echo $value;
		echo "---"; */
		
		if($key=="description")
		{
			$info[] = $value;
		}
		else if($key=="matchNumber" && $value==$i)
		{
			$info[] = $value;
			$kill_array = false;
		}
		else if($key=="scoreRedFinal")
		{
			$info[] = $value;
		}
		else if($key=="scoreBlueFinal")
		{
			$info[] = $value;
		}
		else
		{
			if(!$kill_array)
			{
				//unset($info);
			}
		}
	}
}

	echo json_encode($info);

?>