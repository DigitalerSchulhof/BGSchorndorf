function cms_tagebuchmeldung_laden() {
  var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("anfragenziel", '31');

	function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe != 'keine') {
      var feld = document.getElementById('cms_tagebuchneuigkeit_inhalt');
      feld.innerHTML = '<h4>Tagebucheinträge</h4><p>'+rueckgabe+'</p>';
    }
		else {
      var tagebuchmeldung = document.getElementById('cms_tagebuchneuigkeit');
      tagebuchmeldung.parentNode.removeChild(tagebuchmeldung);
    }
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_notfallzustand_anzeigen(wert, app) {
  var app = app || 'nein';
  if (wert == '1') {
    cms_meldung_notfall('warnung', 'Notfallzustand ausrufen', '<p><b>Achtung!</b> Sie sind dabei den Notfallzustand auszurufen. Alle Schüler, Lehrer, Verwaltungsangestellte und Externe im Schulhof werden angewiesen <b>das Schulgebäude umgehend zu verlassen</b>. Alle Lehrer werden zudem angewiesen, die Vollzähligkeit bzw. Abwesenheit einzelner ihre Schüler der Einsatzleitung zu melden!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_wichtig" onclick="cms_notfallzustand(\''+wert+'\', \''+app+'\')">Notfallzustand ausrufen</span></p>');
  }
  if (wert == '0') {
    cms_meldung_notfall('warnung', 'Notfallzustand aufheben', '<p>Möchten Sie den Notfallzustand wirklich aufheben? Alle Notfallmeldungen werden damit gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_wichtig" onclick="cms_notfallzustand(\''+wert+'\', \''+app+'\')">Notfallzustand aufheben</span></p>');
  }
}

