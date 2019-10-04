function cms_vplan_schulstunden_laden(art) {
	if ((art == 'von') || (art == 'bis')) {
		feld = document.getElementById('cms_ausplanung_schulstunden_'+art);
		feld.innerHTML = cms_ladeicon();
		var tag = document.getElementById('cms_ausplanung_datum_'+art+'_T').value;
		var monat = document.getElementById('cms_ausplanung_datum_'+art+'_M').value;
		var jahr = document.getElementById('cms_ausplanung_datum_'+art+'_J').value;
		if (art == 'von') {
			document.getElementById('cms_klassen_ausplanen').innerHTML = cms_ladeicon();
			document.getElementById('cms_ausplanung_datum_bis_T').value = tag;
			document.getElementById('cms_ausplanung_datum_bis_M').value = monat;
			document.getElementById('cms_ausplanung_datum_bis_J').value = jahr;
			cms_datumcheck('cms_ausplanung_datum_bis');
			document.getElementById('cms_ausplanung_schulstunden_bis').innerHTML = cms_ladeicon();
		}

		if (!fehler) {
			if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
			if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
			if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		}

		if (fehler) {
			feld.innerHTML = "<p class=\"cms_notiz\">Fehler beim Laden der Klassen.</p>";
		}
		else {
			formulardaten = new FormData();
			formulardaten.append("tag", 	tag);
			formulardaten.append("monat", monat);
			formulardaten.append("jahr", 	jahr);
			formulardaten.append("art", 	art);
			formulardaten.append("anfragenziel", 	'171');

			function anfragennachbehandlung(rueckgabe) {
				if (rueckgabe.match(/^<option/) || rueckgabe == "") {
					feld.innerHTML = "<select id=\"cms_ausplanung_std_"+art+"\" name=\"cms_ausplanung_std_"+art+"\">"+rueckgabe+"</select>";
					if (art == 'von') {cms_vplan_klassen_laden();}
				}
				else {"<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";}
			}
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
		}
	}
}


function cms_vplan_klassen_laden() {
	fehler = false;
	feld = document.getElementById('cms_klassen_ausplanen');
	var tag = document.getElementById('cms_ausplanung_datum_von_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_von_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_von_J').value;

	if (!fehler) {
		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	}

	if (fehler) {
		feld.innerHTML = "<p class=\"cms_notiz\">Fehler beim Laden der Klassen.</p>";
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'170');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<option/) || rueckgabe == "") {
				feld.innerHTML = "<select id=\"cms_ausplanen_k\" name=\"cms_ausplanen_k\">"+rueckgabe+"</select>";
				cms_vplan_schulstunden_laden('bis')
			}
			else {"<p class=\"cms_notiz\">Fehler beim Laden der Klassen.</p>";}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_ausgeplant_laden(aendern) {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_l');
	cms_gesichert_laden('cms_ausplanung_ausgeplant_r');
	cms_gesichert_laden('cms_ausplanung_ausgeplant_k');
	if ((aendern == '+') || (aendern == '-')) {
		var tag = parseInt(document.getElementById('cms_ausplanung_datum_T').value);
		var monat = parseInt(document.getElementById('cms_ausplanung_datum_M').value);
		var jahr = parseInt(document.getElementById('cms_ausplanung_datum_J').value);

		if (aendern == '+') {tag++;}
		if (aendern == '-') {tag--;}

		document.getElementById('cms_ausplanung_datum_T').value = tag;
		cms_datumcheck('cms_ausplanung_datum');
	}

	cms_ausplanen_lausgeplant();
}

