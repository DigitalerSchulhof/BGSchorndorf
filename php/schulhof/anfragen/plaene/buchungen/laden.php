<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/seiten/plaene/buchungen/ausgabe.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) 				  {$tag = $_POST['tag'];} 	                    else {echo "FEHLER";exit;}
if (isset($_POST['monat'])) 			  {$monat = $_POST['monat'];} 								  else {echo "FEHLER";exit;}
if (isset($_POST['jahr'])) 				  {$jahr = $_POST['jahr'];} 								    else {echo "FEHLER";exit;}
if (isset($_POST['ziel'])) 				  {$ziel = $_POST['ziel'];} 									  else {echo "FEHLER";exit;}
if (isset($_POST['art'])) 					{$art = $_POST['art'];} 											else {echo "FEHLER";exit;}
if (isset($_POST['standort'])) 			{$standort = $_POST['standort'];} 						else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($standort,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}


$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if($art === "r") { $rart = "räume"; }
else if($art === "l") { $rart = "leihgeräte"; }

if (cms_angemeldet() && cms_r("schulhof.organisation.buchungen.$rart.[|sehen,anonymisiert]")) {
	if (cms_r("schulhof.organisation.buchungen.$rart.sehen")) {$anonymisiert = false;}
	else {$anonymisiert = true;}
	echo cms_buchungsplan_laden($art, $standort, $tag, $monat, $jahr, $ziel, $anonymisiert);
}
else {
	echo "BERECHTIGUNG";
}
?>
