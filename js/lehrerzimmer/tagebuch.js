function cms_tagebuchmeldung_laden() {
  var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("anfragenziel", '31');

	function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe != 'keine') {
      var feld = document.getElementById('cms_tagebuchneuigkeit_inhalt');
      feld.innerHTML = '<h4>Tagebucheinträge</h4><p>'+rueckgabe+'</p>';
    }
		else {
      var tagebuchmeldung = document.getElementById('cms_tagebuchneuigkeit');
      tagebuchmeldung.parentNode.removeChild(tagebuchmeldung);
    }
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_notfallzustand_anzeigen(wert) {
  if (wert == '1') {
    cms_meldung_notfall('warnung', 'Notfallzustand ausrufen', '<p><b>Achtung!</b> Sie sind dabei den Notfallzustand auszurufen. Alle Schüler, Lehrer, Verwaltungsangestellte und Externe im Schulhof werden angewiesen <b>das Schulgebäude umgehend zu verlassen</b>. Alle Lehrer werden zudem angewiesen, die Vollzähligkeit bzw. Abwesenheit einzelner ihre Schüler der Einsatzleitung zu melden!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_wichtig" onclick="cms_notfallzustand(\''+wert+'\')">Notfallzustand ausrufen</span></p>');
  }
  if (wert == '0') {
    cms_meldung_notfall('warnung', 'Notfallzustand beenden', '<p>Möchten Sie den Notfallzustand wirklich beenden? Alle Notfallmeldungen werden damit gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_wichtig" onclick="cms_notfallzustand(\''+wert+'\')">Notfallzustand beenden</span></p>');
  }
}

function cms_notfallzustand(wert) {
  var meldung = '<p>Die Notfallzustand kann nichg geändert werden, denn ...</p><ul>';
  var fehler = false;

  if (!cms_check_toggle(wert)) {
    meldung += '<li>die Eingabe für den aktuellen Notfallzustand ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
     cms_meldung_an ('fehler', 'Notfallzustand ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var formulardaten = new FormData();
  	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("notfall", wert);
    formulardaten.append("anfragenziel", '33');

  	function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == 'ERFOLG') {
        cms_link('Schulhof/Nutzerkonto');
      }
      else {
        cms_fehlerbehandlung(rueckgabe);
      }
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}


function cms_tagebuchdetails_laden() {
  var meldung = '<p>Die Tagebucheinträge können nicht geladen werden, denn ...</p><ul>';
  var kurse = document.getElementById('cms_tagebuch_kurse').value;
  var kursecheck = kurse;
  var freigabe = document.getElementById('cms_tagebuch_freigabe').value;
  if (kurse.substr(-2,2) == '|s') {kursecheck = kurse.substr(0,kurse.length-2);}

  var fehler = false;

  if (!cms_check_toggle(freigabe)) {
    meldung += '<li>die Auswahl zu den freigegebenen Einträgen ist ungültig.</li>'
    fehler = true;
  }
  if (!cms_check_liste(kursecheck)) {
    meldung += '<li>die Auswahl der Kurse ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
     cms_meldung_an ('fehler', 'Tagebucheinträge laden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var gewaehlt = "";
    var ausgeschlossen = "";
    if (kurse.length>0) {
      var kursetest = kurse.substr(1).split('|');
      for (var i=0; i<kursetest.length; i++) {
        if (document.getElementById('cms_tagebuch_kurs_'+kursetest[i]).value == 1) {gewaehlt += '|'+kursetest[i];}
        else {ausgeschlossen += '|'+kursetest[i];}
      }
    }

    alert(gewaehlt+"\n"+ausgeschlossen);

    var formulardaten = new FormData();
  	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("freigabe", freigabe);
    formulardaten.append("gewaehlt", gewaehlt);
    formulardaten.append("ausgeschlossen", ausgeschlossen);
    formulardaten.append("anfragenziel", '32');

  	function anfragennachbehandlung(rueckgabe) {
      document.getElementById('cms_persoenlichestagebuch').innerHTML = rueckgabe;
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}
