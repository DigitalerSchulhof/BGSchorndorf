<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("aktiv", "position", "titel", "bildanzahl", "bildids"));
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte anlegen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (!cms_check_titel($titel)) {$fehler = true;}

	if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('diashows');
		if ($id == '-') {$fehler = true;}
	}

	$bilder = array();
  if ($bildanzahl > 0) {
		$bildids = explode('|', $bildids);

		for ($i=1; $i<count($bildids); $i++) {
      $dd = array();
			if (isset($_POST["bbeschreibung_".$bildids[$i]])) {$dd['beschreibung'] = $_POST["bbeschreibung_".$bildids[$i]];} else {echo "FEHLER"; exit;}

			if (isset($_POST["bpfad_".$bildids[$i]])) {$dd['pfad'] = $_POST["bpfad_".$bildids[$i]];} else {echo "FEHLER"; exit;}
			if (!is_file('../../../'.$dd['pfad'])) {$fehler = true; }
			$dd["beschreibung"] = cms_texttrafo_e_db($dd["beschreibung"]);

      array_push($bilder, $dd);
		}
	}

	if (!$fehler) {
		$dbs = cms_verbinden("s");
		cms_elemente_verschieben_einfuegen($dbs, $spalte, $position);

		$sql = "UPDATE diashows SET spalte = ?, position = ?, aktiv = ?, titelalt = ?, titelaktuell = ?, titelneu = ? WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("iiisssi", $spalte, $position, $aktiv, $titel, $titel, $titel, $id);
		$sql->execute();

		$sql = $dbs->prepare("UPDATE diashowbilder SET diashow = ?, pfadalt = ?, pfadaktuell = ?, pfadneu = ?, beschreibungalt = ?, beschreibungaktuell = ?, beschreibungneu = ? WHERE id = ?");
		$sql->bind_param("issssssi", $id, $pfad, $pfad, $pfad, $beschreibung, $beschreibung, $beschreibung, $bid);
		foreach ($bilder as $b) {
			$bid = cms_generiere_kleinste_id('diashowbilder');
			$pfad = $b["pfad"];
			$beschreibung = $b["beschreibung"];
      $sql->execute();
		}
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
