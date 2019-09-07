function cms_stundenplanzeitraeume_vorbereiten(id) {
  cms_laden_an('Stundenplanzeiträume vorbereiten', 'Die Zeiträume des Schuljahres werden vorbereitet ...');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", 	'325');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Zeiträume');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_zeitraeume_eingabencheck() {
	var bezeichnung = document.getElementById('cms_zeitraeume_bezeichnung').value;
  var beginnT = document.getElementById('cms_zeitraeume_beginn_T').value;
  var beginnM = document.getElementById('cms_zeitraeume_beginn_M').value;
  var beginnJ = document.getElementById('cms_zeitraeume_beginn_J').value;
  var endeT = document.getElementById('cms_zeitraeume_ende_T').value;
  var endeM = document.getElementById('cms_zeitraeume_ende_M').value;
  var endeJ = document.getElementById('cms_zeitraeume_ende_J').value;
  var mon = document.getElementById('cms_cms_zeitraeume_mo').value;
  var die = document.getElementById('cms_cms_zeitraeume_di').value;
  var mit = document.getElementById('cms_cms_zeitraeume_mi').value;
  var don = document.getElementById('cms_cms_zeitraeume_do').value;
  var fre = document.getElementById('cms_cms_zeitraeume_fr').value;
  var sam = document.getElementById('cms_cms_zeitraeume_sa').value;
  var son = document.getElementById('cms_cms_zeitraeume_so').value;
  var rythmen = document.getElementById('cms_zeitraeume_rythmen').value;
  var aktiv = document.getElementById('cms_zeitraeume_aktiv').value;
	var schulstundenanzahl = document.getElementById('cms_zeitraeume_schulstunden_anzahl').value;
	var schulstundenids = document.getElementById('cms_zeitraeume_schulstunden_ids').value;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;
  var datumfehler = false;

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Die Bezeichnung des Zeitraums enthält ungültige Zeichen.</li>';
		fehler = true;
	}

  if (!cms_check_ganzzahl(beginnT, 1, 31) || !cms_check_ganzzahl(beginnM, 1, 12) || !cms_check_ganzzahl(beginnJ, 0)) {
		fehler = true;
    datumfehler = true;
		meldung += '<li>Das eingegebene Beginn-Datum ist ungültig.</li>';
	}
  if (!cms_check_ganzzahl(endeT, 1, 31) || !cms_check_ganzzahl(endeM, 1, 12) || !cms_check_ganzzahl(endeJ, 0)) {
		fehler = true;
    datumfehler = true;
		meldung += '<li>Das eingegebene End-Datum ist ungültig.</li>';
	}

	if (!datumfehler) {
		var beginn = new Date(beginnJ, beginnM-1, beginnT, 0,0,0,0);
		var ende = new Date(endeJ, endeM-1, endeT, 23,59,59,999);
		if (ende <= beginn) {
			fehler = true;
			meldung += '<li>Das End-Datum darf nicht vor dem Beginn-Datum liegen.</li>';
		}
	}

	if (!cms_check_toggle(mon) || !cms_check_toggle(die) || !cms_check_toggle(mit) || !cms_check_toggle(don) || !cms_check_toggle(fre) || !cms_check_toggle(sam) || !cms_check_toggle(son)) {
		meldung += '<li>Die Eingabe der Schultage ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(rythmen, 1,26)) {
		meldung += '<li>Die Eingabe der Rythmen ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>Die Eingabe der Aktivität ist ungültig.</li>';
		fehler = true;
	}

	// Prüfen, ob Blockierungen dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var sbezeichnungf = false;
	var szeitf = false;
  var sfehler = false;
	if (schulstundenanzahl > 0) {
		ids = schulstundenids.split('|');
		for (i=1; i<ids.length; i++) {
			var sid = ids[i];

			var sbezeichnung = document.getElementById('cms_zeitraeume_schulstunden_bezeichnung_'+sid);
			var sidid = document.getElementById('cms_zeitraeume_schulstunden_id_'+sid);
			var sbeginns = document.getElementById('cms_zeitraeume_schulstunden_beginn_'+sid+'_h');
			var sbeginnm = document.getElementById('cms_zeitraeume_schulstunden_beginn_'+sid+'_m');
			var sendes = document.getElementById('cms_zeitraeume_schulstunden_ende_'+sid+'_h');
			var sendem = document.getElementById('cms_zeitraeume_schulstunden_ende_'+sid+'_m');

			if (sbezeichnung) {
				if (cms_check_titel(sbezeichnung.value)) {formulardaten.append("sbezeichnung_"+sid,  sbezeichnung.value);}
				else {sbezeichnungf = true;}
			}
			else {sfehler = true;}

			if ((sbeginns) && (sbeginnm) && (sendes) && (sendem)) {
				if (cms_check_ganzzahl(sbeginns.value, 0,23) && cms_check_ganzzahl(sbeginnm.value, 0,59) && cms_check_ganzzahl(sendes.value, 0,23) && cms_check_ganzzahl(sendem.value, 0,59)) {
					if ((sendes.value > sbeginns.value) || ((sendes.value == sbeginns.value) && (sendem.value > sbeginnm.value))) {
						formulardaten.append("sbeginns_"+sid,  sbeginns.value);
						formulardaten.append("sbeginnm_"+sid,  sbeginnm.value);
						formulardaten.append("sendes_"+sid,  sendes.value);
						formulardaten.append("sendem_"+sid,  sendem.value);
					}
					else {szeitf = true;}
				}
				else {szeitf = true;}
			}
			else {sfehler = true;}

			if (sidid) {
				if ((sidid.value).length > 0) {formulardaten.append("sid_"+sid,  sidid.value);}
				else {sfehler = true;}
			}
			else {sfehler = true;}
		}
	}

	if (sfehler) {
		meldung += '<li>bei den Schulstunden ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (sbezeichnungf) {
		meldung += '<li>bei mindestens einer Schulstunde enthält die Bezeichnung ungültige Zeichen.</li>';
		fehler = true;
	}
	if (szeitf) {
		meldung += '<li>bei mindestens einer Schulstunde sind die Zeiträume ungültig.</li>';
		fehler = true;
	}

	formulardaten.append("bezeichnung", bezeichnung);
	formulardaten.append("beginnT", 	beginnT);
	formulardaten.append("beginnM", 	beginnM);
	formulardaten.append("beginnJ",   beginnJ);
	formulardaten.append("endeT", 	endeT);
	formulardaten.append("endeM", 	endeM);
	formulardaten.append("endeJ",   endeJ);
	formulardaten.append("mo", mon);
	formulardaten.append("di", die);
	formulardaten.append("mi", mit);
	formulardaten.append("do", don);
	formulardaten.append("fr", fre);
	formulardaten.append("sa", sam);
	formulardaten.append("so", son);
	formulardaten.append("rythmen", rythmen);
	formulardaten.append("aktiv", aktiv);
	formulardaten.append("schulstundenanzahl", schulstundenanzahl);
	formulardaten.append("schulstundenids", schulstundenids);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_zeitraeume_neu_speichern() {
	cms_laden_an('Neuen Zeitraum anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_zeitraeume_bezeichnung').value;
	var meldung = '<p>Der Zeitraum konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_zeitraeume_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neuen Zeitraum anlegen', 'Der neue Zeitraum wird angelegt');

		formulardaten.append("anfragenziel", 	'326');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/ZEIT/)) {
				meldung += '<li>dieser Zeitraum liegt ganz oder teilweise außerhalb des gewählten Schuljahres.</li>';
				cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				meldung += '<li>dieser Zeitraum überschneidet sich mit anderen Zeiträumen in diesem Schuljahr.</li>';
				cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/STUNDEN/)) {
				meldung += '<li>Schulstunden dieses Zeitraumes überschneiden sich.</li>';
				cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Zeitraum anlegen', '<p>Der Zeitraum <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Zeiträume\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_zeitraeume_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Zeitraum löschen', '<p>Soll der Zeitraum <b>'+anzeigename+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_zeitraeume_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_zeitraeume_loeschen(anzeigename, id) {
	cms_laden_an('Zeitraum löschen', 'Der Zeitraum <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'327');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Zeitraum löschen', '<p>Der Zeitraum wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Zeiträume\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_zeitraeume_bearbeiten_vorbereiten (id) {
	cms_laden_an('Zeitraum bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'328');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Planung/Zeiträume/Zeitraum_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_zeitraeume_bearbeiten_speichern () {
	cms_laden_an('Zeitraum bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_zeitraeume_bezeichnung').value;
	var meldung = '<p>Der Zeitraum konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_zeitraeume_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Zeitraum bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Zeitraum bearbeiten', 'Der Zeitraum wird bearbeitet');

		formulardaten.append("anfragenziel", 	'329');

		function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe.match(/ZEIT/)) {
				meldung += '<li>dieser Zeitraum liegt ganz oder teilweise außerhalb des gewählten Schuljahres.</li>';
				cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				meldung += '<li>dieser Zeitraum überschneidet sich mit anderen Zeiträumen in diesem Schuljahr.</li>';
				cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/STUNDEN/)) {
				meldung += '<li>Schulstunden dieses Zeitraumes überschneiden sich.</li>';
				cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Zeitraum bearbeiten', '<p>Der Zeitraum <b>'+bezeichnung+'</b> wurde gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Zeiträume\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_zeitraeume_neue_schulstunde() {
	var box = document.getElementById('cms_zeitraeume_schulstunden');
	var anzahl = document.getElementById('cms_zeitraeume_schulstunden_anzahl');
	var nr = document.getElementById('cms_zeitraeume_schulstunden_nr');
	var ids = document.getElementById('cms_zeitraeume_schulstunden_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";

  code += "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_zeitraeume_schulstunden_bezeichnung_"+neueid+"\" id=\"cms_zeitraeume_schulstunden_bezeichnung_"+neueid+"\" value=\"\"><input type=\"hidden\" name=\"cms_zeitraeume_schulstunden_id_"+neueid+"\" id=\"cms_zeitraeume_schulstunden_id_"+neueid+"\" value=\"-\"></td></tr>";
  code += "<tr><th>Beginn:</th><td>"+cms_uhrzeit_eingabe('cms_zeitraeume_schulstunden_beginn_'+neueid, '7', '0')+"</td></tr>";
  code += "<tr><th>Ende:</th><td>"+cms_uhrzeit_eingabe('cms_zeitraeume_schulstunden_ende_'+neueid, '8', '30')+"</td></tr>";
  code += "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_zeitraeume_schulstunden_entfernen('"+neueid+"');\">Schulstunde löschen</span></td></tr>";


	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_zeitraeume_schulstunden_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_zeitraeume_schulstunden_entfernen(id) {
	var box = document.getElementById('cms_zeitraeume_schulstunden');
	var anzahl = document.getElementById('cms_zeitraeume_schulstunden_anzahl');
	var ids = document.getElementById('cms_zeitraeume_schulstunden_ids');
	var stunde = document.getElementById('cms_zeitraeume_schulstunden_'+id);

	box.removeChild(stunde);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}

function cms_zeitraeume_rythmisieren_vorbereiten (id) {
	cms_laden_an('Zeitraum bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'165');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Planung/Zeiträume/Zeitraum_rythmisieren');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_zeitraeume_rythmisierung_speichern() {
  cms_laden_an('Zeitraumrythmisierung speichern', 'Die Eingaben werden überprüft.');
	var beginnjahr = document.getElementById('cms_rythmisierung_beginnjahr').value;
	var beginnkw = document.getElementById('cms_rythmisierung_beginnkw').value;
	var wochenanzahl = document.getElementById('cms_rythmisierung_wochenzahl').value;
	var meldung = '<p>Der Zeitraum konnte nicht geändert werden, denn ...</p><ul>';

  var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;

	if (!cms_check_ganzzahl(beginnjahr, 0)) {
		meldung += '<li>Das Jahr des Beginns des Zeitraums ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(beginnkw, 1,52)) {
		meldung += '<li>Die Kalenderwoche des Beginns des Zeitraums ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(wochenanzahl,0)) {
		meldung += '<li>Die Anzahl an Klaenderwochen istist ungültig.</li>';
		fehler = true;
	}

  if (!fehler) {
    var feldfehler = false;
    for (w=1;w<=wochenanzahl;w++) {
      var feld = document.getElementById('cms_rythmus_'+w);
      if (feld) {
        if (cms_check_ganzzahl(feld.value,1,26)) {formulardaten.append('woche_'+w, feld.value);}
      } else {feldfehler = true;}
    }
  }

	if (fehler) {
		cms_meldung_an('fehler', 'Zeitraumrythmisierung speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Zeitraumrythmisierung speichern', 'Der Zeitraum wird bearbeitet');

		formulardaten.append("anfragenziel", 	'290');

		function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Zeitraumrythmisierung speichern', '<p>Die neue Rythmisierung wurde übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Zeiträume\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}



function cms_zeitraeume_klonen_vorbereiten (id) {
	cms_laden_an('Zeitraum klonen', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'291');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Planung/Zeiträume/Zeitraum_klonen');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}





function cms_zeitraeume_klonen_speichern() {
  cms_laden_an('Zeitraum klonen', 'Die Eingaben werden überprüft.');
  var bezeichnung = document.getElementById('cms_zeitraeume_bezeichnung').value;
  var beginnT = document.getElementById('cms_zeitraeume_beginn_T').value;
  var beginnM = document.getElementById('cms_zeitraeume_beginn_M').value;
  var beginnJ = document.getElementById('cms_zeitraeume_beginn_J').value;
  var endeT = document.getElementById('cms_zeitraeume_ende_T').value;
  var endeM = document.getElementById('cms_zeitraeume_ende_M').value;
  var endeJ = document.getElementById('cms_zeitraeume_ende_J').value;
	var meldung = '<p>Der Zeitraum konnte nicht geändert werden, denn ...</p><ul>';

  var formulardaten = new FormData();
	var meldung = '';
  var fehler = false;
  var datumfehler = false;

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Die Bezeichnung des Zeitraums enthält ungültige Zeichen.</li>';
		fehler = true;
	}

  if (!cms_check_ganzzahl(beginnT, 1, 31) || !cms_check_ganzzahl(beginnM, 1, 12) || !cms_check_ganzzahl(beginnJ, 0)) {
		fehler = true;
    datumfehler = true;
		meldung += '<li>Das eingegebene Beginn-Datum ist ungültig.</li>';
	}
  if (!cms_check_ganzzahl(endeT, 1, 31) || !cms_check_ganzzahl(endeM, 1, 12) || !cms_check_ganzzahl(endeJ, 0)) {
		fehler = true;
    datumfehler = true;
		meldung += '<li>Das eingegebene End-Datum ist ungültig.</li>';
	}

	if (!datumfehler) {
		var beginn = new Date(beginnJ, beginnM-1, beginnT, 0,0,0,0);
		var ende = new Date(endeJ, endeM-1, endeT, 23,59,59,999);
		if (ende <= beginn) {
			fehler = true;
			meldung += '<li>Das End-Datum darf nicht vor dem Beginn-Datum liegen.</li>';
		}
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Zeitraum klonen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Zeitraum klonen', 'Der Zeitraum wird geklont');

    formulardaten.append("bezeichnung", bezeichnung);
    formulardaten.append("beginnT", 	beginnT);
  	formulardaten.append("beginnM", 	beginnM);
  	formulardaten.append("beginnJ",   beginnJ);
  	formulardaten.append("endeT", 	endeT);
  	formulardaten.append("endeM", 	endeM);
  	formulardaten.append("endeJ",   endeJ);
		formulardaten.append("anfragenziel", 	'292');

		function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Zeitraum klonen', '<p>Der Zeitraum wurde erfolgreich geklont.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Zeiträume\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
