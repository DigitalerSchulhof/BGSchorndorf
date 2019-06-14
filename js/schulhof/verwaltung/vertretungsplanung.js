function cms_vertretungsplanung_suchauswahl() {
	var wahl = document.getElementById('cms_vertretungsplan_ausgang_suche').value;
	var lF = document.getElementById('cms_vertretungsplan_ausgang_lehrer_F');
	var rF = document.getElementById('cms_vertretungsplan_ausgang_raum_F');
	var kF = document.getElementById('cms_vertretungsplan_ausgang_klasse_F');

	if (wahl == 'l') {
		lF.style.display = 'table-row';
		rF.style.display = 'none';
		kF.style.display = 'none';
		cms_vertretungsplan_tag_laden();
	}
	else if (wahl == 'r') {
		lF.style.display = 'none';
		rF.style.display = 'table-row';
		kF.style.display = 'none';
		cms_vertretungsplan_tag_laden();
	}
	else if (wahl == 'k') {
		lF.style.display = 'none';
		rF.style.display = 'none';
		kF.style.display = 'table-row';
		cms_vertretungsplan_tag_laden();
	}
}

function cms_vertretungsplan_tag_laden() {
	cms_laden_an('Vertretungsplan aktualisieren', 'Die Eingaben werden überprüft.');
	cms_vertretungsplan_felder_reset('nein');
	cms_vertretungsplan_vertretungstext_laden('ausgang');
	var tag = document.getElementById('cms_vertretungsplan_ausgang_tag_T').value;
	var monat = document.getElementById('cms_vertretungsplan_ausgang_tag_M').value;
	var jahr = document.getElementById('cms_vertretungsplan_ausgang_tag_J').value;
	var art = document.getElementById('cms_vertretungsplan_ausgang_suche').value;
	var fehler = false;
	var ziel = '-';
	if (art == 'l') {ziel = document.getElementById('cms_vertretungsplan_ausgang_lehrer').value;}
	else if (art == 'k') {ziel = document.getElementById('cms_vertretungsplan_ausgang_klasse').value;}
	else if (art == 'r') {ziel = document.getElementById('cms_vertretungsplan_ausgang_raum').value;}
	else {fehler = true;}

	if (fehler) {
    cms_meldung_an('info', 'Vertretungsplan aktualisieren', '<p>Die eingegebenen Daten sind ungültig</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Vertretungsplanung\');">OK</span></p>');
  }
  else {
    var formulardaten = new FormData();
  	formulardaten.append("tag",     tag);
  	formulardaten.append("monat",   monat);
  	formulardaten.append("jahr",    jahr);
  	formulardaten.append("art",     art);
  	formulardaten.append("ziel",    ziel);
  	formulardaten.append("anfragenziel", 	'168');

  	// Anzahl Elemente suchen
    var anfrage = new XMLHttpRequest();
    anfrage.onreadystatechange = function() {
      if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "FEHLER") {
	      	cms_meldung_fehler();
	      }
	      else if (anfrage.responseText == "BERECHTIGUNG") {
	      	cms_meldung_berechtigung();
	      }
	      else if (anfrage.responseText == "SCHULJAHR") {
	      	cms_meldung_an('info', 'Vertretungsplan aktualisieren', '<p>Das eingegebene Datum liegt in keinem verfügbaren Schuljahr.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Vertretungsplanung\');">OK</span></p>');
	      }
	      else if (anfrage.responseText.match(/<div/)) {
					var tagesverlauf = document.getElementById('cms_vertretungsplan_ausgang_tagesverlauf');
					tagesverlauf.innerHTML = anfrage.responseText;
					tagesverlauf.style.display = 'block';
					cms_meldung_aus();
				}
				else {
					cms_debug(anfrage);
				}
			}
    };
  	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
    anfrage.send(formulardaten);
  }
}

