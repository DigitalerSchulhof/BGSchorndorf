function cms_neuer_termin(ziel) {
	cms_laden_an('Neuen Termin vorbereiten', 'Vorbereitungen für den neuen Termin werden getroffen.');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'210');
	formulardaten.append("ziel", 	ziel);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Website/Termine/Neuer_Termin');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_termine_felder() {
	var mehrtaegig = document.getElementById('cms_termin_mehrtaegig').value;
	var uhrzeitb = document.getElementById('cms_termin_uhrzeitb').value;
	var uhrzeite = document.getElementById('cms_termin_uhrzeite').value;
	var ort = document.getElementById('cms_termin_ortt').value;
	var wdh = document.getElementById('cms_termin_wiederholung');

	if (uhrzeitb == 1) {cms_einblenden('cms_schulhof_termin_beginn_zeit_zeile', 'table-row');}
	else {cms_ausblenden('cms_schulhof_termin_beginn_zeit_zeile');}

	if (uhrzeite == 1) {cms_einblenden('cms_schulhof_termin_ende_zeit_zeile', 'table-row');}
	else {cms_ausblenden('cms_schulhof_termin_ende_zeit_zeile');}

	if (mehrtaegig == 1) {cms_einblenden('cms_schulhof_termin_ende_datum_zeile', 'table-row');}
	else {cms_ausblenden('cms_schulhof_termin_ende_datum_zeile');}

	if (ort == 1) {cms_einblenden('cms_schulhof_termin_ort_zeile', 'table-row');}
	else {cms_ausblenden('cms_schulhof_termin_ort_zeile');}

	if (wdh) {
		if (wdh.value == 1) {cms_einblenden('cms_schulhof_termin_wdh_zeile', 'table-row');}
		else {cms_ausblenden('cms_schulhof_termin_wdh_zeile');}
	}

	cms_termine_wiederholung_aktualisieren();
}

function cms_termine_wiederholung_aktualisieren() {
	if(document.getElementById('cms_termin_wiederholung')) {
		var wdh = document.getElementById('cms_termin_wiederholung').value;
		var wdhanzahl = document.getElementById('cms_termin_wdh_anzahl').value;
		var wdhturnus = document.getElementById('cms_termin_wdh_art').value;
		var art = document.getElementById('cms_termin_wdh_art');
		var tag = document.getElementById('cms_termin_beginn_datum_T').value;
		var monat = document.getElementById('cms_termin_beginn_datum_M').value;
		var jahr = document.getElementById('cms_termin_beginn_datum_J').value;
		var feld = document.getElementById('cms_termin_wdh_datumbeginn');

		var erstertag = new Date(jahr, monat-1, tag, 0,0,0,0);
		var beginnzeit = "|"+(erstertag.getTime()/1000);
		var naechstertag = "";
		var tage = '<br>'+cms_tagname(erstertag.getDay())+' '+cms_datumzweistellig(erstertag.getDate())+'.'+cms_datumzweistellig(erstertag.getMonth()+1)+'.'+erstertag.getFullYear();

		if (wdh == 1) {
			for (var i = 1; i<wdhanzahl; i++) {
				if (wdhturnus == 't') {naechstertag = new Date(jahr, monat-1, parseInt(tag)+i, 0,0,0,0);}
				else if (wdhturnus == 'w') {naechstertag = new Date(jahr, monat-1, parseInt(tag)+(7*i), 0,0,0,0);}
				else if (wdhturnus == 'm') {naechstertag = new Date(jahr, parseInt(monat)+i-1, tag, 0,0,0,0);}
				else if (wdhturnus == 'n') {
					// Suche den ersten
					var nrwochentag = Math.floor(erstertag.getDate()/7);
					var wochentagjetzt = erstertag.getDay();
					var ersternaechstermonat = new Date(jahr, parseInt(monat)+i-1, 1, 0,0,0,0);
					var wochentagerster = ersternaechstermonat.getDay();
					// Ausgleich bis zum ersten Tag des Monats mit diesem Wochentag
					var ausgleich = 0;
					if (wochentagjetzt > wochentagerster) {ausgleich = wochentagjetzt-wochentagerster;}
					else if (wochentagjetzt < wochentagerster) {ausgleich = 7-(wochentagerster-wochentagjetzt);}
					// Bestimme nächsten x. Wochentag
					naechstertag = new Date(jahr, parseInt(monat)+i-1, 1+ausgleich+7*nrwochentag, 0,0,0,0);
				}
				else if (wdhturnus == 'j') {naechstertag = new Date(parseInt(jahr)+i, monat-1, parseInt(tag)+i, 0,0,0,0);}
				beginnzeit += "|"+(naechstertag.getTime()/1000);
				tage += '<br>'+cms_tagname(naechstertag.getDay())+' '+cms_datumzweistellig(naechstertag.getDate())+'.'+cms_datumzweistellig(naechstertag.getMonth()+1)+'.'+naechstertag.getFullYear();
			}
		}

		document.getElementById('cms_termin_wdh_datumbeginn').value = beginnzeit;
		document.getElementById('cms_termin_wdh_tage').innerHTML = '<b>Terminbeginne:</b>'+tage;
	}
}

