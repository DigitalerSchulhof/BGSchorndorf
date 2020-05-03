<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schuldetails</h1>

<?php
if (cms_r("schulhof.verwaltung.schule.adressen")) {
	$code = "</div>";
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "";
	$code .= "<h3>Schule</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td>".cms_generiere_input("cms_details_schulname", $CMS_SCHULE)."</td></tr>";
	$code .= "<tr><th>Genitiv des Namens:</th><td>".cms_generiere_input("cms_details_schulnamegenitiv", $CMS_SCHULE_GENITIV)."</td></tr>";
	$code .= "<tr><th>Ort:</th><td>".cms_generiere_input("cms_details_schulort", $CMS_ORT)."</td></tr>";
	$code .= "<tr><th>Straße und Hausnummer:</th><td>".cms_generiere_input("cms_details_schulstrasse", $CMS_STRASSE)."</td></tr>";
	$code .= "<tr><th>Postleitzahl und Ort:</th><td>".cms_generiere_input("cms_details_schulplzort", $CMS_PLZORT)."</td></tr>";
	$code .= "<tr><th>Telefonnummer:</th><td>".cms_generiere_input("cms_details_telefon", $CMS_TELEFON)."</td></tr>";
	$code .= "<tr><th>Faxnummer:</th><td>".cms_generiere_input("cms_details_telefax", $CMS_TELEFAX)."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_input("cms_details_email", $CMS_MAILSCHULE)."</td></tr>";
	$code .= "<tr><th>Domain:</th><td>".cms_generiere_input("cms_details_schuldomain", $CMS_DOMAIN)."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Schulleiter</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td>".cms_generiere_input("cms_details_nameschulleitung", $CMS_NAMESCHULLEITER)."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_input("cms_details_mailschulleitung", $CMS_MAILSCHULLEITER)."</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Datenschutzbeauftragter</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td>".cms_generiere_input("cms_details_namedatenschutz", $CMS_NAMEDATENSCHUTZ)."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_input("cms_details_maildatenschutz", $CMS_MAILDATENSCHUTZ)."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Verantwortlich im Sinne des Presserechts</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td>".cms_generiere_input("cms_details_namepresse", $CMS_NAMEPRESSERECHT)."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_input("cms_details_mailpresse", $CMS_MAILPRESSERECHT)."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Webmaster</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td>".cms_generiere_input("cms_details_namewebmaster", $CMS_NAMEWEBMASTER)."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_input("cms_details_mailwebmaster", $CMS_MAILWEBMASTER)."</td></tr>";
	$code .= "</table>";
	$code .= "<h3>Administrator</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Name:</th><td>".cms_generiere_input("cms_details_nameadmin", $CMS_NAMEADMINISTRATION)."</td></tr>";
	$code .= "<tr><th>eMailadresse:</th><td>".cms_generiere_input("cms_details_mailadmin", $CMS_MAILADMINISTRATION)."</td></tr>";
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
