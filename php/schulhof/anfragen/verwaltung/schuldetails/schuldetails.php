<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schulename'])) 				{$schulename = cms_texttrafo_e_db($_POST['schulename']);} 								else {echo "FEHLER"; exit;}
if (isset($_POST['schulenamegenitiv'])) {$schulenamegenitiv = cms_texttrafo_e_db($_POST['schulenamegenitiv']);}		else {echo "FEHLER"; exit;}
if (isset($_POST['schuleort'])) 				{$schuleort = cms_texttrafo_e_db($_POST['schuleort']);}										else {echo "FEHLER"; exit;}
if (isset($_POST['schulestrasse'])) 		{$schulestrasse = cms_texttrafo_e_db($_POST['schulestrasse']);} 					else {echo "FEHLER"; exit;}
if (isset($_POST['schuleplzort'])) 			{$schuleplzort = cms_texttrafo_e_db($_POST['schuleplzort']);} 						else {echo "FEHLER"; exit;}
if (isset($_POST['schuletelefon'])) 		{$schuletelefon = cms_texttrafo_e_db($_POST['schuletelefon']);} 					else {echo "FEHLER"; exit;}
if (isset($_POST['schulefax'])) 				{$schulefax = cms_texttrafo_e_db($_POST['schulefax']);} 									else {echo "FEHLER"; exit;}
if (isset($_POST['schulemail'])) 				{$schulemail = cms_texttrafo_e_db($_POST['schulemail']);} 								else {echo "FEHLER"; exit;}
if (isset($_POST['schuledomain'])) 			{$schuledomain = cms_texttrafo_e_db($_POST['schuledomain']);} 						else {echo "FEHLER"; exit;}
if (isset($_POST['schulleitungname'])) 	{$schulleitungname = cms_texttrafo_e_db($_POST['schulleitungname']);} 		else {echo "FEHLER"; exit;}
if (isset($_POST['schulleitungmail'])) 	{$schulleitungmail = cms_texttrafo_e_db($_POST['schulleitungmail']);} 		else {echo "FEHLER"; exit;}
if (isset($_POST['datenschutzname'])) 	{$datenschutzname = cms_texttrafo_e_db($_POST['datenschutzname']);} 			else {echo "FEHLER"; exit;}
if (isset($_POST['datenschutzmail'])) 	{$datenschutzmail = cms_texttrafo_e_db($_POST['datenschutzmail']);} 			else {echo "FEHLER"; exit;}
if (isset($_POST['pressename'])) 				{$pressename = cms_texttrafo_e_db($_POST['pressename']);} 								else {echo "FEHLER"; exit;}
if (isset($_POST['pressemail'])) 				{$pressemail = cms_texttrafo_e_db($_POST['pressemail']);} 								else {echo "FEHLER"; exit;}
if (isset($_POST['webmastername'])) 		{$webmastername = cms_texttrafo_e_db($_POST['webmastername']);} 					else {echo "FEHLER"; exit;}
if (isset($_POST['webmastermail'])) 		{$webmastermail = cms_texttrafo_e_db($_POST['webmastermail']);} 					else {echo "FEHLER"; exit;}
if (isset($_POST['administratorname'])) {$administratorname = cms_texttrafo_e_db($_POST['administratorname']);} 	else {echo "FEHLER"; exit;}
if (isset($_POST['administratormail'])) {$administratormail = cms_texttrafo_e_db($_POST['administratormail']);} 	else {echo "FEHLER"; exit;}

if (strlen($schulename) < 3) 				{echo "FEHLER"; exit;}
if (strlen($schulenamegenitiv) < 3) {echo "FEHLER"; exit;}
if (strlen($schuleort) < 3) 				{echo "FEHLER"; exit;}
if (strlen($schulestrasse) < 3) 		{echo "FEHLER"; exit;}
if (strlen($schuleplzort) < 3) 			{echo "FEHLER"; exit;}
if (strlen($schuletelefon) < 3) 		{echo "FEHLER"; exit;}
if (strlen($schulemail) < 3) 				{echo "FEHLER"; exit;}
if (strlen($schuledomain) < 3) 			{echo "FEHLER"; exit;}
if (strlen($schulleitungname) < 3) 	{echo "FEHLER"; exit;}
if (strlen($datenschutzname) < 3) 	{echo "FEHLER"; exit;}
if (strlen($pressename) < 3) 				{echo "FEHLER"; exit;}
if (strlen($webmastername) < 3) 		{echo "FEHLER"; exit;}
if (strlen($administratorname) < 3) {echo "FEHLER"; exit;}

if (!cms_check_mail($schulemail) || !cms_check_mail($schulleitungmail) || !cms_check_mail($datenschutzmail)) {echo "FEHLER"; exit;}
if (!cms_check_mail($pressemail) || !cms_check_mail($webmastermail) || !cms_check_mail($administratormail)) {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("schulhof.verwaltung.schule.details")) {

	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE wichtigeeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	$inhalt = 'Schulname';
	$sql->bind_param('ss', $schulename, $inhalt); $sql->execute();
	$inhalt = 'Schulname Genitiv';
	$sql->bind_param('ss', $schulenamegenitiv, $inhalt);	$sql->execute();
	$inhalt = 'Schule Ort';
	$sql->bind_param('ss', $schuleort, $inhalt);	$sql->execute();
	$inhalt = 'Schule Straße';
	$sql->bind_param('ss', $schulestrasse, $inhalt);	$sql->execute();
	$inhalt = 'Schule PLZOrt';
	$sql->bind_param('ss', $schuleplzort, $inhalt);	$sql->execute();
	$inhalt = 'Schule Telefon';
	$sql->bind_param('ss', $schuletelefon, $inhalt);	$sql->execute();
	$inhalt = 'Schule Fax';
	$sql->bind_param('ss', $schulefax, $inhalt);	$sql->execute();
	$inhalt = 'Schule Mail';
	$sql->bind_param('ss', $schulemail, $inhalt);	$sql->execute();
	$inhalt = 'Schule Domain';
	$sql->bind_param('ss', $schuledomain, $inhalt);	$sql->execute();
	$inhalt = 'Schulleitung Name';
	$sql->bind_param('ss', $schulleitungname, $inhalt);	$sql->execute();
	$inhalt = 'Schulleitung Mail';
	$sql->bind_param('ss', $schulleitungmail, $inhalt);	$sql->execute();
	$inhalt = 'Datenschutz Name';
	$sql->bind_param('ss', $datenschutzname, $inhalt);	$sql->execute();
	$inhalt = 'Datenschutz Mail';
	$sql->bind_param('ss', $datenschutzmail, $inhalt);	$sql->execute();
	$inhalt = 'Presse Name';
	$sql->bind_param('ss', $pressename, $inhalt);	$sql->execute();
	$inhalt = 'Presse Mail';
	$sql->bind_param('ss', $pressemail, $inhalt);	$sql->execute();
	$inhalt = 'Webmaster Name';
	$sql->bind_param('ss', $webmastername, $inhalt);	$sql->execute();
	$inhalt = 'Webmaster Mail';
	$sql->bind_param('ss', $webmastermail, $inhalt);	$sql->execute();
	$inhalt = 'Administration Name';
	$sql->bind_param('ss', $administratorname, $inhalt);	$sql->execute();
	$inhalt = 'Administration Mail';
	$sql->bind_param('ss', $administratormail, $inhalt);	$sql->execute();
	$sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
