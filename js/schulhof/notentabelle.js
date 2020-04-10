function cms_notentabelle_einstellungen(kurs) {
  var tr = $("#notentabelle #kurs_"+kurs);
  tr.addClass("e");
  $("#notentabelle thead tr").addClass("e");
  var neueNote = $("<div></div>", {class: "note neu neueNote"});
  neueNote.text("+");
  neueNote.click(cms_notentabelle_neue_note);

  tr.find(".noten>div>.bereich").append(neueNote);

  // Rahmen bei leeren Zellen
  tr.find(".noten>div>.bereich").each(function() {
    if($(this).children().length == 1) {
      $(this).prev().find(".note:not(.neu)").addClass("bf");  // Border Fix
    }
  });

  var neuerBereich = $("<tr></tr>", {class: "neu neuerBereich"});
  neuerBereich.append($("<td></td>").text("+").attr("colspan", 5));
  neuerBereich.click(cms_notentabelle_neuer_bereich);

  tr.after(neuerBereich);
  tr.find(".fach,.einstellungen").attr("rowspan", 2);
  tr.find(".bereiche>div>.bereich .bez").attr("contenteditable", true);
  tr.find(".bereiche>div>.bereich .gewichtung").attr("contenteditable", true);
  tr.find(">.avg").css("display", "none");
  tr.find(">.noten").attr("colspan", 2);
  tr.find(".noten>div>.bereich .note").click(cms_notentabelle_note_loeschen);
  cms_notentabelle_headerbreite();
}

function cms_notentabelle_einstellungen_abbrechen(kurs) {
  var tr = $("#notentabelle #kurs_"+kurs);
  tr.find(".neu").remove();
  tr.next(".neu").remove();
  tr.find("[rowspan]").attr("rowspan", "");
  tr.find("[colspan]").attr("colspan", "");
  tr.find("[contenteditable]").attr("contenteditable", "false");
  tr.find(".weg").removeClass("weg");
  tr.find(".neue").remove();
  tr.find(".bf").removeClass("bf");
  tr.find(".avg").css("display", "");
  tr.removeClass("e");
  tr.find(".noten>div>.bereich .note").unbind("click", cms_notentabelle_note_loeschen);

  if($("#notentabelle").find(".kurs.e").length == 0) {
    $("#notentabelle thead tr").removeClass("e");
  }
  cms_notentabelle_headerbreite();
}