function cms_vertretungsplan_stunde_waehlen(id) {
	cms_laden_an('Vertretungsplan aktualisieren', 'Die Eingaben werden überprüft.');
	var tag = document.getElementById('cms_vertretungsplan_ausgang_tag_T').value;
	var monat = document.getElementById('cms_vertretungsplan_ausgang_tag_M').value;
	var jahr = document.getElementById('cms_vertretungsplan_ausgang_tag_J').value;

  var formulardaten = new FormData();
	formulardaten.append("id",     id);
	formulardaten.append("tag",     tag);
	formulardaten.append("monat",   monat);
	formulardaten.append("jahr",    jahr);
	formulardaten.append("anfragenziel", 	'169');

	// Anzahl Elemente suchen
  var anfrage = new XMLHttpRequest();
  anfrage.onreadystatechange = function() {
    if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "FEHLER") {
      	cms_meldung_fehler();
      }
      else if (anfrage.responseText == "BERECHTIGUNG") {
      	cms_meldung_berechtigung();
      }
      else if (anfrage.responseText == "SCHULJAHR") {
      	cms_meldung_an('info', 'Vertretungsplan aktualisieren', '<p>Das eingegebene Datum liegt in keinem verfügbaren Schuljahr.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Vertretungsplanung\');">OK</span></p>');
      }
      else if (anfrage.responseText.match(/<table/)) {
				document.getElementById('cms_vertretungsplan_ausgang_stunde').innerHTML = anfrage.responseText;
				document.getElementById('cms_vertretungsplan_ausgang_stunde').style.display = 'block';
				cms_vertretungsplan_stunde_entfall();
				cms_meldung_aus();
			}
			else {
				cms_debug(anfrage);
			}
		}
  };
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
  anfrage.send(formulardaten);
}


function cms_vertretungsplan_stunde_entfall() {
	// Objekte ausblenden
	document.getElementById('cms_vertretungsplan_ziel_tag_vtext').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_tagesverlauf').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_verfuegbar').style.display = 'none';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_datum_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_zeit_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_stunden_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_lehrer_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_raum_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_klasse_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_kurs_F').style.display = 'none';
	// Textfelder füllen
	document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = "";
	document.getElementById('cms_vertretungsplan_ziel_vtext').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_vtext').value;
	document.getElementById('cms_vertretungsplan_ziel_entfall').value = '1';
	document.getElementById('cms_vertretungsplan_ziel_aenderung').value = '0';
	document.getElementById('cms_vertretungsplan_ziel_zusatzstunde').value = '0';
	// Anzeigeklassen ändern
	document.getElementById('cms_vertretungsplan_entfall_knopf').className = 'cms_toggle_aktiv';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').className = 'cms_toggle';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').className = 'cms_toggle';
	// Objekte einblenden
	document.getElementById('cms_vertretungsplan_ziel_vtext_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_aktion_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_entfall_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ausgang_tag_vtext').style.display = 'block';
}


function cms_vertretungsplan_stunde_zusatzstunde() {
	// Objekte ausblenden
	document.getElementById('cms_vertretungsplan_ziel_tag_vtext').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_tagesverlauf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_tag_vtext').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_verfuegbar').style.display = 'none';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_stunde').style.display = 'none';
	// Textfelder füllen
	document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = "";
	var heute = new Date();
	document.getElementById('cms_vertretungsplan_ziel_tag_T').value = heute.getDate();
	document.getElementById('cms_vertretungsplan_ziel_tag_M').value = heute.getMonth()+1;
	document.getElementById('cms_vertretungsplan_ziel_tag_J').value = heute.getFullYear();
	cms_datumcheck('cms_vertretungsplan_ziel_tag');
	document.getElementById('cms_vertretungsplan_ziel_beginn_h').value = heute.getHours();
	document.getElementById('cms_vertretungsplan_ziel_beginn_m').value = heute.getMinutes();
	cms_uhrzeitcheck('cms_vertretungsplan_ziel_beginn');
	document.getElementById('cms_vertretungsplan_ziel_ende_h').value = heute.getHours();
	document.getElementById('cms_vertretungsplan_ziel_ende_m').value = heute.getMinutes() + CMS_SCHULSTUNDENDAUER;
	cms_uhrzeitcheck('cms_vertretungsplan_ziel_ende');
	document.getElementById('cms_vertretungsplan_ziel_stunde').value = '-';
	document.getElementById('cms_vertretungsplan_ziel_vtext').value = '';
	document.getElementById('cms_vertretungsplan_ziel_entfall').value = '0';
	document.getElementById('cms_vertretungsplan_ziel_aenderung').value = '0';
	document.getElementById('cms_vertretungsplan_ziel_zusatzstunde').value = '1';
	// DB-Funktionen aufrufen
	cms_vertretungsplan_zielschulstunden_laden();
	cms_vertretungsplan_kurse_laden();
	// Anzeigeklassen ändern
	document.getElementById('cms_vertretungsplan_entfall_knopf').className = 'cms_toggle';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').className = 'cms_toggle';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').className = 'cms_toggle_aktiv';
	// Objekte einblenden
	document.getElementById('cms_vertretungsplan_ziel_datum_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_zeit_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_stunden_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_lehrer_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_raum_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_vtext_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_aktion_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_klasse_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_kurs_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_entfall_knopf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_verfuegbar').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').style.display = 'block';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'block';
}


