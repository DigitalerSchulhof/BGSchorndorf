function cms_schulhof_website_seite_neu_vorbereiten(zuordnung) {
	cms_laden_an('Neue Seite anlegen', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("zuordnung", zuordnung);
	formulardaten.append("anfragenziel", 	'234');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Seiten/Neue_Seite_anlegen');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_schulhof_website_seite_neu_speichern() {
	cms_laden_an('Neue Seite anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_website_seiten_bezeichnung').value;
	var art = document.getElementById('cms_website_seiten_art').value;
	var position = document.getElementById('cms_website_seiten_position').value;
	var sidebar = document.getElementById('cms_website_seiten_sidebar').value;
	var status = document.getElementById('cms_website_seiten_status').value;
	var styles = document.getElementById('cms_website_seiten_styles').value;
	var klassen = document.getElementById('cms_website_seiten_klassen').value;
	var zuordnung = document.getElementById('cms_website_seiten_zuordnung').value;
	var beschreibung = document.getElementById('cms_website_seiten_beschreibung').value;

	var meldung = '<p>Die Seite konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if ((art != 's') && (art != 'm') && (art != 'b') && (art != 'g') && (art != 't')) {
		meldung += '<li>es wurde eine falsche Seitenart eingegeben.</li>';
		fehler = true;
	}

	if ((art == 'm') && (status == 's')) {
		meldung += '<li>Eine Startseite kann kein Menüpunkt sein.</li>';
		fehler = true;
	}

	var zuordnungsfehler = false;
	if (zuordnung != '-') {
		if (!Number.isInteger(parseInt(zuordnung))) {
			zuordnungsfehler = true;
		}
		else if (zuordnung < 0) {
			zuordnungsfehler = true;
		}
	}

	if (zuordnungsfehler) {
		meldung += '<li>diese Seite wurde ungültig zugeordnet.</li>';
		fehler = true;
	}

	var positionfehler = false;
	if (!Number.isInteger(parseInt(position))) {
		positionfehler = true;
	}
	else if (position < 0) {
		positionfehler = true;
	}

	if (positionfehler) {
		meldung += '<li>es wurde eine ungültige Position angegeben.</li>';
		fehler = true;
	}

	if ((sidebar != '0') && (sidebar != '1')) {
		meldung += '<li>es wurde eine falsche Sidebarauswahl getroffen.</li>';
		fehler = true;
	}

	if ((status != 'a') && (status != 'i') && (status != 's')) {
		meldung += '<li>es wurde eine falsche Statusauswahl getroffen.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Seite anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Seite anlegen', 'Die neue Seite wird angelegt.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("beschreibung", beschreibung);
		formulardaten.append("art", art);
		formulardaten.append("position", position);
		formulardaten.append("sidebar", sidebar);
		formulardaten.append("status", status);
		formulardaten.append("styles", styles);
		formulardaten.append("klassen", klassen);
		formulardaten.append("zuordnung", zuordnung);
		formulardaten.append("anfragenziel", 	'235');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ZUORDNUNG/)) {
				meldung += '<li>die Seite, der diese Seite zugeordnet werden soll, existiert nicht.</li>';
				cms_meldung_an('fehler', 'Neue Seite anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				meldung += '<li>es gibt bereits eine Seite mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neue Seite anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/MAXPOS/)) {
				meldung += '<li>die Seite, wurde an einer ungültigen Position einsortiert.</li>';
				cms_meldung_an('fehler', 'Neue Seite anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Seite anlegen', '<p>Die Seite <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Seiten\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_schulhof_website_seite_bearbeiten_vorbereiten (id) {
	cms_laden_an('Seite bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'236');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Seiten/Seite_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_schulhof_website_seite_bearbeiten() {
	cms_laden_an('Seite bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_website_seiten_bezeichnung').value;
	var beschreibung = document.getElementById('cms_website_seiten_beschreibung').value;
	var art = document.getElementById('cms_website_seiten_art').value;
	var position = document.getElementById('cms_website_seiten_position').value;
	var sidebar = document.getElementById('cms_website_seiten_sidebar').value;
	var status = document.getElementById('cms_website_seiten_status').value;
	var styles = document.getElementById('cms_website_seiten_styles').value;
	var klassen = document.getElementById('cms_website_seiten_klassen').value;

	var meldung = '<p>Die Seite konnte nicht bearbeitet werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if ((art != 's') && (art != 'm') && (art != 'b') && (art != 'g') && (art != 't')) {
		meldung += '<li>es wurde eine falsche Seitenart eingegeben.</li>';
		fehler = true;
	}

	if ((art == 'm') && (status == 's')) {
		meldung += '<li>Eine Startseite kann kein Menüpunkt sein.</li>';
		fehler = true;
	}

	var positionfehler = false;
	if (!Number.isInteger(parseInt(position))) {
		positionfehler = true;
	}
	else if (position < 0) {
		positionfehler = true;
	}

	if (positionfehler) {
		meldung += '<li>es wurde eine ungültige Position angegeben.</li>';
		fehler = true;
	}

	if ((sidebar != '0') && (sidebar != '1')) {
		meldung += '<li>es wurde eine falsche Sidebarauswahl getroffen.</li>';
		fehler = true;
	}

	if ((status != 'a') && (status != 'i') && (status != 's')) {
		meldung += '<li>es wurde eine falsche Statusauswahl getroffen.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Seite bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Seite bearbeiten', 'Die Seite wird bearbeitet.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("beschreibung", beschreibung);
		formulardaten.append("art", art);
		formulardaten.append("position", position);
		formulardaten.append("sidebar", sidebar);
		formulardaten.append("status", status);
		formulardaten.append("styles", styles);
		formulardaten.append("klassen", klassen);
		formulardaten.append("anfragenziel", 	'237');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/DOPPELT/)) {
				meldung += '<li>es gibt bereits eine Seite mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Seite bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/MAXPOS/)) {
				meldung += '<li>die Seite, wurde an einer ungültigen Position einsortiert.</li>';
				cms_meldung_an('fehler', 'Seite bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Seite bearbeiten', '<p>Die Seite <b>'+bezeichnung+'</b> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Seiten\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_schulhof_website_seite_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Seite löschen', '<p>Soll die Seite <b>'+anzeigename+'</b> wirklich gelöscht werden?</p><p><b>Alle Unterseiten</b> dieser Seite werden ebenfalls gelöscht!</p><p>Mit der Löschung einer Seite werden auch <b>alle Inhalte</b> dieser Seite gelöscht. Dateien, die auf dieser Seite verwendet werden, sind von der Löschung nicht betroffen. Sie müssen separat gelöscht werden.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_website_seite_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}

function cms_schulhof_website_seite_loeschen(anzeigename, id) {
	cms_laden_an('Seite löschen', 'Die Seite <b>'+anzeigename+'</b> wird gelöscht.<br>Je nach dem wie viele Unterseiten enthalten sind und wie voll diese Seiten sind, kann das einen Moment dauern.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'238');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Seite löschen', '<p>Die Seite wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Seiten\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_schulhof_website_seite_startseite_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Seite zur Startseite machen', '<p>Soll die Seite <b>'+anzeigename+'</b> wirklich zur Startseite gemacht werden?</p><p>Die vorige Startseite wird danach als aktive Seite angezeigt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_ja" onclick="cms_schulhof_website_seite_startseite(\''+anzeigename+'\','+id+')">Neue Startseite festlegen</span></p>');
}

function cms_schulhof_website_seite_startseite(anzeigename, id) {
	cms_laden_an('Seite zur Startseite machen', 'Die Seite <b>'+anzeigename+'</b> wird zur Startseite gemacht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'239');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Seite zur Startseite machen', '<p>Die Seite wurde zur Startseite gemacht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Seiten\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_seitenwahl_auswahl(id, bezeichnung, seite) {
	var feld = document.getElementById(id+'_wahlF');
	var eingabe = document.getElementById(id);
	feld.innerHTML = bezeichnung;
	eingabe.value = seite;
	cms_ausblenden(id+'_wahl_seitenwahlF');
}


function cms_neue_weiterleitung(ziel) {
	ziel = ziel || "";
	cms_laden_an('Neue Weiterleitung einrichten', 'Daten werden verarbeitet.');

	var formulardaten = new FormData();
	formulardaten.append("ziel",     			ziel);
	formulardaten.append("anfragenziel", 	'340');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link("Schulhof/Website/Weiterleiten/Neu");
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_weiterleitung_neu_speichern() {
	var von = document.getElementById('cms_weiterleitung_von').value;
	var zu 	= document.getElementById('cms_weiterleitung_zu').value;

	var meldung = '<p>Die Weiterleitung konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!/\/(.*)*/.test(von)) {
		meldung += '<li>die neue URL ist ungültig.</li>';
		fehler = true;
	}
	if (!/\/(.*)*/.test(zu)) {
		meldung += '<li>die Zielseite ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Weiterleitung einrichten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Weiterleitung einrichten', 'Daten werden verarbeitet.');
		var formulardaten = new FormData();
		formulardaten.append("von",     			von);
		formulardaten.append("zu",     				zu);
		formulardaten.append("anfragenziel", 	'341');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELT") {
				cms_meldung_an('fehler', 'Neue Weiterleitung einrichten', "<p>Eine Weiterleitung für diese URL ist schon vorhanden.</p>", '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			} else if (rueckgabe == "ERFOLG") {
			cms_link("Schulhof/Website/Weiterleiten");
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_weiterleitung_bearbeiten_vorbereiten (id) {
	cms_laden_an('Weiterleitung bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'383');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Weiterleiten/Details');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_weiterleitung_bearbeiten_speichern(id) {
	cms_laden_an('Weiterleitung speichern', 'Daten werden verarbeitet.');
	var von = document.getElementById('cms_weiterleitung_von').value;
	var zu 	= document.getElementById('cms_weiterleitung_zu').value;

	var meldung = '<p>Die Weiterleitung konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!/\/(.*)*/.test(von)) {
		meldung += '<li>die neue URL ist ungültig.</li>';
		fehler = true;
	}
	if (!/\/(.*)*/.test(zu)) {
		meldung += '<li>die Zielseite ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Weiterleitung einrichten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Weiterleitung einrichten', 'Daten werden verarbeitet.');
		var formulardaten = new FormData();
		formulardaten.append("id",     			id);
		formulardaten.append("von",     			von);
		formulardaten.append("zu",     				zu);
		formulardaten.append("anfragenziel", 	'381');

		function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "DOPPELT") {
			cms_meldung_an('fehler', 'Neue Weiterleitung einrichten', "<p>Eine Weiterleitung für diese URL ist schon vorhanden.</p>", '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		} else if (rueckgabe == "ERFOLG") {
			cms_link("Schulhof/Website/Weiterleiten");
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_weiterleitung_loeschen(id) {
	cms_laden_an('Weiterleitung löschen', 'Daten werden verarbeitet.');

	var formulardaten = new FormData();
	formulardaten.append("id",     				id);
	formulardaten.append("anfragenziel", 	'382');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link("Schulhof/Website/Weiterleiten");
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_multiselect_weiterleitungen_loeschen() {
	cms_multianfrage(382, ["Weiterleitungen löschen", "Die Weiterleitungen werden gelöscht"], {id: cms_multiselect_ids()}).then((rueckgabe) => {
		if (rueckgabe == "ERFOLG") {
			cms_link("Schulhof/Website/Weiterleiten");
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	});
}
