function cms_schulhof_schuljahr_neu_speichern() {
	cms_laden_an('Neues Schuljahr anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_schulhof_schuljahr_bezeichnung').value;
	var beginnt = document.getElementById('cms_schulhof_schuljahr_beginn_T').value;
	var beginnm = document.getElementById('cms_schulhof_schuljahr_beginn_M').value;
	var beginnj = document.getElementById('cms_schulhof_schuljahr_beginn_J').value;
	var endet = document.getElementById('cms_schulhof_schuljahr_ende_T').value;
	var endem = document.getElementById('cms_schulhof_schuljahr_ende_M').value;
	var endej = document.getElementById('cms_schulhof_schuljahr_ende_J').value;
	var schulleitung = document.getElementById('cms_schuljahr_schulleitung_personensuche_gewaehlt').value;
	var stellschulleitung = document.getElementById('cms_schuljahr_stellschulleitung_personensuche_gewaehlt').value;
	var abteilungsleitung = document.getElementById('cms_schuljahr_abteilungsleitung_personensuche_gewaehlt').value;
	var sekretariat = document.getElementById('cms_schuljahr_sekretariat_personensuche_gewaehlt').value;
	var sozialarbeit = document.getElementById('cms_schuljahr_sozialarbeit_personensuche_gewaehlt').value;
	var oberstufenberatung = document.getElementById('cms_schuljahr_oberstufenberatung_personensuche_gewaehlt').value;
	var beratungslehrer = document.getElementById('cms_schuljahr_beratungslehrer_personensuche_gewaehlt').value;
	var verbindungslehrer = document.getElementById('cms_schuljahr_verbindungslehrer_personensuche_gewaehlt').value;
	var schuelersprecher = document.getElementById('cms_schuljahr_schuelersprecher_personensuche_gewaehlt').value;
	var elternbeirat = document.getElementById('cms_schuljahr_elternbeirat_personensuche_gewaehlt').value;
	var vertretungsplanung = document.getElementById('cms_schuljahr_vertretungsplanung_personensuche_gewaehlt').value;
	var datenschutz = document.getElementById('cms_schuljahr_datenschutz_personensuche_gewaehlt').value;
	var hausmeister = document.getElementById('cms_schuljahr_hausmeister_personensuche_gewaehlt').value;

	var meldung = '<p>Das Schuljahr konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>es wurde eine ungültige Bezeichnung eingegeben. Es dürfen nur lateinische Buchstaben, Umlaute, »ß«, »-«, sowie die Zahlen von 0 bis 9 verwendet werden.</li>';
		fehler = true;
	}
	if (bezeichnung.tolowercase == "schuljahrübergreifend") {
		meldung += '<li>es wurde eine ungültige Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	beginnd = new Date(beginnj, beginnm, beginnt, 0, 0, 0, 0);
	ended = new Date(endej, endem, endet, 23, 59, 59, 999);

	if (beginnd-ended >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neues Schuljahr anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neues Schuljahr anlegen', 'Es wird geprüft, ob in diesem Zeitraum bereits ein anders Schuljahr existiert.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung",     	bezeichnung);
		formulardaten.append("beginnj", 				beginnj);
		formulardaten.append("beginnm", 				beginnm);
		formulardaten.append("beginnt", 				beginnt);
		formulardaten.append("endej", 				endej);
		formulardaten.append("endem", 				endem);
		formulardaten.append("endet", 				endet);
		formulardaten.append("schulleitung", 		schulleitung);
		formulardaten.append("stellschulleitung", 	stellschulleitung);
		formulardaten.append("abteilungsleitung", 	abteilungsleitung);
		formulardaten.append("sekretariat", 		sekretariat);
		formulardaten.append("sozialarbeit", 		sozialarbeit);
		formulardaten.append("oberstufenberatung", 	oberstufenberatung);
		formulardaten.append("beratungslehrer", 	beratungslehrer);
		formulardaten.append("verbindungslehrer", 	verbindungslehrer);
		formulardaten.append("schuelersprecher", 	schuelersprecher);
		formulardaten.append("elternbeirat", 		elternbeirat);
		formulardaten.append("vertretungsplanung",	vertretungsplanung);
		formulardaten.append("datenschutz",	datenschutz);
		formulardaten.append("hausmeister",	hausmeister);
		formulardaten.append("anfragenziel", 	'148');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/DOPPELT/)) {
				meldung += '<li>es gibt bereits ein Schuljahr in diesem Zeitraum.</li>';
				cms_meldung_an('fehler', 'Neues Schuljahr anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/PERSONEN/)) {
				meldung += '<li>es wurden Personen Schlüsselpositionen zugeordnet, die diese nicht inne haben dürfen.</li>';
				cms_meldung_an('fehler', 'Neues Schuljahr anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Schuljahr anlegen', '<p>Das Schuljahr <i>'+bezeichnung+'</i> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuljahre\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_schulhof_schuljahr_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Schuljahr löschen', '<p>Soll das Schuljahr <b>'+anzeigename+'</b> wirklich gelöscht werden?</p><p>Mit der Löschung eines Schuljahres wird auch die Löschung aller Klassenstufen, Klassen und Kurse, sowie der zugehörigen Unterrichtsgruppen und sämtlichen Aktivitäten ausgeführt, die diesem Schuljahr zugeordnet sind.</p><p>Die gelöschten Daten können nicht wiederhergestellt werden!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_schuljahr_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_schuljahr_loeschen(anzeigename, id) {
	cms_laden_an('Schuljahr löschen', 'Das Schuljahr <b>'+anzeigename+'</b> wird gelöscht. Dies kann etwas Zeit in Anspruch nehmen.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'149');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Schuljahr löschen', '<p>Das Schuljahr wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuljahre\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_multiselect_schulhof_schuljahr_loeschen_anzeigen () {
	cms_meldung_an('warnung', 'Schuljahre löschen', '<p>Sollen die Schuljahre wirklich gelöscht werden?</p><p>Mit der Löschung eines Schuljahres wird auch die Löschung aller Klassenstufen, Klassen und Kurse, sowie der zugehörigen Unterrichtsgruppen und sämtlichen Aktivitäten ausgeführt, die diesem Schuljahr zugeordnet sind.</p><p>Die gelöschten Daten können nicht wiederhergestellt werden!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_multiselect_schulhof_schuljahr_loeschen()">Löschung durchführen</span></p>');
}

function cms_multiselect_schulhof_schuljahr_loeschen() {
	cms_multianfrage(149, ["Schuljahre löschen", "Die Schuljahre werden gelöscht"], {id: cms_multiselect_ids()}).then((rueckgabe) => {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Schuljahre löschen', '<p>Die Schuljahre wurden gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuljahre\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	});
}

/* SCHULJAHR WIRD ZUM BEARBEITEN VORBEREITET */
function cms_schulhof_schuljahr_bearbeiten_vorbereiten (id) {
	cms_laden_an('Schuljahre bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'150');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Schuljahre/Schuljahr_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


/* BEARBEITETE ROLLE WIRD GESPEICHERT */
function cms_schulhof_schuljahr_bearbeiten_speichern() {
	cms_laden_an('Schuljahr bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_schulhof_schuljahr_bezeichnung').value;
	var beginnt = document.getElementById('cms_schulhof_schuljahr_beginn_T').value;
	var beginnm = document.getElementById('cms_schulhof_schuljahr_beginn_M').value;
	var beginnj = document.getElementById('cms_schulhof_schuljahr_beginn_J').value;
	var endet = document.getElementById('cms_schulhof_schuljahr_ende_T').value;
	var endem = document.getElementById('cms_schulhof_schuljahr_ende_M').value;
	var endej = document.getElementById('cms_schulhof_schuljahr_ende_J').value;
	var schulleitung = document.getElementById('cms_schuljahr_schulleitung_personensuche_gewaehlt').value;
	var stellschulleitung = document.getElementById('cms_schuljahr_stellschulleitung_personensuche_gewaehlt').value;
	var abteilungsleitung = document.getElementById('cms_schuljahr_abteilungsleitung_personensuche_gewaehlt').value;
	var sekretariat = document.getElementById('cms_schuljahr_sekretariat_personensuche_gewaehlt').value;
	var sozialarbeit = document.getElementById('cms_schuljahr_sozialarbeit_personensuche_gewaehlt').value;
	var oberstufenberatung = document.getElementById('cms_schuljahr_oberstufenberatung_personensuche_gewaehlt').value;
	var beratungslehrer = document.getElementById('cms_schuljahr_beratungslehrer_personensuche_gewaehlt').value;
	var verbindungslehrer = document.getElementById('cms_schuljahr_verbindungslehrer_personensuche_gewaehlt').value;
	var schuelersprecher = document.getElementById('cms_schuljahr_schuelersprecher_personensuche_gewaehlt').value;
	var elternbeirat = document.getElementById('cms_schuljahr_elternbeirat_personensuche_gewaehlt').value;
	var vertretungsplanung = document.getElementById('cms_schuljahr_vertretungsplanung_personensuche_gewaehlt').value;
	var datenschutz = document.getElementById('cms_schuljahr_datenschutz_personensuche_gewaehlt').value;
	var hausmeister = document.getElementById('cms_schuljahr_hausmeister_personensuche_gewaehlt').value;

	var meldung = '<p>Das Schuljahr konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>es wurde eine ungültige Bezeichnung eingegeben. Es dürfen nur lateinische Buchstaben, Umlaute, »ß«, »-«, sowie die Zahlen von 0 bis 9 verwendet werden.</li>';
		fehler = true;
	}
	if (bezeichnung.tolowercase == "schuljahrübergreifend") {
		meldung += '<li>es wurde eine ungültige Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	beginnd = new Date(beginnj, beginnm, beginnt, 0, 0, 0, 0);
	ended = new Date(endej, endem, endet, 23, 59, 59, 999);

	if (beginnd-ended >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahr bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schuljahr bearbeiten', 'Es wird geprüft, ob in diesem Zeitraum bereits ein anders Schuljahr existiert.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung",     	bezeichnung);
		formulardaten.append("beginnj", 				beginnj);
		formulardaten.append("beginnm", 				beginnm);
		formulardaten.append("beginnt", 				beginnt);
		formulardaten.append("endej", 				endej);
		formulardaten.append("endem", 				endem);
		formulardaten.append("endet", 				endet);
		formulardaten.append("schulleitung", 		schulleitung);
		formulardaten.append("stellschulleitung", 	stellschulleitung);
		formulardaten.append("abteilungsleitung", 	abteilungsleitung);
		formulardaten.append("sekretariat", 		sekretariat);
		formulardaten.append("sozialarbeit", 		sozialarbeit);
		formulardaten.append("oberstufenberatung", 	oberstufenberatung);
		formulardaten.append("beratungslehrer", 	beratungslehrer);
		formulardaten.append("verbindungslehrer", 	verbindungslehrer);
		formulardaten.append("schuelersprecher", 	schuelersprecher);
		formulardaten.append("elternbeirat", 		elternbeirat);
		formulardaten.append("vertretungsplanung",	vertretungsplanung);
		formulardaten.append("datenschutz",	datenschutz);
		formulardaten.append("hausmeister",	hausmeister);
		formulardaten.append("anfragenziel", 	'151');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/DOPPELT/)) {
				meldung += '<li>es gibt bereits ein Schuljahr in diesem Zeitraum.</li>';
				cms_meldung_an('fehler', 'Schuljahr bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/ZEITRAUM/)) {
				meldung += '<li>es gibt Zeiträume, die durch die neuen Beginn- und Enddaten nicht mehr zum Schuljahr gehören würden. Diese Zeiträume müssen zuerst verlegt oder gelöscht werden.</li>';
				cms_meldung_an('fehler', 'Schuljahr bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/PERSONEN/)) {
				meldung += '<li>es wurden Personen Schlüsselpositionen zugeordnet, die diese nicht inne haben dürfen.</li>';
				cms_meldung_an('fehler', 'Schuljahr bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schuljahr bearbeiten', '<p>Das Schuljahr <i>'+bezeichnung+'</i> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuljahre\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_verantwortlichkeiten_vorbereiten (id) {
	cms_laden_an('Verantwortlichkeiten vorbereiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'354');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Schuljahre/Verantwortlichkeiten_festlegen');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_verantwlortlichkeiten_speichern() {
	cms_laden_an('Verantwortlichkeiten bearbeiten', 'Die Eingaben werden überprüft.');
	var klassen = document.getElementById('cms_verantwortlichkeiten_klassen').value;
	var stufen = document.getElementById('cms_verantwortlichkeiten_stufen').value;
	var klasseninfo = "";
	var stufeninfo = "";

	var meldung = '<p>Die Verantwortlichkeiten konnten nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
	var aktionnoetig = false;

	// Pflichteingaben prüfen
	if (klassen.length > 0) {
		klassen = (klassen.substr(1)).split("|");
		for (var i=0; i<klassen.length; i++) {
			if (cms_check_ganzzahl(klassen[i], 0)) {
				var lehr = document.getElementById('cms_verantwortlichkeiten_kl_'+klassen[i]);
				var stel = document.getElementById('cms_verantwortlichkeiten_ks_'+klassen[i]);
				var raum = document.getElementById('cms_verantwortlichkeiten_kr_'+klassen[i]);
				if (lehr && stel && raum &&
					  (cms_check_ganzzahl(lehr.value, 0) || lehr.value == '-') &&
					  (cms_check_ganzzahl(stel.value, 0) || stel.value == '-') &&
					  (cms_check_ganzzahl(raum.value, 0) || raum.value == '-')) {
					klasseninfo += klassen[i]+';'+lehr.value+';'+stel.value+';'+raum.value+'|';
				}
				else {fehler = true;}
			}
			else {fehler = true;}
		}
	}
	if (stufen.length > 0) {
		stufen = (stufen.substr(1)).split("|");
		for (var i=0; i<stufen.length; i++) {
			if (cms_check_ganzzahl(stufen[i], 0)) {
				var lehr = document.getElementById('cms_verantwortlichkeiten_sl_'+stufen[i]);
				var stel = document.getElementById('cms_verantwortlichkeiten_ss_'+stufen[i]);
				var raum = document.getElementById('cms_verantwortlichkeiten_sr_'+stufen[i]);
				if (lehr && stel && raum &&
					  (cms_check_ganzzahl(lehr.value, 0) || lehr.value == '-') &&
					  (cms_check_ganzzahl(stel.value, 0) || stel.value == '-') &&
					  (cms_check_ganzzahl(raum.value, 0) || raum.value == '-')) {
					stufeninfo += stufen[i]+';'+lehr.value+';'+stel.value+';'+raum.value+'|';
				}
				else {fehler = true;}
			}
			else {fehler = true;}
		}
	}
	if (fehler) {
		meldung += '<li>die Zuordnungen sind ungültig.</li>';
		cms_meldung_an('fehler', 'Schuljahr bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		if (stufeninfo.length > 0) {stufeninfo = stufeninfo.substr(0,stufeninfo.length-1);}
		if (klasseninfo.length > 0) {klasseninfo = klasseninfo.substr(0,klasseninfo.length-1);}

		cms_laden_an('Verantwortlichkeiten bearbeiten', 'Die Verantwortlichkeiten werden gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("klasseninfo",   klasseninfo);
		formulardaten.append("stufeninfo", 		stufeninfo);
		formulardaten.append("anfragenziel", 	'355');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Verantwortlichkeiten bearbeiten', '<p>Die Verantwortlichkeiten werden gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuljahre\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