function cms_vertretungsplan_stunde_aendern() {
	// Objekte ausblenden
	document.getElementById('cms_vertretungsplan_ziel_tag_vtext').style.display = 'none';
	document.getElementById('cms_vertretungsplan_verfuegbar').style.display = 'none';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_klasse_F').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_kurs_F').style.display = 'none';
	// Textfelder füllen
	document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = "";
	document.getElementById('cms_vertretungsplan_ziel_tag_T').value = document.getElementById('cms_vertretungsplan_ausgang_tag_T').value;
	document.getElementById('cms_vertretungsplan_ziel_tag_M').value = document.getElementById('cms_vertretungsplan_ausgang_tag_M').value;
	document.getElementById('cms_vertretungsplan_ziel_tag_J').value = document.getElementById('cms_vertretungsplan_ausgang_tag_J').value;
	cms_datumcheck('cms_vertretungsplan_ziel_tag');
	document.getElementById('cms_vertretungsplan_ziel_beginn_h').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_bs').value;
	document.getElementById('cms_vertretungsplan_ziel_beginn_m').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_bm').value;
	cms_uhrzeitcheck('cms_vertretungsplan_ziel_beginn');
	document.getElementById('cms_vertretungsplan_ziel_ende_h').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_es').value;
	document.getElementById('cms_vertretungsplan_ziel_ende_m').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_em').value;
	cms_uhrzeitcheck('cms_vertretungsplan_ziel_ende');
	document.getElementById('cms_vertretungsplan_ziel_stunde').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_stunde').value;
	document.getElementById('cms_vertretungsplan_ziel_vtext').value = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_vtext').value;
	document.getElementById('cms_vertretungsplan_ziel_entfall').value = '0';
	document.getElementById('cms_vertretungsplan_ziel_aenderung').value = '1';
	document.getElementById('cms_vertretungsplan_ziel_zusatzstunde').value = '0';
	cms_vertretungsplan_lehrer_uebernehmen(document.getElementById('cms_vertretungsplan_stunde_gewaehlt_person_id').value);
	cms_vertretungsplan_raum_uebernehmen(document.getElementById('cms_vertretungsplan_stunde_gewaehlt_raum_id').value);
	// DB-Funktionen aufrufen
	cms_vertretungsplan_zielschulstunden_laden();
	cms_vertretungsplan_verfuegbar_laden();
	// Anzeigeklassen ändern
	document.getElementById('cms_vertretungsplan_entfall_knopf').className = 'cms_toggle';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').className = 'cms_toggle_aktiv';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').className = 'cms_toggle';
	// Objekte einblenden
	document.getElementById('cms_vertretungsplan_ziel_datum_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_zeit_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_stunden_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_lehrer_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_raum_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_vtext_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_ziel_aktion_F').style.display = 'table-row';
	document.getElementById('cms_vertretungsplan_entfall_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').style.display = 'inline-block';
	document.getElementById('cms_vertretungsplan_verfuegbar').style.display = 'block';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ausgang_tagesverlauf').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ausgang_stunde').style.display = 'block';
	document.getElementById('cms_vertretungsplan_ausgang_tag_vtext').style.display = 'block';
}


function cms_vertretungsplan_felder_reset(tagladen) {
	tagladen = tagladen || 'ja';
	// Objekte ausblenden
	document.getElementById('cms_vertretungsplan_ziel_tag_vtext').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_tagesverlauf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_tag_vtext').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_verfuegbar').style.display = 'none';
	document.getElementById('cms_vertretungsplan_zieldetails').style.display = 'none';
	document.getElementById('cms_vertretungsplan_ausgang_stunde').style.display = 'none';
	// Textfelder füllen
	document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = "";
	// Anzeigeklassen ändern
	document.getElementById('cms_vertretungsplan_entfall_knopf').className = 'cms_toggle';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').className = 'cms_toggle';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').className = 'cms_toggle';
	// Objekte einblenden
	document.getElementById('cms_vertretungsplan_entfall_knopf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_aenderung_knopf').style.display = 'none';
	document.getElementById('cms_vertretungsplan_zusatzstunde_knopf').style.display = 'inline-block';

	if (tagladen == 'ja') {cms_vertretungsplan_tag_laden();}
}

