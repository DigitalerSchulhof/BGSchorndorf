$(document).ready(function() {
  $(".cms_recht>.icon").click(function() {
    $(this).toggleClass("cms_recht_eingeklappt");
    $(this).siblings(".cms_rechtekinder").slideToggle("slow");
  });
});

function cms_recht_vergeben_rolle(dis) {
  var r = $(dis).parent().parent(".cms_recht");
  if(!r.length)
    return;
  if(r.hasClass("cms_recht_rolle")) {
    // Recht wegnehmen

    // Allen Eltern nehmen
    r.parents(".cms_recht_rolle").find(">.cms_recht_rolle").removeClass("cms_recht_rolle");
    r.parents(".cms_recht_rolle").removeClass("cms_recht_rolle");
    // Allen Kindern und selbst nehmen
    r.find(".cms_recht_rolle").removeClass("cms_recht_rolle");
    r.removeClass("cms_recht_rolle");
  } else {
    // Recht geben

    // Allen Kindern geben
    r.find(".cms_recht:not(.cms_recht_rolle)").addClass("cms_recht_rolle");
    r.addClass("cms_recht_rolle");

    // Prüfen ob alle Geschwister
    var rekgeben = function(r) {
      // Prüfen ob alle Geschwister vergeben sind sind
      var p = r.parent().closest(".cms_recht");
      if(!p.length)
        return;
      if(p.find(".cms_recht").length == p.find(".cms_recht_rolle").length + p.find("cms_recht_rolle").length) {
        p.addClass("cms_recht_rolle");
        rekgeben(p);
      }
    };
    rekgeben(r);
  }
}

function cms_recht_vergeben_nutzer(dis) {
  var r = $(dis).parent().parent(".cms_recht:not(.cms_recht_rolle)");
  if(!r.length)
    return;
  if(r.hasClass("cms_recht_nutzer")) {
    // Recht wegnehmen

    // Allen Eltern nehmen
    r.parents(".cms_recht_nutzer").find(">.cms_recht_nutzer").removeClass("cms_recht_nutzer");
    r.parents(".cms_recht_nutzer").removeClass("cms_recht_nutzer");
    // Allen Kindern und selbst nehmen
    r.find(".cms_recht_nutzer").removeClass("cms_recht_nutzer");
    r.removeClass("cms_recht_nutzer");
  } else {
    // Recht geben

    // Allen Kindern geben
    r.find(".cms_recht:not(.cms_recht_rolle)").addClass("cms_recht_nutzer");
    r.addClass("cms_recht_nutzer");

    // Prüfen ob alle Geschwister
    var rekgeben = function(r) {
      // Prüfen ob alle Geschwister vergeben sind sind
      var p = r.parent().closest(".cms_recht");
      if(!p.length)
        return;
      if(p.find(".cms_recht").length == p.find(".cms_recht_nutzer").length + p.find("cms_recht_rolle").length) {
        p.addClass("cms_recht_nutzer");
        rekgeben(p);
      }
    };
    rekgeben(r);
  }
}

function cms_rechte_speichern_nutzer() {
  var rechte = [];
  var rekpruefen = function(e, pfad) {
    if(!e.length)
      return;
    e.each(function() {
      e = $(this);
      if(e.is(".cms_recht_nutzer"))
        rechte.push((pfad+"."+e.data("knoten")+(e.is(".cms_hat_kinder")?".*":"")).substr(2));
      else
        if(e.is(".cms_hat_kinder"))
          rekpruefen(e.find(">.cms_rechtekinder>.cms_rechtebox>.cms_recht"), pfad+"."+e.data("knoten"));
    });
  };
  rekpruefen($("#cms_rechtepapa>.cms_recht"), "");

  var formulardaten = new FormData();
  formulardaten.append('rechte', rechte);
  formulardaten.append('anfragenziel', '127');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Rechte vergeben', '<p>Die Rechte wurden vergeben.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_alle_rechte_ausklappen(box, papa) {
  papa = papa ? (papa + " ") : "";
  if($(box).html() == "Alle ausklappen") {
    $(papa+".cms_recht>.icon").removeClass("cms_recht_eingeklappt").siblings(".cms_rechtekinder").show();
    $(box).html("Alle einklappen");
  } else {
    $(papa+".cms_recht>.icon").addClass("cms_recht_eingeklappt").siblings(".cms_rechtekinder").hide();
    $(box).html("Alle ausklappen");
  }
}
