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

  $("#cms_bedingte_rechte tbody").append("<tr><td><input class=\"cms_bedingt_recht_recht\" type=\"hidden\" value=\""+recht+"\">"+recht+"</td><td><input onkeyup=\"cms_bedingte_bedingung_syntax_pruefen(this)\" class=\"cms_bedingt_recht_bedingung\" type=\"text\"></td><td><span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_bedingt_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> <span style=\"display: none\" class=\"cms_syntax_ok cms_aktion_klein cms_button_ja\"><span class=\"cms_hinweis\">Syntax ist in Ordnung</span><img src=\"res/icons/klein/richtig.png\"></span><span class=\"cms_syntax_fehler cms_aktion_klein cms_button_nein\"><span class=\"cms_hinweis\">Syntaxfehler!</span><img src=\"res/icons/klein/falsch.png\"></span></td></tr>");
}

function cms_bedingt_rolle(bez, rid) {
  $("#cms_bedingte_rollen tbody").append("<tr><td><input class=\"cms_bedingt_rolle_rolle\" type=\"hidden\" value=\""+rid+"\">"+bez+"</td><td><input onkeyup=\"cms_bedingte_bedingung_syntax_pruefen(this)\" class=\"cms_bedingt_rolle_bedingung\" type=\"text\"></td><td><span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_bedingt_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> <span style=\"display: none\" class=\"cms_syntax_ok cms_aktion_klein cms_button_ja\"><span class=\"cms_hinweis\">Syntax ist in Ordnung</span><img src=\"res/icons/klein/richtig.png\"></span><span class=\"cms_syntax_fehler cms_aktion_klein cms_button_nein\"><span class=\"cms_hinweis\">Syntaxfehler!</span><img src=\"res/icons/klein/falsch.png\"></span></td></tr>");
}

function cms_bedingte_rechte_speichern() {
  var tbody = $("#cms_bedingte_rechte>tbody");
  var bedingungen = {};
  tbody.find("tr").each(function() {
    var recht     = $(this).find(".cms_bedingt_recht_recht").val();
    var bedingung = $(this).find(".cms_bedingt_recht_bedingung").val();
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
  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_bedingte_rollen_speichern() {
  var tbody = $("#cms_bedingte_rollen>tbody");
  var bedingungen = {};
  tbody.find("tr").each(function() {
    var rolle     = $(this).find(".cms_bedingt_rolle_rolle").val();
    var bedingung = $(this).find(".cms_bedingt_rolle_bedingung").val();
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
  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_bedingte_bedingung_syntax_pruefen(dis) {
  dis = $(dis);
  var tr = dis.parents("tr");
  var bedingung = tr.find(".cms_bedingt_recht_bedingung").val() || tr.find(".cms_bedingt_rolle_bedingung").val() || "";

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
  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
