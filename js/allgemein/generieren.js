function cms_uhrzeit_eingabe (id, stunde, minute, zusatzaktion) {
  var jetzt = new Date();
  var stunde = stunde || jetzt.getHours();
  var minute = minute || jetzt.getMinutes();

  if (minute < 10) {minute = '0'+minute;}
  if (stunde < 10) {stunde = '0'+stunde;}

  var ausgabe  = "<input class=\"cms_input_h\" type=\"text\" name=\""+id+"_h\" id=\""+id+"_h\" onchange=\"cms_uhrzeitcheck('"+id+"');"+zusatzaktion+"\" value=\""+stunde+"\"/> : ";
  ausgabe += "<input class=\"cms_input_m\" type=\"text\" name=\""+id+"_m\" id=\""+id+"_m\" onchange=\"cms_uhrzeitcheck('"+id+"');"+zusatzaktion+"\" value=\""+minute+"\"/>";
  return ausgabe;
}

function cms_datum_eingabe (id, tag, monat, jahr, zusatzaktion) {
  var jetzt = new Date();
  var tag = tag || jetzt.getDate();
  var monat = monat || jetzt.getMonth()+1;
  var jahr = jahr || jetzt.getFullYear();
  var tagbez = cms_tagname(jetzt.getDay());

  if (tag < 10) {tag = '0'+tag;}
  if (monat < 10) {monat = '0'+monat;}

  var ausgabe  = "<span class=\"cms_input_Tbez\" id=\""+id+"_Tbez\">"+tagbez+"</span> ";
  ausgabe += "<input class=\"cms_input_T\" type=\"text\" name=\""+id+"_T\" id=\""+id+"_T\" onchange=\"cms_datumcheck('"+id+"');"+zusatzaktion+"\" value=\""+tag+"\"/> . ";
  ausgabe += "<input class=\"cms_input_M\" type=\"text\" name=\""+id+"_M\" id=\""+id+"_M\" onchange=\"cms_datumcheck('"+id+"');"+zusatzaktion+"\" value=\""+monat+"\"/> . ";
  ausgabe += "<input class=\"cms_input_J\" type=\"text\" name=\""+id+"_J\" id=\""+id+"_J\" onchange=\"cms_datumcheck('"+id+"');"+zusatzaktion+"\" value=\""+jahr+"\"/>";
  return ausgabe;
}


function cms_schieber_generieren(id, wert, zusatzaktion) {
	var zusatzaktion = zusatzaktion || '';
	var code = "";
  var vorsilbe = "in";
  if (wert == 1) {vorsilbe = "";}
  code += "<span class=\"cms_schieber_o_"+vorsilbe+"aktiv\" id=\"cms_schieber_"+id+"\" onclick=\"cms_schieber('"+id+"'); "+zusatzaktion+"\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_"+id+"\" id=\"cms_"+id+"\" value=\""+wert+"\">";
	return code;
}

function cms_togglebutton_generieren(id, buttontext, wert, zusatzaktion) {
	var zusatzaktion = zusatzaktion || "";
	if (wert != 1) {var zusatz = "in";} else {var zusatz = "";}
  return "<span class=\"cms_toggle_"+zusatz+"aktiv\" id=\""+id+"_K\" onclick=\"cms_togglebutton('"+id+"');"+zusatzaktion+"\">"+buttontext+"</span><input type=\"hidden\" id=\""+id+"\" name=\""+id+"\" value=\""+wert+"\">";
}

function cms_tagname(zahl) {
  if (zahl == 0) {return "SO";}
  if (zahl == 1) {return "MO";}
  if (zahl == 2) {return "DI";}
  if (zahl == 3) {return "MI";}
  if (zahl == 4) {return "DO";}
  if (zahl == 5) {return "FR";}
  if (zahl == 6) {return "SA";}
  else {return false;}
}

function cms_tagnamekomplett(zahl) {
  if (zahl == 0) {return "Sonntag";}
  if (zahl == 1) {return "Montag";}
  if (zahl == 2) {return "Dienstag";}
  if (zahl == 3) {return "Mittwoch";}
  if (zahl == 4) {return "Donnerstag";}
  if (zahl == 5) {return "Freitag";}
  if (zahl == 6) {return "Samstag";}
  else {return false;}
}

