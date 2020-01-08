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
if (isset($_POST['inhalt'])) 					{$text = $_POST['inhalt'];} 										else {echo "FEHLER";exit;}
if (isset($_SESSION['INTERNERTERMINGRUPPE'])) {$gruppe = $_SESSION['INTERNERTERMINGRUPPE'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['INTERNERTERMINGRUPPENID'])) {$gruppenid = $_SESSION['INTERNERTERMINGRUPPENID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['TERMININTERNID'])) {$terminid = $_SESSION['TERMININTERNID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($terminid,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($gruppenid,0)) {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($gruppe)) {echo "FEHLER";exit;}

$gk = cms_textzudb($gruppe);

cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$dbs = cms_verbinden('s');
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);

$zugriff = $CMS_GRUPPENRECHTE['termine'] || cms_r("schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen"));

if (($CMS_EINSTELLUNGEN['Genehmigungen '.$gruppe.' Termine'] == 1) && (!cms_r("schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen")))) {$genehmigt = '0';}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

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

  $BEGINN = $beginnd;
  $ENDE = $ended;

  if (!$BEGINN) {$fehler = false;}
  if (!$ENDE) {$fehler = false;}

	if ($beginnd-$ended >= 0) {
		$fehler = true;
	}

	if ($ortt == 1) {
		if (strlen($ort) == 0) {
			$fehler = true;
		}
	}

  if (!cms_check_toggle($genehmigt)) {$fehler = true;}
  if (!cms_check_toggle($aktiv)) {$fehler = true;}
  if (!cms_check_toggle($notifikationen)) {$fehler = true;}

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);

	$zbeginnt = date('j', $BEGINN);
	$zbeginnm = date('n', $BEGINN);
	$zbeginnj = date('Y', $BEGINN);
	$zbeginn = mktime(0,0,0,$zbeginnm,$zbeginnt,$zbeginnj);
	$zende = mktime(0,0,0,$zbeginnm,$zbeginnt+1,$zbeginnj)-1;

	// Prüfen, ob am Tag des Termins bereits eine Termin mit diesem Titel existiert
  $sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM $gk"."termineintern WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND (beginn BETWEEN ? AND ?) AND id != ?");
  $sql->bind_param("siii", $bezeichnung, $zbeginn, $zende, $terminid);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELT"; $fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

  // Gruppe laden
	$sql = $dbs->prepare("SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') as grbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.id = ?");
	$sql->bind_param("i", $gruppenid);
	$gruppensj = "Schuljahrübergreifend";
	$gruppenbez = "";
	if ($sql->execute()) {
		$sql->bind_result($sjbez, $grbez);
		if ($sql->fetch()) {
			if (!is_null($sjbez)) {$gruppensj = cms_textzulink($sjbez);}
			$gruppenbez = cms_textzulink($grbez);
		}
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
      if (strlen($dd['titel']) < 1) {$fehler = true;}

      if (isset($_POST["dbeschreibung_".$dids[$i]])) {$dd['beschreibung'] = $_POST["dbeschreibung_".$dids[$i]];} else {echo "FEHLER"; exit;}

      if (isset($_POST["dpfad_".$dids[$i]])) {$dd['pfad'] = $_POST["dpfad_".$dids[$i]];} else {echo "FEHLER"; exit;}
      if (!is_file('../../../'.$dd['pfad'])) {$fehler = true;}

      if (isset($_POST["ddateiname_".$dids[$i]])) {$dd['dateiname'] = $_POST["ddateiname_".$dids[$i]];} else {echo "FEHLER"; exit;}
      if (!cms_check_toggle($dd['dateiname'])) {$fehler = true;}

      if (isset($_POST["ddateigroesse_".$dids[$i]])) {$dd['dateigroesse'] = $_POST["ddateigroesse_".$dids[$i]];} else {echo "FEHLER"; exit;}
      if (!cms_check_toggle($dd['dateigroesse'])) {$fehler = true;}

      array_push($downloads, $dd);
    }
  }

	if (!$fehler) {
    $ort = cms_texttrafo_e_db($ort);
    $text = cms_texttrafo_e_db($text);
		$jetzt = time();
  	// TERMIN EINTRAGEN
    $sql = $dbs->prepare("UPDATE $gk"."termineintern SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ?, mehrtaegigt = ?, uhrzeitbt = ?, uhrzeitet = ?, ortt = ?, genehmigt = ?, notifikationen = ?, aktiv = ?, text = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), idvon = ?, idzeit = ? WHERE id = ?");
    $sql->bind_param("ssiiiiiiiiisiii", $bezeichnung, $ort, $BEGINN, $ENDE, $mehrtaegigt, $uhrzeitbt, $uhrzeitet, $ortt, $genehmigt, $notifikationen, $aktiv, $text, $CMS_BENUTZERID, $jetzt, $terminid);
    $sql->execute();
    $sql->close();

    $sql = $dbs->prepare("DELETE FROM $gk"."termineinterndownloads WHERE termin = ?");
    $sql->bind_param("i", $terminid);
    $sql->execute();
    $sql->close();

    // DOWNLOADS EINTRAGEN
    $sql = $dbs->prepare("UPDATE $gk"."termineinterndownloads SET termin = ?, pfad = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), dateiname = ?, dateigroesse = ? WHERE id = ?");
    foreach ($downloads as $d) {
      $d['titel'] = cms_texttrafo_e_db($d['titel']);
      $d['beschreibung'] = cms_texttrafo_e_db($d['beschreibung']);
      $did = cms_generiere_kleinste_id('terminedownloads');
      $sql->bind_param("isssiii", $terminid, $d['pfad'], $d['titel'], $d['beschreibung'], $d['dateiname'], $d['dateigroesse'], $did);
      $sql->execute();
    }
    $sql->close();

    $monatsname = cms_monatsnamekomplett(date('m', $BEGINN));
    $jahr = date('Y', $BEGINN);
    $tag = date('d', $BEGINN);
    $eintrag['gruppe']    = $gruppe;
    $eintrag['gruppenid'] = $gruppenid;
    $eintrag['zielid']    = $terminid;
    $eintrag['status']    = "b";
    $eintrag['art']       = "t";
    $eintrag['titel']     = $bezeichnung;
    $eintrag['vorschau']  = cms_tagname(date('w', $BEGINN))." $tag. ".$monatsname." $jahr";
    $eintrag['link']      = "Schulhof/Gruppen/$gruppensj/".cms_textzulink($gruppe)."/$gruppenbez/Termine/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);
		if($notifikationen)
    	cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);

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
