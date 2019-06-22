function cms_faecher_vorbereiten(id) {
  cms_laden_an('Fächer vorbereiten', 'Die Fächer des Schuljahres werden vorbereitet ...');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", 	'335');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Fächer');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_faecher_neu_speichern() {
	cms_laden_an('Neues Fach anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_kuerzel').value;
	var farbe = document.getElementById('cms_faecher_farbe').value;
	var icon = document.getElementById('cms_gruppe_icon').value;
	var kollegen = document.getElementById('cms_faecher_kollegen_personensuche_gewaehlt').value;

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

	if (!cms_check_ganzzahl(farbe,0,47)) {
		meldung += '<li>die gewählte Farbe ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_dateiname(icon)) {
		meldung += '<li>das gewählte Icon ist ungültig.</li>';
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
    formulardaten.append("farbe", farbe);
    formulardaten.append("icon", icon);
    formulardaten.append("kollegen", kollegen);

		formulardaten.append("anfragenziel", 	'80');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Fach anlegen', '<p>Das Fach <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">Zurück zur Übersicht</span></p>');
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
			cms_meldung_an('erfolg', 'Fächer löschen', '<p>Das Fach wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">OK</span></p>');
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
			cms_link('Schulhof/Verwaltung/Planung/Fächer/Fach_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_faecher_bearbeiten () {
	cms_laden_an('Fach bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_kuerzel').value;
	var farbe = document.getElementById('cms_faecher_farbe').value;
	var icon = document.getElementById('cms_gruppe_icon').value;
	var kollegen = document.getElementById('cms_faecher_kollegen_personensuche_gewaehlt').value;

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

	if (!cms_check_ganzzahl(farbe,0,47)) {
		meldung += '<li>die gewählte Farbe ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_dateiname(icon)) {
		meldung += '<li>das gewählte Icon ist ungültig.</li>';
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
    formulardaten.append("farbe", farbe);
    formulardaten.append("icon", icon);
    formulardaten.append("kollegen", kollegen);
		formulardaten.append("anfragenziel", 	'83');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Fach bearbeiten', '<p>Das Fach <b>'+bezeichnung+'</b> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">Zurück zur Übersicht</span></p>');
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
