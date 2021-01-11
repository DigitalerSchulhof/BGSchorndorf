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
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, AES_DECRYPT(pinnwand, '$CMS_SCHLUESSEL'), COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IS NULL");
		$sql->bind_param("s", $gbez);
	}
	else {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, AES_DECRYPT(pinnwand, '$CMS_SCHLUESSEL'), COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
		$sql->bind_param("ss", $gbez, $schuljahr);
	}
	// Schuljahr finden
	if ($sql->execute()) {
    $sql->bind_result($gruppenid, $icon, $sichtbar, $chataktiv, $pinnwand, $anzahl);
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
			$code .= "<h2>Pinnwand</h2>";
			$code .= cms_gruppenlinks_ausgeben($dbs, $g, $gruppenid, $GRUPPENRECHTE);
			if ($GRUPPENRECHTE['blogeintraege']) {
				$code .= "<textarea id=\"cms_gruppenpinnwand\" class=\"cms_notizzettel\">$pinnwand</textarea>";
				$code .= "<p><span class=\"cms_button\" onclick=\"cms_gruppe_pinnwand_speichern('$g', '$gruppenid', '$CMS_URLGANZ')\">Speichern</span> <a class=\"cms_button cms_button_nein\" href=\"$CMS_URLGANZ\">Abbrechen</a></p>";
			}
			else {
				$code .= "<div class=\"cms_notizzettel cms_notizpinnwand\">";
				$code .= cms_textaustextfeld_anzeigen($pinnwand)."</div>";
			}
			$code .= "</div></div>";

			$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Blog</h2>";
				$blogcode = cms_gruppenblogeintraege_ausgeben($dbs, $g, $gruppenid, $CMS_BENUTZERUEBERSICHTANZAHL, 'liste', $CMS_URLGANZ, $GRUPPENRECHTE);
				if (strlen($blogcode) > 0) {
					$code .= "<ul class=\"cms_bloguebersicht_liste\">".$blogcode."</ul>";
					$code .= "<p><a class=\"cms_button\" href=\"$CMS_URLGANZ/Blog\">Alle Blogeinträge</a></p>";
				}
				else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Blogeinträge vorhanden.</p>";}
			$code .= "</div></div>";
			$code .= "<div class=\"cms_clear\"></div>";
			$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Termine</h2>";
				$termincode = cms_gruppentermine_ausgeben($dbs, $g, $gruppenid, $CMS_BENUTZERUEBERSICHTANZAHL, $CMS_URLGANZ, $GRUPPENRECHTE);
				if (strlen($termincode) > 0) {$code .= "<ul class=\"cms_terminuebersicht\">".$termincode."</ul>";}
				else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine anstehenden Termine vorhanden.</p>";}
			$code .= "</div></div>";
			$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Beschlüsse</h2>";
				$beschlusscode = cms_gruppenbeschluesse_ausgeben($dbs, $g, $gruppenid, $CMS_BENUTZERUEBERSICHTANZAHL, $CMS_URLGANZ, $GRUPPENRECHTE);
				if (strlen($beschlusscode) > 0) {$code .= "<ul class=\"cms_beschlussuebersicht\">".$beschlusscode."</ul>";}
				else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Beschlüsse vorhanden.</p>";}
			$code .= "</div></div>";

			$sql = $dbs->prepare("SELECT chataktiv FROM $gk WHERE id = ?");
			$sql->bind_param("i", $gruppenid);
			$sql->bind_result($chataktiv);
			$sql->execute();
			$sql->fetch();
			$sql->close();

			$sql = "DELETE FROM notifikationen WHERE person = ? AND art IN ('d', 'o') AND gruppe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND gruppenid = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("isi", $CMS_BENUTZERID, $g, $gruppenid);
			$sql->execute();
			$sql->close();

			// if($GRUPPENRECHTE["mitglied"] && $chataktiv) {
			// 	$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
			// 		$code .= "<h2>Chat</h2>";
			// 		$code .= cms_gruppenchat_ausgeben($dbs, $g, $gruppenid, $GRUPPENRECHTE);
			// 	$code .= "</div></div>";
			// }

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
				$code .= "<p><span class=\"cms_iconbutton_gesichert\" style=\"background-image: url('res/gruppen/gross/$icon')\">$gbez abonnieren</span></p>";
			}
			$code .= "<ul class=\"cms_aktionen_liste\">";
				//$code .= "<li><a class=\"cms_button\" href=\"$CMS_URLGANZ/Termine\">Kalender</a> </li> ";
				if (($g == "Gremien") || ($g == "Fachschaften")) {
					$code .= "<li><a class=\"cms_button\" href=\"$CMS_URLGANZ/Beschlüsse\">Beschlüsse</a> </li> ";
				}
				if ($CMS_BENUTZERART == 'l') {$allenschreiben = cms_r("schulhof.nutzerkonto.postfach.lehrer.$gk.mitglieder");;}
				else if ($CMS_BENUTZERART == 'e') {$allenschreiben = cms_r("schulhof.nutzerkonto.postfach.eltern.$gk.mitglieder");}
				else if ($CMS_BENUTZERART == 's') {$allenschreiben = cms_r("schulhof.nutzerkonto.postfach.schüler.$gk.mitglieder");}
				else if ($CMS_BENUTZERART == 'v') {$allenschreiben = cms_r("schulhof.nutzerkonto.postfach.verwaltungsangestellte.$gk.mitglieder");}
				else if ($CMS_BENUTZERART == 'x') {$allenschreiben = cms_r("schulhof.nutzerkonto.postfach.externe.$gk.mitglieder");}
				else {$allenschreiben = false;}
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
	$sql = "(SELECT person FROM $gk"."$personengruppe WHERE gruppe = ?) AS x";
	$sql = $dbs->prepare("SELECT y.id AS id, vorname, nachname, titel, nutzerkonten.id AS nutzerkonto FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN $sql ON personen.id = x.person) AS y LEFT JOIN nutzerkonten ON y.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC");
	$sql->bind_param("i", $gruppenid);
	if ($sql->execute()) {
		$sql->bind_result($pid, $pvor, $pnach, $ptitel, $pnutzer);
		while ($sql->fetch()) {
			if (is_null($pnutzer)) {
				$code .= "<li><span class=\"cms_button_passiv\" onclick=\"cms_meldung_keinkonto();\">".cms_generiere_anzeigename($pvor, $pnach, $ptitel)."</span></li> ";
			}
			else if (in_array($pid, $schreibpool)) {
				$code .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $pid)\">".cms_generiere_anzeigename($pvor, $pnach, $ptitel)."</span></li> ";
			}
			else {
				$code .= "<li><span class=\"cms_button_passivda\" onclick=\"cms_meldung_nichtschreiben();\">".cms_generiere_anzeigename($pvor, $pnach, $ptitel)."</span></li> ";
			}
		}
	}
	$sql->close();

	if (strlen($code) > 0) {$code = "<h3>".cms_vornegross($personengruppe)."</h3><ul class=\"cms_aktionen_liste\">".$code."</ul>";}
	return $code;
}
?>
