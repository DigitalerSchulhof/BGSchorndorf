<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("aktiv", "position", "betreff", "kopie", "anhang", "ansicht", "namen", "mails", "beschreibungen"));
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}

$namen = explode(",", $namen);
$mails = explode(",", $mails);
$beschreibungen = explode(",", $beschreibungen);
$bes = array();
foreach ($beschreibungen as $i => $b) {
	array_push($bes, urldecode(base64_decode($b)));
}
$beschreibungen = $bes;
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte anlegen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (($kopie != 0) && ($kopie != 1) && ($kopie != 2)) {$fehler = true;}
	if (($anhang != 0) && ($anhang != 1)) {$fehler = true;}
	if (($ansicht != 'v') && ($ansicht != 'm')) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}

	if(!((count($namen) == count($mails)) && (count($mails) == count($beschreibungen))))
		$fehler = true;

	foreach($namen as $i => $n)
		if(!cms_check_nametitel($n))
			$fehler = true;
	foreach($mails as $i => $m)
		if(!cms_check_mail($m))
			$fehler = true;

	if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 0;}

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
		$betreff = cms_texttrafo_e_db($betreff);
		$sql = $dbs->prepare("UPDATE kontaktformulare SET spalte = ?, position = ?, aktiv = ?, betreffalt = ?, betreffaktuell = ?, betreffneu = ?, kopiealt = ?, kopieaktuell = ?, kopieneu = ?, anhangalt = ?, anhangaktuell = ?, anhangneu = ?, ansichtalt = ?, ansichtaktuell = ?, ansichtneu = ? WHERE id = ?");
		$sql->bind_param("iissssiiiiiisssi", $spalte, $position, $aktiv, $betreff, $betreff, $betreff, $kopie, $kopie, $kopie, $anhang, $anhang, $anhang, $ansicht, $ansicht, $ansicht, $id);
		$sql->execute();

		// Empfänger eintragen
		for ($i=0; $i < count($namen); $i++) {
			$name = $namen[$i];
			$mail = $mails[$i];
			$beschreibung = cms_texttrafo_e_db($beschreibungen[$i]);
			$empfid = cms_generiere_kleinste_id('kontaktformulareempfaenger');
			$sql = "UPDATE kontaktformulareempfaenger SET kontaktformular = ?, name = ?, beschreibung = ?, mail = ? WHERE id = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("isssi", $id, $name, $beschreibung, $mail, $empfid);
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
