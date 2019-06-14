<?php
$code = "";
$code .= "<div class=\"cms_spalte_i cms_spalte_icon\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";

if (isset($_SESSION["GRUPPEID"])) {$gruppenid = $_SESSION["GRUPPEID"];} else {$gruppenid = '-';}
if (isset($_SESSION["GRUPPEBEZEICHNUNG"])) {$gruppenbezeichnung = $_SESSION["GRUPPEBEZEICHNUNG"];} else {$gruppenbezeichnung = '-';}
if (isset($_SESSION["GRUPPE"])) {$gruppe = $_SESSION["GRUPPE"];} else {$gruppe = '-';}
if (isset($_SESSION["GRUPPESINGULAR"])) {$gruppesingular = $_SESSION["GRUPPESINGULAR"];} else {$gruppesingular = '-';}
if (isset($_SESSION["GRUPPENEU"])) {$gruppeneu = $_SESSION["GRUPPENEU"];} else {$gruppeneu = '-';}
if (isset($_SESSION["GRUPPEARTIKEL"])) {$gruppeartikel = $_SESSION["GRUPPEARTIKEL"];} else {$gruppeartikel = '-';}

$titel = str_replace('_', ' ', $gruppenbezeichnung);
$code .= "<h1>$titel</h1>";

if ($gruppenid != "-") {
	$gruppek = strtolower($gruppe);
	$zugriff = false;
	$fehler = false;
	// Prüfen, ob die Gruppe allgemein sichtbar ist oder ob der Benutzer Mitglied der Gruppe ist
	$dbs = cms_verbinden('s');
	// Gremium sichtbar?
	$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(sichtbar, '$CMS_SCHLUESSEL') AS sichtbar, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gruppek WHERE id = $gruppenid;";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			if ($daten['sichtbar'] == '1') {
				$zugriff = true;
			}
		}
		else {$fehler = true;}
		$anfrage->free();
	}
	else {$fehler = true;}
	cms_trennen($dbs);

	$gremienrecht = cms_gruppenrechte_laden($gruppe, $gruppenid);

	// Person Mitglied?
	if ($gremienrecht['mitglied']) {$zugriff = true;}

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();

	if ($angemeldet && $zugriff) {
		include_once('php/schulhof/seiten/termine/termineausgeben.php');
		if (!$CMS_IMLN) {
			$code .= cms_meldung_eingeschraenkt();
		}

		$icon = "standard.png";
		if (is_file("res/ereignisse/gross/".$daten['icon'])) {$icon = $daten['icon'];}
		$code .= "<img class=\"cms_kategorie_icon_anzeige\" src=\"res/ereignisse/gross/$icon\">";
		$code .= "</div>";

		$vorsitzcode = "";
		$mitgliedercode = "";
		$mitgliederhidden = "";
		$aufsichtcode = "";

		// Alle Mitglieder bestimmen
		$erledigt = array();
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, vorsitz, nutzerkonten.id AS nutzerkonto FROM ".$gruppek."mitgliedschaften JOIN personen ON ".$gruppek."mitgliedschaften.person = personen.id LEFT JOIN nutzerkonten ON nutzerkonten.id = personen.id WHERE gruppe = $gruppenid) AS mitglieder ORDER BY nachname, vorname ASC;";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				array_push($erledigt, $daten['id']);
				$mitgliederhidden .= "|".$daten['id'];
				$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
				$nutzerkonto = $daten['nutzerkonto'];
				if ($daten['id'] != $CMS_BENUTZERID) {
					if (!is_null($nutzerkonto)) {
						$zwischencode  = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', ".$daten['id'].", 'p')\">$anzeigename</span> ";
					}
					else {
						$zwischencode  = "<span class=\"cms_button_passiv\" onclick=\"cms_schulhof_kein_nutzerkonto('$anzeigename')\">$anzeigename</span> ";
					}
				}
				else {
					$zwischencode  = "<span class=\"cms_button\" onclick=\"cms_postfach_eigennachricht();\">$anzeigename</span> ";
				}
				if ($daten['vorsitz'] == '1') {
					$vorsitzcode .= $zwischencode;
				}
				else {
					$mitgliedercode .= $zwischencode;
				}
			}
			$anfrage->free();
		}

		$sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, nutzerkonten.id AS nutzerkonto FROM aufsichten JOIN personen ON aufsichten.person = personen.id LEFT JOIN nutzerkonten ON nutzerkonten.id = personen.id WHERE gruppenid = $gruppenid AND gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL')) AS aufsichten ORDER BY nachname, vorname ASC;";

		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				if (!in_array($daten['id'], $erledigt)) {
					$mitgliederhidden .= "|".$daten['id'];
					array_push($erledigt, $daten['id']);
				}
				$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
				$nutzerkonto = $daten['nutzerkonto'];
				if ($daten['id'] != $CMS_BENUTZERID) {
					if (!is_null($nutzerkonto)) {
						$zwischencode  = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', ".$daten['id'].", 'p')\">$anzeigename</span> ";
					}
					else {
						$zwischencode  = "<span class=\"cms_button_passiv\" onclick=\"cms_schulhof_kein_nutzerkonto('$anzeigename')\">$anzeigename</span> ";
					}
				}
				else {
					$zwischencode  = "<span class=\"cms_button\" onclick=\"cms_postfach_eigennachricht();\">$anzeigename</span> ";
				}
				$aufsichtcode .= $zwischencode;
			}
			$anfrage->free();
		}
		cms_trennen($dbs);

		$mitgliederhidden = substr($mitgliederhidden, 1);

		$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
		$code .= "<h2>Termine</h2>";
		$termincode = cms_termine_ausgeben ($gruppe, $gruppenid, 'schulhof', $_SESSION['BENUTZERUEBERSICHTANZAHL']);
		if (strlen($termincode) == 0) {
			$code .= "<p class=\"cms_notiz\">Keine Termine vorhanden</p>";
		}
		else {$code .= $termincode;}

		$code .= "<p>";
		$code .= "<span class=\"cms_button\" onclick=\"cms_schulhof_kalender_vorbreiten('$gruppe', '$gruppenid', '$gruppenbezeichnung')\">Alle Termine</span> ";
		if ($gremienrecht['sch']) {
			$code .= "<span class=\"cms_button_ja\" onclick=\"cms_schulhof_termine_neu_vorbereiten('$gruppe', '$gruppenid')\">+ Neuer Termin</span> ";
		}
		$code .= "</p>";

		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h2>Blog</h2>";
		$code .= cms_gesicherteinhalte("cms_schulhof_gruppenuebersicht_blog", "l", "gruppenuebersicht_blog");
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
		$code .= "<h2>Beschlüsse</h2>";
		$code .= cms_gesicherteinhalte("cms_schulhof_gruppenuebersicht_beschluesse", "l", "gruppenuebersicht_beschluesse");
		$code .= "</div></div>";
		$code .= "<div class=\"cms_clear\"></div>";



		$code .= "<div class=\"cms_spalte_i\">";
		$code .= "<h2>Dateien</h2>";

		$code .= "<ul class=\"cms_reitermenue\">";
		$code .= "<li><span id=\"cms_reiter_gruppenuebersicht_dateien_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('gruppenuebersicht_dateien', 0,1)\">Materialien / Informationen</span></li> ";
		$code .= "<li><span id=\"cms_reiter_gruppenuebersicht_dateien_1\" class=\"cms_reiter\" onclick=\"cms_reiter('gruppenuebersicht_dateien', 1,1)\">Verwaltung / Personenbezogenes</span></li>";
		$code .= "</ul>";

		$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_gruppenuebersicht_dateien_0\" style=\"display: block;\"><div class=\"cms_reitermenue_i\">";
		$code .= cms_dateisystem_generieren ("schulhof/$gruppek/$gruppenid", "schulhof/$gruppek/$gruppenid", 'cms_schulhof_gruppenuebersicht_oe_dateien', 's', "$gruppe", "$gruppenid", $gremienrecht);
		$code .= "</div></div>";
		$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_gruppenuebersicht_dateien_1\">";
		$code .= cms_gesicherteinhalte("cms_schulhof_gruppenuebersicht_dateien", "l", "gruppenuebersicht_dateien");
		$code .= "</div>";

		$mitgliedergesamt = "";
		$hatmitglieder = false;

		if (strlen($mitgliedercode) > 0) {
			$mitgliedergesamt .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
			$mitgliedergesamt .= "<h4>Mitglieder</h4>";
			$mitgliedergesamt .= "<p>".$mitgliedercode."</p>";
			$mitgliedergesamt .= "</div></div>";
			$hatmitglieder = true;
		}

		$mitgliedergesamt .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
		if (strlen($vorsitzcode) > 0) {
			$mitgliedergesamt .= "<h4>Vorsitz</h4>";
			$mitgliedergesamt .= "<p>".$vorsitzcode."</p>";
			$hatmitglieder = true;
		}

		if (strlen($aufsichtcode) > 0) {
			$mitgliedergesamt .= "<h4>Abteilungsleitung</h4>";
			$mitgliedergesamt .= "<p>".$aufsichtcode."</p>";
			$hatmitglieder = true;
		}

		if ((strlen($mitgliederhidden) > 0) || $gremienrecht['mv']) {
			$hatmitglieder = true;
			$mitgliedergesamt .= "<h4>Aktionen</h4>";
			$mitgliedergesamt .= "<p>";
			if (strlen($mitgliederhidden) > 0) {
				$mitgliedergesamt .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', '$mitgliederhidden', 'l')\">Allen schreiben</span> ";
			}
			if ($gremienrecht['mv']) {
				$bezlink = str_replace (" ", "_", $gruppenbezeichnung);
				$mitgliedergesamt .= "<a class=\"cms_button\" href=\"Lehrerzimmer/".$gruppe."/".$bezlink."/Mitglieder\">Mitglieder verwalten</a>";
			}
			$mitgliedergesamt .= "</p>";
		}
		$mitgliedergesamt .= "</div></div>";
		$mitgliedergesamt .= "<div class=\"cms_clear\"></div>";

		if ($hatmitglieder) {
			$code .= "<h2>Mitglieder</h2>";
			$code .= "</div>";
			$code .= $mitgliedergesamt;
		}
	}
	else {
		$code .= cms_meldung_berechtigung();
		$code .= "</div>";
	}
}
else {
	$code .= cms_meldung_bastler();
	$code .= "</div>";
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
