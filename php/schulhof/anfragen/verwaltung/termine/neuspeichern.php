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
if (isset($_POST['mehrtaegigt'])) 		{$mehrtaegigt = $_POST['mehrtaegigt'];} 				else {echo "FEHLER";exit;}
if (isset($_POST['uhrzeitbt'])) 			{$uhrzeitbt = $_POST['uhrzeitbt'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['uhrzeitet'])) 			{$uhrzeitet = $_POST['uhrzeitet'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['ortt'])) 						{$ortt = $_POST['ortt'];} 											else {echo "FEHLER";exit;}
if (isset($_POST['beginnT'])) 				{$beginnT = $_POST['beginnT'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnM'])) 				{$beginnM = $_POST['beginnM'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnJ'])) 				{$beginnJ = $_POST['beginnJ'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnh'])) 				{$beginnh = $_POST['beginnh'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnm'])) 				{$beginnm = $_POST['beginnm'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['endeT'])) 					{$endeT = $_POST['endeT'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['endeM'])) 					{$endeM = $_POST['endeM'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['endeJ'])) 					{$endeJ = $_POST['endeJ'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['endeh'])) 					{$endeh = $_POST['endeh'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['endem'])) 					{$endem = $_POST['endem'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['bezeichnung'])) 		{$bezeichnung = $_POST['bezeichnung'];} 				else {echo "FEHLER";exit;}
if (isset($_POST['ort'])) 						{$ort = $_POST['ort'];} 												else {echo "FEHLER";exit;}
if (isset($_POST['downloadanzahl']))  {$downloadanzahl = $_POST['downloadanzahl'];}   else {echo "FEHLER";exit;}
if (isset($_POST['downloadids']))     {$downloadids = $_POST['downloadids'];}         else {echo "FEHLER";exit;}

foreach($CMS_GRUPPEN as $g) {
  $gk = cms_textzudb($g);
  if (isset($_POST['z'.$gk])) {$zugeordnet[$g] = $_POST['z'.$gk];} else {echo "FEHLER";exit;}
}
if (isset($_POST['wdh'])) 	          {$wdh = $_POST['wdh'];} 	                else {echo "FEHLER";exit;}
if (isset($_POST['inhalt'])) 					{$text = $_POST['inhalt'];} 										else {echo "FEHLER";exit;}

if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if ($CMS_RECHTE['Website']['Termine anlegen']) {
	$zugriff = true;
}

