<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neues Nutzerkonto anlegen</h1>

<?php
$fehler = false;

if (($CMS_RECHTE['Personen']['Nutzerkonten anlegen'])) {

	if (isset($_SESSION['PERSONENDETAILS'])) {
		$fehler = false;
		$dbs = cms_verbinden('s');
		$personenid = $_SESSION['PERSONENDETAILS'];
		$sql = "SELECT AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, nutzerkonten.id AS nutzerkonto FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = $personenid";

		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$art = $daten['art'];
				$vorname = $daten['vorname'];
				$nachname = $daten['nachname'];
				$titel = $daten['titel'];
				$nutzerkonto = $daten['nutzerkonto'];
			}
			else {$fehler = true;}
			$anfrage->free();
		}
		else {$fehler = true;}
		cms_trennen($dbs);

		$artkuerzel = '';

		if ($art == 's') {$art = 'Schüler'; $artkuerzel = 'S';}
		else if ($art == 'l') {$art = 'Lehrer'; $artkuerzel = 'L';}
		else if ($art == 'e') {$art = 'Eltern'; $artkuerzel = 'E';}
		else if ($art == 'v') {$art = 'Verwaltungsangestellte'; $artkuerzel = 'V';}
		else if ($art == 'x') {$art = 'Externe'; $artkuerzel = 'X';}
		else {$fehler = true;}

		if (!is_null($nutzerkonto)) {
			$fehler = true;
		}

		if (!$fehler) {
			$code = "<h3>Zugehörige Person</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr><th>Art:</th><td>$art</td></tr>";
				$code .= "<tr><th>Titel:</th><td>$titel</td></tr>";
				$code .= "<tr><th>Vorname:</th><td>$vorname</td></tr>";
				$code .= "<tr><th>Nachname:</th><td>$nachname</td></tr>";
			$code .= "</table>";

			$code .= "<h3>Daten für das Nutzerkonto</h3>";
			$code .= "<table class=\"cms_formular\">";
				$benutzername = substr($nachname,0,8).substr($vorname,0,3).'-bg';//.$artkuerzel;
				$code .= "<tr><th>Benutzername:</th><td><input type=\"text\" name=\"cms_schulhof_verwaltung_personen_neu_benutzername\" id=\"cms_schulhof_verwaltung_personen_neu_benutzername\" value=\"$benutzername\"></td></tr>";
				$code .= "<tr><th>Passwort:</th><td class=\"cms_notiz\">- wird automatisch generiert und an die eMailadresse verschickt -</td></tr>";
				$code .= "<tr><th>eMailadresse:</th><td><input type=\"email\" name=\"cms_schulhof_verwaltung_personen_neu_mail\" id=\"cms_schulhof_verwaltung_personen_neu_mail\" onkeyup=\"cms_check_mail_wechsel('verwaltung_personen_neu_mail');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_verwaltung_personen_neu_mail_icon\"><img src=\"res/icons/klein/falsch.png\"></span></td></tr>";
			$code .= "</table>";

			$code .= "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_nutzerkonto_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Abbrechen</a></p>";

			echo $code;
		}
		else {echo cms_meldung_fehler ();}
	}
	else {
		echo cms_meldung_bastler();
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
