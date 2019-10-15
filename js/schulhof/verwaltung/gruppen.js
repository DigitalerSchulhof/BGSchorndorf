function cms_gruppen_listeausgeben(name, schuljahr) {
  var feld = document.getElementById('cms_gruppenliste');
  feld.innerHTML = "<tr><td style=\"text-align: center;\" colspan=\"7\">"+cms_ladeicon()+"<br><br>Daten werden geladen. Bitte warten...</td></tr>";

  cms_buttonwechsel('cms_gruppen_schuljahr', schuljahr);

  var formulardaten = new FormData();
  formulardaten.append('name', name);
  formulardaten.append('schuljahr', schuljahr);
  formulardaten.append('anfragenziel', '221');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe.match(/<tr>/)) {
      feld.innerHTML = rueckgabe;
    }
    else {
      feld.innerHTML = "<tr><td style=\"text-align: center;\" colspan=\"7\">-- Keine Datensätze gefunden --</td></tr>";
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppen_neu_speichern (art) {
  cms_laden_an('Neue Gruppe anlegen', 'Die Eingaben werden überprüft.');
  var fehler = false;

  if ((art == 'Fachschaften') || (art == 'Gremien')) {
    if (!CMS_IMLN) {
      fehler = true;
      cms_meldung_firewall();
    }
  }

  if (!fehler) {
    var eingaben = cms_gruppen_eingabenpruefung(art);

    if (eingaben != '-') {
      eingaben.append('anfragenziel', '220');
      eingaben.append('art', art);
      cms_laden_an('Neue Gruppe anlegen', 'Die Gruppe wird angelegt.');

      function anfragennachbehandlung(rueckgabe) {
    		if (rueckgabe.match(/ERFOLG/)) {
          if ((art == 'Fachschaften') || (art == 'Gremien')) {
            id = rueckgabe.split('|');
            cms_laden_an('Neue Gruppe anlegen', 'Die Gruppe wird im gesicherten Bereich angelegt.');
            formulardaten = new FormData();
            formulardaten.append('anfragenziel', '');
            formulardaten.append('art', art);
            formulardaten.append('id', id[1]);
            cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);

            function lehrerservernachbehandlung(rueckgabe) {
              if (rueckgabe == "ERFOLG") {
                cms_meldung_an('erfolg', 'Neue Gruppe anlegen', '<p>Die Gruppe wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Gruppen/'+art.replace(' ', '_')+'\');">Zurück zur Übersicht</span></p>');
              }
              else {cms_fehlerbehandlung(rueckgabe);}
            }

            cms_ajaxanfrage (false, formulardaten, lehrerservernachbehandlung, CMS_LN_DA);
          }
          else {
            cms_meldung_an('erfolg', 'Neue Gruppe anlegen', '<p>Die Gruppe wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Gruppen/'+art.replace(' ', '_')+'\');">Zurück zur Übersicht</span></p>');
          }
        }
    		else {
          var fehlersammlung = false;
          var meldung = "<p>Die Eingaben sind fehlerhaft:</p><ul>";
          if (rueckgabe.match(/DOPPELT/)) {
            meldung += '<li>Es gibt in diesem Schuljahr oder schuljahrübergreifend bereits eine Gruppe mit dieser Bezeichnung.</li>';
            fehlersammlung = true;
          }
          if (rueckgabe.match(/SCHULJAHR/)) {
            meldung += '<li>Das eingegebene Schuljahr existert nicht.</li>';
            fehlersammlung = true;
          }
          if (rueckgabe.match(/MITGLIEDER/)) {
            meldung += '<li>Mindestens ein eingegebenes Mitglied darf dieser Gruppe nicht angehören.</li>';
            fehlersammlung = true;
          }
          if (rueckgabe.match(/AUFSICHT/)) {
            meldung += '<li>Mindestens eine eingegebene Aufsichtsperson darf die Aufsicht nicht führen.</li>';
            fehlersammlung = true;
          }
          if (rueckgabe.match(/STUFE/)) {
            meldung += '<li>Die zugeordnete Stufe existiert nicht im angegebenen Schuljahr.</li>';
            fehlersammlung = true;
          }
          if (rueckgabe.match(/FAECHER/)) {
            meldung += '<li>Das zugeordnete Fach existiert nicht.</li>';
            fehlersammlung = true;
          }
          if (rueckgabe.match(/KLASSEN/)) {
            meldung += '<li>Mindestens eine zugeordnete Klasse existiert nicht in diesem Schuljahr.</li>';
            fehlersammlung = true;
          }

          if (fehlersammlung) {
            cms_fehlerbehandlung(rueckgabe);
            cms_meldung_an('fehler', 'Fehlerhafte Eingaben', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
          }
          else {
            cms_fehlerbehandlung(rueckgabe);
          }
        }
    	}

    	cms_ajaxanfrage (false, eingaben, anfragennachbehandlung);
    }
  }
}

function cms_gruppen_bearbeiten_speichern (art) {
  cms_laden_an('Gruppe bearbeiten', 'Die Eingaben werden überprüft.');

  var eingaben = cms_gruppen_eingabenpruefung(art);

  if (eingaben != '-') {
    eingaben.append('anfragenziel', '224');
    eingaben.append('art', art);
    cms_laden_an('Gruppe bearbeiten', 'Änderungen werden gespeichert.');

    function anfragennachbehandlung(rueckgabe) {
  		if (rueckgabe == 'ERFOLG') {
        cms_meldung_an('erfolg', 'Gruppe bearbeiten', '<p>Die Änderungen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Gruppen/'+art.replace(' ', '_')+'\');">Zurück zur Übersicht</span></p>');
      }
  		else {
        var fehlersammlung = false;
        var meldung = "<p>Die Eingaben sind fehlerhaft:</p><ul>";
        if (rueckgabe.match(/DOPPELT/)) {
          meldung += '<li>Es gibt in diesem Schuljahr oder schuljahrübergreifend bereits eine Gruppe mit dieser Bezeichnung.</li>';
          fehlersammlung = true;
        }
        if (rueckgabe.match(/SCHULJAHR/)) {
          meldung += '<li>Das eingegebene Schuljahr existert nicht.</li>';
          fehlersammlung = true;
        }
        if (rueckgabe.match(/MITGLIEDER/)) {
          meldung += '<li>Mindestens ein eingegebenes Mitglied darf dieser Gruppe nicht angehören.</li>';
          fehlersammlung = true;
        }
        if (rueckgabe.match(/AUFSICHT/)) {
          meldung += '<li>Mindestens eine eingegebene Aufsichtsperson darf die Aufsicht nicht führen.</li>';
          fehlersammlung = true;
        }
        if (rueckgabe.match(/STUFE/)) {
          meldung += '<li>Die zugeordnete Stufe existiert nicht im angegebenen Schuljahr.</li>';
          fehlersammlung = true;
        }
        if (rueckgabe.match(/FAECHER/)) {
          meldung += '<li>Das zugeordnete Fach existiert nicht.</li>';
          fehlersammlung = true;
        }
        if (rueckgabe.match(/KLASSEN/)) {
          meldung += '<li>Mindestens eine zugeordnete Klasse existiert nicht in diesem Schuljahr.</li>';
          fehlersammlung = true;
        }

        if (fehlersammlung) {
          cms_meldung_an('fehler', 'Fehlerhafte Eingaben', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
        }
        else {
          cms_fehlerbehandlung(rueckgabe);
        }
      }
  	}

  	cms_ajaxanfrage (false, eingaben, anfragennachbehandlung);
  }
}


function cms_gruppen_eingabenpruefung(art) {
  var bezeichnung = document.getElementById('cms_gruppe_bezeichnung').value;
  var sichtbar = document.getElementById('cms_gruppe_sichtbar').value;
  var chat = document.getElementById('cms_gruppe_chat').value;
  var schuljahr = document.getElementById('cms_gruppe_schuljahr').value;
  var icon = document.getElementById('cms_gruppe_icon').value;
  var mitglieder = document.getElementById('cms_gruppe_mitglieder_personensuche_gewaehlt').value;
  var vorsitz = document.getElementById('cms_gruppe_mitglieder_personensuche_gewaehlt2').value;
  var aufsicht = document.getElementById('cms_gruppe_aufsicht_personensuche_gewaehlt').value;
  if (art == 'Stufen') {
    var reihenfolge = document.getElementById('cms_gruppe_reihenfolge').value;
    var tagebuch = document.getElementById('cms_gruppe_tagebuch').value;
    var gfs = document.getElementById('cms_gruppe_gfs').value;
  }
  if (art == 'Klassen') {
    var stundenplanextern = document.getElementById('cms_gruppe_stundenplan_extern').value;
    var klassenbezextern = document.getElementById('cms_gruppe_klassenbez_extern').value;
    var stufenbezextern = document.getElementById('cms_gruppe_stufenbez_extern').value;
    var stufe = document.getElementById('cms_gruppe_stufe').value;
    var faecher = document.getElementById('cms_gruppe_faecher').value;
  }
  if (art == 'Kurse') {
    var kurzbezeichnung = document.getElementById('cms_gruppe_kurzbezeichnung').value;
    var stufe = document.getElementById('cms_gruppe_stufe').value;
    var fach = document.getElementById('cms_gruppe_fach').value;
    var klassen = document.getElementById('cms_gruppe_klassen').value;
    var kursbezextern = document.getElementById('cms_gruppe_kursbez_extern').value;
  }
  var meldung = '<p>Die Eingaben sind fehlerhaft:</p><ul>';
  var fehler = false;

  if ((sichtbar != '0') && (sichtbar != '1') && (sichtbar != '2') && (sichtbar != '3')) {
    fehler = true;
    meldung += '<li>Die Eingabe für die Sichtbarkeit der Gruppe ist ungültig.</li>';
  }

  if (!cms_check_toggle(chat)) {
    fehler = true;
    meldung += '<li>Die Eingabe für die Aktivität des Chats der Gruppe ist ungültig.</li>';
  }

  if (art == 'Stufen') {
    if (!cms_check_ganzzahl(reihenfolge)) {
      fehler = true;
      meldung += '<li>Die Eingabe für die Reihenfolge ist ungültig.</li>';
    }
    if (!cms_check_toggle(tagebuch)) {
      fehler = true;
      meldung += '<li>Die Eingabe für das Anlegen von Tagebüchern ist ungültig.</li>';
    }
    if (!cms_check_toggle(gfs)) {
      fehler = true;
      meldung += '<li>Die Eingabe für das Anlegen von GFSen ist ungültig.</li>';
    }
  }

  if (art == 'Kurse') {
    if (!cms_check_titel(kurzbezeichnung)) {
      fehler = true;
      meldung += '<li>Die Kurzbezeichnung der Gruppe darf nur lateinische Buchstaben, Umlaute, »ß«, arabische Zahlen sowie » « und »-« enthalten.</li>';
    }
  }

  if (!cms_check_titel(bezeichnung)) {
    fehler = true;
    meldung += '<li>Die Bezeichnung der Gruppe darf nur lateinische Buchstaben, Umlaute, »ß«, arabische Zahlen sowie » « und »-« enthalten.</li>';
  }

  var mfehler = false;
  var formulardaten = new FormData();
  if (mitglieder.length > 0) {
    var mit = mitglieder.substr(1);
    var wert = "";
    mit = mit.split('|');
    for (var  i = 0; i<mit.length; i++) {
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_upload_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'dateiupload', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_download_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'dateidownload', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_loeschen_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'dateiloeschen', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_umbenennen_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'dateiumbenennen', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_termine_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'termine', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_blogeintraege_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'blogeintraege', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_chatten_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'chatten', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_chat_loeschen_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'nachrichtloeschen', wert);} else {mfehler = true;}} else {mfehler = true;}
      wert = document.getElementById('cms_cms_gruppe_mitglieder_personensuche_mitglieder_chat_bannen_'+mit[i]).value;
      if (wert) {if (cms_check_toggle(wert)) {formulardaten.append('mitglieder'+mit[i]+'nutzerstummschalten', wert);} else {mfehler = true;}} else {mfehler = true;}
    }
  }

  if (mfehler) {
    fehler = true;
    meldung += '<li>Die Eingabe der Mitgliederrechte ist fehlerhaft.</li>';
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Fehlerhafte Eingaben', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
    return '-';
  }
  else {
    formulardaten.append('bezeichnung', bezeichnung);
    formulardaten.append('sichtbar', sichtbar);
    formulardaten.append('chat', chat);
    formulardaten.append('schuljahr', schuljahr);
    formulardaten.append('icon', icon);
    formulardaten.append('mitglieder', mitglieder);
    formulardaten.append('vorsitz', vorsitz);
    formulardaten.append('aufsicht', aufsicht);
    if (art == 'Stufen') {
      formulardaten.append('reihenfolge', reihenfolge);
      formulardaten.append('tagebuch', tagebuch);
      formulardaten.append('gfs', gfs);
    }
    if (art == 'Klassen') {
      formulardaten.append('stundenplanextern', stundenplanextern);
      formulardaten.append('klassenbezextern', klassenbezextern);
      formulardaten.append('stufenbezextern', stufenbezextern);
      formulardaten.append('stufe', stufe);
      formulardaten.append('faecher', faecher);
    }
    if (art == 'Kurse') {
      formulardaten.append('kurzbezeichnung', kurzbezeichnung);
      formulardaten.append('stufe', stufe);
      formulardaten.append('fach', fach);
      formulardaten.append('klassen', klassen);
      formulardaten.append('kursbezextern', kursbezextern);
    }
    return formulardaten;
  }

}