function cms_vertretungsplan_zielschulstunden_laden() {
	cms_vertretungsplan_vertretungstext_laden('ziel');
	document.getElementById('cms_vertretungsplan_ziel_stunden_FI').innerHTML = cms_meldung_laden();
	var tag = document.getElementById('cms_vertretungsplan_ziel_tag_T').value;
	var monat = document.getElementById('cms_vertretungsplan_ziel_tag_M').value;
	var jahr = document.getElementById('cms_vertretungsplan_ziel_tag_J').value;
	var formulardaten = new FormData();
  formulardaten.append("tag",     tag);
  formulardaten.append("monat",   monat);
  formulardaten.append("jahr",    jahr);
  formulardaten.append("anfragenziel", 	'170');

	// Anzahl Elemente suchen
  var anfrage = new XMLHttpRequest();
  anfrage.onreadystatechange = function() {
    if (anfrage.readyState==4 && anfrage.status==200) {
			document.getElementById('cms_vertretungsplan_ziel_stunden_FI').innerHTML = anfrage.responseText;
		}
  };
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
  anfrage.send(formulardaten);
}

function cms_vertretungsplan_zeit_uebernehmen(id, bs, bm, es, em) {
	document.getElementById('cms_vertretungsplan_ziel_beginn_h').value = bs;
	document.getElementById('cms_vertretungsplan_ziel_beginn_m').value = bm;
	document.getElementById('cms_vertretungsplan_ziel_ende_h').value = es;
	document.getElementById('cms_vertretungsplan_ziel_ende_m').value = em;
	cms_uhrzeitcheck('cms_vertretungsplan_ziel_beginn');
	cms_uhrzeitcheck('cms_vertretungsplan_ziel_ende');
	document.getElementById('cms_vertretungsplan_ziel_stunde').value = id;
	cms_vertretungsplan_verfuegbar_laden();
}

function cms_vertretungsplan_verfuegbar_laden() {
	cms_vertretungsplan_zieltage_laden();
	document.getElementById('cms_vertretungsplan_verfuegbar').innerHTML = cms_meldung_laden('Verfügbare Lehrkräfte und Räume in diesem Zeitraum werden geladen.');
	var tag = document.getElementById('cms_vertretungsplan_ziel_tag_T').value;
	var monat = document.getElementById('cms_vertretungsplan_ziel_tag_M').value;
	var jahr = document.getElementById('cms_vertretungsplan_ziel_tag_J').value;
	var bs = document.getElementById('cms_vertretungsplan_ziel_beginn_h').value;
	var bm = document.getElementById('cms_vertretungsplan_ziel_beginn_m').value;
	var es = document.getElementById('cms_vertretungsplan_ziel_ende_h').value;
	var em = document.getElementById('cms_vertretungsplan_ziel_ende_m').value;

	var fehler = false;

	var b = new Date(jahr, monat-1, tag, bs, bm, 0, 0);
	var e = new Date(jahr, monat-1, tag, es, em, 0, 0);

	if (b > e) {
		document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = 'Die Stunde endet bevor sie beginnt';
		document.getElementById('cms_vertretungsplan_verfuegbar').innerHTML = "";
	}
	else if (b == e) {
		document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = 'Die Stunde endet und beginnt zum selben Zeitpunkt.';
		document.getElementById('cms_vertretungsplan_verfuegbar').innerHTML = "";
	}
	else {
		document.getElementById('cms_vertretungsplan_ziel_zeit_meldung').innerHTML = "";
		var formulardaten = new FormData();
	  formulardaten.append("tag",     tag);
	  formulardaten.append("monat",   monat);
	  formulardaten.append("jahr",    jahr);
	  formulardaten.append("bs",    	bs);
	  formulardaten.append("bm",    	bm);
	  formulardaten.append("es",    	es);
	  formulardaten.append("em",    	em);
	  formulardaten.append("anfragenziel", 	'171');

		// Anzahl Elemente suchen
	  var anfrage = new XMLHttpRequest();
	  anfrage.onreadystatechange = function() {
	    if (anfrage.readyState==4 && anfrage.status==200) {
				document.getElementById('cms_vertretungsplan_verfuegbar').innerHTML = anfrage.responseText;
			}
	  };
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	  anfrage.send(formulardaten);
	}
}

