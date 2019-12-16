$(document).ready(function() {
  $(".cms_recht>.icon").click(function() {
    $(this).toggleClass("cms_recht_eingeklappt");
    $(this).siblings(".cms_rechtekinder").slideToggle("slow");
  });
  $("#cms_rechtepapa>.cms_recht>.icon").removeClass("icon");
  $(".cms_recht_beschreibung").click(function() {
    var r = $(this).parent(".cms_recht:not(.cms_recht_rolle)");
    if(!r.length)
      return;
    if(r.hasClass("cms_recht_aktiv")) {
      // Recht wegnehmen

      // Allen Eltern nehmen
      r.parents(".cms_recht_aktiv").find(">.cms_recht_aktiv").removeClass("cms_recht_aktiv");
      r.parents(".cms_recht_aktiv").removeClass("cms_recht_aktiv");
      // Allen Kindern und selbst nehmen
      r.find(".cms_recht_aktiv").removeClass("cms_recht_aktiv");
      r.removeClass("cms_recht_aktiv");
    } else {
      // Recht geben

      // Allen Kindern geben
      r.find(".cms_recht:not(.cms_recht_rolle)").addClass("cms_recht_aktiv");
      r.addClass("cms_recht_aktiv");

      // Prüfen ob alle Geschwister
      var rekgeben = function(r) {
        // Prüfen ob alle Geschwister aktiv sind
        var p = r.parent().closest(".cms_recht");
        if(!p.length)
          return;
        if(p.find(".cms_recht").length == p.find(".cms_recht_aktiv").length) {
          p.addClass("cms_recht_aktiv");
          rekgeben(p);
        }
      };
      rekgeben(r);
    }
  });
});

function cms_rechte_speichern() {
  var rechte = [];
  var rekpruefen = function(e, pfad) {
    if(!e.length)
      return;
    e.each(function() {
      e = $(this);
      if(e.is(".cms_recht_aktiv"))
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

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_alle_rechte_ausklappen(box) {
  if($(box).html() == "Alle ausklappen") {
    $(".cms_recht>.icon").removeClass("cms_recht_eingeklappt").siblings(".cms_rechtekinder").slideDown("slow");
    $(box).html("Alle einklappen");
  } else {
    $(".cms_recht>.icon").addClass("cms_recht_eingeklappt").siblings(".cms_rechtekinder").slideUp("slow");
    $(box).html("Alle ausklappen");
  }
}
