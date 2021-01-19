function cms_blockierung_neu() {
	var box = document.getElementById('cms_blockierungen');
	var anzahl = document.getElementById('cms_blockierungen_anzahl');
	var nr = document.getElementById('cms_blockierungen_nr');
	var ids = document.getElementById('cms_blockierungen_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";

	code += "<tr><th>Wochentag:</th><td><select name=\"cms_blockierungen_wochentag_"+neueid+"\" id=\"cms_blockierungen_wochentag_"+neueid+"\">";
	for (var j=1; j<=7; j++) {
		code += "<option value=\""+j+"\">"+cms_tagnamekomplett(j)+"</option>";
	}
	code += "</select><input type=\"hidden\" name=\"cms_blockierungen_id_"+neueid+"\" id=\"cms_blockierungen_id_"+neueid+"\" value=\""+neueid+"\"></td>";
	code += "<td><span class=\"cms_button_nein\" onclick=\"cms_blockierung_entfernen('"+neueid+"');\">&times;</span></td></tr>";
	code += "<tr><th>Beginn:</th><td colspan=\"2\">"+cms_uhrzeit_eingabe("cms_blockierungen_beginn_"+neueid)+"</td></tr>";
	code += "<tr><th>Ende:</th><td colspan=\"2\">"+cms_uhrzeit_eingabe("cms_blockierungen_ende_"+neueid)+"</td></tr>";
	code += "<tr><th>Grund:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_blockierungen_grund_"+neueid+"\" id=\"cms_blockierungen_grund_"+neueid+"\" value=\"\"></td></tr>";
	code += "<tr><th><span class=\"cms_hinweis_aussen\">Ferien:<span class=\"cms_hinweis\">Auch in den Ferien blockieren?</span></span></th><td colspan=\"2\">"+cms_generiere_schieber("blockierungen_ferien_"+neueid, 0)+"</td></tr>";

	var knoten = document.createElement("TABLE");

	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_blockierungen_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_blockierung_entfernen(id) {
	var box = document.getElementById('cms_blockierungen');
	var anzahl = document.getElementById('cms_blockierungen_anzahl');
	var ids = document.getElementById('cms_blockierungen_ids');
	var blockierung = document.getElementById('cms_blockierungen_'+id);

	box.removeChild(blockierung);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}


function cms_tagnamekomplett(t) {
	if (t == 0) {return 'Sonntag';}
  else if (t == 1) {return 'Montag';}
  else if (t == 2) {return 'Dienstag';}
  else if (t == 3) {return 'Mittwoch';}
  else if (t == 4) {return 'Donnerstag';}
  else if (t == 5) {return 'Freitag';}
  else if (t == 6) {return 'Samstag';}
  else if (t == 7) {return 'Sonntag';}
  else {return false;}
}
