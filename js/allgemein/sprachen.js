$(document).ready(function() {
  $(".cms_sprachwahl").click(function() {
    Cookies.set("sprache", $(this).data("sprache"));
    location.reload();
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
