<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
session_start();

// Variablen einlesen, falls übergeben
$zugeordnet = array();

postLesen(array("ziel", "bezeichnung"));

foreach($CMS_GRUPPEN as $g) {
  $gk = cms_textzudb($g);
  if (isset($_POST['z'.$gk])) {$zugeordnet[$g] = $_POST['z'.$gk];} else {echo "FEHLER";exit;}
}

if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if ($CMS_RECHTE['Website']['Newsletter anlegen']) {
	$zugriff = true;
}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Prüfen, ob die zugeordneten Gruppen existieren
	$dbs = cms_verbinden('s');
	foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
		$ids = str_replace('|', ',', $zugeordnet[$g]);
		if (strlen($ids) > 0) {
      $ids = "(".substr($ids, 1).")";
      if (cms_check_idliste($ids)) {
        $anzahl = count(explode(',', $ids));
  			$sql = "SELECT COUNT(id) AS anzahl FROM $gk WHERE id IN $ids";
  			if ($anfrage = $dbs->query($sql)) {
  				if ($daten = $anfrage->fetch_assoc()) {
  					if ($daten['anzahl'] != $anzahl) {$fehler = true;}
  				}
  				else {$fehler = true;}
  				$anfrage->free();
  			}
  			else {$fehler = true;}
      }
			else {$fehler = true;}
		}
	}

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);

  $sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM newslettertypen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
  $sql->bind_param("s", $bezeichnung);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl > 0) {die("DOPPELT");}}
    else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	if (!$fehler) {
  	// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('newslettertypen');
    $sql = $dbs->prepare("UPDATE newslettertypen SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), idvon = ? WHERE id = ?");
    $sql->bind_param("sii", $bezeichnung, $CMS_BENUTZERID, $id);
    $sql->execute();
    $sql->close();

    foreach($CMS_GRUPPEN as $g) {
			$ids = str_replace('|', ',', $zugeordnet[$g]);
      $gk = cms_textzudb($g);
      if (strlen($ids) > 0) {
				$ids = explode(',', substr($ids, 1));
        $sql = $dbs->prepare("INSERT INTO $gk"."newsletter (gruppe, newsletter) VALUES (?, ?)");
        foreach ($ids as $j) {
          $sql->bind_param("ii", $j, $id);
          $sql->execute();
				}
        $sql->close();
			}
    }

		echo "ERFOLG";

    if($CMS_RECHTE["Website"]["Newsletter bearbeiten"] || $CMS_RECHTE["Newsletter Empfängerliste sehen"])
      echo "cms_newsletter_details_vorbereiten($id, '$ziel')";
    else
      echo "cms_link('$ziel')";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
