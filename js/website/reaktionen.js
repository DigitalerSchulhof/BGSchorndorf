$(window).ready(function() {
  // Liste an
  $("#cms_neue_reaktion img").click(function() {
    $("#cms_reaktionswahl").slideDown("fast", function() {
      $(this).css("overflow-y", "scroll");
    });
  })

  // Liste aus
  $("body").click(function(e) {
    if(!$(e.target).is($("#cms_neue_reaktion>img")))
      $("#cms_reaktionswahl").css("overflow-y", "hidden").slideUp("fast");
  })

  // Auswahl
  $(".cms_reaktion img").click(function() {
    cms_reaktion($(this).data("reaktion"));
  })
})

function cms_reaktion(reaktion) {
  cms_laden_an('Reagieren', 'Wird verarbeitet...');

  var r = $(".cms_reaktion_"+reaktion);
  if(r.hasClass("cms_reagiert"))
    r.children("span").html(parseInt(r.children("span").html().replace("&nbsp;", "")||0)-1||"&nbsp;");
  else
    r.children("span").html(parseInt(r.children("span").html().replace("&nbsp;", "")||0)+1);

  r.toggleClass("cms_reagiert");

  var formulardaten = new FormData();
  formulardaten.append("typ",       cms_typ);
  formulardaten.append("id",        cms_id);
  formulardaten.append("reaktion",  reaktion);

  formulardaten.append("anfragenziel", 	'341');

  cms_ajaxanfrage (false, formulardaten, function(r) {
    if (r == "ERFOLG") {
      cms_laden_aus();
    }else {
      cms_fehlerbehandlung(r);
    }
  });

}
