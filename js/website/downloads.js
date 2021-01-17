function cms_downloads_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Download laden', 'Andere Bearbeitungen werden abgebrochen.');
  cms_menuebearbeiten_ausblenden(spalte);
  cms_laden_an('Download laden', 'Editor wird geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'35');


  function anfragennachbehandlung(rueckgabe) {
		if ((rueckgabe != "FEHLER") && (rueckgabe != "BERECHTIGUNG")) {
      document.getElementById('cms_website_'+feld+'_element_'+spalte+'_'+position).innerHTML = rueckgabe;
      cms_einblenden('cms_website_'+feld+'_element_'+spalte+'_'+position);
      cms_meldung_aus();
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_downloads_neu_speichern(zusatz) {
  cms_laden_an('Neuen Download anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_downloads_aktiv').value;
  var position = document.getElementById('cms_website_element_downloads_position').value;
  var pfad = document.getElementById('cms_website_element_downloads_datei').value;
  var titel = document.getElementById('website_element_downloads_titel').value;
  var beschreibung = document.getElementById('website_element_downloads_beschreibung').value;
  var dateiname = document.getElementById('cms_website_element_downloads_dateiname').value;
  var dateigroesse = document.getElementById('cms_website_element_downloads_dateigroesse').value;

  var meldung = '<p>Der Download konnte nicht erstellt werden, denn ...</p><ul>';
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

  if (pfad.length == 0) {
    meldung += '<li>es wurde keine Datei ausgewählt.</li>';
		fehler = true;
  }

  if (titel.length == 0) {
    meldung += '<li>es wurde kein Titel eingegeben.</li>';
		fehler = true;
  }

  if ((dateiname != 0) && (dateiname != 1)) {
    meldung += '<li>die Auswahl für den Dateinamen ist ungültig.</li>';
		fehler = true;
  }

  if ((dateigroesse != 0) && (dateigroesse != 1)) {
    meldung += '<li>die Auswahl für die Dateigröße ist ungültig.</li>';
		fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Neuen Download anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neuen Download anlegen', 'Der neue Download wird angelegt');

		var formulardaten = new FormData();
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("pfad", pfad);
		formulardaten.append("titel", titel);
		formulardaten.append("beschreibung", beschreibung);
		formulardaten.append("dateiname", dateiname);
		formulardaten.append("dateigroesse", dateigroesse);
		formulardaten.append("anfragenziel", 	'36');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neuen Download anlegen', '<p>Der Download wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
      else if (rueckgabe.match(/DATEI/)) {
        meldung += '<li>die gewählte Datei existiert nicht mehr.</li>';
        cms_meldung_an('fehler', 'Neuen Download anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}

function cms_downloads_bearbeiten_speichern(zusatz) {
  cms_laden_an('Download bearbeiten', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_downloads_aktiv').value;
  var position = document.getElementById('cms_website_element_downloads_position').value;
  var pfad = document.getElementById('cms_website_element_downloads_datei').value;
  var titel = document.getElementById('website_element_downloads_titel').value;
  var beschreibung = document.getElementById('website_element_downloads_beschreibung').value;
  var dateiname = document.getElementById('cms_website_element_downloads_dateiname').value;
  var dateigroesse = document.getElementById('cms_website_element_downloads_dateigroesse').value;

  var meldung = '<p>Der Download konnte nicht geändert werden, denn ...</p><ul>';
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

  if (pfad.length == 0) {
    meldung += '<li>es wurde keine Datei ausgewählt.</li>';
		fehler = true;
  }

  if (titel.length == 0) {
    meldung += '<li>es wurde kein Titel eingegeben.</li>';
		fehler = true;
  }

  if ((dateiname != 0) && (dateiname != 1)) {
    meldung += '<li>die Auswahl für den Dateinamen ist ungültig.</li>';
		fehler = true;
  }

  if ((dateigroesse != 0) && (dateigroesse != 1)) {
    meldung += '<li>die Auswahl für die Dateigröße ist ungültig.</li>';
		fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Download bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Download bearbeiten', 'Der Download wird bearbeitet.');

		var formulardaten = new FormData();
    formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("pfad", pfad);
		formulardaten.append("titel", titel);
		formulardaten.append("beschreibung", beschreibung);
		formulardaten.append("dateiname", dateiname);
		formulardaten.append("dateigroesse", dateigroesse);
		formulardaten.append("anfragenziel", 	'37');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Download bearbeiten', '<p>Die Änderungen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
      else if (rueckgabe.match(/DATEI/)) {
        meldung += '<li>die gewählte Datei existiert nicht mehr.</li>';
        cms_meldung_an('fehler', 'Download bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

    cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}
