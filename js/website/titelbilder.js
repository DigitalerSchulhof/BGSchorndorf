var titelbilderzeit;

function cms_titelbilder_starten() {
  titelbilderzeit = window.setTimeout('cms_titelbilder_wechseln()', 7000);
}

function cms_titelbilder_wechseln() {
  var anzahl = document.getElementById('cms_titelbilder_anzahl');
  var angezeigt = document.getElementById('cms_titelbilder_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  var n = (parseInt(a)+1)%z;

  if (z > 1) {
    cms_bildaendern(a, n);
  }
}

function cms_titelbild_voriges() {
  var anzahl = document.getElementById('cms_titelbilder_anzahl');
  var angezeigt = document.getElementById('cms_titelbilder_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  var n = (parseInt(a)-1)%z;
  n = (n+parseInt(z))%z;

  if (z > 1) {
    cms_bildaendern(a, n);
  }
}

function cms_titelbild_naechstes() {cms_titelbilder_wechseln();}

function cms_titelbild_zeigen(n) {
  var anzahl = document.getElementById('cms_titelbilder_anzahl');
  var angezeigt = document.getElementById('cms_titelbilder_angezeigt');

  var z = anzahl.value;
  var a = angezeigt.value;
  
  if (z > 1) {
    cms_bildaendern(a, n);
  }
}

function cms_bildaendern(alt, neu) {
  var angezeigt = document.getElementById('cms_titelbilder_angezeigt');
  var ausblenden = document.getElementById('cms_hauptbilder_'+alt);
  var einblenden = document.getElementById('cms_hauptbilder_'+neu);
  var ausblendenknopf = document.getElementById('cms_hauptbilder_knopf_'+alt);
  var einblendenknopf = document.getElementById('cms_hauptbilder_knopf_'+neu);

  ausblenden.style.opacity = 0;
  einblenden.style.opacity = 1;
  ausblendenknopf.className = "cms_titelbild_knopf";
  einblendenknopf.className = "cms_titelbild_knopf_aktiv";

  angezeigt.value = neu;
  window.clearTimeout(titelbilderzeit);
  titelbilderzeit = window.setTimeout('cms_titelbilder_wechseln()', 7000);
}
