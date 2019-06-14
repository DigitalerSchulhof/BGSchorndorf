function cms_ajaxanfrage (fehler, formulardaten, wennrichtig, host) {
	if (!fehler) {
		var host = host || '';
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				wennrichtig(anfrage.responseText);
			}
		};
		anfrage.open("POST",host+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
	else {
		cms_meldung_fehler();
	}
}

function cms_fehlerbehandlung(rueckgabe) {
	if (rueckgabe == "BERECHTIGUNG") {cms_meldung_berechtigung();}
	else if (rueckgabe == "BASTLER") {cms_meldung_bastler();}
	else if (rueckgabe == "FEHLER") {cms_meldung_fehler();}
	else {cms_fehlerausgabe(rueckgabe);}
}

function cms_debug(anfrage) {
	document.getElementById('cms_debug').innerHTML = anfrage.responseText;
	document.getElementById('cms_debug').style.display = 'block';
	cms_meldung_aus();
}

function cms_fehlerausgabe(rueckgabe) {
	document.getElementById('cms_debug').innerHTML = rueckgabe;
	document.getElementById('cms_debug').style.display = 'block';
	cms_meldung_aus();
}
