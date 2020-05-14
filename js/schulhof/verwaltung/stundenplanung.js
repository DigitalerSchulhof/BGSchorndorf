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

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}





function cms_stundenplan_vorbereiten(art, id, zeitraum) {
  var zeitraum = zeitraum || '-';
  cms_laden_an('Stundenplan', 'Der Stundenplan wird vorbereitet ...');
  var meldung = '<p>Der Stundenplan konnte nicht geladen werden, denn ...</p><ul>';
  var fehler = false;

  if ((art != 'm') && (art != 'l') && (art != 'p') && (art != 'r') &&
      (art != 'k') && (art != 't')) {fehler = true;}
  if (!cms_check_ganzzahl(id, 0)) {fehler = true;}
  if ((!cms_check_ganzzahl(zeitraum, 0)) && (zeitraum != '-')) {fehler = true;}

  if (fehler) {
		cms_meldung_an('fehler', 'Stundenplanung', '<p>Die Eingaben sind fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
    var formulardaten = new FormData();
    formulardaten.append('art', art);
    formulardaten.append('id', id);
    formulardaten.append('zeitraum', zeitraum);
    formulardaten.append("anfragenziel", 	'295');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        if (art == 'm') {cms_link('Schulhof/Nutzerkonto/Mein_Stundenplan');}
        if (art == 'l') {location.reload();}
        if (art == 'r') {location.reload();}
        if (art == 'k') {location.reload();}
        if (art == 't') {location.reload();}
      }
      else {cms_fehlerbehandlung(rueckgabe);}
    }

    cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
