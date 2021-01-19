function cms_pinnwaende_eingabencheck() {
	var bezeichnung = document.getElementById('cms_pinnwaende_bezeichnung').value;
	var beschreibung = document.getElementById('cms_pinnwaende_beschreibung').value;
	var sichtbarl = document.getElementById('cms_pinnwaende_sichtbarl').value;
	var sichtbars = document.getElementById('cms_pinnwaende_sichtbars').value;
	var sichtbare = document.getElementById('cms_pinnwaende_sichtbare').value;
	var sichtbarv = document.getElementById('cms_pinnwaende_sichtbarv').value;
	var sichtbarx = document.getElementById('cms_pinnwaende_sichtbarx').value;
	var schreibenl = document.getElementById('cms_pinnwaende_schreibenl').value;
	var schreibens = document.getElementById('cms_pinnwaende_schreibens').value;
	var schreibene = document.getElementById('cms_pinnwaende_schreibene').value;
	var schreibenv = document.getElementById('cms_pinnwaende_schreibenv').value;
	var schreibenx = document.getElementById('cms_pinnwaende_schreibenx').value;

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
	if (!cms_check_toggle(schreibenl) || !cms_check_toggle(schreibens) || !cms_check_toggle(schreibene) || !cms_check_toggle(schreibenv) || !cms_check_toggle(schreibenx)) {
		meldung += '<li>Die Eingabe für das Schreibrecht ist ungültig.</li>';
		fehler = true;
	}

	formulardaten.append("bezeichnung", bezeichnung);
	formulardaten.append("beschreibung", beschreibung);
	formulardaten.append("sichtbarl", 	sichtbarl);
	formulardaten.append("sichtbars", 	sichtbars);
	formulardaten.append("sichtbare", 	sichtbare);
	formulardaten.append("sichtbarv", 	sichtbarv);
	formulardaten.append("sichtbarx", 	sichtbarx);
	formulardaten.append("schreibenl", 	schreibenl);
	formulardaten.append("schreibens", 	schreibens);
	formulardaten.append("schreibene", 	schreibene);
	formulardaten.append("schreibenv", 	schreibenv);
	formulardaten.append("schreibenx", 	schreibenx);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_pinnwaende_neu_speichern() {
	cms_laden_an('Neue Pinnwand anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Pinnwand konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_pinnwaende_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Pinnwand anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Pinnwand anlegen', 'Die Pinnwand wird angelegt');

		formulardaten.append("anfragenziel", 	'317');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits eine Pinnwand mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neue Pinnwand anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Pinnwand anlegen', '<p>Die Pinnwand wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Pinnwände\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Die Pinnwand konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>Die Pinnwand enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neue Pinnwand anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_pinnwaende_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Pinnwand löschen', '<p>Soll die Pinnwand wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_pinnwaende_loeschen('+id+')">Löschung durchführen</span></p>');
}


function cms_pinnwaende_loeschen(id) {
	cms_laden_an('Pinnwand löschen', 'Die Pinnwand wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'318');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Pinnwand löschen', '<p>Die Pinnwand wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Pinnwände\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_pinnwaende_bearbeiten_vorbereiten (id) {
	cms_laden_an('Pinnwand bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'319');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Pinnwände/Pinnwand_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_pinnwaende_bearbeiten () {
	cms_laden_an('Pinnwand bearbeiten', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Pinnwand konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_pinnwaende_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Pinnwand bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Pinnwand bearbeiten', 'Die Pinnwand wird bearbeitet');

		formulardaten.append("anfragenziel", 	'320');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits eine Pinnwand mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Pinnwand bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Pinnwand bearbeiten', '<p>Die Pinnwand wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Pinnwände\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Die Pinnwand konnte nicht bearbeitet werden, denn ...</p><ul>';
				meldung += '<ul><li>Die Pinnwand enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Pinnwand bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}






function cms_pinnwandanschlaege_eingabencheck(pinnwand) {
	var titel = document.getElementById('cms_anschalg_titel').value;
	var vonT = document.getElementById('cms_anschlag_von_T').value;
	var vonM = document.getElementById('cms_anschlag_von_M').value;
	var vonJ = document.getElementById('cms_anschlag_von_J').value;
	var bisT = document.getElementById('cms_anschlag_bis_T').value;
	var bisM = document.getElementById('cms_anschlag_bis_M').value;
	var bisJ = document.getElementById('cms_anschlag_bis_J').value;
	var inhalt = document.getElementsByClassName('note-editable');
	inhalt = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;

	if (!cms_check_nametitel(titel)) {
		meldung += '<li>Der Titel enthält ungültige Zeichen.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(vonT, 1, 31) || !cms_check_ganzzahl(vonM, 1, 12) || !cms_check_ganzzahl(vonJ, 0) ||
      !cms_check_ganzzahl(bisT, 1, 31) || !cms_check_ganzzahl(bisM, 1, 12) || !cms_check_ganzzahl(bisJ, 0)) {
		fehler = true;
		meldung += '<li>Mindestens ein eingegebenes Datum ist ungültig.</li>';
	}
	else {
		var beginn = new Date(vonJ, vonM-1, vonT, 0,0,0,0);
		var ende = new Date(bisJ, bisM-1, bisT, 0,0,0,0);
		if (ende < beginn) {
			fehler = true;
			meldung += '<li>Das Enddatum darf nicht vor dem Anfangsdatum liegen.</li>';
		}
	}

	formulardaten.append("titel", titel);
	formulardaten.append("inhalt", inhalt);
	formulardaten.append("vonT", 	vonT);
	formulardaten.append("vonM", 	vonM);
	formulardaten.append("vonJ", 	vonJ);
	formulardaten.append("bisT", 	bisT);
	formulardaten.append("bisM", 	bisM);
	formulardaten.append("bisJ", 	bisJ);
	formulardaten.append("pinnwand", 	pinnwand);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_pinnwandanschlaege_neuspeichern(pinnwand) {
	cms_laden_an('Neuen Anschlag anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Der Anschlag konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_pinnwandanschlaege_eingabencheck(pinnwand);

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neuen Anschlag anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neuen Anschlag anlegen', 'Der Anschlag wird angelegt');

		formulardaten.append("anfragenziel", 	'321');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Anschlag anlegen', '<p>Der Anschlag wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Pinnwände/'+pinnwand+'\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Anschalg konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>Der Anschlag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Anschalg anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_pinnwandanschlag_loeschen_anzeigen (id, pinnwand) {
	cms_meldung_an('warnung', 'Anschlag löschen', '<p>Soll der Anschalg wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_pinnwandanschlag_loeschen('+id+', \''+pinnwand+'\')">Löschung durchführen</span></p>');
}


function cms_pinnwandanschlag_loeschen(id, pinnwand) {
	cms_laden_an('Anschlag löschen', 'Der Anschlag wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("pinnwand",  pinnwand);
	formulardaten.append("anfragenziel", 	'322');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Anschlag löschen', '<p>Die Pinnwand wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Pinnwände/'+pinnwand+'\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_pinnwandanschlag_bearbeiten_vorbereiten (id, pinnwand) {
	cms_laden_an('Anschalg bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("pinnwand", pinnwand);
	formulardaten.append("anfragenziel", 	'323');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Pinnwände/'+pinnwand+'/Anschlag_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_pinnwandanschlag_bearbeiten (pinnwand) {
	cms_laden_an('Anschlag bearbeiten', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Der Anschlag konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_pinnwandanschlaege_eingabencheck(pinnwand);

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Anschlag bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Anschlag bearbeiten', 'Die Anschlag wird bearbeitet');

		formulardaten.append("anfragenziel", 	'324');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Anschlag bearbeiten', '<p>Der Anschlag wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Pinnwände/'+pinnwand+'\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Anschlag konnte nicht bearbeitet werden, denn ...</p><ul>';
				meldung += '<ul><li>Der Anschlag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Anschlag bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
