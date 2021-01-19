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
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

var cms_aktuell = {start: {jahr: 0, monat: 0}, ende: {jahr: 0, monat: 0}, typ: 0, modus: "letzte", geloescht: true, startseite: true};
function cms_besucherstatistik_website_zeitraum(typ, jahr, jahrS, monatS, jahrE, monatE, modus) {
  $(".cms_besucherstatistik_toggle").removeClass("cms_toggle_aktiv");
  $("#cms_besucherstatistik_zeitraum_toggle_"+jahr).addClass("cms_toggle_aktiv");
  var formulardaten = new FormData();

  cms_aktuell.start = {jahr: jahrS, monat: monatS};
  cms_aktuell.ende = {jahr: jahrE, monat: monatE};
  cms_aktuell.modus = modus;
  cms_aktuell.typ = typ;

  formulardaten.append("start",         JSON.stringify(cms_aktuell.start));
  formulardaten.append("ende",          JSON.stringify(cms_aktuell.ende));
  formulardaten.append("typ",           cms_aktuell.typ);
  formulardaten.append("geloescht",     cms_aktuell.geloescht);
  formulardaten.append("startseite",    cms_aktuell.startseite);
  if(cms_aktuell.modus)
    formulardaten.append("modus",       cms_aktuell.modus);
  formulardaten.append("anfragenziel", 	'260');

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "FEHLER" || rueckgabe == "BERECHTIGUNG") {
      cms_fehlerbehandlung(rueckgabe);
    } else {
      $("#besucherstatistik").html(rueckgabe);
    }
  }
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_besucherstatistik_website_startseite_toggle(typ) {
  cms_aktuell.startseite = !cms_aktuell.startseite;
  $("#cms_besucherstatistik_website_startseite_ausblenden").toggleClass("cms_toggle_aktiv");
  var formulardaten = new FormData();

  cms_aktuell.typ = typ;

  formulardaten.append("start",         JSON.stringify(cms_aktuell.start));
  formulardaten.append("ende",          JSON.stringify(cms_aktuell.ende));
  formulardaten.append("typ",           cms_aktuell.typ);
  formulardaten.append("geloescht",     cms_aktuell.geloescht);
  formulardaten.append("startseite",    cms_aktuell.startseite);
  if(cms_aktuell.modus)
    formulardaten.append("modus",       cms_aktuell.modus);
  formulardaten.append("anfragenziel", 	'260');

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "FEHLER" || rueckgabe == "BERECHTIGUNG") {
      cms_fehlerbehandlung(rueckgabe);
    } else {
      $("#besucherstatistik").html(rueckgabe);
    }
  }
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_besucherstatistik_website_geloescht_toggle(typ) {
  cms_aktuell.geloescht = !cms_aktuell.geloescht;
  $("#cms_besucherstatistik_website_geloescht_toggle").toggleClass("cms_toggle_aktiv");
  var formulardaten = new FormData();

  cms_aktuell.typ = typ;

  formulardaten.append("start",         JSON.stringify(cms_aktuell.start));
  formulardaten.append("ende",          JSON.stringify(cms_aktuell.ende));
  formulardaten.append("typ",           cms_aktuell.typ);
  formulardaten.append("geloescht",     cms_aktuell.geloescht);
  formulardaten.append("startseite",    cms_aktuell.startseite);
  if(cms_aktuell.modus)
    formulardaten.append("modus",       cms_aktuell.modus);
  formulardaten.append("anfragenziel", 	'260');

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "FEHLER" || rueckgabe == "BERECHTIGUNG") {
      cms_fehlerbehandlung(rueckgabe);
    } else {
      $("#besucherstatistik").html(rueckgabe);
    }
  }
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
