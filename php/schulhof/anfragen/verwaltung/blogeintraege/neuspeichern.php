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

if (isset($_POST['oeffentlichkeit'])) {$oeffentlichkeit = $_POST['oeffentlichkeit'];} else {echo "FEHLER";exit;}
if (isset($_POST['notifikationen']))  {$notifikationen = $_POST['notifikationen'];}   else {echo "FEHLER";exit;}
if (isset($_POST['genehmigt'])) 			{$genehmigt = $_POST['genehmigt'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['aktiv'])) 			    {$aktiv = $_POST['aktiv'];} 						        else {echo "FEHLER";exit;}
if (isset($_POST['datumT'])) 				  {$datumT = $_POST['datumT'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['datumM'])) 				  {$datumM = $_POST['datumM'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['datumJ'])) 				  {$datumJ = $_POST['datumJ'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['bezeichnung'])) 		{$bezeichnung = $_POST['bezeichnung'];} 				else {echo "FEHLER";exit;}
if (isset($_POST['vorschaubild'])) 		{$vorschaubild = $_POST['vorschaubild'];} 			else {echo "FEHLER";exit;}
if (isset($_POST['zusammenfassung'])) {$zusammenfassung = $_POST['zusammenfassung'];} else {echo "FEHLER";exit;}
if (isset($_POST['autor'])) 		      {$autor = $_POST['autor'];} 			              else {echo "FEHLER";exit;}
if (isset($_POST['downloadanzahl']))  {$downloadanzahl = $_POST['downloadanzahl'];}   else {echo "FEHLER";exit;}
if (isset($_POST['downloadids']))     {$downloadids = $_POST['downloadids'];}         else {echo "FEHLER";exit;}
if (isset($_POST['artikellinkanzahl'])) {$artikellinkanzahl = $_POST['artikellinkanzahl'];} else {echo "FEHLER";exit;}
if (isset($_POST['artikellinkids']))    {$artikellinkids = $_POST['artikellinkids'];}       else {echo "FEHLER";exit;}
if (isset($_POST['inhalt'])) 					{$text = $_POST['inhalt'];} 										else {echo "FEHLER";exit;}

foreach($CMS_GRUPPEN as $g) {
  $gk = cms_textzudb($g);
  if (isset($_POST['z'.$gk])) {$zugeordnet[$g] = $_POST['z'.$gk];} else {echo "FEHLER";exit;}
}

if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER";exit;}


$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

if(!cms_check_ganzzahl($oeffentlichkeit, 0, 4)) {
  die("FEHLER");
}
$zugriff = false;
if (cms_r("artikel.$oeffentlichkeit.blogeinträge.anlegen")) {
	$zugriff = true;
}

