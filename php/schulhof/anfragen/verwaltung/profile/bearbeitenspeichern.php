<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['faecherids'])) {$faecherids = $_POST['faecherids'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['PROFILBEARBEITEN'])) {$pid = $_SESSION['PROFILBEARBEITEN'];} else {echo "FEHLER";exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.profile.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($stufe)) {echo "FEHLER"; exit;}
	if ($art != 'w' && $art != 'p') {echo "FEHLER"; exit;}

	$dbs = cms_verbinden('s');
	// Existiert das Schuljahr
  $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, schuljahr FROM profile WHERE id = ?");
  $sql->bind_param('i', $pid);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $SCHULJAHR);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
  } else {$fehler = true;}
  $sql->close();

	// Existiert die Stufe
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM stufen WHERE schuljahr = ? AND id = ?");
	  $sql->bind_param('ii', $SCHULJAHR, $stufe);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true; echo "STUFE";}
			}
	  } else {$fehler = true;}
	  $sql->close();
	}

	if (!$fehler) {
		$wahlfaecher = array();
		if (strlen($faecherids) > 0) {
			$fids = explode('|', $faecherids);
			for ($i=1; $i<count($fids); $i++) {
				if (isset($_POST["fach_".$fids[$i]])) {
					if ($_POST["fach_".$fids[$i]] == '1') {
						array_push($wahlfaecher, $fids[$i]);
					}
				}
				else {$fehler = true;}
			}
		}
		else {$fehler = true;}
	}

	if (count($wahlfaecher) < 1) {
		echo "FACH";
		$fehler = true;
	}

	// Prüfen, ob die Fächer existieren
	if (!$fehler) {
		$faecher = count($wahlfaecher);
		$faechersql = "(".implode(',', $wahlfaecher).")";
		if (cms_check_idliste($faechersql)) {
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM faecher WHERE schuljahr = ? AND id IN $faechersql");
			$sql->bind_param("i", $SCHULJAHR);
		  if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {
					if ($anzahl != $faecher) {$fehler = true; echo "FACH";}
				}
		  } else {$fehler = true;}
		  $sql->close();
		}
		else {$fehler = true;}
	}


	if (!$fehler) {
		// ZEITRAUM EINTRAGEN
		$sql = $dbs->prepare("UPDATE profile SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufe = ?, art = ? WHERE id = ?");
	  $sql->bind_param("sisi", $bezeichnung, $stufe, $art, $pid);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("DELETE FROM profilfaecher WHERE profil = ?");
		$sql->bind_param("i", $pid);
		$sql->execute();

		$sql = $dbs->prepare("INSERT INTO profilfaecher (profil, fach) VALUES (?, ?)");
		foreach ($wahlfaecher as $w) {
			$sql->bind_param("ii", $pid, $w);
			$sql->execute();
		}
		$sql->close();

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
cms_trennen($dbs);
?>