if (!r("artikel.genehmigen.termine")) {$genehmigt = '0';}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Prüfen, ob die zugeordneten Gruppen existieren
	$dbs = cms_verbinden('s');
	foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
		$ids = str_replace('|', ',', $zugeordnet[$g]);
		if (strlen($ids) > 0) {
      $ids = substr($ids, 1);
      $idsk = "(".$ids.")";
      if (cms_check_idliste($idsk)) {
        $anzahl = count(explode(',', $ids));
  			$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM $gk WHERE id IN $idsk");
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

	if ($mehrtaegigt == 1) {
		$beginnd = mktime(0, 0, 0, $beginnM, $beginnT, $beginnJ);
		$ended = mktime(23, 59, 59, $endeM, $endeT, $endeJ);
		if ($uhrzeitbt == 1) {$beginnd = mktime($beginnh, $beginnm, 0, $beginnM, $beginnT, $beginnJ);}
		if ($uhrzeitet == 1) {$ended = mktime($endeh, $endem, 59, $endeM, $endeT, $endeJ);}
	}
	else {
		$beginnd = mktime(0, 0, 0, $beginnM, $beginnT, $beginnJ);
		$ended = mktime(23, 59, 59, $beginnM, $beginnT, $beginnJ);
		if ($uhrzeitbt == 1) {$beginnd = mktime($beginnh, $beginnm, 0, $beginnM, $beginnT, $beginnJ);}
		if ($uhrzeitet == 1) {$ended = mktime($endeh, $endem, 59, $beginnM, $beginnT, $beginnJ);}
	}

  $BEGINN[0] = $beginnd;
  $ENDE[0] = $ended;
  $dauerd = $ENDE[0] - $BEGINN[0];
  // Prüfen, ob der Termin wiederholt werden soll und füge ggf weitere Daten in die Reihe an
  if (strlen($wdh) > 0) {
    $wdh = explode('|', substr($wdh,1));
    if ($wdh[0] == $BEGINN[0]) {
      for ($i=1; $i<count($wdh); $i++) {
        $BEGINN[$i] = $wdh[$i];
        $ENDE[$i] = $wdh[$i] + $dauerd;
      }
    }
  }

	if ($beginnd-$ended >= 0) {
		$fehler = true;
	}

	if ($ortt == 1) {
		if (strlen($ort) == 0) {
			$fehler = true;
		}
	}

	if (($oeffentlichkeit != 0) && ($oeffentlichkeit != 1) && ($oeffentlichkeit != 2) && ($oeffentlichkeit != 3) && ($oeffentlichkeit != 4)) {
		$fehler = true;
	}

  if (!cms_check_toggle($genehmigt)) {$fehler = true;}
  if (!cms_check_toggle($aktiv)) {$fehler = true;}
  if (!cms_check_toggle($notifikationen)) {$fehler = true;}

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);

  $sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM termine WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND (beginn BETWEEN ? AND ?)");
  for ($i = 0; $i<count($BEGINN); $i++) {
  	$zbeginnt = date('j', $BEGINN[$i]);
  	$zbeginnm = date('n', $BEGINN[$i]);
  	$zbeginnj = date('Y', $BEGINN[$i]);
  	$zbeginn = mktime(0,0,0,$zbeginnm,$zbeginnt,$zbeginnj);
  	$zende = mktime(0,0,0,$zbeginnm,$zbeginnt+1,$zbeginnj)-1;

  	$sql->bind_param("sii", $bezeichnung, $zbeginn, $zende);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELT"; $fehler = true;}}
  		else {$fehler = true;}
    }
    else {$fehler = true;}

  }
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



	if (!$fehler) {
    $ort = cms_texttrafo_e_db($ort);
    $text = cms_texttrafo_e_db($text);

    for ($i = 0; $i<count($BEGINN); $i++) {
			// NÄCHSTE FREIE ID SUCHEN
			$terminid = cms_generiere_kleinste_id('termine');
			// TERMIN EINTRAGEN
      $jetzt = time();
      $sql = $dbs->prepare("UPDATE termine SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ?, mehrtaegigt = ?, uhrzeitbt = ?, uhrzeitet = ?, ortt = ?, oeffentlichkeit = ?, notifikationen = ?, genehmigt = ?, aktiv = ?, text = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), idvon = ?, idzeit = ? WHERE id = ?");
      $sql->bind_param("ssiiiiiiiiiisiii", $bezeichnung, $ort, $BEGINN[$i], $ENDE[$i], $mehrtaegigt, $uhrzeitbt, $uhrzeitet, $ortt, $oeffentlichkeit, $notifikationen, $genehmigt, $aktiv, $text, $CMS_BENUTZERID, $jetzt, $terminid);
      $sql->execute();
      $sql->close();

			// TERMINZUORDNUNGEN EINTRAGEN
			foreach($CMS_GRUPPEN as $g) {
				$ids = str_replace('|', ',', $zugeordnet[$g]);
        $gk = cms_textzudb($g);
				if (strlen($ids) > 0) {
					$ids = explode(',', substr($ids, 1));
          $sql = $dbs->prepare("INSERT INTO $gk"."termine (gruppe, termin) VALUES (?, ?)");
  				foreach ($ids as $j) {
  					$sql->bind_param("ii", $j, $terminid);
            $sql->execute();
  				}
          $sql->close();
				}
			}

      // DOWNLOADS EINTRAGEN
      $sql = $dbs->prepare("UPDATE terminedownloads SET termin = ?, pfad = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), dateiname = ?, dateigroesse = ? WHERE id = ?");
			foreach ($downloads as $d) {
				$d['titel'] = cms_texttrafo_e_db($d['titel']);
				$d['beschreibung'] = cms_texttrafo_e_db($d['beschreibung']);
				$did = cms_generiere_kleinste_id('terminedownloads');
        $sql->bind_param("isssiii", $terminid, $d['pfad'], $d['titel'], $d['beschreibung'], $d['dateiname'], $d['dateigroesse'], $did);
        $sql->execute();
			}
      $sql->close();

      $monatsname = cms_monatsnamekomplett(date('m', $BEGINN[$i]));
      $jahr = date('Y', $BEGINN[$i]);
      $tag = date('d', $BEGINN[$i]);
      $eintrag['gruppe']    = "Termine";
      $eintrag['gruppenid'] = $oeffentlichkeit;
      $eintrag['zielid']    = $terminid;
      $eintrag['status']    = "n";
      $eintrag['art']       = "t";
      $eintrag['titel']     = $bezeichnung;
      $eintrag['vorschau']  = cms_tagname(date('w', $BEGINN[$i]))." $tag. ".$monatsname." $jahr";
      $eintrag['link']      = "Schulhof/Termine/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);
      if (($notifikationen) && ($aktiv))
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
