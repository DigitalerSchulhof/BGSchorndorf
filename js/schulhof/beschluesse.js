function cms_neuer_beschluss() {
  var box = document.getElementById('cms_beschluesse');
	var anzahl = document.getElementById('cms_beschluesse_anzahl');
	var nr = document.getElementById('cms_beschluesse_nr');
	var ids = document.getElementById('cms_beschluesse_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";
	code += "<tr><th>Titel:</th><td colspan=\"6\"><input type=\"text\" name=\"cms_beschluss_titel_"+neueid+"\" id=\"cms_beschluss_titel_"+neueid+"\" value=\"\"></td></tr>";
	code += "<tr><th>Beschreibung:</th><td colspan=\"6\"><textarea name=\"cms_beschluss_beschreibung_"+neueid+"\" id=\"cms_beschluss_beschreibung_"+neueid+"\"></textarea></td></tr>";
  code += "<tr><th>Langfristig:</th><td colspan=\"6\">"+cms_generiere_schieber('cms_beschluss_langfristig_'+neueid, 0)+"</td></tr>";
  code += "<tr><th>Stimmen:</th><td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">Dafür</span><img src=\"res/icons/klein/pro.png\"></span></td>";
  code += "<td><input type=\"number\" min=\"0\" step=\"1\" value=\"0\" name=\"cms_beschluss_pro_"+neueid+"\" id=\"cms_beschluss_pro_"+neueid+"\"></td>";
  code += "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">Enthaltung</span><img src=\"res/icons/klein/egal.png\"></span></td>";
  code += "<td><input type=\"number\" min=\"0\" step=\"1\" value=\"0\" name=\"cms_beschluss_enthaltung_"+neueid+"\" id=\"cms_beschluss_enthaltung_"+neueid+"\"></td>";
  code += "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">Dagegen</span><img src=\"res/icons/klein/contra.png\"></span></td>";
  code += "<td><input type=\"number\" min=\"0\" step=\"1\" value=\"0\" name=\"cms_beschluss_contra_"+neueid+"\" id=\"cms_beschluss_contra_"+neueid+"\"></td>";
  code += "</tr>";
	code += "<tr><th></th><td colspan=\"6\"><span class=\"cms_button_nein\" onclick=\"cms_beschluss_entfernen('"+neueid+"');\">Beschluss löschen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_beschluss_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_beschluss_entfernen(id) {
	var box = document.getElementById('cms_beschluesse');
	var anzahl = document.getElementById('cms_beschluesse_anzahl');
	var ids = document.getElementById('cms_beschluesse_ids');
	var beschluss = document.getElementById('cms_beschluss_'+id);

	box.removeChild(beschluss);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}
