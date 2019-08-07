function cms_reaktionen_speichern() {
  cms_laden_an('Einstellungen speichern', 'Die Einstellungen werden gespeichert.');

  var b = $("#cms_reaktionen_b").val();
  var t = $("#cms_reaktionen_t").val();
  var g = $("#cms_reaktionen_g").val();

  var formulardaten = new FormData();
  formulardaten.append("b", b);
  formulardaten.append("t", t);
  formulardaten.append("g", g);

  formulardaten.append("anfragenziel", 	'262');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_laden_aus();
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
