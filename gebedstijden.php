<?php
function getJson($url,$moskee_tijd) {
    // cache files are created like cache/abcdef123456...
    $cacheFile = dirname(__FILE__).'/cache' . DIRECTORY_SEPARATOR . md5($url).".json";

    if (file_exists($cacheFile)) {
        //echo "hij vind wel";
        //echo $cacheFile;
        
        $fh = fopen($cacheFile, 'r');
        $cacheTime = fgets($fh);
        //echo $cacheTime;
        // if data was cached recently, return cached data
        $ch_date=date("m-d",$cacheTime);
        $date=date("m-d");
        //echo $ch_date;
        
        //if($ch_date == $date) {
            //echo "test";
        return fgets($fh);
            //die('Run');
        //}
        //return($cacheTime);
        // else delete cache file
        //fclose($fh);
        //unlink($cacheFile);
    }

    //$url = "https://izaachen.de/api/times/2019/Cities+2019/".urlencode($moskee_tijd)."/json/minify";
    $json_gz=file_get_contents($url);
    $decoded_content = gzdecode($json_gz);
    $fh = fopen($cacheFile, 'w');
    fwrite($fh, time() . "\n");
    fwrite($fh, $decoded_content);
    fclose($fh);
    
    return $json;
}

if(isset($_GET["moskee_tijd"])){
        
	$moskee_tijd=$_GET["moskee_tijd"];
	$url = "https://izaachen.de/api/times/2019/Cities+2019/".urlencode($moskee_tijd)."/json/minify";
	$json=getJson($url,$moskee_tijd);
        
	//var_dump($json);
	$json_data = json_decode($json, true);
	//echo $decoded_content;
	//echo $json_data["formatted_address"];
	
        $date=$_GET["datum"];
        $maand=date("n",strtotime($date));
	$day=date("j",strtotime($date));
        //$datetime = new DateTime('tomorrow');
        //$nieuwMaand=$datetime->format('n');
        //$nieuwDag=$datetime->format('j');
	//var_dump($json_data["times"][$maand][$day]);

	$fajr = date($json_data["times"][$maand][$day]["p1"]["t"]);
	$shuroeq = date($json_data["times"][$maand][$day]["p2"]["t"]);
	$duhr = date($json_data["times"][$maand][$day]["p3"]["t"]);
	$asr = date($json_data["times"][$maand][$day]["p4"]["t"]);
	$maghreb = date($json_data["times"][$maand][$day]["p5"]["t"]);
	$ishaa = date($json_data["times"][$maand][$day]["p6"]["t"]);
}else{
	die("Geen tijden voor het gebed gevonden.");
}



//$fajr="0:01";
//$duhr='10:49';
$huidige_tijd = strtotime(date("Y-m-d H:i"));

function active($fajr, $shuroeq, $duhr, $asr, $maghreb, $ishaa,$fajr_m) {
    $huidige_tijd = strtotime(date("Y-m-d H:i"));
    //$huidige_tijd = strtotime(date("12:47"));
    if (strtotime($fajr) >= $huidige_tijd) {
        return 'fajr-'.$fajr;
    } elseif (strtotime($shuroeq) >= $huidige_tijd) {
        return 'shuroeq-'.$shuroeq;
    } elseif (strtotime($duhr) >= $huidige_tijd) {
        return 'duhr-'.$duhr;
    } elseif (strtotime($asr) >= $huidige_tijd) {
        return 'asr-'.$asr;
    } elseif (strtotime($maghreb) >= $huidige_tijd) {
        return 'maghreb-'.$maghreb;
    } elseif (strtotime($ishaa) >= $huidige_tijd) {
        return 'ishaa-'.$ishaa;
    } else {
        return 'fajr-'.$ishaa;
    }
}

$active = active($fajr, $shuroeq, $duhr, $asr, $maghreb, $ishaa);

function check_time($time,$active,$gebed) {
    $huidige_tijd = strtotime(date("Y-m-d H:i"));
    //$huidige_tijd = strtotime(date("12:47"));
    if (strtotime($time) == $huidige_tijd) {
        return 'flash';
    } elseif($active==$gebed){
        return 'active';
    } elseif (strtotime($time) > $huidige_tijd) {
        return '';
    }
    else {
        return 'old';
    }
}

$array = [
    "fajr" => array(
        "time" =>$fajr,
        ),
    "shuroeq" => array(
        "time" =>$shuroeq,
        ),
    "duhr" => array(
        "time" =>$duhr,
        ),
    "asr" => array(
        "time" =>$asr,
        ),
    "maghreb" => array(
        "time" =>$maghreb,
        ),
    "ishaa" => array(
        "time" =>$ishaa,
        ),
];
$json=json_encode($array, JSON_FORCE_OBJECT);
//$json_decode = json_decode($json);

echo $json;

?>