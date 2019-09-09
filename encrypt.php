<?php
$aktiv = true;

if ($aktiv) {
	include_once("php/schulhof/funktionen/config.php");
	include_once("php/schulhof/funktionen/dateisystem.php");
	echo $CMS_SCHLUESSEL."<br><br>";
	cms_dateisystem_ordner_verschluesseln('dateien/schulhof/personen');
}
?>
