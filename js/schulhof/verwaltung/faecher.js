/* ROLLE WIRD GESPEICHERT */
function cms_schulhof_faecher_neu_speichern() {
	cms_laden_an('Neues Fach anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_kuerzel').value;

	var meldung = '<p>Das Fach konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	if (kuerzel.length == 0) {
		meldung += '<li>es wurde kein Kürzel eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neues Fach anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neues Fach anlegen', 'Das neue Fach wird angelegt');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("kuerzel", kuerzel);

		formulardaten.append("anfragenziel", 	'80');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Fach anlegen', '<p>Das Fach <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Fächer\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTB/)) {
				meldung += '<li>es gibt bereits ein Fach mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neues Fach anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTK/)) {
				meldung += '<li>es gibt bereits ein Fach mit diesem Kürzel.</li>';
				cms_meldung_an('fehler', 'Neues Fach anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schulhof_faecher_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Fach löschen', '<p>Soll das Fach <b>'+anzeigename+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_faecher_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_faecher_loeschen(anzeigename, id) {
	cms_laden_an('Fach löschen', 'Das Fach <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'81');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Fächer löschen', '<p>Das Fach wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Fächer\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

/* RAUM WIRD ZUM BEARBEITEN VORBEREITET */
function cms_schulhof_faecher_bearbeiten_vorbereiten (id) {
	cms_laden_an('Klassenstufe bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'82');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Fächer/Fach_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_faecher_bearbeiten () {
	cms_laden_an('Fach bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_kuerzel').value;

	var meldung = '<p>Das Fach konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	if (kuerzel.length == 0) {
		meldung += '<li>es wurde kein Kürzel eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Fach bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Fach bearbeiten', 'Das Fach wird bearbeitet');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("kuerzel", kuerzel);
		formulardaten.append("anfragenziel", 	'83');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Fach bearbetien', '<p>Das Fach <b>'+bezeichnung+'</b> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Fächer\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTB/)) {
				meldung += '<li>es gibt bereits ein Fach mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Fach bearbetien', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTK/)) {
				meldung += '<li>es gibt bereits ein Fach mit diesem Kürzel.</li>';
				cms_meldung_an('fehler', 'Fach bearbetien', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