function cms_termine_neu_speichern(ziel) {
	cms_laden_an('Neuen Termin anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_termine_eingabenpruefen('neu');

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '211');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Termin anlegen', '<p>Der Termin wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, an dem der Termin beginnt, existiert bereits ein Termin mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Termin anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>der Termin enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Termin anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neuen Termin anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_termine_bearbeiten_speichern(ziel) {
	cms_laden_an('Termin bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_termine_eingabenpruefen('bearbeiten');

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '214');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Termin bearbeiten', '<p>Die Änderungen wurden übernommen</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, an dem der Termin beginnt, existiert bereits ein Termin mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Termin bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>der Termin enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Termin anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Termin bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_termine_bearbeiten_vorbereiten(id, ziel) {
	cms_laden_an('Termin bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
	formulardaten.append("anfragenziel", 	'213');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Termine/Termin_bearbeiten');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_termine_loeschen_vorbereiten(id, bezeichnung, ziel) {
	cms_meldung_an('warnung', 'Termin löschen', '<p>Soll der Termin <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_termine_loeschen(\''+id+'\',\''+ziel+'\')">Löschung durchführen</span></p>');
}


function cms_termine_loeschen(id, ziel) {
	cms_laden_an('Termin löschen', 'Der Termin wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'212');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Termin löschen', '<p>Der Termin wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_termine_eingabenpruefen(modus) {
	var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	var oeffentlichkeit = document.getElementById('cms_oeffentlichkeit').value;
	var genehmigt = document.getElementById('cms_termin_genehmigt').value;
	var aktiv = document.getElementById('cms_termin_aktiv').value;
	var mehrtaegigt = document.getElementById('cms_termin_mehrtaegig').value;
	var uhrzeitbt = document.getElementById('cms_termin_uhrzeitb').value;
	var uhrzeitet = document.getElementById('cms_termin_uhrzeite').value;
	var ortt = document.getElementById('cms_termin_ortt').value;
	var beginnT = document.getElementById('cms_termin_beginn_datum_T').value;
	var beginnM = document.getElementById('cms_termin_beginn_datum_M').value;
	var beginnJ = document.getElementById('cms_termin_beginn_datum_J').value;
	var endeT = document.getElementById('cms_termin_ende_datum_T').value;
	var endeM = document.getElementById('cms_termin_ende_datum_M').value;
	var endeJ = document.getElementById('cms_termin_ende_datum_J').value;
	var beginnh = document.getElementById('cms_termin_beginn_zeit_h').value;
	var beginnm = document.getElementById('cms_termin_beginn_zeit_m').value;
	var endeh = document.getElementById('cms_termin_ende_zeit_h').value;
	var endem = document.getElementById('cms_termin_ende_zeit_m').value;
	var bezeichnung = document.getElementById('cms_termin_bezeichnung').value;
	var downloadanzahl = document.getElementById('cms_downloads_anzahl').value;
	var downloadids = document.getElementById('cms_downloads_ids').value;
	var ort = document.getElementById('cms_termin_ort').value;
	if (modus == 'neu') {
		var wdh = document.getElementById('cms_termin_wdh_datumbeginn').value;
	}
	var formulardaten = new FormData();
	for (var i=0; i<CMS_GRUPPEN.length; i++) {
		var gruppek = cms_textzudb(CMS_GRUPPEN[i]);
		var wert = document.getElementById('cms_zuorndung_zugeordnetegruppen_'+gruppek);
		formulardaten.append('z'+gruppek, wert.value);
	}


	var inhalt = document.getElementsByClassName('note-editable');
	inhalt = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if ((oeffentlichkeit != 0) && (oeffentlichkeit != 1) && (oeffentlichkeit != 2) && (oeffentlichkeit != 3) && (oeffentlichkeit != 4)) {
		meldung += '<li>Die Eingabe für die Öffentlichkeit ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(genehmigt)) {
		meldung += '<li>Die Eingabe für die Genehmigung ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>Die Eingabe für die Aktivität ist ungültig.</li>';
		fehler = true;
	}

	if (mehrtaegigt == 1) {
		beginnd = new Date(beginnJ, beginnM-1, beginnT, 0, 0, 0, 0);
		ended = new Date(endeJ, endeM-1, endeT, 23, 59, 59, 999);
		if (uhrzeitbt == 1) {beginnd = new Date(beginnJ, beginnM-1, beginnT, beginnh, beginnm, 0, 0);}
		if (uhrzeitet == 1) {ended = new Date(endeJ, endeM-1, endeT, endeh, endem, 59, 999);}
	}
	else {
		beginnd = new Date(beginnJ, beginnM-1, beginnT, 0, 0, 0, 0);
		ended = new Date(beginnJ, beginnM-1, beginnT, 23, 59, 59, 999);
		if (uhrzeitbt == 1) {beginnd = new Date(beginnJ, beginnM-1, beginnT, beginnh, beginnm, 0, 0);}
		if (uhrzeitet == 1) {ended = new Date(beginnJ, beginnM-1, beginnT, endeh, endem, 59, 999);}
	}

	if (beginnd-ended >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum eingegeben.</li>';
		fehler = true;
	}

	if (ortt == 1) {
		if (ort.length == 0) {
			meldung += '<li>es wurde kein gültiger Ort eingegeben.</li>';
			fehler = true;
		}
	}

	// Downloads
	var dtitelf = false;
	var dpfadf = false;
	var ddateinamef = false;
	var ddateigroessef = false;
	var dfehler = false;
	if (downloadanzahl > 0) {
		ids = downloadids.split('|');
		for (i=1; i<ids.length; i++) {
			var did = ids[i];

			var dtitel = document.getElementById('cms_download_titel_'+did);
			var dbeschreibung = document.getElementById('cms_download_beschreibung_'+did);
			var dpfad = document.getElementById('cms_download_datei_'+did);
			var ddateiname = document.getElementById('cms_cms_download_dateiname_'+did);
			var ddateigroesse = document.getElementById('cms_cms_download_dateigroesse_'+did);

			if (dtitel) {if ((dtitel.value).length > 0) {formulardaten.append("dtitel_"+did,  dtitel.value);} else {dtitelf = true;}} else {dfehler = true;}
			if (dbeschreibung) {formulardaten.append("dbeschreibung_"+did,  dbeschreibung.value);} else {dfehler = true;}
			if (dpfad) {if ((dpfad.value).length > 0) {formulardaten.append("dpfad_"+did,  dpfad.value);} else {dpfadf = true;}} else {dfehler = true;}
			if (ddateiname) {if ((ddateiname.value == 1) || (ddateiname.value == 0)) {formulardaten.append("ddateiname_"+did,  ddateiname.value);} else {ddateinamef = true;}} else {dfehler = true;}
			if (ddateigroesse) {if ((ddateigroesse.value == 1) || (ddateigroesse.value == 0)) {formulardaten.append("ddateigroesse_"+did,  ddateigroesse.value);} else {ddateigroessef = true;}} else {dfehler = true;}
		}
	}

	if (dfehler) {
		meldung += '<li>bei der Erstellung der Downloads ist ein unbekannter Fehler aufgetreten. Bitte den Administrator über den Link in der Fußzeile informieren.</li>';
		fehler = true;
	}
	if (dtitelf) {
		meldung += '<li>bei mindestens einem Download ist der Titel nicht angegeben.</li>';
		fehler = true;
	}
	if (dpfadf) {
		meldung += '<li>bei mindestens einem Download wurde keine Datei ausgewählt.</li>';
		fehler = true;
	}
	if (ddateinamef) {
		meldung += '<li>bei mindestens einem Download ist die Eingabe für die Anzeige des Dateinamens ungültig.</li>';
		fehler = true;
	}
	if (ddateigroessef) {
		meldung += '<li>bei mindestens einem Download ist die Eingabe für die Anzeige der Dateigröße ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("oeffentlichkeit", oeffentlichkeit);
		formulardaten.append("genehmigt",  			genehmigt);
		formulardaten.append("aktiv",  					aktiv);
		formulardaten.append("mehrtaegigt", 		mehrtaegigt);
		formulardaten.append("uhrzeitbt",  			uhrzeitbt);
		formulardaten.append("uhrzeitet", 			uhrzeitet);
		formulardaten.append("ortt",  					ortt);
		formulardaten.append("beginnT",  				beginnT);
		formulardaten.append("beginnM",					beginnM);
		formulardaten.append("beginnJ",  				beginnJ);
		formulardaten.append("endeT",  					endeT);
		formulardaten.append("endeM",  					endeM);
		formulardaten.append("endeJ",  					endeJ);
		formulardaten.append("beginnh",  				beginnh);
		formulardaten.append("beginnm",  				beginnm);
		formulardaten.append("endeh",  					endeh);
		formulardaten.append("endem",  					endem);
		formulardaten.append("bezeichnung",  		bezeichnung);
		formulardaten.append("ort",  						ort);
		formulardaten.append("inhalt",  				inhalt);
		formulardaten.append("downloadanzahl",  downloadanzahl);
		formulardaten.append("downloadids", 		downloadids);
		if (modus == 'neu') {formulardaten.append("wdh", wdh);}
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}


// TERMINE eines Jahres löschen
function cms_termine_jahr_loeschen_vorbereiten() {
  var jahr = document.getElementById('cms_verwaltung_termine_jahr_angezeigt').value;
	cms_meldung_an('warnung', 'Termine des Jahres '+jahr+' löschen', '<p>Sollen <b>alle</b> Termine des Jahres '+jahr+' wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_termine_jahr_loeschen()">Löschung durchführen</span></p>');
}

function cms_termine_jahr_loeschen() {
  var jahr = document.getElementById('cms_verwaltung_termine_jahr_angezeigt').value;
	cms_laden_an('Termine des Jahres '+jahr+' löschen', 'Alle Termine des Jahres '+jahr+' werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("jahr", 	jahr);
	formulardaten.append("anfragenziel", 	'167');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Termine des Jahres '+jahr+' löschen', '<p>Die Termine wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Termine\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_terminverwaltung(jahr, spalten, anfang, ende) {
  var feld = document.getElementById('cms_verwaltung_termine_jahr');
  feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\"><img src="res/laden/standard.gif"><br>Die Termine für das Jahr '+jahr+' werden geladen. Je nach Verbindung und Terminanzahl kann dies etwas dauern.</td></tr>';

  for (var i=anfang; i<=ende; i++) {
    var toggle = document.getElementById('cms_verwaltung_termine_jahr_'+i);
    toggle.className = 'cms_toggle';
  }

  var fehler = false;

  if ((!Number.isInteger(parseInt(jahr))) || (!Number.isInteger(parseInt(spalten)))) {
    fehler = true;
  }

  if (fehler) {
    feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\">– ungültige Anfrage –</td></tr>';
  }
  else {
    var formulardaten = new FormData();
  	formulardaten.append("jahr",         jahr);
  	formulardaten.append("anfragenziel", 	'166');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.slice(0,4) == '<tr>') {
				var toggle = document.getElementById('cms_verwaltung_termine_jahr_'+jahr);
				toggle.className = 'cms_toggle_aktiv';
				document.getElementById('cms_verwaltung_termine_jahr_angezeigt').value = jahr;
				feld.innerHTML = rueckgabe;
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}


function cms_termin_genehmigen(gruppe, id, ziel) {
	cms_laden_an('Termin genehmigen', 'Der Termin wird genehmigt.');
	var ziel = ziel || 'Schulhof/Aufgaben/Termine_genehmigen';
	var formulardaten = new FormData();
	formulardaten.append("gruppe",         gruppe);
	formulardaten.append("id",         		 id);
	formulardaten.append("anfragenziel", 	'215');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Termin genehmigen', '<p>Der Termin wurde genehmigt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_termin_ablehnen(gruppe, id, ziel) {
	cms_laden_an('Termin ablehnen', 'Der Termin wird abgelehnt.');
	var ziel = ziel || 'Schulhof/Aufgaben/Termine_genehmigen';
	var formulardaten = new FormData();
	formulardaten.append("gruppe",         gruppe);
	formulardaten.append("id",         		 id);
	formulardaten.append("anfragenziel", 	'216');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Termin ablehnen', '<p>Der Termin wurde abgelehnt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
