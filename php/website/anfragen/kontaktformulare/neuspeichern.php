<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("aktiv", "position", "betreff", "kopie", "anhang", "namen", "mails", "beschreibungen"));
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}

$namen = explode(",", $namen);
$mails = explode(",", $mails);
$beschreibungen = explode(",", $beschreibungen);
$bes = array();
foreach ($beschreibungen as $i => $b) {
	array_push($bes, urldecode(base64_decode($b)));
}
$beschreibungen = $bes;
cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.elemente.kontaktformular.anlegen"))) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (($kopie != 0) && ($kopie != 1) && ($kopie != 2)) {$fehler = true;}
	if (($anhang != 0) && ($anhang != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}

	if(!((count($namen) == count($mails)) && (count($mails) == count($beschreibungen))))
		$fehler = true;


	foreach($namen as $i => $n)
		if(!cms_check_nametitel($n))
			$fehler = true;
	foreach($mails as $i => $m)
		if(!cms_check_mail($m))
			$fehler = true;

	if (!cms_r("website.freigeben"))) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('kontaktformulare');
		if ($id == '-') {$fehler = true;}
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_einfuegen($dbs, $spalte, $position);

		// Formular eintragen
		$sql = "UPDATE kontaktformulare SET spalte = $spalte, position = $position, aktiv = '$aktiv', ";
		$sql .= cms_sql_aan(array("betreff", "kopie", "anhang"));
		$sql = substr($sql, 0, -1)." ";
		$sql .= "WHERE id = $id";
		$sql = $dbs->prepare($sql);

		$betreff = cms_texttrafo_e_db($betreff);

		$sql->bind_param("sssiiiiii", $betreff, $betreff, $betreff, $kopie, $kopie, $kopie, $anhang, $anhang, $anhang);
		$sql->execute();

		// Empfänger eintragen
		$sql = "UPDATE kontaktformulareempfaenger SET kontaktformular = $id, ";
		$sql .= cms_sql_aan(array("name", "beschreibung", "mail"));
		$sql = substr($sql, 0, -1)." ";
		$sql .= "WHERE id = ?";
		$sql = $dbs->prepare($sql);

		$sql->bind_param("sssssssssi", $name, $name, $name, $beschreibung, $beschreibung, $beschreibung, $mail, $mail, $mail, $empfid);
		for ($i=0; $i < count($namen); $i++) {
			$name = $namen[$i];
			$mail = $mails[$i];
			$beschreibung = cms_texttrafo_e_db($beschreibungen[$i]);
			$empfid = cms_generiere_kleinste_id('kontaktformulareempfaenger');

			$sql->execute();
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