function cms_notentabelle_einstellungen_speichern(kurs) {
  var tr = $("#notentabelle #kurs_"+kurs);
  var original = [tr.clone(true), tr.next(".neu").clone(true)];
  tr.next(".neu").remove();
  tr.find("[rowspan]").attr("rowspan", "");
  tr.find("[colspan]").attr("colspan", "");
  tr.find("[contenteditable]").attr("contenteditable", "false");
  tr.removeClass("e");
  tr.find(".noten>div>.bereich .note").unbind("click", cms_notentabelle_note_loeschen);
  tr.find(".weg").remove();
  tr.find(".neue").removeClass("neue");
  tr.find(".neu").remove();
  tr.find(".avg").css("display", "");
  tr.find(".bf").removeClass("bf");

  cms_laden_an("Änderungen werden gespeichert", "Die Struktur wird übertragen...");
  // Eingaben prüfen
  var meldung = '<p>Die Änderungen konnten nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
  var gewf = false;
  var bezf = false;

  tr.find(".bereiche>div>.bereich").each(function() {
    if(!gewf && !$.isNumeric($(this).find(".gewichtung").text())) {
      meldung += '<li>die Eingabe einer Gewichtung ist ungültig.</li>';
      fehler = true;
      gewf = true;
    }
    if(!bezf && !cms_check_name($(this).find(".bez").text())) {
      meldung += '<li>die Bezeichung eines Bereichs ist ungültig.</li>';
      fehler = true;
      bezf = true;
    }
  });
  if(tr.find(".bereiche>div>.bereich").length < 2) {
    meldung += '<li>es müssen mindestens zwei Bereiche ausgewählt sein.</li>';
    fehler = true;
  }

  if (fehler) {
    tr.after(original[1]);
    tr.replaceWith(original[0]);
		cms_meldung_an('fehler', 'Änderungen speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}

  if($("#notentabelle").find(".kurs.e").length == 0) {
    $("#notentabelle thead tr").removeClass("e");
  }
  cms_notentabelle_headerbreite();

  if(fehler) {
    // Nicht hochladen
    return;
  }

  var bereiche = [];
  var noten = [];
  tr.find(".bereiche>div>.bereich").each(function() {
    var bereich = {};
    bereich.gew = $(this).find(".gewichtung").text();
    bereich.bez = $(this).find(".bez").text();
    bereiche.push(bereich);
  })
  tr.find(".noten").each(function() {
    var bereich = [];
    $(this).find(">div .bereich").each(function() {
      var z = 0;
      $(this).find(".note").each(function() {
        z++;
      })
      bereich.push(z);
    })
    noten.push(bereich);
  });

  var formulardaten = new FormData();
  formulardaten.append("kurs",      kurs);
  formulardaten.append("bereiche",  JSON.stringify(bereiche));
  formulardaten.append("noten",     JSON.stringify(noten));
  formulardaten.append("anfragenziel", 	'384');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Änderungen speichern', '<p>Die Änderugnen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_notentabelle_neue_note() {
  var leereNote = $("<div></div>", {class: "note neue"});
  leereNote.click(cms_notentabelle_note_loeschen)
  if($(this).parent().next().children().length == 1) {
    leereNote.addClass("bf");
  }
  $(this).before(leereNote);
  if($(this).siblings(".note:not(.neu)").length == 1) {
    $(this).parent().prev().children().removeClass("bf");
  }

  cms_notentabelle_headerbreite();
}

function cms_notentabelle_note_loeschen() {
  var b = $(this).parents(".bereich").siblings().addBack();
  if($(this).is(".neue")) {
    $(this).remove();
  }
  b.each(function() {
    if($(this).children().length == 1) {
      $(this).prev().find(".note:not(.neu)").addClass("bf");  // Border Fix
    }
  });
  if($(this).is(".weg")) {
    $(this).removeClass("weg");
  } else {
    if(!$(this).is(".neu")) {
      $(this).addClass("weg");
    }
  }
  cms_notentabelle_headerbreite();
}

function cms_notentabelle_neuer_bereich() {
  var tr = $(this).prev();
  var neuerBereich = $("<div></div>", {class: "neue bereich"});
  var neueNoten = $("<div></div>", {class: "neue bereich"});
  var neueNote = $("<div></div>", {class: "note neu neueNote"});
  var neuerAvg = $("<div></div>", {class: "neue bereich"});
  neueNote.text("+");
  neueNote.click(cms_notentabelle_neue_note);
  neueNoten.append(neueNote);
  neuerBereich.append($("<div></div>", {class: "loeschen"}).click(cms_notentabelle_bereich_loeschen));
  neuerBereich.append($("<div></div>", {class: "gewichtung"}).text("1").attr("contenteditable", "true"));
  neuerBereich.append($("<div></div>", {class: "bez"}).attr("contenteditable", "true"));
  neuerAvg.text("...");
  tr.find(".bereiche>div").append(neuerBereich);
  tr.find(".noten>div").append(neueNoten);
  tr.find(".avg>div").append(neuerAvg);
  neuerBereich.find(".bez").focus();
}

function cms_notentabelle_bereich_loeschen(e) {
  var dis;
  if(e && e.nodeName) { // Unterschied jQuery-call und onclick=""
    dis = $(e)
  } else {
    dis = $(this);
  }
  var tr = dis.parents("tr");
  var i = dis.parents(".bereich").index();
  if(dis.parents(".bereich").is(".weg")) {
    tr.find(".bereiche, .noten, .avg").each(function() {
      $(this).find(">div .bereich").eq(i).removeClass("weg");
    });
    tr.find(".bereiche>div .bereich").eq(i).find(".bez").attr("contenteditable", "true");
    tr.find(".noten").each(function() {
      $(this).find(">div .bereich").eq(i).find(".note").click(cms_notentabelle_note_loeschen);
    });

  } else if(dis.parents(".bereich").is(".neue")) {
    tr.find(".bereiche, .noten, .avg").each(function() {
      $(this).find(">div .bereich").eq(i).remove();
    });
  } else {
    tr.find(".bereiche, .noten, .avg").each(function() {
      $(this).find(">div .bereich").eq(i).addClass("weg");
    });
    tr.find(".bereiche>div .bereich").eq(i).find(".bez").attr("contenteditable", "false");
    tr.find(".noten").each(function() {
      $(this).find(">div .bereich").eq(i).find(".note").unbind("click", cms_notentabelle_note_loeschen);
    });
  }
}

function cms_notentabelle_headerbreite() {
  $("#notentabelle th.hj").each(function() {
    var max = 0;
    var i = $(this).index(".hj");
    $("#notentabelle .kurs").each(function() {
      $(this).find(".noten").eq(i).find(">div .bereich").each(function() {
        max = Math.max(Math.max(1, max), $(this).children(":not(.neu)").length);
      })
    })
    $(this).css("width", 30*max);
  })
}
