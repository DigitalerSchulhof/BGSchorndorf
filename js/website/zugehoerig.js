function cms_zugehoerig_laden (jahr, anfang, ende, gruppe, gruppenid) {
  var feld = document.getElementById('cms_zugehoerig_jahr');
  feld.innerHTML = '<div class="cms_zugehoerig_spalte_a1"><div class="cms_zugehoerig_spalte_i"><p class="cms_notiz">'+cms_ladeicon()+'<br>Die zugehörigen Inhalte für das Jahr '+jahr+' werden geladen.</p></div></div>';

  for (var i=anfang; i<=ende; i++) {
    var toggle = document.getElementById('cms_zugehoerig_jahr_'+i);
    toggle.className = 'cms_toggle';
  }

  var fehler = false;

  if (!Number.isInteger(parseInt(jahr))) {
    fehler = true;
  }

	if (fehler) {
    feld.innerHTML = '<div class="cms_zugehoerig_spalte_a1"><div class="cms_zugehoerig_spalte_i"><p class="cms_notiz">– ungültige Anfrage –</p></div></div>';
  }
  else {
    var formulardaten = new FormData();
  	formulardaten.append("jahr",      jahr);
  	formulardaten.append("gruppe",    gruppe);
  	formulardaten.append("gruppenid", gruppenid);
  	formulardaten.append("anfragenziel", 	'44');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe.slice(0,4) == '<div') {
        var toggle = document.getElementById('cms_zugehoerig_jahr_'+jahr);
        toggle.className = 'cms_toggle_aktiv';
        feld.innerHTML = rueckgabe;
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
