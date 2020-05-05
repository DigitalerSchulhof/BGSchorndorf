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
if (isset($_POST['notifikationen']))  	{$notifikationen = $_POST['notifikationen'];}   			else {echo "FEHLER";exit;}
if (isset($_POST['genehmigt'])) 			  {$genehmigt = $_POST['genehmigt'];} 						      else {echo "FEHLER";exit;}
if (isset($_POST['aktiv'])) 			      {$aktiv = $_POST['aktiv'];} 						              else {echo "FEHLER";exit;}
if (isset($_POST['datumT'])) 				    {$datumT = $_POST['datumT'];} 								        else {echo "FEHLER";exit;}
if (isset($_POST['datumM'])) 				    {$datumM = $_POST['datumM'];} 								        else {echo "FEHLER";exit;}
if (isset($_POST['datumJ'])) 				    {$datumJ = $_POST['datumJ'];} 								        else {echo "FEHLER";exit;}
if (isset($_POST['bezeichnung'])) 		  {$bezeichnung = $_POST['bezeichnung'];} 				      else {echo "FEHLER";exit;}
if (isset($_POST['zusammenfassung']))   {$zusammenfassung = $_POST['zusammenfassung'];}       else {echo "FEHLER";exit;}
if (isset($_POST['autor'])) 		        {$autor = $_POST['autor'];} 			                    else {echo "FEHLER";exit;}
if (isset($_POST['downloadanzahl']))    {$downloadanzahl = $_POST['downloadanzahl'];}         else {echo "FEHLER";exit;}
if (isset($_POST['downloadids']))       {$downloadids = $_POST['downloadids'];}               else {echo "FEHLER";exit;}
if (isset($_POST['artikellinkanzahl'])) {$artikellinkanzahl = $_POST['artikellinkanzahl'];} else {echo "FEHLER";exit;}
if (isset($_POST['artikellinkids']))    {$artikellinkids = $_POST['artikellinkids'];}       else {echo "FEHLER";exit;}
if (isset($_POST['beschluesseanzahl'])) {$beschluesseanzahl = $_POST['beschluesseanzahl'];}   else {echo "FEHLER";exit;}
if (isset($_POST['beschluesseids']))    {$beschluesseids = $_POST['beschluesseids'];}         else {echo "FEHLER";exit;}
if (isset($_POST['inhalt'])) 					  {$text = $_POST['inhalt'];} 										      else {echo "FEHLER";exit;}
if (isset($_SESSION['INTERNERBLOGGRUPPE'])) {$gruppe = $_SESSION['INTERNERBLOGGRUPPE'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['INTERNERBLOGGRUPPENID'])) {$gruppenid = $_SESSION['INTERNERBLOGGRUPPENID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BLOGEINTRAGINTERNID'])) {$blogid = $_SESSION['BLOGEINTRAGINTERNID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($blogid,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($gruppenid,0)) {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($gruppe)) {echo "FEHLER";exit;}

$gk = cms_textzudb($gruppe);


$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

$dbs = cms_verbinden('s');
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);

$zugriff = $CMS_GRUPPENRECHTE['blogeintraege'] || cms_r("schulhof.gruppen.%GRUPPEN%.artikel.blogeinträge.genehmigen");

if (($CMS_EINSTELLUNGEN['Genehmigungen '.$gruppe.' Blogeinträge'] == 1) && (!cms_r("schulhof.gruppen.%GRUPPEN%.artikel.blogeinträge.genehmigen"))) {$genehmigt = '0';}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	$datum = mktime(0, 0, 0, $datumM, $datumT, $datumJ);
  if (!$datum) {$fehler = false;}

	if (!cms_check_toggle($genehmigt)) {$fehler = true;}
  if (!cms_check_toggle($aktiv)) {$fehler = true;}
  if (!cms_check_toggle($notifikationen)) {$fehler = true;}

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);
	$autor = cms_texttrafo_e_db($autor);

	$sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM $gk"."blogeintraegeintern WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ? AND id != ?");
  $sql->bind_param("sii", $bezeichnung, $datum, $blogid);
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

  $beschluesse = array();
  if ($beschluesseanzahl > 0) {
		$bids = explode('|', $beschluesseids);
		$sqlwhere = substr(implode(' OR ',$bids), 4);

		for ($i=1; $i<count($bids); $i++) {
      $bb = array();
			if (isset($_POST["btitel_".$bids[$i]])) {$bb['titel'] = $_POST["btitel_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (strlen($bb['titel']) < 1) {$fehler = true; }

			if (isset($_POST["bbeschreibung_".$bids[$i]])) {$bb['beschreibung'] = $_POST["bbeschreibung_".$bids[$i]];} else {echo "FEHLER"; exit;}

			if (isset($_POST["blangfristig_".$bids[$i]])) {$bb['langfristig'] = $_POST["blangfristig_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (!cms_check_toggle($bb['langfristig'])) {$fehler = true; }

			if (isset($_POST["bpro_".$bids[$i]])) {$bb['pro'] = $_POST["bpro_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (!cms_check_ganzzahl($bb['pro'],0)) {$fehler = true;}
			if (isset($_POST["benthaltung_".$bids[$i]])) {$bb['enthaltung'] = $_POST["benthaltung_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (!cms_check_ganzzahl($bb['enthaltung'],0)) {$fehler = true;}
			if (isset($_POST["bcontra_".$bids[$i]])) {$bb['contra'] = $_POST["bcontra_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (!cms_check_ganzzahl($bb['contra'],0)) {$fehler = true;}

      array_push($beschluesse, $bb);
		}
	}

	if (!$fehler) {
    $text = cms_texttrafo_e_db($text);
		$jetzt = time();
		// BLOGEINTRAG EINTRAGEN
		$sql = $dbs->prepare("UPDATE $gk"."blogeintraegeintern SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), datum = ?, vorschau = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), gruppe = ?, genehmigt = ?, aktiv = ?, notifikationen = ?, text = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), autor = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), idvon = ?, idzeit = ? WHERE id = ?");
	  $sql->bind_param("sisiiiissiii", $bezeichnung, $datum, $zusammenfassung, $gruppenid, $genehmigt, $aktiv, $notifikationen, $text, $autor, $CMS_BENUTZERID, $jetzt, $blogid);
	  $sql->execute();
	  $sql->close();

    // DOWNLOADS EINTRAGEN
    $sql = $dbs->prepare("DELETE FROM $gk"."blogeintragdownloads WHERE blogeintrag = ?;");
    $sql->bind_param("i", $blogid);
    $sql->execute();
    $sql->close();
		$sql = $dbs->prepare("UPDATE $gk"."blogeintragdownloads SET blogeintrag = ?, pfad = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), dateiname = ?, dateigroesse = ? WHERE id = ?");
		foreach ($downloads as $d) {
			$d['titel'] = cms_texttrafo_e_db($d['titel']);
			$d['beschreibung'] = cms_texttrafo_e_db($d['beschreibung']);
			$did = cms_generiere_kleinste_id($gk.'blogeintragdownloads');
			$sql->bind_param("isssiii", $blogid, $d['pfad'], $d['titel'], $d['beschreibung'], $d['dateiname'], $d['dateigroesse'], $did);
		  $sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("DELETE FROM {$gk}blogeintraglinks WHERE blogeintrag = ?");
    $sql->bind_param("i", $blogid);
    $sql->execute();
    $sql->close();

    // LINKS EINTRAGEN
    $sql = $dbs->prepare("UPDATE {$gk}blogeintraglinks SET blogeintrag = ?, link = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		foreach ($artikellinks as $l) {
			$l['titel'] = cms_texttrafo_e_db($l['titel']);
			$l['beschreibung'] = cms_texttrafo_e_db($l['beschreibung']);
			$did = cms_generiere_kleinste_id($gk.'blogeintraglinks');
      $sql->bind_param("isssi", $blogid, $l['link'], $l['titel'], $l['beschreibung'], $did);
      $sql->execute();
		}
    $sql->close();

    // BESCHLÜSSE EINTRAGEN
    $sql = $dbs->prepare("DELETE FROM $gk"."blogeintragbeschluesse WHERE blogeintrag = ?;");
    $sql->bind_param("i", $blogid);
    $sql->execute();
    $sql->close();
		$sql = $dbs->prepare("UPDATE $gk"."blogeintragbeschluesse SET blogeintrag = ?, langfristig = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), pro = ?, enthaltung = ?, contra = ? WHERE id = ?");
		foreach ($beschluesse as $b) {
			$b['titel'] = cms_texttrafo_e_db($b['titel']);
			$b['beschreibung'] = cms_texttrafo_e_db($b['beschreibung']);
			$bid = cms_generiere_kleinste_id($gk.'blogeintragbeschluesse');
			$sql->bind_param("isssiiii", $blogid, $b['langfristig'], $b['titel'], $b['beschreibung'], $b['pro'], $b['enthaltung'], $b['contra'], $bid);
		  $sql->execute();
		}
		$sql->close();

    $monatsname = cms_monatsnamekomplett(date('m', $datum));
    $jahr = date('Y', $datum);
    $tag = date('d', $datum);
    $eintrag['gruppe']    = $gruppe;
    $eintrag['gruppenid'] = $gruppenid;
    $eintrag['zielid']    = $blogid;
    $eintrag['status']    = "b";
    $eintrag['art']       = "b";
    $eintrag['titel']     = $bezeichnung;
    $eintrag['vorschau']  = cms_tagname(date('w', $datum))." $tag. ".$monatsname." $jahr";
    $eintrag['link']      = "Schulhof/Gruppen/$gruppensj/".cms_textzulink($gruppe)."/$gruppenbez/Blog/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);

		if($notifikationen && ($aktiv == 1)) {
			// ToDo Eintragen
			$sql = "INSERT INTO {$gk}todoartikel (person, blogeintrag, termin) SELECT abo.person, ?, NULL FROM {$gk}notifikationsabo as abo WHERE abo.gruppe = ? AND abo.person != ? AND NOT EXISTS(SELECT todo.blogeintrag FROM {$gk}todoartikel as todo WHERE todo.person = abo.person AND todo.blogeintrag = ?)";
			$sql = $dbs->prepare($sql);//";
			$sql->bind_param("iiii", $blogid, $gruppenid, $CMS_BENUTZERID, $blogid);
			$sql->execute();
			$sql->close();

    	cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);
		}
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
