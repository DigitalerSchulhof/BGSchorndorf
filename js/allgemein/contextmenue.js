$(window).on("load", function() {
  $("body").contextmenu(function(e) { // Body, sodass bei dynamischem Laden die Events bestehen
    var el = $(e.target);
    var p, a;
    if(!el)
      return;
    if(!(p = el.parents(".cms_liste")).length)
      return;
    if(!(p = el.parents("tr")).length)
      return;
    if(!(a = p.children("td:last-of-type")).length)
      return;
    if(!(a = a.children("span.cms_aktion_klein").clone()).length)
      return;

    $("#contextmenue").html(a).show().css({"top": e.pageY+10, "left": e.pageX+10}).find(".cms_hinweis").removeClass("cms_hinweis").addClass("cms_alter_hinweis");

    e.preventDefault();
  });
  $("body").click(function() {
    $("#contextmenue").html("").hide();
  });
});
