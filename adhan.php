<?php
if(isset($_GET["moskee_tijd"])){
	$moskee_tijd=$_GET["moskee_tijd"];
	$url = "https://izaachen.de/api/times/2019/Cities+2019/".urlencode($moskee_tijd)."/json/minify";
	$json = file_get_contents($url);
	$decoded_content = gzdecode($json);
	$json_data = json_decode($decoded_content, true);
	//echo $decoded_content;
	//echo $json_data["formatted_address"];
	$maand=date("n");
	$day=date("j");
	//var_dump($json_data["times"][$maand][$day]);

	$fadjr = $json_data["times"][$maand][$day]["p1"]["t"];
	$shoeroeq = $json_data["times"][$maand][$day]["p2"]["t"];
	$dhoehr = $json_data["times"][$maand][$day]["p3"]["t"];
	$asr = $json_data["times"][$maand][$day]["p4"]["t"];
	$maghrib = $json_data["times"][$maand][$day]["p5"]["t"];
	$isha = $json_data["times"][$maand][$day]["p6"]["t"];
	
}else{
	
}

$datetime = new DateTime('tomorrow');

$date_m = $datetime->format('j');
$date_t = $datetime->format('Y-m-d');
//echo $date_t;
$fajr_m=$json_data["times"][$maand][$date_m]["p1"]["t"];


$fadjr_m= $date_t.' '.$fajr_m;
//echo $fadjr_m;
//$fadjr="0:01";

$huidige_tijd = strtotime(date("H:i"));

function active($fadjr, $shoeroeq, $dhoehr, $asr, $maghrib, $isha, $fadjr_m) {
    $huidige_tijd = strtotime(date("H:i"));
    //$huidige_tijd = strtotime(date("12:50"));
    if (strtotime($fadjr) > $huidige_tijd) {
        $gebedsnaam = 'Fajr';
        $tijd = strtotime($fadjr);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => $gebedsnaam, "gebedstijd" => $tijd);
        return $array;
    } elseif (strtotime($shoeroeq) > $huidige_tijd) {
        $gebedsnaam = 'Shuroeq';
        $tijd = strtotime($shoeroeq);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => $gebedsnaam, "gebedstijd" => $tijd);
        return $array;
    } elseif (strtotime($dhoehr) > $huidige_tijd) {
        $gebedsnaam = 'Duhr';
        $tijd = strtotime($dhoehr);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => $gebedsnaam, "gebedstijd" => $tijd);
        return $array;
    } elseif (strtotime($asr) > $huidige_tijd) {
        $gebedsnaam = 'Asr';
        $tijd = strtotime($asr);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => $gebedsnaam, "gebedstijd" => $tijd);
        return $array;
    } elseif (strtotime($maghrib) > $huidige_tijd) {
        $gebedsnaam = 'Maghreb ';
        $tijd = strtotime($maghrib);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => $gebedsnaam, "gebedstijd" => $tijd);
        return $array;
    } elseif (strtotime($isha) > $huidige_tijd) {
        $gebedsnaam = 'Ishaa';
        $tijd = strtotime($isha);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => $gebedsnaam, "gebedstijd" => $tijd);
        return $array;
    } else {
        $tijd = strtotime($fadjr_m);
        $tijd = gmdate('r',$tijd);
        $array = array("gebedsnaam" => 'Fadjr', "gebedstijd" => $tijd);
        return $array;
    }
}

$active = active($fadjr, $shoeroeq, $dhoehr, $asr, $maghrib, $isha, $fadjr_m);
echo json_encode($active);
?>

