function cms_schulhof_aktualisieren_vorbereiten() {
  cms_meldung_an('warnung', 'Schulhof', '<p>Soll der Schulhof wirklich aktualisiert werden?<br>Die gesamte Website wird für wenige Minuten komplett deaktiviert und die neue Version wird hochgeladen.</p>', '<p><span class="cms_button_nein" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_ja" onclick="cms_schulhof_aktualisieren()">Schulhof aktualisieren</span></p>');
}

function cms_schulhof_aktualisieren() {
  cms_laden_an("Schulhof aktualisieren", "Der Digitale Schulhof wird aktualisiert.<br>Dies kann einige Minuten dauern...");

  var formulardaten = new FormData();
  formulardaten.append("anfragenziel", 	'387' );
  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Schulhof aktualisieren', '<p>Der Digitale Schulhof wurde aktualisiert!</p>', '<p><span class="cms_button" onclick="location.reload()">OK</span></p>');
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
