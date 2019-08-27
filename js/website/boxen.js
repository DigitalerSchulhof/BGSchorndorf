function cms_boxenaussen_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Boxen laden', 'Andere Bearbeitungen werden abgebrochen.');
  cms_menuebearbeiten_ausblenden(spalte);
  cms_laden_an('Boxen laden', 'Boxen werden geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'32');

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

function cms_boxen_ausrichtung_aendern() {
  var ausrichtung = document.getElementById('cms_website_element_boxen_ausrichtung').value;
  var breite = document.getElementById('cms_website_element_boxen_breite').value;
  var ids = (document.getElementById('cms_boxen_boxen_ids').value).split('|');
  if ((ausrichtung == 'n') || (ausrichtung == 'u')) {
    document.getElementById('cms_boxen_boxen').className = 'cms_boxen_'+ausrichtung;
    for (var i=1; i<ids.length; i++) {
      cms_klasse_weg('cms_boxen_box_'+ids[i], 'cms_box_u');
      cms_klasse_weg('cms_boxen_box_'+ids[i], 'cms_box_n');
      cms_klasse_dazu('cms_boxen_box_'+ids[i], 'cms_box_'+ausrichtung);
      if (ausrichtung == 'u') {
        document.getElementById('cms_boxen_box_'+ids[i]).style.width = '';
        document.getElementById('cms_box_titel_'+ids[i]).style.width = breite+'px';
      }
      else if (ausrichtung == 'n') {
        document.getElementById('cms_boxen_box_'+ids[i]).style.width = breite+'px';
        document.getElementById('cms_box_titel_'+ids[i]).style.width = '';
      }
    }
  }
}

function cms_boxen_breite_aendern() {
  var ausrichtung = document.getElementById('cms_website_element_boxen_ausrichtung').value;
  var breite = document.getElementById('cms_website_element_boxen_breite').value;
  var ids = (document.getElementById('cms_boxen_boxen_ids').value).split('|');

  if (ausrichtung == 'n') {
    for (var i=1; i<ids.length; i++) {document.getElementById('cms_boxen_box_'+ids[i]).style.width = breite+'px';}
  }
  else if (ausrichtung == 'u') {
    for (var i=1; i<ids.length; i++) {document.getElementById('cms_box_titel_'+ids[i]).style.width = breite+'px';}
  }
}

function cms_box_style(id, farbe) {
  if ((farbe == 1) || (farbe == 2) || (farbe == 3) ||
      (farbe == 4) || (farbe == 5)) {
    cms_klasse_weg('cms_boxen_box_'+id, 'cms_box_1');
    cms_klasse_weg('cms_boxen_box_'+id, 'cms_box_2');
    cms_klasse_weg('cms_boxen_box_'+id, 'cms_box_3');
    cms_klasse_weg('cms_boxen_box_'+id, 'cms_box_4');
    cms_klasse_weg('cms_boxen_box_'+id, 'cms_box_5');
    var altefarbe = document.getElementById('cms_boxen_box_style_'+id).value;
    cms_klasse_weg('cms_box_style_'+id+'_'+altefarbe, 'cms_farbbeispiel_aktiv');
    cms_klasse_dazu('cms_box_style_'+id+'_'+altefarbe, 'cms_farbbeispiel');
    cms_klasse_dazu('cms_boxen_box_'+id, 'cms_box_'+farbe);
    cms_klasse_weg('cms_box_style_'+id+'_'+farbe, 'cms_farbbeispiel');
    cms_klasse_dazu('cms_box_style_'+id+'_'+farbe, 'cms_farbbeispiel_aktiv');
    document.getElementById('cms_boxen_box_style_'+id).value = farbe;
  }
}

function cms_boxinhalt_bearbeiten(id) {
  var bearbeitung = document.getElementById('cms_boxen_boxen_bearbeitung');
  // Alle Boxen beenden
  if ((bearbeitung.value).length > 0) {
    cms_boxinhalt_bearbeiten_abschliessen(bearbeitung.value);
  }
  cms_editor_aktivieren('cms_boxen_box_editor_'+id, true);
  bearbeitung.value = id;
  // Bearbeitenknopf verbergen, Fertig-Knopf anzeigen
  cms_ausblenden('cms_boxen_box_inhalt_bearbeiten_'+id);
  cms_einblenden('cms_boxen_box_inhalt_fertig_'+id, 'inline-block');
}

function cms_boxinhalt_bearbeiten_abschliessen(id) {
  // Inhalt aus dem Editor holen
  var inhalt = document.getElementsByClassName('note-editable')[0];
  var editorfeld = document.getElementById('cms_boxen_box_editor_'+id);
  var boxinhalt = document.getElementById('cms_boxen_box_inhalt_'+id);
  var editor = document.getElementsByClassName('note-editor')[0];
  editorfeld.innerHTML = inhalt.innerHTML;
  boxinhalt.removeChild(editor);
  editorfeld.style.display = 'block';
  document.getElementById('cms_boxen_boxen_bearbeitung').value = '';
  // Bearbeitenknopf verbergen, Fertig-Knopf anzeigen
  cms_ausblenden('cms_boxen_box_inhalt_fertig_'+id);
  //cms_einblenden('cms_boxen_box_inhalt_bearbeiten_'+id, 'inline-block');
}



function cms_boxen_neue_box(freigabe) {
	var boxaussen = document.getElementById('cms_boxen_boxen');
	var anzahl = document.getElementById('cms_boxen_boxen_anzahl');
	var nr = document.getElementById('cms_boxen_boxen_nr');
	var ids = document.getElementById('cms_boxen_boxen_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

  var ausrichtung = document.getElementById('cms_website_element_boxen_ausrichtung').value;
  var breite = document.getElementById('cms_website_element_boxen_breite').value;

	var code = "";

  if (ausrichtung == 'n') {style = "";} else {style = " style=\"width: "+breite+"px;\"";}
  code += "<div class=\"cms_box_titel\" id=\"cms_box_titel_"+neueid+"\""+style+">";
  code += "<table class=\"cms_formular\">";
    code += "<tr><th>Aktiv:</th><td>";
    if (freigabe == '1') {code += cms_schieber_generieren('cms_boxen_box_aktiv_'+neueid, '1');}
    else {
      code += cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Bis dieser Eintrag freigegeben wird, bleibt er inaktiv.</p>');
      code += '<input type="hidden" name="cms_boxen_box_aktiv_'+neueid+'" id="cms_boxen_box_aktiv_'+neueid+'" value="0">';
    }
    code += "</td></tr>";
    code += "<tr><td colspan=\"2\">";
    code += "<span class=\"cms_farbbeispiel_aktiv cms_box_style_"+1+"\" id=\"cms_box_style_"+neueid+"_"+1+"\" onclick=\"cms_box_style('"+neueid+"', '"+1+"')\"></span> ";
    for (j=2; j<=5; j++) {
      code += "<span class=\"cms_farbbeispiel cms_box_style_"+j+"\" id=\"cms_box_style_"+neueid+"_"+j+"\" onclick=\"cms_box_style('"+neueid+"', '"+j+"')\"></span> ";
    }
    code += "<input type=\"hidden\" name=\"cms_boxen_box_style_"+neueid+"\" id=\"cms_boxen_box_style_"+neueid+"\" value=\"1\">";
    code += "</td></tr>";
    code += "<tr><td colspan=\"2\"><span class=\"cms_button_nein\" onclick=\"cms_boxen_box_entfernen('"+neueid+"');\">Box löschen</span></td></tr>";
  code += "</table>";
  code += "<input type=\"text\" id=\"cms_boxen_box_titel_"+neueid+"\" name=\"cms_boxen_box_titel_"+neueid+"\" value=\"\">"

  code += "</div>";
  code += "<div class=\"cms_box_inhalt\" id=\"cms_boxen_box_inhalt_"+neueid+"\">";
    code += "<div id=\"cms_boxen_box_editor_"+neueid+"\"></div>";
    code += "<p><span id=\"cms_boxen_box_inhalt_bearbeiten_"+neueid+"\" class=\"cms_button\" onclick=\"cms_boxinhalt_bearbeiten('"+neueid+"');\">Bearbeiten</span> ";
    code += "<span id=\"cms_boxen_box_inhalt_fertig_"+neueid+"\" class=\"cms_button cms_button_ja\" onclick=\"cms_boxinhalt_bearbeiten_abschliessen('"+neueid+"');\" style=\"display: none;\">Fertig</span></p>";
  code += "</div>";
  code += "<div class=\"cms_clear\"></div>";


	var knoten = document.createElement("DIV");
	knoten.className = 'cms_box_'+ausrichtung+' cms_box_1';
  if (ausrichtung == 'n') {knoten.style.width = breite+"px";}
	knoten.setAttribute('id', 'cms_boxen_box_'+neueid);
	knoten.innerHTML = code;
	boxaussen.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_boxen_box_entfernen(id) {
  var bearbeitung = document.getElementById('cms_boxen_boxen_bearbeitung');
  // Alle Boxen beenden
  if (bearbeitung.value ==  id) {
    cms_boxinhalt_bearbeiten_abschliessen(bearbeitung.value);
  }

	var boxaussen = document.getElementById('cms_boxen_boxen');
	var anzahl = document.getElementById('cms_boxen_boxen_anzahl');
	var ids = document.getElementById('cms_boxen_boxen_ids');
	var box = document.getElementById('cms_boxen_box_'+id);

	boxaussen.removeChild(box);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}


function cms_boxen_neu_speichern(zusatz) {
  cms_laden_an('Neue Boxen anlegen', 'Die Eingaben werden überprüft.');
  // Bearbeitungen abschließen
  var bearbeitung = document.getElementById('cms_boxen_boxen_bearbeitung');
  // Alle Boxen beenden
  if ((bearbeitung.value).length > 0) {
    cms_boxinhalt_bearbeiten_abschliessen(bearbeitung.value);
  }

  var aktiv = document.getElementById('cms_website_element_boxen_aktiv').value;
  var position = document.getElementById('cms_website_element_boxen_position').value;
  var ausrichtung = document.getElementById('cms_website_element_boxen_ausrichtung').value;
  var breite = document.getElementById('cms_website_element_boxen_breite').value;
  var boxenanzahl = document.getElementById('cms_boxen_boxen_anzahl').value;
  var boxenids = document.getElementById('cms_boxen_boxen_ids').value;

  var meldung = '<p>Die Boxen konnten nicht erstellt werden, denn ...</p><ul>';
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

  if ((ausrichtung != 'n') && (ausrichtung != 'u')) {
    meldung += '<li>es wurde keine gültige Ausrichtung angegeben.</li>';
		fehler = true;
  }

  var breitefehler = false;
	if (!Number.isInteger(parseInt(breite))) {
		breitefehler = true;
	}
	else if (breite < 0) {
		breitefehler = true;
	}

	if (breitefehler) {
		meldung += '<li>es wurde eine ungültige Breite angegeben.</li>';
		fehler = true;
	}

  var formulardaten = new FormData();
	// Prüfen, ob Downloads dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var baktivf = false;
	var bstylef = false;
	var btitelf = false;
  var binhaltf = false;
  var bfehler = false;
  if (boxenanzahl > 0) {
		ids = boxenids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var baktiv = document.getElementById('cms_cms_boxen_box_aktiv_'+bid);
			var bstyle = document.getElementById('cms_boxen_box_style_'+bid);
			var btitel = document.getElementById('cms_boxen_box_titel_'+bid);
			var binhalt = document.getElementById('cms_boxen_box_editor_'+bid);

			if (baktiv) {if ((baktiv.value == 1) || (baktiv.value == 0)) {formulardaten.append("baktiv_"+bid,  baktiv.value);} else {baktivf = true;}} else {bfehler = true;}
			if (bstyle) {if ((bstyle.value == 1) || (bstyle.value == 2) || (bstyle.value == 3) || (bstyle.value == 4) || (bstyle.value == 5)) {
        formulardaten.append("bstyle_"+bid,  bstyle.value);} else {bstylef = true;}} else {bfehler = true;
      }
      if (btitel) {if ((btitel.value).length > 0) {formulardaten.append("btitel_"+bid,  btitel.value);} else {btitelf = true;}} else {bfehler = true;}
      if (binhalt) {if ((binhalt.innerHTML).length > 0) {formulardaten.append("binhalt_"+bid,  binhalt.innerHTML);} else {binhaltf = true;}} else {bfehler = true;}
		}
	}

	if (bfehler) {
		meldung += '<li>bei der Erstellung der einzelnen Boxen ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (baktivf) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen ist die Eingabe für die Aktivität ungültig.</li>';
		fehler = true;
	}
	if (bstylef) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen ist die Eingabe für den Style ungültig.</li>';
		fehler = true;
	}
	if (btitelf) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen wurde kein Titel eingegeben.</li>';
		fehler = true;
	}
	if (binhaltf) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen wurde kein Inhalt eingegeben.</li>';
		fehler = true;
	}

  if (fehler) {
    cms_meldung_an('fehler', 'Neue Boxen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neue Boxen anlegen', 'Die neuen Boxen werden angelegt');

		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("ausrichtung", ausrichtung);
		formulardaten.append("breite", breite);
    formulardaten.append("boxenanzahl", boxenanzahl);
		formulardaten.append("boxenids", boxenids);
		formulardaten.append("anfragenziel", 	'33');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neue Boxen anlegen', '<p>Die Boxen wurden angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
      else if (rueckgabe.match(/ZUORDNUNG/)) {
        meldung += '<li>der Ort, an dem die Boxen erstellt werden sollten, existiert nicht mehr.</li>';
        cms_meldung_an('fehler', 'Neue Boxen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
      else {cms_fehlerbehandlung(rueckgabe);}
    }

    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}



function cms_boxen_bearbeiten_speichern(zusatz) {
  cms_laden_an('Boxen bearbeiten', 'Die Eingaben werden überprüft.');
  // Bearbeitungen abschließen
  var bearbeitung = document.getElementById('cms_boxen_boxen_bearbeitung');
  // Alle Boxen beenden
  if ((bearbeitung.value).length > 0) {
    cms_boxinhalt_bearbeiten_abschliessen(bearbeitung.value);
  }

  var aktiv = document.getElementById('cms_website_element_boxen_aktiv').value;
  var position = document.getElementById('cms_website_element_boxen_position').value;
  var ausrichtung = document.getElementById('cms_website_element_boxen_ausrichtung').value;
  var breite = document.getElementById('cms_website_element_boxen_breite').value;
  var boxenanzahl = document.getElementById('cms_boxen_boxen_anzahl').value;
  var boxenids = document.getElementById('cms_boxen_boxen_ids').value;

  var meldung = '<p>Die Boxen konnten nicht erstellt werden, denn ...</p><ul>';
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

  if ((ausrichtung != 'n') && (ausrichtung != 'u')) {
    meldung += '<li>es wurde keine gültige Ausrichtung angegeben.</li>';
		fehler = true;
  }

  var breitefehler = false;
	if (!Number.isInteger(parseInt(breite))) {
		breitefehler = true;
	}
	else if (breite < 0) {
		breitefehler = true;
	}

	if (breitefehler) {
		meldung += '<li>es wurde eine ungültige Breite angegeben.</li>';
		fehler = true;
	}

  var formulardaten = new FormData();
	// Prüfen, ob Downloads dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var baktivf = false;
	var bstylef = false;
	var btitelf = false;
  var binhaltf = false;
  var bfehler = false;
  if (boxenanzahl > 0) {
		ids = boxenids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var baktiv = document.getElementById('cms_cms_boxen_box_aktiv_'+bid);
			var bstyle = document.getElementById('cms_boxen_box_style_'+bid);
			var btitel = document.getElementById('cms_boxen_box_titel_'+bid);
			var binhalt = document.getElementById('cms_boxen_box_editor_'+bid);

			if (baktiv) {if ((baktiv.value == 1) || (baktiv.value == 0)) {formulardaten.append("baktiv_"+bid,  baktiv.value);} else {baktivf = true;}} else {bfehler = true;}
			if (bstyle) {if ((bstyle.value == 1) || (bstyle.value == 2) || (bstyle.value == 3) || (bstyle.value == 4) || (bstyle.value == 5)) {
        formulardaten.append("bstyle_"+bid,  bstyle.value);} else {bstylef = true;}} else {bfehler = true;
      }
      if (btitel) {if ((btitel.value).length > 0) {formulardaten.append("btitel_"+bid,  btitel.value);} else {btitelf = true;}} else {bfehler = true;}
      if (binhalt) {if ((binhalt.innerHTML).length > 0) {formulardaten.append("binhalt_"+bid,  binhalt.innerHTML);} else {binhaltf = true;}} else {bfehler = true;}
		}
	}

	if (bfehler) {
		meldung += '<li>bei der Erstellung der einzelnen Boxen ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (baktivf) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen ist die Eingabe für die Aktivität ungültig.</li>';
		fehler = true;
	}
	if (bstylef) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen ist die Eingabe für den Style ungültig.</li>';
		fehler = true;
	}
	if (btitelf) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen wurde kein Titel eingegeben.</li>';
		fehler = true;
	}
	if (binhaltf) {
		meldung += '<li>bei mindestens einer der einzelnen Boxen wurde kein Inhalt eingegeben.</li>';
		fehler = true;
	}

  if (fehler) {
    cms_meldung_an('fehler', 'Neue Boxen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Boxen bearbeiten', 'Die Boxen werden bearbeitet.');

		formulardaten.append("aktiv", aktiv);
		formulardaten.append("position", position);
		formulardaten.append("ausrichtung", ausrichtung);
		formulardaten.append("breite", breite);
    formulardaten.append("boxenanzahl", boxenanzahl);
		formulardaten.append("boxenids", boxenids);
		formulardaten.append("anfragenziel", 	'34');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Boxen bearbeiten', '<p>Die Boxen wurden bearbeitet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
      else if (rueckgabe.match(/ZUORDNUNG/)) {
        meldung += '<li>der Ort, an dem die Boxen bearbeitet werden sollten, existiert nicht mehr.</li>';
        cms_meldung_an('fehler', 'Boxen bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
      else {cms_fehlerbehandlung(rueckgabe);}
    }

    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
