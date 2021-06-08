function cms_coronatest_vorbereiten(gruppe, id) {
  cms_laden_an('Test erfassen', 'Informationen werden gesammelt, damit die Tests erfasst werden können.');

  var formulardaten = new FormData();
  formulardaten.append("id",   id);
  formulardaten.append("gruppe", gruppe);
  formulardaten.append("anfragenziel", 	'432');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == 'ERFOLG') {
      cms_link('Schulhof/Verwaltung/Coronatest/Test_einsehen');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_coronatest_speichern() {
  cms_laden_an('Test erfassen', 'Die Eingaben werden überprüft.');
	var personen = document.getElementById('cms_testpersonen').value;
	var ps = personen.split(",");

  var meldung = '<p>Der Test konnte nicht gespeichert werden, denn ...</p><ul>';
  var fehler = false;

  var anz = 0;

  var formulardaten = new FormData();
  formulardaten.append("personen", personen);
  if (personen.length > 0) {
    var feldfehler = false;
    for (var i=0; i<ps.length; i++) {
      feld = document.getElementById("cms_testerfassen_"+ps[i]);
      if (!feld) {
        feldfehler = true;
        alert("cms_testerfassen_"+ps[i]);
      }
      else {
        var wert = feld.value;
        if ((wert == 'nt') || (wert == 't') || (wert == 'b')) {
          formulardaten.append("test_"+ps[i], wert);
          if ((wert == 't') || (wert == 'b')) {
            anz++;
          }
        } else {
            feldfehler = true;
            alert("cms_testerfassen2_"+ps[i]);
        }
      }
    }
  }

  if (anz == 0) {
    meldung += '<li>es wurde niemand getestet.</li>';
		fehler = true;
  }

  if (feldfehler) {
    meldung += '<li>mindestens eine Testeingabe ist ungültig.</li>';
		fehler = true;
  }

	if (!fehler) {
		formulardaten.append("anfragenziel", '433');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Test erfassen', '<p>Der Test wurde gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Coronatest/Test_einsehen\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Test erfassen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}
