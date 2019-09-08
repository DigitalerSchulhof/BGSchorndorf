function cms_stundenplanung_vorbereiten(sjid, zrid) {
  cms_laden_an('Stundenplanung vorbereiten', 'Der Stundenplan des Zeitraums wird geladen ...');

  var formulardaten = new FormData();
  formulardaten.append('sjid', sjid);
  formulardaten.append('zrid', zrid);
  formulardaten.append("anfragenziel", 	'279');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_stundenplanung_vollbild(vollbild) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_toggle(vollbild)) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('vollbild', vollbild);
	  formulardaten.append("anfragenziel", 	'155');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_modus(modus) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if ((modus != 'L') && (modus != 'P')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('modus', modus);
	  formulardaten.append("anfragenziel", 	'162');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_stufewaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'156');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_klassewaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'157');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_kurswaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'158');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_lehrerwaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'159');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_raumwaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'160');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_rythmuswaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'161');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplanung_rythmuswaehlen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0) && (id !== '-')) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'161');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundemarkieren(tag, stunde) {
	cms_klasse_dazu('cms_stunde_k_'+tag+'_'+stunde, 'cms_stundenplanung_markiert');
	cms_klasse_dazu('cms_stunde_l_'+tag+'_'+stunde, 'cms_stundenplanung_markiert');
	cms_klasse_dazu('cms_stunde_r_'+tag+'_'+stunde, 'cms_stundenplanung_markiert');
}

function cms_stundedemarkieren(tag, stunde) {
	cms_klasse_weg('cms_stunde_k_'+tag+'_'+stunde, 'cms_stundenplanung_markiert');
	cms_klasse_weg('cms_stunde_l_'+tag+'_'+stunde, 'cms_stundenplanung_markiert');
	cms_klasse_weg('cms_stunde_r_'+tag+'_'+stunde, 'cms_stundenplanung_markiert');
}

