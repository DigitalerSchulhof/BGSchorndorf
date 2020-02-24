<?php
function cms_appmenue() {
	global $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_SCHLUESSEL, $CMS_RECHTE, $CMS_IMLN, $CMS_EINSTELLUNGEN;
	$menue = "";

	if (($CMS_BENUTZERART == 's') || ($CMS_BENUTZERART == 'l')) {
		$menue .= "<li><a class=\"cms_uebersicht_app_meintag\" href=\"App/Mein_Tag\">";
			$menue .= "<h3>Mein Tag</h3>";
			$menue .= "<p>Vertretungsplan und aktuelle Tagesinformationen</p>";
		$menue .= "</a></li>";
	}

	// Daten für Postfach berechnen
	$dbp = cms_verbinden('p');
	$sql = $dbp->prepare("SELECT AES_DECRYPT(gelesen, '$CMS_SCHLUESSEL') AS gelesen, COUNT(gelesen) AS anzahl FROM posteingang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-' GROUP BY gelesen;");
	$anzahl['-'] = 0;
	$anzahl[1] = 0;
	if ($sql->execute()) {
		$sql->bind_result($pngelesen, $pnganzahl);
		while ($sql->fetch()) {
			$anzahl[$pngelesen] = $pnganzahl;
		}
	}
	$sql->close();
	$eingangneu = $anzahl['-'];
	$eingangalt = $anzahl[1];
	$eingangalle  = $eingangneu + $eingangalt;
	$entwuerfe = 0;
	$ausgang = 0;
	$papierkorb = 0;

	$sql = $dbp->prepare("SELECT COUNT(*) AS anzahl FROM postentwurf_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-'");
	if ($sql->execute()) {
		$sql->bind_result($entwuerfe);
		$sql->fetch();
	}
	$sql->close();

	$sql = $dbp->prepare("SELECT COUNT(*) AS anzahl FROM postausgang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-'");
	if ($sql->execute()) {
		$sql->bind_result($ausgang);
		$sql->fetch();
	}
	$sql->close();

	$sql = "SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM posteingang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '1') UNION ALL (SELECT COUNT(*) AS anzahl FROM postentwurf_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '1') UNION ALL ";
	$sql.= "(SELECT COUNT(*) AS anzahl FROM postausgang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '1')) AS nachrichtenpapierkorb";
	$sql = $dbp->prepare($sql);
	if ($sql->execute()) {
		$sql->bind_result($papierkorb);
		$sql->fetch();
	}
	$sql->close();
	cms_trennen($dbp);

	if ($eingangneu > 0) {$mzeingang = " (<b>$eingangneu</b> / $eingangalle)";}
	else if ($eingangalle > 0) {$mzeingang = " ($eingangalle)";}
	else {$mzeingang = "";}

	if ($entwuerfe > 0) {$mzentwuerfe = " ($entwuerfe)";}
	else {$mzentwuerfe = "";}

	if ($ausgang > 0) {$mzausgang = " ($ausgang)";}
	else {$mzausgang = "";}

	if ($papierkorb > 0) {$mzpapierkorb = " ($papierkorb)";}
	else {$mzpapierkorb = "";}

	$menue .= "<li><span class=\"cms_uebersicht_app_postfach cms_appmenue_element\">";
		$menue .= "<h3>Postfach</h3>";
		$menue .= "<p><a class=\"cms_appmenue_uliste\" href=\"javascript:cms_schulhof_postfach_nachricht_vorbereiten ('neu', '', '', '', '-', '-', 'app')\">Neue Nachricht</a>";
		$menue .= "<a class=\"cms_appmenue_uliste\" href=\"App/Postfach/Posteingang\">Posteingang$mzeingang</a>";
		$menue .= "<a class=\"cms_appmenue_uliste\" href=\"App/Postfach/Entwürfe\">Entwürfe$mzentwuerfe</a>";
		$menue .= "<a class=\"cms_appmenue_uliste\" href=\"App/Postfach/Postausgang\">Postausgang$mzausgang</a>";
		$menue .= "<a class=\"cms_appmenue_uliste\" href=\"App/Postfach/Papierkorb\">Papierkorb$mzpapierkorb</a></p>";
	$menue .= "</span></li>";


	/*if ($CMS_RECHTE['Planung']['Buchungen vornehmen']) {
		$menue .= "<li><a class=\"cms_uebersicht_app_buchen\" href=\"App/Aufgaben/Buchen\">";
			$menue .= "<h3>Buchen</h3>";
			$menue .= "<p>Leihgeräte oder Räume buchen</p>";
		$menue .= "</a></li>";
	}*/
	if ($CMS_RECHTE['Technik']['Geräte-Probleme melden']) {
		$menue .= "<li><a class=\"cms_uebersicht_app_geraetemelden\" href=\"App/Probleme_melden/Geräte\">";
			$menue .= "<h3>Geräte melden</h3>";
			$menue .= "<p>Defekte Geräte den zuständigen Stellen melden</p>";
		$menue .= "</a></li>";
	}/*
	if ($CMS_RECHTE['Technik']['Geräte verwalten']) {
		$menue .= "<li><a class=\"cms_uebersicht_app_geraeteverwalten\" href=\"App/Aufgaben/Geräte_verwalten\">";
			$menue .= "<h3>Geräte verwalten</h3>";
			$menue .= "<p>Gerätezustände ändern oder Aufgaben deligieren</p>";
		$menue .= "</a></li>";
	}*/
	if ($CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen']) {
		$menue .= "<li><a class=\"cms_uebersicht_app_hausmeisterauftraege\" href=\"App/Probleme_melden/Hausmeisteraufträge\">";
			$menue .= "<h3>Hausmeisteraufträge erteilen</h3>";
			$menue .= "<p>Hausmeisteraufträge erteilen</p>";
		$menue .= "</a></li>";
	}/*
	if ($CMS_RECHTE['Technik']['Hausmeisteraufträge markieren']) {
		$menue .= "<li><a class=\"cms_uebersicht_app_hausmeister\" href=\"App/Aufgaben/Hausmeisteraufträge\">";
			$menue .= "<h3>Hausmeisteraufträge markieren</h3>";
			$menue .= "<p>Hausmeisteraufträge markieren</p>";
		$menue .= "</a></li>";
	}*/


	if (($CMS_RECHTE['Tagebücher']['Notfallzustand']) && ($CMS_IMLN)) {
		if ($CMS_EINSTELLUNGEN['Tagebuch Notfallzustand'] == '0') {
			$menue .= "<li><a class=\"cms_uebersicht_app_notfallzustand\" href=\"javascript:cms_notfallzustand_anzeigen('1', 'app')\">";
				$menue .= "<h3>Notfallzustand ausrufen</h3>";
				$menue .= "<p>Fordert alle Schüler und Lehrer auf, das Gebäude zu verlassen.<br>Lehrer erhalten Schülerlisten in der App und auf der Website, deren Anwensenheit zu überprüfen ist.</p>";
			$menue .= "</a></li>";
		}
		else {
			$menue .= "<li><a class=\"cms_uebersicht_app_notfallzustand\" href=\"javascript:cms_notfallzustand_anzeigen('0', 'app')\">";
				$menue .= "<h3>Notfallzustand aufheben</h3>";
				$menue .= "<p>Der Notfallzustand wird aufgehoben. Anwesenheitslisten werden gelöscht.</p>";
			$menue .= "</a></li>";
		}
	}

	if (strlen($menue) > 0) {
		$menue = "<ul class=\"cms_uebersicht\">".$menue."</ul>";
	}
	else {
		$menue = "<p class=\"cms_notiz\">Es stehen momentan keine Dienste zur Verfügung.</p>";
	}

	return $menue."<p><span class=\"cms_button_nein\" onclick=\"cms_abmelden('app')\"> Abmelden</span></p>";
}
?>
