function cms_zeitraum_neue_schulstunde() {
	var box = document.getElementById('cms_zeitraum_schulstunden');
	var anzahl = document.getElementById('cms_zeitraum_schulstunden_anzahl');
	var nr = document.getElementById('cms_zeitraum_schulstunden_nr');
	var ids = document.getElementById('cms_zeitraum_schulstunden_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";
	code +="<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_zeitraum_bezeichnung_"+neueid+"\" id=\"cms_zeitraum_bezeichnung_"+neueid+"\" value=\"\"/></td></tr>";
	code +="<tr><th>Beginn:</th><td><input class=\"cms_input_h\" type=\"text\" name=\"cms_zeitraum_beginn_"+neueid+"_h\" id=\"cms_zeitraum_beginn_"+neueid+"_h\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_beginn_"+neueid+"')\" value=\"07\"> : <input class=\"cms_input_m\" type=\"text\" name=\"cms_zeitraum_beginn_"+neueid+"_m\" id=\"cms_zeitraum_beginn_"+neueid+"_m\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_beginn_"+neueid+"')\" value=\"30\"></td></tr>";
	code +="<tr><th>Ende:</th><td><input class=\"cms_input_h\" type=\"text\" name=\"cms_zeitraum_ende_"+neueid+"_h\" id=\"cms_zeitraum_ende_"+neueid+"_h\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_ende_"+neueid+"')\" value=\"08\"> : <input class=\"cms_input_m\" type=\"text\" name=\"cms_zeitraum_ende_"+neueid+"_m\" id=\"cms_zeitraum_ende_"+neueid+"_m\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_ende_"+neueid+"')\" value=\"15\"></td></tr>";
	code +="<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_zeitraum_schulstunden_entfernen('"+neueid+"');\">Schulstunde löschen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_zeitraum_schulstunden_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_zeitraum_schulstunden_entfernen(id) {
	var box = document.getElementById('cms_zeitraum_schulstunden');
	var anzahl = document.getElementById('cms_zeitraum_schulstunden_anzahl');
	var ids = document.getElementById('cms_zeitraum_schulstunden_ids');
	var download = document.getElementById('cms_zeitraum_schulstunden_'+id);

	box.removeChild(download);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}

function cms_zeitraum_jahr_laden(jahr, spalten) {
  var feld = document.getElementById('cms_zeitraum_schulstunden_jahr');
	var jahrids = (document.getElementById('cms_zeitraum_ids').value).split('|');
  feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\">'+cms_ladeicon()+'<br>Die Zeiträume werden geladen.</td></tr>';

  for (var i=1; i<jahrids.length; i++) {
    var toggle = document.getElementById('cms_zeitraum_'+jahrids[i]);
    toggle.className = 'cms_toggle';
	}

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

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
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_stundenplan_neuestundenplaene() {
	document.getElementById('cms_stundenplanung_lehrerslot').innerHTML = '<p class="cms_stundenplan_laden"><img src="res/laden/standard.gif"><br>Der Stundenplan wird aktualisiert.</p>';
	document.getElementById('cms_stundenplanung_raumslot').innerHTML = '<p class="cms_stundenplan_laden"><img src="res/laden/standard.gif"><br>Der Stundenplan wird aktualisiert.</p>';
	document.getElementById('cms_stundenplanung_klassenslot').innerHTML = '<p class="cms_stundenplan_laden"><img src="res/laden/standard.gif"><br>Der Stundenplan wird aktualisiert.</p>';
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

function cms_stundenplan_neuestundenplaene() {
	document.getElementById('cms_stundenplanung_lehrerslot').innerHTML = '<p class="cms_stundenplan_laden">'+cms_ladeicon()+'<br>Der Stundenplan wird aktualisiert.</p>';
	document.getElementById('cms_stundenplanung_raumslot').innerHTML = '<p class="cms_stundenplan_laden">'+cms_ladeicon()+'<br>Der Stundenplan wird aktualisiert.</p>';
	document.getElementById('cms_stundenplanung_klassenslot').innerHTML = '<p class="cms_stundenplan_laden">'+cms_ladeicon()+'<br>Der Stundenplan wird aktualisiert.</p>';
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
  var zeitraume = document.getElementById('cms_zeitraeume').value;
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
  stufen = '-'+stufen;
  var st = stufen.split('|');

  var stanlegen = 0;
  var stanzahl = st.length;
  var ztanlegen = 0;
  var ztanzahl = zt.length;

  if (fehler) {
    cms_meldung_an('fehler', 'Stunden und Tagebücher erzeugen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var formulardaten = new FormData();
    formulardaten.append("anfragenziel", 	'294');

    function anfragennachbehandlung(rueckgabe) {
      /*if (rueckgabe == "ERFOLG") {
        cms_link('Schulhof/Verwaltung/Planung/Stunden_und_Tagebücher_erzeugen');
      }
      else {cms_fehlerbehandlung(rueckgabe);}*/
    }

    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
