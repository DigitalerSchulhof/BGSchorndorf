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
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.elemente.eventübersicht.anlegen")) {
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
		$sql = $dbs->prepare("UPDATE eventuebersichten SET spalte = ?, position = ?, aktiv = ?, terminealt = ?, termineaktuell = ?, termineneu = ?, termineanzahlalt = ?, termineanzahlaktuell = ?, termineanzahlneu = ?, blogalt = ?, blogaktuell = ?, blogneu = ?, bloganzahlalt = ?, bloganzahlaktuell = ?, bloganzahlneu = ?, galeriealt = ?, galerieaktuell = ?, galerieneu = ?, galerieanzahlalt = ?, galerieanzahlaktuell = ?, galerieanzahlneu = ? WHERE id = ?");
		$sql->bind_param("iissssiiisssiiisssiiii", $spalte, $position, $aktiv, $termine, $termine, $termine, $termineanzahl, $termineanzahl, $termineanzahl, $blog, $blog, $blog, $bloganzahl, $bloganzahl, $bloganzahl, $galerie, $galerie, $galerie, $galerieanzahl, $galerieanzahl, $galerieanzahl, $id);
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