if (!cms_r("artikel.genehmigen.blogeinträge")) {$genehmigt = '0';}


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

  $sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM blogeintraege WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ?");
  $sql->bind_param("si", $bezeichnung, $datum);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELT"; $fehler = true;}}
    else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();


  $downloads = array();
  if ($downloadanzahl > 0) {
		$dids = explode('|', $downloadids);
		$sqlwhere = substr(implode(' OR ',$dids), 4);

		for ($i=1; $i<count($dids); $i++) {
      $dd = array();
			if (isset($_POST["dtitel_".$dids[$i]])) {$dd['titel'] = $_POST["dtitel_".$dids[$i]];} else {echo "FEHLER"; exit;}
			if (strlen($dd['titel']) < 1) {$fehler = true; }

			if (isset($_POST["dbeschreibung_".$dids[$i]])) {$dd['beschreibung'] = $_POST["dbeschreibung_".$dids[$i]];} else {echo "FEHLER"; exit;}

			if (isset($_POST["dpfad_".$dids[$i]])) {$dd['pfad'] = $_POST["dpfad_".$dids[$i]];} else {echo "FEHLER"; exit;}
			if (!is_file('../../../'.$dd['pfad'])) {$fehler = true; }

			if (isset($_POST["ddateiname_".$dids[$i]])) {$dd['dateiname'] = $_POST["ddateiname_".$dids[$i]];} else {echo "FEHLER"; exit;}
			if (!cms_check_toggle($dd['dateiname'])) {$fehler = true; }

			if (isset($_POST["ddateigroesse_".$dids[$i]])) {$dd['dateigroesse'] = $_POST["ddateigroesse_".$dids[$i]];} else {echo "FEHLER"; exit;}
			if (!cms_check_toggle($dd['dateigroesse'])) {$fehler = true; }

      array_push($downloads, $dd);
		}
	}

  $artikellinks = array();
  if ($artikellinkanzahl > 0) {
		$lids = explode('|', $artikellinkids);
		$sqlwhere = substr(implode(' OR ', $lids), 4);

		for ($i=1; $i<count($lids); $i++) {
      $ll = array();
			if (isset($_POST["ltitel_".$lids[$i]])) {$ll['titel'] = $_POST["ltitel_".$lids[$i]];} else {echo "FEHLER"; exit;}
			if (strlen($ll['titel']) < 1) {$fehler = true; }

			if (isset($_POST["lbeschreibung_".$lids[$i]])) {$ll['beschreibung'] = $_POST["lbeschreibung_".$lids[$i]];} else {echo "FEHLER"; exit;}

			if (isset($_POST["llink_".$lids[$i]])) {$ll['link'] = $_POST["llink_".$lids[$i]];} else {echo "FEHLER"; exit;}
      if (strlen($ll['link']) < 1) {$fehler = true; }

      array_push($artikellinks, $ll);
		}
	}


	if (!$fehler) {
    $jetzt = time();
    $text = cms_texttrafo_e_db($text);
    $zusammenfassung = cms_texttrafo_e_db($zusammenfassung);

  	// NÄCHSTE FREIE ID SUCHEN
		$blogid = cms_generiere_kleinste_id('blogeintraege');
    $sql = $dbs->prepare("UPDATE blogeintraege SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), datum = ?, vorschau = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorschaubild = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), oeffentlichkeit = ?, genehmigt = ?, aktiv = ?, notifikationen = ?, text = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), autor = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), idvon = ?, idzeit = ? WHERE id = ?");
    $sql->bind_param("sissiiiissiii", $bezeichnung, $datum, $zusammenfassung, $vorschaubild, $oeffentlichkeit, $genehmigt, $aktiv, $notifikationen, $text, $autor, $CMS_BENUTZERID, $jetzt, $blogid);
    $sql->execute();
    $sql->close();

		// BLOGZUORDNUNGEN EINTRAGEN
		foreach($CMS_GRUPPEN as $g) {
			$ids = str_replace('|', ',', $zugeordnet[$g]);
      $gk = cms_textzudb($g);
      if (strlen($ids) > 0) {
				$ids = explode(',', substr($ids, 1));
        $sql = $dbs->prepare("INSERT INTO $gk"."blogeintraege (gruppe, blogeintrag) VALUES (?, ?)");
        foreach ($ids as $j) {
          $sql->bind_param("ii", $j, $blogid);
          $sql->execute();
				}
        $sql->close();
			}
    }

    // DOWNLOADS EINTRAGEN
    $sql = $dbs->prepare("UPDATE blogeintragdownloads SET blogeintrag = ?, pfad = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), dateiname = ?, dateigroesse = ? WHERE id = ?");
		foreach ($downloads as $d) {
			$d['titel'] = cms_texttrafo_e_db($d['titel']);
			$d['beschreibung'] = cms_texttrafo_e_db($d['beschreibung']);
			$did = cms_generiere_kleinste_id('blogeintragdownloads');
      $sql->bind_param("isssiii", $blogid, $d['pfad'], $d['titel'], $d['beschreibung'], $d['dateiname'], $d['dateigroesse'], $did);
      $sql->execute();
		}
    $sql->close();

    // LINKS EINTRAGEN
    $sql = $dbs->prepare("UPDATE blogeintraglinks SET blogeintrag = ?, link = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		foreach ($artikellinks as $l) {
			$l['titel'] = cms_texttrafo_e_db($l['titel']);
			$l['beschreibung'] = cms_texttrafo_e_db($l['beschreibung']);
			$did = cms_generiere_kleinste_id('blogeintraglinks');
      $sql->bind_param("isssi", $blogid, $l['link'], $l['titel'], $l['beschreibung'], $did);
      $sql->execute();
		}
    $sql->close();

    $monatsname = cms_monatsnamekomplett(date('m', $datum));
    $jahr = date('Y', $datum);
    $tag = date('d', $datum);
    $eintrag['gruppe']    = "Blogeinträge";
    $eintrag['gruppenid'] = $oeffentlichkeit;
    $eintrag['zielid']    = $blogid;
    $eintrag['status']    = "n";
    $eintrag['art']       = "b";
    $eintrag['titel']     = $bezeichnung;
    $eintrag['vorschau']  = cms_tagname(date('w', $datum))." $tag. ".$monatsname." $jahr";
    $eintrag['link']      = "Schulhof/Blog/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);

    if (($notifikationen) && ($aktiv)) {
  		$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');
  	  $CMS_MAIL = cms_einstellungen_laden('maileinstellungen');

      cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);
    }

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