function cms_vertretungsplan_lehrer_uebernehmen(id) {
	const $lehrerauswahl = $('#cms_vertretungsplan_ziel_lehrer');
	$lehrerauswahl.val(id);
	cms_vertretungsplan_zieltage_laden();
}

function cms_vertretungsplan_raum_uebernehmen(id) {
	const $raumauswahl = $('#cms_vertretungsplan_ziel_raum');
	$raumauswahl.val(id);
	cms_vertretungsplan_zieltage_laden();
}

function cms_vertretungsplan_stunde_raus() {
	document.getElementById('cms_vertretungsplan_ziel_stunde').value = '-';
}

function cms_vertretungsplan_zieltage_laden() {
	document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').innerHTML = cms_meldung_laden('Der Tagesverlauf aller Beteiligten in der neuen Konstellation wird ermittelt.');
	var tag = document.getElementById('cms_vertretungsplan_ziel_tag_T').value;
	var monat = document.getElementById('cms_vertretungsplan_ziel_tag_M').value;
	var jahr = document.getElementById('cms_vertretungsplan_ziel_tag_J').value;
	var bs = document.getElementById('cms_vertretungsplan_ziel_beginn_h').value;
	var bm = document.getElementById('cms_vertretungsplan_ziel_beginn_m').value;
	var es = document.getElementById('cms_vertretungsplan_ziel_ende_h').value;
	var em = document.getElementById('cms_vertretungsplan_ziel_ende_m').value;
	var lehrer = document.getElementById('cms_vertretungsplan_ziel_lehrer').value;
	var raum = document.getElementById('cms_vertretungsplan_ziel_raum').value;
	var kurs = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_kurs_id');
	if (kurs) {
		kurs = kurs.value;
	}
	else {
		kurs = document.getElementById('cms_vertretungsplan_ziel_kurs').value;
	}

	var fehler = false;

	var b = new Date(jahr, monat-1, tag, bs, bm, 0, 0);
	var e = new Date(jahr, monat-1, tag, es, em, 0, 0);

	if (b >= e) {
		document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').innerHTML = "";
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("tag",     tag);
		formulardaten.append("monat",   monat);
		formulardaten.append("jahr",    jahr);
		formulardaten.append("bs",    	bs);
		formulardaten.append("bm",    	bm);
		formulardaten.append("es",    	es);
		formulardaten.append("em",    	em);
		formulardaten.append("lehrer",  lehrer);
		formulardaten.append("raum",    raum);
		formulardaten.append("kurs",    kurs);
		formulardaten.append("anfragenziel", 	'172');

		// Anzahl Elemente suchen
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				document.getElementById('cms_vertretungsplan_ziel_tagesverlauf').innerHTML = anfrage.responseText;
			}
		};
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}


function cms_vertretungsplan_kurse_laden() {
	var klasse = document.getElementById('cms_vertretungsplan_ziel_klasse').value;
	document.getElementById('cms_vertretungsplan_ziel_kurs_Fi').innerHTML = cms_meldung_laden();
	var formulardaten = new FormData();
	formulardaten.append("klasse",    klasse);
	formulardaten.append("anfragenziel", 	'173');

	// Anzahl Elemente suchen
	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			document.getElementById('cms_vertretungsplan_ziel_kurs_Fi').innerHTML = anfrage.responseText;
			cms_vertretungsplan_verfuegbar_laden();
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}

