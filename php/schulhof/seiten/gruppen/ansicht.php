<?php
$schuljahr = cms_linkzutext($CMS_URL[2]);
$g = cms_linkzutext($CMS_URL[3]);
$gk = cms_textzudb($g);
$gbez = cms_linkzutext($CMS_URL[4]);
$gruppenid = "";

$fehler = false;
// Prüfen, ob diese Gruppe existiert
if (in_array($g, $CMS_GRUPPEN)) {
	if ($schuljahr == "Schuljahrübergreifend") {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IS NULL");
		$sql->bind_param("s", $gbez);
	}
	else {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
		$sql->bind_param("ss", $gbez, $schuljahr);
	}
	// Schuljahr finden
	if ($sql->execute()) {
    $sql->bind_result($gruppenid, $icon, $sichtbar, $chataktiv, $anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}
		}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();
}
else {$fehler = true;}



if (!$fehler) {
	include_once('php/schulhof/seiten/termine/termineausgeben.php');
	include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
	include_once('php/schulhof/seiten/gruppen/ausgaben.php');
	include_once('php/schulhof/seiten/verwaltung/beschluesse/beschluesse.php');
	$code = "";
	$code .= "<div class=\"cms_spalte_i\">";
		$code .= "<p class=\"cms_brotkrumen\">";
		$code .= cms_brotkrumen($CMS_URL);
		$code .= "</p>";
		$code .= "<h1>$gbez</h1>";

	// RECHTE LADEN
	$GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gruppenid);

	if ($GRUPPENRECHTE['sichtbar']) {
		if ((($g == 'Fachschaften') || ($g == 'Gremien')) && (!$CMS_IMLN)) {
			$code .= cms_meldung_eingeschraenkt();
		}
		$code .= "</div>";
		$code .= "<div class=\"cms_spalte_34\">";
			$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Termine</h2>";
				$termincode = cms_gruppentermine_ausgeben($dbs, $g, $gruppenid, $CMS_BENUTZERUEBERSICHTANZAHL, $CMS_URLGANZ);
				if (strlen($termincode) > 0) {$code .= "<ul class=\"cms_terminuebersicht\">".$termincode."</ul>";}
				else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine anstehenden Termine vorhanden.</p>";}
			$code .= "</div></div>";
			$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Blog</h2>";
				$blogcode = cms_gruppenblogeintraege_ausgeben($dbs, $g, $gruppenid, $CMS_BENUTZERUEBERSICHTANZAHL, 'liste', $CMS_URLGANZ);
				if (strlen($blogcode) > 0) {$code .= "<ul class=\"cms_bloguebersicht_liste\">".$blogcode."</ul>";}
				else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Blogeinträge vorhanden.</p>";}
			$code .= "</div></div>";
			$code .= "<div class=\"cms_clear\"></div>";
			$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Beschlüsse</h2>";
				$beschlusscode = cms_gruppenbeschluesse_ausgeben($dbs, $g, $gruppenid, $CMS_BENUTZERUEBERSICHTANZAHL, $CMS_URLGANZ);
				if (strlen($beschlusscode) > 0) {$code .= "<ul class=\"cms_beschlussuebersicht\">".$beschlusscode."</ul>";}
				else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Beschlüsse vorhanden.</p>";}
			$code .= "</div></div>";

			$sql = $dbs->prepare("SELECT chataktiv FROM $gk WHERE id = ?");
			$sql->bind_param("i", $gruppenid);
			$sql->bind_result($chataktiv);
			$sql->execute();
			$sql->fetch();
			$sql->close();

			if($GRUPPENRECHTE["mitglied"] && $chataktiv) {
				$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
					$code .= "<h2>Chat</h2>";
					$code .= cms_gruppenchat_ausgeben($dbs, $g, $gruppenid, $GRUPPENRECHTE);
				$code .= "</div></div>";
			}
			$code .= "<div class=\"cms_clear\"></div>";
			$code .= "<div class=\"cms_spalte_i\">";
				$code .= "<h2>Dateien</h2>";
				$code .= cms_dateisystem_generieren ('schulhof/gruppen/'.$gk.'/'.$gruppenid, 'schulhof/gruppen/'.$gk.'/'.$gruppenid, 'cms_dateien_gruppe', 's', 'schulhof', $gruppenid, $GRUPPENRECHTE);
			$code .= "</div>";
		$code .= "</div>";
		$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
			$code .= "<h2>Aktionen</h2>";
			if ($GRUPPENRECHTE['mitglied']) {
				$code .= "<p>".cms_toggleiconbuttontext_generieren ('cms_gruppe_abonnieren', 'res/gruppen/gross/'.$icon, $gbez." abbestellen", $gbez." abonnieren", $GRUPPENRECHTE['abonniert'], "cms_gruppe_abonnieren('$g', '$gruppenid')")."</p>";
			}
			else {
				$code .= "<p><span class=\"cms_iconbutton_eingeschraenkt\" style=\"background-image: url('res/gruppen/gross/$icon')\">$gbez abonnieren</span></p>";
			}
			$code .= "<ul class=\"cms_aktionen_liste\">";
				//$code .= "<li><a class=\"cms_button\" href=\"$CMS_URLGANZ/Termine\">Kalender</a> </li> ";
				$code .= "<li><a class=\"cms_button\" href=\"$CMS_URLGANZ/Blog\">Blog</a> </li> ";
				if (($g == "Gremien") || ($g == "Fachschaften")) {
					$code .= "<li><a class=\"cms_button\" href=\"$CMS_URLGANZ/Beschlüsse\">Beschlüsse</a> </li> ";
				}
				if ($CMS_BENUTZERART == 'l') {$allenschreiben = $CMS_EINSTELLUNGEN["Postfach - Lehrer dürfen $g Mitglieder schreiben"];}
				else if ($CMS_BENUTZERART == 'e') {$allenschreiben = $CMS_EINSTELLUNGEN["Postfach - Eltern dürfen $g Mitglieder schreiben"];}
				else if ($CMS_BENUTZERART == 's') {$allenschreiben = $CMS_EINSTELLUNGEN["Postfach - Schüler dürfen $g Mitglieder schreiben"];}
				else if ($CMS_BENUTZERART == 'v') {$allenschreiben = $CMS_EINSTELLUNGEN["Postfach - Verwaltungsangestellte dürfen $g Mitglieder schreiben"];}
				else if ($CMS_BENUTZERART == 'x') {$allenschreiben = $CMS_EINSTELLUNGEN["Postfach - Externe dürfen $g Mitglieder schreiben"];}
				else {$$allenschreiben = false;}
				if ($GRUPPENRECHTE['mitglied'] && $allenschreiben) {$code .= "<li><span onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('gruppe', '', '', '', '$g', '$gruppenid')\" class=\"cms_button\">Allen schreiben</span></li> ";}
				if ($GRUPPENRECHTE['bearbeiten']) {
					$code .= "<li><a class=\"cms_button\" onclick=\"cms_gruppen_bearbeiten_vorbereiten('$g', '$gruppenid', '$CMS_URLGANZ')\">Gruppe bearbeiten</a> </li> ";
				}

				$interntermine = cms_internterminvorschlag($GRUPPENRECHTE);
				$internblog = cms_internblogvorschlag($GRUPPENRECHTE);

				if ($interntermine)	{$code .= "<li><a class=\"cms_button_ja\" href=\"$CMS_URLGANZ/Termine/Neuer_Termin\">+ Neuer interner Termin</a> </li> </li> ";}

				if ($internblog) {$code .= "<li><a class=\"cms_button_ja\" href=\"$CMS_URLGANZ/Blog/Neuer_Blogeintrag\">+ Neuer interner Blogeintrag</a> </li> ";}
			$code .= "</ul>";

			$personencode = "";
			include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
			$schreibpool = cms_postfach_empfaengerpool_generieren($dbs);
			$personencode .= cms_personengruppe_ausgeben($dbs, $gk, $gruppenid, $schreibpool, 'vorsitz');
			$personencode .= cms_personengruppe_ausgeben($dbs, $gk, $gruppenid, $schreibpool, 'mitglieder');
			$personencode .= cms_personengruppe_ausgeben($dbs, $gk, $gruppenid, $schreibpool, 'aufsicht');

			if (strlen($personencode) > 0) {$code .= "<h2>Personen</h2>".$personencode;}

		$code .= "</div></div>";
		$code .= "<div class=\"cms_clear\"></div>";
	}
	else {
		$code .= cms_meldung_berechtigung();
		$code .= "</div>";
	}
}
else {
	cms_fehler('Schulhof', '404');
}

