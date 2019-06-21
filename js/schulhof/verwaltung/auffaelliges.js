function cms_auffaelliges_details(id) {
  cms_laden_an('Details sehen', 'Die notwendigen Daten werden gesammelt.');

  var formulardaten = new FormData();
  formulardaten.append("id", id);

  formulardaten.append("anfragenziel", 	'336');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Aufgaben/Auffälliges/Details');
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_auffaelliges_status_setzen(id, status) {
  cms_laden_an('Status setzen', 'Wird verarbeitet...');

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("status", status);

  formulardaten.append("anfragenziel", 	'338');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link("Schulhof/Aufgaben/Auffälliges");
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_auffaelliges_loeschen(id) {
  cms_auffaelliges_status_setzen(id, -1);
}

function cms_auffaelliges_notizen_speichern(id) {
  cms_laden_an('Änderungen speichren', 'Die Daten werden verarbeitet...');
  var notizen = document.getElementById("cms_auffaelliges_notizen").value;
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("notizen", notizen);
  formulardaten.append("anfragenziel", 	'337');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Aufgaben/Auffälliges/Details');
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
