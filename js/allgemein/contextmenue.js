$(window).on("load", function() {
  $("body").on("keydown", function(e) {
    if(e.keyCode === 27) {
      $("#contextmenue").hide().html("").removeClass("contextmenue_multiselect");
      $(".cms_multiselect_s").removeClass("cms_multiselect_s");
      multiselect_drag = false;
    }
  });
  $("body").on("contextmenu", ".cms_liste td", function(e) {
    var context = $("#contextmenue");
    context.html("").hide();

    var dis = $(this);
    var tr = $(this).parents("tr");

    if((dis.is(".cms_multiselect")) && dis.parents("table").find(".cms_multiselect_s").length) {
      tr.toggleClass("cms_multiselect_s");
      cms_multiselect_toggle(dis, true);
      return false;
    } else {
      if(!(a = tr.children("td:last-of-type")).length)
        return;
      if(!(a = a.children("span.cms_aktion_klein").clone()).length)
        return;

      context.html(a).show().css({"top": e.pageY+10, "left": e.pageX+10}).removeClass("contextmenue_multiselect");
      context.find(".cms_hinweis").removeClass("cms_hinweis").addClass("cms_alter_hinweis");
    }
    return false;
  });

  $("body").on("click", "", function() {
    if(!multiselect_drag) {
      $("#contextmenue").html("").hide().removeClass("contextmenue_multiselect");
    }
    multiselect_drag = false;
  });

  var multiselect_drag = false;
  var multiselect_behandelt = [];
  $("body").on("dragstart selectstart", ".cms_liste .cms_multiselect", () => false);

  $("body").on("mousedown", ".cms_liste .cms_multiselect",  function(e) {
    if(e.which != 1)
      return;
    multiselect_drag = true;
    cms_multiselect_toggle($(this));
    multiselect_behandelt = [$(this)[0]];
    return false;
  });
  $("body").on("mouseover", ".cms_liste tr", function() {
    var td;
    if((td = $(this).children("td.cms_multiselect")).length) {
      if(multiselect_drag && !multiselect_behandelt.includes(td[0])) {
        cms_multiselect_toggle(td);
        multiselect_behandelt.push(td[0]);
      }
    }
  });
});

function cms_multiselect_toggle(dis, sofort) {
  $("#contextmenue").html("").hide();
  var menue = dis.parents(".cms_liste").find(".cms_multiselect_menue");
  if(menue.length != 1) {
    return;
  }
  var tr = dis.parents("tr");
  var context = $("#contextmenue");

  tr.toggleClass("cms_multiselect_s");

  var aktionen = [];
  menue.find("td>span").each((i, e) => aktionen.push($(e).clone()));
  context.html(aktionen);
  context.find(".cms_hinweis").removeClass("cms_hinweis").addClass("cms_alter_hinweis");

  var top = tr.offset().top+(tr.outerHeight()-context.outerHeight())/2;
  var left = tr.offset().left+dis.outerWidth();

  if(context.hasClass("contextmenue_multiselect")) {
    context.clearQueue().show().delay(sofort ? 0 : 1000).animate({"top": top, "left": left});
  } else {
    context.clearQueue().css({"top": top, "left": left}).fadeIn();
  }
  context.addClass("contextmenue_multiselect");
}
