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
if (isset($_POST['galerie'])) {$galerie = $_POST['galerie'];} else {echo "FEHLER"; exit;}
if (isset($_POST['galerieanzahl'])) {$galerieanzahl = $_POST['galerieanzahl'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.elemente.eventübersicht.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (($termine != 0) && ($termine != 1)) {$fehler = true;}
	if (($blog != 0) && ($blog != 1)) {$fehler = true;}
	if (($galerie != 0) && ($galerie != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($termineanzahl,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($bloganzahl,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($galerieanzahl,0)) {$fehler = true;}

	if ($termine == '0') {$termineanzahl = '10';}
	if ($blog == '0') {$bloganzahl = '5';}
	if ($galerie == '0') {$galerieanzahl = '5';}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);
		if (!cms_r("website.freigeben")) {
			$sql = "UPDATE eventuebersichten SET position = $position, termineneu = '$termine', termineanzahlneu = '$termineanzahl', blogneu = '$blog', bloganzahlneu = '$bloganzahl', galerieneu = '$galerie', galerieanzahlneu = '$galerieanzahl' WHERE id = $id";
		}
		else {
			$sql = "UPDATE eventuebersichten SET position = $position, aktiv = '$aktiv', ";
			$sql .= "terminealt = termineaktuell, termineaktuell = '$termine', termineneu = '$termine', ";
			$sql .= "termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = '$termineanzahl', termineanzahlneu = '$termineanzahl', ";
			$sql .= "blogalt = blogaktuell, blogaktuell = '$blog', blogneu = '$blog', ";
			$sql .= "bloganzahlalt = bloganzahlaktuell, bloganzahlaktuell = '$bloganzahl', bloganzahlneu = '$bloganzahl', ";
			$sql .= "galeriealt = galerieaktuell, galerieaktuell = '$galerie', galerieneu = '$galerie', ";
			$sql .= "galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = '$galerieanzahl', galerieanzahlneu = '$galerieanzahl' ";
			$sql .= "WHERE id = $id";
		}
		$anfrage = $dbs->query($sql);	// TODO: Irgendwie safe machen
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
