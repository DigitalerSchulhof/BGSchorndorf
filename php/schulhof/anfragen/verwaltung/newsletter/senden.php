<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';

session_start();

// Variablen einlesen, falls übergeben
postLesen(array("text", "id"));



if (cms_angemeldet() && cms_r("schulhof.information.newsletter.schreiben")) {
	$dbs = cms_verbinden('s');

	$text = cms_texttrafo_e_db_ohnetag($text);

	$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM newslettertypen WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $id);
	$sql->bind_result($bez);
	if(!$sql->execute() || !$sql->fetch())
		die("FEHLER");

	$sql = "SELECT id, AES_DECRYPT(name, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL'), AES_DECRYPT(token, '$CMS_SCHLUESSEL') FROM newsletterempfaenger WHERE newsletter = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $id);
	$sql->bind_result($id, $empfn, $empfm, $token);
	if(!$sql->execute()) {
		die("FEHLER");
	}

	$CMS_WICHTIG = cms_einstellungen_laden("wichtigeeinstellungen");
	$CMS_MAIL = cms_einstellungen_laden("maileinstellungen");

	while($sql->fetch()) {
		$betreff = "Newsletter $bez";
		$textHTML = cms_textaustextfeld_anzeigen($text."\n\n\n<div style=\"font-size: 9px\">Newsletter abbestellen: <a href=\"".$CMS_WICHTIG['Schule Domain']."/Website/Newsletter_abbestellen/$token\">".$CMS_WICHTIG['Schule Domain']."/Website/Newsletter_abbestellen/$token</a></div>");
		$textPlain = $text."\n\n\Newsletter abbestellen: ".$CMS_WICHTIG['Schule Domain']."/Website/Newsletter_abbestellen/$token";
		cms_mailsenden($empfn, $empfm, $betreff, $textHTML, $textPlain, false);
	}
	cms_trennen($dbs);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
