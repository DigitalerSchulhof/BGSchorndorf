function cms_vplan_schulstunden_laden(art) {
	if ((art == 'von') || (art == 'bis')) {
		vplanstdfeld = document.getElementById('cms_ausplanung_schulstunden_'+art);
		vplanstdfeld.innerHTML = cms_ladeicon();
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
			vplanstdfeld.innerHTML = "<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";
		}
		else {
			formulardaten = new FormData();
			formulardaten.append("tag", 	tag);
			formulardaten.append("monat", monat);
			formulardaten.append("jahr", 	jahr);
			formulardaten.append("art", 	art);
			formulardaten.append("anfragenziel", 	'171');

			function anfragennachbehandlung_vplanstunden(rueckgabe) {
				if (rueckgabe.match(/^<option/) || rueckgabe == "") {
					vplanstdfeld.innerHTML = "<select id=\"cms_ausplanung_std_"+art+"\" name=\"cms_ausplanung_std_"+art+"\">"+rueckgabe+"</select>";
					if (art == 'von') {cms_vplan_klassen_laden();}
				}
				else {"<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";}
			}
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung_vplanstunden);
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
				document.getElementById('cms_ausplanung_datum_bis_T').value = vonT;
				document.getElementById('cms_ausplanung_datum_bis_M').value = vonM;
				document.getElementById('cms_ausplanung_datum_bis_J').value = vonJ;
				document.getElementById("cms_ausplanung_std_von").selectedIndex = "0";
				cms_datumcheck('cms_ausplanung_datum_bis');
				window.setTimeout(cms_vplan_schulstunden_laden('bis'), 100);
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
	// Nächsten Montag bestimmen
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	// Nächster Montag
	zweittag = parseInt(tag) - wochentag + 8;
	document.getElementById('cms_vplankonflikte_zweitdatum_T').value = zweittag;
	document.getElementById('cms_vplankonflikte_zweitdatum_M').value = monat;
	document.getElementById('cms_vplankonflikte_zweitdatum_J').value = jahr;
	cms_datumcheck('cms_vplankonflikte_zweitdatum');

	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;

	if (koppeln == 1) {
		cms_gesichert_laden('cms_vplan_wochenplan_l');
		cms_gesichert_laden('cms_vplan_wochenplan_r');
		cms_gesichert_laden('cms_vplan_wochenplan_k');
		document.getElementById('cms_vplanlehrer_datum_T').value = tag;
		document.getElementById('cms_vplanlehrer_datum_M').value = monat;
		document.getElementById('cms_vplanlehrer_datum_J').value = jahr;
		cms_datumcheck('cms_vplanlehrer_datum');
		document.getElementById('cms_vplanraum_datum_T').value = tag;
		document.getElementById('cms_vplanraum_datum_M').value = monat;
		document.getElementById('cms_vplanraum_datum_J').value = jahr;
		cms_datumcheck('cms_vplanraum_datum');
		document.getElementById('cms_vplanklasse_datum_T').value = tag;
		document.getElementById('cms_vplanklasse_datum_M').value = monat;
		document.getElementById('cms_vplanklasse_datum_J').value = jahr;
		cms_datumcheck('cms_vplanklasse_datum');
		cms_vplan_lehrer('j');
	}

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


function cms_vplan_zweitkonflikte(aendern) {
	cms_gesichert_laden('cms_vplan_konflikte_plan');
	if ((aendern == '+') || (aendern == '-')) {
		var tag = parseInt(document.getElementById('cms_vplankonflikte_zweitdatum_T').value);
		var monat = parseInt(document.getElementById('cms_vplankonflikte_zweitdatum_M').value);
		var jahr = parseInt(document.getElementById('cms_vplankonflikte_zweitdatum_J').value);
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
		document.getElementById('cms_vplankonflikte_zweitdatum_T').value = tag;
		cms_datumcheck('cms_vplankonflikte_zweitdatum');
	}
	var plan = document.getElementById('cms_vplan_konflikte_plan');

	var tag = document.getElementById('cms_vplankonflikte_zweitdatum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_zweitdatum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_zweitdatum_J').value;

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
		cms_vplan_konflikte_plan('k');
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
		cms_gesichert_laden('cms_vplan_konflikte_liste');
		cms_gesichert_laden('cms_vplan_konflikte_plan');
	}
	var tag = parseInt(document.getElementById('cms_vplanlehrer_datum_T').value);
	var originaltag = tag;
	var monat = parseInt(document.getElementById('cms_vplanlehrer_datum_M').value);
	var jahr = parseInt(document.getElementById('cms_vplanlehrer_datum_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	if ((aendern == '+') || (aendern == '-')) {
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
		originaltag = tag;
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
		document.getElementById('cms_vplankonflikte_datum_T').value = originaltag;
		document.getElementById('cms_vplankonflikte_datum_M').value = monat;
		document.getElementById('cms_vplankonflikte_datum_J').value = jahr;
		cms_datumcheck('cms_vplankonflikte_datum');
		document.getElementById('cms_vplankonflikte_zweitdatum_T').value = tag+7;
		document.getElementById('cms_vplankonflikte_zweitdatum_M').value = monat;
		document.getElementById('cms_vplankonflikte_zweitdatum_J').value = jahr;
		cms_datumcheck('cms_vplankonflikte_zweitdatum');
	}

	if (koppeln == 1) {
		cms_vplan_wochenplan_l('a');
		cms_vplan_konflikte_liste('a');
	}
	else {cms_vplan_wochenplan_l();}
}


function cms_vplan_raum(aendern) {
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
	cms_gesichert_laden('cms_vplan_wochenplan_r');
	if (koppeln == 1) {
		cms_gesichert_laden('cms_vplan_wochenplan_l');
		cms_gesichert_laden('cms_vplan_wochenplan_k');
		cms_gesichert_laden('cms_vplan_konflikte_liste');
		cms_gesichert_laden('cms_vplan_konflikte_plan');
	}
	var tag = parseInt(document.getElementById('cms_vplanraum_datum_T').value);
	var originaltag = tag;
	var monat = parseInt(document.getElementById('cms_vplanraum_datum_M').value);
	var jahr = parseInt(document.getElementById('cms_vplanraum_datum_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	if ((aendern == '+') || (aendern == '-')) {
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
		originaltag = tag;
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
		document.getElementById('cms_vplankonflikte_datum_T').value = originaltag;
		document.getElementById('cms_vplankonflikte_datum_M').value = monat;
		document.getElementById('cms_vplankonflikte_datum_J').value = jahr;
		cms_datumcheck('cms_vplankonflikte_datum');
		document.getElementById('cms_vplankonflikte_zweitdatum_T').value = tag+7;
		document.getElementById('cms_vplankonflikte_zweitdatum_M').value = monat;
		document.getElementById('cms_vplankonflikte_zweitdatum_J').value = jahr;
		cms_datumcheck('cms_vplankonflikte_zweitdatum');
	}

	if (koppeln == 1) {
		cms_vplan_wochenplan_l('a');
		cms_vplan_konflikte_liste('a');
	}
	else {cms_vplan_wochenplan_r();}
}


function cms_vplan_klasse(aendern) {
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
	cms_gesichert_laden('cms_vplan_wochenplan_k');
	if (koppeln == 1) {
		cms_gesichert_laden('cms_vplan_wochenplan_l');
		cms_gesichert_laden('cms_vplan_wochenplan_r');
		cms_gesichert_laden('cms_vplan_konflikte_liste');
		cms_gesichert_laden('cms_vplan_konflikte_plan');
	}
	var tag = parseInt(document.getElementById('cms_vplanklasse_datum_T').value);
	var originaltag = tag;
	var monat = parseInt(document.getElementById('cms_vplanklasse_datum_M').value);
	var jahr = parseInt(document.getElementById('cms_vplanklasse_datum_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	if ((aendern == '+') || (aendern == '-')) {
		if (aendern == '+') {tag += 7;}
		if (aendern == '-') {tag -= 7;}
		originaltag = tag;
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
		document.getElementById('cms_vplankonflikte_datum_T').value = originaltag;
		document.getElementById('cms_vplankonflikte_datum_M').value = monat;
		document.getElementById('cms_vplankonflikte_datum_J').value = jahr;
		cms_datumcheck('cms_vplankonflikte_datum');
		document.getElementById('cms_vplankonflikte_zweitdatum_T').value = tag+7;
		document.getElementById('cms_vplankonflikte_zweitdatum_M').value = monat;
		document.getElementById('cms_vplankonflikte_zweitdatum_J').value = jahr;
		cms_datumcheck('cms_vplankonflikte_zweitdatum');
	}

	if (koppeln == 1) {
		cms_vplan_wochenplan_l('a');
		cms_vplan_konflikte_liste('a');
	}
	else {cms_vplan_wochenplan_k();}
}



function cms_vplan_konflikte_liste(art, sortierung) {
	if (document.getElementById('cms_vplankonflikte_sortierung')) {var sortierungsalternative = document.getElementById('cms_vplankonflikte_sortierung').value;}
	else {var sortierungsalternative = 's';}
	var sortierung = sortierung || sortierungsalternative;
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
		formulardaten.append("sortierung", 	sortierung);
		formulardaten.append("anfragenziel", 	'6');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h4/) || rueckgabe.match(/^<p/) || rueckgabe.match(/^<table/)) {
				liste.innerHTML = rueckgabe;
				if ((art == 'a') || (art == 'k')) {cms_vplan_konflikte_plan(art);}
			}
			else {
				liste.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
				if ((art == 'a') || (art == 'k')) {plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';}
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_vplan_konfliktloesung(uid, kid, ort) {
	cms_laden_an('Konfliktlösung speichern', 'Die Eingaben werden überprüft.');
	fehler = false;
	if ((ort != 'k') && (ort != 'l')) {fehler = false;}
	if (!cms_check_ganzzahl(kid,0) && (kid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(uid,0) && (uid != '-')) {fehler = true;}

	if (!fehler) {
		var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
		var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
		var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
		var kurs = document.getElementById('cms_kurs'+ort+'_'+uid+'_'+kid).value;
		var lehrer = document.getElementById('cms_lehrer'+ort+'_'+uid+'_'+kid).value;
		var raum = document.getElementById('cms_raum'+ort+'_'+uid+'_'+kid).value;
		var stunde = document.getElementById('cms_std'+ort+'_'+uid+'_'+kid).value;
		var bem = document.getElementById('cms_vtext'+ort+'_'+uid+'_'+kid).value;
		var anz = document.getElementById('cms_vanz'+ort+'_'+uid+'_'+kid).value;
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
		formulardaten.append("uid", 	uid);
		formulardaten.append("kid", 	kid);
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
	var zweittag = document.getElementById('cms_vplankonflikte_zweitdatum_T').value;
	var zweitmonat = document.getElementById('cms_vplankonflikte_zweitdatum_M').value;
	var zweitjahr = document.getElementById('cms_vplankonflikte_zweitdatum_J').value;
	var planart = document.getElementById('cms_vplankonflikte_plan_art').value;
	var planziel = document.getElementById('cms_vplankonflikte_plan_ziel').value;
	var plan = document.getElementById('cms_vplan_konflikte_plan');
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(zweittag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(zweitmonat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(zweitjahr,0)) {fehler = true;}

	if (fehler) {
		plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("zweittag", 	 zweittag);
		formulardaten.append("zweitmonat", zweitmonat);
		formulardaten.append("zweitjahr",  zweitjahr);
		formulardaten.append("planart", 	planart);
		formulardaten.append("planziel", 	planziel);
		formulardaten.append("anfragenziel", 	'7');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<div class/) || rueckgabe.match(/^<p class/)) {
				plan.innerHTML = rueckgabe;
			}
			else {
				plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
			}
			if (art == 'a') {cms_vplan_wochenplan_l(art);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_wochenplan_l(art, lehrer, raum, klasse, datum) {
	var lehrer = lehrer || false;
	var raum = raum || false;
	var klasse = klasse || false;
	var art = art || false;
	var datum = datum || false;
	var altlehrer = document.getElementById('cms_vplan_woche_lehrer').value;
	var altraum = document.getElementById('cms_vplan_woche_raum').value;
	var altklasse = document.getElementById('cms_vplan_woche_klasse').value;
	var aktualisieren = '';
	if (art != 'a') {aktualisieren = 'l';}
	else if (art == 'a') {
		if (lehrer) {
			if (lehrer != altlehrer) {
				aktualisieren += 'l';
				document.getElementById('cms_vplan_woche_lehrer').value = lehrer;
				cms_gesichert_laden('cms_vplan_wochenplan_l');
			}
		}
		else {aktualisieren += 'l';}
		if (raum) {
			if (raum != altraum) {
				aktualisieren += 'r';
				document.getElementById('cms_vplan_woche_raum').value = raum;
				cms_gesichert_laden('cms_vplan_wochenplan_r');
			}
		}
		else {aktualisieren += 'r';}
		if (klasse) {
			if (klasse != altklasse) {
				aktualisieren += 'k';
				document.getElementById('cms_vplan_woche_klasse').value = klasse;
				cms_gesichert_laden('cms_vplan_wochenplan_k');
			}
		}
		else {aktualisieren += 'k';}
		if (datum) {
			datum = datum.split(".");
			if ((datum.length == 3) && cms_check_ganzzahl(datum[0],1,31) && cms_check_ganzzahl(datum[1],1,12) && cms_check_ganzzahl(datum[2],0)) {
				document.getElementById('cms_vplanlehrer_datum_T').value = datum[0];
				document.getElementById('cms_vplanraum_datum_T').value = datum[0];
				document.getElementById('cms_vplanklasse_datum_T').value = datum[0];
				document.getElementById('cms_vplanlehrer_datum_M').value = datum[1];
				document.getElementById('cms_vplanraum_datum_M').value = datum[1];
				document.getElementById('cms_vplanklasse_datum_M').value = datum[1];
				document.getElementById('cms_vplanlehrer_datum_J').value = datum[2];
				document.getElementById('cms_vplanraum_datum_J').value = datum[2];
				document.getElementById('cms_vplanklasse_datum_J').value = datum[2];
				cms_datumcheck('cms_vplanlehrer_datum');
				cms_datumcheck('cms_vplanraum_datum');
				cms_datumcheck('cms_vplanklasse_datum');
			}

		}
	}

	// Lehrer aktualisieren
	if (aktualisieren.match(/l/)) {
		var tag = document.getElementById('cms_vplanlehrer_datum_T').value;
		var monat = document.getElementById('cms_vplanlehrer_datum_M').value;
		var jahr = document.getElementById('cms_vplanlehrer_datum_J').value;
		var lehrer = document.getElementById('cms_vplan_woche_lehrer').value;
		var plan = document.getElementById('cms_vplan_wochenplan_l');
		var fehler = false;

		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		if (!cms_check_ganzzahl(lehrer,0)) {fehler = true;}

		if (fehler) {
			plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Lehrerplans ist ein Fehler aufgetreten.</p>';
		}
		else {
			formulardaten = new FormData();
			cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
			formulardaten.append("tag", 	tag);
			formulardaten.append("monat", monat);
			formulardaten.append("jahr", 	jahr);
			formulardaten.append("lehrer",lehrer);
			formulardaten.append("anfragenziel", 	'8');

			function anfragennachbehandlung(rueckgabe) {
				if (rueckgabe.match(/^<div class/)) {
					plan.innerHTML = rueckgabe;
				}
				else {
					plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Lehrerplans ist ein Fehler aufgetreten.</p>';
				}
				if (art == 'a') {cms_vplan_wochenplan_r(aktualisieren);}
			}
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
		}
	}
	else if (art == 'a') {cms_vplan_wochenplan_r(aktualisieren);}
}

function cms_vplan_wochenplan_r(aktualisieren) {
	var aktualisieren = aktualisieren || 'r';
	if (aktualisieren.match(/r/)) {
		var tag = document.getElementById('cms_vplanraum_datum_T').value;
		var monat = document.getElementById('cms_vplanraum_datum_M').value;
		var jahr = document.getElementById('cms_vplanraum_datum_J').value;
		var raum = document.getElementById('cms_vplan_woche_raum').value;
		var plan = document.getElementById('cms_vplan_wochenplan_r');
		var fehler = false;

		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		if (!cms_check_ganzzahl(raum,0)) {fehler = true;}

		if (fehler) {
			plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Raumplans ist ein Fehler aufgetreten.</p>';
		}
		else {
			formulardaten = new FormData();
			cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
			formulardaten.append("tag", 	tag);
			formulardaten.append("monat", monat);
			formulardaten.append("jahr", 	jahr);
			formulardaten.append("raum",raum);
			formulardaten.append("anfragenziel", 	'9');

			function anfragennachbehandlung(rueckgabe) {
				if (rueckgabe.match(/^<div class/)) {
					plan.innerHTML = rueckgabe;
				}
				else {
					plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Raumplans ist ein Fehler aufgetreten.</p>';
				}
				if (aktualisieren.match(/k/)) {cms_vplan_wochenplan_k(aktualisieren);}
			}
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
		}
	}
	else if (aktualisieren.match(/k/)) {cms_vplan_wochenplan_k(aktualisieren);}
}

function cms_vplan_wochenplan_k(aktualisieren) {
	var aktualisieren = aktualisieren || 'k';
	if (aktualisieren.match(/k/)) {
		var tag = document.getElementById('cms_vplanklasse_datum_T').value;
		var monat = document.getElementById('cms_vplanklasse_datum_M').value;
		var jahr = document.getElementById('cms_vplanklasse_datum_J').value;
		var klasse = document.getElementById('cms_vplan_woche_klasse').value;
		var plan = document.getElementById('cms_vplan_wochenplan_k');
		var fehler = false;

		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		if (!cms_check_ganzzahl(klasse.substr(1),0)) {fehler = true;}
		if ((klasse.substr(0,1) != 's') && (klasse.substr(0,1) != 'k')) {fehler = true;}

		if (fehler) {
			plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Klassen- und Stufenplans ist ein Fehler aufgetreten.</p>';
		}
		else {
			formulardaten = new FormData();
			cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
			formulardaten.append("tag", 	tag);
			formulardaten.append("monat", monat);
			formulardaten.append("jahr", 	jahr);
			formulardaten.append("klasse", klasse);
			formulardaten.append("anfragenziel", 	'10');

			function anfragennachbehandlung(rueckgabe) {
				if (rueckgabe.match(/^<div class/)) {
					plan.innerHTML = rueckgabe;
				}
				else {
					plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Klassen- und Stufenplans ist ein Fehler aufgetreten.</p>';
				}
			}
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
		}
	}
}


var vplan_start_std = null;
var vplan_start_lehrer = null;
var vplan_start_raum = null;
var vplan_start_kurs = null;
var vplan_start_bez = null;
var vplan_start_uid = null;
var vplan_start_kid = null;
var vplan_ziel_std = null;
var vplan_ziel_lehrer = null;
var vplan_ziel_raum = null;
var vplan_ziel_kurs = null;
var vplan_ziel_bez = null;
var vplan_ziel_uid = null;
var vplan_ziel_kid = null;

function cms_vertretungsplan_stunde_verschieben_start(std, kurs, lehrer, raum, bez, uid, kid) {
	vplan_start_std = std;
	vplan_start_lehrer = lehrer;
	vplan_start_raum = raum;
	vplan_start_kurs = kurs;
	vplan_start_bez = bez;
	vplan_start_uid = uid;
	vplan_start_kid = kid;
}

function cms_vertretungsplan_stunde_verschieben_ziel(std, kurs, lehrer, raum, bez, uid, kid) {
	vplan_ziel_std = std;
	vplan_ziel_lehrer = lehrer;
	vplan_ziel_raum = raum;
	vplan_ziel_kurs = kurs;
	vplan_ziel_bez = bez;
	vplan_ziel_uid = uid;
	vplan_ziel_kid = kid;
	cms_vertretungsplan_stunde_verschieben_info();
}


function cms_vertretungsplan_stunde_verschieben_info() {
	if ((vplan_start_std == vplan_ziel_std)) {
		cms_meldung_an('info', 'Stunde verschieben', '<p>Es wurde die identische Stunde ausgewählt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span></p>');
	}
	else {
		var info = '<b>Kopieren</b> lässt die Ausgangsstunde unberührt und erstellt am Zielort einen Sondereinsatz.';
		var optionen = '<span class="cms_button" onclick="cms_vplan_stunde_kopieren();">Kopieren</span> ';
		info += '<br><b>Verlegen</b> verlegt die Ausgangsstunde in die Zielstunde. Andere Stunden bleiben unberührt.';
		optionen += '<span class="cms_button" onclick="cms_vplan_stunde_verlegen();">Verlegen</span> ';
		if (cms_check_ganzzahl(vplan_ziel_kurs,0)) {
			info += '<br><b>Ersetzen</b> lässt die Zielstunde entfallen und verlegt die Ausgangsstunde in die Zielstunde.';
			optionen += '<span class="cms_button" onclick="cms_vplan_stunde_ersetzen();">Ersetzen</span> ';
			info += '<br><b>Tauschen</b> vertauscht Ziel- mit Ausgangsstunde.';
			optionen += '<span class="cms_button" onclick="cms_vplan_stunde_tauschen();">Tauschen</span> ';
		}
		optionen += '<span class="cms_button_nein" onclick="cms_meldung_aus();">Abbrechen</span>';
		cms_meldung_an('info', 'Stunde verschieben', '<p>'+vplan_start_bez+' <b>'+vplan_start_std+'</b><br>&searr;<br> '+vplan_ziel_bez+' <b>'+vplan_ziel_std+'</b></p><p>Was genau wollen Sie tun?</p>', '<p>'+optionen+'</p><p class=\"cms_notiz\">'+info+'</p>');
	}
}


function cms_vplan_stunde_kopieren() {
	cms_laden_an('Stunde kopieren', 'Stunde wird kopiert ...');
	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("ausgangsstundeu", vplan_start_uid);
  formulardaten.append("ausgangsstundek", vplan_start_kid);
  formulardaten.append("zielzeit", 	vplan_ziel_std);
  formulardaten.append("anfragenziel", 	'11');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_vplan_konflikte_liste('a');
			cms_laden_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vplan_stunde_verlegen() {
	cms_laden_an('Stunde verlegen', 'Stunde wird verlegt ...');

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("ausgangsstundeu", vplan_start_uid);
  formulardaten.append("ausgangsstundek", vplan_start_kid);
  formulardaten.append("zielzeit", 	vplan_ziel_std);
  formulardaten.append("anfragenziel", 	'12');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_vplan_konflikte_liste('a');
			cms_laden_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vplan_stunde_ersetzen() {
	cms_laden_an('Stunde ersetzen', 'Stunde wird ersetzt ...');

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("ausgangsstundeu", vplan_start_uid);
  formulardaten.append("ausgangsstundek", vplan_start_kid);
  formulardaten.append("zielstundeu", 	vplan_ziel_uid);
  formulardaten.append("zielstundek", 	vplan_ziel_kid);
  formulardaten.append("anfragenziel", 	'13');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_vplan_konflikte_liste('a');
			cms_laden_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vplan_stunde_tauschen() {
	cms_laden_an('Stunde tauschen', 'Stunde wird getauscht ...');

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("ausgangsstundeu", vplan_start_uid);
  formulardaten.append("ausgangsstundek", vplan_start_kid);
  formulardaten.append("zielstundeu", 	vplan_ziel_uid);
  formulardaten.append("zielstundek", 	vplan_ziel_kid);
  formulardaten.append("anfragenziel", 	'14');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_vplan_konflikte_liste('a');
			cms_laden_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vplan_stunde_auswaehlen(uid, kid, modus) {
	if ((modus == 'k') || (modus == 'l')) {
		var auswahl = document.getElementById("cms_stunde"+modus+"_gewaehlt");
		if (auswahl) {
			var test = auswahl.value+";";
			var suche = new RegExp(";"+uid+"\\\|"+kid+";");
			if (test.match(suche, test) !== null) {
				var neueauswahl = test.replace(";"+uid+"|"+kid+";", ";");
				auswahl.value = neueauswahl.substr(0,neueauswahl.length-1);
				cms_klasse_weg_wennklasse("cms_zeile"+modus+"_"+uid+"_"+kid, 'cms_vplan_ausgewaehlt');
			}
			else {
				auswahl.value = test+uid+"|"+kid;
				cms_klasse_dazu_wennklasse("cms_zeile"+modus+"_"+uid+"_"+kid, 'cms_vplan_ausgewaehlt');
			}
		}
	}
}


function cms_vplan_freieressourcen_laden(modus, uid, kid) {
	var uid = uid || '-';
	var kid = kid || '-';
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if ((modus != 'k') && (modus != 'l') && (modus != 'd')) {fehler = true;}
	if (((modus == 'k') || (modus == 'l')) && (kid == '-') && (uid == '-')) {fehler = true;}
	if (!fehler) {
		if ((modus == 'k') || (modus == 'l')) {
			var kurse = document.getElementById("cms_kurs"+modus+"_"+uid+"_"+kid);
			var lehrer = document.getElementById("cms_lehrer"+modus+"_"+uid+"_"+kid);
			var raeume = document.getElementById("cms_raum"+modus+"_"+uid+"_"+kid);
			var tag = document.getElementById("cms_vplankonflikte_datum_T").value;
			var monat = document.getElementById("cms_vplankonflikte_datum_M").value;
			var jahr = document.getElementById("cms_vplankonflikte_datum_J").value;
			var std = document.getElementById("cms_std"+modus+"_"+uid+"_"+kid).value;
		}
		else {
			var kurse = document.getElementById("cms_stundendetails_kurs");
			var lehrer = document.getElementById("cms_stundendetails_lehrer");
			var raeume = document.getElementById("cms_stundendetails_raum");
			var tag = document.getElementById("cms_stundendetails_datum_T").value;
			var monat = document.getElementById("cms_stundendetails_datum_M").value;
			var jahr = document.getElementById("cms_stundendetails_datum_J").value;
			var std = document.getElementById("cms_stundendetails_std").value;
		}
		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		if (!cms_check_ganzzahl(std,0)) {fehler = true;}
	}

	if (!fehler) {
		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", jahr);
		formulardaten.append("std", std);
		formulardaten.append("uid", uid);
		formulardaten.append("kid", kid);
		formulardaten.append("anfragenziel", 	'16');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<option/)) {
				var teile = rueckgabe.split("|||");
				kurse.innerHTML = teile[0];
				lehrer.innerHTML = teile[1];
				raeume.innerHTML = teile[2];
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}



function cms_stundendetails_laden(uid, kid, datum, uhrzeit) {
	var datum = datum || '-';
	var uhrzeit = uhrzeit || '-';
	cms_gesichert_laden('cms_vplan_stundendetails');
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true; alert(1);}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true; alert(kid);}
	if (!datum.match(/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/) && (datum != '-')) {fehler = true; alert(3);}
	if (!uhrzeit.match(/^[0-9]{2}\:[0-9]{2}$/) && (uhrzeit != '-')) {fehler = true; alert(4);}
	if ((uid == '-') && (kid == '-')) {if ((datum == '-') || (uhrzeit == '-')) {fehler = true; alert(5);}}
	var stundendetailsf = document.getElementById('cms_vplan_stundendetails');
	if ((datum != '-') && (uhrzeit != '-') && (!fehler)) {
		datum = datum.split('.');
		var tag = datum[0];
		var monat = datum[1];
		var jahr = datum[2];
		uhrzeit = uhrzeit.split(':');
		var std = uhrzeit[0];
		var min = uhrzeit[1];
		if (!cms_check_ganzzahl(tag, 1,31)) {fehler = true; alert(6);}
		if (!cms_check_ganzzahl(monat, 1,12)) {fehler = true; alert(7);}
		if (!cms_check_ganzzahl(jahr, 0)) {fehler = true; alert(8);}
		if (!cms_check_ganzzahl(std, 0,23)) {fehler = true; alert(9);}
		if (!cms_check_ganzzahl(min, 0,59)) {fehler = true; alert(10);}
	}
	else {
		var tag = "-";
		var monat = "-";
		var jahr = "-";
		var std = "-";
		var min = "-";
	}

	if (fehler) {
		stundendetailsf.innerHTML = "<p class=\"cms_notiz\">Beim Laden der Stundendetails ist ein Fehler aufgetreten.</p>";
	}
	else {
		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("uid", uid);
		formulardaten.append("kid", kid);
		formulardaten.append("tag", tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", jahr);
		formulardaten.append("std", std);
		formulardaten.append("min", min);
		formulardaten.append("anfragenziel", 	'15');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				stundendetailsf.innerHTML = rueckgabe;
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_vplan_stunde_entfall (uid, kid) {
	cms_laden_an('Stunde entfallen lassen', 'Die Eingaben werden überprüft.');
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if ((uid == '-') && (kid == '-')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Stunde entfallen lassen', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Stunde entfallen lassen', 'Die Stunde wird zum Entfall vorgemerkt.');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("uid", 	uid);
		formulardaten.append("kid", 	kid);
		formulardaten.append("anfragenziel", 	'18');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ERFOLG$/)) {
				kid = rueckgabe.substr(0,rueckgabe.length-6);
				cms_stundendetails_laden(uid, kid);
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_vplan_stunde_aenderungenzurueck_anzeigen (uid, kid) {
	cms_meldung_an('warnung', 'Vorgemerkte Änderung wirklich löschen', '<p>Soll die vorgemerkte Änderung für diese Stunde wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_stunde_aenderungenzurueck(\''+uid+'\', \''+kid+'\')">Löschung durchführen</span></p>');
}


function cms_vplan_stunde_aenderungenzurueck (uid, kid) {
	cms_laden_an('Vorgemerkte Änderungen für diese Stunde löschen', 'Die Eingaben werden überprüft.');
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if ((uid == '-') && (kid == '-')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Vorgemerkte Änderungen für diese Stunde löschen', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Vorgemerkte Änderungen für diese Stunde löschen', 'Die vorgemerkten Änderungen für diese Stunde werden gelöscht.');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("kid", 	kid);
		formulardaten.append("anfragenziel", 	'19');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_stundendetails_laden(uid, '-');
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_vplan_stunde_regelstundenplan_anzeigen (uid, kid) {
	cms_meldung_an('warnung', 'Stunde auf Regelstundenplan zurücksetzen', '<p>Soll die Stunde wirklich auf den Regelstundenplan zurück gesetzt werden?</p><p>Vorgemerkte Änderungen bleiben davon unberührt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_stunde_regelstundenplan(\''+uid+'\', \''+kid+'\')">Stunde auf Regelstundenplan zurücksetzen</span></p>');
}


function cms_vplan_stunde_regelstundenplan (uid, kid) {
	cms_laden_an('Stunde auf Regelstundenplan zurücksetzen', 'Die Eingaben werden überprüft.');
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if ((uid == '-') && (kid == '-')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Stunde auf Regelstundenplan zurücksetzen', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Stunde auf Regelstundenplan zurücksetzen', 'Die Stunde wird auf den Regelstundenplan zurückgesetzt.');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("uid", 	uid);
		formulardaten.append("anfragenziel", 	'20');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_stundendetails_laden(uid, kid);
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_vplan_stunde_zusatzstunde (bemerkungszusatz, zwang) {
	var bemerkungszusatz = bemerkungszusatz || '';
	var zwang = zwang || 'n';
	cms_laden_an('Zusatzstunde speichern', 'Die Eingaben werden überprüft.');
	var tag = document.getElementById('cms_stundendetails_datum_T').value;
	var monat = document.getElementById('cms_stundendetails_datum_M').value;
	var jahr = document.getElementById('cms_stundendetails_datum_J').value;
	var kurs = document.getElementById('cms_stundendetails_kurs').value;
	var lehrer = document.getElementById('cms_stundendetails_lehrer').value;
	var raum = document.getElementById('cms_stundendetails_raum').value;
	var stunde = document.getElementById('cms_stundendetails_std').value;
	var bem = document.getElementById('cms_stundendetails_vbem').value;
	var anz = document.getElementById('cms_stundendetails_vanz').value;

	var fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(kurs,0)) {fehler = true;}
	if (!cms_check_ganzzahl(raum,0)) {fehler = true;}
	if (!cms_check_ganzzahl(stunde,0)) {fehler = true;}
	if (!cms_check_toggle(anz)) {fehler = true;}
	if ((zwang != 'j') && (zwang != 'n')) {fehler = true;}

	if ((bem.length > 0) && (bemerkungszusatz.length > 0)) {bem = bemerkungszusatz+" - "+bem;}
	else if (bemerkungszusatz.length > 0) {bem = bemerkungszusatz;}

	if (fehler) {
		cms_meldung_an('fehler', 'Zusatzstunde speichern', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Zusatzstunde speichern', 'Die Zusatzstunde wird zwischengespeichert.');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 			tag);
		formulardaten.append("monat", 		monat);
		formulardaten.append("jahr", 			jahr);
		formulardaten.append("lehrer", 		lehrer);
		formulardaten.append("kurs", 			kurs);
		formulardaten.append("raum", 			raum);
		formulardaten.append("stunde", 		stunde);
		formulardaten.append("bemerkung", bem);
		formulardaten.append("anzeigen", 	anz);
		formulardaten.append("zwang", 		zwang);
		formulardaten.append("anfragenziel", 	'21');

		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ERFOLG$/)) {
				kid = rueckgabe.substr(0,rueckgabe.length-6);
				cms_stundendetails_laden('-', kid);
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else if ((rueckgabe == 'KONFLIKT') || (rueckgabe == 'KONFLIKTL')) {
				cms_vplan_stunde_zusatzstunde_konfliktanzeigen(rueckgabe);
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_zusatzstunde_konfliktanzeigen(art) {
	var zusatzoption = '';
	if (art == 'KONFLIKTL') {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_zusatzstunde(\'Nebenaufsicht\', \'j\')">Nebenaufsicht</span> ';
	}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch diese Zusatzstunde entstehen Konflikte. Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_zusatzstunde(\'\', \'j\')">Trotzdem!</span></p>');
}




function cms_vplan_stunde_aendern (uid, kid, ort, bemerkungszusatz, zwang) {
	var bemerkungszusatz = bemerkungszusatz || '';
	var zwang = zwang || 'n';
	cms_laden_an('Zusatzstunde speichern', 'Die Eingaben werden überprüft.');
	var tag = document.getElementById('cms_stundendetails_datum_T').value;
	var monat = document.getElementById('cms_stundendetails_datum_M').value;
	var jahr = document.getElementById('cms_stundendetails_datum_J').value;
	var kurs = document.getElementById('cms_stundendetails_kurs').value;
	var lehrer = document.getElementById('cms_stundendetails_lehrer').value;
	var raum = document.getElementById('cms_stundendetails_raum').value;
	var stunde = document.getElementById('cms_stundendetails_std').value;
	var bem = document.getElementById('cms_stundendetails_vbem').value;
	var anz = document.getElementById('cms_stundendetails_vanz').value;

	var fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true; alert(1);}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true; alert(2);}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true; alert(3);}
	if (!cms_check_ganzzahl(kurs,0)) {fehler = true; alert(4);}
	if (!cms_check_ganzzahl(raum,0)) {fehler = true; alert(5);}
	if (!cms_check_ganzzahl(stunde,0)) {fehler = true; alert(6);}
	if (!cms_check_toggle(anz)) {fehler = true; alert(7);}
	if ((zwang != 'j') && (zwang != 'n')) {fehler = true; alert(8);}

	if ((bem.length > 0) && (bemerkungszusatz.length > 0)) {bem = bemerkungszusatz+" - "+bem;}
	else if (bemerkungszusatz.length > 0) {bem = bemerkungszusatz;}

	if (fehler) {
		cms_meldung_an('fehler', 'Zusatzstunde speichern', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Zusatzstunde speichern', 'Die Zusatzstunde wird zwischengespeichert.');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("uid", 			uid);
		formulardaten.append("kid", 			kid);
		formulardaten.append("tag", 			tag);
		formulardaten.append("monat", 		monat);
		formulardaten.append("jahr", 			jahr);
		formulardaten.append("lehrer", 		lehrer);
		formulardaten.append("kurs", 			kurs);
		formulardaten.append("raum", 			raum);
		formulardaten.append("stunde", 		stunde);
		formulardaten.append("bemerkung", bem);
		formulardaten.append("anzeigen", 	anz);
		formulardaten.append("zwang", 		zwang);
		formulardaten.append("anfragenziel", 	'22');

		// Stunde ändern
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ERFOLG$/)) {
				kid = rueckgabe.substr(0,rueckgabe.length-6);
				cms_stundendetails_laden(uid, kid);
				cms_vplan_konflikte_liste('a');
				cms_laden_aus();
			}
			else if ((rueckgabe == 'KONFLIKT') || (rueckgabe == 'KONFLIKTL')) {
				cms_vplan_stunde_aendern_konfliktanzeigen(uid, kid, ort, rueckgabe);
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_aendern_konfliktanzeigen(uid, kid, ort, art) {
	var zusatzoption = '';
	if (art == 'KONFLIKTL') {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_aendern(\''+uid+'\', \''+kid+'\', \''+ort+'\', \'Nebenaufsicht\', \'j\')">Nebenaufsicht</span> ';
	}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch diese Zusatzstunde entstehen Konflikte. Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_aendern(\''+uid+'\', \''+kid+'\', \''+ort+'\', \'\', \'j\')">Trotzdem!</span></p>');
}

function cms_vplan_stundendetails_stunden_laden(uid, kid) {
	var uid = uid || '-';
	var kid = kid || '-';
	vplanstdfeld = document.getElementById('cms_stundendetails_std');
	vplanstdfeld.innerHTML = cms_ladeicon();
	var tag = document.getElementById('cms_stundendetails_datum_T').value;
	var monat = document.getElementById('cms_stundendetails_datum_M').value;
	var jahr = document.getElementById('cms_stundendetails_datum_J').value;
	var zusatz = document.getElementById('cms_stundendetails_zusatz').value;
	var fehler = false;

	if (!cms_check_ganzzahl(uid,0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid,0) && (kid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_toggle(zusatz)) {fehler = true;}

	if (fehler) {
		vplanstdfeld.innerHTML = "<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("zusatz", 	zusatz);
		formulardaten.append("anfragenziel", 	'174');

		function anfragennachbehandlung_vplanstunden(rueckgabe) {
			if (rueckgabe.match(/^<option/) || rueckgabe == "") {
				vplanstdfeld.innerHTML = "<select id=\"cms_stundendetails_std\" name=\"cms_stundendetails_std\" onchange=\"cms_vplan_freieressourcen_laden('d')\">"+rueckgabe+"</select>";
				cms_vplan_freieressourcen_laden('d', uid, kid);
			}
			else {"<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung_vplanstunden);
	}
}




function cms_vplan_vormerkungen_uebernehmen() {
  cms_laden_an('Vorgemerkte Änderungen übernehmen', 'Geplante Vertretungen werden gespeichert ...');

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("anfragenziel", 	'23');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_vplan_konflikte_liste('a');
			cms_laden_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vplan_vormerkungen_loeschen_anzeigen() {
	cms_meldung_an('warnung', 'Vorgemerkte Änderungen löschen', '<p>Sollen alle bisher vorgemerkten Änderungen wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_vormerkungen_loeschen()">Vorgemerkte Änderungen löschen</span></p>');
}


function cms_vplan_vormerkungen_loeschen() {
	cms_laden_an('Vorgemerkte Änderungen löschen', 'Alle vorgemerkten Änderung werden gelöscht');
	formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	formulardaten.append("anfragenziel", 	'24');
		// VERTRETUNSTEXTE LADEN
	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_vplan_konflikte_liste('a');
			cms_meldung_an('erfolg', 'Vorgemerkte Änderungen löschen', '<p>Die vorgemerkten Änderungen wurden gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}


function cms_vplan_regelstundenplan_zueuecksetzen_anzeigen() {
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var jetzt = new Date(jahr, monat-1, tag, 0,0,0,0);
	var datum = cms_tagnamekomplett(jetzt.getDay())+", "+jetzt.getDate()+". "+cms_monatsnamekomplett(jetzt.getMonth()+1)+" "+jetzt.getFullYear();
	cms_meldung_an('warnung', 'Tag auf Regelstundenplan zurücksetzen', '<p>Sollen wirklich alle Unterrichtsstunden am <b>'+datum+'</b> auf den Regelstundenplan zurückgesetzt werden, sofern der ursprüngliche Zeitpunkt der Stunden in der Zukunft liegt?</p><p>Dadurch können sich neue Konflikte ergeben! Außerdem werden dadurch getauschte noch ausstehen Stunden wieder auf ihren ursprünglichen Zeitpunkt zurückgesetzt, sofern diese in der Zukunft liegt. Zusatzstunden werden gelöscht.</p><p>Die Vorgemerkten Änderungen bleiben davon unberührt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_regelstundenplan_zueuecksetzen()">Stunden auf Regelstundenplan zurücksetzen</span></p>');
}


function cms_vplan_regelstundenplan_zueuecksetzen() {
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (fehler) {
		cms_meldung_an('fehler', 'Tag auf Regelstundenplan zurücksetzen', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var jetzt = new Date(jahr, monat-1, tag, 0,0,0,0);
		var datum = cms_tagnamekomplett(jetzt.getDay())+", "+jetzt.getDate()+". "+cms_monatsnamekomplett(jetzt.getMonth()+1)+" "+jetzt.getFullYear();
		cms_laden_an('Tag auf Regelstundenplan zurücksetzen', 'Alle Stunden am <b>'+datum+'</b> werden auf den Regelstundenplan zurückgesetzt.');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'17');
			// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == 'ERFOLG') {
				cms_vplan_konflikte_liste('a');
				cms_meldung_an('erfolg', 'Tag auf Regelstundenplan zurücksetzen', '<p>Die Stunden wurden zurückgesetzt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}
