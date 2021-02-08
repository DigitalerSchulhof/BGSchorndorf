<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

if (isset($_POST['aktiv'])) {$aktiv = cms_texttrafo_e_db($_POST['aktiv']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorbeginnT'])) {$vorbeginnT = cms_texttrafo_e_db($_POST['vorbeginnT']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorbeginnM'])) {$vorbeginnM = cms_texttrafo_e_db($_POST['vorbeginnM']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorbeginnJ'])) {$vorbeginnJ = cms_texttrafo_e_db($_POST['vorbeginnJ']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorbeginns'])) {$vorbeginns = cms_texttrafo_e_db($_POST['vorbeginns']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorbeginnm'])) {$vorbeginnm = cms_texttrafo_e_db($_POST['vorbeginnm']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorendeT'])) {$vorendeT = cms_texttrafo_e_db($_POST['vorendeT']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorendeM'])) {$vorendeM = cms_texttrafo_e_db($_POST['vorendeM']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorendeJ'])) {$vorendeJ = cms_texttrafo_e_db($_POST['vorendeJ']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorendes'])) {$vorendes = cms_texttrafo_e_db($_POST['vorendes']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorendem'])) {$vorendem = cms_texttrafo_e_db($_POST['vorendem']);} else {echo "FEHLER"; exit;}
if (isset($_POST['persnoetig'])) {$persnoetig = cms_texttrafo_e_db($_POST['persnoetig']);} else {echo "FEHLER"; exit;}
if (isset($_POST['perbeginnT'])) {$perbeginnT = cms_texttrafo_e_db($_POST['perbeginnT']);} else {echo "FEHLER"; exit;}
if (isset($_POST['perbeginnM'])) {$perbeginnM = cms_texttrafo_e_db($_POST['perbeginnM']);} else {echo "FEHLER"; exit;}
if (isset($_POST['perbeginnJ'])) {$perbeginnJ = cms_texttrafo_e_db($_POST['perbeginnJ']);} else {echo "FEHLER"; exit;}
if (isset($_POST['perendeT'])) {$perendeT = cms_texttrafo_e_db($_POST['perendeT']);} else {echo "FEHLER"; exit;}
if (isset($_POST['perendeM'])) {$perendeM = cms_texttrafo_e_db($_POST['perendeM']);} else {echo "FEHLER"; exit;}
if (isset($_POST['perendeJ'])) {$perendeJ = cms_texttrafo_e_db($_POST['perendeJ']);} else {echo "FEHLER"; exit;}
if (isset($_POST['ueberhang'])) {$ueberhang = cms_texttrafo_e_db($_POST['ueberhang']);} else {echo "FEHLER"; exit;}
if (isset($_POST['eintritt'])) {$eintritt = cms_texttrafo_e_db($_POST['eintritt']);} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulung'])) {$einschulung = cms_texttrafo_e_db($_POST['einschulung']);} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = cms_texttrafo_e_db($_POST['klasse']);} else {echo "FEHLER"; exit;}
if (isset($_POST['einleitung'])) {$einleitung = cms_texttrafo_e_db($_POST['einleitung']);} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.organisation.schulanmeldung.vorbereiten")) {
	$fehler = false;

	$vorbeginn = mktime($vorbeginns, $vorbeginnm, 0, $vorbeginnM, $vorbeginnT, $vorbeginnJ);
  $vorende = mktime($vorendes, $vorendem, 59, $vorendeM, $vorendeT, $vorendeJ);
	$perbeginn = mktime(0, 0, 0, $perbeginnM, $perbeginnT, $perbeginnJ);
  $perende = mktime(23, 59, 59, $perendeM, $perendeT, $perendeJ);
	if (!cms_check_toggle($aktiv)) {$fehler = true;}
	if (!cms_check_toggle($persnoetig)) {$fehler = true;}
  if ($vorbeginn-$vorende >= 0) {$fehler = true;}
	if ($persnoetig == '1') {
		if ($perbeginn-$perende >= 0) {$fehler = true;}
	}
  if (!cms_check_ganzzahl($ueberhang, 1, 1000)) {$fehler = true;}
  if (!cms_check_ganzzahl($eintritt, 1, 100)) {$fehler = true;}
  if (!cms_check_ganzzahl($einschulung, 1, 100)) {$fehler = true;}
  if (!cms_check_ganzzahl($klasse, 1, 20)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("UPDATE schulanmeldung SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$einstellungsname = 'Anmeldung aktiv';
	  $sql->bind_param("ss", $aktiv, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung von';
	  $sql->bind_param("ss", $vorbeginn, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung bis';
	  $sql->bind_param("ss", $vorende, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung Überhang Tage';
	  $sql->bind_param("ss", $ueberhang, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung Eintrittsalter';
	  $sql->bind_param("ss", $eintritt, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung Einschulungsalter';
	  $sql->bind_param("ss", $einschulung, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung Klassenstufe';
	  $sql->bind_param("ss", $klasse, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung persönlich von';
	  $sql->bind_param("ss", $perbeginn, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Persönlich nötig';
	  $sql->bind_param("ss", $persnoetig, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung persönlich bis';
	  $sql->bind_param("ss", $perende, $einstellungsname);
	  $sql->execute();

		$einstellungsname = 'Anmeldung Einleitung';
	  $sql->bind_param("ss", $einleitung, $einstellungsname);
	  $sql->execute();
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
?>
