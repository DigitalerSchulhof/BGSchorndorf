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
  if (was == 'blog') {
    var blog = document.getElementById('cms_website_element_eventuebersicht_blog').value;
    var zeile1 = document.getElementById('cms_website_element_eventuebersicht_blog_zeile1');
    var zeile2 = document.getElementById('cms_website_element_eventuebersicht_blog_zeile2');
    if (blog == '1') {
      zeile1.style.display = 'table-row';
      zeile2.style.display = 'table-row';
    }
    else {
      zeile1.style.display = 'none';
      zeile2.style.display = 'none';
    }
  }
  else if (was == 'galerien') {
    var galerie = document.getElementById('cms_website_element_eventuebersicht_galerien').value;
    var zeile = document.getElementById('cms_website_element_eventuebersicht_galerien_zeile');
    if (galerie == '1') {zeile.style.display = 'table-row';}
    else {zeile.style.display = 'none';}
  }
  else if (was == 'termine') {
    var termine = document.getElementById('cms_website_element_eventuebersicht_termine').value;
    var zeile = document.getElementById('cms_website_element_eventuebersicht_termine_zeile');
    if (termine == '1') {zeile.style.display = 'table-row';}
    else {zeile.style.display = 'none';}
  }

}

function cms_eventuebersichten_neu_speichern(zusatz) {
  cms_laden_an('Neue Eventübersicht anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_eventuebersicht_aktiv').value;
  var position = document.getElementById('cms_website_element_eventuebersicht_position').value;
  var termine = document.getElementById('cms_website_element_eventuebersicht_termine').value;
  var termineanzahl = document.getElementById('cms_website_element_eventuebersicht_termineanzahl').value;
  var blog = document.getElementById('cms_website_element_eventuebersicht_blog').value;
  var bloganzahl = document.getElementById('cms_website_element_eventuebersicht_bloganzahl').value;
  var blogart = document.getElementById('cms_website_element_eventuebersicht_blogart').value;
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

	if (!cms_check_ganzzahl(termineanzahl,0)) {
		meldung += '<li>es wurde eine ungültige Terminanzahl angegeben.</li>';
		fehler = true;
	}

  if ((blog != 0) && (blog != 1)) {
    meldung += '<li>die Blogauswahl ist ungültig.</li>';
		fehler = true;
  }

  if ((blogart != 'a') && (blogart != 'l') && (blogart != 'd')) {
    meldung += '<li>die Blogart ist ungültig.</li>';
		fehler = true;
  }

	if (!cms_check_ganzzahl(bloganzahl, 0)) {
		meldung += '<li>es wurde eine ungültige Bloganzahl angegeben.</li>';
		fehler = true;
	}

  if ((galerie != 0) && (galerie != 1)) {
    meldung += '<li>die Galerieauswahl ist ungültig.</li>';
		fehler = true;
  }

	if (!cms_check_ganzzahl(galerieanzahl, 0)) {
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
    formulardaten.append("blogart", blogart);
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
  var blogart = document.getElementById('cms_website_element_eventuebersicht_blogart').value;
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

	if (!cms_check_ganzzahl(termineanzahl, 0)) {
		meldung += '<li>es wurde eine ungültige Terminanzahl angegeben.</li>';
		fehler = true;
	}

  if ((blog != 0) && (blog != 1)) {
    meldung += '<li>die Blogauswahl ist ungültig.</li>';
		fehler = true;
  }

	if (!cms_check_ganzzahl(bloganzahl, 0)) {
		meldung += '<li>es wurde eine ungültige Bloganzahl angegeben.</li>';
		fehler = true;
	}

  if ((blogart != 'a') && (blogart != 'l') && (blogart != 'd')) {
    meldung += '<li>die Blogart ist ungültig.</li>';
		fehler = true;
  }

  if ((galerie != 0) && (galerie != 1)) {
    meldung += '<li>die Galerieauswahl ist ungültig.</li>';
		fehler = true;
  }

	if (!cms_check_ganzzahl(galerieanzahl, 0)) {
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
		formulardaten.append("blogart", blogart);
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
