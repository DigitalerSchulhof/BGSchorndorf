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

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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
  else if (was == 'breaking') {
    var breaking = document.getElementById('cms_website_element_eventuebersicht_breaking').value;
    var zeilel1 = document.getElementById('cms_website_element_eventuebersicht_breakinglink_zeile1');
    var zeilel2 = document.getElementById('cms_website_element_eventuebersicht_breakinglink_zeile2');
    var zeilel3 = document.getElementById('cms_website_element_eventuebersicht_breakinglink_zeile3');
    var zeilel4 = document.getElementById('cms_website_element_eventuebersicht_breakinglink_zeile4');
    var zeilel5 = document.getElementById('cms_website_element_eventuebersicht_breakinglink_zeile5');
    var zeilet1 = document.getElementById('cms_website_element_eventuebersicht_breakingtext_zeile1');
    var zeilet2 = document.getElementById('cms_website_element_eventuebersicht_breakingtext_zeile2');
    var zeilet3 = document.getElementById('cms_website_element_eventuebersicht_breakingtext_zeile3');
    var zeilet4 = document.getElementById('cms_website_element_eventuebersicht_breakingtext_zeile4');
    var zeilet5 = document.getElementById('cms_website_element_eventuebersicht_breakingtext_zeile5');
    if (breaking == '1') {
      zeilel1.style.display = 'table-row';
      zeilel2.style.display = 'table-row';
      zeilel3.style.display = 'table-row';
      zeilel4.style.display = 'table-row';
      zeilel5.style.display = 'table-row';
      zeilet1.style.display = 'table-row';
      zeilet2.style.display = 'table-row';
      zeilet3.style.display = 'table-row';
      zeilet4.style.display = 'table-row';
      zeilet5.style.display = 'table-row';
    }
    else {
      zeilel1.style.display = 'none';
      zeilel2.style.display = 'none';
      zeilel3.style.display = 'none';
      zeilel4.style.display = 'none';
      zeilel5.style.display = 'none';
      zeilet1.style.display = 'none';
      zeilet2.style.display = 'none';
      zeilet3.style.display = 'none';
      zeilet4.style.display = 'none';
      zeilet5.style.display = 'none';
    }
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

  var breaking = document.getElementById('cms_website_element_eventuebersicht_breaking').value;
  var breakingt1 = document.getElementById('cms_website_element_eventuebersicht_breakingtext1').value;
  var breakingl1 = document.getElementById('cms_website_element_eventuebersicht_breakinglink1').value;
  var breakingt2 = document.getElementById('cms_website_element_eventuebersicht_breakingtext2').value;
  var breakingl2 = document.getElementById('cms_website_element_eventuebersicht_breakinglink2').value;
  var breakingt3 = document.getElementById('cms_website_element_eventuebersicht_breakingtext3').value;
  var breakingl3 = document.getElementById('cms_website_element_eventuebersicht_breakinglink3').value;
  var breakingt4 = document.getElementById('cms_website_element_eventuebersicht_breakingtext4').value;
  var breakingl4 = document.getElementById('cms_website_element_eventuebersicht_breakinglink4').value;
  var breakingt5 = document.getElementById('cms_website_element_eventuebersicht_breakingtext5').value;
  var breakingl5 = document.getElementById('cms_website_element_eventuebersicht_breakinglink5').value;

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

  if ((breaking != 0) && (breaking != 1)) {
    meldung += '<li>die Auswahl für die Breaking-News ist ungültig.</li>';
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
    formulardaten.append("breaking", breaking);
    formulardaten.append("breakingl1", breakingl1);
    formulardaten.append("breakingt1", breakingt1);
    formulardaten.append("breakingl2", breakingl2);
    formulardaten.append("breakingt2", breakingt2);
    formulardaten.append("breakingl3", breakingl3);
    formulardaten.append("breakingt3", breakingt3);
    formulardaten.append("breakingl4", breakingl4);
    formulardaten.append("breakingt4", breakingt4);
    formulardaten.append("breakingl5", breakingl5);
    formulardaten.append("breakingt5", breakingt5);

		formulardaten.append("anfragenziel", 	'42');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neue Eventübersicht anlegen', '<p>Die Eventübersicht wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

  var breaking = document.getElementById('cms_website_element_eventuebersicht_breaking').value;
  var breakingt1 = document.getElementById('cms_website_element_eventuebersicht_breakingtext1').value;
  var breakingl1 = document.getElementById('cms_website_element_eventuebersicht_breakinglink1').value;
  var breakingt2 = document.getElementById('cms_website_element_eventuebersicht_breakingtext2').value;
  var breakingl2 = document.getElementById('cms_website_element_eventuebersicht_breakinglink2').value;
  var breakingt3 = document.getElementById('cms_website_element_eventuebersicht_breakingtext3').value;
  var breakingl3 = document.getElementById('cms_website_element_eventuebersicht_breakinglink3').value;
  var breakingt4 = document.getElementById('cms_website_element_eventuebersicht_breakingtext4').value;
  var breakingl4 = document.getElementById('cms_website_element_eventuebersicht_breakinglink4').value;
  var breakingt5 = document.getElementById('cms_website_element_eventuebersicht_breakingtext5').value;
  var breakingl5 = document.getElementById('cms_website_element_eventuebersicht_breakinglink5').value;

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

  if ((breaking != 0) && (breaking != 1)) {
    meldung += '<li>die Auswahl für die Breaking-News ist ungültig.</li>';
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
    formulardaten.append("breaking", breaking);
    formulardaten.append("breakingl1", breakingl1);
    formulardaten.append("breakingt1", breakingt1);
    formulardaten.append("breakingl2", breakingl2);
    formulardaten.append("breakingt2", breakingt2);
    formulardaten.append("breakingl3", breakingl3);
    formulardaten.append("breakingt3", breakingt3);
    formulardaten.append("breakingl4", breakingl4);
    formulardaten.append("breakingt4", breakingt4);
    formulardaten.append("breakingl5", breakingl5);
    formulardaten.append("breakingt5", breakingt5);
		formulardaten.append("anfragenziel", 	'43');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Eventübersicht bearbeiten', '<p>Die Eventübersicht wurde bearbeitet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}