function cms_ausplanen_lausgeplant(tagu, monatu, jahru) {
	tagu = tagu || false;
	monatu = monatu || false;
	jahru = jahru || false;
	if (tagu && monatu && jahru) {
		document.getElementById('cms_ausplanung_datum_T').value = tagu;
		document.getElementById('cms_ausplanung_datum_M').value = monatu;
		document.getElementById('cms_ausplanung_datum_J').value = jahru;
		cms_datumcheck('cms_ausplanung_datum');
	}
	cms_gesichert_laden('cms_ausplanung_ausgeplant_l');
	cms_gesichert_laden('cms_ausplanung_ausgeplant_r');
	cms_gesichert_laden('cms_ausplanung_ausgeplant_k');
	feld = document.getElementById('cms_ausplanung_ausgeplant_l');
	var tag = document.getElementById('cms_ausplanung_datum_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_J').value;
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		feld.innerHTML = cms_meldung_code('fehler', 'Fehler beim Laden der ausgeplanten Lehrkräfte', '<p>Beim Laden der ausgeplanten Lehrkräfte ist ein Fehler aufgetreten.</p>');
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'0');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				feld.innerHTML = rueckgabe;
				cms_ausplanen_rausgeplant();
			}
			else {feld.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_ausplanen_rausgeplant() {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_r');
	feld = document.getElementById('cms_ausplanung_ausgeplant_r');
	var tag = document.getElementById('cms_ausplanung_datum_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_J').value;
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		feld.innerHTML = cms_meldung_code('fehler', 'Fehler beim Laden der ausgeplanten Räume', '<p>Beim Laden der ausgeplanten Räume ist ein Fehler aufgetreten.</p>');
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'1');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				feld.innerHTML = rueckgabe;
				cms_ausplanen_kausgeplant()
			}
			else {feld.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_ausplanen_kausgeplant() {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_k');
	feld = document.getElementById('cms_ausplanung_ausgeplant_k');
	var tag = document.getElementById('cms_ausplanung_datum_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_J').value;
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		feld.innerHTML = cms_meldung_code('fehler', 'Fehler beim Laden der ausgeplanten Klassen', '<p>Beim Laden der ausgeplanten Klassen ist ein Fehler aufgetreten.</p>');
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'2');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				feld.innerHTML = rueckgabe;
			}
			else {feld.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_ausplanung_art_aendern() {
	var art = document.getElementById('cms_ausplanen_art').value;

	if (art == 'l') {
		cms_ausblenden('cms_ausplanung_art_r');
		cms_ausblenden('cms_ausplanung_grund_r');
		cms_ausblenden('cms_ausplanung_art_k');
		cms_ausblenden('cms_ausplanung_grund_k');
		cms_einblenden('cms_ausplanung_art_l', 'table-row');
		cms_einblenden('cms_ausplanung_grund_l', 'table-row');
	}
	if (art == 'r') {
		cms_ausblenden('cms_ausplanung_art_l');
		cms_ausblenden('cms_ausplanung_grund_l');
		cms_ausblenden('cms_ausplanung_art_k');
		cms_ausblenden('cms_ausplanung_grund_k');
		cms_einblenden('cms_ausplanung_art_r', 'table-row');
		cms_einblenden('cms_ausplanung_grund_r', 'table-row');
	}
	if (art == 'k') {
		cms_ausblenden('cms_ausplanung_art_r');
		cms_ausblenden('cms_ausplanung_grund_r');
		cms_ausblenden('cms_ausplanung_art_l');
		cms_ausblenden('cms_ausplanung_grund_l');
		cms_einblenden('cms_ausplanung_art_k', 'table-row');
		cms_einblenden('cms_ausplanung_grund_k', 'table-row');
	}
}

function cms_ausplanung_speichern() {
	cms_laden_an('Ausplanung speichern', 'Die Eingaben werden überprüft.');
	var art = document.getElementById('cms_ausplanen_art').value;
	var ziel = document.getElementById('cms_ausplanen_'+art).value;
	var grund = document.getElementById('cms_ausplanen_grund'+art).value;
	var vonS = document.getElementById('cms_ausplanung_std_von').value;
	var vonT = document.getElementById('cms_ausplanung_datum_von_T').value;
	var vonM = document.getElementById('cms_ausplanung_datum_von_M').value;
	var vonJ = document.getElementById('cms_ausplanung_datum_von_J').value;
	var bisS = document.getElementById('cms_ausplanung_std_bis').value;
	var bisT = document.getElementById('cms_ausplanung_datum_bis_T').value;
	var bisM = document.getElementById('cms_ausplanung_datum_bis_M').value;
	var bisJ = document.getElementById('cms_ausplanung_datum_bis_J').value;

	var meldung = '<p>Die Ausplanung konnte nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(vonT,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(vonM,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(vonJ,0)) {fehler = true;}
	if (!cms_check_ganzzahl(bisT,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(bisM,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(bisJ,0)) {fehler = true;}
	if (!cms_check_uhrzeit(vonS,0) || !cms_check_uhrzeit(bisS,0)) {fehler = true;}
	else {vonx = vonS.split(':'); bisx = bisS.split(':');}
	if (!cms_check_ganzzahl(ziel, 0)) {fehler = true;}

	if (!fehler) {
		var von = new Date(vonJ, vonM, vonT, vonx[0], vonx[0], 0, 0).getTime() / 1000;
		var bis = new Date(bisJ, bisM, bisT, bisx[0], bisx[0], 0, 0).getTime() / 1000;

		if (von >= bis) {
			meldung += '<li>der Ausplanugnszeitraum ist ungültig.</li>';
			fehler = true;
		}
	}

	if ((art != 'l') && (art != 'r') && (art != 'k')) {fehler = true;}
	if ((art == 'l') && (grund != 'dv') && (grund != 'k') && (grund != 'kk') && (grund != 'p') && (grund != 'b') && (grund != 'ex') && (grund != 's')) {fehler = true;}
	else if ((art == 'r') && (grund != 'b') && (grund != 'p') && (grund != 'k') && (grund != 's')) {fehler = true;}
	else if ((art == 'k') && (grund != 'ex') && (grund != 'sh') && (grund != 'p') && (grund != 'bv') && (grund != 'k') && (grund != 's')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Ausplanung speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Ausplanung speichern', 'Die Ausplanung wird gespeichert');

		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("art", art);
		formulardaten.append("ziel", ziel);
    formulardaten.append("grund", grund);
    formulardaten.append("vonS", vonS);
    formulardaten.append("vonT", vonT);
    formulardaten.append("vonM", vonM);
    formulardaten.append("vonJ", vonJ);
    formulardaten.append("bisS", bisS);
    formulardaten.append("bisT", bisT);
    formulardaten.append("bisM", bisM);
    formulardaten.append("bisJ", bisJ);
		formulardaten.append("anfragenziel", 	'3');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_ausplanen_lausgeplant(vonT, vonM, vonJ);
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_ausplanung_loeschen_anzeigen (name, id, art) {
	cms_meldung_an('warnung', 'Ausplanung löschen', '<p>Soll die Ausplanung für »'+name+'« wirklich gelöscht werden?</p><p>Bereits durchgeführte Vertretungen bleiben davon unberührt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_ausplanung_loeschen(\''+id+'\', \''+art+'\')">Löschung durchführen</span></p>');
}


function cms_ausplanung_loeschen(id, art) {
	cms_laden_an('Ausplanung löschen', 'Die Ausplanung wird gelöscht.');

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	formulardaten.append("id",     				id);
	formulardaten.append("art",     			art);
	formulardaten.append("anfragenziel", 	'4');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_ausplanen_lausgeplant();
			cms_laden_aus();
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vplan_konflikte(aendern) {
	cms_gesichert_laden('cms_vplan_vertretungstext');
	cms_gesichert_laden('cms_vplan_konflikte_liste');
	cms_gesichert_laden('cms_vplan_konflikte_plan');
	if ((aendern == '+') || (aendern == '-')) {
		var tag = parseInt(document.getElementById('cms_vplankonflikte_datum_T').value);
		var monat = parseInt(document.getElementById('cms_vplankonflikte_datum_M').value);
		var jahr = parseInt(document.getElementById('cms_vplankonflikte_datum_J').value);

		if (aendern == '+') {tag++;}
		if (aendern == '-') {tag--;}

		document.getElementById('cms_vplankonflikte_datum_T').value = tag;
		cms_datumcheck('cms_vplankonflikte_datum');
	}
	var vtext = document.getElementById('cms_vplan_vertretungstext');
	var liste = document.getElementById('cms_vplan_konflikte_liste');
	var plan = document.getElementById('cms_vplan_konflikte_plan');

	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;

	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		vtext.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Anmerkungen ist ein Fehler aufgetreten.</p>';
		liste.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
		plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'172');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/)) {
				vtext.innerHTML = rueckgabe;
				cms_vplan_konflikte_liste('k');
			}
			else {
				vtext.innerHTML = rueckgabe;
				liste.innerHTML = rueckgabe;
				plan.innerHTML = rueckgabe;
			}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_vtexte_speichern(aendern) {
	cms_laden_an('Vertretungstexte speichern', 'Die Eingaben werden überprüft.');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var lehrer = document.getElementById('cms_vplan_vtext_lehrer').value;
	var schueler = document.getElementById('cms_vplan_vtext_schueler').value;
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Vertretungstexte speichern', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Vertretungstexte speichern', 'Der Vertretungstext wird gespeichert.');
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("lehrer", 	lehrer);
		formulardaten.append("schueler", 	schueler);
		formulardaten.append("anfragenziel", 	'173');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == 'ERFOLG') {
				cms_meldung_an('erfolg', 'Vertretungstexte speichern', '<p>Die Vertretungstexte wurden gespeichert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_vplan_lehrer(aendern) {
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
	cms_gesichert_laden('cms_vplan_wochenplan_l');
	if (koppeln == 1) {
		cms_gesichert_laden('cms_vplan_wochenplan_r');
		cms_gesichert_laden('cms_vplan_wochenplan_k');
	}
	var tag = parseInt(document.getElementById('cms_vplanlehrer_datum_T').value);
	var monat = parseInt(document.getElementById('cms_vplanlehrer_datum_M').value);
	var jahr = parseInt(document.getElementById('cms_vplanlehrer_datum_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	if ((aendern == '+') || (aendern == '-')) {
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
	}

	document.getElementById('cms_vplanlehrer_datum_T').value = tag;
	document.getElementById('cms_vplanlehrer_datum_M').value = monat;
	document.getElementById('cms_vplanlehrer_datum_J').value = jahr;
	cms_datumcheck('cms_vplanlehrer_datum');
	if (koppeln == 1) {
		document.getElementById('cms_vplanraum_datum_T').value = tag;
		document.getElementById('cms_vplanraum_datum_M').value = monat;
		document.getElementById('cms_vplanraum_datum_J').value = jahr;
		cms_datumcheck('cms_vplanraum_datum');
		document.getElementById('cms_vplanklasse_datum_T').value = tag;
		document.getElementById('cms_vplanklasse_datum_M').value = monat;
		document.getElementById('cms_vplanklasse_datum_J').value = jahr;
		cms_datumcheck('cms_vplanklasse_datum');
	}

	if (koppeln == 1) {cms_vplan_wochenplan_l('a');}
	else {cms_vplan_wochenplan_l();}
}


function cms_vplan_raum(aendern) {
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
	cms_gesichert_laden('cms_vplan_wochenplan_r');
	if (koppeln == 1) {
		cms_gesichert_laden('cms_vplan_wochenplan_l');
		cms_gesichert_laden('cms_vplan_wochenplan_k');
	}
	var tag = parseInt(document.getElementById('cms_vplanraum_datum_T').value);
	var monat = parseInt(document.getElementById('cms_vplanraum_datum_M').value);
	var jahr = parseInt(document.getElementById('cms_vplanraum_datum_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	if ((aendern == '+') || (aendern == '-')) {
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
	}

	document.getElementById('cms_vplanraum_datum_T').value = tag;
	cms_datumcheck('cms_vplanraum_datum');
	if (koppeln == 1) {
		document.getElementById('cms_vplanlehrer_datum_T').value = tag;
		document.getElementById('cms_vplanlehrer_datum_M').value = monat;
		document.getElementById('cms_vplanlehrer_datum_J').value = jahr;
		cms_datumcheck('cms_vplanlehrer_datum');
		document.getElementById('cms_vplanklasse_datum_T').value = tag;
		document.getElementById('cms_vplanklasse_datum_M').value = monat;
		document.getElementById('cms_vplanklasse_datum_J').value = jahr;
		cms_datumcheck('cms_vplanklasse_datum');
	}

	if (koppeln == 1) {cms_vplan_wochenplan_l('a');}
	else {cms_vplan_wochenplan_r();}
}


function cms_vplan_klasse(aendern) {
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
	cms_gesichert_laden('cms_vplan_wochenplan_k');
	if (koppeln == 1) {
		cms_gesichert_laden('cms_vplan_wochenplan_l');
		cms_gesichert_laden('cms_vplan_wochenplan_r');
	}
	var tag = parseInt(document.getElementById('cms_vplanklasse_datum_T').value);
	var monat = parseInt(document.getElementById('cms_vplanklasse_datum_M').value);
	var jahr = parseInt(document.getElementById('cms_vplanklasse_datum_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	if ((aendern == '+') || (aendern == '-')) {
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
	}

	document.getElementById('cms_vplanklasse_datum_T').value = tag;
	document.getElementById('cms_vplanklasse_datum_M').value = monat;
	document.getElementById('cms_vplanklasse_datum_J').value = jahr;
	cms_datumcheck('cms_vplanklasse_datum');
	if (koppeln == 1) {
		document.getElementById('cms_vplanlehrer_datum_T').value = tag;
		document.getElementById('cms_vplanlehrer_datum_M').value = monat;
		document.getElementById('cms_vplanlehrer_datum_J').value = jahr;
		cms_datumcheck('cms_vplanlehrer_datum');
		document.getElementById('cms_vplanraum_datum_T').value = tag;
		document.getElementById('cms_vplanraum_datum_M').value = monat;
		document.getElementById('cms_vplanraum_datum_J').value = jahr;
		cms_datumcheck('cms_vplanraum_datum');
	}

	if (koppeln == 1) {cms_vplan_wochenplan_l('a');}
	else {cms_vplan_wochenplan_k();}
}



function cms_vplan_konflikte_liste(art) {
	cms_gesichert_laden('cms_vplan_konflikte_liste');
	if ((art == 'a') || (art == 'k')) {cms_gesichert_laden('cms_vplan_konflikte_plan');}
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var liste = document.getElementById('cms_vplan_konflikte_liste');
	var plan = document.getElementById('cms_vplan_konflikte_plan');
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		liste.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
		if (art == 'a') {plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';}
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'6');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h4/) || rueckgabe.match(/^<p/) || rueckgabe.match(/^<table/)) {
				liste.innerHTML = rueckgabe;
				if ((art == 'a') || (art == 'k')) {cms_vplan_konflikte_plan();}
			}
			else {
				liste.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
				if ((art == 'a') || (art == 'k')) {plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';}
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}



function cms_vplan_konfliktloesung_zueuecksetzen_anzeigen() {
	cms_meldung_an('warnung', 'Konfliktlösungen zurücksetzen', '<p>Sollen alle bisher nicht übernommenen Konfliktlösungen wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_konfliktloesung_zueuecksetzen()">Löschung durchführen</span></p>');
}


function cms_vplan_konfliktloesung_zueuecksetzen() {
	cms_laden_an('Konfliktlösungen zurücksetzen', 'Alle Konfliktlösungen seit der letzten Änderung werden gelöscht');
	formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'174');
		// VERTRETUNSTEXTE LADEN
	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_vplan_konflikte_liste('a');
			cms_meldung_an('erfolg', 'Konfliktlösungen zurücksetzen', '<p>Die Konfliktlösungen wurden zurückgesetzt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_vplan_konfliktloesung(ort, id) {
	cms_laden_an('Konfliktlösung speichern', 'Die Eingaben werden überprüft.');
	fehler = false;
	if ((ort != 'a') && (ort != 't')) {fehler = false;}
	if (!cms_check_ganzzahl(id,0)) {fehler = true;}

	if (!fehler) {
		var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
		var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
		var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
		var kurs = document.getElementById('cms_vplan_liste_kurs_'+ort+id).value;
		var lehrer = document.getElementById('cms_vplan_liste_lehrer_'+ort+id).value;
		var raum = document.getElementById('cms_vplan_liste_raum_'+ort+id).value;
		var stunde = document.getElementById('cms_vplan_liste_std_'+ort+id).value;
		var bem = document.getElementById('cms_vplan_liste_bem_'+ort+id).value;
		var anz = document.getElementById('cms_vplan_liste_anz_'+ort+id).value;
		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		if (!cms_check_ganzzahl(kurs,0)) {fehler = true;}
		if (!cms_check_ganzzahl(raum,0)) {fehler = true;}
		if (!cms_check_ganzzahl(stunde,0) && (stunde != '-')) {fehler = true;}
		if (!cms_check_toggle(anz,0)) {fehler = true;}
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Konfliktlösung speichern', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Konfliktlösung speichern', 'Die Konfliktlösung wird zwischengespeichert.');
		formulardaten = new FormData();
		formulardaten.append("ort", 	ort);
		formulardaten.append("id", 	id);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 		jahr);
		formulardaten.append("lehrer", 	lehrer);
		formulardaten.append("kurs", 		kurs);
		formulardaten.append("raum", 		raum);
		formulardaten.append("stunde", 	stunde);
		formulardaten.append("bemerkung", bem);
		formulardaten.append("anzeigen", 	anz);
		formulardaten.append("anfragenziel", 	'175');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == 'ERFOLG') {
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_sondereinsatz_speichern() {
	cms_laden_an('Sondereinsatz speichern', 'Die Eingaben werden überprüft.');
	fehler = false;
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var kurs = document.getElementById('cms_vplan_sondereinsatz_kurs').value;
	var lehrer = document.getElementById('cms_vplan_sondereinsatz_lehrer').value;
	var raum = document.getElementById('cms_vplan_sondereinsatz_raum').value;
	var stunde = document.getElementById('cms_vplan_sondereinsatz_stunde').value;
	var bem = document.getElementById('cms_vplan_sondereinsatz_bem').value;
	var anz = document.getElementById('cms_vplan_sondereinsatz_anz').value;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(kurs,0)) {fehler = true;}
	if (!cms_check_ganzzahl(raum,0)) {fehler = true;}
	if (!cms_check_ganzzahl(stunde,0)) {fehler = true;}
	if (!cms_check_toggle(anz,0)) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Sondereinsatz speichern', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Sondereinsatz speichern', 'Der Sondereinsatz wird zwischengespeichert.');
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 		jahr);
		formulardaten.append("lehrer", 	lehrer);
		formulardaten.append("kurs", 		kurs);
		formulardaten.append("raum", 		raum);
		formulardaten.append("stunde", 	stunde);
		formulardaten.append("bemerkung", bem);
		formulardaten.append("anzeigen", 	anz);
		formulardaten.append("anfragenziel", 	'176');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == 'ERFOLG') {
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_konflikte_planwahl() {
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var planart = document.getElementById('cms_vplankonflikte_plan_art').value;
	var ziele = document.getElementById('cms_vplankonflikte_plan_ziel');
	ziele.innerHTML = "";
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if ((planart != 'l') && (planart != 'r') && (planart != 'k') && (planart != 's')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Konfliktplan laden', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("planart", 	planart);
		formulardaten.append("anfragenziel", 	'177');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<option/) || rueckgabe == '') {
				ziele.innerHTML = rueckgabe;
				cms_vplan_konflikte_plan();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_vplan_konflikte_plan(art) {
	cms_gesichert_laden('cms_vplan_konflikte_plan');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var planart = document.getElementById('cms_vplankonflikte_plan_art').value;
	var planziel = document.getElementById('cms_vplankonflikte_plan_ziel').value;
	var plan = document.getElementById('cms_vplan_konflikte_plan');
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("planart", 	planart);
		formulardaten.append("planziel", 	planziel);
		formulardaten.append("anfragenziel", 	'7');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<p/) || rueckgabe.match(/^<h4/)) {
				plan.innerHTML = rueckgabe;
			}
			else {
				plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_wochenplan_l(art, lehrer, raum, klasse) {
	alert(art+lehrer+raum+klasse);
}
