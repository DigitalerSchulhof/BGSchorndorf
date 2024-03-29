<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("aktiv", "position", "typ", "bezeichnung", "beschreibung"));
if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}


if (cms_angemeldet() && cms_r("website.elemente.newsletter.anlegen")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	if (!cms_r("website.freigeben")) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);

		$beschreibung = cms_texttrafo_e_db($beschreibung);

		if (!cms_r("website.freigeben")) {
			$sql = $dbs->prepare("UPDATE wnewsletter SET position = $position, bezeichnungneu = ?, beschreibungneu = ?, typneu = ? WHERE id = ?");

			$sql->bind_param("siii", $bezeichnung, $beschreibung, $typ, $id);
			$sql->execute();
			$sql->close();
		}
		else {
			$sql = $dbs->prepare("UPDATE wnewsletter SET spalte = ?, position = , aktiv = '', bezeichnungalt = ?, bezeichnungaktuell = ?, bezeichnungneu = ?, beschreibungalt = ?, beschreibungaktuell = ?, beschreibungneu = ?, typalt = ?, typaktuell = ?, typneu = ? WHERE id = ?");
			$sql->bind_param("iisssssssiiii", $spalte, $position, $aktiv, $bezeichnung, $bezeichnung, $bezeichnung, $beschreibung, $beschreibung, $beschreibung, $typ, $typ, $typ, $id);
			$sql->execute();
			$sql->close();
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
