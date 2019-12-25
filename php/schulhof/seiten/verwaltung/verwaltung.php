<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Der Verwaltungsbereich</h1>
<?php
$ausgabe = "";
// PERSONEN UND GRUPPEN
$code = "";
if (r("schulhof.verwaltung.personen.sehen || schulhof.verwaltung.personen.anlegen || schulhof.verwaltung.personen.bearbeiten || schulhof.verwaltung.personen.löschen || schulhof.verwaltung.personen.daten")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_personen\" href=\"Schulhof/Verwaltung/Personen\">";
			$code .= "<h3>Personen</h3>";
			$code .= "<p>Benutzerdaten und -konten von Schülern, Lehrern, Verwaltungsangestellten und Eltern ".aufzaehlen(array("sehen" => r("schulhof.verwaltung.personen.sehen || schulhof.verwaltung.personen.daten"), "verwalten" => r("schulhof.verwaltung.personen.anlegen || schulhof.verwaltung.personen.bearbeiten || schulhof.verwaltung.personen.löschen")))."</p>";
		$code .= "</a>";
	$code .= "</li>";
}
if (r("schulhof.verwaltung.rechte.rollen.*")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_rollen\" href=\"Schulhof/Verwaltung/Rollen\">";
			$code .=  "<h3>Rollen</h3>";
			$code .=  "<p>Rollen mit besonderen Rechten definieren.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.gremien.anlegen || schulhof.gruppen.gremien.bearbeiten || schulhof.gruppen.gremien.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_gremien\" href=\"Schulhof/Verwaltung/Gruppen/Gremien\">";
			$code .=  "<h3>Gremien</h3>";
			$code .=  "<p>Gremien ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.gremien.anlegen"), "bearbeiten" => r("schulhof.gruppen.gremien.bearbeiten"), "löschen" => r("schulhof.gruppen.gremien.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.fachschaften.anlegen || schulhof.gruppen.fachschaften.bearbeiten || schulhof.gruppen.fachschaften.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_fachschaften\" href=\"Schulhof/Verwaltung/Gruppen/Fachschaften\">";
			$code .=  "<h3>Fachschaften</h3>";
			$code .=  "<p>Fachschaften ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.fachschaften.anlegen"), "bearbeiten" => r("schulhof.gruppen.fachschaften.bearbeiten"), "löschen" => r("schulhof.gruppen.fachschaften.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.klassen.anlegen || schulhof.gruppen.klassen.bearbeiten || schulhof.gruppen.klassen.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_klassen\" href=\"Schulhof/Verwaltung/Gruppen/Klassen\">";
			$code .=  "<h3>Klassen und Tutorenklassen</h3>";
			$code .=  "<p>Klassen und Tutorenklassen ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.klassen.anlegen"), "bearbeiten" => r("schulhof.gruppen.klassen.bearbeiten"), "löschen" => r("schulhof.gruppen.klassen.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.kurse.anlegen || schulhof.gruppen.kurse.bearbeiten || schulhof.gruppen.kurse.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_kurse\" href=\"Schulhof/Verwaltung/Gruppen/Kurse\">";
			$code .=  "<h3>Kurse</h3>";
			$code .=  "<p>Kurse ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.kurse.anlegen"), "bearbeiten" => r("schulhof.gruppen.kurse.bearbeiten"), "löschen" => r("schulhof.gruppen.kurse.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.stufen.anlegen || schulhof.gruppen.stufen.bearbeiten || schulhof.gruppen.stufen.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_klassenstufen\" href=\"Schulhof/Verwaltung/Gruppen/Stufen\">";
			$code .=  "<h3>Klassenstufen</h3>";
			$code .=  "<p>Klassenstufen ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.stufen.anlegen"), "bearbeiten" => r("schulhof.gruppen.stufen.bearbeiten"), "löschen" => r("schulhof.gruppen.stufen.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.arbeitsgemeinschaften.anlegen || schulhof.gruppen.arbeitsgemeinschaften.bearbeiten || schulhof.gruppen.arbeitsgemeinschaften.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_ags\" href=\"Schulhof/Verwaltung/Gruppen/Arbeitsgemeinschaften\">";
			$code .=  "<h3>Arbeitsgemeinschaften</h3>";
			$code .=  "<p>Arbeitsgemeinschaften ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.arbeitsgemeinschaften.anlegen"), "bearbeiten" => r("schulhof.gruppen.arbeitsgemeinschaften.bearbeiten"), "löschen" => r("schulhof.gruppen.arbeitsgemeinschaften.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.arbeitskreise.anlegen || schulhof.gruppen.arbeitskreise.bearbeiten || schulhof.gruppen.arbeitskreise.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_aks\" href=\"Schulhof/Verwaltung/Gruppen/Arbeitskreise\">";
			$code .=  "<h3>Arbeitskreise</h3>";
			$code .=  "<p>Arbeitskreise ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.arbeitskreise.anlegen"), "bearbeiten" => r("schulhof.gruppen.arbeitskreise.bearbeiten"), "löschen" => r("schulhof.gruppen.arbeitskreise.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.fahrten.anlegen || schulhof.gruppen.fahrten.bearbeiten || schulhof.gruppen.fahrten.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_fahrten\" href=\"Schulhof/Verwaltung/Gruppen/Fahrten\">";
			$code .=  "<h3>Fahrten</h3>";
			$code .=  "<p>Fahrten ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.fahrten.anlegen"), "bearbeiten" => r("schulhof.gruppen.fahrten.bearbeiten"), "löschen" => r("schulhof.gruppen.fahrten.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.wettbewerbe.anlegen || schulhof.gruppen.wettbewerbe.bearbeiten || schulhof.gruppen.wettbewerbe.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_wettbewerbe\" href=\"Schulhof/Verwaltung/Gruppen/Wettbewerbe\">";
			$code .=  "<h3>Wettbewerbe</h3>";
			$code .=  "<p>Wettbewerbe ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.wettbewerbe.anlegen"), "bearbeiten" => r("schulhof.gruppen.wettbewerbe.bearbeiten"), "löschen" => r("schulhof.gruppen.wettbewerbe.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.ereignisse.anlegen || schulhof.gruppen.ereignisse.bearbeiten || schulhof.gruppen.ereignisse.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_ereignisse\" href=\"Schulhof/Verwaltung/Gruppen/Ereignisse\">";
			$code .=  "<h3>Ereignisse</h3>";
			$code .=  "<p>Ereignisse ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.ereignisse.anlegen"), "bearbeiten" => r("schulhof.gruppen.ereignisse.bearbeiten"), "löschen" => r("schulhof.gruppen.ereignisse.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.gruppen.sonstigegruppen.anlegen || schulhof.gruppen.sonstigegruppen.bearbeiten || schulhof.gruppen.sonstigegruppen.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_sonstige\" href=\"Schulhof/Verwaltung/Gruppen/Sonstige_Gruppen\">";
			$code .=  "<h3>Sonstige Gruppen</h3>";
			$code .=  "<p>Sonstige Gruppen ".aufzaehlen(array("anlegen" => r("schulhof.gruppen.sonstigegruppen.anlegen"), "bearbeiten" => r("schulhof.gruppen.sonstigegruppen.bearbeiten"), "löschen" => r("schulhof.gruppen.sonstigegruppen.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.verwaltung.nutzerkonten.verstöße.chatmeldungen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_chatmeldungen\" href=\"Schulhof/Aufgaben/Chatmeldungen\">";
			$code .=  "<h3>Chatmeldungen</h3>";
			$code .=  "<p>Chatmeldungen sehen und verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}

if ($code) {
	$ausgabe .=  "<div class=\"cms_spalte_4\">";
		$ausgabe .=  "<div class=\"cms_spalte_i\">";
			$ausgabe .=  "<h2>Personen und Gruppen</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
		$ausgabe .=  "</div>";
	$ausgabe .=  "</div>";
}

// PLANUNG
$code = "";
if (r("schulhof.planung.schuljahre.fabrik")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_schuljahrfabrik\" href=\"javascript:cms_schuljahrfabrik_vorbereiten($CMS_BENUTZERSCHULJAHR)\">";
			$code .=  "<h3>Schuljahrfabrik</h3>";
			$code .=  "<p>Aus bestehendem Schuljahr neues Schuljahr inklusive Stufen, Klassen und Kurse generieren.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.schuljahre.planungszeiträume.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_zeitraeume\" href=\"javascript:cms_stundenplanzeitraeume_vorbereiten($CMS_BENUTZERSCHULJAHR)\">";
			$code .=  "<h3>Stundenplanzeiträume</h3>";
			$code .=  "<p>Für einzelne Schuljahre Planungszeiträume ".aufzaehlen(array("anlegen" => r("schulhof.planung.schuljahre.planungszeiträume.anlegen"), "bearbeiten" => r("schulhof.planung.schuljahre.planungszeiträume.bearbeiten"), "löschen" => r("schulhof.planung.schuljahre.planungszeiträume.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.schuljahre.fächer.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_faecher\" href=\"javascript:cms_faecher_vorbereiten($CMS_BENUTZERSCHULJAHR)\">";
			$code .=  "<h3>Fächer</h3>";
			$code .=  "<p>Fächer ".aufzaehlen(array("anlegen" => r("schulhof.planung.schuljahre.fächer.anlegen"), "bearbeiten" => r("schulhof.planung.schuljahre.fächer.bearbeiten"), "löschen" => r("schulhof.planung.schuljahre.fächer.löschen"))).(r("schulhof.planung.schuljahre.fächer.koppeln.*")?", sowie Fächer an ".aufzaehlen(array("Räume" => r("schulhof.planung.schuljahre.fächer.koppeln.räume"), "Zeiten" => r("schulhof.planung.schuljahre.fächer.koppeln.zeiten"), "Lehrer" => r("schulhof.planung.schuljahre.fächer.koppeln.lehrer")))." koppeln.":"")."</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.schuljahre.profile.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_profile\" href=\"javascript:cms_profile_vorbereiten($CMS_BENUTZERSCHULJAHR)\">";
			$code .=  "<h3>Profile</h3>";
			$code .=  "<p>Für einzelne Schuljahre Profile anlegen und die entsprechenden Wahlmöglichkeiten definieren.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.schienen.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_schienen\" href=\"javascript:cms_schienen_vorbereiten($CMS_BENUTZERSCHULJAHR)\">";
			$code .=  "<h3>Schienen</h3>";
			$code .=  "<p>Für jede Klassenstufe Unterricht festlegen, der parallel laufen muss.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_stundenplanung\" href=\"javascript:cms_stundenplanung_vorbereiten($CMS_BENUTZERSCHULJAHR, '-')\">";
			$code .=  "<h3>Stundenplanung</h3>";
			$code .=  "<p>Regelstundenpläne eingeben und ändern.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.schuljahre.stundentagebücher.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_stundenerzeugen\" href=\"javascript:cms_stundenerzeugen_vorbereiten($CMS_BENUTZERSCHULJAHR, '-')\">";
			$code .=  "<h3>Stunden und Tagebücher erzeugen</h3>";
			$code .=  "<p>Aus den Regelstunden Unterrichtsstunden für die einzelnen Unterrichtstage unter Berücksichtigung der angelegten Rythmen und Ferien erzeugen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.vertretungsplan.vertretungsplanung")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_vertretungsplanung\" href=\"Schulhof/Verwaltung/Planung/Vertretungsplanung\">";
			$code .=  "<h3>Vertretungsplan</h3>";
			$code .=  "<p>Schulstunden ändern, verschieben oder entfallen lassen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.vertretungsplan.ausplanungen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_planung_ausplanung\" href=\"Schulhof/Verwaltung/Planung/Ausplanungen\">";
			$code .=  "<h3>Ausplanungen durchführen</h3>";
			$code .=  "<p>Räume, Lehrer, Klassen und Kurse ausplanen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}

if ($code) {
	$ausgabe .=  "<div class=\"cms_spalte_4\">";
		$ausgabe .=  "<div class=\"cms_spalte_i\">";
			$ausgabe .=  "<h2>Planung</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
		$ausgabe .=  "</div>";
	$ausgabe .=  "</div>";
}

// ORGANISATION
$code = "";
if (r("schulhof.planung.schuljahre.anlegen || schulhof.planung.schuljahre.bearbeiten || schulhof.planung.schuljahre.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_schuljahre\" href=\"Schulhof/Verwaltung/Schuljahre\">";
			$code .=  "<h3>Schuljahre</h3>";
			$code .=  "<p>Schuljahre ".aufzaehlen(array("anlegen" => r("schulhof.planung.schuljahre.anlegen"), "bearbeiten" => r("schulhof.planung.schuljahre.bearbeiten"), "löschen" => r("schulhof.planung.schuljahre.löschen"))).". Schlüsselpositionen in den Schuljahren besetzen.</p>";		// TODO: Recht Schlüsselpositionen
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.planung.räume.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_raeume\" href=\"Schulhof/Verwaltung/Räume\">";
			$code .=  "<h3>Räume</h3>";
			$code .=  "<p>Räume ".aufzaehlen(array("anlegen" => r("schulhof.planung.räume.anlegen"), "bearbeiten" => r("schulhof.planung.räume.bearbeiten"), "löschen" => r("schulhof.planung.räume.löschen"))).", sowie sie als Klassenzimmer festlegen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.organisation.leihgeräte.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_leihgeraete\" href=\"Schulhof/Verwaltung/Leihgeräte\">";
			$code .=  "<h3>Leihgeräte</h3>";
			$code .=  "<p>Leihgeräte ".aufzaehlen(array("anlegen" => r("schulhof.organisation.leihgeräte.anlegen"), "bearbeiten" => r("schulhof.organisation.leihgeräte.bearbeiten"), "löschen" => r("schulhof.organisation.leihgeräte.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.organisation.ferien.*")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_ferien\" href=\"Schulhof/Verwaltung/Ferien\">";
			$code .=  "<h3>Ferien</h3>";
			$code .=  "<p>Ferienkategorien ".aufzaehlen(array("anlegen" => r("schulhof.organisation.ferien.anlegen"), "bearbeiten" => r("schulhof.organisation.ferien.bearbeiten"), "löschen" => r("schulhof.organisation.ferien.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.organisation.schulanmeldung.*")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_schulanmeldung\" href=\"Schulhof/Verwaltung/Schulanmeldung\">";
			$code .=  "<h3>Schulanmeldung</h3>";
			$code .=  "<p>Veröffentlichungszeitraum festlegen, Anschreiben einstellen, Profile anlegen, Fristen setzen. ".(r("schulhof.organisation.schulanmeldung.erfassen || schulhof.organisation.schulanmeldung.bearbeiten || schulhof.organisation.schulanmeldung.löschen || schulhof.organisation.schulanmeldung.exportieren")?"Anmeldungen ".aufzaehlen(array("erfassen" => r("schulhof.organisation.schulanmeldung.erfassen"), "bearbeiten" => r("schulhof.organisation.schulanmeldung.bearbeiten"), "löschen" => r("schulhof.organisation.schulanmeldung.löschen"), "exportieren" => r("schulhof.organisation.schulanmeldung.exportieren"))).".":"")."</p>";	// TODO: Beschreibung
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("artikel.genehmigen.termine")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_genehmigungen_termine\" href=\"Schulhof/Aufgaben/Termine_genehmigen\">";
			$code .=  "<h3>Genehmigungscenter Termine</h3>";
			$code .=  "<p>Öffentliche und gruppeninterne Termine bearbeiten und genehmigen.</p>";	// TODO: Recht gruppenintern genehmigen
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("artikel.genehmigen.blogeinträge")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_genehmigungen_blogeintraege\" href=\"Schulhof/Aufgaben/Blogeinträge_genehmigen\">";
			$code .=  "<h3>Genehmigungscenter Blogeinträge</h3>";
			$code .=  "<p>Öffentliche und gruppeninterne Blogeinträge bearbeiten und genehmigen.</p>";	// TODO: Recht gruppenintern genehmigen
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("artikel.genehmigen.galerien")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_genehmigungen_galerien\" href=\"Schulhof/Aufgaben/Galerien_genehmigen\">";
			$code .=  "<h3>Genehmigungscenter Galerien</h3>";
			$code .=  "<p>Öffentliche Galerien bearbeiten und genehmigen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.information.dauerbrenner.*")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_dauerbrenner\" href=\"Schulhof/Verwaltung/Dauerbrenner\">";
			$code .=  "<h3>Dauerbrenner</h3>";
			$code .=  "<p>Dauerbrenner ".aufzaehlen(array("anlegen" => r("schulhof.information.dauerbrenner.anlegen"), "bearbeiten" => r("schulhof.information.dauerbrenner.bearbeiten"), "löschen" => r("schulhof.information.dauerbrenner.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.information.pinnwände.*")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_pinnwaende\" href=\"Schulhof/Verwaltung/Pinnwände\">";
			$code .=  "<h3>Pinnwände</h3>";
			$code .=  "<p>Pinnwände ".aufzaehlen(array("anlegen" => r("schulhof.information.pinnwände.anlegen"), "bearbeiten" => r("schulhof.information.pinnwände.bearbeiten"), "löschen" => r("schulhof.information.pinnwände.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}

if ($code) {
	$ausgabe .=  "<div class=\"cms_spalte_4\">";
		$ausgabe .=  "<div class=\"cms_spalte_i\">";
			$ausgabe .=  "<h2>Organisation</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
		$ausgabe .=  "</div>";
	$ausgabe .=  "</div>";
}

// WEBSITE
$code = "";
if (r("website.seiten.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_seiten\" href=\"Schulhof/Website/Seiten\">";
			$code .=  "<h3>Seiten</h3>";
			$code .=  "<p>".aufzaehlen('Seiten $. ', array("anlegen" => r("schulhof.information.pinnwände.anlegen"), "bearbeiten" => r("schulhof.information.pinnwände.bearbeiten"), "löschen" => r("schulhof.information.pinnwände.löschen"))).(r("website.seiten.startseite")?"Startseite festlegen.":"")."</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("website.navigation")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_hauptnavigationen\" href=\"Schulhof/Website/Hauptnavigationen\">";
			$code .=  "<h3>Hauptnavigationen</h3>";
			$code .=  "<p>Hauptnavigationen festlegen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("website.dateien.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_dateien\" href=\"Schulhof/Website/Dateien\">";
			$code .=  "<h3>Dateien</h3>";
			$code .=  "<p>Dateien ".aufzaehlen(array("hochladen" => r("website.dateien.hochladen"), "umbenennen" => r("website.dateien.umbenennen"), "löschen" => r("website.dateien.löschen"))).", die auf der Website verwendet werden können, sowie Ordner ".aufzaehlen(array("hochladen" => r("website.dateien.hochladen"), "umbenennen" => r("website.dateien.umbenennen"), "löschen" => r("website.dateien.löschen"))).", um diese Dateien zu organisieren.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("artikel.öffentlich.termine.*")) {
	$code .= "<li>";
		$code .= "<a class=\"cms_uebersicht_verwaltung_termine\" href=\"Schulhof/Website/Termine\">";
			$code .=  "<h3>Termine</h3>";
			$code .=  "<p>Termine ".aufzaehlen(array("anlegen" => r("artikel.öffentlich.termine.anlegen"), "bearbeiten" => r("artikel.öffentlich.termine.bearbeiten"), "löschen" => r("artikel.öffentlich.termine.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("artikel.öffentlich.blogeinträge.* || website.freigeben")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_blog\" href=\"Schulhof/Website/Blogeinträge\">";
			$code .=  "<h3>Blogeinträge</h3>";
			$code .=  "<p>Blogeinträge ".aufzaehlen(array("anlegen" => r("artikel.öffentlich.blogeinträge.anlegen"), "bearbeiten" => r("artikel.öffentlich.blogeinträge.bearbeiten"), "löschen" => r("artikel.öffentlich.blogeinträge.löschen"), "freigeben" => r("website.freigeben"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("artikel.öffentlich.galerien.* || website.freigeben")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_galerien\" href=\"Schulhof/Website/Galerien\">";
			$code .=  "<h3>Galerien</h3>";
			$code .=  "<p>Galerien ".aufzaehlen(array("anlegen" => r("artikel.öffentlich.galerien.anlegen"), "bearbeiten" => r("artikel.öffentlich.galerien.bearbeiten"), "löschen" => r("artikel.öffentlich.galerien.löschen"), "freigeben" => r("website.freigeben"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("website.titelbilder.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_titelbilder\" href=\"Schulhof/Website/Titelbilder\">";
			$code .=  "<h3>Titelbilder</h3>";
			$code .=  "<p>Titelbilder ".aufzaehlen(array("hochladen" => r("website.titelbilder.hochladen"), "umbenennen" => r("website.titelbilder.umbenennen"), "löschen" => r("website.titelbilder.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("website.auszeichnungen.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_auszeichnungen\" href=\"Schulhof/Website/Auszeichnungen\">";
			$code .=  "<h3>Auszeichnungen</h3>";
			$code .=  "<p>Auszeichnungen ".aufzaehlen(array("anlegen" => r("website.auszeichnungen.anlegen"), "bearbeiten" => r("website.auszeichnungen.bearbeiten"), "löschen" => r("website.auszeichnungen.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("statistik.besucher.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_besucherstatistik\" href=\"Schulhof/Website/Besucherstatistiken\">";
			$code .=  "<h3>Besucherstatistiken</h3>";
			$code .=  "<p>".aufzaehlen(
				'Besucherstatistiken $ sehen. ',
				array(
					"der Website" => r("statistik.besucher.website.*"),
					"des Schulhof" => r("statistik.besucher.schulhof.sehen")
				)).(
					r("statistik.besucher.schulhof.anteile.*")?"Besucheranteile sehen.":""
				)."</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("technik.feedback")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_feedback\" href=\"Schulhof/Website/Feedback\">";
			$code .=  "<h3>Feedback</h3>";
			$code .=  "<p>Feedback sehen und verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("technik.fehlermeldungen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_fehlermeldungen\" href=\"Schulhof/Website/Fehlermeldungen\">";
			$code .=  "<h3>Fehlermeldungen</h3>";
			$code .=  "<p>Fehlermeldungen sehen und verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_auffaellig\" href=\"Schulhof/Aufgaben/Auffälliges\">";
			$code .=  "<h3>Auffälliges Verhalten</h3>";
			$code .=  "<p>Auffälliges Verhalten von Nutzern sehen und verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("*") && false) {	// TODO: Recht
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_emoticons\" href=\"Schulhof/Website/Emoticons\">";
			$code .=  "<h3>Emoticons</h3>";
			$code .=  "<p>Emoticons verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.information.newsletter.*")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_website_newsletter\" href=\"Schulhof/Website/Newsletter\">";
			$code .=  "<h3>Newsletter</h3>";
			$code .=  "<p>Newsletter und Mailinglisten verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}

if ($code) {
	$ausgabe .=  "<div class=\"cms_spalte_4\">";
		$ausgabe .=  "<div class=\"cms_spalte_i\">";
			$ausgabe .=  "<h2>Website</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
		$ausgabe .=  "</div>";
	$ausgabe .=  "</div>";
}

// TECHNIK
$code = "";
if (r("schulhof.technik.geräte.verwalten")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_technik_geraete\" href=\"Schulhof/Aufgaben/Geräte_verwalten\">";
			$code .=  "<h3>Geräte verwalten</h3>";
			$code .=  "<p>Fehlermeldungen zu Geräten verarbeiten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.technik.hausmeisteraufträge.sehen || schulhof.technik.hausmeisteraufträge.markieren || schulhof.technik.hausmeisteraufträge.löschen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_technik_hausmeister\" href=\"Schulhof/Hausmeister/Aufträge\">";
			$code .=  "<h3>Hausmeisteraufräge</h3>";
			$code .=  "<p>Hausmeisteraufträge ".aufzaehlen(array("sehen" => r("schulhof.technik.hausmeisteraufträge.sehen"), "markieren" => r("schulhof.technik.hausmeisteraufträge.markieren"), "löschen" => r("schulhof.technik.hausmeisteraufträge.löschen"))).".</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("technik.haustechnik")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_technik_haustechnik\" href=\"Schulhof/Verwaltung/Haustechnik\">";
			$code .=  "<h3>Haustechnikausgabe verwalten</h3>";
			$code .=  "<p>Auswählen der Informationen, die der Haustechnikschnittstelle zur Verfügung gestellt werden.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}

if ($code) {
	$ausgabe .=  "<div class=\"cms_spalte_4\">";
		$ausgabe .=  "<div class=\"cms_spalte_i\">";
			$ausgabe .=  "<h2>Technik</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
		$ausgabe .=  "</div>";
	$ausgabe .=  "</div>";
}

// ADMINISTRATION
$code = "";
if (r("schulhof.verwaltung.einstellungen")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_allgemeine_einstellungen\" href=\"Schulhof/Verwaltung/Allgemeine_Einstellungen\">";
			$code .=  "<h3>Allgemeine Einstellungen</h3>";
			$code .=  "<p>Allgemeine Einstellungen zum Betrieb des Schulhofs vornehmen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("technik.server.netze")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_schulnetze\" href=\"Schulhof/Verwaltung/Schulnetze\">";
			$code .=  "<h3>Schulnetze</h3>";
			$code .=  "<p>Hinterlegte Zugangsdaten für die verschiedenen Netzte (Schulhof, Lehrerzimmer und Verwaltung und Notennetz) verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("technik.server.vpn")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_vpn\" href=\"Schulhof/Verwaltung/VPN\">";
			$code .=  "<h3>VPN</h3>";
			$code .=  "<p>Hinterlegte Software und Anleitung zur VPN-Konfiguration verwalten.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("technik.server.dateienerlaubnis")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_zulaessig\" href=\"Schulhof/Verwaltung/Zulässige_Dateien\">";
			$code .=  "<h3>Zulässige Dateien</h3>";
			$code .=  "<p>Zulässige Datentypen und Dateigrößen einstellen.</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}
if (r("schulhof.verwaltung.schule.adressen || schulhof.verwaltung.schule.mail")) {
	$code .=  "<li>";
		$code .=  "<a class=\"cms_uebersicht_verwaltung_schuldetails\" href=\"Schulhof/Verwaltung/Schuldetails\">";
			$code .=  "<h3>Schuldetails</h3>";
			$code .=  "<p>Einstellen ".aufzaehlen(array("der Adresse der Schule" => r("schulhof.verwaltung.schule.adressen"), "der eMailadresse des Schulhofs" => r("schulhof.verwaltung.schule.mail")))."</p>";
		$code .=  "</a>";
	$code .=  "</li>";
}

if ($code) {
	$ausgabe .=  "<div class=\"cms_spalte_4\">";
		$ausgabe .=  "<div class=\"cms_spalte_i\">";
			$ausgabe .=  "<h2>Administration</h2><ul class=\"cms_uebersicht\">".$code."</ul>";
		$ausgabe .=  "</div>";
	$ausgabe .=  "</div>";
}

if($ausgabe) {
	echo "</div>";
	echo $ausgabe;
} else {
	echo cms_meldung_berechtigung()."</div>";
}

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
		<a class="cms_uebersicht_verwaltung_speicherplatz" href="Schulhof/Verwaltung/Speicherplatz/">
			<h3>Speicherplatz</h3>
			<p>Statistiken über die Verwendung des Online-Speichers, sowie Einstellungen von Speicherlimits.</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_verwaltung_backup" href="Schulhof/Verwaltung/Backup/">
			<h3>Manuelles Backup</h3>
			<p>Sichert Datenbank und Webserver manuell. Generiert Archiv zum Download.</p>
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
			<p>Persönliche Daten werden verschlüsselt in der Datenbank hinterlegt. Der notwendige Schlüssel liegt in einer Konfigurations-Datei auf dem Webserver. Hier kann der Schlüssel geändert werden. Da dann die gesamte Datenbank ent- und neu verschlüsselt werden muss, kann dieser Prozess etwas dauern.</p>
		</a>
	</li>
</ul> -->
<div class="cms_clear"></div>
<?php
	/*
		Nimmt
		a)
			1 - Ein Array und verbindet die Inhalte mit ,,,und. Inhalte mit dem Wert "false" werden ausgelassen
		b)
			1 - Einen String, in dem "$" mit aufzaehlen(2) ersetzt wird. Ist aufzaehlen(2) leer, wird nichts zurückgegeben.
			2 - Ein Array
	*/
	function aufzaehlen($ws, $w = null) {
		if(is_array($ws)) {
			while(($k = array_search(false, $ws)) !== false)
				unset($ws[$k]);
			if(array_values($ws) !== $ws)
				$ws = array_keys($ws);
			$l = array_pop($ws);
			if($ws)
				return implode(", ", $ws)." und ".$l;
			return $l;
		} else {
			if(($a = aufzaehlen($w)))
				return str_replace('$', $a, $ws);
			return "";
		}
	}
?>
