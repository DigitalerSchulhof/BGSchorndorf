<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';
session_start();

// Variablen einlesen, falls übergeben
$zugeordnet = array();

if (isset($_POST['notifikationen']))  {$notifikationen = $_POST['notifikationen'];}   else {echo "FEHLER";exit;}
if (isset($_POST['genehmigt'])) 			{$genehmigt = $_POST['genehmigt'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['aktiv'])) 			    {$aktiv = $_POST['aktiv'];} 						        else {echo "FEHLER";exit;}
if (isset($_POST['datumT'])) 				  {$datumT = $_POST['datumT'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['datumM'])) 				  {$datumM = $_POST['datumM'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['datumJ'])) 				  {$datumJ = $_POST['datumJ'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['bezeichnung'])) 		{$bezeichnung = $_POST['bezeichnung'];} 				else {echo "FEHLER";exit;}
if (isset($_POST['beschreibung']))    {$beschreibung = $_POST['beschreibung'];}       else {echo "FEHLER";exit;}
if (isset($_POST['autor'])) 		      {$autor = $_POST['autor'];} 			              else {echo "FEHLER";exit;}
if (isset($_POST['vorschaubild'])) 		{$vorschaubild = $_POST['vorschaubild'];}    		else {echo "FEHLER";exit;}
if (isset($_POST['bildanzahl']))      {$bildanzahl = $_POST['bildanzahl'];}           else {echo "FEHLER";exit;}
if (isset($_POST['bildids']))         {$bildids = $_POST['bildids'];}                 else {echo "FEHLER";exit;}

foreach($CMS_GRUPPEN as $g) {
  $gk = cms_textzudb($g);
  if (isset($_POST['z'.$gk])) {$zugeordnet[$g] = $_POST['z'.$gk];} else {echo "FEHLER";exit;}
}

if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER";exit;}
if (isset($_SESSION['GALERIEID'])) {$galerieid = $_SESSION['GALERIEID'];} else {echo "FEHLER";exit;}


$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

if (cms_r("artikel.galerien.bearbeiten")) {
	$zugriff = true;
}

if (!cms_r("artikel.genehmigen.galerien")) {$genehmigt = '0';}


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
  			$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM $gk WHERE id IN $ids");
  			if ($sql->execute()) {
          $sql->bind_result($checkanzahl);
  				if ($sql->fetch()) {
  					if ($checkanzahl != $anzahl) {$fehler = true;}
  				}
  				else {$fehler = true;}
  			}
  			else {$fehler = true;}
        $sql->close();
      }
			else {$fehler = true;}
		}
	}

  $datum = mktime(0, 0, 0, $datumM, $datumT, $datumJ);
  if (!$datum) {$fehler = false;}

	if (!cms_check_toggle($genehmigt)) {$fehler = true;}
  if (!cms_check_toggle($aktiv)) {$fehler = true;}
  if (!cms_check_toggle($notifikationen)) {$fehler = true;}

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);
	$autor = cms_texttrafo_e_db($autor);
	$vorschaubild = cms_texttrafo_e_db($vorschaubild);

  $sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM galerien WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ? AND id != ?");
  $sql->bind_param("sii", $bezeichnung, $datum, $galerieid);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELT"; $fehler = true;}}
    else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

  $bilder = array();
  if ($bildanzahl > 0) {
		$bids = explode('|', $bildids);
		$sqlwhere = substr(implode(' OR ',$bids), 4);

		for ($i=1; $i<count($bids); $i++) {
      $dd = array();
			if (isset($_POST["bbeschreibung_".$bids[$i]])) {$dd['beschreibung'] = $_POST["bbeschreibung_".$bids[$i]];} else {echo "FEHLER"; exit;}

			if (isset($_POST["bpfad_".$bids[$i]])) {$dd['pfad'] = $_POST["bpfad_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (!is_file('../../../'.$dd['pfad'])) {$fehler = true; }

      array_push($bilder, $dd);
		}
	}


	if (!$fehler) {
    $beschreibung = cms_texttrafo_e_db($beschreibung);

  	// GALERIE EINTRAGEN
    $sql = $dbs->prepare("UPDATE galerien SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), datum = ?, beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorschaubild = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), oeffentlichkeit = 4, genehmigt = ?, aktiv = ?, notifikationen = ?, autor = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), idvon = ? WHERE id = ?");
    $sql->bind_param("sissiiisii", $bezeichnung, $datum, $beschreibung, $vorschaubild, $genehmigt, $aktiv, $notifikationen, $autor, $CMS_BENUTZERID, $galerieid);
    $sql->execute();
    $sql->close();

    $sql = $dbs->prepare("DELETE FROM galerienbilder WHERE galerie = ?");
    $sql->bind_param("i", $galerieid);
    $sql->execute();
    $sql->close();

    $sql = $dbs->prepare("UPDATE galerienbilder SET galerie = ?, pfad = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		foreach ($bilder as $b) {
			$b['beschreibung'] = cms_texttrafo_e_db($b['beschreibung']);
			$bid = cms_generiere_kleinste_id('galerienbilder');
      $sql->bind_param("issi", $galerieid, $b['pfad'], $b['beschreibung'], $bid);
      $sql->execute();
		}
    $sql->close();


    foreach($CMS_GRUPPEN as $g) {
			$ids = str_replace('|', ',', $zugeordnet[$g]);
      $gk = cms_textzudb($g);
      $sql = $dbs->prepare("DELETE FROM $gk"."galerien WHERE galerie = ?");
      $sql->bind_param("i", $galerieid);
      $sql->execute();
      $sql->close();

			if (strlen($ids) > 0) {
				$ids = explode(',', substr($ids, 1));
        $sql = $dbs->prepare("INSERT INTO $gk"."galerien (gruppe, galerie) VALUES (?, ?)");
        foreach ($ids as $j) {
          $sql->bind_param("ii", $j, $galerieid);
          $sql->execute();
				}
        $sql->close();
			}
    }

    $monatsname = cms_monatsnamekomplett(date('m', $datum));
    $jahr = date('Y', $datum);
    $tag = date('d', $datum);
    $eintrag['gruppe']    = "Galerien";
    $eintrag['gruppenid'] = 4;
    $eintrag['zielid']    = $galerieid;
    $eintrag['status']    = "b";
    $eintrag['art']       = "g";
    $eintrag['titel']     = $bezeichnung;
    $eintrag['vorschau']  = cms_tagname(date('w', $datum))." $tag. ".$monatsname." $jahr";
    $eintrag['link']      = "Schulhof/Galerien/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);
    if($notifikationen)
      cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);

		echo "ERFOLG";
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