function cms_gruppen_loeschen_anzeigen (gruppe, bezeichnung, id) {
	cms_meldung_an('warnung', 'Kategorie löschen', '<p>Soll die Gruppe <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p><p>Mit dieser Gruppe verknüpfte interne Termine und Blogeinträge werden gelöscht.</p><p>Verknüpfungen zu öffentlichen Terminen, Blogeinträgen und Galerien werden entfernt. Alle für diese Gruppe gespeicherten Daten gehen unwiederbringlich verloren.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_gruppen_loeschen(\''+gruppe+'\', \''+bezeichnung+'\', \''+id+'\')">Löschen</span></p>');
}


function cms_gruppen_loeschen (art, bezeichnung, id) {
	cms_laden_an('Gruppe löschen', 'Die Gruppe <b>'+bezeichnung+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",   id);
	formulardaten.append("name", art);
	formulardaten.append("anfragenziel", 	'222');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == 'ERFOLG') {
      var aschuljahr = document.getElementById('cms_gruppen_schuljahr_aktiv').value;
      if ((art == 'Fachschaften') || (art == 'Gremien')) {
        lehrerdaten.append("id",   id);
      	lehrerdaten.append("name", art);
      	lehrerdaten.append("anfragenziel", 	'');
        cms_lehrerdatenbankzugangsdaten_schicken(lehrerdaten);

        function lehrerservernachbehandlung(rueckgabe) {
          if (rueckgabe == "ERFOLG") {
            cms_gruppen_listeausgeben(art, aschuljahr);
            cms_meldung_an('erfolg', 'Gruppe löschen', '<p>Die Gruppe wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
          }
          else {cms_fehlerbehandlung(rueckgabe);}
        }

        cms_ajaxanfrage (false, lehrerdaten, lehrerservernachbehandlung, CMS_LN_DA);
      }
      else {
        cms_gruppen_listeausgeben(art, aschuljahr);
        cms_meldung_an('erfolg', 'Gruppe löschen', '<p>Die Gruppe wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
      }
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppen_bearbeiten_vorbereiten(art, id) {
  cms_laden_an('Gruppe bearbeiten', 'Informationen werden gesammelt, damit die Gruppe bearbeitet werden kann.');

  var formulardaten = new FormData();
  formulardaten.append("id",   id);
  formulardaten.append("name", art);
  formulardaten.append("anfragenziel", 	'223');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == 'ERFOLG') {
      cms_link('Schulhof/Verwaltung/Gruppen/'+art.replace(' ', '_')+'/Gruppe_bearbeiten');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_kategorie_icon_waehlen(id, nr) {
	var anzahl = document.getElementById(id+'_icon_anzahl').value;
	if (nr % 1 != 0) {nr = 0;}
	if (nr > anzahl) {nr = 0;}

	var dateiname = 'standard.png';
	if (document.getElementById(id+'_icon_'+nr+'_name')!== null) {
		dateiname = document.getElementById(id+'_icon_'+nr+'_name').value;
	}

	// Alle deaktivieren
	for (var i=1; i<=anzahl; i++) {
		feld = document.getElementById(id+'_icon_'+i);
		feld.className = "cms_kategorie_icon";
	}
	document.getElementById(id+'_icon_'+nr).className = "cms_kategorie_icon_aktiv";
	document.getElementById(id+'_icon').value = dateiname;
	document.getElementById(id+'_icon_vorschau').src = 'res/gruppen/gross/'+dateiname;
	cms_ausblenden(id+'_icon_auswahl');
}

function cms_gruppe_reihenfolge_laden() {
  cms_laden_an('Reihenfolgen laden', 'Informationen werden gesammelt.');
  var schuljahr = document.getElementById('cms_gruppe_schuljahr').value;
  var gewaehlt = document.getElementById('cms_gruppe_reihenfolge').value;

  var formulardaten = new FormData();
  formulardaten.append("schuljahr",   schuljahr);
  formulardaten.append("gewaehlt", gewaehlt);
  formulardaten.append("anfragenziel", 	'241');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe.match(/^<option/)) {
      document.getElementById('cms_gruppe_reihenfolge').innerHTML = rueckgabe;
      cms_meldung_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppe_stufen_laden() {
  cms_laden_an('Stufen laden', 'Informationen werden gesammelt.');
  var schuljahr = document.getElementById('cms_gruppe_schuljahr').value;
  var gewaehlt = document.getElementById('cms_gruppe_stufe').value;

  var formulardaten = new FormData();
  formulardaten.append("schuljahr",   schuljahr);
  formulardaten.append("gewaehlt", gewaehlt);
  formulardaten.append("anfragenziel", 	'12');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe.match(/^<option/)) {
      document.getElementById('cms_gruppe_stufe').innerHTML = rueckgabe;
      cms_meldung_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppe_klassenfaecher_laden() {
  cms_laden_an('Fächer laden', 'Informationen werden gesammelt.');
  var schuljahr = document.getElementById('cms_gruppe_schuljahr').value;
  var faecher = document.getElementById('cms_gruppe_faecher');
  var faecherF = document.getElementById('cms_grupppe_faecher_F');

  faecher.value = "";

  var formulardaten = new FormData();
  formulardaten.append("schuljahr",   schuljahr);
  formulardaten.append("anfragenziel", 	'350');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe.match(/^<span/)) {
      faecherF.innerHTML = rueckgabe;
      cms_meldung_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppe_kursefaecher_laden() {
  cms_laden_an('Fächer laden', 'Informationen werden gesammelt.');
  var schuljahr = document.getElementById('cms_gruppe_schuljahr').value;
  var faecher = document.getElementById('cms_gruppe_fach');

  var formulardaten = new FormData();
  formulardaten.append("schuljahr",   schuljahr);
  formulardaten.append("anfragenziel", 	'351');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe.match(/^<option/)) {
      faecher.innerHTML = rueckgabe;
      cms_meldung_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppe_klassen_laden() {
  document.getElementById('cms_gruppe_klassen_F').innerHTML = '<div class=\"cms_meldung_laden\">'+cms_ladeicon()+'<p class=\"cms_notiz\">Klassen dieses Schuljahres werden geladen</p></div>'
  var schuljahr = document.getElementById('cms_gruppe_schuljahr').value;
  var stufe = document.getElementById('cms_gruppe_stufe').value;

  var formulardaten = new FormData();
  formulardaten.append("schuljahr",   schuljahr);
  formulardaten.append("stufe",   stufe);
  formulardaten.append("anfragenziel", 	'13');

  function anfragennachbehandlung(rueckgabe) {
    if ((rueckgabe.match(/^<span/)) || (rueckgabe.match(/^<input/))) {
      document.getElementById('cms_gruppe_klassen_F').innerHTML = rueckgabe;
      cms_meldung_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppe_klassenaktualisieren() {
  var alleklassen = document.getElementById('cms_gruppe_klassen_alle').value;
  var klassen = document.getElementById('cms_gruppe_klassen');
  var gewaehlt = "";
  alleklassen = (alleklassen.substr(1)).split('|');
  for (var i=0; i<alleklassen.length; i++) {
    var pruefwert = document.getElementById('cms_gruppe_klassen_'+alleklassen[i]).value;
    if (pruefwert == '1') {
      gewaehlt += '|'+alleklassen[i];
    }
  }
  klassen.value = gewaehlt;
}

function cms_gruppe_faecheraktualisieren() {
  var allefaecher = document.getElementById('cms_gruppe_faecher_alle').value;
  var faecher = document.getElementById('cms_gruppe_faecher');
  var gewaehlt = "";
  allefaecher = (allefaecher.substr(1)).split('|');
  for (var i=0; i<allefaecher.length; i++) {
    var pruefwert = document.getElementById('cms_gruppe_faecher_'+allefaecher[i]).value;
    if (pruefwert == '1') {
      gewaehlt += '|'+allefaecher[i];
    }
  }
  faecher.value = gewaehlt;
}


function cms_gruppe_personenausklassen() {
  cms_laden_an('Mitglieder übernehmen', 'Informationen werden gesammelt.');
  var klassen = document.getElementById('cms_gruppe_klassen').value;

  var formulardaten = new FormData();
  formulardaten.append("klassen",   klassen);
  formulardaten.append("anfragenziel", 	'14');

  function anfragennachbehandlung(rueckgabe) {
    if ((rueckgabe.match(/^|/)) || (rueckgabe.length == 0)) {
      var personen = (rueckgabe.substr(1)).split('|');
      for (var i = 0; i < personen.length; i++) {
        var pdetails = personen[i].split(';');
        cms_personensuche_wahl_mitglieder('cms_gruppe_mitglieder', pdetails[0], pdetails[1], pdetails[2], 'Kurse');
      }
      cms_meldung_aus();
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_chatmeldung_loeschen(gruppe, id) {
  cms_laden_an('Chatmeldung löschen', 'Informationen werden gesammelt.');

  var formulardaten = new FormData();
  formulardaten.append("id",              id);
  formulardaten.append("gruppe",          gruppe);
  formulardaten.append("anfragenziel", 	  '272');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {cms_link("Schulhof/Aufgaben/Chatmeldungen");}
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_chatmeldung_nachricht_loeschen(gruppe, id) {
  cms_laden_an('Nachricht löschen', 'Informationen werden gesammelt.');

  var formulardaten = new FormData();
  formulardaten.append("id",              id);
  formulardaten.append("gruppe",          gruppe);
  formulardaten.append("anfragenziel", 	  '275');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {cms_link("Schulhof/Aufgaben/Chatmeldungen");}
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
