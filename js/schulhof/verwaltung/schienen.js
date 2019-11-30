function cms_schienen_vorbereiten(id) {
  cms_laden_an('Profile vorbereiten', 'Die Profile des Schuljahres werden vorbereitet ...');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", 	'176');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Schienen');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schienen_fach_auswaehlen (id) {
  var auswahl = document.getElementById('cms_schiene_kurs_'+id);
  var gewaehlt = document.getElementById('cms_schiene_kursegewaehltids').value;
  if (auswahl) {
    gewaehlt = gewaehlt+'|';
    var suche = new RegExp("/|"+id+"|/");
    if (auswahl.value == '1') {
      if (!gewaehlt.match(suche)) {
        gewaehlt = gewaehlt + id + '|';
      }
    }
    else {
      gewaehlt = gewaehlt.replace("|"+id+"|", "|");
    }
    document.getElementById('cms_schiene_kursegewaehltids').value = gewaehlt.substr(0,gewaehlt.length-1);
  }
}

function cms_schienen_kurse_laden (id) {
  var schienenkursefeld = document.getElementById('cms_schiene_kurse_feld');
  var gewaehlt = document.getElementById('cms_schiene_kursegewaehltids').value;
  var fach = document.getElementById('cms_schiene_filter_faecher').value;
  var stufe = document.getElementById('cms_schiene_filter_stufe').value;
  schienenkursefeld.innerHTML = cms_ladeicon();

  var formulardaten = new FormData();
  formulardaten.append('gewaehlt', gewaehlt);
  formulardaten.append('fach', fach);
  formulardaten.append('stufe', stufe);
  formulardaten.append("anfragenziel", 	'349');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe.match(/^<span /)) {
      schienenkursefeld.innerHTML = rueckgabe;
    }
    else {schienenkursefeld.innerHTML = '<span class=\"cms_notiz\">Bein Laden der Kurse ist ein Fehler aufgetreten.</span><input type="hidden" name="cms_schiene_kursegewaehltids" id="cms_schiene_kursegewaehltids" value="'+gewaehlt+'">';}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);

}


function cms_schienen_eingabencheck() {
	var bezeichnung = document.getElementById('cms_schiene_bezeichnung').value;
  var kurse = document.getElementById('cms_schiene_kursegewaehltids').value;
  var zeitraum = document.getElementById('cms_schiene_zeitraum').value;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;
  var datumfehler = false;

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Die Bezeichnung der Schiene‚ enthält ungültige Zeichen.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(zeitraum, 0)) {
		meldung += '<li>Der gewählte Zeitraum ist ungültig.</li>';
		fehler = true;
	}

	formulardaten.append("bezeichnung", bezeichnung);
	formulardaten.append("zeitraum", zeitraum);
	formulardaten.append("kurse",  kurse);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_schienen_neu_speichern() {
	cms_laden_an('Neue Schiene anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Schiene konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_schienen_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Schiene anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Schiene anlegen', 'Die neue Schiene wird angelegt');

		formulardaten.append("anfragenziel", 	'356');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Schiene anlegen', '<p>Die Schiene wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schienen\');">Zurück zur Übersicht</span></p>');
			}
      else if (rueckgabe.match(/KURSE/)) {
        cms_meldung_an('fehler', 'Schiene bearbeiten', '<p>Es wurden Kurse gewählt, die nicht oder nicht mehr existieren.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schienen\');">Zurück zur Übersicht</span></p>');
      }
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_schienen_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Schiene löschen', '<p>Soll die Schiene <b>'+anzeigename+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schienen_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}

function cms_schienen_loeschen(anzeigename, id) {
	cms_laden_an('Schiene löschen', 'Die Schiene <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'357');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Schiene löschen', '<p>Die Schiene wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schienen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schienen_bearbeiten_vorbereiten (id) {
	cms_laden_an('Schiene bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'358');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Planung/Schienen/Schiene_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schienen_bearbeiten_speichern () {
	cms_laden_an('Schiene bearbeiten', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Schiene konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_schienen_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Schiene bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schiene bearbeiten', 'Die Schiene wird bearbeitet');

		formulardaten.append("anfragenziel", 	'359');

		function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schiene bearbeiten', '<p>Die Schiene wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schienen\');">Zurück zur Übersicht</span></p>');
			}
      else if (rueckgabe.match(/KURSE/)) {
				cms_meldung_an('fehler', 'Schiene bearbeiten', '<p>Es wurden Kurse gewählt, die nicht oder nicht mehr existieren.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schienen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
