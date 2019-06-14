<?php
$aktiv = false;

if ($aktiv) {
	include_once("php/schulhof/funktionen/dateisystem.php");
	cms_dateisystem_ordner_verschluesseln('dateien/schulhof');
}
?>
