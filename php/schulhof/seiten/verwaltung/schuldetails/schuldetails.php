<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schuldetails</h1>

<?php
if (cms_r("schulhof.verwaltung.schule.details")) {
	$code = "</div>";
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "";
	$code .= "<h3>Schule</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td colspan=\"2\">".cms_generiere_input("cms_details_schulname", $CMS_WICHTIG['Schulname'])."</td></tr>";
	$code .= "<tr><th>Genitiv des Namens:</th><td colspan=\"2\">".cms_generiere_input("cms_details_schulnamegenitiv", $CMS_WICHTIG['Schulname Genitiv'])."</td></tr>";
	$code .= "<tr><th>Ort:</th><td colspan=\"2\">".cms_generiere_input("cms_details_schulort", $CMS_WICHTIG['Schule Ort'])."</td></tr>";
	$code .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\">".cms_generiere_input("cms_details_schulstrasse", $CMS_WICHTIG['Schule Straße'])."</td></tr>";
	$code .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\">".cms_generiere_input("cms_details_schulplzort", $CMS_WICHTIG['Schule PLZOrt'])."</td></tr>";
	$code .= "<tr><th>Telefonnummer:</th><td colspan=\"2\">".cms_generiere_input("cms_details_telefon", $CMS_WICHTIG['Schule Telefon'])."</td></tr>";
	$code .= "<tr><th>Faxnummer:</th><td colspan=\"2\">".cms_generiere_input("cms_details_telefax", $CMS_WICHTIG['Schule Fax'])."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_mailinput("cms_details_email", $CMS_WICHTIG['Schule Mail'])."</td></tr>";
	$code .= "<tr><th>Domain:</th><td colspan=\"2\">".cms_generiere_input("cms_details_schuldomain", $CMS_WICHTIG['Schule Domain'])."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Schulleitung</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td colspan=\"2\">".cms_generiere_input("cms_details_nameschulleitung", $CMS_WICHTIG['Schulleitung Name'])."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_mailinput("cms_details_mailschulleitung", $CMS_WICHTIG['Schulleitung Mail'])."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Datenschutzbeauftragter</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td colspan=\"2\">".cms_generiere_input("cms_details_namedatenschutz", $CMS_WICHTIG['Datenschutz Name'])."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_mailinput("cms_details_maildatenschutz", $CMS_WICHTIG['Datenschutz Mail'])."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Verantwortlich im Sinne des Presserechts</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td colspan=\"2\">".cms_generiere_input("cms_details_namepresse", $CMS_WICHTIG['Presse Name'])."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_mailinput("cms_details_mailpresse", $CMS_WICHTIG['Presse Mail'])."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Webmaster</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td colspan=\"2\">".cms_generiere_input("cms_details_namewebmaster", $CMS_WICHTIG['Webmaster Name'])."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_mailinput("cms_details_mailwebmaster", $CMS_WICHTIG['Webmaster Mail'])."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Administrator</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td colspan=\"2\">".cms_generiere_input("cms_details_nameadmin", $CMS_WICHTIG['Administration Name'])."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_mailinput("cms_details_mailadmin", $CMS_WICHTIG['Administration Mail'])."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";
	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_schuldetails_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
	$code .= "</div>";
	echo $code;
}
else {
	cms_meldung_berechtigung();
	echo "</div>";
}
?>
