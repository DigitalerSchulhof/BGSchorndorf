function cms_buchung_neu_speichern(art, standort, ziel) {
	cms_laden_an('Neue Buchung anlegen', 'Die Eingaben werden überprüft.');
	var grund = document.getElementById('cms_buchung_grund').value;
	var datumT = document.getElementById('cms_buchung_datum_T').value;
	var datumM = document.getElementById('cms_buchung_datum_M').value;
	var datumJ = document.getElementById('cms_buchung_datum_J').value;
	var beginnS = document.getElementById('cms_buchung_beginn_h').value;
	var beginnM = document.getElementById('cms_buchung_beginn_m').value;
	var endeS = document.getElementById('cms_buchung_ende_h').value;
	var endeM = document.getElementById('cms_buchung_ende_m').value;

	var meldung = '<p>Die Buchung konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	if (grund.length == 0) {
		meldung += '<li>es wurde kein Grund für die Buchung angegeben.</li>';
		fehler = true;
	}

	if ((art != 'l') && (art != 'r')) {
		meldung += '<li>die Art der Buchung ist ungültig.</li>';
		fehler = true;
	}

	if ((!cms_check_ganzzahl(datumT, 1, 31)) || (!cms_check_ganzzahl(datumM, 1, 12)) || (!cms_check_ganzzahl(datumJ, 0))) {
		meldung += '<li>das Datum ist ungültig.</li>';
		fehler = true;
	}

	if ((!cms_check_ganzzahl(beginnS, 0, 23)) || (!cms_check_ganzzahl(beginnM, 0, 59))) {
		meldung += '<li>der Beginn ist ungültig.</li>';
		fehler = true;
	}

	if ((!cms_check_ganzzahl(endeS, 0, 23)) || (!cms_check_ganzzahl(endeM, 0, 59))) {
		meldung += '<li>das Ende ist ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		beginn = new Date(datumJ, datumM-1, datumT, beginnS, beginnM, 0, 0);
		ende = new Date(datumJ, datumM-1, datumT, endeS, endeM, 0, 0);

		if (ende.getTime() - beginn.getTime() <= 0) {
			meldung += '<li>das Ende liegt vor dem Beginn oder ist mit ihm identisch.</li>';
			fehler = true;
		}
	}

	if (!cms_check_ganzzahl(standort, 0)) {
		meldung += '<li>der Standort ist ungültig.</li>';
		fehler = true;
	}


	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("standort", standort);
		formulardaten.append("grund", grund);
		formulardaten.append("art", art);
		formulardaten.append("datumT", datumT);
		formulardaten.append("datumM", datumM);
		formulardaten.append("datumJ", datumJ);
		formulardaten.append("beginnS", beginnS);
		formulardaten.append("beginnM", beginnM);
		formulardaten.append("endeS", endeS);
		formulardaten.append("endeM", endeM);
		formulardaten.append("anfragenziel", '18');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Buchung anlegen', '<p>Die neue Buchung wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/STANDORT/)) {
				meldung += '<li>der Standort ist ungültig.</li>';
				cms_meldung_an('fehler', 'Neue Buchung anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/ÜBER/)) {
				meldung += '<li>die Buchung überschneidet sich mit anderen Buchungen oder Blockierungen.</li>';
				cms_meldung_an('fehler', 'Neue Buchung anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neue Buchung anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_buchung_loeschen_vorbereiten(id, art, standort, ziel) {
	cms_meldung_an('warnung', 'Buchung löschen', '<p>Soll die Buchung wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_buchung_loeschen(\''+id+'\', \''+art+'\', \''+standort+'\', \''+ziel+'\')">Löschung durchführen</span></p>');
}


function cms_buchung_loeschen(id, art, standort, ziel) {
	cms_laden_an('Buchung löschen', 'Die Buchung wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("art", art);
	formulardaten.append("standort", standort);
	formulardaten.append("anfragenziel", 	'19');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Buchung löschen', '<p>Die Buchung wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_buchung_alleloeschen_vorbereiten(ziel) {
	cms_meldung_an('warnung', 'Vergangene Buchungen löschen', '<p>Sollen wirklich alle vergangenen Buchungen aller Räume und Leihgeräte gelsöcht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_buchung_alleloeschen(\''+ziel+'\')">Löschung durchführen</span></p>');
}


function cms_buchung_alleloeschen(ziel) {
	cms_laden_an('Vergangene Buchungen löschen', 'Die Buchungen werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'20');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Vergangene Buchungen löschen', '<p>Die Buchungen wurden gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_buchunganzeigen(art, standort, richtung, ziel) {
	var box = document.getElementById('cms_buchungsplan');
	box.innerHTML = cms_meldung_laden('Buchungen der neuen Woche werden geladen.');
	var tag = document.getElementById('cms_buchungsplan_datum_T');
	var monat = document.getElementById('cms_buchungsplan_datum_M');
	var jahr = document.getElementById('cms_buchungsplan_datum_J');

	if (richtung == '-') {tag.value = tag.value-7;}
	if (richtung == '+') {tag.value = parseInt(tag.value)+7;}
	cms_datumcheck('cms_buchungsplan_datum');

	var meldung = "<p>Der Buchungen der neuen Woche konnten nicht geladen werden, denn ...</p><ul>";
	var fehler = false;
	if ((art != 'r') && (art != 'l')) {
		meldung += "<li>die Art des Buchunsobjekts ist ungültig.</li>";
		fehler = true;
	}
	if (!cms_check_ganzzahl(standort, 0)) {
		meldung += "<li>der Standort ist ungültig.</li>";
		fehler = true;
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("art", art);
		formulardaten.append("standort", standort);
		formulardaten.append("tag", tag.value);
		formulardaten.append("monat", monat.value);
		formulardaten.append("jahr", jahr.value);
		formulardaten.append("ziel", ziel);
		formulardaten.append("anfragenziel", 	'21');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/<div /)) {
				box.innerHTML = rueckgabe;
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		box.innerHTML = cms_meldung_code ('fehler', 'Buchungen konnten nicht geladen werden', meldung+'</ul>');
	}
}
