<?php
include ('../../../wp-load.php');

if(isset($_GET["moskee"])){
	$moskee=$_GET["moskee"];
	$user = get_user_by( 'login', $moskee );
	echo $user->titelbalk;
}else
{
	echo "";
}

?>
