<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['rolle'])) {$rolle = $_POST['rolle'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($rolle,0)) {echo "FEHLER"; exit;}
if (isset($_POST['anschalten'])) {$anschalten = $_POST['anschalten'];} else {echo "FEHLER"; exit;}
if (!cms_check_toggle($anschalten)) {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.verwaltung.rechte.rollen.zuordnen")) {
	$fehler = false;

	if (!isset($_SESSION['PERSONENDETAILS'])) {
		$fehler = true;
	}
	else {
		$person = $_SESSION['PERSONENDETAILS'];
	}

	$dbs = cms_verbinden('s');
	// Art der Person laden
	$personenart = "";

	$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?;");
  $sql->bind_param("i", $person);
  if ($sql->execute()) {
    $sql->bind_result($personenart);
    if (!$sql->fetch()) {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	// Prüfen, ob zu vergebende Rolle existiert und diesem Benutzer zugeordnet werden kann
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM rollen WHERE id = ?;");
  $sql->bind_param("i", $rolle);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
		if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	// Prüfen, falls die Administratorrolle entfernt wird, ob ein anderer sie noch inne hat
	if (($rolle == 0) && ($anschalten == 0)) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM rollenzuordnung WHERE person != ? AND rolle = 0");
	  $sql->bind_param("i", $person);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
			if ($sql->fetch()) {if ($anzahl < 1) {echo "ADMINFEHLER"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
	}
	cms_trennen($dbs);


	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Wenn die Rolle vergeben werden soll:
		if ($anschalten == 1) {
			// Neue Rolle vergeben
			$sql = $dbs->prepare("INSERT INTO rollenzuordnung (person, rolle) VALUES (?, ?);");
		  $sql->bind_param("ii", $person, $rolle);
		  $sql->execute();
		  $sql->close();
		}
		// Wenn die Rolle entfernt werden soll
		else {
			$sql = $dbs->prepare("DELETE FROM rollenzuordnung WHERE person = ? AND rolle = ?;");
			$sql->bind_param("ii", $person, $rolle);
			$sql->execute();
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
