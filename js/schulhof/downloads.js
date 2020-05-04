function cms_neuer_download() {
  var box = document.getElementById('cms_downloads');
	var anzahl = document.getElementById('cms_downloads_anzahl');
	var nr = document.getElementById('cms_downloads_nr');
	var ids = document.getElementById('cms_downloads_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";
	code += "<tr><th>Titel:</th><td colspan=\"4\"><input type=\"text\" name=\"cms_download_titel_"+neueid+"\" id=\"cms_download_titel_"+neueid+"\" value=\"\"></td></tr>";
	code += "<tr><th>Beschreibung:</th><td colspan=\"4\"><textarea name=\"cms_download_beschreibung_"+neueid+"\" id=\"cms_download_beschreibung_"+neueid+"\"></textarea></td></tr>";
	code += "<tr><th>Datei:</th>";
	code += "<td colspan=\"4\"><input id=\"cms_download_datei_"+neueid+"\" name=\"cms_download_datei_"+neueid+"\" type=\"hidden\" value=\"\">";
	code += "<p class=\"cms_notiz cms_vorschau\" id=\"cms_download_datei_"+neueid+"_vorschau\">Keine Datei ausgewählt</p>";
		code += "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'website', '-', 'website', 'cms_download_datei_"+neueid+"', 'download')\">Datei auswählen</span></p>";
		code += "<p id=\"cms_download_datei_"+neueid+"_verzeichnis\"></p>";
	code += "</td></tr>";
	code += "<tr><th></th><th>Dateiname anzeigen:</th><td>"+cms_generiere_schieber('cms_download_dateiname_'+neueid, '1')+"</td><th>Dateigröße anzeigen:</th><td>"+cms_generiere_schieber('cms_download_dateigroesse_'+neueid, '1')+"</td></tr>";
	code += "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_download_entfernen('"+neueid+"');\">Download löschen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_download_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_neuer_internerdownload(gruppe, gruppenid) {
  var box = document.getElementById('cms_downloads');
	var anzahl = document.getElementById('cms_downloads_anzahl');
	var nr = document.getElementById('cms_downloads_nr');
	var ids = document.getElementById('cms_downloads_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";
	code += "<tr><th>Titel:</th><td colspan=\"4\"><input type=\"text\" name=\"cms_download_titel_"+neueid+"\" id=\"cms_download_titel_"+neueid+"\" value=\"\"></td></tr>";
	code += "<tr><th>Beschreibung:</th><td colspan=\"4\"><textarea name=\"cms_download_beschreibung_"+neueid+"\" id=\"cms_download_beschreibung_"+neueid+"\"></textarea></td></tr>";
	code += "<tr><th>Datei:</th>";
	code += "<td colspan=\"4\"><input id=\"cms_download_datei_"+neueid+"\" name=\"cms_download_datei_"+neueid+"\" type=\"hidden\" value=\"\">";
	code += "<p class=\"cms_notiz cms_vorschau\" id=\"cms_download_datei_"+neueid+"_vorschau\">Keine Datei ausgewählt</p>";
		code += "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'gruppe', '"+gruppenid+"', 'schulhof/gruppen/"+gruppe+"/"+gruppenid+"', 'cms_download_datei_"+neueid+"', 'download', '"+gruppe+"', '"+gruppenid+"')\">Datei auswählen</span></p>";
		code += "<p id=\"cms_download_datei_"+neueid+"_verzeichnis\"></p>";
	code += "</td></tr>";
	code += "<tr><th></th><th>Dateiname anzeigen:</th><td>"+cms_generiere_schieber('cms_download_dateiname_'+neueid, '1')+"</td><th>Dateigröße anzeigen:</th><td>"+cms_generiere_schieber('cms_download_dateigroesse_'+neueid, '1')+"</td></tr>";
	code += "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_download_entfernen('"+neueid+"');\">Download löschen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_download_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_download_entfernen(id) {
	var box = document.getElementById('cms_downloads');
	var anzahl = document.getElementById('cms_downloads_anzahl');
	var ids = document.getElementById('cms_downloads_ids');
	var download = document.getElementById('cms_download_'+id);

	box.removeChild(download);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}
