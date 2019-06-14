<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Der Verwaltungsbereich</h1>

</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
// PERSONEN UND GRUPPEN
$tabzahl = 0;
$code = "";
if ($CMS_RECHTE['Personen']['Personen sehen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_personen\" href=\"Schulhof/Verwaltung/Personen\">";
			$code .= "<h3>Personen</h3>";
			$code .= "<p>Benutzerdaten und -konten von Schülern, Lehrern, Verwaltungsangestellten und Eltern verwalten, sowie Schüler mit Eltern verknüpfen.</p>";
		$code .= "</a>";
	$code .= "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Personen']['Rollen anlegen'] || $CMS_RECHTE['Personen']['Rollen bearbeiten'] || $CMS_RECHTE['Personen']['Rollen löschen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_rollen\" href=\"Schulhof/Verwaltung/Rollen\">";
			$code .=  "<h3>Rollen</h3>";
			$code .=  "<p>Rollen mit besonderen Rechten definieren.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Gremien anlegen'] || $CMS_RECHTE['Gruppen']['Gremien bearbeiten'] || $CMS_RECHTE['Gruppen']['Gremien löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_gremien\" href=\"Schulhof/Verwaltung/Gruppen/Gremien\">";
			$code .=  "<h3>Gremien</h3>";
			$code .=  "<p>Gremien erstellen, verwalten, löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Fachschaften anlegen'] || $CMS_RECHTE['Gruppen']['Fachschaften bearbeiten'] || $CMS_RECHTE['Gruppen']['Fachschaften löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_fachschaften\" href=\"Schulhof/Verwaltung/Gruppen/Fachschaften\">";
			$code .=  "<h3>Fachschaften</h3>";
			$code .=  "<p>Fachschaften anlegen, verwalten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Klassen anlegen'] || $CMS_RECHTE['Gruppen']['Klassen bearbeiten'] || $CMS_RECHTE['Gruppen']['Klassen löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_klassen\" href=\"Schulhof/Verwaltung/Gruppen/Klassen\">";
			$code .=  "<h3>Klassen und Tutorenklassen</h3>";
			$code .=  "<p>Klassen und Tutorenklassen anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Kurse anlegen'] || $CMS_RECHTE['Gruppen']['Kurse bearbeiten'] || $CMS_RECHTE['Gruppen']['Kurse löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_kurse\" href=\"Schulhof/Verwaltung/Gruppen/Kurse\">";
			$code .=  "<h3>Kurse</h3>";
			$code .=  "<p>Kurse anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Stufen anlegen'] || $CMS_RECHTE['Gruppen']['Stufen bearbeiten'] || $CMS_RECHTE['Gruppen']['Stufen löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_klassenstufen\" href=\"Schulhof/Verwaltung/Gruppen/Klassenstufen\">";
			$code .=  "<h3>Klassenstufen</h3>";
			$code .=  "<p>Klassenstufen anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Arbeitsgemeinschaften anlegen'] || $CMS_RECHTE['Gruppen']['Arbeitsgemeinschaften bearbeiten'] || $CMS_RECHTE['Gruppen']['Arbeitsgemeinschaften löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_ags\" href=\"Schulhof/Verwaltung/Gruppen/Arbeitsgemeinschaften\">";
			$code .=  "<h3>Arbeitsgemeinschaften</h3>";
			$code .=  "<p>Arbeitsgemeinschaften anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Arbeitskreise anlegen'] || $CMS_RECHTE['Gruppen']['Arbeitskreise bearbeiten'] || $CMS_RECHTE['Gruppen']['Arbeitskreise löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_aks\" href=\"Schulhof/Verwaltung/Gruppen/Arbeitskreise\">";
			$code .=  "<h3>Arbeitskreise</h3>";
			$code .=  "<p>Arbeitskreise anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Fahrten anlegen'] || $CMS_RECHTE['Gruppen']['Fahrten bearbeiten'] || $CMS_RECHTE['Gruppen']['Fahrten löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_fahrten\" href=\"Schulhof/Verwaltung/Gruppen/Fahrten\">";
			$code .=  "<h3>Fahrten</h3>";
			$code .=  "<p>Fahrten anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Wettbewerbe anlegen'] || $CMS_RECHTE['Gruppen']['Wettbewerbe bearbeiten'] || $CMS_RECHTE['Gruppen']['Wettbewerbe löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_wettbewerbe\" href=\"Schulhof/Verwaltung/Gruppen/Wettbewerbe\">";
			$code .=  "<h3>Wettbewerbe</h3>";
			$code .=  "<p>Wettbewerbe anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Ereignisse anlegen'] || $CMS_RECHTE['Gruppen']['Ereignisse bearbeiten'] || $CMS_RECHTE['Gruppen']['Ereignisse löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_ereignisse\" href=\"Schulhof/Verwaltung/Gruppen/Ereignisse\">";
			$code .=  "<h3>Ereignisse</h3>";
			$code .=  "<p>Ereignisse anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Gruppen']['Sonstige Gruppen anlegen'] || $CMS_RECHTE['Gruppen']['Sonstige Gruppen bearbeiten'] || $CMS_RECHTE['Gruppen']['Sonstige Gruppen löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_sonstige\" href=\"Schulhof/Verwaltung/Gruppen/Sonstige_Gruppen\">";
			$code .=  "<h3>Sonstige Gruppen</h3>";
			$code .=  "<p>Sonstige Gruppen anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}

if ($tabzahl > 0) {
	$code = "<h2>Personen und Gruppen</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
}
echo $code;
?>
</div>
</div>



<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php

// ORGANISATION
$tabzahl = 0;
$code = "";
if ($CMS_RECHTE['Organisation']['Schuljahre anlegen'] || $CMS_RECHTE['Organisation']['Schuljahre bearbeiten'] || $CMS_RECHTE['Organisation']['Schuljahre löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_schuljahre\" href=\"Schulhof/Verwaltung/Schuljahre\">";
			$code .=  "<h3>Schuljahre</h3>";
			$code .=  "<p>Schuljahre erstellen, bearbeiten und löschen. Schlüsselpositionen in den Schuljahren besetzen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}

if ($CMS_RECHTE['Organisation']['Räume anlegen'] || $CMS_RECHTE['Organisation']['Räume bearbeiten'] || $CMS_RECHTE['Organisation']['Räume löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_raeume\" href=\"Schulhof/Verwaltung/Räume\">";
			$code .=  "<h3>Räume</h3>";
			$code .=  "<p>Räume anlegen, bearbeiten und löschen, sowie sie als Klassenzimmer festlegen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Organisation']['Leihgeräte anlegen'] || $CMS_RECHTE['Organisation']['Leihgeräte bearbeiten'] || $CMS_RECHTE['Organisation']['Leihgeräte löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_leihgeraete\" href=\"Schulhof/Verwaltung/Leihgeräte\">";
			$code .=  "<h3>Leihgeräte</h3>";
			$code .=  "<p>Leihgeräte anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Organisation']['Fächer anlegen'] || $CMS_RECHTE['Organisation']['Fächer bearbeiten'] || $CMS_RECHTE['Organisation']['Fächer löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_faecher\" href=\"Schulhof/Verwaltung/Fächer\">";
			$code .=  "<h3>Fächer</h3>";
			$code .=  "<p>Fächer anlegen, bearbeiten und löschen, sowie Fächer den Klassenstufen zuordnen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Organisation']['Ferien anlegen'] || $CMS_RECHTE['Organisation']['Ferien bearbeiten'] || $CMS_RECHTE['Organisation']['Ferien löschen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_ferien\" href=\"Schulhof/Verwaltung/Ferien\">";
			$code .=  "<h3>Ferien</h3>";
			$code .=  "<p>Ferienkategorien anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Organisation']['Schulanmeldung vorbereiten'] || $CMS_RECHTE['Organisation']['Schulanmeldungen erfassen'] || $CMS_RECHTE['Organisation']['Schulanmeldungen bearbeiten'] || $CMS_RECHTE['Organisation']['Schulanmeldungen exportieren'] || $CMS_RECHTE['Organisation']['Schulanmeldungen löschen'] || $CMS_RECHTE['Organisation']['Schulanmeldungen akzeptieren']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_schulanmeldung\" href=\"Schulhof/Verwaltung/Schulanmeldung\">";
			$code .=  "<h3>Schulanmeldung</h3>";
			$code .=  "<p>Veröffentlichungszeitraum festlegen, Anschreiben einstellen, Profile anlegen, Fristen setzen, Anmeldungen erfassen, Anmeldungen bearbeiten, Daten exportieren, Daten löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Organisation']['Termine genehmigen'] || $CMS_RECHTE['Organisation']['Blogeinträge genehmigen'] || $CMS_RECHTE['Organisation']['Galerien genehmigen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_genehmigungen_oe\" href=\"Schulhof/Aufgaben/Öffentliche_Beiträge_genehmigen\">";
			$code .=  "<h3>Genehmigungscenter Öffentlichkeit</h3>";
			$code .=  "<p>Öffentliche Termine, Blogeinträge und Galerien bearbeiten und genehmigen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Organisation']['Gruppentermine genehmigen'] || $CMS_RECHTE['Organisation']['Gruppenblogeinträge genehmigen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_genehmigungen_in\" href=\"Schulhof/Aufgaben/Gruppeninterna_genehmigen\">";
			$code .=  "<h3>Genehmigungscenter Gruppeninterna</h3>";
			$code .=  "<p>Gruppeninterne Termine und Blogeinträge bearbeiten und genehmigen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($tabzahl > 0) {
	$code = "<h2>Organisation</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
}
echo $code;



// PLANUNG
$tabzahl = 0;
$code = "";

/*if ($CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume löschen'] ||
    $CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_stundenplanung\" href=\"Schulhof/Verwaltung/Stundenplanung\">";
			$code .=  "<h3>Stundenplanung</h3>";
			$code .=  "<p>Stundenplanzeiträume anlegen, bearbeiten und löschen. Stundenpläne anlegen, bearbeiten, löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Planung']['Vertretungen planen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_vertretungsplanung\" href=\"Schulhof/Verwaltung/Vertretungsplanung\">";
			$code .=  "<h3>Vertretungsplanung</h3>";
			$code .=  "<p>Vertretungen planen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}*/

if ($tabzahl > 0) {
	$code = "<h2>Planung</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
}
echo $code;
?>
</div>
</div>



<div class="cms_spalte_4">
<div class="cms_spalte_i">


<?php
// WEBSITE
$tabzahl = 0;
$code = "";
if ($CMS_RECHTE['Website']['Seiten anlegen'] || $CMS_RECHTE['Website']['Seiten bearbeiten'] || $CMS_RECHTE['Website']['Seiten löschen'] || $CMS_RECHTE['Website']['Startseite festlegen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_seiten\" href=\"Schulhof/Website/Seiten\">";
			$code .=  "<h3>Seiten</h3>";
			$code .=  "<p>Seiten anlegen, bearbeiten und löschen. Bestehende Seiten zur Startseite machen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Hauptnavigationen festlegen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_hauptnavigationen\" href=\"Schulhof/Website/Hauptnavigationen\">";
			$code .=  "<h3>Hauptnavigationen</h3>";
			$code .=  "<p>Hauptnavigationen festlegen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Dateien hochladen'] || $CMS_RECHTE['Website']['Dateien umbenennen'] || $CMS_RECHTE['Website']['Dateien löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_dateien\" href=\"Schulhof/Website/Dateien\">";
			$code .=  "<h3>Dateien</h3>";
			$code .=  "<p>Dateien hochladen, umbenennen und löschen, die auf der Website verwendet werden können, sowie Ordner anlegen, umbenennen und löschen, um diese Dateien zu organisieren.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Termine anlegen'] || $CMS_RECHTE['Website']['Termine bearbeiten'] || $CMS_RECHTE['Website']['Termine löschen']) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_termine\" href=\"Schulhof/Website/Termine\">";
			$code .=  "<h3>Termine</h3>";
			$code .=  "<p>Termine anlegen, bearbeiten und löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Blogeinträge anlegen'] || $CMS_RECHTE['Website']['Blogeinträge bearbeiten'] || $CMS_RECHTE['Website']['Blogeinträge löschen'] || $CMS_RECHTE['Website']['Inhalte freigeben']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_blog\" href=\"Schulhof/Website/Blogeinträge\">";
			$code .=  "<h3>Blogeinträge</h3>";
			$code .=  "<p>Blogeinträge anlegen, bearbeiten, löschen und freigeben.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Galerien anlegen'] || $CMS_RECHTE['Website']['Galerien bearbeiten'] || $CMS_RECHTE['Website']['Galerien löschen'] || $CMS_RECHTE['Website']['Inhalte freigeben']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_galerien\" href=\"Schulhof/Website/Galerien\">";
			$code .=  "<h3>Galerien</h3>";
			$code .=  "<p>Galerien anlegen, bearbeiten, löschen und freigeben.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Titelbilder hochladen'] || $CMS_RECHTE['Website']['Titelbilder umbenennen'] || $CMS_RECHTE['Website']['Titelbilder löschen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_titelbilder\" href=\"Schulhof/Website/Titelbilder\">";
			$code .=  "<h3>Titelbilder</h3>";
			$code .=  "<p>Titelbilder hochladen, umbenennen, löschen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'] || $CMS_RECHTE['Website']['Besucherstatistiken - Schulhof sehen']) {	//B
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_besucherstatistik\" href=\"Schulhof/Website/Besucherstatistiken\">";
			$code .=  "<h3>Besucherstatistiken</h3>";
			$code .=  "<p>Besucherstatistiken der Website und des Schulhofs sehen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Feedback sehen'] || $CMS_RECHTE['Website']['Feedback verwalten']) {	//B
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_feedback\" href=\"Schulhof/Website/Feedback\">";
			$code .=  "<h3>Feedback</h3>";
			$code .=  "<p>Feedback sehen".($CMS_RECHTE['Website']['Feedback verwalten']?" und verwalten":"").".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Website']['Fehlermeldungen sehen'] || $CMS_RECHTE['Website']['Fehlermeldungen verwalten']) {	//B
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_fehlermeldungen\" href=\"Schulhof/Website/Fehlermeldungen\">";
			$code .=  "<h3>Fehlermeldungen</h3>";
			$code .=  "<p>Fehlermeldungen sehen".($CMS_RECHTE['Website']['Fehlermeldungen verwalten']?" und verwalten":"").".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($tabzahl > 0) {
	$code = "<h2>Website</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
}
echo $code;

?>

<!-- <ul class="cms_uebersicht">
<li>
	<a class="cms_uebersicht_verwaltung_antraege" href="Schulhof/Verwaltung/Anträge/">
		<h3>Anträge</h3>
		<p>Anträge erstellen, bearbeiten und löschen. Festlegen, wer Anträge genehmigen darf.</p>
	</a>
</li>
	<li>
		<a class="cms_uebersicht_verwaltung_stundenplaene" href="Schulhof/Verwaltung/Stundenpläne/">
			<h3>Stundenpläne</h3>
			<p>Stundenpläne erstellen, bearbeiten und löschen.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_vertretungsplan" href="Schulhof/Verwaltung/Vertretungsplan/">
			<h3>Vertretungsplan</h3>
			<p>Vertretungen erstellen, bearbeiten und löschen.</p>
		</a>
	</li>
</ul> -->



<!-- <h2>Technik</h2>
<p class="cms_notiz">In Planung</p>
<ul class="cms_uebersicht">
	<li>
		<a class="cms_uebersicht_verwaltung_geraete" href="Schulhof/Verwaltung/Geräte/">
			<h3>Geräte</h3>
			<p>Geräte erstellen, bearbeiten, warten und löschen.</p>
		</a>
	</li>
</ul>


<h2>Noten</h2>
<p class="cms_notiz">In Planung in späteren Versionen</p>


<h2>Tagebuch</h2>
<p class="cms_notiz">In Planung in späteren Versionen</p> -->

</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php

// ADMINISTRATION
$tabzahl = 0;
$code = "";
if ($CMS_RECHTE['Administration']['Allgemeine Einstellungen vornehmen']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_allgemeine_einstellungen\" href=\"Schulhof/Verwaltung/Allgemeine_Einstellungen\">";
			$code .=  "<h3>Allgemeine Einstellungen</h3>";
			$code .=  "<p>Allgemeine Einstellungen zum Betrieb des Schulhofs vornehmen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Administration']['Schulnetze verwalten']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_schulnetze\" href=\"Schulhof/Verwaltung/Schulnetze\">";
			$code .=  "<h3>Schulnetze</h3>";
			$code .=  "<p>Hinterlegte Zugangsdaten für die verschiedenen Netzte (Schulhof, Lehrerzimmer und Verwaltung und Notennetz) verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Administration']['VPN verwalten']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_vpn\" href=\"Schulhof/Verwaltung/VPN\">";
			$code .=  "<h3>VPN</h3>";
			$code .=  "<p>Hinterlegte Software und Anleitung zur VPN-Konfiguration verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if ($CMS_RECHTE['Administration']['Zulässige Dateien verwalten']) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_zulaessig\" href=\"Schulhof/Verwaltung/Zulässige_Dateien\">";
			$code .=  "<h3>Zulässige Dateien</h3>";
			$code .=  "<p>Zulässige Datentypen und Dateigrößen einstellen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}
if (($CMS_RECHTE['Administration']['Adressen des Schulhofs verwalten']) || ($CMS_RECHTE['Administration']['Mailadresse des Schulhofs verwalten'])) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_schuldetails\" href=\"Schulhof/Verwaltung/Schuldetails\">";
			$code .=  "<h3>Schuldetails</h3>";
			$code .=  "<p>Einstellungen der Mailadresse des Schulhofs, der Adresse und des Webmasters.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
	$tabzahl++;
}

if ($tabzahl > 0) {
	$code = "<h2>Administration</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
}
echo $code;

?>

<!-- <p class="cms_notiz">In Planung</p>
<ul class="cms_uebersicht">
	<li>
		<a class="cms_uebersicht_verwaltung_dateien" href="Schulhof/Verwaltung/Dateien_auf_dem_Server/">
			<h3>Dateien auf dem Server</h3>
			<p>Alle Verzeichnisse, alle Dateien.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_hinweise" href="Schulhof/Verwaltung/Hinweise/">
			<h3>Hinweise</h3>
			<p>Hinweise verwalten, sowie Personen zuordnen, die Hinweise schreiben dürfen. Hinweise werden vor der Anmeldung zum Schulhof angezeigt.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_neuerungen" href="Schulhof/Verwaltung/Neuerungen/">
			<h3>Neuerungen</h3>
			<p>Neuerungen verwalten sowie Personen zuordnen, die Neuerungen schreiben dürfen. Neuerungen werden vor der Anmeldung zum Schulhof angezeigt. Durch Updates werden automatisch Neuerungen eingespielt. Diese können ergänzt, umgeschrieben oder entfernt werden.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_speicherplatz" href="Schulhof/Verwaltung/Speicherplatz/">
			<h3>Speicherplatz</h3>
			<p>Statistiken über die Verwendung des Online-Speichers, sowie Einstellungen von Speicherlimits.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_update" href="Schulhof/Verwaltung/Update/">
			<h3>Update</h3>
			<p>Softwareerneuerungen einspielen.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_backup" href="Schulhof/Verwaltung/Backup/">
			<h3>Manuelles Backup</h3>
			<p>Sichert Datenbank und Webserver manuell. Generiert Archive zum Download.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_haeufigefragen" href="Schulhof/Verwaltung/Häufige_Fragen/">
			<h3>Häufige Fragen</h3>
			<p>Häufige Fragen verwalten, sowie Personen zuordnen, die Häufige Fragen bearbeiten dürfen.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_verschluesselung" href="Schulhof/Verwaltung/Verschlüsselung/">
			<h3>Verschlüsselung</h3>
			<p>Persönliche Daten werden verschlüsselt in der Datenbank hinterlegt. Der notwendige Schlüssel liegt in einer config-Datei auf dem Webserver. Hier kann der Schlüssel geändert werden. Da dann die gesamte Datenbank ent- und neu verschlüsselt werden muss, kann dieser Prozess etwas dauern.</p>
		</a>
	</li>
</ul> -->

</div>
</div>

<div class="cms_clear"></div>