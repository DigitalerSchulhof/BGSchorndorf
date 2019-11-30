function cms_hausmeisterauftrag_abbrechen() {
	document.getElementById('cms_hausmeisterauftrag_titel').value = "";
	document.getElementById('cms_hausmeisterauftrag_beschreibung').value = "";
	var jetzt = Date.now();
	// 14 Tage später
	var spaeter = new Date(jetzt+1000*60*60*24*14);
	document.getElementById('cms_hausmeisteraufrag_zieldatum_T').value = spaeter.getDate();
	document.getElementById('cms_hausmeisteraufrag_zieldatum_M').value = spaeter.getMonth()+1;
	document.getElementById('cms_hausmeisteraufrag_zieldatum_J').value = spaeter.getFullYear();
	cms_datumcheck('cms_hausmeisteraufrag_zieldatum');
}

function cms_hausmeisterauftrag_neu_speichern() {
	cms_laden_an('Hausmeisterauftrag einreichen', 'Der Auftrag wird zusammengestellt.');
	var auftragstitel = document.getElementById('cms_hausmeisterauftrag_titel').value;
	var auftragsbeschreibung = document.getElementById('cms_hausmeisterauftrag_beschreibung').value;
	var tag = document.getElementById('cms_hausmeisteraufrag_zieldatum_T').value;
	var monat = document.getElementById('cms_hausmeisteraufrag_zieldatum_M').value;
	var jahr = document.getElementById('cms_hausmeisteraufrag_zieldatum_J').value;
	var tag = document.getElementById('cms_hausmeisteraufrag_zieldatum_T').value;
	var std = document.getElementById('cms_hausmeisteraufrag_zieluhrzeit_h').value;
	var min = document.getElementById('cms_hausmeisteraufrag_zieluhrzeit_m').value;
	var zugehoerig = document.getElementById('cms_hausmeisterauftrag_zugehoerig').value;

	var fehler = false;
	var meldung = '<p>Der Auftrag konnte nicht verschickt werden, denn ...</p><ul>';

	if ((!zugehoerig.match(/^[lr]\|[0-9]+$/)) && (zugehoerig != '')) {
		fehler = true;
		meldung += '<li>Die Zuordnung des Auftrags zu einem Gerät ist ungültig.</li>';
	}

	if (!cms_check_titel(auftragstitel)) {
		fehler = true;
		meldung += '<li>Der Titel des Auftrags ist ungültig.</li>';
	}

	if (!cms_check_ganzzahl(tag, 1, 31) || !cms_check_ganzzahl(monat, 1, 12) || !cms_check_ganzzahl(jahr, 0) || !cms_check_ganzzahl(std, 0,23) || !cms_check_ganzzahl(min, 0,59)) {
		fehler = true;
		meldung += '<li>Das eingegebene Datum ist ungültig.</li>';
	}
	else {
		var jetzt = new Date();
		var start = new Date(jetzt.getFullYear(), jetzt.getMonth(), jetzt.getDate(), jetzt.getHours(), jetzt.getMinutes(), 0,0);
		var ziel = new Date(jahr, monat-1, tag, std, min, 0,0);
		if (ziel < start) {
			fehler = true;
			meldung += '<li>Das Zieldatum darf nicht vor dem heutigen Datum liegen.</li>';
		}
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Hausmeisterauftrag einreichen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Hausmeisterauftrag einreichen', 'Der Hausmeisterauftrag wird gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("titel", auftragstitel);
		formulardaten.append("beschreibung", auftragsbeschreibung);
		formulardaten.append("zugehoerig", zugehoerig);
		formulardaten.append("tag", tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", jahr);
		formulardaten.append("std", std);
		formulardaten.append("min", min);
		formulardaten.append("anfragenziel", 	'209');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Hausmeisterauftrag einreichen', '<p>Der Hausmeistereintrag wurde eingereicht. Sie erhalten eine Notifikation, wenn er bearbeitet wurde.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Hausmeister\');">Zurück zur Hausmeisterseite</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Auftrag konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>der Auftrag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Hausmeisterauftrag einreichen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_hausmeisterauftrag_markieren(art, id) {
	cms_laden_an('Hausmeisterauftrag markieren', 'Die Markierung wird geprüft.');

	var fehler = false;
	var meldung = '<p>Die Markierung konnte nicht vorgenommen werden, denn ...</p><ul>';

	if ((art != 'n') && (art != 'e')) {
		fehler = true;
		meldung += '<li>Es wurde eine ungültige Markierung angegeben.</li>';
	}

	if (!cms_check_ganzzahl(id,0)) {
		fehler = true;
		meldung += '<li>Die eingegebene ID ist ungültig.</li>';
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Hausmeisterauftrag markieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Hausmeisterauftrag einreichen', 'Der Hausmeisterauftrag wird markiert.');

		var formulardaten = new FormData();
		formulardaten.append("id", id);
		formulardaten.append("art", art);
		formulardaten.append("anfragenziel", 	'310');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Hausmeisterauftrag einreichen', '<p>Der Hausmeistereintrag wurde markiert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Hausmeister/Aufträge\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_hausmeisterauftrag_lesen(id) {
	cms_laden_an('Hausmeisterauftrag lesen', 'Der Auftrag wird geöffnet.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'311');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link("Schulhof/Hausmeister/Aufträge/Details");
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_hausmeisterauftrag_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Auftrag löschen', '<p>Sollen der Auftrag wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_hausmeisterauftrag_loeschen('+id+')">Löschung durchführen</span></p>');
}


function cms_hausmeisterauftrag_loeschen(id) {
	cms_laden_an('Auftrag löschen', 'Der Auftrag wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		    id);
	formulardaten.append("anfragenziel", 	'312');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Auftrag löschen', '<p>Der Auftrag wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Hausmeister/Aufträge\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
