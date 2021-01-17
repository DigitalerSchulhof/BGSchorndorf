<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
if (isset($_POST['aktiv'])) {$aktiv = $_POST['aktiv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['position'])) {$position = $_POST['position'];} else {echo "FEHLER"; exit;}
if (isset($_POST['termine'])) {$termine = $_POST['termine'];} else {echo "FEHLER"; exit;}
if (isset($_POST['termineanzahl'])) {$termineanzahl = $_POST['termineanzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['blog'])) {$blog = $_POST['blog'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bloganzahl'])) {$bloganzahl = $_POST['bloganzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['blogart'])) {$blogart = $_POST['blogart'];} else {echo "FEHLER"; exit;}
if (isset($_POST['galerie'])) {$galerie = $_POST['galerie'];} else {echo "FEHLER"; exit;}
if (isset($_POST['galerieanzahl'])) {$galerieanzahl = $_POST['galerieanzahl'];} else {echo "FEHLER"; exit;}

if (isset($_POST['breaking'])) {$breaking = $_POST['breaking'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingl1'])) {$breakingl1 = $_POST['breakingl1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingt1'])) {$breakingt1 = $_POST['breakingt1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingl2'])) {$breakingl2 = $_POST['breakingl2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingt2'])) {$breakingt2 = $_POST['breakingt2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingl3'])) {$breakingl3 = $_POST['breakingl3'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingt3'])) {$breakingt3 = $_POST['breakingt3'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingl4'])) {$breakingl4 = $_POST['breakingl4'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingt4'])) {$breakingt4 = $_POST['breakingt4'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingl5'])) {$breakingl5 = $_POST['breakingl5'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breakingt5'])) {$breakingt5 = $_POST['breakingt5'];} else {echo "FEHLER"; exit;}

if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("website.elemente.eventübersicht.anlegen")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (($breaking != 0) && ($termine != 1)) {$fehler = true;}
	if (($termine != 0) && ($termine != 1)) {$fehler = true;}
	if (($blog != 0) && ($blog != 1)) {$fehler = true;}
	if (($blogart != 'a') && ($blogart != 'd') && ($blogart != 'l')) {$fehler = true;}
	if (($galerie != 0) && ($galerie != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($termineanzahl,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($bloganzahl,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($galerieanzahl,0)) {$fehler = true;}

	if ($termine == '0') {$termineanzahl = '10';}
	if ($blog == '0') {$bloganzahl = '5'; $blogart = 'a';}
	if ($galerie == '0') {$galerieanzahl = '5';}
	if ($breaking == '0') {
    $breakingl1 = '';
    $breakingl2 = '';
    $breakingl3 = '';
    $breakingl4 = '';
    $breakingl5 = '';
    $breakingt1 = '';
    $breakingt2 = '';
    $breakingt3 = '';
    $breakingt4 = '';
    $breakingt5 = '';
  }

	if (!cms_r("website.freigeben")) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('eventuebersichten');
		if ($id == '-') {$fehler = true;}
	}

	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_einfuegen($dbs, $spalte, $position);
		$sql = $dbs->prepare("UPDATE eventuebersichten SET spalte = ?, position = ?, aktiv = ?, terminealt = ?, termineaktuell = ?, termineneu = ?, termineanzahlalt = ?, termineanzahlaktuell = ?, termineanzahlneu = ?, blogalt = ?, blogaktuell = ?, blogneu = ?, bloganzahlalt = ?, bloganzahlaktuell = ?, bloganzahlneu = ?, blogartalt = ?, blogartaktuell = ?, blogartneu = ?, galeriealt = ?, galerieaktuell = ?, galerieneu = ?, galerieanzahlalt = ?, galerieanzahlaktuell = ?, galerieanzahlneu = ?, breakingalt = ?, breakingaktuell = ?, breakingneu = ?, breakinglink1alt = ?, breakinglink1aktuell = ?, breakinglink1neu = ?, breakinglink2alt = ?, breakinglink2aktuell = ?, breakinglink2neu = ?, breakinglink3alt = ?, breakinglink3aktuell = ?, breakinglink3neu = ?, breakinglink4alt = ?, breakinglink4aktuell = ?, breakinglink4neu = ?, breakinglink5alt = ?, breakinglink5aktuell = ?, breakinglink5neu = ?, breakingtext1alt = ?, breakingtext1aktuell = ?, breakingtext1neu = ?, breakingtext2alt = ?, breakingtext2aktuell = ?, breakingtext2neu = ?, breakingtext3alt = ?, breakingtext3aktuell = ?, breakingtext3neu = ?, breakingtext4alt = ?, breakingtext4aktuell = ?, breakingtext4neu = ?, breakingtext5alt = ?, breakingtext5aktuell = ?, breakingtext5neu = ? WHERE id = ?");
		$sql->bind_param("iissssiiisssiiissssssiiisssssssssssssssssssssssssssssssssi", $spalte, $position, $aktiv, $termine, $termine, $termine, $termineanzahl, $termineanzahl, $termineanzahl, $blog, $blog, $blog, $bloganzahl, $bloganzahl, $bloganzahl, $blogart, $blogart, $blogart, $galerie, $galerie, $galerie, $galerieanzahl, $galerieanzahl, $galerieanzahl, $breaking, $breaking, $breaking, $breakingl1, $breakingl1, $breakingl1, $breakingl2, $breakingl2, $breakingl2, $breakingl3, $breakingl3, $breakingl3, $breakingl4, $breakingl4, $breakingl4, $breakingl5, $breakingl5, $breakingl5, $breakingt1, $breakingt1, $breakingt1, $breakingt2, $breakingt2, $breakingt2, $breakingt3, $breakingt3, $breakingt3, $breakingt4, $breakingt4, $breakingt4, $breakingt5, $breakingt5, $breakingt5, $id);
		$sql->execute();
		$sql->close();
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
