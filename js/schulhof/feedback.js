function cms_fehlermeldung_details(id) {
  cms_laden_an('Fehlermeldung ansehen', 'Die notwendigen Daten werden gesammelt.');

  var formulardaten = new FormData();
  formulardaten.append("id", id);

  formulardaten.append("anfragenziel", 	'258');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Website/Fehlermeldungen/Details');
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
function cms_fehlermeldung_status_setzen(id, status) {
  cms_laden_an('Fehlermeldung ändern', 'Wird verarbeitet...');

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("status", status);

  formulardaten.append("anfragenziel", 	'258');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link("Schulhof/Website/Fehlermeldungen");
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_fehlermeldung_notizen_speichern(id) {
  cms_laden_an('Änderungen speichren', 'Die Daten werden verarbeitet...');
  var notizen = document.getElementById("cms_fehlermeldung_notizen").value;
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("notizen", notizen);
  formulardaten.append("anfragenziel", 	'258');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Website/Fehlermeldungen/Details');
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_feedback_details(id) {
  cms_laden_an('Feedback ansehen', 'Die notwendigen Daten werden gesammelt.');

  var formulardaten = new FormData();
  formulardaten.append("id", id);

  formulardaten.append("anfragenziel", 	'261');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Website/Feedback/Details');
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
function cms_feedback_loeschen(id) {
  cms_laden_an('Feedback löschen', 'Wird verarbeitet...');

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("status", 0);

  formulardaten.append("anfragenziel", 	'261');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link("Schulhof/Website/Feedback");
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