function cms_monatsnamekomplett(zahl) {
  if (zahl == 1) {return 'Januar';}
  else if (zahl == 2) {return 'Februar';}
  else if (zahl == 3) {return 'März';}
  else if (zahl == 4) {return 'April';}
  else if (zahl == 5) {return 'Mai';}
  else if (zahl == 6) {return 'Juni';}
  else if (zahl == 7) {return 'Juli';}
  else if (zahl == 8) {return 'August';}
  else if (zahl == 9) {return 'September';}
  else if (zahl == 10) {return 'Oktober';}
  else if (zahl == 11) {return 'November';}
  else if (zahl == 12) {return 'Dezember';}
  else {return false;}
}

function cms_monatsname(zahl) {
  if (zahl == 1) {return 'JAN';}
  else if (zahl == 2) {return 'FEB';}
  else if (zahl == 3) {return 'MÄR';}
  else if (zahl == 4) {return 'APR';}
  else if (zahl == 5) {return 'MAI';}
  else if (zahl == 6) {return 'JUN';}
  else if (zahl == 7) {return 'JUL';}
  else if (zahl == 8) {return 'AUG';}
  else if (zahl == 9) {return 'SEP';}
  else if (zahl == 10) {return 'OKT';}
  else if (zahl == 11) {return 'NOV';}
  else if (zahl == 12) {return 'DEZ';}
  else {return false;}
}

function cms_id_entfernen(feldid, id) {
  var feld = document.getElementById(feldid);
  var bisher = feld.value+'|';
  bisher = bisher.replace('|'+id+'|', '|');
  feld.value = bisher.slice(0,-1);
}

function cms_id_eintragen(feldid, id) {
  document.getElementById(feldid).value += '|'+id;
}

function cms_textzudb(wert) {
  return (wert.replace(' ', '')).toLowerCase();
} 

function cms_textzulink(wert) {
  return wert.replace(' ', '_');
} 

function cms_datumzweistellig(zahl) {
  if (zahl<=9) {
    return '0'+''+zahl;
  }
  else {
    return zahl;
  }
}

function cms_uebernehmen(idvon, idzu) {
  document.getElementById(idzu).value = document.getElementById(idvon).value;
}

function cms_farbbeispiel_waehlen(nr, id) {
	if (isNaN(nr)) {nr = 0;}
	if (nr % 1 != 0) {nr = 0;}
	if (nr > 47) {nr = 0;}

	// Alle deaktivieren
	for (var i=0; i<48; i++) {
		feld = document.getElementById('cms_farbbeispiel_'+i);
		feld.className = "cms_farbbeispiel cms_farbbeispiel_"+i;
	}

	document.getElementById('cms_farbbeispiel_'+nr).className = "cms_farbbeispiel_aktiv cms_farbbeispiel_"+nr;
	document.getElementById(id).value = nr;
}

function cms_ladeicon() {return "<div class=\"cms_ladeicon\"><div></div><div></div><div></div><div></div></div>";}
function cms_neue_captcha(uid) {
  cms_laden_an("Sicherheitsabfrage aktualisieren", "Die Daten werden neu geladen.")
  var img = $(".cms_spamschutz_"+uid);
  var box = $("#cms_spamverhinderung_"+uid);
  box.val("");
  var formulardaten = new FormData();

  formulardaten.append("alteuuid",      uid);
  formulardaten.append("anfragenziel",  '268');

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "FEHLER")
    cms_meldung_an('fehler', 'Neue Captcha', '<p>Bei der Erstellung einer neuen Sicherheitsabfrage ist ein unbekannter Fehler aufgetreten.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Korrigieren</span></p>');
    else {
      var r = $(rueckgabe);
      img.replaceWith(r);
      box.attr("id", "cms_spamverhinderung_"+r.data("uuid"));
    }
    cms_laden_aus();
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
