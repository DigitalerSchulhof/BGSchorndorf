<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Klassen- und Kurslisten</h1>";

$zugriff = $CMS_RECHTE['Gruppen']['Klassen Listen sehen'];

if ($zugriff) {
	$dbs = cms_verbinden();
	$liste = "";


	$klassenwahl = "";
	$klassegewaehltbez = "";
	if (isset($_SESSION['KLASSENLISTE'])) {
		$klassegewaehlt = $_SESSION['KLASSENLISTE'];
		$gewaehlt = true;
	}
	else  {
		$klassegewaehlt = "";
		$gewaehlt = false;
	}

	$sql = "SELECT * FROM (SELECT klassen.id AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id) AS y ORDER BY reihenfolge ASC, bezeichnung ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
	    $id = $daten['id'];
			$bezeichnung = $daten['bezeichnung'];

			$zusatz = "";
			if (!$gewaehlt) {$klassegewaehlt = $id; $gewaehlt = true;}
			if ($id == $klassegewaehlt) {$zusatz = "_aktiv";}
			$klassenwahl .= "<span class=\"cms_toggle$zusatz\" onclick=\"cms_listen_klassenwahl('$id')\">$bezeichnung</span> ";
		}
		$anfrage->free();
	}

	if (strlen($klassenwahl) > 0) {
		$code .= "<p>$klassenwahl</p>";
		include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
		include_once('php/schulhof/seiten/listen/klassenerzeugen.php');
		$code .= "<h2>Liste $klassegewaehltbez</h2>";
		$code .= cms_listen_klassen_ausgeben($dbs, $klassegewaehlt);
	}
	else {
		$code .= cms_meldung('info', '<h4>Keine Klassen vorhanden</h4><p>In diesem Schuljahr wurden noch keine Klassen angelegt.</p>');
	}

	cms_trennen($dbs);
}
else {
	$code .= cms_meldung_berechtigung();
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
