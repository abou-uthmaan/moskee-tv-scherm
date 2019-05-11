<?php
if(isset($_GET["plaats"])){
	$plaats=$_GET["plaats"];
	
	$url="https://api.openweathermap.org/data/2.5/weather?id=".$plaats."&units=metric&APPID=5d546b4811ba8cab4249ab92b86b2a39";

//  Initiate curl
$ch = curl_init();
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

// Will dump a beauty json :3
echo $result;
}else
{
	echo "";
}

?>