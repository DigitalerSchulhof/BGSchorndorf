function cms_dauerbrenner_eingabencheck() {
	var bezeichnung = document.getElementById('cms_dauerbrenner_bezeichnung').value;
	var sichtbarl = document.getElementById('cms_dauerbrenner_sichtbarl').value;
	var sichtbars = document.getElementById('cms_dauerbrenner_sichtbars').value;
	var sichtbare = document.getElementById('cms_dauerbrenner_sichtbare').value;
	var sichtbarv = document.getElementById('cms_dauerbrenner_sichtbarv').value;
	var sichtbarx = document.getElementById('cms_dauerbrenner_sichtbarx').value;
	var inhalt = document.getElementsByClassName('note-editable');
	inhalt = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(sichtbarl) || !cms_check_toggle(sichtbars) || !cms_check_toggle(sichtbare) || !cms_check_toggle(sichtbarv) || !cms_check_toggle(sichtbarx)) {
		meldung += '<li>Die Eingabe für die Sichtbarkeit ist ungültig.</li>';
		fehler = true;
	}

	formulardaten.append("bezeichnung", bezeichnung);
	formulardaten.append("inhalt", inhalt);
	formulardaten.append("sichtbarl", 	sichtbarl);
	formulardaten.append("sichtbars", 	sichtbars);
	formulardaten.append("sichtbare", 	sichtbare);
	formulardaten.append("sichtbarv", 	sichtbarv);
	formulardaten.append("sichtbarx", 	sichtbarx);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_dauerbrenner_neu_speichern() {
	cms_laden_an('Neuen Dauerbrenner anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Der Dauerbrenner konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_dauerbrenner_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neuen Dauerbrenner anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neuen Dauerbrenner anlegen', 'Der neue Dauerbrenner wird angelegt');

		formulardaten.append("anfragenziel", 	'313');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits einen Dauerbrenner mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neuen Dauerbrenner anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Dauerbrenner anlegen', '<p>Der Dauerbrenner wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Dauerbrenner\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Dauerbrenner konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>der Dauerbrenner enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Dauerbrenner anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_dauerbrenner_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Dauerbrenner löschen', '<p>Soll der Dauerbrenner wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_dauerbrenner_loeschen('+id+')">Löschung durchführen</span></p>');
}


function cms_dauerbrenner_loeschen(id) {
	cms_laden_an('Dauerbrenner löschen', 'Der Dauerbrenner wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'314');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Dauerbrenner löschen', '<p>Der Dauerbrenner wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Dauerbrenner\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_dauerbrenner_bearbeiten_vorbereiten (id) {
	cms_laden_an('Dauerbrenner bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'315');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Dauerbrenner/Dauerbrenner_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_dauerbrenner_bearbeiten () {
	cms_laden_an('Dauerbrenner bearbeiten', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Der Dauerbrenner konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_dauerbrenner_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Dauerbrenner bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Dauerbrenner bearbeiten', 'Der Dauerbrenner wird bearbeitet');

		formulardaten.append("anfragenziel", 	'316');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits einen Dauerbrenner mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Dauerbrenner bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Dauerbrenner bearbeiten', '<p>Der Dauerbrenner wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Dauerbrenner\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Dauerbrenner konnte nicht bearbeitet werden, denn ...</p><ul>';
				meldung += '<ul><li>der Dauerbrenner enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Dauerbrenner bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
