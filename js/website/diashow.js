function cms_diashows_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Diashow laden', 'Diashow wird geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'168');

  function anfragennachbehandlung(rueckgabe) {
    if ((rueckgabe != "FEHLER") && (rueckgabe != "BERECHTIGUNG")) {
      document.getElementById('cms_website_'+feld+'_element_'+spalte+'_'+position).innerHTML = rueckgabe;
      cms_einblenden('cms_website_'+feld+'_element_'+spalte+'_'+position);
      cms_meldung_aus();
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_diashows_neu_speichern(zusatz) {
  cms_laden_an('Neue Diashow anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_diashow_aktiv').value;
  var position = document.getElementById('cms_website_element_diashow_position').value;
  var titel = document.getElementById('cms_website_element_diashow_titel').value;

  var bilderanzahl = document.getElementById('cms_bilder_anzahl').value;
	var bilderids = document.getElementById('cms_bilder_ids').value;

  var meldung = '<p>Die Diashow konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

  if ((aktiv != 0) && (aktiv != 1)) {
    meldung += '<li>der Aktivitätsgrad ist ungültig.</li>';
		fehler = true;
  }

  var positionfehler = false;
	if (!Number.isInteger(parseInt(position))) {
		positionfehler = true;
	}
	else if (position < 0) {
		positionfehler = true;
	}

	if (positionfehler) {
		meldung += '<li>es wurde eine ungültige Position angegeben.</li>';
		fehler = true;
	}

  if(!cms_check_titel(titel)) {
    meldung += "<li>der Titel ist ungültig.</li>";
    fehler = true;
  }

  var formulardaten = new FormData();

  var bpfadf = false;
	var bfehler = false;
	if (bilderanzahl > 0) {
		ids = bilderids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var bbeschreibung = document.getElementById('cms_bild_beschreibung_'+bid);
			var bpfad = document.getElementById('cms_bild_datei_'+bid);

			if (bbeschreibung) {formulardaten.append("bbeschreibung_"+bid,  bbeschreibung.value);} else {bfehler = true;}
			if (bpfad) {if ((bpfad.value).length > 0) {formulardaten.append("bpfad_"+bid,  bpfad.value);} else {bpfadf = true;}} else {bfehler = true;}
		}
	}

	if (bfehler) {
		meldung += '<li>bei der Erfassung der Bilder ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (bpfadf) {
		meldung += '<li>bei mindestens einem Bild wurde keine Datei ausgewählt.</li>';
		fehler = true;
	}

  if (fehler) {
    cms_meldung_an('fehler', 'Neue Diashow anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neue Diashow anlegen', 'Die neue Diashow wird angelegt.');

		formulardaten.append("aktiv",           aktiv);
		formulardaten.append("position",        position);
    formulardaten.append("titel",           titel);
    formulardaten.append("bildanzahl",  		bilderanzahl);
		formulardaten.append("bildids", 				bilderids);

		formulardaten.append("anfragenziel", 	'262');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neue Diashow anlegen', '<p>Die Diashow wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}

function cms_diashows_bearbeiten_speichern(zusatz) {
  cms_laden_an('Diashow bearbeiten', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_diashow_aktiv').value;
  var position = document.getElementById('cms_website_element_diashow_position').value;
  var titel = document.getElementById('cms_website_element_diashow_titel').value;

  var bilderanzahl = document.getElementById('cms_bilder_anzahl').value;
	var bilderids = document.getElementById('cms_bilder_ids').value;

  var meldung = '<p>Die Diashow konnte nicht bearbeitet werden, denn ...</p><ul>';
	var fehler = false;

  if ((aktiv != 0) && (aktiv != 1)) {
    meldung += '<li>der Aktivitätsgrad ist ungültig.</li>';
		fehler = true;
  }

  var positionfehler = false;
	if (!Number.isInteger(parseInt(position))) {
		positionfehler = true;
	}
	else if (position < 0) {
		positionfehler = true;
	}

	if (positionfehler) {
		meldung += '<li>es wurde eine ungültige Position angegeben.</li>';
		fehler = true;
	}

  if(!cms_check_titel(titel)) {
    meldung += "<li>der Titel ist ungültig.</li>";
    fehler = true;
  }

  var formulardaten = new FormData();

  var bpfadf = false;
	var bfehler = false;
	if (bilderanzahl > 0) {
		ids = bilderids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var bbeschreibung = document.getElementById('cms_bild_beschreibung_'+bid);
			var bpfad = document.getElementById('cms_bild_datei_'+bid);
			var bidi = document.getElementById('cms_bild_id_'+bid);

			if (bbeschreibung) {formulardaten.append("bbeschreibung_"+bid,  bbeschreibung.value);} else {bfehler = true;}
			if (bpfad) {if ((bpfad.value).length > 0) {formulardaten.append("bpfad_"+bid,  bpfad.value);} else {bpfadf = true;}} else {bfehler = true;}
			if (bidi) {if (bidi.value.toString().length > 0) {formulardaten.append("bid_"+bid,  bidi.value);} else {formulardaten.append("bid_"+bid,-1);}} else {formulardaten.append("bid_"+bid, -1);}
		}
	}

	if (bfehler) {
		meldung += '<li>bei der Erfassung der Bilder ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (bpfadf) {
		meldung += '<li>bei mindestens einem Bild wurde keine Datei ausgewählt.</li>';
		fehler = true;
	}

  if (fehler) {
    cms_meldung_an('fehler', 'Diashow bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Diashow bearbeiten', 'Die Diashow wird geändert.');

    formulardaten.append("aktiv",           aktiv);
		formulardaten.append("position",        position);
    formulardaten.append("titel",           titel);
    formulardaten.append("bildanzahl",  		bilderanzahl);
		formulardaten.append("bildids", 				bilderids);

		formulardaten.append("anfragenziel", 	'263');


    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Diashow bearbeiten', '<p>Die Diashow wurde bearbeitet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
