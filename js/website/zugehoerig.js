function cms_zugehoerig_laden (feldid, jahr, gruppe, gruppenid, url) {
  var feld = document.getElementById(feldid);
  var inhalt = feld.innerHTML;
  feld.innerHTML = cms_ladeicon()+'<p class="cms_notiz">Die zugehörigen Inhalte für das Jahr '+jahr+' werden geladen.</p>';
  feld.style.opacity = 1;

  var fehler = false;

  if (!Number.isInteger(parseInt(jahr))) {
    fehler = true;
  }

	if (fehler) {
    feld.innerHTML = '<p class="cms_notiz">– ungültige Anfrage –</p>';
  }
  else {
    var formulardaten = new FormData();
    formulardaten.append("url",       url)
    formulardaten.append("feldid",    feldid)
  	formulardaten.append("jahr",      jahr);
  	formulardaten.append("gruppe",    gruppe);
  	formulardaten.append("gruppenid", gruppenid);
  	formulardaten.append("anfragenziel", 	'44');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe.slice(0,3) == '<h3>') {
        feld.innerHTML = rueckgabe;
      }
  		else {feld.innerHTML = rueckgabe;}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
