<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
$postgruppen[0] = 'lehrer';
$postgruppen[1] = 'verwaltungsangestellte';
$postgruppen[2] = 'schueler';
$postgruppen[3] = 'eltern';
$postgruppen[4] = 'externe';

$gruppenvorspann[0] = 'gruppenmitglieder';
$gruppenvorspann[1] = 'gruppenvorsitzende';
$gruppenvorspann[2] = 'gruppenaufsicht';

$personengruppen[0] = 'lehrer';
$personengruppen[1] = 'verwaltungsangestellte';
$personengruppen[2] = 'schueler';
$personengruppen[3] = 'eltern';
$personengruppen[4] = 'externe';

foreach ($personengruppen as $p) {
	foreach ($postgruppen as $post) {
		if (!isset($_POST[$p.$post])) {echo "FEHLER";exit;}
	}
	foreach ($gruppenvorspann as $gv) {
		foreach ($CMS_GRUPPEN as $g) {
			$g = cms_textzudb($g);
			if (!isset($_POST[$p.$gv.$g])) {echo "FEHLER";exit;}
		}
	}
}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.verwaltung.einstellungen")) {
	$fehler = false;

	foreach ($personengruppen as $p) {
		foreach ($postgruppen as $post) {
			if (!cms_check_toggle($_POST[$p.$post])) {$fehler = true;}
		}
		foreach ($gruppenvorspann as $gv) {
			foreach ($CMS_GRUPPEN as $g) {
				$g = cms_textzudb($g);
				if (!cms_check_toggle($_POST[$p.$gv.$g])) {$fehler = true;}
			}
		}
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$tpostgruppen[0] = 'Lehrer';
		$tpostgruppen[1] = 'Verwaltungsangestellte';
		$tpostgruppen[2] = 'Schüler';
		$tpostgruppen[3] = 'Eltern';
		$tpostgruppen[4] = 'Externe';

		$tgruppenvorspann[0] = "Mitglieder";
		$tgruppenvorspann[1] = "Vorsitzende";
		$tgruppenvorspann[2] = "Aufsicht";

		$tpersonengruppen[0] = 'Lehrer';
		$tpersonengruppen[1] = 'Verwaltungsangestellte';
		$tpersonengruppen[2] = 'Schüler';
		$tpersonengruppen[3] = 'Eltern';
		$tpersonengruppen[4] = 'Externe';


		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		for ($i = 0; $i < count($personengruppen); $i++) {
			for ($j = 0; $j < count($postgruppen); $j++) {
				$wert = $_POST[$personengruppen[$i].$postgruppen[$j]];
				$einstellungsname = "Postfach - ".$tpersonengruppen[$i]." dürfen ".$tpostgruppen[$j]." schreiben";
				$sql->bind_param("ss", $wert, $einstellungsname);
			  $sql->execute();
			}

			for ($j = 0; $j < count($gruppenvorspann); $j++) {
				for ($k = 0; $k < count($CMS_GRUPPEN); $k++) {
					$wert = $_POST[$personengruppen[$i].$gruppenvorspann[$j].cms_textzudb($CMS_GRUPPEN[$k])];
					$einstellungsname = "Postfach - ".$tpersonengruppen[$i]." dürfen ".$CMS_GRUPPEN[$k]." ".$tgruppenvorspann[$j]." schreiben";
					$sql->bind_param("ss", $wert, $einstellungsname);
				  $sql->execute();
				}
			}
		}
		$sql->close();

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
