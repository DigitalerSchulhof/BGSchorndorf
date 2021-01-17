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

if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.elemente.eventübersicht.bearbeiten")) {
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

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);
		if (!cms_r("website.freigeben")) {
			$sql = $dbs->prepare("UPDATE eventuebersichten SET position = ?, termineneu = ?, termineanzahlneu = ?, blogneu = ?, bloganzahlneu = ?, blogartneu = ?, galerieneu = ?, galerieanzahlneu = ?, breakingneu = ?, breakinglink1neu = ?, breakinglink2neu = ?, breakinglink3neu = ?, breakinglink4neu = ?, breakinglink5neu = ?, breakingtext1neu = ?, breakingtext2neu = ?, breakingtext3neu = ?, breakingtext4neu = ?, breakingtext5neu = ? WHERE id = ?");
			$sql->bind_param("isisissiisssssssssss", $position, $termine, $termineanzahl, $blog, $bloganzahl, $blogart, $galerie, $galerieanzahl, $breaking, $breakingl1, $breakingl2, $breakingl3, $breakingl4, $breakingl5, $breakingt1,$breakingt2, $breakingt3, $breakingt4, $breakingt5, $id);
		}
		else {
			$sql = $dbs->prepare("UPDATE eventuebersichten SET position = ?, aktiv = ?, terminealt = termineaktuell, termineaktuell = ?, termineneu = ?, termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = ?, termineanzahlneu = ?, blogalt = blogaktuell, blogaktuell = ?, blogneu = ?, bloganzahlalt = bloganzahlaktuell, breakingalt = breakingaktuell, breakinglink1alt = breakinglink1aktuell, breakinglink2alt = breakinglink2aktuell, breakinglink3alt = breakinglink3aktuell, breakinglink4alt = breakinglink4aktuell, breakinglink5alt = breakinglink5aktuell, breakingtext1alt = breakingtext1aktuell, breakingtext2alt = breakingtext2aktuell, breakingtext3alt = breakingtext3aktuell, breakingtext4alt = breakingtext4aktuell, breakingtext5alt = breakingtext5aktuell, bloganzahlaktuell = ?, bloganzahlneu = ?, blogartalt = blogartaktuell, blogartaktuell = ?, blogartneu = ?, galeriealt = galerieaktuell, galerieaktuell = ?, galerieneu = ?, galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = ?, galerieanzahlneu = ?, breakingaktuell = ?, breakingneu = ?, breakinglink1aktuell = ?, breakinglink1neu = ?, breakinglink2aktuell = ?, breakinglink2neu = ?, breakinglink3aktuell = ?, breakinglink3neu = ?, breakinglink4aktuell = ?, breakinglink4neu = ?, breakinglink5aktuell = ?, breakinglink5neu = ?, breakingtext1aktuell = ?, breakingtext1neu = ?, breakingtext2aktuell = ?, breakingtext2neu = ?, breakingtext3aktuell = ?, breakingtext3neu = ?, breakingtext4aktuell = ?, breakingtext4neu = ?, breakingtext5aktuell = ?, breakingtext5neu = ? WHERE id = ?");
			$sql->bind_param("isssiissiissssiissssssssssssssssssssssi", $position, $aktiv, $termine, $termine, $termineanzahl, $termineanzahl, $blog, $blog, $bloganzahl, $bloganzahl, $blogart, $blogart, $galerie, $galerie, $galerieanzahl, $galerieanzahl, $breaking, $breaking, $breakingl1, $breakingl1, $breakingl2, $breakingl2, $breakingl3, $breakingl3, $breakingl4, $breakingl4, $breakingl5, $breakingl5, $breakingt1, $breakingt1, $breakingt2, $breakingt2, $breakingt3, $breakingt3, $breakingt4, $breakingt4, $breakingt5, $breakingt5, $id);
		}
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
