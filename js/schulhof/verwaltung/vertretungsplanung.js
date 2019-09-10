function cms_vplan_klassen_laden(art) {
	fehler = false;
	if (art == 'ausplanung') {
		cms_gesichert_laden('cms_ausplanung_ausgeplant_l');
		cms_gesichert_laden('cms_ausplanung_ausgeplant_r');
		cms_gesichert_laden('cms_ausplanung_ausgeplant_k');
		feld = document.getElementById('cms_klassen_ausplanen');
		feld.innerHTML = cms_ladeicon();
		var tag = document.getElementById('cms_ausplanung_datum_T').value;
		var monat = document.getElementById('cms_ausplanung_datum_M').value;
		var jahr = document.getElementById('cms_ausplanung_datum_J').value;
	}
	else if (art == 'vplanung') {
		cms_gesichert_laden('cms_vplan_konflikte');
		feld = document.getElementById('cms_vplan_klassen');
		feld.innerHTML = cms_ladeicon();
		var tag = document.getElementById('cms_vplan_datum_T').value;
		var monat = document.getElementById('cms_vplan_datum_M').value;
		var jahr = document.getElementById('cms_vplan_datum_J').value;
	}
	else {fehler = true;}

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
				if (art == 'ausplanung') {
					feld.innerHTML = "<select id=\"cms_ausplanen_k\" name=\"cms_ausplanen_k\">"+rueckgabe+"</select>";
					cms_ausplanen_lausgeplant();
				}
				else if (art == 'vplanung') {
					feld.innerHTML = "<select id=\"cms_vplan_k\" name=\"cms_vplan_k\">"+rueckgabe+"</select>";
					cms_vplan_konflikte();
				}
			}
			else {"<p class=\"cms_notiz\">Fehler beim Laden der Klassen.</p>";}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_ausplanen_lausgeplant() {
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
		cms_einblenden('cms_ausplanung_grundt_l', 'table-row');
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
	var vonS = document.getElementById('cms_ausplanung_von_h').value;
	var vonM = document.getElementById('cms_ausplanung_von_m').value;
	var bisS = document.getElementById('cms_ausplanung_bis_h').value;
	var bisM = document.getElementById('cms_ausplanung_bis_m').value;
	var tag = document.getElementById('cms_ausplanung_datum_T').value;
	var monat = document.getElementById('cms_ausplanung_datum_M').value;
	var jahr = document.getElementById('cms_ausplanung_datum_J').value;

	var meldung = '<p>Die Ausplanung konnte nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if ((art != 'l') && (art != 'r') && (art != 'k')) {
		meldung += '<li>es wurde eine falsche Ausplanungsart angegeben.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(ziel, 0)) {
		meldung += '<li>das Zielobjekt (Lehrkraft/Raum/Klasse) ist ungültig.</li>';
		fehler = true;
	}
	if ((art == 'l') && (grund != 'dv') && (grund != 'k') && (grund != 'b') && (grund != 's')) {
		meldung += '<li>der Grund ist ungültig.</li>';
		fehler = true;
	}
	else if ((art == 'r') && (grund != 'b') && (grund != 'k') && (grund != 's')) {
		meldung += '<li>der Grund ist ungültig.</li>';
		fehler = true;
	}
	else if ((art == 'k') && (grund != 'ex') && (grund != 'sh') && (grund != 'k') && (grund != 's')) {
		meldung += '<li>der Grund ist ungültig.</li>';
		fehler = true;
	}

	var von = new Date(0, 0, 0, vonS, vonM, 0, 0).getTime();
	var bis = new Date(0, 0, 0, bisS, bisM, 0, 0).getTime();

	if (bis <= von) {
		meldung += '<li>der Ausplanungszeitraum ist ungültig.</li>';
		fehler = true;
	}

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
    formulardaten.append("vonM", vonM);
    formulardaten.append("bisS", bisS);
    formulardaten.append("bisM", bisM);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'3');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_link('Schulhof/Verwaltung/Planung/Ausplanungen');
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
			cms_meldung_an('erfolg', 'Ausplanung löschen', '<p>Die Ausplanung wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Ausplanungen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_vertretungsplanung_vorbereiten() {
  cms_laden_an('Vertretungsplan vorbereiten', 'Der Vertretungsplan wird vorbereitet ...');
	var formulardaten = new FormData();
  formulardaten.append("anfragenziel", 	'296');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Vertretungsplanung');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_vertretungsplanung_tagaendern() {
  cms_laden_an('Vertretungsplan vorbereiten', 'Der Vertretungsplan wird vorbereitet ...');
	var tag = document.getElementById('cms_vplan_datum_T').value;
	var monat = document.getElementById('cms_vplan_datum_M').value;
	var jahr = document.getElementById('cms_vplan_datum_J').value;

	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		feld.innerHTML = cms_meldung_code('fehler', 'Fehler beim Laden der Konflikte', '<p>Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
	  formulardaten.append("anfragenziel", 	'297');

	  function anfragennachbehandlung(rueckgabe) {
	    if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Vertretungsplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vplan_konflikte() {
	cms_gesichert_laden('cms_vplan_konflikte');
	feld = document.getElementById('cms_vplan_konflikte');
	var tag = document.getElementById('cms_vplan_datum_T').value;
	var monat = document.getElementById('cms_vplan_datum_M').value;
	var jahr = document.getElementById('cms_vplan_datum_J').value;
	fehler = false;

	if (!cms_check_ganzzahl(tag,1,31)) {fehler = true;}
	if (!cms_check_ganzzahl(monat,1,12)) {fehler = true;}
	if (!cms_check_ganzzahl(jahr,0)) {fehler = true;}

	if (fehler) {
		feld.innerHTML = cms_meldung_code('fehler', 'Fehler beim Laden der Konflikte', '<p>Beim Laden der Konflikte ist ein Fehler aufgetreten.</p>');
	}
	else {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("tag", 	tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", 	jahr);
		formulardaten.append("anfragenziel", 	'5');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h2/)) {
				feld.innerHTML = rueckgabe;
			}
			else {feld.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}

function cms_vplan_vollbild(vollbild) {
  cms_laden_an('Vertretungsplanung aktualisieren', 'Der Vertretungsplanung wird aktualisiert ...');

	if (!cms_check_toggle(vollbild)) {
		cms_meldung_an('fehler', 'Vertretungsplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('vollbild', vollbild);
	  formulardaten.append("anfragenziel", 	'298');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Vertretungsplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
