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

function cms_emoticon_loeschen(emoticon) {
  var formulardaten = new FormData();
  formulardaten.append("emoticon", emoticon);

  formulardaten.append("anfragenziel", 	'263');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      $("#cms_emoticons_liste_"+emoticon).remove();
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }
  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_emoticons_speichern() {
  cms_laden_an('Einstellungen speichern', 'Die Einstellungen werden gespeichert.');
  var emoticons = $(".cms_emoticons_liste tr:not(:first-of-type)");
  var w = {};
  $.each(emoticons, function(i, e) {
    w[$(this).data("emoticon")] = $("#cms_emoticon_"+$(this).data("emoticon")).val();
  });

  var formulardaten = new FormData();
  $.each(w, function(em, v) {
    formulardaten.append(em + "_aktiv", v);
  });

  formulardaten.append("anfragenziel", "263");

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_laden_aus();
    }else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
