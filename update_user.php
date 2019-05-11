<?php
include '../../../wp-load.php';
//var_dump($_POST);
$user_id = $_POST["user_id"];

foreach ($_POST as $key => $value) {
    if($key!="user_id" || $key!="submit"){
        if (strpos($key, 'kleur') !== false) {
            $meta_value = "#".$value;
        }else
        {
            $meta_value = $value;
        }
        $meta_key = $key;
        
        update_user_meta( $user_id, $meta_key, $meta_value );
    }
}

$user = get_user_by('id', $_POST["user_id"]);
$newURL="https://moskeewijzer.nl/tv-scherm/?naam=".$user->user_login;
header('Location: '.$newURL);

?>

