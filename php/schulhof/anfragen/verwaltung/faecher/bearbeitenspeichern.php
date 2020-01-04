<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = cms_texttrafo_e_db($_POST['bezeichnung']);} else {echo "FEHLER";exit;}
if (isset($_POST['kuerzel'])) {$kuerzel = cms_texttrafo_e_db($_POST['kuerzel']);} else {echo "FEHLER";exit;}
if (isset($_POST['farbe'])) {$farbe = $_POST['farbe'];} else {echo "FEHLER";exit;}
if (isset($_POST['icon'])) {$icon = $_POST['icon'];} else {echo "FEHLER";exit;}
if (isset($_POST['kollegen'])) {$kollegen = $_POST['kollegen'];} else {echo "FEHLER‚";exit;}
if (isset($_SESSION["FAECHERBEARBEITEN"])) {$id = $_SESSION["FAECHERBEARBEITEN"];} else {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.planung.schuljahre.fächer.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}
	if (!cms_check_titel($kuerzel)) {$fehler = true;}
	if (!cms_check_ganzzahl($farbe,0,63)) {$fehler = true;}
	if (!cms_check_dateiname($icon)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		// Schuljahr laden
		$SCHULJAHR = null;
	  $sql = $dbs->prepare("SELECT COUNT(*), schuljahr FROM faecher WHERE id = ?");
	  $sql->bind_param('i', $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl, $SCHULJAHR);
	    if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			}
	  } else {$fehler = true;}
	  $sql->close();

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ? AND id != ?");
	  $sql->bind_param("sii", $bezeichnung, $SCHULJAHR, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELTB"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ? AND id != ?");
	  $sql->bind_param("sii", $kuerzel, $SCHULJAHR, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELTK"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		// Prüfen, ob alle eingegebenen Personen Lehrer sind
		if (strlen($kollegen) > 0) {
			$pids = str_replace("|", ",", $kollegen);
			$pids = "(".substr($pids, 1).")";
			if (cms_check_idliste($pids)) {
				$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$pids." AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL');");
				if ($sql->execute()) {
					$sql->bind_result($anzahl);
					if ($sql->fetch()) {
						if ($anzahl != 0) {
							$fehler = true;
						}
					}
					else {$fehler = true;}
				}
				else {$fehler = true;}
				$sql->close();
			}
			else {$fehler = true;}
		}

		cms_trennen($dbs);
	}

	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE faecher SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), farbe = ? WHERE id = ?");
		$sql->bind_param("sssii", $bezeichnung, $kuerzel, $icon, $farbe, $id);
		$sql->execute();
		$sql->close();

		$sql = $dbs->prepare("DELETE FROM fachkollegen WHERE fach = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		if (strlen($kollegen) > 0) {
			$sql = $dbs->prepare("INSERT INTO fachkollegen SET kollege = ?, fach = ?");
			$kollegenids = explode("|", $kollegen);
			for ($i = 1; $i < count($kollegenids); $i++) {
				$sql->bind_param("ii", $kollegenids[$i], $id);
				$sql->execute();
			}
			$sql->close();
		}


		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
