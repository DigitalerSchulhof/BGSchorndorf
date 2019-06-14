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
