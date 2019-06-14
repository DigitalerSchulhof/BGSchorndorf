<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Stufenlisten</h1>";

$zugriff = $CMS_RECHTE['lehrer'] || $CMS_RECHTE['verwaltung'];

if ($zugriff) {
	$dbs = cms_verbinden();
	$liste = "";


	$stufenwahl = "";
	$stufegewaehltbez = "";
	if (isset($_SESSION['STUFENLISTE'])) {
		$stufegewaehlt = $_SESSION['STUFENLISTE'];
		$gewaehlt = true;
	}
	else  {
		$stufegewaehlt = "";
		$gewaehlt = false;
	}

	$sql = "SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge, id FROM stufen WHERE schuljahr = $CMS_BENUTZERSCHULJAHR) AS y ORDER BY reihenfolge ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
	    $id = $daten['id'];
			$stufe = $daten['stufe'];

			$zusatz = "";
			if (!$gewaehlt) {$stufegewaehlt = $id; $gewaehlt = true;}
			if ($id == $stufegewaehlt) {
				$zusatz = "_aktiv";
				$stufegewaehltbez = $stufe;
			}
			$stufenwahl .= "<span class=\"cms_toggle$zusatz\" onclick=\"cms_listen_stufenwahl('$id')\">$stufe</span> ";
		}
		$anfrage->free();
	}

	if (strlen($stufenwahl) > 0) {
		$code .= "<p>$stufenwahl</p>";
		include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
		include_once('php/schulhof/seiten/listen/stufenerzeugen.php');
		$code .= "<h2>Liste der Stufe $stufegewaehltbez</h2>";
		$code .= cms_listen_stufen_ausgeben($dbs, $stufegewaehlt);
	}
	else {
		$code .= cms_meldung('info', '<h4>Keine Stufen vorhanden</h4><p>In diesem Schuljahr wurden noch keine Stufen angelegt.</p>');
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
