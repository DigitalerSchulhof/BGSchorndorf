$(window).on("load", function() {
  $("body").contextmenu(function(e) {
    var c = $("#contextmenue");
    c.html("").hide();

    var el = $(e.target);
    var p, a;
    if(!el)
      return;
    if(el.is("input"))
      return;
    if(!(p = el.parents(".cms_liste")).length)
      return;
    if(!(p = el.parents("tr")).length)
      return;
    if((el.is(".cms_multiselect") || el.parents("td").is(".cms_multiselect")) && el.parents("table").find(".cms_multiselect_s").length) {
      el.parents("tr").toggleClass("cms_multiselect_s");
      el.trigger("click");
      return false;
    } else {
      if(!(a = p.children("td:last-of-type")).length)
        return;
      if(!(a = a.children("span.cms_aktion_klein").clone()).length)
        return;

      c.html(a).show().css({"top": e.pageY+10, "left": e.pageX+10}).removeClass("contextmenue_multiselect").find(".cms_hinweis").removeClass("cms_hinweis").addClass("cms_alter_hinweis");
    }
    return false;
  });
  $("body").click(function() {
    $("#contextmenue").html("").hide().removeClass("contextmenue_multiselect");
  });

  $(".cms_liste .cms_multiselect").click(function() {
    $("#contextmenue").html("").hide();

    var menue = $(this).parents(".cms_liste").find(".cms_multiselect_menue");
    var c = $("#contextmenue");

    if(menue.length != 1) {
      return true;
    }
    var a = [];
    var tr = $(this).parents("tr");
    tr.toggleClass("cms_multiselect_s");
    menue.find("td>span").each((i, e) => a.push($(e).clone()));
    c.html(a).show().find(".cms_hinweis").removeClass("cms_hinweis").addClass("cms_alter_hinweis");

    var top = tr.offset().top+(tr.outerHeight()-c.outerHeight())/2;
    var left = tr.offset().left+$(this).outerWidth();

    if(c.hasClass("contextmenue_multiselect")) {
      c.animate({"top": top, "left": left});
    } else {
      c.css({"top": top, "left": left});
    }
    c.addClass("contextmenue_multiselect");

    return false;
  });
});
