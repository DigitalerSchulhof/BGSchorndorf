function cms_schulhof_aktualisieren_vorbereiten() {
  cms_meldung_an('warnung', 'Digitalen Schulhof aktualisieren', '<p>Soll der Schulhof wirklich aktualisiert werden?<br>Die gesamte Website wird für wenige Minuten komplett deaktiviert und die neue Version wird hochgeladen.</p>', '<p><span class="cms_button_nein" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_ja" onclick="cms_schulhof_aktualisieren()">Schulhof aktualisieren</span></p>');
}

function cms_schulhof_aktualisieren() {

  var cms_schulhof = function() {
    cms_laden_an("Digitalen Schulhof aktualisieren (1/2)", "Der Digitale Schulhof wird aktualisiert.<br>Dies kann einige Minuten dauern...<br>Der Digitale Schulhof ist derweil nicht errichbar.<br><br><b>Fortschritt:</b><br><span id=\"aktualisieren_fortschritt\"></span>");

    var anfrage = new XMLHttpRequest();
    var formulardaten = new FormData();
    formulardaten.append("anfragenziel", '387');

    anfrage.onreadystatechange = function() {
      rueckgabe = anfrage.responseText;
      if (anfrage.readyState==3 && anfrage.status==200) {
        rueckgabe = rueckgabe.substring(16);
        $("#aktualisieren_fortschritt").html(rueckgabe);
      }
      if (anfrage.readyState==4 && anfrage.status==200) {
        if (rueckgabe.match(/ERFOLG$/)) {
          cms_lehrerdateien();
        } else if (rueckgabe.match(/SICHER$/)) {
          cms_meldung_an('fehler', 'Digitalen Schulhof aktualisieren', '<p>Es ist ein unbekannter Fehler aufgetreten.<br>Bitte den <a href="Website/Feedback">Administrator informieren</a>.<br>Die Website wurde wiederhergestellt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">Zurück</span></p>');
        }
        else {
          cms_fehlerbehandlung(rueckgabe);
        }
      }
    }

    anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
    anfrage.send(formulardaten);
  }
  var cms_lehrerdateien = function() {
    cms_laden_an("Digitalen Schulhof aktualisieren (2/2)", "Die Lehrerdateien werden aktualisiert.<br>Dies kann einige Minuten dauern...<br>Das Lehrernetz ist derweil nicht errichbar.<br><br><b>Fortschritt:</b><br><span id=\"aktualisieren_fortschritt_l\"></span>");

    var anfrage = new XMLHttpRequest();
    var formulardaten = new FormData();
    cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("anfragenziel", 	'40' );

    anfrage.onreadystatechange = function() {
      rueckgabe = anfrage.responseText;
      if (anfrage.readyState==3 && anfrage.status==200) {
        rueckgabe = rueckgabe.substring(2);
        $("#aktualisieren_fortschritt_l").html(rueckgabe);
      }
      if (anfrage.readyState==4 && anfrage.status==200) {
        if (rueckgabe.match(/ERFOLG$/)) {
          cms_meldung_an('erfolg', 'Digitalen Schulhof aktualisieren', '<p>Der Digitale Schulhof wurde vollständig aktualisiert!</p>', '<p><span class="cms_button" onclick="location.reload()">OK</span></p>');
        } else if (rueckgabe.match(/SICHER$/)) {
          cms_meldung_an('fehler', 'Digitalen Schulhof aktualisieren', '<p>Es ist ein unbekannter Fehler aufgetreten.<br>Bitte den <a href="Website/Feedback">Administrator informieren</a>.<br>Die Dateien wurde wiederhergestellt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">Zurück</span></p>');
        }
        else {
          cms_fehlerbehandlung(rueckgabe);
        }
      }
    }

    anfrage.open("POST",CMS_LN_DA+"php/oeffentlich/anfragen/anfrage.php",true);
    anfrage.send(formulardaten);
  }
  cms_schulhof();
}