function cms_vertretungsplan_vertretungstext_laden(ziel) {
	var fehler = false;
	if (ziel == 'ausgang') {
		var feld = document.getElementById('cms_vertretungsplan_ausgang_tag_vtext');
		var tag = document.getElementById('cms_vertretungsplan_ausgang_tag_T').value;
		var monat = document.getElementById('cms_vertretungsplan_ausgang_tag_M').value;
		var jahr = document.getElementById('cms_vertretungsplan_ausgang_tag_J').value;
	}
	else if (ziel == 'ziel') {
		var feld = document.getElementById('cms_vertretungsplan_ziel_tag_vtext');
		var tag = document.getElementById('cms_vertretungsplan_ziel_tag_T').value;
		var monat = document.getElementById('cms_vertretungsplan_ziel_tag_M').value;
		var jahr = document.getElementById('cms_vertretungsplan_ziel_tag_J').value;
	}
	else {
		cms_meldung_bastler();
		fehler = true;
	}

	if (!fehler) {
		feld.innerHTML = cms_meldung_laden('Vertretungstexte werden geladen.');
		var formulardaten = new FormData();
		formulardaten.append("tag",    tag);
		formulardaten.append("monat",  monat);
		formulardaten.append("jahr",   jahr);
		formulardaten.append("ziel",   ziel);
		formulardaten.append("anfragenziel", 	'174');

		// Anzahl Elemente suchen
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				feld.innerHTML = anfrage.responseText;
				feld.style.display = 'block';
			}
		};
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}

function cms_vertretungsplan_vertretungstext_loeschen_vorebereiten(id) {
	cms_meldung_an('warnung', 'Vertretungstext löschen', '<p>Soll der ausgewählte Vertretungstext wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_vertretungsplan_vertretungstext_loeschen(\''+id+'\')">Löschen</span></p>');
}

function cms_vertretungsplan_vertretungstext_loeschen(id) {
	cms_laden_an('Vertretungstext löschen', 'Der Vertretungstext wird gelöscht.');
	var formulardaten = new FormData();
	formulardaten.append("id",    id);
	formulardaten.append("anfragenziel", 	'175');

	// Anzahl Elemente suchen
	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "FEHLER") {
	    	cms_meldung_fehler();
      }
      else if  (anfrage.responseText == "BERECHTIGUNG") {
	    	cms_meldung_berechtigung();
      }
      else if (anfrage.responseText == "ERFOLG") {
	    	cms_meldung_an('erfolg', 'Vertretungstext löschen', '<p>Der Vertretungstext wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Vertretungsplanung\');">OK</span></p>');
      }
      else {
	    	cms_debug(anfrage);
      }
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}

function cms_vertretungsplan_vertretungstext_speichern(ziel) {
	cms_laden_an('Vertretungstext speichern', 'Der Vertretungstext wird gespeichert.');
	var fehler = false;
	if ((ziel != 'ausgang') && (ziel != 'ziel')) {cms_meldung_bastler();fehler = true;}

	if (!fehler) {
		var schueler = document.getElementById('cms_vertretungsplan_'+ziel+'_text_schueler').value;
		var lehrer = document.getElementById('cms_vertretungsplan_'+ziel+'_text_lehrer').value;
		var tag = document.getElementById('cms_vertretungsplan_'+ziel+'_tag_T').value;
		var monat = document.getElementById('cms_vertretungsplan_'+ziel+'_tag_M').value;
		var jahr = document.getElementById('cms_vertretungsplan_'+ziel+'_tag_J').value;

		var formulardaten = new FormData();
		formulardaten.append("schueler",  schueler);
		formulardaten.append("lehrer",    lehrer);
		formulardaten.append("tag",    tag);
		formulardaten.append("monat",  monat);
		formulardaten.append("jahr",   jahr);
		formulardaten.append("anfragenziel", 	'176');

		// Anzahl Elemente suchen
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "FEHLER") {
		    	cms_meldung_fehler();
	      }
	      else if  (anfrage.responseText == "BERECHTIGUNG") {
		    	cms_meldung_berechtigung();
	      }
	      else if (anfrage.responseText == "ERFOLG") {
		    	cms_meldung_an('erfolg', 'Vertretungstext speichern', '<p>Der Vertretungstext wurde gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Vertretungsplanung\');">OK</span></p>');
	      }
	      else {
		    	cms_debug(anfrage);
	      }
			}
		};
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}

