$(document).ready(function() {
  $(".cms_sprachwahl").click(function() {
    cms_laden_an(s("aendern.kopf"), s("aendern.inhalt"));
    formulardaten = new FormData();
    var sprache = $(this).data("sprache");
    formulardaten.append("sprache",       sprache);
    formulardaten.append("url",           window.location.href.replace(CMS_DOMAIN, "").substring(1));
    formulardaten.append("anfragenziel",  274);


    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe != "FEHLER") {
        Cookies.set("sprache", sprache);
        cms_link(rueckgabe);
      }
      else {
        cms_fehlerbehandlung(rueckgabe);
      }
    }
    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);

  });
});

function s(key, variablen) {
  variablen = variablen || {};

  if(strings === "javascript")
    return key;
  if(typeof strings === "string")
    strings = JSON.parse(strings);

  keys = key.split(".")

  var val = strings;
  var k;
  while(k = keys.shift())
    if(val[k])
      val = val[k];
    else
      return key;

  if(!(typeof val === "string"))
      return val;

  $.each(variablen, function(key, value) {
    val = val.replace(key, value);
  })

  return val;
}
