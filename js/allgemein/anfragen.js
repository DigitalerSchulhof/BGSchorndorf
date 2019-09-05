function cms_ajaxanfrage (fehler, formulardaten, wennrichtig, host) {
	if (!fehler) {
		var host = host || '';
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				wennrichtig(anfrage.responseText);
			}
		};
		var ziel;
		if(ziel = formulardaten.get("anfragenziel"))
			formulardaten.delete("anfragenziel");
		anfrage.open("POST",host+"php/oeffentlich/anfragen/anfrage.php?ziel="+ziel,true);
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

function cms_fehlerbehandlungfeld(feld, rueckgabe) {
	if (rueckgabe == "BERECHTIGUNG") {feld.innerHTML = cms_meldung_berechtigung_code();}
	else if (rueckgabe == "BASTLER") {feld.innerHTML = cms_meldung_bastler_code();}
	else if (rueckgabe == "FEHLER") {feld.innerHTML = cms_meldung_fehler_code();}
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