function cms_notfallzustand(wert, app) {
  var app = app ||' nein';
  var meldung = '<p>Die Notfallzustand kann nichg geändert werden, denn ...</p><ul>';
  var fehler = false;

  if (!cms_check_toggle(wert)) {
    meldung += '<li>die Eingabe für den aktuellen Notfallzustand ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
     cms_meldung_an ('fehler', 'Notfallzustand ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var formulardaten = new FormData();
  	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("notfall", wert);
    formulardaten.append("anfragenziel", '33');

  	function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == 'ERFOLG') {
        if (app != 'app') {cms_link('Schulhof/Nutzerkonto');}
        else {cms_link('App');}
      }
      else {
        cms_fehlerbehandlung(rueckgabe);
      }
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}


function cms_tagebuchdetails_laden() {
  var meldung = '<p>Die Tagebucheinträge können nicht geladen werden, denn ...</p><ul>';
  var kurse = document.getElementById('cms_tagebuch_kurse').value;
  var kursecheck = kurse;
  var freigabe = document.getElementById('cms_tagebuch_freigabe').value;
  if (kurse.substr(-2,2) == '|s') {kursecheck = kurse.substr(0,kurse.length-2);}

  var fehler = false;

  if (!cms_check_toggle(freigabe)) {
    meldung += '<li>die Auswahl zu den freigegebenen Einträgen ist ungültig.</li>'
    fehler = true;
  }
  if (!cms_check_liste(kursecheck)) {
    meldung += '<li>die Auswahl der Kurse ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
     cms_meldung_an ('fehler', 'Tagebucheinträge laden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var gewaehlt = "";
    var ausgeschlossen = "";
    if (kurse.length>0) {
      var kursetest = kurse.substr(1).split('|');
      for (var i=0; i<kursetest.length; i++) {
        if (document.getElementById('cms_tagebuch_kurs_'+kursetest[i]).value == 1) {gewaehlt += '|'+kursetest[i];}
        else {ausgeschlossen += '|'+kursetest[i];}
      }
    }

    var formulardaten = new FormData();
  	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("freigabe", freigabe);
    formulardaten.append("gewaehlt", gewaehlt);
    formulardaten.append("ausgeschlossen", ausgeschlossen);
    formulardaten.append("anfragenziel", '32');

  	function anfragennachbehandlung(rueckgabe) {
      document.getElementById('cms_persoenlichestagebuch').innerHTML = rueckgabe;
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}

function cms_tagebuch_eintraege_ausblenden() {
  var felder = document.getElementById('cms_tagebuch_eintraege').value;
  felder = felder.split(",");
  for (var i=0; i<felder.length; i++) {
    var f = document.getElementById("cms_tagebuch_eintrag_"+felder[i]);
    if (f)  {
      f.style.display = 'none';
      f.innerHTML = '<td colspan="6"></td>';
    }
  }
}

function cms_tagebuch_eintragbearbeiten(id) {
  if (cms_check_ganzzahl(id,0)) {
    cms_tagebuch_eintraege_ausblenden();
    var feld = document.getElementById("cms_tagebuch_eintrag_"+id);
    feld.style.display = 'table-row';
    feld.innerHTML = '<td colspan="6" class="cms_zentriert">'+cms_ladeicon()+'</td>';

    var formulardaten = new FormData();
  	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("eintrag", id);
    formulardaten.append("anfragenziel", '34');

  	function anfragennachbehandlung(rueckgabe) {
      feld.innerHTML = rueckgabe;
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}

function cms_eintrag_fzdazu(beginn, ende) {
  var feld = document.getElementById('cms_eintrag_fz_p');
  if (feld.options[feld.selectedIndex]) {
    var pid = feld.value;
    var name = feld.options[feld.selectedIndex].text;
    var liste = document.getElementById('cms_tagebuch_fehlzeiten');
    var anzahl = document.getElementById('cms_tagebuch_eintrag_fzan');
    var ids = document.getElementById('cms_tagebuch_eintrag_fzids');
    var neueid = 'temp'+anzahl.value;

    var start = new Date(beginn*1000);
    var ziel = new Date(ende*1000);
    var code = "<input type=\"hidden\" name=\"cms_eintrag_fzp_"+neueid+"\" id=\"cms_eintrag_fzp_"+neueid+"\" value=\""+pid+"\">";
    code += cms_uhrzeit_eingabe('cms_eintrag_fz_beginn_'+neueid, start.getHours(), start.getMinutes());
    code += " – "+cms_uhrzeit_eingabe('cms_eintrag_fz_ende_'+neueid, ziel.getHours(), ziel.getMinutes());
    code += ": "+name+"<br>";
    code += "<input class=\"cms_gross\" type=\"text\" name=\"cms_eintrag_fz_bem_"+neueid+"\" id=\"cms_eintrag_fz_bem_"+neueid+"\" value=\"\"> ";
    code += "<span class=\"cms_button_nein\" onclick=\"cms_eintrag_fzweg('"+neueid+"')\">–</span>";

    var knoten = document.createElement("LI");
    knoten.innerHTML = code;
    liste.appendChild(knoten);
    knoten.setAttribute('id', 'cms_eintrag_fz_'+neueid);
    anzahl.value = parseInt(anzahl.value)+1;
    if (ids.value.length > 0) {ids.value = ids.value+','+neueid;}
    else {ids.value = neueid;}
  }
}

function cms_eintrag_fzweg(id) {
  var liste = document.getElementById('cms_tagebuch_fehlzeiten');
	var fz = document.getElementById('cms_eintrag_fz_'+id);
	var ids = document.getElementById('cms_tagebuch_eintrag_fzids');

  if (fz) {
    liste.removeChild(fz);
  	var neueids = (','+ids.value+',').replace(','+id+',', ',');
    ids.value = neueids.substr(1,neueids.length-2);
  }
}

function cms_eintrag_ltdazu() {
  var feld = document.getElementById('cms_eintrag_lt_p');
  if (feld.options[feld.selectedIndex]) {
    var pid = feld.value;
    var name = feld.options[feld.selectedIndex].text;
    var liste = document.getElementById('cms_tagebuch_bemerkungen');
    var anzahl = document.getElementById('cms_tagebuch_eintrag_ltan');
    var ids = document.getElementById('cms_tagebuch_eintrag_ltids');
    var neueid = 'temp'+anzahl.value;

    var code = "<input type=\"hidden\" name=\"cms_eintrag_ltp_"+neueid+"\" id=\"cms_eintrag_ltp_"+neueid+"\" value=\""+pid+"\">";
    code += "<span class=\"cms_button\" id=\"cms_eintrag_ltart_knopf_"+neueid+"\" onclick=\"cms_ltart_aendern('"+neueid+"')\">Bemerkung</span> ";
    code += "<span class=\"cms_button_nein\" id=\"cms_eintrag_ltchar_knopf_"+neueid+"\" onclick=\"cms_ltchar_aendern('"+neueid+"')\">negativ</span>: ";
    code += name+"<br>";
    code += "<input class=\"cms_gross\" type=\"text\" name=\"cms_eintrag_lt_bem_"+neueid+"\" id=\"cms_eintrag_lt_bem_"+neueid+"\" value=\"\"> ";
    code += "<span class=\"cms_button_nein\" onclick=\"cms_eintrag_ltweg('"+neueid+"')\">–</span> ";
    code += "<input type=\"hidden\" name=\"cms_eintrag_ltchar_"+neueid+"\" id=\"cms_eintrag_ltchar_"+neueid+"\" value=\"-\">";
    code += "<input type=\"hidden\" name=\"cms_eintrag_ltart_"+neueid+"\" id=\"cms_eintrag_ltart_"+neueid+"\" value=\"B\">";

    var knoten = document.createElement("LI");
    knoten.innerHTML = code;
    liste.appendChild(knoten);
    knoten.setAttribute('id', 'cms_eintrag_lt_'+neueid);
    anzahl.value = parseInt(anzahl.value)+1;
    if (ids.value.length > 0) {ids.value = ids.value+','+neueid;}
    else {ids.value = neueid;}
  }
}

function cms_ltchar_aendern(id) {
  var knopf = document.getElementById("cms_eintrag_ltchar_knopf_"+id);
  var wert = document.getElementById("cms_eintrag_ltchar_"+id);

  if (wert.value == '-') {
    wert.value = '0';
    knopf.innerHTML = 'neutral';
    knopf.className = 'cms_button';
  }
  else if (wert.value == '0') {
    wert.value = '+';
    knopf.innerHTML = 'positiv';
    knopf.className = 'cms_button_ja';
  }
  else if (wert.value == '+') {
    wert.value = '-';
    knopf.innerHTML = 'negativ';
    knopf.className = 'cms_button_nein';
  }
}

function cms_ltart_aendern(id) {
  var knopf = document.getElementById("cms_eintrag_ltart_knopf_"+id);
  var wert = document.getElementById("cms_eintrag_ltart_"+id);

  if (wert.value == 'B') {
    wert.value = 'M';
    knopf.innerHTML = 'Mitarbeit';
  }
  else if (wert.value == 'M') {
    wert.value = 'V';
    knopf.innerHTML = 'Verhalten';
  }
  else if (wert.value == 'V') {
    wert.value = 'B';
    knopf.innerHTML = 'Bemerkung';
  }
}

function cms_eintrag_ltweg(id) {
  var liste = document.getElementById('cms_tagebuch_bemerkungen');
	var lt = document.getElementById('cms_eintrag_lt_'+id);
	var ids = document.getElementById('cms_tagebuch_eintrag_ltids');

  if (lt) {
    liste.removeChild(lt);
  	var neueids = (','+ids.value+',').replace(','+id+',', ',');
    ids.value = neueids.substr(1,neueids.length-2);
  }
}

function cms_tagebuch_eintrag_speichern(freigabe) {
  cms_laden_an('Tagebucheintrag speichern', 'Die Eingaben werden überprüft.');
  var meldung = '<p>Der Tagebucheintrag konnte nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
	var inhalt = document.getElementById('cms_tagebuch_eintrag_inhalt').value;
	var hausi = document.getElementById('cms_tagebuch_eintrag_hausi').value;
	var leistung = document.getElementById('cms_cms_tagebuch_eintrag_lm').value;
	var fzids = document.getElementById('cms_tagebuch_eintrag_fzids').value;
	var ltids = document.getElementById('cms_tagebuch_eintrag_ltids').value;

	var ierlaubt = document.getElementById('cms_tagebuch_eintrag_ierlaubt').value;
	var ferlaubt = document.getElementById('cms_tagebuch_eintrag_ferlaubt').value;
	var lterlaubt = document.getElementById('cms_tagebuch_eintrag_lterlaubt').value;

  if (ierlaubt == '1') {
    if (inhalt.length == 0) {
  		meldung += '<li>es muss ein Unterrichtsgegenstand angegeben werden.</li>';
  		fehler = true;
  	}
    if (!cms_check_toggle(leistung)) {
  		meldung += '<li>die Eingabe für die Leistungsmessung ist ungültig.</li>';
  		fehler = true;
  	}
  }

  var fzfehler = false;
  if ((ferlaubt == '1') && (fzids.length > 0)) {
    if (cms_check_templiste(fzids)) {
      fzids = fzids.split(',');
      var fehlzeiten = "";
      for (var i=0; i<fzids.length; i++) {
        var fzifehler = false;
        var pers = document.getElementById('cms_eintrag_fzp_'+fzids[i]).value;
        var vstd = document.getElementById('cms_eintrag_fz_beginn_'+fzids[i]+'_h').value;
        var vmin = document.getElementById('cms_eintrag_fz_beginn_'+fzids[i]+'_m').value;
        var bstd = document.getElementById('cms_eintrag_fz_ende_'+fzids[i]+'_h').value;
        var bmin = document.getElementById('cms_eintrag_fz_ende_'+fzids[i]+'_m').value;
        var von = vstd+':'+vmin;
        var bis = bstd+':'+bmin;
        var bem = document.getElementById('cms_eintrag_fz_bem_'+fzids[i]).value;
        if (!cms_check_ganzzahl(pers)) {fzifehler = true;}
        if (!cms_check_uhrzeit(von)) {fzifehler = true;}
        if (!cms_check_uhrzeit(bis)) {fzifehler = true;}
        if (!cms_check_bemerkung(bem)) {fzifehler = true;}
        if (!fzifehler) {
          fehlzeiten += fzids[i]+"|$|"+pers+"|$|"+von+"|$|"+bis+"|$|"+bem+"\n";
        }
        else {
          fzfehler = true;
        }
      }
    }
    else {
      fzfehler = true;
    }

    if (fzfehler) {
  		meldung += '<li>die Eingabe der Fehlzeiten ist ungültig. Stellen Sie sicher, dass die Fehlzeittexte die Zeichenkette »|$|« nicht enthalten.</li>';
  		fehler = true;
  	}
  }

  var ltfehler = false;
  if ((lterlaubt == '1') && (ltids.length > 0)) {
    if (cms_check_templiste(ltids)) {
      ltids = ltids.split(',');
      var lobtadel = "";
      for (var i=0; i<ltids.length; i++) {
        var ltifehler = false;
        var pers = document.getElementById('cms_eintrag_ltp_'+ltids[i]).value;
        var bem = document.getElementById('cms_eintrag_lt_bem_'+ltids[i]).value;
        var charakter = document.getElementById('cms_eintrag_ltchar'+ltids[i]).value;
        var art = document.getElementById('cms_eintrag_ltart_'+ltids[i]).value;
        if ((!cms_check_ganzzahl(pers)) && (pers != '-')) {ltifehler = true;}
        if (!cms_check_bemerkung(bem)) {ltifehler = true;}
        if ((charakter != '+') && (charakter != '-') && (charakter != '0')) {ltifehler = true;}
        if ((art != 'B') && (art != 'M') && (art != 'V')) {ltifehler = true;}
        if (!ltifehler) {
          lobtadel += ltids[i]+"|$|"+pers+"|$|"+char+"|$|"+art+"|$|"+bem+"\n";
        }
        else {
          ltfehler = true;
        }
      }
    }
    else {
      fzfehler = true;
    }

    if (ltfehler) {
  		meldung += '<li>die Eingabe der Bemerkungen ist ungültig. Stellen Sie sicher, dass die Bemerkungstexte die Zeichenkette »|$|« nicht enthalten.</li>';
  		fehler = true;
  	}
  }

  var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("anfragenziel", '35');
  formulardaten.append("inhalt", inhalt);
  formulardaten.append("hausi", hausi);
  formulardaten.append("leistung", leistung);
  formulardaten.append("fehlzeiten", fehlzeiten);
  formulardaten.append("lobtadel", lobtadel);

	function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_tagebuchdetails_laden();
    }
    else if (false) {

    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}
