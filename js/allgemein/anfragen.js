function cms_ajaxanfrage (fehler, formulardaten, wennrichtig, host) {
	if (!fehler) {
		var host = host || '';
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (wennrichtig !== null) {wennrichtig(anfrage.responseText);}
			}
			if(anfrage.readyState==4 && anfrage.status!=200) {
				cms_meldung_an('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!<br>Die Anfrage wird in 5 Sekunden wiederholt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
				setTimeout(function () {
					cms_laden_an("Anfrage wiederholen", "Die Anfrage wird wiederholt");
					cms_ajaxanfrage (fehler, formulardaten, wennrichtig, host);
				}, 5000);
			}
		};
		anfrage.onerror = function() {
			cms_meldung_an('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!<br>Die Anfrage wird in 5 Sekunden wiederholt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			setTimeout(function () {
				cms_laden_an("Anfrage wiederholen", "Die Anfrage wird wiederholt");
				cms_ajaxanfrage (fehler, formulardaten, wennrichtig, host);
			}, 5000);
		}
		anfrage.open("POST",host+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
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
