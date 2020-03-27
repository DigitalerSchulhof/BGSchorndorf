<?php
include_once("php/schulhof/funktionen/dateisystem.php");

$pfad = "dateien/download";
chmod($pfad, 0777);
$dateien = scandir($pfad);

foreach ($dateien as $d) {
	if (($d != ".") && ($d != "..") && ($d != ".htaccess")) {
		echo "Lösche: ".$pfad."/".$d."<br>";
		unlink($pfad."/".$d);
	}
}
chmod($pfad, 0775);

echo "DATEIEN GELÖSCHT<br>";
echo phpinfo();
?>
