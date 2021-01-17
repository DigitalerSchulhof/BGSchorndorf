function cms_bedingt_loeschen(dis) {
  dis = $(dis);

  return dis.parents("tr").remove();
}

function cms_bedingt_recht(dis) {
  dis = $(dis);

  var rekpruefen = function(e) {
    var r = e.data("knoten");
    if(e.parent().parent().parent(".cms_recht").length)
      r = rekpruefen(e.parent().parent().parent(".cms_recht")) + "." + r;
    return r;
  };
  var recht = rekpruefen(dis.parent().parent(".cms_recht")).substring(1);

  if(dis.parent().parent(".cms_recht").is(".cms_hat_kinder"))
    recht+=".*";

  var add = $("<tr><td><input class=\"cms_bedingt_recht_recht\" type=\"hidden\" value=\""+recht+"\">"+recht+"</td><td><input onkeyup=\"cms_bedingte_bedingung_syntax_pruefen(this)\" class=\"cms_bedingt_bedingung\" type=\"text\"></td><td><span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_bedingt_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> <span style=\"display: none\" class=\"cms_syntax_ok cms_aktion_klein cms_button_ja\"><span class=\"cms_hinweis\">Syntax ist in Ordnung</span><img src=\"res/icons/klein/richtig.png\"></span><span class=\"cms_syntax_fehler cms_aktion_klein cms_button_nein\"><span class=\"cms_hinweis\">Syntaxfehler!</span><img src=\"res/icons/klein/falsch.png\"></span></td></tr>");

  var gui = add.find(".cms_bedingt_bedingung").parent("td").append("<div class=\"cms_bedingt_bedingung_gui\"></div>").find(".cms_bedingt_bedingung_gui");

  cms_bedingt_gui_optionen(gui);

  $("#cms_bedingte_rechte tbody").append(add);
}

function cms_bedingt_rolle(bez, rid) {
  var add = $("<tr><td><input class=\"cms_bedingt_rolle_rolle\" type=\"hidden\" value=\""+rid+"\">"+bez+"</td><td><input onkeyup=\"cms_bedingte_bedingung_syntax_pruefen(this)\" class=\"cms_bedingt_bedingung\" type=\"text\"></td><td><span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_bedingt_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> <span style=\"display: none\" class=\"cms_syntax_ok cms_aktion_klein cms_button_ja\"><span class=\"cms_hinweis\">Syntax ist in Ordnung</span><img src=\"res/icons/klein/richtig.png\"></span><span class=\"cms_syntax_fehler cms_aktion_klein cms_button_nein\"><span class=\"cms_hinweis\">Syntaxfehler!</span><img src=\"res/icons/klein/falsch.png\"></span></td></tr>");

  var gui = add.find(".cms_bedingt_bedingung").parent("td").append("<div class=\"cms_bedingt_bedingung_gui\"></div>").find(".cms_bedingt_bedingung_gui");

  cms_bedingt_gui_optionen(gui);

  $("#cms_bedingte_rollen tbody").append(add);
}

