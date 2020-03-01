var wechselbilderzeit = new Array();

function cms_wechselbilder_starten(id) {
  wechselbilderzeit[id] = window.setTimeout('cms_wechselbilder_wechseln(\''+id+'\')', CMS_DIASHOWZEIT);
}

function cms_wechselbilder_wechseln(id) {
  var anzahl = document.getElementById('cms_wechselbilder_'+id+'_anzahl');
  var angezeigt = document.getElementById('cms_wechselbilder_'+id+'_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  var n = (parseInt(a)+1)%z;

  if (z > 1) {
    cms_wechselbild_aendern(id, a, n);
  }
}

function cms_wechselbild_voriges(id) {
  var anzahl = document.getElementById('cms_wechselbilder_'+id+'_anzahl');
  var angezeigt = document.getElementById('cms_wechselbilder_'+id+'_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  var n = (parseInt(a)-1)%z;
  n = (n+parseInt(z))%z;

  if (z > 1) {
    cms_wechselbild_aendern(id, a, n);
  }
}

function cms_wechselbild_naechstes(id) {cms_wechselbilder_wechseln(id);}

function cms_wechselbild_zeigen(id, n) {
  var anzahl = document.getElementById('cms_wechselbilder_'+id+'_anzahl');
  var angezeigt = document.getElementById('cms_wechselbilder_'+id+'_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;

  if (z > 1) {
    cms_wechselbild_aendern(id, a, n);
  }
}

function cms_wechselbild_aendern(id, alt, neu) {
  var angezeigt = document.getElementById('cms_wechselbilder_'+id+'_angezeigt');
  var ausblenden = document.getElementById('cms_wechselbilder_bild_'+id+'_'+alt);
  var einblenden = document.getElementById('cms_wechselbilder_bild_'+id+'_'+neu);
  var ausblendenknopf = document.getElementById('cms_wechselbilder_knopf_'+id+'_'+alt);
  var einblendenknopf = document.getElementById('cms_wechselbilder_knopf_'+id+'_'+neu);

  ausblenden.style.opacity = 0;
  ausblenden.style.zIndex = 1;
  einblenden.style.opacity = 1;
  einblenden.style.zIndex = 2;
  ausblendenknopf.className = "cms_wechselbilder_knopf";
  einblendenknopf.className = "cms_wechselbilder_knopf_aktiv";

  angezeigt.value = neu;
  window.clearTimeout(wechselbilderzeit[id]);
  wechselbilderzeit[id] = window.setTimeout('cms_wechselbilder_wechseln(\''+id+'\')', CMS_DIASHOWZEIT);
}
