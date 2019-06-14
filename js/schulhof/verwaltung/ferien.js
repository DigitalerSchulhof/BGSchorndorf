function cms_neue_ferien() {
	cms_laden_an('Neue Ferien vorbereiten', 'Vorbereitungen für den neuen Ferientermin werden getroffen.');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'5');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Verwaltung/Ferien/Neuer_Ferientermin');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_ferien_neu_speichern() {
	cms_laden_an('Neuen Ferientermin anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_ferientermine_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '6');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Ferientermin anlegen', '<p>Der Ferientermin wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Ferien\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neuen Ferientermin anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_ferien_bearbeiten_speichern() {
	cms_laden_an('Ferientermin bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_ferientermine_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '8');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Ferientermin bearbeiten', '<p>Die Änderungen wurden übernommen</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Ferien\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Ferientermin bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_ferien_bearbeiten_vorbereiten(id) {
	cms_laden_an('Ferientermin bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'7');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Ferien/Ferientermin_bearbeiten');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_ferien_loeschen_vorbereiten(id, bezeichnung) {
	cms_meldung_an('warnung', 'Ferien löschen', '<p>Soll der Ferientermin <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p><p><b>Achtung!</b> Tagebücher werden nicht automatisch angepasst!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_ferien_loeschen(\''+id+'\')">Löschung durchführen</span></p>');
}


function cms_ferien_loeschen(id, ziel) {
	cms_laden_an('Ferien löschen', 'Der Termin wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'10');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Ferien löschen', '<p>Der Ferientermin wurde gelöscht.</p><p><b>Achtung!</b> Tagebücher wurden nicht automatisch angepasst!</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Ferien\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_ferientermine_eingabenpruefen(modus) {
	var meldung = '<p>Der Ferientermin konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	var beginnT = document.getElementById('cms_ferien_beginn_datum_T').value;
	var beginnM = document.getElementById('cms_ferien_beginn_datum_M').value;
	var beginnJ = document.getElementById('cms_ferien_beginn_datum_J').value;
	var endeT = document.getElementById('cms_ferien_ende_datum_T').value;
	var endeM = document.getElementById('cms_ferien_ende_datum_M').value;
	var endeJ = document.getElementById('cms_ferien_ende_datum_J').value;
	var bezeichnung = document.getElementById('cms_ferien_bezeichnung').value;
	var art = document.getElementById('cms_ferien_art').value;

	var formulardaten = new FormData();

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	beginn = new Date(beginnJ, beginnM-1, beginnT, 0, 0, 0, 0);
	ende = new Date(endeJ, endeM-1, endeT, 23, 59, 59, 999);

	if (beginn-ende >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum eingegeben.</li>';
		fehler = true;
	}

	if ((art != 'f') && (art != 'b') && (art != 't') && (art != 's')) {
		if (ort.length == 0) {
			meldung += '<li>es wurde keine erlaubte Ferienart angegeben.</li>';
			fehler = true;
		}
	}

	if (!fehler) {
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("art",  			art);
		formulardaten.append("beginnT",  				beginnT);
		formulardaten.append("beginnM",					beginnM);
		formulardaten.append("beginnJ",  				beginnJ);
		formulardaten.append("endeT",  					endeT);
		formulardaten.append("endeM",  					endeM);
		formulardaten.append("endeJ",  					endeJ);
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}


// FERIEN eines Jahres löschen
function cms_ferien_jahr_loeschen_vorbereiten() {
  var jahr = document.getElementById('cms_verwaltung_ferien_jahr_angezeigt').value;
	cms_meldung_an('warnung', 'Ferien des Jahres '+jahr+' löschen', '<p>Sollen <b>alle</b> Ferien des Jahres '+jahr+' wirklich gelöscht werden?</p><p><b>Achtung!</b> Tagebücher werden nicht automatisch angepasst!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_ferien_jahr_loeschen()">Löschung durchführen</span></p>');
}

function cms_ferien_jahr_loeschen() {
  var jahr = document.getElementById('cms_verwaltung_ferien_jahr_angezeigt').value;
	cms_laden_an('Ferien des Jahres '+jahr+' löschen', 'Alle Ferien des Jahres '+jahr+' werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("jahr", 	jahr);
	formulardaten.append("anfragenziel", 	'11');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Ferien des Jahres '+jahr+' löschen', '<p>Die Ferientermine wurden gelöscht.</p><b>Achtung!</b> Tagebücher wurden nicht automatisch angepasst!</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Ferien\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_ferienverwaltung(jahr, spalten, anfang, ende) {
  var feld = document.getElementById('cms_verwaltung_ferien_jahr');
  feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\"><img src="res/laden/standard.gif"><br>Die Ferien für das Jahr '+jahr+' werden geladen.</td></tr>';

  for (var i=anfang; i<=ende; i++) {
    var toggle = document.getElementById('cms_verwaltung_ferien_jahr_'+i);
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
  	formulardaten.append("anfragenziel", 	'9');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.slice(0,4) == '<tr>') {
				var toggle = document.getElementById('cms_verwaltung_ferien_jahr_'+jahr);
				toggle.className = 'cms_toggle_aktiv';
				document.getElementById('cms_verwaltung_ferien_jahr_angezeigt').value = jahr;
				feld.innerHTML = rueckgabe;
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