echo $code;


function cms_personengruppe_ausgeben($dbs, $gk, $gruppenid, $schreibpool, $personengruppe) {
	global $CMS_SCHLUESSEL;
	$code = "";
	$sql = "(SELECT person FROM $gk"."$personengruppe WHERE gruppe = $gruppenid) AS x";
	$sql = "SELECT y.id AS id, vorname, nachname, titel, nutzerkonten.id AS nutzerkonto FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN $sql ON personen.id = x.person) AS y LEFT JOIN nutzerkonten ON y.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		while ($daten = $anfrage->fetch_assoc()) {
			if (is_null($daten['nutzerkonto'])) {
				$code .= "<li><span class=\"cms_button_passiv\" onclick=\"cms_meldung_keinkonto();\">".cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel'])."</span></li> ";
			}
			else if (in_array($daten['id'], $schreibpool)) {
				$code .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$daten['id'].")\">".cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel'])."</span></li> ";
			}
			else {
				$code .= "<li><span class=\"cms_button_passivda\" onclick=\"cms_meldung_nichtschreiben();\">".cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel'])."</span></li> ";
			}
		}
		$anfrage->free();
	}

	if (strlen($code) > 0) {$code = "<h3>".cms_vornegross($personengruppe)."</h3><ul class=\"cms_aktionen_liste\">".$code."</ul>";}
	return $code;
}
?>