function cms_vertretungsplan_vertretung_speichern() {
	cms_laden_an('Vertretung speichern', 'Die Eingaben werdengeprüft.');
	var fehler = false;
	// Worum handelt es sich?
	var entfall = document.getElementById('cms_vertretungsplan_ziel_entfall').value;
	var zusatzstunde = document.getElementById('cms_vertretungsplan_ziel_zusatzstunde').value;
	var aenderung = document.getElementById('cms_vertretungsplan_ziel_aenderung').value;

	var fehler = false;
	var meldung = '<p>Die Vertretung konnte nicht gespeicher werden, denn ...</p><ul>'
	var formulardaten = new FormData();


	var vtext = document.getElementById('cms_vertretungsplan_ziel_vtext').value;
	formulardaten.append("vtext", vtext);

	if ((entfall == '1') && (zusatzstunde == '0') && (aenderung == '0')) {
		var id = document.getElementById('cms_vertretungsplan_stunde_gewaehlt').value;
		var schuljahr = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_schuljahr').value;
		formulardaten.append("art", "entfall");
		formulardaten.append("id", id);
		formulardaten.append("schuljahr", schuljahr);
	}
	else if (((entfall == '0') && (zusatzstunde == '1') && (aenderung == '0')) ||
	    ((entfall == '0') && (zusatzstunde == '0') && (aenderung == '1'))) {
		var tag = document.getElementById('cms_vertretungsplan_ziel_tag_T').value;
		var monat = document.getElementById('cms_vertretungsplan_ziel_tag_M').value;
		var jahr = document.getElementById('cms_vertretungsplan_ziel_tag_J').value;
		var bs = document.getElementById('cms_vertretungsplan_ziel_beginn_h').value;
		var bm = document.getElementById('cms_vertretungsplan_ziel_beginn_m').value;
		var es = document.getElementById('cms_vertretungsplan_ziel_ende_h').value;
		var em = document.getElementById('cms_vertretungsplan_ziel_ende_m').value;
		var lehrer = document.getElementById('cms_vertretungsplan_ziel_lehrer').value;
		var raum = document.getElementById('cms_vertretungsplan_ziel_raum').value;
		formulardaten.append("tag", tag);
		formulardaten.append("monat", monat);
		formulardaten.append("jahr", jahr);
		formulardaten.append("bs", bs);
		formulardaten.append("bm", bm);
		formulardaten.append("es", es);
		formulardaten.append("em", em);
		formulardaten.append("lehrer", lehrer);
		formulardaten.append("raum", raum);
		if (zusatzstunde == '1') {
			var kurs = document.getElementById('cms_vertretungsplan_ziel_kurs').value;
			formulardaten.append("art", "zusatzstunde");
			formulardaten.append("kurs", kurs);
		}
		else if (aenderung == '1') {
			var id = document.getElementById('cms_vertretungsplan_stunde_gewaehlt').value;
			var schuljahr = document.getElementById('cms_vertretungsplan_stunde_gewaehlt_schuljahr').value;
			formulardaten.append("art", "aenderung");
			formulardaten.append("id", id);
			formulardaten.append("schuljahr", schuljahr);
		}
	}
	else {
		meldung += '<li>Es wurde keine gültige Aktion ausgewählt.</li>';
		cms_meldung_an('fehler', 'Vertretung speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("anfragenziel", 	'177');

		// Anzahl Elemente suchen
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "FEHLER") {
		    	cms_meldung_fehler();
	      }
	      else if  (anfrage.responseText == "LEHRER") {
		    	cms_meldung_an('fehler', 'Vertretung speichern', '<p>Der gewählte Lehrer existiert nicht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	      }
	      else if  (anfrage.responseText == "RAUM") {
		    	cms_meldung_an('fehler', 'Vertretung speichern', '<p>Der gewählte Raum existiert nicht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	      }
	      else if  (anfrage.responseText == "ZSCHULJAHR") {
		    	cms_meldung_an('fehler', 'Vertretung speichern', '<p>Die Zusatzstunde befindet sich in keinem Schuljahr.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	      }
	      else if  (anfrage.responseText == "ZKURS") {
		    	cms_meldung_an('fehler', 'Vertretung speichern', '<p>Der Kurs befindet sich nicht im selben Schuljahr wie die Zusatzstunde, oder der Kurs existiert nicht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	      }
	      else if  (anfrage.responseText == "BERECHTIGUNG") {
		    	cms_meldung_berechtigung();
	      }
	      else if  (anfrage.responseText == "BASTLER") {
		    	cms_meldung_bastler();
	      }
	      else if (anfrage.responseText == "ERFOLG") {
		    	cms_meldung_an('erfolg', 'Vertretung speichern', '<p>Die Vertretung wurde gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Vertretungsplanung\');">OK</span></p>');
	      }
	      else {
		    	cms_debug(anfrage);
	      }
			}
		};
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}