function cms_bedingte_rechte_speichern() {
  var tbody = $("#cms_bedingte_rechte>tbody");
  var bedingungen = {};
  tbody.find("tr").each(function() {
    var recht     = $(this).find(".cms_bedingt_recht_recht").val();
    var bedingung = $(this).find(".cms_bedingt_bedingung").val();
    var b         = bedingungen[recht] || [];
    b.push(bedingung);
    bedingungen[recht] = b;
  });

  var formulardaten = new FormData();

  formulardaten.append("bedingungen",   JSON.stringify(bedingungen));
  formulardaten.append("anfragenziel", 	'368');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Bedingte Rechtezuordnung', '<p>Die Bedingungen wurden gespeichert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
    } else if (rueckgabe == "SYNTAX") {
      cms_meldung_an('fehler', 'Syntaxfehler', '<p>Die Syntax einer Bedingung ist fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
    } else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_bedingte_rollen_speichern() {
  var tbody = $("#cms_bedingte_rollen>tbody");
  var bedingungen = {};
  tbody.find("tr").each(function() {
    var rolle     = $(this).find(".cms_bedingt_rolle_rolle").val();
    var bedingung = $(this).find(".cms_bedingt_bedingung").val();
    var b         = bedingungen[rolle] || [];
    b.push(bedingung);
    bedingungen[rolle] = b;
  });

  var formulardaten = new FormData();

  formulardaten.append("bedingungen",   JSON.stringify(bedingungen));
  formulardaten.append("anfragenziel", 	'369');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Bedingte Rollenezuordnung', '<p>Die Bedingungen wurden gespeichert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
    } else if (rueckgabe == "SYNTAX") {
      cms_meldung_an('fehler', 'Syntaxfehler', '<p>Die Syntax einer Bedingung ist fehlerhaft.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
    } else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_bedingte_bedingung_syntax_pruefen(dis) {
  dis = $(dis);
  var tr = dis.parents("tr");
  var bedingung = tr.find(".cms_bedingt_bedingung").val() || "";

  var formulardaten = new FormData();

  formulardaten.append("bedingung",      bedingung);
  formulardaten.append("anfragenziel", 	'380');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "FEHLER") {
      tr.find(".cms_syntax_ok").hide();
      tr.find(".cms_syntax_fehler").show();
    } else if(rueckgabe == "ERFOLG") {
      tr.find(".cms_syntax_ok").show();
      tr.find(".cms_syntax_fehler").hide();
    } else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }
  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

// Grafische Bedingungen
function cms_bedingt_gui_optionen(dis) {
  dis = $(dis);

  var box = $("<div class=\"cms_bedingt_gui_optionen\"></div>");

  box.append($("<span class=\"cms_aktion_klein cms_button_bedingt_logisch\"><span class=\"cms_hinweis\">Logisches Oder</span><img src=\"res/icons/klein/bedingte_rechte/logisch_oder.png\"></span>")
    .click(function() {
      cms_bedingt_gui_logisch_oder(box);
    })
  ).append(" ");

  box.append($("<span class=\"cms_aktion_klein cms_button_bedingt_logisch\"><span class=\"cms_hinweis\">Logisches Und</span><img src=\"res/icons/klein/bedingte_rechte/logisch_und.png\"></span>")
    .click(function() {
      cms_bedingt_gui_logisch_und(box);
    })
  ).append(" ");

  box.append($("<span class=\"cms_aktion_klein cms_button_bedingt_logisch\"><span class=\"cms_hinweis\">Logisches Nicht</span><img src=\"res/icons/klein/bedingte_rechte/logisch_nicht.png\"></span>")
    .click(function() {
      cms_bedingt_gui_logisch_nicht(box);
    })
  ).append(" ");

  box.append($("<span class=\"cms_aktion_klein cms_button_bedingt_bedingung\"><span class=\"cms_hinweis\">Bedingung</span><img src=\"res/icons/klein/bedingte_rechte/bedingung.png\"></span>")
    .click(function() {
      cms_bedingt_gui_bedingung(box);
    })
  ).append(" ");

  dis.append(box);

  return dis;
}

function cms_bedingt_gui_logisch_oder(dis) {
  dis = $(dis);

  var box = $("<div class=\"cms_bedingt_gui_logisch cms_bedingt_gui_logisch_oder\"></div>");

  box.append("<span class=\"cms_aktion_klein\" style=\"background-color: transparent; cursor: default;\"><span class=\"cms_hinweis\">Logisches Oder</span><img src=\"res/icons/klein/bedingte_rechte/logisch_oder.png\"></span><br>");
  box.append("<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_bedingt_gui_feld_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>");

  box.append(cms_bedingt_gui_optionen($("<div class=\"cms_bedingt_gui_logisch_feld\"></div>")));
  box.append(cms_bedingt_gui_optionen($("<div class=\"cms_bedingt_gui_logisch_feld\"></div>")));

  dis.replaceWith(box);

  cms_bedingt_gui_zu_string(box);
}

function cms_bedingt_gui_logisch_und(dis) {
  dis = $(dis);

  var box = $("<div class=\"cms_bedingt_gui_logisch cms_bedingt_gui_logisch_und\"></div>");

  box.append("<span class=\"cms_aktion_klein\" style=\"background-color: transparent; cursor: default;\"><span class=\"cms_hinweis\">Logisches Und</span><img src=\"res/icons/klein/bedingte_rechte/logisch_und.png\"></span><br>");
  box.append("<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_bedingt_gui_feld_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>");

  box.append(cms_bedingt_gui_optionen($("<div class=\"cms_bedingt_gui_logisch_feld\"></div>")));
  box.append(cms_bedingt_gui_optionen($("<div class=\"cms_bedingt_gui_logisch_feld\"></div>")));

  dis.replaceWith(box);

  cms_bedingt_gui_zu_string(box);
}

function cms_bedingt_gui_logisch_nicht(dis) {
  dis = $(dis);

  var box = $("<div class=\"cms_bedingt_gui_logisch cms_bedingt_gui_logisch_nicht\"></div>");

  box.append("<span class=\"cms_aktion_klein\" style=\"background-color: transparent; cursor: default;\"><span class=\"cms_hinweis\">Logisches Nicht</span><img src=\"res/icons/klein/bedingte_rechte/logisch_nicht.png\"></span><br>");
  box.append("<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_bedingt_gui_feld_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>");

  box.append(cms_bedingt_gui_optionen($("<div class=\"cms_bedingt_gui_logisch_feld\"></div>")));

  dis.replaceWith(box);

  cms_bedingt_gui_zu_string(box);
}

function cms_bedingt_gui_bedingung(dis) {
  dis = $(dis);

  var box = $("<div class=\"cms_bedingt_gui_bedingung\"></div>");

  var kriterium = $("<select class=\"cms_bedingt_gui_bedingung_feld\"><option selected style=\"display: none\" disabled>Kriterium</option></select>");
  var kriterien = {
    "zeit": "Zeit",
    "nutzer.id": "NutzerID",
    "nutzer.vorname": "Vorname",
    "nutzer.nachname": "Nachname",
    "nutzer.titel": "Titel",
    "nutzer.art": "Nutzerart",
    "nutzer.imln": "Im Lehrernetz",
    "nutzer.hatRolle": "Hat Rolle",
  }
  $.each(kriterien, function(k, v) {
    kriterium.append($("<option value=\""+k+"\">"+v+"</option>"));
  });

  kriterium.change(function() {
    var val = $(this).val();

    box.find(".cms_bedingt_gui_bedingung_feld").not($(this)).remove();

    if(["zeit", "nutzer.id"].includes(val)) {

      var operator = $("<select class=\"cms_bedingt_gui_bedingung_feld\"><option selected style=\"display: none\" disabled>Operation</option></select>");
      var operationen = {
        "==": "Gleich",
        "!=": "Ungleich",
        "<": "Kleiner als",
        ">": "Größer als"
      }
      $.each(operationen, function(k, v) {
        operator.append($("<option value=\""+k+"\">"+v+"</option>"));
      });

      box.append(operator.change(function() {cms_bedingt_gui_zu_string(box)}));
      box.append($("<input type=\"number\" class=\"cms_bedingt_gui_bedingung_feld\" placeholder=\"Wert\">").change(function() {cms_bedingt_gui_zu_string(box)}).keyup(function() {cms_bedingt_gui_zu_string(box)}));
    }

    if(["nutzer.vorname", "nutzer.nachname", "nutzer.titel"].includes(val)) {

      var operator = $("<select class=\"cms_bedingt_gui_bedingung_feld\"><option selected style=\"display: none\" disabled>Operation</option></select>");
      var operationen = {
        "==": "Gleich",
        "!=": "Ungleich",
        "<": "Kleiner als",
        ">": "Größer als"
      }
      $.each(operationen, function(k, v) {
        operator.append($("<option value=\""+k+"\">"+v+"</option>"));
      });

      box.append(operator.change(function() {cms_bedingt_gui_zu_string(box)}));
      box.append($("<input type=\"text\" class=\"cms_bedingt_gui_bedingung_feld\" placeholder=\"Wert\">").keyup(function() {cms_bedingt_gui_zu_string(box)}));
    }

    if(["nutzer.art"].includes(val)) {

      var operator = $("<select class=\"cms_bedingt_gui_bedingung_feld\"><option selected style=\"display: none\" disabled>Operation</option></select>");
      var operationen = {
        "==": "Gleich",
        "!=": "Ungleich",
        "<": "Kleiner als",
        ">": "Größer als"
      }
      $.each(operationen, function(k, v) {
        operator.append($("<option value=\""+k+"\">"+v+"</option>"));
      });

      box.append(operator.change(function() {cms_bedingt_gui_zu_string(box)}));

      var art = $("<select class=\"cms_bedingt_gui_bedingung_feld\"><option selected style=\"display: none\" disabled>Nutzerart</option></select>");
      var arten = {
        "s": "Schüler",
        "l": "Lehrer",
        "e": "Eltern",
        "v": "Verwaltungsangestellte",
        "x": "Externe",
      }
      $.each(arten, function(k, v) {
        art.append($("<option value=\""+k+"\">"+v+"</option>"));
      });

      box.append(art.change(function() {cms_bedingt_gui_zu_string(box)}));
    }

    if(["nutzer.hatRolle"].includes(val)) {
      box.append($("<input type=\"hidden\" class=\"cms_bedingt_gui_bedingung_feld\" value=\"\">"));

      box.append($("<input type=\"text\" class=\"cms_bedingt_gui_bedingung_feld cms_bedingt_funktion\" placeholder=\"Rolle\">").keyup(function() {cms_bedingt_gui_zu_string(box)}));
    }

    box.find("select,input").css("width", (100/box.find("select,input:not([type=hidden])").length)+"%");

    cms_bedingt_gui_zu_string(box);
  });

  box.append(kriterium);

  dis.replaceWith(box);

  cms_bedingt_gui_zu_string(box);
}

function cms_bedingt_gui_feld_loeschen(dis) {
  dis = $(dis);
  gui = dis.parents(".cms_bedingt_bedingung_gui");
  cms_bedingt_gui_optionen(dis.parent().parent());

  dis.parent().remove();

  cms_bedingt_gui_zu_string(gui);
}

function cms_bedingt_gui_logisch_hinzufuegen(dis) {
  dis = $(dis);

  dis.before(cms_bedingt_gui_optionen($("<div class=\"cms_bedingt_gui_logisch_feld\"></div>")));

  cms_bedingt_gui_zu_string(dis);
}

function cms_bedingt_gui_zu_string(dis) {
  dis = $(dis);
  td = dis.parents("td");

  var op = td.find(".cms_bedingt_bedingung");

  var gui = td.find(".cms_bedingt_bedingung_gui");

  var rekpruefen = function(element) {
    if(element.is(".cms_bedingt_bedingung_gui")) {
      return rekpruefen(element.children());
    }
    if(element.is(".cms_bedingt_gui_logisch")) {
      var basteln;
      if(element.is(".cms_bedingt_gui_logisch_oder")) {
        basteln = function(werte) {
          var w = "";
          $.each(werte, function(i, wert) {
            w += "||"+wert;
          });
          w = w.substring("2");
          return "("+w+")";
        }
      }
      if(element.is(".cms_bedingt_gui_logisch_und")) {
        basteln = function(werte) {
          var w = "";
          $.each(werte, function(i, wert) {
            w += "&&"+wert;
          });
          w = w.substring("2");
          return "("+w+")";
        }
      }
      if(element.is(".cms_bedingt_gui_logisch_nicht")) {
        basteln = function(werte) {
          return "(!"+werte[0]+")";
        }
      }
      r = [];
      element.children(".cms_bedingt_gui_logisch_feld").each(function() {
        r.push(rekpruefen($(this)));
      });

      return basteln(r);
    }
    if(element.is(".cms_bedingt_gui_logisch_feld")) {
      return rekpruefen(element.children());
    }
    if(element.is(".cms_bedingt_gui_optionen")) {
      return "";
    }
    if(element.is(".cms_bedingt_gui_bedingung")) {
      var felder    = element.find(".cms_bedingt_gui_bedingung_feld");
      var r         = felder.eq(0).val() || "";
      var operator  = felder.eq(1);
      var wert      = felder.eq(2);
      if(operator.length) {
        if(operator.val() != null) {
          r += operator.val();
        }
      }
      if(wert.length) {
        if(wert.is(".cms_bedingt_funktion")) {
          r += "[";
        }
        if(wert.val() != null && wert.val() != "") {
          if(wert.is("[type=number]")) {
            r += wert.val();
          }
          if(wert.is("[type=text],select")) {
            r += "\""+wert.val()+"\"";
          }
        }
        if(wert.is(".cms_bedingt_funktion")) {
          r += "]";
        }
      }
      return r;
    }
  }

  op.val(rekpruefen(gui));

  cms_bedingte_bedingung_syntax_pruefen(op);
}
