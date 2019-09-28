$(window).on("load", function() {
  galerie.zeigen(0);
  galerie.starteLoop();
  galerie.keydownSetzen();
});

var galerie = {
  index: null,
  interval: null,
  zeigen: function(i, auto) {
    console.log(i);
    if(i.jQuery)
      if(!i.parents("#cms_galerie_bilder").length || !i.is(".cms_galerie_bild"))
        return false;
      else
        i = i.index(".cms_galerie_bild");

      $(".cms_galerie_bild").removeClass("cms_galerie_zeigen");
      galerie.index = i;
      galerie.dotZeigen();
      if(!auto) {
        clearInterval(galerie.interval);
        galerie.interval = null;
        setTimeout(function() {
          galerie.starteLoop();
        }, 6000); // Bei manuellem Wechsel 6s warten
      }
      return $("#cms_galerie_bilder").find(".cms_galerie_bild:eq("+i+")").addClass("cms_galerie_zeigen");
  },
  dotZeigen: function(i) {
    i !== 0 && (i = i || galerie.index);
    $("#cms_galerie_dots").find(".cms_galerie_dot").removeClass("cms_galerie_zeigen");
    $("#cms_galerie_dots").find(".cms_galerie_dot:eq("+i+")").addClass("cms_galerie_zeigen");
  },
  vor: function(i, auto) {
    i = i || 1;
    auto = auto || false;
    galerie.next(-i);
  },
  next: function(i, auto) {
    i = i || 1;
    auto = auto || false;
    galerie.zeigen(galerie.index+i, auto);
  },
  starteLoop: function() {
    if(galerie.interval)
      return;
    galerie.interval = setInterval(function() {
      galerie.next(1, true)
    }, 3000);
  },
  keydownSetzen: function() {
    $(document).keydown(function(e) {
        switch(e.which) {
            case 37: // Links
              galerie.vor();
              break;
            case 39: // Rechts
              galerie.next();
              break;
            default: return;
        }
        e.preventDefault();
    });
  }
}
