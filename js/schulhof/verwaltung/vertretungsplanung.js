function cms_vplan_schulstunden_laden_von() {
	vplanstdfeldvon = document.getElementById('cms_ausplanung_schulstunden_von');
	vplanstdfeldvon.innerHTML = cms_ladeicon();
	var tag = document.getElementById('cms_ausplanung_datum_von_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_von_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_von_J').value;
	document.getElementById('cms_klassen_ausplanen').innerHTML = cms_ladeicon();
	document.getElementById('cms_ausplanung_datum_bis_T').value = tag;
	document.getElementById('cms_ausplanung_datum_bis_M').value = monat;
	document.getElementById('cms_ausplanung_datum_bis_J').value = jahr;
	cms_datumcheck('cms_ausplanung_datum_bis');
	document.getElementById('cms_ausplanung_schulstunden_bis').innerHTML = cms_ladeicon();

	if (!fehler) {
		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	}

	if (fehler) {
		vplanstdfeldvon.innerHTML = "<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("art", 	'von');
		formulardaten.append("anfragenziel", 	'171');

		function anfragennachbehandlung_vplanstunden(rueckgabe) {
			if (rueckgabe.match(/^<option/) || rueckgabe == "") {
				vplanstdfeldvon.innerHTML = "<select id=\"cms_ausplanung_std_von\" name=\"cms_ausplanung_std_von\">"+rueckgabe+"</select>";
				cms_vplan_klassen_laden();
			}
			else {"<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung_vplanstunden);
	}
}


function cms_vplan_schulstunden_laden_bis() {
	vplanstdfeldbis = document.getElementById('cms_ausplanung_schulstunden_bis');
	vplanstdfeldbis.innerHTML = cms_ladeicon();
	var tag = document.getElementById('cms_ausplanung_datum_bis_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_bis_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_bis_J').value;

	if (!fehler) {
		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	}

	if (fehler) {
		vplanstdfeldbis.innerHTML = "<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("art", 	'bis');
		formulardaten.append("anfragenziel", 	'171');

		function anfragennachbehandlung_vplanstunden(rueckgabe) {
			if (rueckgabe.match(/^<option/) || rueckgabe == "") {
				vplanstdfeldbis.innerHTML = "<select id=\"cms_ausplanung_std_bis\" name=\"cms_ausplanung_std_bis\">"+rueckgabe+"</select>";
			}
			else {"<p class=\"cms_notiz\">Fehler beim Laden der Schulstunden.</p>";}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung_vplanstunden);
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
				cms_vplan_schulstunden_laden_bis();
			}
			else {"<p class=\"cms_notiz\">Fehler beim Laden der Klassen.</p>";}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_ausgeplant_laden(aendern) {
	if ((aendern == '+') || (aendern == '-')) {
		var tag = parseInt(document.getElementById('cms_vplankonflikte_datum_T').value);
		var monat = parseInt(document.getElementById('cms_vplankonflikte_datum_M').value);
		var jahr = parseInt(document.getElementById('cms_vplankonflikte_datum_J').value);

		if (aendern == '+') {tag++;}
		if (aendern == '-') {tag--;}

		document.getElementById('cms_vplankonflikte_datum_T').value = tag;
		cms_datumcheck('cms_vplankonflikte_datum');
	}

	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	document.getElementById('cms_ausplanung_datum_von_T').value = tag;
	document.getElementById('cms_ausplanung_datum_von_M').value = monat;
	document.getElementById('cms_ausplanung_datum_von_J').value = jahr;
	cms_datumcheck('cms_ausplanung_datum_von');
	cms_vplan_schulstunden_laden_von();
	cms_datumspeichern(tag, monat, jahr, 'a');
	cms_ausplanen_neuladen();
}

function cms_ausplanen_neuladen(tagu, monatu, jahru) {
	tagu = tagu || false;
	monatu = monatu || false;
	jahru = jahru || false;
	if (tagu && monatu && jahru) {
		document.getElementById('cms_vplankonflikte_datum_T').value = tagu;
		document.getElementById('cms_vplankonflikte_datum_M').value = monatu;
		document.getElementById('cms_vplankonflikte_datum_J').value = jahru;
		cms_datumcheck('cms_vplankonflikte_datum');
	}
	cms_ausplanen_lausgeplant();
	cms_ausplanen_rausgeplant();
	cms_ausplanen_kausgeplant();
	cms_ausplanen_sausgeplant();
}

function cms_datumspeichern(tag, monat, jahr, art) {
	var fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if ((art != 'a') && (art != 'k1') && (art != 'k2') && (art != 'l') &&
	    (art != 'r') && (art != 'ks')) {fehler = true;}

	if (!fehler) {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("art", 	art);
		formulardaten.append("anfragenziel", 	'370');

		cms_ajaxanfrage (false, formulardaten, null);
	}
}

function cms_ausplanen_lausgeplant() {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_l');
	var feldlehreraus = document.getElementById('cms_ausplanung_ausgeplant_l');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
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
				feldlehreraus.innerHTML = rueckgabe;
			}
			else {feldlehreraus.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_ausplanen_rausgeplant() {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_r');
	var feldraumaus = document.getElementById('cms_ausplanung_ausgeplant_r');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
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
				feldraumaus.innerHTML = rueckgabe;
			}
			else {feldraumaus.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_ausplanen_kausgeplant() {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_k');
	var feldklasseaus = document.getElementById('cms_ausplanung_ausgeplant_k');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
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
				feldklasseaus.innerHTML = rueckgabe;
			}
			else {feldklasseaus.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_ausplanen_sausgeplant() {
	cms_gesichert_laden('cms_ausplanung_ausgeplant_s');
	var feldstufeaus = document.getElementById('cms_ausplanung_ausgeplant_s');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
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
		formulardaten.append("anfragenziel", 	'26');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				feldstufeaus.innerHTML = rueckgabe;
			}
			else {feldstufeaus.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_wuensche_neuladen() {
	cms_gesichert_laden('cms_vplan_vertretungswuensche');
	var feldvplanwunsch = document.getElementById('cms_vplan_vertretungswuensche');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		feld.innerHTML = cms_meldung_code('fehler', 'Fehler beim Laden der Vertretungswünsche', '<p>Beim Laden der ausgeplanten Lehrkräfte ist ein Fehler aufgetreten.</p>');
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'399');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				feldvplanwunsch.innerHTML = rueckgabe;
			}
			else {feldvplanwunsch.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplanwunsch_status(id, status) {
	cms_laden_an('Status des Wunsches ändern', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Der Status des Wunsches konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(id, 0)) {
		meldung += '<li>die Wunsch-ID ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(status, 0,1)) {
		meldung += '<li>der neue Status ist ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("id", id);
		formulardaten.append("status", status);
		formulardaten.append("anfragenziel", '410');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_wuensche_neuladen();
				cms_meldung_aus();
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Status des Wunsches ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_vplanwunsch_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Wunsch löschen', '<p>Soll der Wunsch wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplanwunsch_loeschen('+id+')">Löschung durchführen</span></p>');
}

function cms_vplanwunsch_loeschen(id) {
	cms_laden_an('Wunsch löschen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Der Wunsches konnte nicht gelöscht werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(id, 0)) {
		meldung += '<li>die Wunsch-ID ist ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("id", id);
		formulardaten.append("anfragenziel", '411');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_wuensche_neuladen();
				cms_meldung_aus();
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Wunsch löschen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_ausplanung_art_aendern() {
	var art = document.getElementById('cms_ausplanen_art').value;
	var folge = document.getElementById('cms_ausplanen_folge');

	if (art == 'l') {
		cms_ausblenden('cms_ausplanung_art_r');
		cms_ausblenden('cms_ausplanung_grund_r');
		cms_ausblenden('cms_ausplanung_art_k');
		cms_ausblenden('cms_ausplanung_grund_k');
		cms_ausblenden('cms_ausplanung_art_s');
		cms_ausblenden('cms_ausplanung_grund_s');
		cms_einblenden('cms_ausplanung_art_l', 'table-row');
		cms_einblenden('cms_ausplanung_grund_l', 'table-row');
		folge.selectedIndex = "0";
	}
	if (art == 'r') {
		cms_ausblenden('cms_ausplanung_art_l');
		cms_ausblenden('cms_ausplanung_grund_l');
		cms_ausblenden('cms_ausplanung_art_k');
		cms_ausblenden('cms_ausplanung_grund_k');
		cms_ausblenden('cms_ausplanung_art_s');
		cms_ausblenden('cms_ausplanung_grund_s');
		cms_einblenden('cms_ausplanung_art_r', 'table-row');
		cms_einblenden('cms_ausplanung_grund_r', 'table-row');
		folge.selectedIndex = "0";
	}
	if (art == 'k') {
		cms_ausblenden('cms_ausplanung_art_r');
		cms_ausblenden('cms_ausplanung_grund_r');
		cms_ausblenden('cms_ausplanung_art_l');
		cms_ausblenden('cms_ausplanung_grund_l');
		cms_ausblenden('cms_ausplanung_art_s');
		cms_ausblenden('cms_ausplanung_grund_s');
		cms_einblenden('cms_ausplanung_art_k', 'table-row');
		cms_einblenden('cms_ausplanung_grund_k', 'table-row');
		folge.selectedIndex = "2";
	}
	if (art == 's') {
		cms_ausblenden('cms_ausplanung_art_r');
		cms_ausblenden('cms_ausplanung_grund_r');
		cms_ausblenden('cms_ausplanung_art_l');
		cms_ausblenden('cms_ausplanung_grund_l');
		cms_ausblenden('cms_ausplanung_art_k');
		cms_ausblenden('cms_ausplanung_grund_k');
		cms_einblenden('cms_ausplanung_art_s', 'table-row');
		cms_einblenden('cms_ausplanung_grund_s', 'table-row');
		folge.selectedIndex = "2";
	}
}

function cms_ausplanung_speichern() {
	cms_laden_an('Ausplanung speichern', 'Die Eingaben werden überprüft.');
	var art = document.getElementById('cms_ausplanen_art').value;
	var ziel = document.getElementById('cms_ausplanen_'+art).value;
	var grund = document.getElementById('cms_ausplanen_grund'+art).value;
	var zusatz = document.getElementById('cms_ausplanen_zusatz').value;
	var vonS = document.getElementById('cms_ausplanung_std_von').value;
	var vonT = document.getElementById('cms_ausplanung_datum_von_T').value;
	var vonM = document.getElementById('cms_ausplanung_datum_von_M').value;
	var vonJ = document.getElementById('cms_ausplanung_datum_von_J').value;
	var bisS = document.getElementById('cms_ausplanung_std_bis').value;
	var bisT = document.getElementById('cms_ausplanung_datum_bis_T').value;
	var bisM = document.getElementById('cms_ausplanung_datum_bis_M').value;
	var bisJ = document.getElementById('cms_ausplanung_datum_bis_J').value;
	var ort = document.getElementById('cms_ausplanungen_ort').value;
	var folge = document.getElementById('cms_ausplanen_folge').value;

	var meldung = '<p>Die Ausplanung konnte nicht gespeichert werden, denn die Eingaben sind ungültig.</p>';
	var fehler = false;

	if ((ort != 'v') && (ort != 'a')) {fehler = true;}
	if (!cms_check_ganzzahl(vonT,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(vonM,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(vonJ,0)) {fehler = true;}
	if (!cms_check_ganzzahl(bisT,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(bisM,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(bisJ,0)) {fehler = true;}
	if (!cms_check_ganzzahl(vonS,0) || !cms_check_ganzzahl(bisS,0)) {fehler = true;}
	if (!cms_check_ganzzahl(ziel, 0)) {fehler = true;}

	if ((folge != 'k') && (folge != 'e') && (folge != 'u')) {fehler = true;}
	if ((art != 'l') && (art != 'r') && (art != 'k') && (art != 's')) {fehler = true;}
	if ((art == 'l') && (grund != 'dv') && (grund != 'k') && (grund != 'kk') && (grund != 'p') && (grund != 'b') && (grund != 'ex') && (grund != 's')) {fehler = true;}
	else if ((art == 'r') && (grund != 'b') && (grund != 'p') && (grund != 'k') && (grund != 's')) {fehler = true;}
	else if ((art == 'k') && (grund != 'ex') && (grund != 'sh') && (grund != 'p') && (grund != 'bv') && (grund != 'k') && (grund != 's')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Ausplanung speichern', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Ausplanung speichern', 'Die Ausplanung wird gespeichert');

		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("art", art);
		formulardaten.append("ziel", ziel);
    formulardaten.append("grund", grund);
    formulardaten.append("zusatz", zusatz);
    formulardaten.append("folge", folge);
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
				vonT = document.getElementById('cms_vplankonflikte_datum_T').value;
				vonM = document.getElementById('cms_vplankonflikte_datum_M').value;
				vonJ = document.getElementById('cms_vplankonflikte_datum_J').value;
				cms_ausplanen_neuladen(vonT, vonM, vonJ);
				document.getElementById('cms_ausplanung_datum_von_T').value = vonT;
				document.getElementById('cms_ausplanung_datum_von_M').value = vonM;
				document.getElementById('cms_ausplanung_datum_von_J').value = vonJ;
				document.getElementById('cms_ausplanung_datum_bis_T').value = vonT;
				document.getElementById('cms_ausplanung_datum_bis_M').value = vonM;
				document.getElementById('cms_ausplanung_datum_bis_J').value = vonJ;
				document.getElementById("cms_ausplanung_std_von").selectedIndex = "0";
				cms_datumcheck('cms_ausplanung_datum_bis');
				cms_vplan_schulstunden_laden_von();
				cms_vplan_schulstunden_laden_bis();
				if (ort == 'v') {cms_vplan_alles_neuladen();}
				cms_laden_aus();
			}
			else if (rueckgabe == "ZEITRAUM") {
				cms_meldung_an('fehler', 'Ausplanung speichern', '<p>Der ausgewählte Zeitraum ist ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "DOPPELT") {
				cms_meldung_an('fehler', 'Ausplanung speichern', '<p>Die Ausplanung überschneidet sich mit einer bestehenden.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_ausplanung_loeschen_anzeigen (name, id, art, rueck) {
	var zusatzknopf = "";
	if (rueck == 'j') {zusatzknopf = ' <span class="cms_button_nein" onclick="cms_ausplanung_rueckabwicklung(\''+id+'\', \''+art+'\')">Ausplanung rückabwickeln</span>';}
	cms_meldung_an('warnung', 'Ausplanung löschen', '<p>Soll die Ausplanung für »'+name+'« wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_ausplanung_loeschen(\''+id+'\', \''+art+'\')">Ausplanung löschen</span>'+zusatzknopf+'</p>');
}


function cms_ausplanung_loeschen(id, art) {
	cms_laden_an('Ausplanung löschen', 'Die Ausplanung wird gelöscht.');
	var ort = document.getElementById('cms_ausplanungen_ort').value;
	var fehler = false;
	if ((ort != 'v') && (ort != 'a')) {fehler = true;}

	if (fehler) {
		cms_meldung_an('fehler', 'Ausplanung löschen', '<p>Beim Löschen der Ausplanung ist ein Fehler aufgetreten.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
	}
	else {
		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("id",     				id);
		formulardaten.append("art",     			art);
		formulardaten.append("anfragenziel", 	'4');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_ausplanen_neuladen();
				if (ort == 'v') {cms_vplan_alles_neuladen();}
				cms_laden_aus();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

var rueckkonflikte = new Array();
var rueckuids = new Array();
var rueckkids = new Array();


function cms_ausplanung_rueckabwicklung(id, art) {
	cms_laden_an('Ausplanung rückabwickeln', 'Die Ausplanung wird zurückabgewickelt.');

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	formulardaten.append("id",     				id);
	formulardaten.append("art",     			art);
	formulardaten.append("anfragenziel", 	'28');

	rueckkonflikte = new Array();
	rueckuids = new Array();
	rueckkids = new Array();

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.length > 0) {
			var daten = rueckgabe.split("\n\n\n");
			if (daten.length == 3) {
				var neukonflikte = daten[0];
				var neukids = daten[1];
				var neuuids = daten[2];
				if (neukonflikte.length > 0) {
					neukonflikte = neukonflikte.split("\n");
					for (var i = 0; i < neukonflikte.length; i++) {
						neukonflikte[i] = neukonflikte[i].split(";");
						rueckkonflikte.unshift(neukonflikte[i]);
					}
				}
				if (neukids.length > 0) {
					neukids = neukids.split(";");
					rueckkids.unshift(neukids);
				}
				if (neuuids.length > 0) {
					neuuids = neuuids.split(";");
					rueckuids.unshift(neuuids);
				}
				// Konflikte abarbeiten
				cms_ausplanung_rueckabwicklung_naechstestunde();
			}
			else {
				cms_meldung_an('fehler', 'Ausplanung rückabwickeln', '<p>Beim Rückabwickeln der Ausplanung ist ein Fehler aufgetreten.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
		}
		else {
			cms_meldung_an('erfolg', 'Ausplanung rückabwickeln', '<p>Die Rückabwicklung hatte keinen Effekt auf Unterrichtsstunden.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_ausplanung_rueckabwicklung_naechstestunde() {
	if (rueckkonflikte.length > 0) {
		var aktkonflikt = rueckkonflikte.shift();
		var aktstdjetzt = aktkonflikt[2]+': '+aktkonflikt[3]+' bei '+aktkonflikt[4]+' in '+aktkonflikt[5];
		var aktstdvorher = aktkonflikt[6]+': '+aktkonflikt[7]+' bei '+aktkonflikt[8]+' in '+aktkonflikt[9];
		if (aktkonflikt[1] == '') {aktstdart = '<b>Geplante Änderung:</b><br>';}
		else {aktstdart = '<b>Vormerkung:</b><br>';}
		if (aktkonflikt[10] == 'e') {aktstdvorher += ' (Entfall)';}
		if (aktkonflikt[10] == 'z') {aktstdvorher = 'Zusatzstunde';}
		if (aktkonflikt[1] == '') {
			cms_meldung_an('warnung', 'Ausplanung rückabwickeln', '<p>Soll folgende Stunde rückabgewickelt werden?</p><p>'+aktstdart+aktstdjetzt+'<br>&searr;<br>'+aktstdvorher+'</p>', '<p><span class="cms_button_ja" onclick="cms_ausplanung_rueckabwicklung_stunde(\''+aktkonflikt[0]+'\', \''+aktkonflikt[1]+'\', \'n\');">Stunde unsichtbar rückabwickeln</span> <span class="cms_button_ja" onclick="cms_ausplanung_rueckabwicklung_stunde(\''+aktkonflikt[0]+'\', \''+aktkonflikt[1]+'\', \'j\');">Stunde kommentiert rückabwickeln</span> <span class="cms_button_nein" onclick="cms_ausplanung_rueckabwicklung_naechstestunde();">Stunde überspringen</span></p>');
		}
		else {
			cms_meldung_an('warnung', 'Ausplanung rückabwickeln', '<p>Soll folgende Stunde rückabgewickelt werden?</p><p>'+aktstdart+aktstdjetzt+'<br>&searr;<br>'+aktstdvorher+'</p>', '<p><span class="cms_button_ja" onclick="cms_ausplanung_rueckabwicklung_stunde(\''+aktkonflikt[0]+'\', \''+aktkonflikt[1]+'\', \'n\');">Änderung rückabwickeln</span> <span class="cms_button_nein" onclick="cms_ausplanung_rueckabwicklung_naechstestunde();">Stunde überspringen</span></p>');
		}

	}
	else {
		if (document.getElementById('cms_vplanklasse_datum_T')) {cms_vplan_alles_neuladen();}
		cms_meldung_an('erfolg', 'Ausplanung rückabwickeln', '<p>Die Rückabwicklung der Ausplanung ist abgeschlossen.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
	}
}

function cms_ausplanung_rueckabwicklung_stunde(uid, kid, b) {
	cms_laden_an('Ausplanung rückabwickeln', 'Die Stunde wird zurückabgewickelt.');
	if ((cms_check_ganzzahl(uid,0) || uid == '') && (cms_check_ganzzahl(kid,0) || kid == '') && ((b == 'j') || (b == 'n'))) {
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("uid",     			uid);
		formulardaten.append("kid",     			kid);
		formulardaten.append("bem",     			b);
		formulardaten.append("uids",     			rueckuids.join(','));
		formulardaten.append("kids",     			rueckkids.join(','));
		formulardaten.append("anfragenziel", 	'29');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.length > 0) {
				var daten = rueckgabe.split("\n\n\n");
				if (daten.length == 3) {
					var neukonflikte = daten[0];
					var neukids = daten[1];
					var neuuids = daten[2];
					if (neukonflikte.length > 0) {
						neukonflikte = neukonflikte.split("\n");
						for (var i = 0; i < neukonflikte.length; i++) {
							neukonflikte[i] = neukonflikte[i].split(";");
							rueckkonflikte.unshift(neukonflikte[i]);
						}
					}
					if (neukids.length > 0) {
						neukids = neukids.split(";");
						rueckkids.unshift(neukids);
					}
					if (neuuids.length > 0) {
						neuuids = neuuids.split(";");
						rueckuids.unshift(neuuids);
					}
					// Konflikte abarbeiten
					cms_ausplanung_rueckabwicklung_naechstestunde();
				}
				else {
					cms_meldung_an('fehler', 'Ausplanung rückabwickeln', '<p>Beim Rückabwickeln der Ausplanung ist ein Fehler aufgetreten.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
				}
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
	else {
		cms_ausplanung_rueckabwicklung_naechstestunde();
	}
}


function cms_vplantext_laden() {
	cms_gesichert_laden('cms_vplan_vertretungstext');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var vtext = document.getElementById('cms_vplan_vertretungstext');
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		vtext.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Tagesinformationen ist ein Fehler aufgetreten.</p>';
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'172');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h4/)) {
				vtext.innerHTML = rueckgabe;
				$('#cms_vplan_vtext_schueler').summernote({
		      toolbar: [
		        // [groupName, [list of button]]
		        ['textfarbe', ['color']],
		        ['textformat', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'clear']],
		        ['listentabellen', ['ul', 'ol']]
		      ]
		    });
				$('#cms_vplan_vtext_lehrer').summernote({
		      toolbar: [
		        // [groupName, [list of button]]
		        ['textfarbe', ['color']],
		        ['textformat', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'clear']],
		        ['listentabellen', ['ul', 'ol']]
		      ]
		    });
			}
			else {
				vtext.innerHTML = rueckgabe;
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_vplan_vplanvorschau() {
	cms_gesichert_laden('cms_vplan_vplanvorschau');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var vplanvorschau = document.getElementById('cms_vplan_vplanvorschau');
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		vplanvorschau.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Vertretungsplanvorschau ist ein Fehler aufgetreten.</p>';
	}
	else {
		formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'371');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<table/) || rueckgabe.match(/^<p/)) {
				vplanvorschau.innerHTML = rueckgabe;
			}
			else {
				vplanvorschau.innerHTML = rueckgabe;
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_konflikte(aendern) {
	if ((aendern == '+') || (aendern == '-')) {
		var tag = parseInt(document.getElementById('cms_vplankonflikte_datum_T').value);
		var monat = parseInt(document.getElementById('cms_vplankonflikte_datum_M').value);
		var jahr = parseInt(document.getElementById('cms_vplankonflikte_datum_J').value);
		if (aendern == '+') {tag++;}
		if (aendern == '-') {tag--;}
		document.getElementById('cms_vplankonflikte_datum_T').value = tag;
		cms_datumcheck('cms_vplankonflikte_datum');
	}

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
	}

	if (koppeln == 1) {cms_vplan_alles_neuladen();}
	else {cms_vplan_konflikte_neuladen();}
}


function cms_vplan_zweitkonflikte(aendern) {
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
		plan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
	}
	else {
		cms_vplan_konflikte_plan();
	}
}

function cms_vplan_vtexte_speichern(aendern) {
	cms_laden_an('Vertretungstexte speichern', 'Die Eingaben werden überprüft.');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var lehrerbox = document.getElementById('cms_vplan_vtext_lehrer_box');
	var schuelerbox = document.getElementById('cms_vplan_vtext_schueler_box');
	var texte = document.getElementsByClassName('note-editable');
	schueler = texte[0].innerHTML;
	lehrer = texte[1].innerHTML;
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
	if ((koppeln == 1) && ((aendern == '+') || (aendern == '-') || (aendern == 'j'))) {
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
		cms_vplan_alles_neuladen();
	}
	else {cms_vplan_wochenplan_l();}
}

function cms_vplan_detailstunde_wochenplaene_laden() {
	var tag = document.getElementById('cms_stundendetails_datum_T').value;
	var monat = document.getElementById('cms_stundendetails_datum_M').value;
	var jahr = document.getElementById('cms_stundendetails_datum_J').value;

	document.getElementById('cms_vplanlehrer_datum_T').value = tag;
	document.getElementById('cms_vplanlehrer_datum_M').value = monat;
	document.getElementById('cms_vplanlehrer_datum_J').value = jahr;
	cms_datumcheck('cms_vplanlehrer_datum');
	cms_vplan_montagdatum('cms_vplanlehrer_datum');
	document.getElementById('cms_vplanraum_datum_T').value = tag;
	document.getElementById('cms_vplanraum_datum_M').value = monat;
	document.getElementById('cms_vplanraum_datum_J').value = jahr;
	cms_datumcheck('cms_vplanraum_datum');
	cms_vplan_montagdatum('cms_vplanraum_datum');
	document.getElementById('cms_vplanklasse_datum_T').value = tag;
	document.getElementById('cms_vplanklasse_datum_M').value = monat;
	document.getElementById('cms_vplanklasse_datum_J').value = jahr;
	cms_datumcheck('cms_vplanklasse_datum');
	cms_vplan_montagdatum('cms_vplanklasse_datum');
	cms_vplan_wochenplan_k();
	cms_vplan_wochenplan_l();
	cms_vplan_wochenplan_r();
}


function cms_vplan_raum(aendern) {
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
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
	if ((koppeln == 1) && ((aendern == '+') || (aendern == '-') || (aendern == 'j'))) {
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
		cms_vplan_alles_neuladen();
	}
	else {cms_vplan_wochenplan_r();}
}

function cms_vplan_montagdatum(id) {
	var tag = parseInt(document.getElementById(id+'_T').value);
	var monat = parseInt(document.getElementById(id+'_M').value);
	var jahr = parseInt(document.getElementById(id+'_J').value);
	var jetzt = new Date(jahr, monat-1, tag, 0, 0, 0, 0);
	var wochentag = jetzt.getDay();
	if (wochentag == 0) {wochentag = 7;}
	tag = parseInt(tag) - wochentag + 1;
	document.getElementById(id+'_T').value = tag;
	document.getElementById(id+'_M').value = monat;
	document.getElementById(id+'_J').value = jahr;
	cms_datumcheck(id);
}


function cms_vplan_klasse(aendern) {
	var leise = leise || false;
	var koppeln = document.getElementById('cms_vplan_wochenplaene').value;
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
	if ((koppeln == 1) && ((aendern == '+') || (aendern == '-') || (aendern == 'j'))) {
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
		cms_vplan_alles_neuladen();
	}
	else {cms_vplan_wochenplan_k();}
}

function cms_vplan_konflikte_neuladen() {
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	cms_vplan_konflikte_plan();
	cms_vplan_konflikte_liste();
	cms_vplantext_laden();
	cms_vplan_vplanvorschau();
	cms_vplan_ausgeplant_laden();
	cms_ausplanen_neuladen();
	cms_wuensche_neuladen();
}

function cms_vplan_alles_neuladen() {
	cms_vplan_wochenplan_k();
	cms_vplan_wochenplan_l();
	cms_vplan_wochenplan_r();
	cms_vplan_konflikte_neuladen();
}

function cms_vplan_konflikte_liste(sortierung, sortierrichtung) {
	if (document.getElementById('cms_vplankonflikte_sortierrichtung')) {var  sortierrichtungsalternative = document.getElementById('cms_vplankonflikte_sortierrichtung').value;}
	else {var  sortierrichtungsalternative = '0';}
	var sortierrichtung = sortierrichtung || sortierrichtungsalternative;
	if (document.getElementById('cms_vplankonflikte_sortierung')) {var sortierungsalternative = document.getElementById('cms_vplankonflikte_sortierung').value;}
	else {var sortierungsalternative = 's';}
	var sortierung = sortierung || sortierungsalternative;
	cms_gesichert_laden('cms_vplan_konflikte_liste');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var konflikteliste = document.getElementById('cms_vplan_konflikte_liste');
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_toggle(sortierrichtung)) {fehler = true;}

	if (fehler) {
		konflikteliste.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
	}
	else {
		cms_datumspeichern(tag, monat, jahr, 'k1');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("sortierrichtung", 	sortierrichtung);
		formulardaten.append("sortierung", 	sortierung);
		formulardaten.append("anfragenziel", 	'6');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h4/) || rueckgabe.match(/^<p/) || rueckgabe.match(/^<table/)) {
				konflikteliste.innerHTML = rueckgabe;
			}
			else {
				konflikteliste.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
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


function cms_vplan_konflikte_plan() {
	cms_gesichert_laden('cms_vplan_konflikte_plan');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var zweittag = document.getElementById('cms_vplankonflikte_zweitdatum_T').value;
	var zweitmonat = document.getElementById('cms_vplankonflikte_zweitdatum_M').value;
	var zweitjahr = document.getElementById('cms_vplankonflikte_zweitdatum_J').value;
	var planart = document.getElementById('cms_vplankonflikte_plan_art').value;
	var planziel = document.getElementById('cms_vplankonflikte_plan_ziel').value;
	var regelplan = document.getElementById('cms_vplan_konfliktplan_regelplan').value;
	var konflikteplan = document.getElementById('cms_vplan_konflikte_plan');
	fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(zweittag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(zweitmonat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(zweitjahr,0)) {fehler = true;}
	if (!cms_check_toggle(regelplan)) {fehler = true;}

	if (fehler) {
		konflikteplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
	}
	else {
		cms_datumspeichern(tag, monat, jahr, 'k1');
		cms_datumspeichern(zweittag, zweitmonat, zweitjahr, 'k2');
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
		formulardaten.append("regelplan", regelplan);
		formulardaten.append("anfragenziel", 	'7');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<div class/) || rueckgabe.match(/^<p class/)) {
				konflikteplan.innerHTML = rueckgabe;
				cms_vplan_stunde_markieren();
			}
			else {
				konflikteplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_wochenplan_neuladen (art, lehrer, raum, klasse, datum) {
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
				document.getElementById('cms_vplan_woche_lehrer').value = lehrer;
			}
				aktualisieren += 'l';
		}
		else {aktualisieren += 'l';}
		if (raum) {
			if (raum != altraum) {
				document.getElementById('cms_vplan_woche_raum').value = raum;
			}
			aktualisieren += 'r';
		}
		else {aktualisieren += 'r';}
		if (klasse) {
			if (klasse != altklasse) {
				document.getElementById('cms_vplan_woche_klasse').value = klasse;
			}
			aktualisieren += 'k';
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
				cms_vplan_montagdatum('cms_vplanlehrer_datum');
				cms_vplan_montagdatum('cms_vplanraum_datum');
				cms_vplan_montagdatum('cms_vplanklasse_datum');
			}
		}

		// Neues Laden auslösen
		if (aktualisieren.match(/k/)) {
			cms_vplan_wochenplan_k();
		}
		if (aktualisieren.match(/l/)) {
			cms_vplan_wochenplan_l();
		}
		if (aktualisieren.match(/r/)) {
			cms_vplan_wochenplan_r();
		}
	}
}

function cms_vplan_wochenplan_l(details) {
	cms_gesichert_laden('cms_vplan_wochenplan_l');
	var details = details || '-';
	if (details == 'd') {
		var lehrer = document.getElementById('cms_stundendetails_lehrer').value;
		document.getElementById('cms_vplan_woche_lehrer').value = lehrer;
	}
	// Lehrer aktualisieren
	var tag = document.getElementById('cms_vplanlehrer_datum_T').value;
	var monat = document.getElementById('cms_vplanlehrer_datum_M').value;
	var jahr = document.getElementById('cms_vplanlehrer_datum_J').value;
	var lehrer = document.getElementById('cms_vplan_woche_lehrer').value;
	var lehrerwochenplan = document.getElementById('cms_vplan_wochenplan_l');
	var fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(lehrer,0)) {fehler = true;}

	if (fehler) {
		lehrerwochenplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Lehrerplans ist ein Fehler aufgetreten.</p>';
	}
	else {
		cms_datumspeichern(tag, monat, jahr, 'l');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("lehrer",lehrer);
		formulardaten.append("anfragenziel", 	'8');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<div class/) || rueckgabe.match(/^<p class/)) {
				lehrerwochenplan.innerHTML = rueckgabe;
				cms_vplan_stunde_markieren();
			}
			else {
				lehrerwochenplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Lehrerplans ist ein Fehler aufgetreten.</p>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_wochenplan_r(details) {
	cms_gesichert_laden('cms_vplan_wochenplan_r');
	var details = details || '-';
	if (details == 'd') {
		var raum = document.getElementById('cms_stundendetails_raum').value;
		document.getElementById('cms_vplan_woche_raum').value = raum;
	}
	var tag = document.getElementById('cms_vplanraum_datum_T').value;
	var monat = document.getElementById('cms_vplanraum_datum_M').value;
	var jahr = document.getElementById('cms_vplanraum_datum_J').value;
	var raum = document.getElementById('cms_vplan_woche_raum').value;
	var raumwochenplan = document.getElementById('cms_vplan_wochenplan_r');
	var fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(raum,0)) {fehler = true;}

	if (fehler) {
		raumwochenplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Raumplans ist ein Fehler aufgetreten.</p>';
	}
	else {
		cms_datumspeichern(tag, monat, jahr, 'r');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("raum",raum);
		formulardaten.append("anfragenziel", 	'9');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<div class/) || rueckgabe.match(/^<p class/)) {
				raumwochenplan.innerHTML = rueckgabe;
				cms_vplan_stunde_markieren();
			}
			else {
				raumwochenplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Raumplans ist ein Fehler aufgetreten.</p>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_wochenplan_k() {
	cms_gesichert_laden('cms_vplan_wochenplan_k');
	var tag = document.getElementById('cms_vplanklasse_datum_T').value;
	var monat = document.getElementById('cms_vplanklasse_datum_M').value;
	var jahr = document.getElementById('cms_vplanklasse_datum_J').value;
	var klasse = document.getElementById('cms_vplan_woche_klasse').value;
	var klassenwochenplan = document.getElementById('cms_vplan_wochenplan_k');
	var fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (!cms_check_ganzzahl(klasse.substr(1),0)) {fehler = true;}
	if ((klasse.substr(0,1) != 's') && (klasse.substr(0,1) != 'k')) {fehler = true;}

	if (fehler) {
		klassenwochenplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Klassen- und Stufenplans ist ein Fehler aufgetreten.</p>';
	}
	else {
		cms_datumspeichern(tag, monat, jahr, 'ks');
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("klasse", klasse);
		formulardaten.append("anfragenziel", 	'10');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<div class/) || rueckgabe.match(/^<p class/)) {
				klassenwochenplan.innerHTML = rueckgabe;
				cms_vplan_stunde_markieren();

			}
			else {
				klassenwochenplan.innerHTML = '<p class=\"cms_notiz\">Beim Laden des Klassen- und Stufenplans ist ein Fehler aufgetreten.</p>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
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

var text1 = "scheisse";
var text2 = "bullshit";

function cms_vertretungsplan_stunde_verschieben_erlauben(ev) {
  ev.preventDefault();
}

function cms_vertretungsplan_stunde_verschieben_start(ev, std, kurs, lehrer, raum, bez, uid, kid) {
	ev.dataTransfer.setData("X", ev.target.id);
	vplan_start_std = std;
	vplan_start_lehrer = lehrer;
	vplan_start_raum = raum;
	vplan_start_kurs = kurs;
	vplan_start_bez = bez;
	vplan_start_uid = uid;
	vplan_start_kid = kid;
}

function cms_vertretungsplan_stunde_verschieben_ziel(ev, std, kurs, lehrer, raum, bez, uid, kid) {
	ev.preventDefault();
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
		var info = '<b>Verlegen</b> verlegt die Ausgangsstunde in die Zielstunde. Andere Stunden bleiben unberührt.';
		var optionen = '<span class="cms_button" onclick="cms_vplan_stunde_verlegen();">Verlegen</span> ';
		if (cms_check_ganzzahl(vplan_ziel_kurs,0)) {
			info += '<br><b>Überschreiben</b> ändert den Unterricht der Zielstunde auf den der Ausgangsstunde.';
			optionen += '<span class="cms_button" onclick="cms_vplan_stunde_ueberschreiben();">Überschreiben</span> ';
			info += '<br><b>Ersetzen</b> lässt die Ausgangsstunde entfallen, diese findet nun in der Zielstunde statt.';
			optionen += '<span class="cms_button" onclick="cms_vplan_stunde_ersetzen();">Ersetzen</span> ';
			info += '<br><b>Tauschen</b> vertauscht Ziel- mit Ausgangsstunde.';
			optionen += '<span class="cms_button" onclick="cms_vplan_stunde_tauschen();">Tauschen</span> ';
		}
		optionen += '<span class="cms_button_nein" onclick="cms_meldung_aus();">Abbrechen</span>';
		cms_meldung_an('info', 'Stunde verschieben', '<p>'+vplan_start_bez+' <b>'+vplan_start_std+'</b><br>&searr;<br> '+vplan_ziel_bez+' <b>'+vplan_ziel_std+'</b></p><p>Was genau wollen Sie tun?</p>', '<p>'+optionen+'</p><p class=\"cms_notiz\">'+info+'</p>');
	}
}


function cms_vplan_stunde_ueberschreiben(zusatzbem, zwang) {
	var zusatzbem = zusatzbem || '';
	var zwang = zwang || 'n';
	if ((zwang == 'j') || (zwang == 'n')) {
		cms_laden_an('Stunde überschreiben', 'Stunde wird überschrieben ...');
		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	  formulardaten.append("ausgangsstundeu", vplan_start_uid);
	  formulardaten.append("ausgangsstundek", vplan_start_kid);
	  formulardaten.append("zielstundeu", vplan_ziel_uid);
	  formulardaten.append("zielstundek", vplan_ziel_kid);
	  formulardaten.append("zielzeit", 		vplan_ziel_std);
	  formulardaten.append("zwang", 			zwang);
	  formulardaten.append("zusatzbem", 	zusatzbem);
	  formulardaten.append("anfragenziel", 	'11');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_vplan_alles_neuladen();
				cms_laden_aus();
	    }
			else if (rueckgabe.match(/^KONFLIKT/)) {
				cms_vplan_stunde_ueberschreiben_konfliktanzeigen(rueckgabe.substr(8));
			}
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_ueberschreiben_konfliktanzeigen(art) {
	var zusatzoption = '';
	if (art.match(/L/)) {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_ueberschreiben(\'Nebenaufsicht\', \'j\')">Nebenaufsicht</span> ';
	}
	var zusatzmeldung = '';
	if (art.match(/L/)) {zusatzmeldung += ', Lehrer';}
	if (art.match(/R/)) {zusatzmeldung += ', Raum';}
	if (art.match(/K/)) {zusatzmeldung += ', Klasse';}
	if (art.match(/S/)) {zusatzmeldung += ', Stufe';}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch die Kopie der Stunde entstehen Konflikte ('+zusatzmeldung.substr(2)+'). Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_ueberschreiben(\'\', \'j\')">Trotzdem!</span></p>');
}

function cms_vplan_stunde_verlegen(zusatzbem, zwang) {
	var zusatzbem = zusatzbem || '';
	var zwang = zwang || 'n';
	if ((zwang == 'j') || (zwang == 'n')) {
		cms_laden_an('Stunde verlegen', 'Stunde wird verlegt ...');
		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	  formulardaten.append("ausgangsstundeu", vplan_start_uid);
	  formulardaten.append("ausgangsstundek", vplan_start_kid);
	  formulardaten.append("zielzeit", 	vplan_ziel_std);
		formulardaten.append("zwang", 			zwang);
		formulardaten.append("zusatzbem", 	zusatzbem);
	  formulardaten.append("anfragenziel", 	'12');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_vplan_alles_neuladen();
				cms_laden_aus();
	    }
			else if (rueckgabe.match(/^KONFLIKT/)) {
				cms_vplan_stunde_verlegen_konfliktanzeigen(rueckgabe.substr(8));
			}
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_verlegen_konfliktanzeigen(art) {
	var zusatzoption = '';
	if (art.match(/L/)) {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_verlegen(\'Nebenaufsicht\', \'j\')">Nebenaufsicht</span> ';
	}
	var zusatzmeldung = '';
	if (art.match(/L/)) {zusatzmeldung += ', Lehrer';}
	if (art.match(/R/)) {zusatzmeldung += ', Raum';}
	if (art.match(/K/)) {zusatzmeldung += ', Klasse';}
	if (art.match(/S/)) {zusatzmeldung += ', Stufe';}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch die Verlegung der Stunde entstehen Konflikte ('+zusatzmeldung.substr(2)+'). Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_verlegen(\'\', \'j\')">Trotzdem!</span></p>');
}

function cms_vplan_stunde_ersetzen(zusatzbem, zwang) {
	var zusatzbem = zusatzbem || '';
	var zwang = zwang || 'n';
	if ((zwang == 'j') || (zwang == 'n')) {
		cms_laden_an('Stunde ersetzen', 'Stunde wird ersetzt ...');

		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	  formulardaten.append("ausgangsstundeu", vplan_start_uid);
	  formulardaten.append("ausgangsstundek", vplan_start_kid);
	  formulardaten.append("zielstundeu", 	vplan_ziel_uid);
	  formulardaten.append("zielstundek", 	vplan_ziel_kid);
		formulardaten.append("zwang", 			zwang);
		formulardaten.append("zusatzbem", 	zusatzbem);
	  formulardaten.append("anfragenziel", 	'13');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_vplan_alles_neuladen();
				cms_laden_aus();
	    }
			else if (rueckgabe.match(/^KONFLIKT/)) {
				cms_vplan_stunde_ersetzen_konfliktanzeigen(rueckgabe.substr(8));
			}
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_ersetzen_konfliktanzeigen(art) {
	var zusatzoption = '';
	if (art.match(/L/)) {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_ersetzen(\'Nebenaufsicht\', \'j\')">Nebenaufsicht</span> ';
	}
	var zusatzmeldung = '';
	if (art.match(/L/)) {zusatzmeldung += ', Lehrer';}
	if (art.match(/R/)) {zusatzmeldung += ', Raum';}
	if (art.match(/K/)) {zusatzmeldung += ', Klasse';}
	if (art.match(/S/)) {zusatzmeldung += ', Stufe';}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch das Ersetzen der Stunde entstehen Konflikte ('+zusatzmeldung.substr(2)+'). Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_ersetzen(\'\', \'j\')">Trotzdem!</span></p>');
}

function cms_vplan_stunde_tauschen(zwang) {
	var zusatzbem = zusatzbem || '';
	var zwang = zwang || 'n';
	if ((zwang == 'j') || (zwang == 'n')) {
		cms_laden_an('Stunde tauschen', 'Stunde wird getauscht ...');

		var formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	  formulardaten.append("ausgangsstundeu", vplan_start_uid);
	  formulardaten.append("ausgangsstundek", vplan_start_kid);
	  formulardaten.append("zielstundeu", 	vplan_ziel_uid);
	  formulardaten.append("zielstundek", 	vplan_ziel_kid);
		formulardaten.append("zwang", 			zwang);
	  formulardaten.append("anfragenziel", 	'14');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_vplan_alles_neuladen();
				cms_laden_aus();
	    }
			else if (rueckgabe.match(/^KONFLIKT/)) {
				cms_vplan_stunde_tauschen_konfliktanzeigen(rueckgabe.substr(8));
			}
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_tauschen_konfliktanzeigen(art) {
	var zusatzoption = '';
	var zusatzmeldung = '';
	if (art.match(/L/)) {zusatzmeldung += ', Lehrer';}
	if (art.match(/R/)) {zusatzmeldung += ', Raum';}
	if (art.match(/K/)) {zusatzmeldung += ', Klasse';}
	if (art.match(/S/)) {zusatzmeldung += ', Stufe';}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch den Tausch der Stunden entstehen Konflikte ('+zusatzmeldung.substr(2)+'). Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_tauschen(\'j\')">Trotzdem!</span></p>');
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


function cms_vplan_freieressourcen_laden(modus, uid, kid, nr) {
	var nr = nr || '';
	var uid = uid || '-';
	var kid = kid || '-';
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if ((modus != 'k') && (modus != 'l') && (modus != 'd')) {fehler = true;}
	if (((modus == 'k') || (modus == 'l')) && (kid == '-') && (uid == '-')) {fehler = true;}
	if (!fehler) {
		if ((modus == 'k') || (modus == 'l')) {
			var kurse = document.getElementById("cms_kurs"+modus+"_"+uid+"_"+kid+nr);
			var lehrer = document.getElementById("cms_lehrer"+modus+"_"+uid+"_"+kid+nr);
			var raeume = document.getElementById("cms_raum"+modus+"_"+uid+"_"+kid+nr);
			var tag = document.getElementById("cms_vplankonflikte_datum_T").value;
			var monat = document.getElementById("cms_vplankonflikte_datum_M").value;
			var jahr = document.getElementById("cms_vplankonflikte_datum_J").value;
			var std = document.getElementById("cms_std"+modus+"_"+uid+"_"+kid+nr).value;
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
			if (rueckgabe.match(/^<optgroup/)) {
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

function cms_vplan_stunden_demarkieren() {
	document.getElementById('cms_vplan_stunde_markierteklasse').value = "XXX";
	cms_klasse_weg_wennklasse ('cms_vplan_stunde_markiert', 'cms_vplan_stunde_markiert');
}

function cms_vplan_stunde_markieren() {
	var fehler = false;
	if (!fehler) {
		var klasse = document.getElementById('cms_vplan_stunde_markierteklasse').value;
		cms_klasse_dazu_wennklasse (klasse, 'cms_vplan_stunde_markiert');
	}
}

function cms_vplan_stunde_markieren_setzen(klasse) {
	cms_vplan_stunden_demarkieren();
	cms_vplan_stunden_demarkieren();
	document.getElementById('cms_vplan_stunde_markierteklasse').value = klasse;
}


function cms_stundendetails_laden(uid, kid, datum, uhrzeit, anzeigen) {
	var anzeigen = anzeigen || 'j';
	var datum = datum || '-';
	var uhrzeit = uhrzeit || '-';
	cms_gesichert_laden('cms_vplan_stundendetails');
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if (!datum.match(/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}$/) && (datum != '-')) {fehler = true;}
	if (!uhrzeit.match(/^[0-9]{2}\:[0-9]{2}$/) && (uhrzeit != '-')) {fehler = true;}
	if ((uid == '-') && (kid == '-')) {if ((datum == '-') || (uhrzeit == '-')) {fehler = true;}}
	var stundendetailsf = document.getElementById('cms_vplan_stundendetails');
	if ((datum != '-') && (uhrzeit != '-') && (!fehler)) {
		datum = datum.split('.');
		var tag = datum[0];
		var monat = datum[1];
		var jahr = datum[2];
		uhrzeit = uhrzeit.split(':');
		var std = uhrzeit[0];
		var min = uhrzeit[1];
		if (!cms_check_ganzzahl(tag, 1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat, 1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr, 0)) {fehler = true;}
		if (!cms_check_ganzzahl(std, 0,23)) {fehler = true;}
		if (!cms_check_ganzzahl(min, 0,59)) {fehler = true;}
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
				if (anzeigen == 'j') {
					cms_reiter('konflikte',2,5);
				}
				cms_vplan_stunde_markieren();
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}


function cms_vplan_stunde_entfall (uid, kid, sichtbar) {
	var sichtbar = sichtbar || '1';
	cms_laden_an('Stunde entfallen lassen', 'Die Eingaben werden überprüft.');
	var fehler = false;
	if (!cms_check_ganzzahl(uid, 0) && (uid != '-')) {fehler = true;}
	if (!cms_check_ganzzahl(kid, 0) && (kid != '-')) {fehler = true;}
	if (!cms_check_toggle(sichtbar)) {fehler = true;}
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
		formulardaten.append("sichtbar", 	sichtbar);
		formulardaten.append("anfragenziel", 	'18');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ERFOLG$/)) {
				kid = rueckgabe.substr(0,rueckgabe.length-6);
				cms_stundendetails_laden(uid, kid);
				cms_vplan_alles_neuladen();
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

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				if (uid == '-') {
					document.getElementById('cms_vplan_stundendetails').innerHTML = '';
				}
				else {
					cms_stundendetails_laden(uid, '-');
				}
					cms_vplan_alles_neuladen();
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

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_stundendetails_laden(uid, kid);
				cms_vplan_alles_neuladen();
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

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ERFOLG$/)) {
				kid = rueckgabe.substr(0,rueckgabe.length-6);
				cms_stundendetails_laden('-', kid);
				cms_vplan_alles_neuladen();
				cms_laden_aus();
			}
			else if (rueckgabe.match(/^KONFLIKT/)) {
				cms_vplan_stunde_zusatzstunde_konfliktanzeigen(rueckgabe.substr(8));
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_stunde_zusatzstunde_konfliktanzeigen(art) {
	var zusatzoption = '';
	if (art.match(/L/)) {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_zusatzstunde(\'Nebenaufsicht\', \'j\')">Nebenaufsicht</span> ';
	}
	var zusatzmeldung = '';
	if (art.match(/L/)) {zusatzmeldung += ', Lehrer';}
	if (art.match(/R/)) {zusatzmeldung += ', Raum';}
	if (art.match(/K/)) {zusatzmeldung += ', Klasse';}
	if (art.match(/S/)) {zusatzmeldung += ', Stufe';}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch diese Zusatzstunde entstehen Konflikte ('+zusatzmeldung.substr(2)+'). Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_zusatzstunde(\'\', \'j\')">Trotzdem!</span></p>');
}




function cms_vplan_stunde_aendern (uid, kid, ort, bemerkungszusatz, zwang, leise, nr) {
	var nr = nr || '';
	var leise = leise || 'n';
	var bemerkungszusatz = bemerkungszusatz || '';
	var zwang = zwang || 'n';
	cms_laden_an('Stunde ändern', 'Die Eingaben werden überprüft.');
	if ((ort == 'd') || (ort == 'k') || (ort == 'l')) {
		if (ort == 'd') {
			var tag = document.getElementById('cms_stundendetails_datum_T').value;
			var monat = document.getElementById('cms_stundendetails_datum_M').value;
			var jahr = document.getElementById('cms_stundendetails_datum_J').value;
			var kurs = document.getElementById('cms_stundendetails_kurs').value;
			var lehrer = document.getElementById('cms_stundendetails_lehrer').value;
			var raum = document.getElementById('cms_stundendetails_raum').value;
			var stunde = document.getElementById('cms_stundendetails_std').value;
			var bem = document.getElementById('cms_stundendetails_vbem').value;
			var anz = document.getElementById('cms_stundendetails_vanz').value;
		}
		else if ((ort == 'k') || (ort == 'l')) {
			var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
			var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
			var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
			var kurs = document.getElementById('cms_kurs'+ort+'_'+uid+'_'+kid+nr).value;
			var lehrer = document.getElementById('cms_lehrer'+ort+'_'+uid+'_'+kid+nr).value;
			var raum = document.getElementById('cms_raum'+ort+'_'+uid+'_'+kid+nr).value;
			var stunde = document.getElementById('cms_std'+ort+'_'+uid+'_'+kid+nr).value;
			var bem = document.getElementById('cms_vtext'+ort+'_'+uid+'_'+kid+nr).value;
			var anz = document.getElementById('cms_vanz'+ort+'_'+uid+'_'+kid+nr).value;
		}

		var fehler = false;
		if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
		if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
		if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
		if (!cms_check_ganzzahl(kurs,0)) {fehler = true;}
		if (!cms_check_ganzzahl(raum,0)) {fehler = true;}
		if (!cms_check_ganzzahl(stunde,0) && (stunde != '-')) {fehler = true;}
		if (!cms_check_toggle(anz)) {fehler = true;}
		if ((zwang != 'j') && (zwang != 'n')) {fehler = true;}

		if ((bem.length > 0) && (bemerkungszusatz.length > 0)) {bem = bemerkungszusatz+" - "+bem;}
		else if (bemerkungszusatz.length > 0) {bem = bemerkungszusatz;}

		if (fehler) {
			cms_meldung_an('fehler', 'Stunde ändern', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else {
			cms_laden_an('Stunde ändern', 'Die Stundenänderung wird zwischengespeichert.');
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
					if (leise == 'n') {cms_stundendetails_laden(uid, kid);}
					cms_vplan_alles_neuladen();
					cms_laden_aus();
				}
				else if (rueckgabe.match(/^KONFLIKT/)) {
					cms_vplan_stunde_aendern_konfliktanzeigen(uid, kid, ort, rueckgabe.substr(8), leise, nr);
				}
				else {cms_fehlerbehandlung(rueckgabe);}
			}

			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
		}
	}
}

function cms_vplan_stunde_aendern_konfliktanzeigen(uid, kid, ort, art, leise, nr) {
	var nr = nr || '';
	var leise = leise || 'n';
	var zusatzoption = '';
	if (art.match(/L/)) {
		zusatzoption = '<span class="cms_button_wichtig" onclick="cms_vplan_stunde_aendern(\''+uid+'\', \''+kid+'\', \''+ort+'\', \'Nebenaufsicht\', \'j\', \''+leise+'\', \''+nr+'\')">Nebenaufsicht</span> ';
	}
	var zusatzmeldung = '';
	if (art.match(/L/)) {zusatzmeldung += ', Lehrer';}
	if (art.match(/R/)) {zusatzmeldung += ', Raum';}
	if (art.match(/K/)) {zusatzmeldung += ', Klasse';}
	if (art.match(/S/)) {zusatzmeldung += ', Stufe';}
	cms_meldung_an('warnung', 'Es entstehen Konflikte', '<p>Durch diese Stundenänderung entstehen Konflikte ('+zusatzmeldung.substr(2)+'). Wie soll weiter verfahren werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> '+zusatzoption+'<span class="cms_button_wichtig" onclick="cms_vplan_stunde_aendern(\''+uid+'\', \''+kid+'\', \''+ort+'\', \'\', \'j\', \''+leise+'\', \''+nr+'\')">Trotzdem!</span></p>');
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
				cms_vplan_detailstunde_wochenplaene_laden();
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
			cms_vplan_alles_neuladen();
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
	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			document.getElementById('cms_vplan_stundendetails').innerHTML = '';
			cms_vplan_alles_neuladen();
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
				document.getElementById('cms_vplan_stundendetails').innerHTML = '';
				cms_vplan_alles_neuladen();
				cms_meldung_an('erfolg', 'Tag auf Regelstundenplan zurücksetzen', '<p>Die Stunden wurden zurückgesetzt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_drucken() {
  cms_laden_an('Vertretungsplan drucken', 'Die Druckansicht wird vorbereitet.');
	var tag = document.getElementById('cms_vplankonflikte_datum_T').value;
	var monat = document.getElementById('cms_vplankonflikte_datum_M').value;
	var jahr = document.getElementById('cms_vplankonflikte_datum_J').value;
	var fehler = false;
	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}
	if (fehler) {
		cms_meldung_an('fehler', 'Vertretungsplan drucken', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
	  var formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
	  formulardaten.append("anfragenziel", '175');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_laden_aus();
				cms_drucken();
	    }
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_auswahl_aendern(modus, aktion, sichtbar) {
	var sichtbar = sichtbar || '1';
	if (cms_check_toggle(sichtbar) &&
	    ((aktion == 'sichtbarkeit') || (aktion == 'entfall') || (aktion == 'regelstundenplan') || (aktion == 'aenderung')) &&
		  ((modus == 'k') || (modus == 'l'))) {
		cms_laden_an('Stunden mit der Auswahl bearbeiten', 'Die Bearbeitung wird vorbereitet.');
		if (sichtbar == '1') {var sichtbarw = "sichtbar";}
		else if (sichtbar == '1') {var sichtbarw = "unsichtbar";}

		var auswahl = document.getElementById('cms_stunde'+modus+'_gewaehlt').value;

		if (auswahl.length > 0) {
			auswahl = auswahl.substr(1);
		  var stds = auswahl.split(';');
			var stdnr = 0;
			var stdanz = stds.length;
			var fehlerstunden = 0;
      var feld = document.getElementById('cms_blende_i');
      var neuemeldung = '<div class="cms_spalte_i">';
      neuemeldung += '<h2 id="cms_laden_ueberschrift">';
			if (aktion == 'aenderung') {neuemeldung += 'Vorgemerkte Änderungen der ';}
			neuemeldung += 'Stunden mit der Auswahl ';
			if (aktion == 'sichtbarkeit') {neuemeldung += sichtbarw+' machen';}
			if (aktion == 'entfall') {neuemeldung += sichtbarw+' entfallen lassen';}
			if (aktion == 'regelstundenplan') {neuemeldung += 'auf den Regelstundenplan zurücksetzen';}
			if (aktion == 'aenderung') {neuemeldung += 'entfernen';}
			neuemeldung += '</h2>';
      neuemeldung += '<p id="cms_laden_meldung_vorher">Bitte warten ...</p>';
      neuemeldung += '<h4>Gesamtfortschritt</h4>';
      neuemeldung += '<div class="cms_fortschritt_o">';
        neuemeldung += '<div class="cms_fortschritt_i" id="cms_auswahl_gesamt" style="width: 0%;"></div>';
      neuemeldung += '</div>';
      neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Stunden: <span id="cms_auswahl_aktuell">0</span>/'+stdanz+' abgeschlossen</p>';
      neuemeldung += '</div>';
      feld.innerHTML = neuemeldung;

			var std = stds[stdnr].split('|');

      var formulardaten = new FormData();
			cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
			if (aktion != 'aenderung') {formulardaten.append("uid",         std[0]);}
			if (aktion != 'regelstundenplan') {formulardaten.append("kid",  std[1]);}
			if ((aktion == 'entfall') || (aktion == 'sichtbarkeit')) {
				formulardaten.append("sichtbar",  sichtbar);
			}
			if (aktion == 'aenderung') {formulardaten.append("anfragenziel",	'19');}
			if (aktion == 'regelstundenplan') {formulardaten.append("anfragenziel",	'20');}
			if (aktion == 'entfall') {formulardaten.append("anfragenziel",	'18');}
			if (aktion == 'sichtbarkeit') {formulardaten.append("anfragenziel",	'25');}

      function anfragennachbehandlung(rueckgabe) {
        if (!rueckgabe.match(/ERFOLG/)) {
					fehlerstunden++;
				}
        // Abgeschlossene ids erhöhen:
        stdnr++;
        // Anzeige aktualisieren
        document.getElementById('cms_auswahl_aktuell').innerHTML = stdnr;
        document.getElementById('cms_auswahl_gesamt').style.width = (100*stdnr)/stdanz+'%';

        if (stdnr == stdanz) {
					document.getElementById('cms_vplan_stundendetails').innerHTML = '';
					cms_vplan_alles_neuladen();
					var meldungszusatz = ""; var meldungsart = 'erfolg';
					if (fehlerstunden > 0) {meldungszusatz = '<p>Die Anpassung konnt bei '+fehlerstunden+' nicht vorgenommen werden.</p>'; meldungsart = 'info';}
          cms_meldung_an(meldungsart, 'Stunden mit der Auswahl anpassen', '<p>Die Anpassungen sind abgeschlossen.</p>'+meldungszusatz, '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
        }
        else {
					var std = stds[stdnr].split('|');

		      var formulardaten = new FormData();
					cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
					if (aktion != 'aenderung') {formulardaten.append("uid",         std[0]);}
					if (aktion != 'regelstundenplan') {formulardaten.append("kid",  std[1]);}
					if ((aktion == 'entfall') || (aktion == 'sichtbarkeit')) {
						formulardaten.append("sichtbar",  sichtbar);
					}
					if (aktion == 'aenderung') {formulardaten.append("anfragenziel",	'19');}
					if (aktion == 'regelstundenplan') {formulardaten.append("anfragenziel",	'20');}
					if (aktion == 'entfall') {formulardaten.append("anfragenziel",	'18');}
					if (aktion == 'sichtbarkeit') {formulardaten.append("anfragenziel",	'25');}

          cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
        }
      }

      cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
    }
    else {
			cms_meldung_aus();
    }
	}
	else {
		cms_meldung_an('fehler', 'Stunden mit der Auswahl bearbeiten', '<p>Die Eingaben sind ungültig.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_vplan_auswahl_aendern_anzeigen(modus, aktion, sichtbar) {
	if (aktion == 'regelstundenplan') {
		cms_meldung_an('warnung', 'Stunden auf Regelstundenplan zurücksetzen', '<p>Sollen wirklich diese Unterrichtsstunden auf den Regelstundenplan zurückgesetzt werden, sofern der ursprüngliche Zeitpunkt der Stunden in der Zukunft liegt?</p><p>Dadurch können sich neue Konflikte ergeben! Außerdem werden dadurch getauschte noch ausstehen Stunden wieder auf ihren ursprünglichen Zeitpunkt zurückgesetzt, sofern diese in der Zukunft liegt. Zusatzstunden werden gelöscht.</p><p>Die Vorgemerkten Änderungen bleiben davon unberührt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_auswahl_aendern(\''+modus+'\', \''+aktion+'\')">Stunden auf Regelstundenplan zurücksetzen</span></p>');
	}
	else if (aktion == 'aenderung') {
		cms_meldung_an('warnung', 'Vorgemerkte Änderungen löschen', '<p>Sollen die vorgemerkten Änderungen dieser Stunden wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vplan_auswahl_aendern(\''+modus+'\', \''+aktion+'\')">Vorgemerkte Änderungen löschen</span></p>');
	}
}

var spalte_wochenplaene = 60;
var spalte_konflikteplan = 40;
var spalte_lehrerplan = 100/3;
var spalte_raeumeplan = 100/3;
var spalte_klassenplan = 100/3;
var gedrueckte_xpos;

function cms_vplan_standardansicht() {
	spalte_wochenplaene = 60;
	spalte_konflikteplan = 40;
	spalte_lehrerplan = 100/3;
	spalte_raeumeplan = 100/3;
	spalte_klassenplan = 100/3;
	document.getElementById('cms_spalte_wochenplaene').style.width = '';
	document.getElementById('cms_spalte_konflikte').style.width = '';
	document.getElementById('cms_spalte_lehrer').style.width = '';
	document.getElementById('cms_spalte_raeume').style.width = '';
	document.getElementById('cms_spalte_klassen').style.width = '';
}

function cms_groesse_wochenkonf_aendern(ev) {
	ev.preventDefault();
	gedrueckte_xpos = ev.x;
	var spalte_konf = document.getElementById('cms_spalte_konflikte');
	var spalte_woche = document.getElementById('cms_spalte_wochenplaene');
	var breite = spalte_konf.offsetWidth + spalte_woche.offsetWidth;
	spalte_wochenplaene = ((spalte_woche.offsetWidth + cms_abstandvonklick(spalte_woche))*100)/breite;
	if (spalte_wochenplaene < 15) {spalte_wochenplaene = 15;}
	if (spalte_wochenplaene > 95) {spalte_wochenplaene = 95;}
	spalte_konflikteplan = 100 - spalte_wochenplaene;
	spalte_konf.style.width = spalte_konflikteplan+'%';
	spalte_woche.style.width = spalte_wochenplaene+'%';
}

function cms_groesse_klasselehrer_aendern(ev) {
	ev.preventDefault();
	gedrueckte_xpos = ev.x;
	var spalte_klasse = document.getElementById('cms_spalte_klassen');
	var spalte_lehrer = document.getElementById('cms_spalte_lehrer');
	var spalte_raeume = document.getElementById('cms_spalte_raeume');
	var breite = spalte_klasse.offsetWidth + spalte_lehrer.offsetWidth + spalte_raeume.offsetWidth;
	spalte_klassenplan = ((spalte_klasse.offsetWidth + cms_abstandvonklick(spalte_klasse))*100)/breite;
	spalte_raeumeplan = (spalte_raeume.offsetWidth*100)/breite;
	if (spalte_raeumeplan > 90) {spalte_raeumeplan = 90;}
	if (spalte_raeumeplan < 5) {spalte_raeumeplan = 5;}
	if (spalte_klassenplan > 100 - spalte_raeumeplan - 5) {spalte_klassenplan = 100 - spalte_raeumeplan - 5;}
	if (spalte_klassenplan < 5) {spalte_klassenplan = 5;}
	if (spalte_klassenplan > 90) {spalte_klassenplan = 90;}
	spalte_lehrerplan = 100 - spalte_klassenplan - spalte_raeumeplan;
	spalte_klasse.style.width = spalte_klassenplan+'%';
	spalte_lehrer.style.width = spalte_lehrerplan+'%';
	spalte_raeume.style.width = spalte_raeumeplan+'%';
}
function cms_groesse_lehrerraum_aendern(ev) {
	ev.preventDefault();
	gedrueckte_xpos = ev.x;
	var spalte_klasse = document.getElementById('cms_spalte_klassen');
	var spalte_lehrer = document.getElementById('cms_spalte_lehrer');
	var spalte_raeume = document.getElementById('cms_spalte_raeume');
	var breite = spalte_klasse.offsetWidth + spalte_lehrer.offsetWidth + spalte_raeume.offsetWidth;
	spalte_lehrerplan = ((spalte_lehrer.offsetWidth + cms_abstandvonklick(spalte_lehrer))*100)/breite;
	spalte_klassenplan = (spalte_klasse.offsetWidth*100)/breite;
	if (spalte_klassenplan > 90) {spalte_klassenplan = 90;}
	if (spalte_klassenplan < 5) {spalte_klassenplan = 5;}
	if (spalte_lehrerplan > 100 - spalte_klassenplan - 5) {spalte_lehrerplan = 100 - spalte_klassenplan - 5;}
	if (spalte_lehrerplan < 5) {spalte_lehrerplan = 5;}
	if (spalte_lehrerplan > 90) {spalte_lehrerplan = 90;}
	spalte_raeumeplan = 100 - spalte_klassenplan - spalte_lehrerplan;
	spalte_klasse.style.width = spalte_klassenplan+'%';
	spalte_lehrer.style.width = spalte_lehrerplan+'%';
	spalte_raeume.style.width = spalte_raeumeplan+'%';
}

function cms_abstandvonklick(element) {
	var x = 0;
	var breite = element.offsetWidth;
	while (element != null) {
		x += element.offsetLeft;
		element = element.offsetParent;
	}
  return gedrueckte_xpos - (x + breite);
}

function cms_vplan_schnellmenue (art, status, zeile) {
	var zeile = zeile || null;
	if ((art == 'k') || (art == 'l')) {
		var menue = document.getElementById('cms_konflikte_liste_menue_'+art);
		if (menue) {
			if (status == 'j') {
				menue.style.opacity = 1;
				if (zeile) {
					zeile = document.getElementById('cms_vplan_konflikteliste_zeile_'+zeile);
					menue.style.top = zeile.offsetTop+'px';
					menue.style.left = (zeile.offsetLeft+30)+'px';
				}
			}
			else {menue.style.opacity = 0;}
		}
	}
}

function cms_vplanwunsch_einreichen() {
	cms_laden_an('Wunsch für den Vertretungsplan', 'Die Eingaben werden überprüft.');
	var datumT = document.getElementById('cms_vplanwunsch_datum_T').value;
	var datumM = document.getElementById('cms_vplanwunsch_datum_M').value;
	var datumJ = document.getElementById('cms_vplanwunsch_datum_J').value;
	var anliegen = document.getElementById('cms_vplanwunsch_anliegen').value;

	var meldung = '<p>Der Wunsch für den Vertretungsplan wurde nicht übermittelt, denn ...</p><ul>';
	var fehler = false;

	if (anliegen.length == 0) {
		meldung += '<li>es wurde kein Anligen angegeben.</li>';
		fehler = true;
	}

	if ((!cms_check_ganzzahl(datumT, 1, 31)) || (!cms_check_ganzzahl(datumM, 1, 12)) || (!cms_check_ganzzahl(datumJ, 0))) {
		meldung += '<li>das Datum ist ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("datumT", datumT);
		formulardaten.append("datumM", datumM);
		formulardaten.append("datumJ", datumJ);
		formulardaten.append("anliegen", anliegen);
		formulardaten.append("anfragenziel", '398');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Wunsch für den Vertretungsplan', '<p>Der Wunsch für den Vertretungsplan wurde übermittelt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Wunsch für den Vertretungsplan', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}
