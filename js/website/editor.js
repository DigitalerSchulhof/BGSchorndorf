function cms_editoren_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Editor laden', 'Andere Bearbeitungen werden abgebrochen.');
  cms_menuebearbeiten_ausblenden(spalte);
  cms_laden_an('Editor laden', 'Editor wird geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'38');

  function anfragennachbehandlung(rueckgabe) {
		if ((rueckgabe != "FEHLER") && (rueckgabe != "BERECHTIGUNG")) {
      document.getElementById('cms_website_'+feld+'_element_'+spalte+'_'+position).innerHTML = rueckgabe;
      cms_editor_aktivieren('cms_website_element_editor', true);
      cms_einblenden('cms_website_'+feld+'_element_'+spalte+'_'+position);
      cms_meldung_aus();
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_editoren_neu_speichern(zusatz) {
  cms_laden_an('Neuen Editor anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_editoren_aktiv').value;
  var position = document.getElementById('cms_website_element_editoren_position').value;
  var inhalt = document.getElementsByClassName('note-editable');
  inhalt = inhalt[0].innerHTML;

  var meldung = '<p>Der Editor konnte nicht erstellt werden, denn ...</p><ul>';
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

  if (inhalt.length == 0) {
    meldung += '<li>es wurde kein Inhalt eingegeben.</li>';
		fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Neuen Editor anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neuen Editor anlegen', 'Der neue Editor wird angelegt');

		var formulardaten = new FormData();
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("inhalt", inhalt);
		formulardaten.append("anfragenziel", 	'39');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neuen Editor anlegen', '<p>Der Editor wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
      else if (rueckgabe.match(/ZUORDNUNG/)) {
        meldung += '<li>der Ort, an dem der Editor erstellt werden sollte, existiert nicht mehr.</li>';
        cms_meldung_an('fehler', 'Neuen Editor anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}

function cms_editoren_bearbeiten_speichern(zusatz) {
  cms_laden_an('Editor bearbeiten', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_editoren_aktiv').value;
  var position = document.getElementById('cms_website_element_editoren_position').value;
  var inhalt = document.getElementsByClassName('note-editable');
  inhalt = inhalt[0].innerHTML;

  var meldung = '<p>Der Editor konnte nicht geändert werden, denn ...</p><ul>';
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

  if (inhalt.length == 0) {
    meldung += '<li>es wurde kein Inhalt eingegeben.</li>';
		fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Editor bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Editor bearbeiten', 'Der Editor wird bearbeitet.');

		var formulardaten = new FormData();
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("inhalt", inhalt);
		formulardaten.append("anfragenziel", 	'40');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Editor bearbeiten', '<p>Die Änderungen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
      else if (rueckgabe.match(/ZUORDNUNG/)) {
        meldung += '<li>der Ort, an dem der Editor erstellt werden sollte, existiert nicht mehr.</li>';
        cms_meldung_an('fehler', 'Editor bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}
