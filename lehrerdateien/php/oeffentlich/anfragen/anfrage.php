<?php
include_once("../../lehrerzimmer/funktionen/config.php");
header('Access-Control-Allow-Origin: '.$CMS_SH_SERVER);
header('Access-Control-Allow-Methods: POST');

// Variablen einlesen, falls übergeben
if (isset($_POST['anfragenziel'])) {$anfragenziel = $_POST['anfragenziel'];}
else {$anfragenziel = '';}

include_once('../../lehrerzimmer/anfragen/ziele.php');

if (isset($CMS_ZIELE[$anfragenziel])) {

	//include_once('../../../'.$CMS_ZIELE[34]);
	include_once('../../../'.$CMS_ZIELE[$anfragenziel]);
}
?>
