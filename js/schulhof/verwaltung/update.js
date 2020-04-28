function cms_schulhof_aktualisieren_vorbereiten() {
  cms_meldung_an('warnung', 'Digitalen Schulhof aktualisieren', '<p>Soll der Schulhof wirklich aktualisiert werden?<br>Die gesamte Website wird für wenige Minuten komplett deaktiviert und die neue Version wird hochgeladen.</p>', '<p><span class="cms_button_nein" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_ja" onclick="cms_schulhof_aktualisieren()">Schulhof aktualisieren</span></p>');
}

function cms_schulhof_aktualisieren() {
  cms_laden_an("Digitalen Schulhof aktualisieren", "Der Digitale Schulhof wird aktualisiert. (1/2)<br>Dies kann einige Minuten dauern...");

  var formulardatenl = new FormData();
  cms_lehrerdatenbankzugangsdaten_schicken(formulardatenl);
  formulardatenl.append("anfragenziel", 	'40');
  function anfragennachbehandlungl(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Digitalen Schulhof aktualisieren', '<p>Der Digitale Schulhof wurde vollständig aktualisiert!</p>', '<p><span class="cms_button" onclick="location.reload()">OK</span></p>');
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  var formulardaten = new FormData();
  formulardaten.append("anfragenziel", 	'387' );
  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_laden_an("Digitalen Schulhof aktualisieren", "Die Dateien im Lehrernetz werden aktualisiert. (2/2)<br>Dies kann einige Minuten dauern...");
      cms_ajaxanfrage (false, formulardatenl, anfragennachbehandlungl, CMS_LN_DA);
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
