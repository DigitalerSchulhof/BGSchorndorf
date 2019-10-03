function cms_reaktion(reaktion, typ, id, gid) {
  cms_laden_an('Reagieren', 'Wird verarbeitet...');

  var r = $(".cms_reaktion_"+reaktion);
  if(r.hasClass("cms_reagiert"))
    r.children("span").html(parseInt(r.children("span").html().replace("&nbsp;", "")||0)-1||"&nbsp;");
  else
    r.children("span").html(parseInt(r.children("span").html().replace("&nbsp;", "")||0)+1);

  r.toggleClass("cms_reagiert");

  var formulardaten = new FormData();
  formulardaten.append("typ",       typ);
  formulardaten.append("id",        id);
  formulardaten.append("gid",       gid);
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
