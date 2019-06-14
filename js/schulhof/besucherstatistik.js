function cms_besucherstatistik_schulhof_zeitraum(jahr, jahrS, monatS, jahrE, monatE, modus) {
  $(".cms_besucherstatistik_toggle").removeClass("cms_toggle_aktiv");
  $("#cms_besucherstatistik_zeitraum_toggle_"+jahr).addClass("cms_toggle_aktiv");
  var formulardaten = new FormData();
  formulardaten.append("start",         JSON.stringify({jahr: jahrS, monat: monatS}));
  formulardaten.append("ende",          JSON.stringify({jahr: jahrE, monat: monatE}));
  if(modus)
    formulardaten.append("modus",       modus);
  formulardaten.append("anfragenziel", 	'257');

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "FEHLER" || rueckgabe == "BERECHTIGUNG") {
      cms_fehlerbehandlung(rueckgabe);
    } else {
      $("#besucherstatistik").html(rueckgabe);
    }
  }
  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
function cms_besucherstatistik_website_zeitraum(typ, jahr, jahrS, monatS, jahrE, monatE, modus) {
  $(".cms_besucherstatistik_toggle").removeClass("cms_toggle_aktiv");
  $("#cms_besucherstatistik_zeitraum_toggle_"+jahr).addClass("cms_toggle_aktiv");
  var formulardaten = new FormData();
  formulardaten.append("start",         JSON.stringify({jahr: jahrS, monat: monatS}));
  formulardaten.append("ende",          JSON.stringify({jahr: jahrE, monat: monatE}));
  formulardaten.append("typ",           typ);
  if(modus)
    formulardaten.append("modus",       modus);
  formulardaten.append("anfragenziel", 	'260');

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "FEHLER" || rueckgabe == "BERECHTIGUNG") {
      cms_fehlerbehandlung(rueckgabe);
    } else {
      $("#besucherstatistik").html(rueckgabe);
    }
  }
  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
