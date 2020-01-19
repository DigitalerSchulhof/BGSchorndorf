<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("aktiv", "position", "betreff", "kopie", "anhang", "ids", "namen", "mails", "beschreibungen"));
if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}


$ids = 						explode(",", $ids);
$namen =					explode(",", $namen);
$mails = 					explode(",", $mails);
$beschreibungen = explode(",", $beschreibungen);
$bes = array();
foreach ($beschreibungen as $i => $b) {
	array_push($bes, urldecode(base64_decode($b)));
}
$beschreibungen = $bes;

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte bearbeiten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (($kopie != 0) && ($kopie != 1) && ($kopie != 2)) {$fehler = true;}
	if (($anhang != 0) && ($anhang != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}

	foreach($ids as $k => $v)
		if($v !== "")
			if (!cms_check_ganzzahl($v, 0))			 $fehler = true;

	if(!((count($ids) == count($namen)) && (count($namen) == count($mails)) && (count($mails) == count($beschreibungen))))
		$fehler = true;


	if (!cms_check_nametitel($namen))	$fehler = true;
	if (!cms_check_mail($mails))	$fehler = true;
	if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);

		$betreff = cms_texttrafo_e_db($betreff);

		if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {
		 	$sql = "UPDATE kontaktformulare SET position = $position, betreffneu = ?, kopieneu = ?, anhangneu = ? ";
			$sql .= "WHERE id = $id";
			$sql = $dbs->prepare($sql);

			$sql->bind_param("sii", $betreff, $kopie, $anhang);
			$sql->execute();
			$sql->close();

			$sql = "UPDATE kontaktformulareempfaenger SET nameneu = ?, beschreibungneu = ?, mailneu = ? WHERE id = ? AND kontaktformular = $id";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("sssi", $name, $beschreibung, $mail, $id);
			for ($i=0; $i < count($ids); $i++) {
				$id = $ids[$i];
				$name = $namen[$i];
				$mail = $mails[$i];
				$beschreibung = cms_texttrafo_e_db($beschreibungen[$i]);
				$sql->execute();
			}
			$sql->close();
		}
		else {
			$sql = "UPDATE kontaktformulare SET spalte = ?, position = ?, aktiv = ?, betreffaktuell = ?, betreffneu = ?, kopieaktuell = ?, kopieneu = ?, anhangaktuell = ?, anhangneu = ? WHERE id = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("iisssiiiii", $spalte, $position, $aktiv, $betreff, $betreff, $kopie, $kopie, $anhang, $anhang, $id);
			$sql->execute();
			$sql->close();

			foreach ($ids as $i => $ide) {
				$ide = $ids[$i];
				$name = $namen[$i];
				$mail = $mails[$i];
				$beschreibung = cms_texttrafo_e_db($beschreibungen[$i]);
				if($ide === "") {
					$ide = cms_generiere_kleinste_id('kontaktformulareempfaenger');
				}
				$sql = $dbs->prepare("UPDATE kontaktformulareempfaenger SET kontaktformular = ?, nameaktuell = ?, nameneu = ?, beschreibungaktuell = ?, beschreibungneu = ?, mailaktuell = ?, mailneu = ? WHERE id = ?");
				$sql->execute();
				$sql->bind_param("issssssi", $id, $name, $name, $beschreibung, $beschreibung, $mail, $mail, $ide);
				$sql->close();
			}
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
