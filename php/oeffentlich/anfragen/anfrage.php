<?php
// Variablen einlesen, falls übergeben
if (isset($_POST['anfragenziel'])) {$anfragenziel = $_POST['anfragenziel'];}
else {$anfragenziel = '';}

include_once('../../schulhof/anfragen/ziele.php');

if (isset($CMS_ZIELE[$anfragenziel])) {
	include_once('../../../'.$CMS_ZIELE[$anfragenziel]);
}
?>
