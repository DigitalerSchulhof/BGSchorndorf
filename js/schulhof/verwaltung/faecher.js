function cms_faecher_vorbereiten(id) {
  cms_laden_an('Fächer vorbereiten', 'Die Fächer des Schuljahres werden vorbereitet ...');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", 	'335');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Fächer');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_faecher_neu_speichern() {
	cms_laden_an('Neues Fach anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_kuerzel').value;
	var farbe = document.getElementById('cms_faecher_farbe').value;
	var icon = document.getElementById('cms_gruppe_icon').value;
	var kollegen = document.getElementById('cms_faecher_kollegen_personensuche_gewaehlt').value;

	var meldung = '<p>Das Fach konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	if (kuerzel.length == 0) {
		meldung += '<li>es wurde kein Kürzel eingegeben.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(farbe,0,47)) {
		meldung += '<li>die gewählte Farbe ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_dateiname(icon)) {
		meldung += '<li>das gewählte Icon ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neues Fach anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neues Fach anlegen', 'Das neue Fach wird angelegt');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("kuerzel", kuerzel);
    formulardaten.append("farbe", farbe);
    formulardaten.append("icon", icon);
    formulardaten.append("kollegen", kollegen);

		formulardaten.append("anfragenziel", 	'80');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Fach anlegen', '<p>Das Fach <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTB/)) {
				meldung += '<li>es gibt bereits ein Fach mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neues Fach anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTK/)) {
				meldung += '<li>es gibt bereits ein Fach mit diesem Kürzel.</li>';
				cms_meldung_an('fehler', 'Neues Fach anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_faecher_import_speichern() {
	cms_laden_an('Fächer importieren', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_import_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_import_kuerzel').value;
	var farbe = document.getElementById('cms_faecher_import_farbe').value;
	var icon = document.getElementById('cms_faecher_import_icon').value;
	var csv = document.getElementById('cms_faecher_import_csv').value;
	var trennung = document.getElementById('cms_faecher_import_trennung').value;

	var meldung = '<p>Die Fächer konnten nicht importiert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (csv.length == 0) {
		meldung += '<li>es wurden keine Datensätze eingegeben.</li>';
		fehler = true;
	}

	if (trennung.length == 0) {
		meldung += '<li>es wurde kein Trennsymbol eingegeben.</li>';
		fehler = true;
	}

  var maxspalten = 0;
  if (!fehler) {
    // Inhalte analysieren
    var csvanalyse = csv.split("\n");
    for (var i=0; i<csvanalyse.length; i++) {
      var aktspalten = csvanalyse[i].split(trennung).length;
      if (aktspalten > maxspalten) {maxspalten = aktspalten;}
    }
  }

	if (!cms_check_ganzzahl(bezeichnung, 1, maxspalten)) {
		meldung += '<li>die Auswahl für die Bezeichnung ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(kuerzel, 1, maxspalten)) {
		meldung += '<li>die Auswahl für das Kürzel ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(farbe, 1, maxspalten) && (farbe != '-')) {
		meldung += '<li>die Auswahl für die Farbe ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(icon, 1, maxspalten) && (icon != '-')) {
		meldung += '<li>die Auswahl für das Icon ist ungültig.</li>';
		fehler = true;
	}

  if (!fehler) {
    var importfehler = false;
    for (var i=0; i<csvanalyse.length; i++) {
      var csvdaten = csvanalyse[i].split(trennung);
      if (bezeichnung != '-') {if (!cms_check_titel(csvdaten[bezeichnung-1])) {alert(i); importfehler = true;}}
      if (kuerzel != '-') {if (!cms_check_titel(csvdaten[kuerzel-1])) {alert(i); importfehler = true;}}
      if (farbe != '-') {if (!cms_check_ganzzahl(csvdaten[farbe-1],0,47)) {importfehler = true;}}
      if (icon != '-') {if (!cms_check_dateiname(csvdaten[icon-1],0,47)) {importfehler = true;}}
    }
    if (importfehler) {
  		meldung += '<li>die zu importierenden Daten enthielten zum Teil ungültige Zeichen. Bezeichnungen und Kürzel müssen mindestens ein Zeichen lang sein.</li>';
  		fehler = true;
  	}
  }

	if (fehler) {
		cms_meldung_an('fehler', 'Fächer importieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
    var anz = csvanalyse.length;
    var nr = 0;

    if (anz > 0) {
      var feld = document.getElementById('cms_blende_i');
      var neuemeldung = '<div class="cms_spalte_i">';
      neuemeldung += '<h2 id="cms_laden_ueberschrift">Fächer importieren</h2>';
      neuemeldung += '<p id="cms_laden_meldung_vorher">Bitte warten ...</p>';
      neuemeldung += '<h4>Gesamtfortschritt</h4>';
      neuemeldung += '<div class="cms_fortschritt_o">';
        neuemeldung += '<div class="cms_fortschritt_i" id="cms_hochladen_balken_gesamt" style="width: 0%;"></div>';
      neuemeldung += '</div>';
      neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Fächer: <span id="cms_importakt">0</span>/'+anz+' abgeschlossen</p></div>';
      feld.innerHTML = neuemeldung;

      var formulardaten = new FormData();
      var csvdaten = csvanalyse[nr].split(trennung);
      var abgelehnt = "";
      formulardaten.append("bezeichnung", csvdaten[bezeichnung-1]);
  		formulardaten.append("kuerzel", csvdaten[kuerzel-1]);
      if (farbe != '-') {formulardaten.append("farbe", csvdaten[farbe-1]);}
      else {formulardaten.append("farbe", '0');}
      if (icon != '-') {formulardaten.append("icon", csvdaten[icon-1]);}
      else {formulardaten.append("icon", 'faecher.png');}
      formulardaten.append("kollegen", "");
      formulardaten.append("anfragenziel", 	'80');

      function anfragennachbehandlung(rueckgabe) {
        var csvdaten = csvanalyse[nr].split(trennung);
        if (rueckgabe != "ERFOLG") {
          abgelehnt += ", "+csvdaten[bezeichnung-1]+" ("+csvdaten[kuerzel-1]+")";
        }

        // Abgeschlossene ids erhöhen:
        nr++;
        // Anzeige aktualisieren
        document.getElementById('cms_importakt').innerHTML = nr;
        document.getElementById('cms_hochladen_balken_gesamt').style.width = (100*nr)/anz+'%';

        if (nr == anz) {
          if (abgelehnt.length > 0) {
            var ablehnung = "<p>Folgende Fächer konnten nicht erzeugt werden: "+abgelehnt.substr(2)+"</p>";
          }
          else {var ablehnung = "";}
          cms_meldung_an('erfolg', 'Fächer importieren', '<p>Die Fächer wurden importiert.</p>'+ablehnung, '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">OK</span></p>');
        }
        else {
          // Nächstes Fach starten
          var csvdaten = csvanalyse[nr].split(trennung);
          var formulardaten = new FormData();
          formulardaten.append("bezeichnung", csvdaten[bezeichnung-1]);
          formulardaten.append("kuerzel", csvdaten[kuerzel-1]);
          if (farbe != '-') {formulardaten.append("farbe", csvdaten[farbe-1]);}
          else {formulardaten.append("farbe", '0');}
          if (icon != '-') {formulardaten.append("icon", csvdaten[icon-1]);}
          else {formulardaten.append("icon", 'faecher.png');}
          formulardaten.append("kollegen", "");
          formulardaten.append("anfragenziel", 	'80');

          cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
        }
      }

      cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
    }
    else {
      cms_meldung_an('erfolg', 'Fächer importieren', '<p>Es war nichts zu importieren.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">OK</span></p>');
    }
	}
}


function cms_schulhof_faecher_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Fach löschen', '<p>Soll das Fach <b>'+anzeigename+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_faecher_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_faecher_loeschen(anzeigename, id) {
	cms_laden_an('Fach löschen', 'Das Fach <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'81');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Fächer löschen', '<p>Das Fach wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

/* RAUM WIRD ZUM BEARBEITEN VORBEREITET */
function cms_schulhof_faecher_bearbeiten_vorbereiten (id) {
	cms_laden_an('Klassenstufe bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'82');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Planung/Fächer/Fach_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_faecher_bearbeiten () {
	cms_laden_an('Fach bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_faecher_bezeichnung').value;
	var kuerzel = document.getElementById('cms_faecher_kuerzel').value;
	var farbe = document.getElementById('cms_faecher_farbe').value;
	var icon = document.getElementById('cms_gruppe_icon').value;
	var kollegen = document.getElementById('cms_faecher_kollegen_personensuche_gewaehlt').value;

	var meldung = '<p>Das Fach konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	if (kuerzel.length == 0) {
		meldung += '<li>es wurde kein Kürzel eingegeben.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(farbe,0,47)) {
		meldung += '<li>die gewählte Farbe ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_dateiname(icon)) {
		meldung += '<li>das gewählte Icon ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Fach bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Fach bearbeiten', 'Das Fach wird bearbeitet');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("kuerzel", kuerzel);
    formulardaten.append("farbe", farbe);
    formulardaten.append("icon", icon);
    formulardaten.append("kollegen", kollegen);
		formulardaten.append("anfragenziel", 	'83');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Fach bearbeiten', '<p>Das Fach <b>'+bezeichnung+'</b> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Fächer\');">Zurück zur Übersicht</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTB/)) {
				meldung += '<li>es gibt bereits ein Fach mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Fach bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/DOPPELTK/)) {
				meldung += '<li>es gibt bereits ein Fach mit diesem Kürzel.</li>';
				cms_meldung_an('fehler', 'Fach bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
