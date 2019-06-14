function cms_kalender_ansicht_erneuern(art) {
  var feldt = document.getElementById('cms_kalender_tagansicht');
  var feldw = document.getElementById('cms_kalender_wocheansicht');
  var feldm = document.getElementById('cms_kalender_monatansicht');
  var feldj = document.getElementById('cms_kalender_jahransicht');
  var felda = document.getElementById('cms_kalender_ansicht');
  var weiter = false;

  if (art == 'tag') {
    feldt.value = 1; document.getElementById('cms_kalender_tagansicht_K').className = "cms_toggle_aktiv";
    feldw.value = 0; document.getElementById('cms_kalender_wocheansicht_K').className = "cms_toggle_inaktiv";
    feldm.value = 0; document.getElementById('cms_kalender_monatansicht_K').className = "cms_toggle_inaktiv";
    feldj.value = 0; document.getElementById('cms_kalender_jahransicht_K').className = "cms_toggle_inaktiv";
    felda.value = 'tag';
  }
  else if (art == 'woche') {
    feldt.value = 0; document.getElementById('cms_kalender_tagansicht_K').className = "cms_toggle_inaktiv";
    feldw.value = 1; document.getElementById('cms_kalender_wocheansicht_K').className = "cms_toggle_aktiv";
    feldm.value = 0; document.getElementById('cms_kalender_monatansicht_K').className = "cms_toggle_inaktiv";
    feldj.value = 0; document.getElementById('cms_kalender_jahransicht_K').className = "cms_toggle_inaktiv";
    felda.value = 'woche';
  }
  else if (art == 'monat') {
    feldt.value = 0; document.getElementById('cms_kalender_tagansicht_K').className = "cms_toggle_inaktiv";
    feldw.value = 0; document.getElementById('cms_kalender_wocheansicht_K').className = "cms_toggle_inaktiv";
    feldm.value = 1; document.getElementById('cms_kalender_monatansicht_K').className = "cms_toggle_aktiv";
    feldj.value = 0; document.getElementById('cms_kalender_jahransicht_K').className = "cms_toggle_inaktiv";
    felda.value = 'monat';
  }
  else if (art == 'jahr') {
    feldt.value = 0; document.getElementById('cms_kalender_tagansicht_K').className = "cms_toggle_inaktiv";
    feldw.value = 0; document.getElementById('cms_kalender_wocheansicht_K').className = "cms_toggle_inaktiv";
    feldm.value = 1; document.getElementById('cms_kalender_monatansicht_K').className = "cms_toggle_inaktiv";
    feldj.value = 0; document.getElementById('cms_kalender_jahransicht_K').className = "cms_toggle_aktiv";
    felda.value = 'jahr';
  }
}

function cms_kalender_sichtbaretermine() {
  var sichtbar = document.getElementById('cms_kalender_sichtbar').value;

  if (sichtbar == 1) {cms_einblenden('cms_kalender_gruppenansicht', 'table-row');}
  else {cms_ausblenden('cms_kalender_gruppenansicht');}
}

function cms_kalender_neu(ziel) {
  cms_laden_an('Kalender aktualiseren', 'Der Kalender wird aktualisiert.');
  var ansicht = document.getElementById('cms_kalender_ansicht').value;
  var ansichtP = document.getElementById('cms_kalender_persoenlich').value;
  var ansichtO = document.getElementById('cms_kalender_oeffentlich').value;
  var ansichtF = document.getElementById('cms_kalender_ferien').value;
  var fehler = false;

  if ((ansicht != 'tag') && (ansicht != 'woche') && (ansicht != 'monat') && (ansicht != 'jahr')) {fehler = true;}

  if (!cms_check_toggle(ansichtP)) {fehler = true;}
  if (!cms_check_toggle(ansichtO)) {fehler = true;}
  if (!cms_check_toggle(ansichtF)) {fehler = true;}

  if (fehler) {
		cms_meldung_bastler();
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("ansicht", ansicht);
		formulardaten.append("ansichtP", ansichtP);
		formulardaten.append("ansichtO", ansichtO);
		formulardaten.append("ansichtF", ansichtF);
		formulardaten.append("anfragenziel", 	'25');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link(ziel);}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
