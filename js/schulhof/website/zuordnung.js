function cms_zuordnung_aktualisieren() {
	var feld = document.getElementById('cms_zuordnungsauswahl_ergebnisse');
	feld.innerHTML = '<img src="res/laden/standard.gif"><br><br>Gruppen werden geladen. Bitte warten...';
	feld.style.textAlign = 'center';
	var gruppe = document.getElementById('cms_zuordnungsauswahl_gruppe').value;
	var zugeordnet = document.getElementById('cms_zuorndung_zugeordnetegruppen_'+cms_textzudb(gruppe)).value;
	var schuljahr = document.getElementById('cms_zuordnungsauswahl_schuljahr').value;

	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'219');
	formulardaten.append("gruppe", gruppe);
	formulardaten.append("zugeordnet", zugeordnet);
	formulardaten.append("schuljahr", schuljahr);

	function anfragennachbehandlung(rueckgabe) {
		var feld = document.getElementById('cms_zuordnungsauswahl_ergebnisse');
		if (rueckgabe.substr(0,6) == "<span ") {
			feld.style.textAlign = 'left';
			feld.innerHTML = rueckgabe;
		}
		else {
			feld.style.textAlign = 'left';
			feld.innerHTML = '<p class=\"cms_notiz\">Bei der Verarbeitung der Anfrage ist ein Fehler aufgetreten.</p>';
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_gruppe_zuordnen(gruppe, id) {
	var feld = document.getElementById('cms_zuorndung_zugeordnetegruppen_'+gruppe);
	var ergebnisse = document.getElementById('cms_zuordnungsauswahl_zugeordnet');
	if (feld) {
		var vorhanden = (feld.value).split('|');
		if (vorhanden.indexOf(id) == -1) {
			feld.value = feld.value+'|'+id;
			var titel = document.getElementById('cms_zuordnen_'+gruppe+'_'+id).innerHTML;
			var code = "<span class=\"cms_toggle cms_toggle_aktiv\" id=\"cms_zugeordnet_"+gruppe+"_"+id+"\" onclick=\"cms_gruppe_abordnen('"+gruppe+"', '"+id+"')\">";
			code += cms_vornegross(gruppe)+" » "+titel;
			code += "</span>";
			ergebnisse.innerHTML = ergebnisse.innerHTML+code+' ';
			cms_ausblenden('cms_zuordnungsauswahl_feld');
			cms_zuordnung_aktualisieren();
		}
	}
}

function cms_gruppe_abordnen(gruppe, id) {
	var feld = document.getElementById('cms_zuorndung_zugeordnetegruppen_'+gruppe);
	var button = document.getElementById('cms_zugeordnet_'+gruppe+'_'+id);
	if (feld) {
		var vorhanden = feld.value+'|';
		vorhanden = vorhanden.replace("|"+id+"|", "|");
		vorhanden = vorhanden.slice(0,(vorhanden.length-1));
		feld.value = vorhanden;
		button.remove();
		cms_zuordnung_aktualisieren();
	}
}
