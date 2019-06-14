<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {$zeitraum = $_SESSION["STUNDENPLANZEITRAUM"];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	$code = "";

	$dbs = cms_verbinden('s');

	if ($art == 'l') {
		$code = cms_stundenplan_erzeugen($dbs, $zeitraum, $art, $lehrer);
		if (!$code) {$fehler = true;}
	}
	else if ($art == 'r') {
		$code = cms_stundenplan_erzeugen($dbs, $zeitraum, $art, $raum);
		if (!$code) {$fehler = true;}
	}
	else if ($art == 'k') {
		// Prüfe, ob die Klasse in diesen Zeitraum gehört
		$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT id, klassenstufe FROM klassen WHERE klassen.id = $klasse) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id JOIN schuljahre ON klassenstufen.schuljahr = schuljahre.id JOIN zeitraeume ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = $zeitraum";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] != 1) {$fehler = true;}
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}

		// Prüfen, ob der Kurs der Klasse zugeordnet ist
		if (!$fehler) {
			$sql = "SELECT COUNT(*) AS anzahl FROM kursklassen WHERE klasse = $klasse AND kurs = $kurs";
			if ($anfrage = $dbs->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()) {
					if ($daten['anzahl'] != 1) {$fehler = true;}
				} else {$fehler = true;}
				$anfrage->free();
			} else {$fehler = true;}
		}

		// Weitere Klassen suchen, denen dieser Kurs zugeordnet ist
		$klassen = '|'.$klasse;
		if (!$fehler) {
			$sql = "SELECT DISTINCT klasse FROM kursklassen WHERE kurs = $kurs AND klasse != $klasse";
			if ($anfrage = $dbs->query($sql)) {
				while ($daten = $anfrage->fetch_assoc()) {
					$klassen .= '|'.$daten['klasse'];
				}
				$anfrage->free();
			} else {$fehler = true;}
		}

		// Klassenstundenpläne erzeugen
		if (!$fehler) {
	    $klassen = explode('|', $klassen);
			$breite = 100 / count($klassen)-1;
      if ($breite > 37.5) {$breite = 37.5;}
	    for ($k=1; $k<count($klassen); $k++) {
	      $stundenplan = cms_stundenplan_erzeugen($dbs, $zeitraum, 'k', $klassen[$k], count($klassen)-1);
	      if (!$stundenplan) {$fehler = true;}
				$code .= "<div class=\"cms_klassenstundenplan\" style=\"width: $breite%;\"><div class=\"cms_spalte_i\">".$stundenplan."</div></div>";
	    }
	  }
	}
	else {$fehler = true;}

	cms_trennen($dbs);
	if ($fehler) {echo "BASTLER";}
	else {
		echo $code;
	}
}
else {
	echo "BERECHTIGUNG";
}
?>