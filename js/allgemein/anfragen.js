/*
	cms_ajaxanfrage(ziel);
	cms_ajaxanfrage(ziel, daten);
	cms_ajaxanfrage(ziel, titel, daten);
	cms_ajaxanfrage(ziel, array[titel, tätigkeit], daten[, host]);

	Ist titel null, wird laden nicht angezeigt
	Ist host === true, dann CMS_LN_DA
	Ist host === CMS_LN_DA, werden cms_lehrerdatenbankzugangsdaten_schicken an die Daten gehängt
*/
function cms_ajaxanfrage (a, b, c, d) {
	var host = host || '';

	var daten;

	var titel, taetigkeit, daten, host;

	if(a instanceof FormData) {
		// Rückwärtskompatibilität
		daten = a;
		var callback = b;
		host = c || "";
	} else {
		if(Array.isArray(b)) {
			switch(b.length) {
				case 0:
					return cms_ajaxanfrage(a, [null, null], c, d);
				case 1:
					return cms_ajaxanfrage(a, [b[1], null], c, d);
			}
		} if(b instanceof FormData) {
			return cms_ajaxanfrage(a, [null, null], b, c);
		} else {
			if(b === undefined || b === null) {
				return cms_ajaxanfrage (a, [null, null], c, d);
			}
			if(typeof b === "string") {
				return cms_ajaxanfrage (a, [b, null], c, d);
			}
		}
		if(c === undefined || c === null) {
			return cms_ajaxanfrage (a, b, {}, d);
		}

		if(d === undefined || d === null) {
			return cms_ajaxanfrage(a, b, c, "");
		} else if(d === true) {
			return cms_ajaxanfrage (a, b, c, CMS_LN_DA);
		}

		titel = b[0];
		taetigkeit = b[1] || "";
		if(c instanceof FormData) {
			daten = c;
			if(a !== null) {
				daten.append("anfragenziel", a);
			}
		} else {
			daten = new FormData();
			for(var k in c) {
				daten.append(k, c[k]);
			}
			daten.append("anfragenziel", a);
		}
		host = d;
		if(host === CMS_LN_DA) {
			cms_lehrerdatenbankzugangsdaten_schicken(daten);
		}
		if(titel !== null) {
			cms_laden_an(titel, taetigkeit);
		}
	}
	return new Promise((erfolg) => {
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				erfolg(anfrage.responseText);
				// Rückwärtskompatibilität
				if (typeof callback === "function") {callback(anfrage.responseText);}
			}
		};
		anfrage.open("POST",host+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(daten);
	});
}

/*
	ziel: 			Das Ziel der Anfrage
	laden:			Wenn String: als Titel interpretiert, wenn Array: laden[0] als Titel, laden[1] als Tätigkeit
	arrays:			Die Anfragewerte als Array - Fehler wenn leer
	statisch: 	Statische Anfragewerte, die bei bei jeder Anfrage gleich sind - Strandard {}
	host:				Host - Bei true CMS_LN_DA UND cms_lehrerdatenbankzugangsdaten_schicken an die Daten
	rueckgabenbehandlung:	Callback der Rückgaben, nimmt array an Rückgabewerten, gibt Rückgabewert an .then(x) zurück
*/
function cms_multianfrage(ziel, laden, arrays, statisch, host, rueckgabenbehandlung) {
	var titel, taetigkeit;
	if(ziel === undefined) {
		return false;
	}
	if(typeof laden === "string") {
		titel = laden;
		taetigkeit = "";
	} else if(Array.isArray(laden)) {
		titel = laden[0];
		taetigkeit = laden[1];
	}
	if(arrays === undefined || arrays === null) {
		arrays = {};
	}
	if(host === true) {
		host = CMS_LN_DA;
	}
	host = host || "";
	if(statisch === undefined || statisch === null) {
		statisch = {};
	}
	if(typeof rueckgabenbehandlung !== "function") {
		rueckgabenbehandlung = (rueckgaben) => {
			let rueckgabe = "ERFOLG";
			rueckgaben.forEach((rueck) => {
				if(rueck != "ERFOLG") {
					rueckgabe = rueck;
				}
			});
			return rueckgabe;
		}
	}

	var rueckgaben = [];
	var daten = [];
	let k = Object.keys(arrays).shift();
	let werte = arrays[k];
	werte.forEach((w, i) => {
		let data = new FormData();
		for(var sk in statisch) {
			data.append(sk, statisch[sk]);
		}
		for(var ak in arrays) {
			data.append(ak, arrays[ak][i]);
		}
		daten.push(data);
	});

	if(host === CMS_LN_DA) {
		cms_lehrerdatenbankzugangsdaten_schicken(daten);
	}

	var max = daten.length;

	cms_einblenden('cms_blende_o');
	var feld = document.getElementById('cms_blende_i');
	var neuemeldung = '<div class="cms_spalte_i">';
	neuemeldung += '<h2 id="cms_laden_ueberschrift">'+titel+'</h2>';
	neuemeldung += '<p id="cms_laden_meldung_vorher">'+taetigkeit+'</p>';
	neuemeldung += '<div class="cms_fortschritt_box">';
		neuemeldung += '<h4>Fortschritt</h4>';
		neuemeldung += '<div class="cms_fortschritt_o">';
			neuemeldung += '<div class="cms_fortschritt_i" id="cms_multianfrage_balken_aktuell" style="width: 0%;"></div>';
		neuemeldung += '</div>'
		neuemeldung += '<p class="cms_multianfrage_fortschritt_anzeige">Abgeschlossen: <span id="cms_multianfrage_fortschritt_nr">0</span>/'+max+'</p>';
	neuemeldung += '</div></div>';
	feld.innerHTML = neuemeldung;

	return new Promise((erfolg) => {
		function anfrage(i) {
			cms_ajaxanfrage (ziel, null, daten[i], host).then((rueckgabe) => {
				rueckgaben.push(rueckgabe);
				document.getElementById('cms_multianfrage_fortschritt_nr').innerHTML = i+1;
				document.getElementById('cms_multianfrage_balken_aktuell').style.width = (100*(i+1))/max+'%';
				if(i == max-1) {
					// Letztes Element
					setTimeout(() => {
						cms_laden_aus();
						rueckgabe = rueckgabenbehandlung(rueckgaben);
						erfolg(rueckgabe);
					}, 100);
				} else {
					anfrage(i+1);
				}
			});
		}
		anfrage(0);
	});
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
