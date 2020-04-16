<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stunde'])) {$stunde = $_POST['stunde'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {$zeitraum = $_SESSION["STUNDENPLANZEITRAUM"];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$fehler = false;
	$code = "";

	$dbs = cms_verbinden('s');
	// Prüfe, ob die Klasse in diesen Zeitraum gehört
	$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT id, klassenstufe FROM klassen WHERE klassen.id = ?) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id JOIN schuljahre ON klassenstufen.schuljahr = schuljahre.id JOIN zeitraeume ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $klasse, $zeitraum);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($daten = $anfrage->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	} else {$fehler = true;}

	// Prüfen, ob der Kurs der Klasse zugeordnet ist
	if (!$fehler) {
		$sql = "SELECT COUNT(*) AS anzahl FROM kursklassen WHERE klasse = ? AND kurs = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("ii", $klasse, $kurs);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			} else {$fehler = true;}
			$sql->close();
		} else {$fehler = true;}
	}

	// Weitere Klassen suchen, denen dieser Kurs zugeordnet ist
	$klassen = '|'.$klasse;
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT DISTINCT klasse FROM kursklassen WHERE kurs = ? AND klasse != ?");
		$sql->bind_param("ii", $kurs, $klasse);
		if ($sql->execute()) {
			$sql->bind_result($kklasse);
			while ($sql->fetch()) {
				$klassen .= '|'.$kklasse;
			}
		} else {$fehler = true;}
		$sql->close();
	}

	$bestehend = array();
	$anzahl = 0;
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT * FROM (SELECT kurs, lehrkraft, raum FROM stunden WHERE zeitraum = ? AND tag = ? AND stunde = ? AND (kurs IN (SELECT kurs FROM kursklassen WHERE klasse IN (SELECT DISTINCT klasse FROM kursklassen WHERE kurs = ?)) OR raum = ? OR lehrkraft = ?)) AS x)";
		$sql->bind_param("iiiii", $zeitraum, $tag, $stunde, $kurs, $raum, $lehrer);
		if ($sql->execute()) {
			$sql->bind_result($bkurs, $blehrer, $braum)
			while ($daten = $anfrage->fetch()) {
				$bestehend[$anzahl]['kurs'] = $bkurs;
				$bestehend[$anzahl]['lehrkraft'] = $blehrer;
				$bestehend[$anzahl]['raum'] = $braum;
				$anzahl++;
			}
		} else {$fehler = true;}
		$sql->close();
	}


	// Stundendetails laden
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE id = ?");
	$sql->bind_param("i", $stunde);
	if ($sql->execute()) {
		$sql->bind_result($stdid, $stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
		if ($sql->fetch()) {
			$stunde = array();
			$stunde['id'] = $stdid;
			$stunde['bezeichnung'] = $stdbez;
			$stunde['bs'] = $stdbeginns;
			$stunde['bm'] = $stdbeginnm;
			$stunde['es'] = $stdendes;
			$stunde['em'] = $stdendem;
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();


	if (!$fehler) {
		$code = "";
		// Prüfen, ob genau diese Konstellation schon besteht - Falls nicht zur Anlage vorschlagen
		$gefunden = false;
		foreach ($bestehend AS $erg) {if (($erg['kurs'] == $kurs) && ($erg['lehrkraft'] == $lehrer) && ($erg['raum'] == $raum)) {$gefunden = true;}}
		if ((!$gefunden) && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
			// Neue Buchung anlegen
			$code .= "<h4>Neue Stunde</h4>";
			$code .= cms_stunde_ausgeben($dbs, $lehrer, $raum, $kurs, $tag, $stunde, 'a', count($bestehend));
		}
		// Bestehende Buchungen ausgeben
		if (count($bestehend) > 0) {$code .= "<h4>Bestehende Stunden</h4>";}
		foreach ($bestehend AS $erg) {
			$code .= cms_stunde_ausgeben($dbs, $erg['lehrkraft'], $erg['raum'], $erg['kurs'], $tag, $stunde, 'l', count($bestehend));
		}
	}

	cms_trennen($dbs);
	if ($fehler) {echo "ZUORDNUNG";}
	else {
		echo $code;
	}
}
else {
	echo "BERECHTIGUNG";
}


function cms_stunde_ausgeben($dbs, $lehrer, $raum, $kurs, $tag, $stunde, $modus, $anzahl) {
	global $CMS_SCHLUESSEL;
	$fehler = false;

	// Lehrerdetails laden
	$sql = $dbs->prepare("SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.id = ?");
	$sql->bind_param("i", $lehrer);
	if ($sql->execute()) {
		$sql->bind_result($lid, $lvor, $lnach, $ltit, $lkurz);
		if ($sql->fetch()) {
			$lehrer = array();
			$lehrer['id'] = $lid;
			$lehrer['vorname'] = $lvor;
			$lehrer['nachname'] = $lnach;
			$lehrer['titel'] = $ltit;
			$lehrer['kuerzel'] = $lkurz;
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();

	// Raumdetails laden
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id = ?");
	$sql->bind_param("i", $raum);
	$sql->bind_param("i", $lehrer);
	if ($sql->execute()) {
		$sql->bind_result($rid, $rbez);
		if ($sql->fetch()) {
			$raum = array();
			$raum['id'] = $rid;
			$raum['bezeichnung'] = $rbez;
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();


	// Klassenbezeichnungen laden
	$klassenbezeichnungen = "";
	$sql = "SELECT bezeichnung FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM klassen JOIN kursklassen ON klassen.id = kursklassen.klasse WHERE kursklassen.kurs = ?) AS x ORDER BY bezeichnung";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $kurs);
	if ($sql->execute()) {
		$sql->bind_result($bez);
		while ($sql->fetch()) {
			$klassenbezeichnungen .= $bez;
		}
		$sql->close();
	} else {$fehler = true;}

	// Kurs laden
	$sql = $dbs->prepare("SELECT kurse.id AS id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM kurse JOIN klassenstufen ON kurse.klassenstufe = klassenstufen.id WHERE kurse.id = ?");
	$sql->bind_param("i", $kurs);
	if ($sql->execute()) {
		$sql->bind_result($kid, $kbez, $kstufe);
		while ($sql->fetch()) {
			$kurs = array();
			$kurs['id'] = $kid;
			$kurs['bezeichnung'] = $kbez;
			$kurs['stufe'] = $kstufe;
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();


	if (!$fehler) {
		$code = "";
		if ((($modus == 'a') && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) || $modus == 'l') {
			$code .= "<table class=\"cms_liste\">";
			$code .= "<tr><th>Tag:</th><td>".cms_tagnamekomplett($tag)."</td></tr>";
			$code .= "<tr><th>Stunde:</th><td>".$stunde['bezeichnung']." (".$stunde['bs'].":".$stunde['bm']." - ".$stunde['es'].":".$stunde['em'].")</td></tr>";
			$code .= "<tr><th>Lehrkraft:</th><td>".cms_generiere_anzeigename($lehrer['vorname'], $lehrer['nachname'], $lehrer['titel'])." (".$lehrer['kuerzel'].")</td></tr>";
			$code .= "<tr><th>Raum:</th><td>".$raum['bezeichnung']."</td></tr>";
			$code .= "<tr><th>Kurs:</th><td>".$kurs['bezeichnung']." (".$kurs['stufe']." ".$klassenbezeichnungen.")</td></tr>";
			if ($modus == 'a') {
				$code .= "<tr><td colspan=\"2\">";
				if ($anzahl > 0) {
					$code .= "".cms_meldung('warnung', '<h4>Überschneidung!</h4><p>Achtung, durch Anlegen dieser Stunde entstehen Überschneidungen!</p>');
				}
				$code .= "<input type=\"hidden\" name=\"cms_stundenplanung_tag\" id=\"cms_stundenplanung_tag\" value=\"$tag\">";
				$code .= "<input type=\"hidden\" name=\"cms_stundenplanung_stunde\" id=\"cms_stundenplanung_stunde\" value=\"".$stunde['id']."\">";
				$code .= "<span class=\"cms_button_ja\" onclick=\"cms_stundenplanung_stunde_neu_speichern();\">Anlegen</span></td></tr>";
			}
			else if (($modus == 'l') && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
				$code .= "<tr><td colspan=\"2\"><span class=\"cms_button_nein\" ";
				$code .= "onclick=\"cms_stundenplanung_stunde_loeschen_vorbereiten('".$lehrer['id']."', '".$raum['id']."', '".$kurs['id']."', '".$tag."', '".$stunde['id']."');\">";
				$code .=  "Löschen</span></td></tr>";}
			$code .= "</table>";
		}
		return $code;
	}
	else {return false;}
}
?>
