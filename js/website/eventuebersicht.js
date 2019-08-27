function cms_eventuebersichten_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Editor laden', 'Andere Bearbeitungen werden abgebrochen.');
  cms_menuebearbeiten_ausblenden(spalte);
  cms_laden_an('Eventübersicht laden', 'Eventübersicht wird geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'41');

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

function cms_eventuebersichten_aendern(was) {
  var blog = document.getElementById('cms_website_element_eventuebersicht_'+was).value;
  var zeile = document.getElementById('cms_website_element_eventuebersicht_'+was+'_zeile');
  if (blog == '1') {zeile.style.display = 'table-row';}
  else {zeile.style.display = 'none';}
}

function cms_eventuebersichten_neu_speichern(zusatz) {
  cms_laden_an('Neue Eventübersicht anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_eventuebersicht_aktiv').value;
  var position = document.getElementById('cms_website_element_eventuebersicht_position').value;
  var termine = document.getElementById('cms_website_element_eventuebersicht_termine').value;
  var termineanzahl = document.getElementById('cms_website_element_eventuebersicht_termineanzahl').value;
  var blog = document.getElementById('cms_website_element_eventuebersicht_blog').value;
  var bloganzahl = document.getElementById('cms_website_element_eventuebersicht_bloganzahl').value;
  var galerie = document.getElementById('cms_website_element_eventuebersicht_galerien').value;
  var galerieanzahl = document.getElementById('cms_website_element_eventuebersicht_galerienanzahl').value;

  var meldung = '<p>Die Eventübersicht konnte nicht erstellt werden, denn ...</p><ul>';
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

  if ((termine != 0) && (termine != 1)) {
    meldung += '<li>die Terminauswahl ist ungültig.</li>';
		fehler = true;
  }

  var terminfehler = false;
	if (!Number.isInteger(parseInt(termineanzahl))) {
		terminfehler = true;
	}
	else if (termineanzahl < 0) {
		terminfehler = true;
	}

	if (terminfehler) {
		meldung += '<li>es wurde eine ungültige Terminanzahl angegeben.</li>';
		fehler = true;
	}

  if ((blog != 0) && (blog != 1)) {
    meldung += '<li>die Blogauswahl ist ungültig.</li>';
		fehler = true;
  }

  var blogfehler = false;
	if (!Number.isInteger(parseInt(bloganzahl))) {
		blogfehler = true;
	}
	else if (bloganzahl < 0) {
		blogfehler = true;
	}

	if (blogfehler) {
		meldung += '<li>es wurde eine ungültige Bloganzahl angegeben.</li>';
		fehler = true;
	}

  if ((galerie != 0) && (galerie != 1)) {
    meldung += '<li>die Galerieauswahl ist ungültig.</li>';
		fehler = true;
  }

  var galeriefehler = false;
	if (!Number.isInteger(parseInt(galerieanzahl))) {
		galeriefehler = true;
	}
	else if (galerieanzahl < 0) {
		galeriefehler = true;
	}

	if (galeriefehler) {
		meldung += '<li>es wurde eine ungültige Galerienanzahl angegeben.</li>';
		fehler = true;
	}

  if (fehler) {
    cms_meldung_an('fehler', 'Neue Eventübersicht anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neue Eventübersicht anlegen', 'Die neue Eventübersicht wird angelegt.');

		var formulardaten = new FormData();
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("termine", termine);
		formulardaten.append("termineanzahl", termineanzahl);
		formulardaten.append("blog", blog);
		formulardaten.append("bloganzahl", bloganzahl);
		formulardaten.append("galerie", galerie);
		formulardaten.append("galerieanzahl", galerieanzahl);
		formulardaten.append("anfragenziel", 	'42');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neue Eventübersicht anlegen', '<p>Die Eventübersicht wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}



function cms_eventuebersichten_bearbeiten_speichern(zusatz) {
  cms_laden_an('Eventübersicht bearbeiten', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_eventuebersicht_aktiv').value;
  var position = document.getElementById('cms_website_element_eventuebersicht_position').value;
  var termine = document.getElementById('cms_website_element_eventuebersicht_termine').value;
  var termineanzahl = document.getElementById('cms_website_element_eventuebersicht_termineanzahl').value;
  var blog = document.getElementById('cms_website_element_eventuebersicht_blog').value;
  var bloganzahl = document.getElementById('cms_website_element_eventuebersicht_bloganzahl').value;
  var galerie = document.getElementById('cms_website_element_eventuebersicht_galerien').value;
  var galerieanzahl = document.getElementById('cms_website_element_eventuebersicht_galerienanzahl').value;

  var meldung = '<p>Die Eventübersicht konnte nicht bearbeitet werden, denn ...</p><ul>';
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

  if ((termine != 0) && (termine != 1)) {
    meldung += '<li>die Terminauswahl ist ungültig.</li>';
		fehler = true;
  }

  var terminfehler = false;
	if (!Number.isInteger(parseInt(termineanzahl))) {
		terminfehler = true;
	}
	else if (termineanzahl < 0) {
		terminfehler = true;
	}

	if (terminfehler) {
		meldung += '<li>es wurde eine ungültige Terminanzahl angegeben.</li>';
		fehler = true;
	}

  if ((blog != 0) && (blog != 1)) {
    meldung += '<li>die Blogauswahl ist ungültig.</li>';
		fehler = true;
  }

  var blogfehler = false;
	if (!Number.isInteger(parseInt(bloganzahl))) {
		blogfehler = true;
	}
	else if (bloganzahl < 0) {
		blogfehler = true;
	}

	if (blogfehler) {
		meldung += '<li>es wurde eine ungültige Bloganzahl angegeben.</li>';
		fehler = true;
	}

  if ((galerie != 0) && (galerie != 1)) {
    meldung += '<li>die Galerieauswahl ist ungültig.</li>';
		fehler = true;
  }

  var galeriefehler = false;
	if (!Number.isInteger(parseInt(galerieanzahl))) {
		galeriefehler = true;
	}
	else if (galerieanzahl < 0) {
		galeriefehler = true;
	}

	if (galeriefehler) {
		meldung += '<li>es wurde eine ungültige Galerienanzahl angegeben.</li>';
		fehler = true;
	}

  if (fehler) {
    cms_meldung_an('fehler', 'Eventübersicht bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Eventübersicht bearbeiten', 'Die Eventübersicht wird geändert.');

		var formulardaten = new FormData();
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("termine", termine);
		formulardaten.append("termineanzahl", termineanzahl);
		formulardaten.append("blog", blog);
		formulardaten.append("bloganzahl", bloganzahl);
		formulardaten.append("galerie", galerie);
		formulardaten.append("galerieanzahl", galerieanzahl);
		formulardaten.append("anfragenziel", 	'43');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Eventübersicht bearbeiten', '<p>Die Eventübersicht wurde bearbeitet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
