var galeriebilderzeit;

function cms_galeriebilder_starten() {
  galeriebilderzeit = window.setTimeout('cms_galeriebilder_wechseln()', 7000);
}

function cms_galeriebilder_wechseln() {
  var anzahl = document.getElementById('cms_galeriebilder_anzahl');
  var angezeigt = document.getElementById('cms_galeriebilder_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  var n = (parseInt(a)+1)%z;

  if (z > 1) {
    cms_galeriebildaendern(a, n);
  }
}

function cms_galeriebild_voriges() {
  var anzahl = document.getElementById('cms_galeriebilder_anzahl');
  var angezeigt = document.getElementById('cms_galeriebilder_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  var n = (parseInt(a)-1)%z;
  n = (n+parseInt(z))%z;

  if (z > 1) {
    cms_galeriebildaendern(a, n);
  }
}

function cms_galeriebild_naechstes() {cms_galeriebilder_wechseln();}

function cms_galeriebild_zeigen(n) {
  var anzahl = document.getElementById('cms_galeriebilder_anzahl');
  var angezeigt = document.getElementById('cms_galeriebilder_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;

  if (z > 1) {
    cms_galeriebildaendern(a, n);
  }
}

function cms_galeriebildaendern(alt, neu) {
  var angezeigt = document.getElementById('cms_galeriebilder_angezeigt');
  var ausblenden = document.getElementById('cms_galeriebilder_'+alt);
  var einblenden = document.getElementById('cms_galeriebilder_'+neu);
  var ausblendenknopf = document.getElementById('cms_galeriebilder_knopf_'+alt);
  var einblendenknopf = document.getElementById('cms_galeriebilder_knopf_'+neu);

  ausblenden.style.opacity = 0;
  einblenden.style.opacity = 1;
  ausblendenknopf.className = "cms_galeriebild_knopf";
  einblendenknopf.className = "cms_galeriebild_knopf_aktiv";

  angezeigt.value = neu;
  window.clearTimeout(galeriebilderzeit);
  galeriebilderzeit = window.setTimeout('cms_galeriebilder_wechseln()', 7000);
}
