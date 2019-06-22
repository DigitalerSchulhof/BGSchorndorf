function cms_fehlermeldung_einhanden() {
  var t = document.getElementById("cms_fehlermeldung_t").value;
  var titel = document.getElementById("cms_fehlermeldung_titel").value;
  var beschreibung = document.getElementById("cms_fehlermeldung_beschreibung").value;
  var okay = document.getElementById("cms_fehlermeldung_okay").value;

  var meldung = '<p>Der Fehler konnte nicht gemeldet werden, denn ...</p><ul>';
  var fehler = false;

  if (!titel) {
    fehler = true;
    meldung += '<li>der Titel ist unvollständig.</li>';
  }

  if (!beschreibung) {
    fehler = true;
    meldung += '<li>die Beschreibung ist unvollständig.</li>';
  }
  if (okay != 1) {
    fehler = true;
    meldung += '<li>die Sammlung von technischen Daten muss erlaubt werden.</li>';
  }


  var formulardaten = new FormData();
  formulardaten.append("t", t);
  formulardaten.append("titel", titel);
  formulardaten.append("beschreibung", beschreibung);
  formulardaten.append("anfragenziel", '259');

  if (!fehler) {
    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {cms_link('Website/Feedback/Danke!');}
      else {cms_fehlerbehandlung(rueckgabe);}
    }
    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
  else {
    cms_meldung_an('fehler', 'Fehler melden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
}

function cms_feedback_einhanden() {
  var name = document.getElementById("cms_feedback_name").value;
  var feedback = document.getElementById("cms_feedback_beschreibung").value;

  var meldung = '<p>Ihr Feedback konnte nicht gemeldet werden, denn ...</p><ul>';
  var fehler = false;

  if (!name) {
    fehler = true;
    meldung += '<li>Ihr Name ist unvollständig.</li>';
  }

  var formulardaten = new FormData();
  formulardaten.append("name", name);
  formulardaten.append("feedback", feedback);
  formulardaten.append("anfragenziel", '259');

  if (!fehler) {
    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {cms_link('Website/Feedback/Danke!');}
      else {cms_fehlerbehandlung(rueckgabe);}
    }
    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
  else {
    cms_meldung_an('fehler', 'Fehler melden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
}
