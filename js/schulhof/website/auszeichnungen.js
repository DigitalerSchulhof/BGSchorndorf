function cms_auszeichnung_eingabenpruefen() {
	var meldung = "";
	var fehler = false;
	var formulardaten = new FormData();

	var aktiv = document.getElementById('cms_auszeichnung_aktiv').value;
	var bild = document.getElementById('cms_auszeichnung_bild').value;
	var bezeichnung = document.getElementById('cms_auszeichnung_bezeichnung').value;
	var link = document.getElementById('cms_auszeichnung_link').value;
	var ziel = document.getElementById('cms_auszeichnung_ziel').value;
	var position = document.getElementById('cms_auszeichnung_position').value;

	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>die Aktivität der Auszeichnung ist ungültig.</li>';
		fehler = true;
	}

	if (bild.length == 0) {
		meldung += '<li>es wurde ein ungültiges Bild gewählt.</li>';
		fehler = true;
	}

	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	if (link.length == 0) {
		meldung += '<li>es wurde kein Link eingegeben.</li>';
		fehler = true;
	}

	if ((ziel != '_self') && (ziel != '_blank')) {
		meldung += '<li>es wurde ein ungültiges Ziel gewählt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(position, 1)) {
		meldung += '<li>es wurde eine ungültige Position gewählt.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("bild", bild);
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("link", link);
		formulardaten.append("ziel", ziel);
		formulardaten.append("position", position);
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}

function cms_auszeichnung_neu_speichern() {
	cms_laden_an('Neue Auszeichnung anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Auszeichnung konnte nicht erstellt werden, denn ...</p><ul>';

	var pruefen = cms_auszeichnung_eingabenpruefen();
	var fehler = pruefen[1];
	meldung += pruefen[0];

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Auszeichnung anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Auszeichnung anlegen', 'Die neue Auszeichnung wird angelegt.');

		var formulardaten = pruefen[2];
		formulardaten.append("anfragenziel", 	'270');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/POSITION/)) {
				meldung += '<li>die Auszeichnung, wurde an einer ungültigen Position einsortiert.</li>';
				cms_meldung_an('fehler', 'Neue Auszeichnung anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Auszeichnung anlegen', '<p>Die Auszeichnung wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Auszeichnungen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_auszeichnung_bearbeiten_vorbereiten (id) {
	cms_laden_an('Auszeichnung bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'273');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Auszeichnungen/Auszeichnung_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_auszeichnung_bearbeiten_speichern() {
	cms_laden_an('Auszeichnung bearbeiten', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Auszeichnung konnte nicht geändert werden, denn ...</p><ul>';

	var pruefen = cms_auszeichnung_eingabenpruefen();
	var fehler = pruefen[1];
	meldung += pruefen[0];

	if (fehler) {
		cms_meldung_an('fehler', 'Auszeichnung bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Auszeichnung bearbeiten', 'Die Auszeichnung wird bearbeitet.');

		var formulardaten = pruefen[2];
		formulardaten.append("anfragenziel", 	'276');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/POSITION/)) {
				meldung += '<li>die Auszeichnung, wurde an einer ungültigen Position einsortiert.</li>';
				cms_meldung_an('fehler', 'Auszeichnung bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Auszeichnung bearbeiten', '<p>Die Auszeichnung wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Auszeichnungen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_auszeichnung_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Auszeichnung löschen', '<p>Soll die Auszeichnung wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_auszeichnung_loeschen(\''+id+'\')">Löschung durchführen</span></p>');
}


function cms_auszeichnung_loeschen(id) {
	cms_laden_an('Auszeichnung löschen', 'Die Auszeichnung wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'277');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Auszeichnung löschen', '<p>Die Auszeichnung wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Auszeichnungen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