function cms_stundeplatzieren(tag, stunde) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(tag,1,7) || !cms_check_ganzzahl(stunde,0)) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('tag', tag);
	  formulardaten.append('stunde', stunde);
	  formulardaten.append("anfragenziel", 	'163');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
      else if (rueckgabe == "DOPPELTFEHLER") {
        cms_meldung_an('fehler', 'Stunde eintragen', '<p>Diese Stunde existiert bereits.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundeloeschen(id) {
  cms_laden_an('Stundenplanung aktualisieren', 'Der Stundenplan wird aktualisiert ...');

	if (!cms_check_ganzzahl(id,0)) {
		cms_meldung_an('fehler', 'Stundenplanung aktualisieren', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
	  formulardaten.append('id', id);
	  formulardaten.append("anfragenziel", 	'164');

	  function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
	      cms_link('Schulhof/Verwaltung/Planung/Stundenplanung');
	    }
	    else {cms_fehlerbehandlung(rueckgabe);}
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_stundenerzeugen_vorbereiten(sjid) {
  cms_laden_an('Stunden und Tagebücher erzeugen', 'Die Zeiträume werden geladen ...');

  var formulardaten = new FormData();
  formulardaten.append('sjid', sjid);
  formulardaten.append("anfragenziel", 	'293');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Stunden_und_Tagebücher_erzeugen');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_stundenerzeugen_speichern() {
  cms_laden_an('Stunden und Tagebücher erzeugen', 'Die Erzeugung wird vorbereitet ...');
  var stufen = document.getElementById('cms_stufen').value;
  var zeitraeume = document.getElementById('cms_zeitraeume').value;
  var schuljahr = document.getElementById('cms_schuljahr').value;

  var meldung = '<p>Die Stunden und Tagebücher konnten nicht erzeugt werden, denn ...</p><ul>';
  var fehler = false;

  if (!cms_check_ganzzahl(schuljahr,0)) {
    meldung += '<li>die das Schuljahres ist ungültig.</li>'
    fehler = true;
  }

  if (!cms_check_idfeld(zeitraeume)) {
    meldung += '<li>die die Zeiträume sind ungültig.</li>'
    fehler = true;
  }

  if ((!cms_check_idfeld(stufen)) && (stufen.length != 0)) {
    meldung += '<li>die Stufen des Schuljahres sind ungültig.</li>'
    fehler = true;
  }

  zeitraeume = zeitraeume.substr(1);
  var zt = zeitraeume.split('|');
  var st = (stufen.substr(1)).split('|');
  // Falls keine Stufen angelegt
  if ((st.length == 1) && (st[0].length == 0)) {st = new Array();}

  // Prüfen, welche Zeiträume erstellt werden sollen
  var ztanlegen = new Array();
  var ztindex = 0;
  var zeitraumfehler = false;
  for (var z = 0; z < zt.length; z++) {
    if (zt[z].length > 0) {
      var feld = document.getElementById('cms_zeitraum_erzeugen_'+zt[z]);
      if (feld) {
        if (!cms_check_toggle(feld.value)) {zeitraumfehler = true;}
        else {if (feld.value == 1) {ztanlegen[ztindex] = zt[z]; ztindex++;}}
      }
      else {zeitraumfehler = true;}
    }
  }

  if (zeitraumfehler) {
    meldung += '<li>die Auswahl der Zeiträume ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Stunden und Tagebücher erzeugen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var anzstufen = st.length;
    var anzzeitraeume = ztanlegen.length;
    var stufennr = 0;
    var zeitraumnr = 0;

    if ((anzzeitraeume > 0) || (st.length == 0)) {
      var feld = document.getElementById('cms_blende_i');
      var neuemeldung = '<div class="cms_spalte_i">';
      neuemeldung += '<h2 id="cms_laden_ueberschrift">Stunden und Tagebücher erzeugen</h2>';
      neuemeldung += '<p id="cms_laden_meldung_vorher">Bitte warten ...</p>';
      neuemeldung += '<h4>Gesamtfortschritt</h4>';
      neuemeldung += '<div class="cms_hochladen_fortschritt_o">';
        neuemeldung += '<div class="cms_hochladen_fortschritt_i" id="cms_hochladen_balken_gesamt" style="width: 0%;"></div>';
      neuemeldung += '</div>';
      neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Zeiträume: <span id="cms_stundnerezeugen_ztaktuell">0</span>/'+anzzeitraeume+' abgeschlossen</p>';
      neuemeldung += '<div id="cms_hochladen_aktuelledatei" style="display: block;">';
        neuemeldung += '<h4>Fortschritt in diesem Zeitraum</h4>';
        neuemeldung += '<div class="cms_hochladen_fortschritt_o">';
          neuemeldung += '<div class="cms_hochladen_fortschritt_i" id="cms_hochladen_balken_aktuell" style="width: 0%;"></div>';
        neuemeldung += '</div>'
        neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Stufe: <span id="cms_stundnerezeugen_staktuell">0</span>/'+anzstufen+' abgeschlossen</p>';
      neuemeldung += '</div></div>';
      feld.innerHTML = neuemeldung;

      var formulardaten = new FormData();
      formulardaten.append("stufe", st[stufennr]);
      formulardaten.append("zeitraum", 	ztanlegen[zeitraumnr]);
      formulardaten.append("schuljahr", 	schuljahr);
      formulardaten.append("anfragenziel", 	'294');

      function anfragennachbehandlung(rueckgabe) {
        if (rueckgabe == "ERFOLG") {
          // Abgeschlossene ids erhöhen:
          stufennr++;
          if (stufennr == anzstufen) {stufennr = 0; zeitraumnr++;}
          // Anzeige aktualisieren
          document.getElementById('cms_stundnerezeugen_ztaktuell').innerHTML = zeitraumnr;
          document.getElementById('cms_stundnerezeugen_staktuell').innerHTML = stufennr;
          document.getElementById('cms_hochladen_balken_gesamt').style.width = (100*zeitraumnr)/anzzeitraeume+'%';
          document.getElementById('cms_hochladen_balken_aktuell').style.width = (100*stufennr)/anzstufen+'%';

          if (zeitraumnr == anzzeitraeume) {
            cms_meldung_an('erfolg', 'Stunden und Tagebücher erzeugen', '<p>Die Unterrichtsstunden und Tagebücher wurden erzeugt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung\');">OK</span></p>');
          }
          else {
            // Nächste Stufe/Zeitraum starten
            var formulardaten = new FormData();
            formulardaten.append("stufe", st[stufennr]);
            formulardaten.append("zeitraum", 	ztanlegen[zeitraumnr]);
            formulardaten.append("schuljahr", 	schuljahr);
            formulardaten.append("anfragenziel", 	'294');

            cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
          }
        }
        else {cms_fehlerbehandlung(rueckgabe);}
      }

      cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
    }
    else {
      cms_meldung_an('erfolg', 'Stunden und Tagebücher erzeugen', '<p>Es war nichts zu erzeugen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung\');">OK</span></p>');
    }


  }
}
