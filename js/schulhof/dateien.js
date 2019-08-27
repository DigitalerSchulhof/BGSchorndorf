var CMS_UPLOADDATEIEN = [];
var UP_aktuell = 0;
var UP_aktuellnummer = 0;
var UP_aktuellname = '';
var UP_aktuellgesamt = 0;
var UP_aktuellgesamtanzeige = '0 B';
var UP_gesamt = 0;
var UP_gesamtanzahl = 0;
var UP_gesamtgesamt = 0;
var UP_gesamtgesamtanzeige = '0 B';
var UP_fehlgeschlagen = '';

var CMS_DATEIICONS = ["3gp", "7z", "ace", "ai", "aif", "aiff", "amr",
	"asf", "asx", "bat", "bin", "bmp", "bup", "cab", "cbr", "cda", "cdl", "cdr",
	"chm", "dat", "deb", "divx", "dll", "dmg", "doc", "docx", "dss", "dvf", "dwg",
	"eml", "eps", "exe", "fla", "flv", "gif",	"gpn", "gz", "hqx", "htm", "html",
	"ifo", "indd", "iso", "jar", "jpeg", "jpg", "lnk", "log", "m4a", "m4b", "m4p",
	"m4v", "mcd", "mdb", "midi", "mov", "mp2", "mp4", "mpeg", "mpg", "msi", "msw",
	"odp", "ods", "odt", "ogg", "pdf", "pkg", "png", "ppt", "pptx", "ps", "psd",
	"pst", "ptb", "pub", "pubx", "qbb", "qbw", "qxd", "ram", "rar", "rm", "rmvb",
	"rtf", "sea", "ses", "sit",	"sitx", "ss", "swf", "tgz", "thm", "tif", "tmp",
	"torrent", "ttf", "txt", "typ", "vcd", "vob", "wav", "wma", "wmv", "wps",
	"xls", "xlsx", "xpi", "zip"];

window.addEventListener("dragover", function() {event.preventDefault();}, false);

function cms_dateisystem_upload_dateiendazu(feldid, event) {
	event.stopPropagation();
	event.preventDefault();

	for (var i=0; i<event.dataTransfer.files.length; i++) {
		if (!Array.isArray(CMS_UPLOADDATEIEN[feldid])) {CMS_UPLOADDATEIEN[feldid] = [];}
		CMS_UPLOADDATEIEN[feldid].push(event.dataTransfer.files[i]);
	}

	cms_dateisystem_uploadliste_erneuern(feldid);
}

function cms_dateisystem_upload_dateiraus (feldid, nr) {
	CMS_UPLOADDATEIEN[feldid].splice(nr, 1);
	cms_dateisystem_uploadliste_erneuern(feldid);
}

function cms_uploadgesamtgroesse(feldid) {
	var gesamt = 0;
	for (var i=0; i<CMS_UPLOADDATEIEN[feldid].length; i++) {
		gesamt += CMS_UPLOADDATEIEN[feldid][i].size;
	}
	return gesamt;
}


function cms_dateisystem_uploadliste_erneuern(feldid) {
	var liste = document.getElementById(feldid+'_aktionen_hochladen_liste');
	var gesamt = document.getElementById(feldid+'_aktionen_hochladen_gesamtgroesse');
	var code = '';
	var uploadgroesse = cms_uploadgesamtgroesse(feldid);

	for (var i=0; i<CMS_UPLOADDATEIEN[feldid].length; i++) {
		code += '<li>'+cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][i].name)+' ('+cms_groesse_umrechnen(CMS_UPLOADDATEIEN[feldid][i].size)+')';
		code += ' <span class="cms_button_nein" onclick="cms_dateisystem_upload_dateiraus(\''+feldid+'\', '+i+')">';
		code += '<span class="cms_hinweis">Datei entfernen</span>&times;</span></li>';
	}

	if (code.length == 0) {
		code = '<li>keine Datein ausgewählt</li>';
	}

	liste.innerHTML = code;
	gesamt.innerHTML = 'Gesamtgröße: '+cms_groesse_umrechnen(uploadgroesse)+' • Anzahl Dateien: '+CMS_UPLOADDATEIEN[feldid].length;
}


function cms_dateisystem_aktionen_input_nutzen(feldid) {
	var dateifeld = document.getElementById(feldid+'_aktionen_hochladen_eingabe');
	for (var i=0; i<dateifeld.files.length; i++) {
		if (!Array.isArray(CMS_UPLOADDATEIEN[feldid])) {CMS_UPLOADDATEIEN[feldid] = [];}
		CMS_UPLOADDATEIEN[feldid].push(dateifeld.files[i]);
	}
	cms_dateisystem_uploadliste_erneuern(feldid);
	dateifeld.value = '';
}


function cms_dateisystem_aktionen_hochladen_aktualisieren(aname, agroesse, afortschritt, anummer, ganzahl, ggroesse, gfortschritt) {
	if (afortschritt > agroesse) {afortschritt = agroesse;}
	aprozent = Math.round(afortschritt/agroesse*10000)/100;
	document.getElementById('cms_hochladen_name_aktuell').innerHTML = aname;
	document.getElementById('cms_hochladen_nummer_aktuell').innerHTML = anummer+1;
	document.getElementById('cms_hochladen_groesse_aktuell').innerHTML = cms_groesse_umrechnen(agroesse);
	document.getElementById('cms_hochladen_fortschritt_aktuell').innerHTML = cms_groesse_umrechnen(afortschritt);
	document.getElementById('cms_hochladen_prozent_aktuell').innerHTML = ((aprozent.toString()).replace(/\./, ','))+' %';
	document.getElementById('cms_hochladen_balken_aktuell').style.width = aprozent+'%';

	if (gfortschritt > ggroesse) {gfortschritt = ggroesse;}
	gprozent = Math.round(gfortschritt/ggroesse*10000)/100;
	document.getElementById('cms_hochladen_anzahl_gesamt').innerHTML = ganzahl;
	document.getElementById('cms_hochladen_groesse_gesamt').innerHTML = cms_groesse_umrechnen(ggroesse);
	document.getElementById('cms_hochladen_fortschritt_gesamt').innerHTML = cms_groesse_umrechnen(gfortschritt);
	document.getElementById('cms_hochladen_prozent_gesamt').innerHTML = ((gprozent.toString()).replace(/\./, ','))+' %';
	document.getElementById('cms_hochladen_balken_gesamt').style.width = gprozent+'%';
}


function cms_dateisystem_aktionen_hochladen_fertig(netz, bereich, id, pfad, feldid) {
	cms_dateisystem_aktionen_hochladen_aktualisieren(UP_aktuellname, UP_aktuellgesamt, UP_aktuellgesamt, UP_aktuellnummer, UP_gesamtanzahl, UP_gesamtgesamt, UP_gesamtgesamt);
	var liste = document.getElementById(feldid+'_aktionen_hochladen_liste');
	var gesamt = document.getElementById(feldid+'_aktionen_hochladen_gesamtgroesse');
	var urheberrecht = document.getElementById(feldid+'_hochladen_urheberrecht');
	cms_ausblenden(feldid+'_aktionen_hochladen');
	liste.innerHTML = '<li>keine Datein ausgewählt</li>';
	gesamt.innerHTML = 'Gesamtgröße: '+cms_groesse_umrechnen(UP_gesamtgesamt)+' • Anzahl Dateien: '+CMS_UPLOADDATEIEN[feldid].length;
	var schieberid = feldid+'_hochladen_urheberrecht';
	if (urheberrecht.value != 0) {cms_schieber(schieberid.substr(4));}

	if (bereich != "anhang") {
		cms_ordnerwechsel(netz, bereich, id, pfad, feldid);
	}
	else {
		cms_dateisystem_anhang_aktualisieren();
	}
	button = "<span class=\"cms_button\" onclick=\"cms_meldung_aus()\">OK</span>";

	document.getElementById('cms_hochladen_fortschritt_gesamt').innerHTML = cms_groesse_umrechnen(UP_gesamtgesamt);
	document.getElementById('cms_hochladen_prozent_gesamt').innerHTML = '100 %';
	document.getElementById('cms_hochladen_balken_gesamt').style.width = '100%';
	document.getElementById('cms_laden_meldung_nachher').innerHTML = button;
	document.getElementById('cms_laden_meldung_vorher').innerHTML = 'Fertig!';
	document.getElementById('cms_hochladen_aktuelledatei').style.display = 'none';

	CMS_UPLOADDATEIEN[feldid] = [];
	UP_aktuell = 0;
	UP_aktuellnummer = 0;
	UP_aktuellname = '';
	UP_aktuellgesamt = 0;
	UP_aktuellgesamtanzeige = '0 B';
	UP_gesamt = 0;
	UP_gesamtanzahl = 0;
	UP_gesamtgesamt = 0;
	UP_gesamtgesamtanzeige = '0 B';
	UP_fehlgeschlagen = '';
}


function cms_dateisystem_aktionen_hochladen(netz, bereich, id, feldid) {
	cms_laden_an('Dateien hochladen', 'Die Eingaben werden überprüft.');
	var urheberrecht = document.getElementById(feldid+'_hochladen_urheberrecht').value;
	var skalieren = document.getElementById(feldid+'_bilderskalieren').value;
	var skalierengroesse = document.getElementById(feldid+'_skalieren_groesse').value;
	var fehler = false;
	var meldung = '<p>Der Upload konnte nicht durchgeführt werden, denn ...</p><ul>';
	var pfad = document.getElementById(feldid+'_pfad_feld').value;

	if (!((netz == 's') || ((netz == 'l') && (CMS_IMLN)))) {
		fehler = true;
		meldung += '<li>in dieses Netz können keine Dateien hochgeladen werden.</li>';
	}

	if (urheberrecht != 1) {
		fehler = true;
		meldung += '<li>ohne Urheberrecht können keine Dateien hochgeladen werden.</li>';
	}

	if ((skalieren != 1) && (skalieren != 0)) {
		fehler = true;
		meldung += '<li>die Angabe zum Sklaieren von Bildern ist ungültig.</li>';
	}

	if (skalieren == 1) {
		if ((!Number.isInteger(parseInt(skalierengroesse))) || (skalierengroesse < 1)) {
			fehler = true;
			meldung += '<li>Die Größe, auf die skaliert werden soll, ist ungültig.</li>';
		}
	}

	if (CMS_UPLOADDATEIEN[feldid].length < 1) {
		fehler = true;
		meldung += '<li>es wurden keine Dateien zum Upload ausgewählt.</li>';
	}
	for (var i=0; i<CMS_UPLOADDATEIEN[feldid].length; i++) {
		if (CMS_UPLOADDATEIEN[feldid][i].size > CMS_MAX_DATEI) {
			meldung += '<li>die Datei <b>'+cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][i].name)+'</b> ist zu groß ('+cms_groesse_umrechnen(CMS_UPLOADDATEIEN[feldid][i].size)+'). Erlaubt sind bis zu '+cms_groesse_umrechnen(CMS_MAX_DATEI)+'.</li>';
			fehler = true;
		}
		if (!cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][i].name).match(/^([-a-zA-Z0-9_\.]{3,255})$/)) {
			meldung += '<li>die Datei <b>'+cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][i].name)+'</b> hat einen ungültigen Dateinamen. Es sind nur lateinische Buchstaben, arabische Ziffern und die Zeichen »-« und »_« zulässig. Außerdem darf der Dateiname nicht weniger als 3 Zeichen und nicht mehr als 255 Zeichen lang sein.</li>';
			fehler = true;
		}
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Dateien hochladen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		UP_aktuell = 0;
		UP_aktuellnummer = 0;
		UP_aktuellname = cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][0].name);
		UP_aktuellgesamt = CMS_UPLOADDATEIEN[feldid][0].size;
		UP_aktuellgesamtanzeige = cms_groesse_umrechnen(UP_aktuellgesamt);
		UP_gesamt = 0;
		UP_gesamtanzahl = CMS_UPLOADDATEIEN[feldid].length;
		UP_gesamtgesamt = cms_uploadgesamtgroesse(feldid);
		UP_gesamtgesamtanzeige = cms_groesse_umrechnen(UP_gesamtgesamt);
		UP_fehlgeschlagen = '';

		cms_dateisystem_hochladen_blende_vorbereiten();
		cms_einblenden('cms_blende_o');

		// Erste Datei hochladen
		cms_dateisystem_aktionen_hochladen_naechstedatei(netz, bereich, id, feldid, urheberrecht, skalieren, skalierengroesse);
	}
}

function cms_dateisystem_aktionen_hochladen_naechstedatei(netz, bereich, id, feldid, urheberrecht, skalieren, skalierengroesse) {
	var anzahl = CMS_UPLOADDATEIEN[feldid].length;
	// Prüfen, ob es noch Dateien hochzuladen gibt
	if (UP_aktuellnummer < anzahl) {
		cms_dateisystem_aktionen_hochladen_aktualisieren(UP_aktuellname, UP_aktuellgesamt, UP_aktuell, UP_aktuellnummer, UP_gesamtanzahl, UP_gesamtgesamt, UP_gesamt);
		var anfrage = new XMLHttpRequest();
		var formulardaten = new FormData();
		var pfad = document.getElementById(feldid+'_pfad_feld').value;
		var keinnetz = false;
		var netzhost = "";
		formulardaten.append('bereich', bereich);
		formulardaten.append('id', id);
		formulardaten.append('pfad', pfad);
		formulardaten.append('urheberrecht', urheberrecht);
		formulardaten.append('skalieren', skalieren);
		formulardaten.append('skalierengroesse', skalierengroesse);
		formulardaten.append('max', CMS_MAX_DATEI);
		formulardaten.append('datei', CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer], CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer].name);

		if ((netz == 'l') && (CMS_IMLN)) {
			netzhost = CMS_LN_DA;
			formulardaten.append("anfragenziel", 	'179');
			cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		}
		else if (netz == 's') {
			formulardaten.append("anfragenziel", 	'180');
		}
		else {
			keinnetz = true;
			cms_dateisystem_aktonen_hochladen_dateifehler('<b>'+cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer].name)+'</b> (Netzfehler)');
		}

		// Wenn Netztechnisch alles sauber ist, beginne Upload
		if (!keinnetz) {
			// FEHLERFALL
			anfrage.onerror = function(e) {
				cms_dateisystem_aktonen_hochladen_dateifehler('<b>'+cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer].name)+'</b> (Verbindungsfehler)');
				cms_dateisystem_aktionen_hochladen_naechstedatei_vorbereiten(false, feldid);
				if (UP_aktuellnummer < anzahl) {cms_dateisystem_aktionen_hochladen_naechstedatei(netz, bereich, id, feldid, urheberrecht, skalieren, skalierengroesse);}
				else {
					// Nach dem Upload wieder aufräumen und den Ursprung des Dateisystems wiederherstellen
					cms_dateisystem_aktionen_hochladen_fertig(netz, bereich, id, pfad, feldid);
				}
		  };
			// WÄHREND DES UPLOADS
			anfrage.upload.onprogress = function(e) {
	    	UP_aktuell = e.position || e.loaded;
				if (UP_aktuell > UP_aktuellgesamt) {UP_aktuell = UP_aktuellgesamt;}
	    	var gesamt = UP_gesamt + UP_aktuell;
				cms_dateisystem_aktionen_hochladen_aktualisieren(UP_aktuellname, UP_aktuellgesamt, UP_aktuell, UP_aktuellnummer, UP_gesamtanzahl, UP_gesamtgesamt, gesamt);
	    };
			// FERTIGER UPLOAD
			anfrage.onreadystatechange = function() {
	    	if (anfrage.readyState==4 && anfrage.status==200) {
					var grund = '';
					if (anfrage.responseText == "FEHLER") {
						grund += 'Sicherheitsablehnung, ';
	        }
	        if (anfrage.responseText.match(/DOPPELT/)) {
						grund += 'Datei existiert bereits, ';
	        }
	        if (anfrage.responseText.match(/ZEICHEN/)) {
		        grund += 'Dateiname unzulässig, ';
	        }
	        if (anfrage.responseText.match(/GROESSE/)) {
		      	grund += 'Datei zu groß, ';
	        }
	        if (anfrage.responseText.match(/ENDUNG/)) {
		        grund += 'Dateityp unzulässig, ';
	        }
	        if (anfrage.responseText == "BERECHTIGUNG") {
						grund += 'Dateityp unzulässig, ';
	        }
	        if (anfrage.responseText == "UNSKALIERT") {
						grund += 'Datei wurde in Originalgröße hochgeladen, ';
	        }

					if (grund.length > 0) {
						grund = grund.substring(0, grund.length-2);
						cms_dateisystem_aktonen_hochladen_dateifehler('<b>'+cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer].name)+'</b> ('+grund+')');
						cms_dateisystem_aktionen_hochladen_naechstedatei_vorbereiten(false, feldid);
						if (UP_aktuellnummer < anzahl) {cms_dateisystem_aktionen_hochladen_naechstedatei(netz, bereich, id, feldid, urheberrecht, skalieren, skalierengroesse);}
						else {
							// Nach dem Upload wieder aufräumen und den Ursprung des Dateisystems wiederherstellen
							cms_dateisystem_aktionen_hochladen_fertig(netz, bereich, id, pfad, feldid);
						}
					}
					else if (anfrage.responseText == "ERFOLG") {
		        cms_dateisystem_aktionen_hochladen_naechstedatei_vorbereiten(true, feldid);
						if (UP_aktuellnummer < anzahl) {cms_dateisystem_aktionen_hochladen_naechstedatei(netz, bereich, id, feldid, urheberrecht, skalieren, skalierengroesse);}
						else {
							// Nach dem Upload wieder aufräumen und den Ursprung des Dateisystems wiederherstellen
							cms_dateisystem_aktionen_hochladen_fertig(netz, bereich, id, pfad, feldid);
						}
	        }
	        else {
		      	cms_debug(anfrage);
	        }
	      }
	    };
			anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
			anfrage.send(formulardaten);
		}
	}
}

function cms_dateisystem_aktionen_hochladen_naechstedatei_vorbereiten(erfolg, feldid) {
	if (erfolg) {
		// aktuelle Datei wurde hochgeladen
		UP_gesamt += UP_aktuellgesamt;
	}
	else {
		// eine Datei weniger wird hochgeladen
		UP_gesamtanzahl -= 1;
		// Dateigröße der fehlenden Datei herausrechnen
		UP_gesamtgesamt -= UP_aktuellgesamt;
	}
	// Nächste Datei lesen
	UP_aktuellnummer += 1;
	// Falls noch Dateien zur Verfügung stehen
	if (UP_aktuellnummer < CMS_UPLOADDATEIEN[feldid].length) {
		UP_aktuell = 0;
		UP_aktuellname = cms_dateiname_erzeugen(CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer].name);
		UP_aktuellgesamt = CMS_UPLOADDATEIEN[feldid][UP_aktuellnummer].size;
		UP_aktuellgesamtanzeige = cms_groesse_umrechnen(UP_aktuellgesamt);
	}
}

// Fehlerhafte Datei in Uploadliste eintragen
function cms_dateisystem_aktonen_hochladen_dateifehler(code) {
	UP_fehlgeschlagen += code+'<br>';
	document.getElementById('cms_hochladen_fehlgeschlagen_liste').innerHTML = UP_fehlgeschlagen.substring(0,UP_fehlgeschlagen.length-4);
	document.getElementById('cms_hochladen_fehlgeschlagen').style.display = 'block';
}

function cms_dateisystem_laden_an(feldid) {
	var feld = document.getElementById(feldid+'_inhalt');
	var code = '<div class="cms_dateisystem_laden">';
		code += '<p id="cms_dateiladen_meldung_vorher">Bitte warten...</p>';
		code += cms_ladeicon();
		code += '<p id="cms_dateiladen_meldung_nachher">Dateien werden geladen</p>';
	code += '</div>';
	feld.innerHTML = code;
}

function cms_dateisystem_meldung_an (art, titel, text) {
		var code = '<div class="cms_meldung cms_meldung_'+art+'">';
			code += '<h4>'+titel+'</h4>';
			code += text;
		code += '</div>';
		return code;
}

function cms_dateisystem_meldung_berechtigung(feldid) {
	var feld = document.getElementById(feldid+'_inhalt');
	var code = '<div class="cms_dateisystem_meldung">';
	var meldung;
		if (CMS_BENUTZERART == "s") {
	        meldung = '<p>Du bist nicht berechtigt, diese Aktion auszuführen!</p>';
	    }
	    else {
	        meldung = '<p>Sie sind nicht berechtigt, diese Aktion auszuführen!</p>';
	    }
		code += cms_dateisystem_meldung_an('fehler', 'Zugriff verweigert!', meldung);
	code += '</div>';
	feld.innerHTML = code;
}

function cms_dateisystem_meldung_fehler(feldid) {
	var feld = document.getElementById(feldid+'_inhalt');
	var code = '<div class="cms_dateisystem_meldung">';
		code += cms_dateisystem_meldung_an('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!</p>');
	code += '</div>';
	feld.innerHTML = code;
}

function cms_dateisystem_meldung_firewall(feldid) {
	var feld = document.getElementById(feldid+'_inhalt');
	var code = '<div class="cms_dateisystem_meldung">';
		code += cms_dateisystem_meldung_an('firewall', 'Firewall', '<p>Aus Diesem Netz kein kein Zugriff auf diese Daten erfolgen.</p>');
	code += '</div>';
	feld.innerHTML = code;
}



function cms_ordnerwechsel(netz, bereich, id, pfad, feldid) {
	cms_dateisystem_laden_an(feldid);
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("feldid", 				feldid);

	// Pfad ändern
	var pfadfeld = document.getElementById(feldid+'_pfad_feld');
	var pfadbox = document.getElementById(feldid+'_pfad');
	pfadfeld.value = pfad;
	var stammverzeichnis = document.getElementById(feldid+'_stammverzeichnis_feld').value;
	var pfadcode = "<span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_ordnerwechsel('"+netz+"', '"+bereich+"', '"+id+"', '"+stammverzeichnis+"', '"+feldid+"')\"><img src=\"res/dateiicons/klein/stammverzeichnis.png\"> Stammverzeichnis</span>";
	var zwischenpfad = stammverzeichnis;
	var restverzeichnis = pfad.substr(stammverzeichnis.length+1);
	restverzeichnis = restverzeichnis.split("/");

	for (i=0; i<restverzeichnis.length; i++) {
		if ((restverzeichnis[i]).length > 0) {
			zwischenpfad += "/"+restverzeichnis[i];
			pfadcode += " » <span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_ordnerwechsel('"+netz+"', '"+bereich+"', '"+id+"', '"+zwischenpfad+"', '"+feldid+"')\"><img src=\"res/dateiicons/klein/ordner.png\"> "+restverzeichnis[i]+"</span>";
		}
	}
	pfadbox.innerHTML = pfadcode;

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'181');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'182');
	}
	else {keinnetz = true;}

	if (keinnetz) {cms_dateisystem_meldung_fehler(feldid);}
	else {

		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_dateisystem_meldung_berechtigung(feldid);
				}
				if (anfrage.responseText == "FEHLER") {
					cms_dateisystem_meldung_fehler(feldid);
				}
				else {
					var feld = document.getElementById(feldid+'_inhalt');
					feld.innerHTML = anfrage.responseText;
				}
			}
		};
		anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}

function cms_dateisystem_ordnererstellen_anzeigen(feldid) {
	var ordnererstellen = document.getElementById(feldid+'_aktionen_neuerordner');
	var dateihochladen = document.getElementById(feldid+'_aktionen_hochladen');
	if (ordnererstellen) {ordnererstellen.style.display = 'block';}
	if (dateihochladen) {dateihochladen.style.display = 'none';}
}

function cms_dateisystem_hochladen_anzeigen(feldid) {
	var ordnererstellen = document.getElementById(feldid+'_aktionen_neuerordner');
	var dateihochladen = document.getElementById(feldid+'_aktionen_hochladen');
	if (dateihochladen) {dateihochladen.style.display = 'block';}
	if (ordnererstellen) {ordnererstellen.style.display = 'none';}
}

function cms_ordnererstellen(netz, bereich, id, feldid) {
	cms_laden_an('Neuen Ordner anlegen', 'Die Eingaben werden überprüft.');
	var name = document.getElementById(feldid+'_aktionen_neuerordner_eingabe').value;
	var pfad = document.getElementById(feldid+'_pfad_feld').value;
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("name", 					name);

	var meldung = '<p>Der Ordner konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	if (name.match(/^([a-zA-Z0-9_-])+$/) === null) {
		fehler = true;
		meldung += '<li>die Ordnerbezeichnung enthält unerlaubte Zeichen. Folgende Zeichen sind erlaubt: lateinische Buchstaben von »a-z« und von »A-Z« (keine Umlaute, kein ß) sowie die Zeichen »-« und »_« (keine Leerzeichen).</li>';
	}
	if (name.length < 3) {
		fehler = true;
		meldung += '<li>die Ordnerbezeichnung muss mindestens drei Zeichen lang sein.</li>';
	}

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'183');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'184');
	}
	else {keinnetz = true;}

	if (keinnetz) {cms_meldung_firewall();}
	else {
		if (fehler) {
			cms_meldung_an('fehler', 'Neuen Ordner anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else {
			var anfrage = new XMLHttpRequest();
			anfrage.onreadystatechange = function() {
				if (anfrage.readyState==4 && anfrage.status==200) {
					if (anfrage.responseText == "BERECHTIGUNG") {
						cms_meldung_berechtigung();
					}
					else if (anfrage.responseText == "ERFOLG") {
						cms_ordnerwechsel(netz, bereich, id, pfad, feldid);
						document.getElementById(feldid+'_aktionen_neuerordner_eingabe').value = "";
						cms_ausblenden(feldid+'_aktionen_neuerordner');
						cms_meldung_an('erfolg', 'Neuen Ordner anlegen', '<p>Der Ordner wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
					}
					else if (anfrage.responseText == "FEHLER") {
						cms_meldung_fehler();
					}
					else if (anfrage.responseText == "EXISTIERT") {
						meldung += '<li>Es existiert bereits eine Datei oder ein Verzeichnis mit diesem Namen.</li>';
						cms_meldung_an('fehler', 'Neuen Ordner anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
					}
					else {
						cms_debug(anfrage);
					}
				}
			};
			anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
			anfrage.send(formulardaten);
		}
	}
}


function cms_ordnerloeschen_anzeigen(netz, bereich, id, pfad, ordner, feldid) {
	cms_meldung_an('warnung', 'Ordner löschen', "<p>Soll der Ordner <b>"+ordner+"</b> wirklich gelöscht werden?</p><p>Mit der Löschung des Ordners werden auch alle darin enthaltenen Dateien gelöscht.</p><p>Die gelöschten Daten können nicht wiederhergestellt werden!</p><p><span class=\"cms_button\" onclick=\"cms_meldung_aus();\">Abbrechen</span> <span class=\"cms_button_nein\" onclick=\"cms_ordnerloeschen('"+netz+"', '"+bereich+"', '"+id+"', '"+pfad+"', '"+ordner+"', '"+feldid+"')\">Löschung durchführen</span></p>", '');
}


function cms_ordnerloeschen(netz, bereich, id, pfad, ordner, feldid) {
	cms_laden_an('Ordner löschen', 'Die Löschung wird durchgeführt.');
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("ordner", 				ordner);

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'185');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'186');
	}
	else {keinnetz = true;}

	if (keinnetz) {cms_meldung_firewall();}
	else {
		var anfrage = new XMLHttpRequest();
			anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_meldung_berechtigung();
				}
				else if (anfrage.responseText == "ERFOLG") {
					cms_ordnerwechsel(netz, bereich, id, pfad, feldid);
					cms_meldung_an('erfolg', 'Ordner löschen', '<p>Der Ordner wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
				}
				else if (anfrage.responseText == "FEHLER") {
					cms_meldung_fehler();
				}
				else {
					cms_debug(anfrage);
				}
			}
		};
		anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}


function cms_dateiloeschen_anzeigen(netz, bereich, id, pfad, datei, feldid) {
	cms_meldung_an('warnung', 'Datei löschen', "<p>Soll die Datei <b>"+datei+"</b> wirklich gelöscht werden?</p><p>Die gelöschte Datei kann nicht wiederhergestellt werden!</p><p><span class=\"cms_button\" onclick=\"cms_meldung_aus();\">Abbrechen</span> <span class=\"cms_button_nein\" onclick=\"cms_dateiloeschen('"+netz+"', '"+bereich+"', '"+id+"', '"+pfad+"', '"+datei+"', '"+feldid+"')\">Löschung durchführen</span></p>", '');
}


function cms_dateiloeschen(netz, bereich, id, pfad, datei, feldid) {
	cms_laden_an('Datei löschen', 'Die Löschung wird durchgeführt.');
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("datei", 				datei);

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'187');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'188');
	}
	else {keinnetz = true;}

	if (keinnetz) {cms_meldung_firewall();}
	else {
		var anfrage = new XMLHttpRequest();
			anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_meldung_berechtigung();
				}
				else if (anfrage.responseText == "ERFOLG") {
					cms_ordnerwechsel(netz, bereich, id, pfad, feldid);
					cms_meldung_an('erfolg', 'Datei löschen', '<p>Die Datei wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
				}
				else if (anfrage.responseText == "FEHLER") {
					cms_meldung_fehler();
				}
				else {
					cms_debug(anfrage);
				}
			}
		};
		anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}

function cms_dateiumbenennen_anzeigen(netz, bereich, id, pfad, datei, feldid) {
	var dateifeld = document.getElementById(feldid+"_datei"+datei);
	var dateiname = dateifeld.innerHTML;
	var code = "<p><input class=\"cms_dateisystem_umbenennen\" type=\"text\" name=\""+feldid+"_umbenennen_d"+datei+"\" id=\""+feldid+"_umbenennen_d"+datei+"\" value=\""+dateiname+"\">";
	code += "<input type=\"hidden\" name=\""+feldid+"_umbenennen_da"+datei+"\" id=\""+feldid+"_umbenennen_da"+datei+"\" value=\""+dateiname+"\"></p>";
	code += "<p><span class=\"cms_button\" onclick=\"cms_dateiumbenennen('"+netz+"', '"+bereich+"', '"+id+"', '"+pfad+"', '"+datei+"', '"+feldid+"')\">Umbenennen</span> ";
	code += "<span class=\"cms_button_nein\" onclick=\"cms_dateiumbenennen_abbrechen('"+datei+"', '"+feldid+"')\">Abbrechen</span></p>";
	dateifeld.innerHTML = code;
}

function cms_dateiumbenennen_abbrechen(datei, feldid) {
	var dateifeld = document.getElementById(feldid+"_datei"+datei);
	var dateinamealt = document.getElementById(feldid+"_umbenennen_da"+datei);
	var dateiname = dateinamealt.value;
	dateifeld.innerHTML = dateiname;
}

function cms_dateiumbenennen(netz, bereich, id, pfad, datei, feldid) {
	cms_laden_an('Datei umbenennen', 'Die Eingaben werden geprüft.');
	var namealt = document.getElementById(feldid+'_umbenennen_da'+datei).value;
	var nameneu = document.getElementById(feldid+'_umbenennen_d'+datei).value;
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("namealt", 			namealt);
	formulardaten.append("nameneu", 			nameneu);

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'189');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'190');
	}
	else {keinnetz = true;}

	var fehler = false;
	var meldung = '<p>Die Datei konnte nicht umbenannt werden, denn ...</p><ul>';

	if (nameneu == namealt) {
		fehler = true;
		meldung += '<li>der neue Dateiname unterscheidet sich nicht vom alten Dateinamen.</li>'
	}

	if (nameneu.match(/^([a-zA-Z0-9_-])*.([a-zA-Z0-9])+$/) === null) {
		fehler = true;
		meldung += '<li>die Dateibezeichnung enthält unerlaubte Zeichen. Folgende Zeichen sind erlaubt: lateinische Buchstaben von »a-z« und von »A-Z« (keine Umlaute, kein ß) sowie die Zeichen »-« und »_« (keine Leerzeichen). Sie besteht aus einem Dateinamen, einem Punkt und einer Dateiendung.</li>';
	}

	if (keinnetz) {cms_meldung_firewall();}
	else {
		if (fehler) {
			cms_meldung_an('fehler', 'Datei umbenennen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else {
			var anfrage = new XMLHttpRequest();
				anfrage.onreadystatechange = function() {
				if (anfrage.readyState==4 && anfrage.status==200) {
					if (anfrage.responseText == "BERECHTIGUNG") {
						cms_meldung_berechtigung();
					}
					else if ((anfrage.responseText).match(/ENDUNG/)) {
						cms_meldung_an('fehler', 'Datei umbenennen', '<p>Der neue Dateityp ist nicht gestattet.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
					}
					else if (anfrage.responseText == "ERFOLG") {
						cms_ordnerwechsel(netz, bereich, id, pfad, feldid);
						cms_meldung_an('erfolg', 'Datei umbenennen', '<p>Die Datei wurde umbenannt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
					}
					else if (anfrage.responseText == "FEHLER") {
						cms_meldung_fehler();
					}
					else if (anfrage.responseText == "EXISTIERT") {
						meldung += '<li>Es existiert bereits eine Datei oder ein Verzeichnis mit diesem Namen.</li>';
						cms_meldung_an('fehler', 'Datei umbenennen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
					}
					else {
						cms_debug(anfrage);
					}
				}
			};
			anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
			anfrage.send(formulardaten);
		}
	}
}



function cms_ordnerumbenennen_anzeigen(netz, bereich, id, pfad, ordner, feldid) {
	var ordnerfeld = document.getElementById(feldid+"_ordner"+ordner);
	var ordnername = ordnerfeld.innerHTML;
	var code = "<p><input class=\"cms_dateisystem_umbenennen\" type=\"text\" name=\""+feldid+"_umbenennen_o"+ordner+"\" id=\""+feldid+"_umbenennen_o"+ordner+"\" value=\""+ordnername+"\">";
	code += "<input type=\"hidden\" name=\""+feldid+"_umbenennen_oa"+ordner+"\" id=\""+feldid+"_umbenennen_oa"+ordner+"\" value=\""+ordnername+"\"></p>";
	code += "<p><span class=\"cms_button\" onclick=\"cms_ordnerumbenennen('"+netz+"', '"+bereich+"', '"+id+"', '"+pfad+"', '"+ordner+"', '"+feldid+"')\">Umbenennen</span> ";
	code += "<span class=\"cms_button_nein\" onclick=\"cms_ordnerumbenennen_abbrechen('"+netz+"', '"+bereich+"', '"+id+"', '"+pfad+"', '"+ordner+"', '"+feldid+"')\">Abbrechen</span></p>";
	ordnerfeld.setAttribute('onclick', '');
	ordnerfeld.innerHTML = code;
}

function cms_ordnerumbenennen_abbrechen(netz, bereich, id, pfad, ordner, feldid) {
	var ordnerfeld = document.getElementById(feldid+"_ordner"+ordner);
	var ordnernamealt = document.getElementById(feldid+"_umbenennen_oa"+ordner);
	var ordnername = ordnernamealt.value;
	ordnerfeld.setAttribute('onclick', "cms_ordnerwechsel('"+netz+"', '"+bereich+"', '"+id+"', '"+pfad+"', '"+feldid+"')");
	ordnerfeld.innerHTML = ordnername;
}

function cms_ordnerumbenennen(netz, bereich, id, pfad, datei, feldid) {
	cms_laden_an('Ordner umbenennen', 'Die Eingaben werden geprüft.');
	var namealt = document.getElementById(feldid+'_umbenennen_oa'+datei).value;
	var nameneu = document.getElementById(feldid+'_umbenennen_o'+datei).value;
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("namealt", 			namealt);
	formulardaten.append("nameneu", 			nameneu);

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'191');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'192');
	}
	else {keinnetz = true;}

	var fehler = false;
	var meldung = '<p>Die Datei konnte nicht umbenannt werden, denn ...</p><ul>';

	if (nameneu == namealt) {
		fehler = true;
		meldung += '<li>der neue Dateiname unterscheidet sich nicht vom alten Dateinamen.</li>'
	}

	if (nameneu.match(/^([a-zA-Z0-9_-])+$/) === null) {
		fehler = true;
		meldung += '<li>die Ordnerbezeichnung enthält unerlaubte Zeichen. Folgende Zeichen sind erlaubt: lateinische Buchstaben von »a-z« und von »A-Z« (keine Umlaute, kein ß) sowie die Zeichen »-« und »_« (keine Leerzeichen).</li>';
	}

	if (nameneu.length < 3) {
		fehler = true;
		meldung += '<li>die Ordnerbezeichnung muss mindestens drei Zeichen lang sein.</li>';
	}

	if (keinnetz) {cms_meldung_firewall();}
	else {
		if (fehler) {
			cms_meldung_an('fehler', 'Ordner umbenennen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else {
			var anfrage = new XMLHttpRequest();
				anfrage.onreadystatechange = function() {
				if (anfrage.readyState==4 && anfrage.status==200) {
					if (anfrage.responseText == "BERECHTIGUNG") {
						cms_meldung_berechtigung();
					}
					else if (anfrage.responseText == "ERFOLG") {
						cms_ordnerwechsel(netz, bereich, id, pfad, feldid);
						cms_meldung_an('erfolg', 'Ordner umbenennen', '<p>Der Ordner wurde umbenannt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus()">OK</span></p>');
					}
					else if (anfrage.responseText == "FEHLER") {
						cms_meldung_fehler();
					}
					else if (anfrage.responseText == "EXISTIERT") {
						meldung += '<li>Es existiert bereits eine Datei oder ein Verzeichnis mit diesem Namen.</li>';
						cms_meldung_an('fehler', 'Ordner umbenennen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
					}
					else {
						cms_debug(anfrage);
					}
				}
			};
			anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
			anfrage.send(formulardaten);
		}
	}
}



function cms_herunterladen(netz, bereich, id, pfad) {
	cms_laden_an('Download vorbereiten', 'Die Berechtigung wird geprüft.');
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("pfad", pfad);

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", '193');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'194');
	}
	else {keinnetz = true;}

	var fehler = false;
	var meldung = '<p>Die Datei konnte nicht heruntergeladen werden, denn ...</p><ul>';

	if (keinnetz) {cms_meldung_firewall();}
	else {
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_meldung_berechtigung();
				}
				else if (anfrage.responseText == "FEHLER") {
					cms_meldung_fehler();
				}
				else {
					var rueckmeldung = anfrage.responseText;
					window.location.href = netzhost+'php/oeffentlich/seiten/download/download.php?x='+rueckmeldung,'Download';
					cms_meldung_aus();
				}
			}
		};
		anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}










function cms_groesse_umrechnen(bytes) {
    if (bytes/1024 > 1) {
        bytes = bytes/1024;
        if (bytes/1024 > 1) {
            bytes = bytes/1024;
            if (bytes/1024 > 1) {
                bytes = bytes/1024;
                if (bytes/1024 > 1) {
                    bytes = bytes/1024;
                    if (bytes/1024 > 1) {
                        bytes = bytes/1024;
                        if (bytes/1024 > 1) {
                            bytes = bytes/1024;
                            bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
                            return bytes+" EB";
                        }
                        bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
                        return bytes+" PB";
                    }
                    bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
                    return bytes+" TB";
                }
                bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
                return bytes+" GB";
            }
            bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
            return bytes+" MB";
        }
        bytes = (Math.round(bytes*100)/100).toString().replace('.', ',');
        return bytes+" KB";
    }
    return bytes+" B";
}


function cms_dateiwaehler_ordnerwechsel(netz, bereich, id, pfad, feldid, art) {
	cms_dateisystem_laden_an(feldid);
	var keinnetz = false;
	var netzhost = "";
	var formulardaten = new FormData();
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("feldid", 				feldid);
	formulardaten.append("art", 					art);

	// Pfad ändern
	var pfadfeld = document.getElementById(feldid+'_pfad_feld');
	var pfadbox = document.getElementById(feldid+'_pfad');
	pfadfeld.value = pfad;
	var stammverzeichnis = document.getElementById(feldid+'_stammverzeichnis_feld').value;
	var pfadcode = "<span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_dateiwaehler_ordnerwechsel('"+netz+"', '"+bereich+"', '"+id+"', '"+stammverzeichnis+"', '"+feldid+"', '"+art+"')\"><img src=\"res/dateiicons/klein/stammverzeichnis.png\"> Stammverzeichnis</span>";
	var zwischenpfad = stammverzeichnis;
	var restverzeichnis = pfad.substr(stammverzeichnis.length+1);
	restverzeichnis = restverzeichnis.split("/");

	for (i=0; i<restverzeichnis.length; i++) {
		if ((restverzeichnis[i]).length > 0) {
			zwischenpfad += "/"+restverzeichnis[i];
			pfadcode += " » <span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_dateiwaehler_ordnerwechsel('"+netz+"', '"+bereich+"', '"+id+"', '"+zwischenpfad+"', '"+feldid+"', '"+art+"')\"><img src=\"res/dateiicons/klein/ordner.png\"> "+restverzeichnis[i]+"</span>";
		}
	}
	pfadbox.innerHTML = pfadcode;

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'195');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'196');
	}
	else {keinnetz = true;}

	if (keinnetz) {cms_dateisystem_meldung_fehler(feldid);}
	else {

		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_dateisystem_meldung_berechtigung(feldid);
				}
				else if (anfrage.responseText == "FEHLER") {
					cms_dateisystem_meldung_fehler(feldid);
				}
				else {
					var feld = document.getElementById(feldid+'_inhalt');
					feld.innerHTML = anfrage.responseText;
				}
			}
		};
		anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}

function cms_dateiwahl(netz, bereich, id, pfad, feldid, art, gruppe, gruppenid) {
	cms_laden_an('Dateien auswählen', 'Das Verzeichnis wird geladen.');
	var gruppe = gruppe || '-';
	var gruppenid = gruppenid || '-';
	var formulardaten = new FormData();
	if (art == 'download');
	formulardaten.append("bereich", 			bereich);
	formulardaten.append("id", 						id);
	formulardaten.append("pfad", 					pfad);
	formulardaten.append("feldid", 				feldid);
	formulardaten.append("art", 					art);
	formulardaten.append("gruppe", 				gruppe);
	formulardaten.append("gruppenid", 		gruppenid);
	var netzhost = "";
	var keinnetz = false;

	if ((netz == 'l') && (CMS_IMLN)) {
		netzhost = CMS_LN_DA;
		formulardaten.append("anfragenziel", 	'197');
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	}
	else if (netz == 's') {
		formulardaten.append("anfragenziel", 	'198');
	}
	else {keinnetz = true;}

	if (keinnetz) {cms_meldung_fehler();}
	else {
		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_meldung_berechtigung();
				}
				else if (anfrage.responseText == "FEHLER") {
					cms_meldung_fehler();
				}
				else {
					var verzeichnis = document.getElementById(feldid+'_verzeichnis');
					verzeichnis.innerHTML = anfrage.responseText;
					cms_meldung_aus();
				}
			}
		};
		anfrage.open("POST",netzhost+"php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);
	}
}

function cms_datei_waehlen(id, pfad) {
	var zielid = document.getElementById(id+'_auswahl').value;
	var fehler = false;
	if (zielid == 'bilder') {id = 'cms_editor_bilderlink';}
	else if (zielid == 'video') {id = 'cms_editor_videolink';}
	else if (zielid == 'linkdownload') {id = 'cms_editor_linklink';}
	else if ((zielid != 'download') && (zielid != 'vorschaubild')) {fehler = true;}

	if (!fehler) {
		if (zielid != 'linkdownload') {var vorschau = document.getElementById(id+'_vorschau');}
		var feld = document.getElementById(id);
		var verzeichnis = document.getElementById(id+'_verzeichnis');
		if (zielid != 'linkdownload') {
			var code = "";
			if ((zielid == 'bilder') || (zielid == 'vorschaubild')) {code = '<img src="'+pfad+'">';}
			else if (zielid == 'video') {code = '<video src="'+pfad+'" controls></video>';}
			else if (zielid == 'download') {
				var dateiteile = pfad.split('/');
				var dateiname = dateiteile[dateiteile.length-1];
				var dateinameteile = dateiname.split('.');
				var dateiendung = dateinameteile[dateinameteile.length-1];
				code = '<span class="cms_datei_gewaehlt">';
				if (CMS_DATEIICONS.indexOf(dateiendung) != -1) {code += '<img src="res/dateiicons/klein/'+dateiendung+'.png">';}
				else {code += '<img src="res/dateiicons/klein/typ.png">';}
				code += ' '+dateiname+'</span>';
			}
			vorschau.innerHTML = code;
		}
		verzeichnis.innerHTML = "";
		if (zielid == 'linkdownload') {
			feld.value = "javascript:cms_download('"+pfad+"')";
		}
		else {
			feld.value = pfad;
		}

		// Button aktivieren nach klasse
		if (zielid == 'bilder') {
			document.getElementById('cms_editor_bilderlink_button').disabled = false;
		}
		if (zielid == 'video') {
			document.getElementById('cms_editor_videolink_button').disabled = false;
		}
	}
	else {
		cms_meldung_fehler();
	}
}

function cms_dateiwahl_zuruecksetzen(ort) {
	if (ort == 'bilder') {
		document.getElementById('cms_editor_bilderlink_vorschau').innerHTML = 'Kein Bild ausgewählt';
		document.getElementById('cms_editor_bilderlink_verzeichnis').innerHMTL = '';
		document.getElementById('cms_editor_bilderlink_button').disabled = true;
	}
	else if (ort == 'video') {
		document.getElementById('cms_editor_videolink_vorschau').innerHTML = 'Kein Bild ausgewählt';
		document.getElementById('cms_editor_videolink_verzeichnis').innerHMTL = '';
		document.getElementById('cms_editor_videolink_button').disabled = true;
	}
}


function cms_dateisystem_hochladen_bilderskalieren(id) {
	var status = document.getElementById(id+'_bilderskalieren').value;
	var feld = document.getElementById(id+'_slalieren_groesse_feld');
	if (status == 1) {
		feld.style.display = 'block';
	}
	else {
		feld.style.display = 'none';
	}
}

function cms_dateisystem_hochladen_blende_vorbereiten() {
	var blende = '<div class="cms_spalte_i">';
	blende += '<h2 id="cms_laden_ueberschrift">Dateien hochladen</h2>';
	blende += '<p id="cms_laden_meldung_vorher">Bitte warten...</p>';
	blende += '<h4>Gesamtfortschritt</h4>';
	blende += '<div class="cms_hochladen_fortschritt_o"><div class="cms_hochladen_fortschritt_i" id=\"cms_hochladen_balken_gesamt\" style=\"width:0%;\"></div></div>';
	blende += '<p class="cms_hochladen_fortschritt_anzeige">Anzahl Dateien: <span id=\"cms_hochladen_anzahl_gesamt\">'+UP_gesamtanzahl+'</span> • Gesamtgröße: <span id=\"cms_hochladen_groesse_gesamt\">'+UP_gesamtgesamtanzeige+'</span> • ';
	blende += '<span id=\"cms_hochladen_fehler_anzeige_gesamt\">Geladen: <span id=\"cms_hochladen_fortschritt_gesamt\">0 B</span> – <span id=\"cms_hochladen_prozent_gesamt\">0 %</span></span>';

	blende += '<div id="cms_hochladen_aktuelledatei">';
	blende += '<h4 id="cms_hochladen_name_aktuell">'+UP_aktuellname+'</h4>';
	blende += '<div class="cms_hochladen_fortschritt_o"><div class="cms_hochladen_fortschritt_i" id=\"cms_hochladen_balken_aktuell\" style=\"width:0%;\"></div></div>';
	blende += '<p class="cms_hochladen_fortschritt_anzeige">Datei: <span id=\"cms_hochladen_nummer_aktuell\">1</span> • Gesamtgröße: <span id=\"cms_hochladen_groesse_aktuell\">'+UP_aktuellgesamtanzeige+'</span> • ';
	blende += '<span id=\"cms_hochladen_fehler_anzeige_aktuell\">Geladen: <span id=\"cms_hochladen_fortschritt_aktuell\">0 B</span> – <span id=\"cms_hochladen_prozent_aktuell\">0 %</span></span>';
	blende += '</div>';

	blende += '<div id="cms_hochladen_fehlgeschlagen">';
	blende += '<h4>Fehlgeschlagene Uploads</h4>';
	blende += '<p id="cms_hochladen_fehlgeschlagen_liste"></p>';
	blende += '</div>';

	blende += '<p id="cms_laden_meldung_nachher"></p>';
	blende += '</div>';

	document.getElementById('cms_blende_i').innerHTML = blende;
}


function cms_dateisystem_anhang_loeschen(datei) {
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'217');
	formulardaten.append("datei", datei);

	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (anfrage.responseText == "FEHLER") {
				cms_meldung_fehler();
			}
			else if (anfrage.responseText == "ERFOLG") {
				cms_dateisystem_anhang_aktualisieren();
				cms_meldung_aus();
			}
			else {
				cms_debug(anfrage);
			}
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}


function cms_dateisystem_anhang_aktualisieren() {
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'218');

	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			var box = document.getElementById('cms_nutzerkonto_postfach_nachricht_schreiben_anhang_box');
			var zeile = document.getElementById('cms_nutzerkonto_postfach_nachricht_schreiben_anhangF');
			if (anfrage.responseText == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (anfrage.responseText == "FEHLER") {
				cms_meldung_fehler();
			}
			else if (anfrage.responseText.match(/^<span /)) {
				box.innerHTML = anfrage.responseText;
				zeile.style.display = 'table-row';
			}
			else if (anfrage.responseText == '') {
				box.innerHTML = '<span class="cms_notiz">Diese Nachricht hat keinen Anhang.</span>';
				zeile.style.display = 'none';
			}
			else {
				cms_debug(anfrage);
			}
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}



function cms_dateiname_erzeugen(wert) {
	while (wert.indexOf(String.fromCharCode(776)) != -1) {
		wert = wert.replace(String.fromCharCode(776), 'e');
	}

	wert = wert.replace(/ä/g, "ae");
	wert = wert.replace(/ö/g, "oe");
	wert = wert.replace(/ü/g, "ue");
	wert = wert.replace(/Ä/g, "Ae");
	wert = wert.replace(/Ö/g, "Oe");
	wert = wert.replace(/Ü/g, "Ue");
	wert = wert.replace(/ß/g, "ss");

	wert = wert.replace(/\u00e4/g, "ae");
	wert = wert.replace(/\u00f6/g, "oe");
	wert = wert.replace(/\u00fc/g, "ue");
	wert = wert.replace(/\u00c4/g, "Ae");
	wert = wert.replace(/\u00d6/g, "Oe");
	wert = wert.replace(/\u00dc/g, "Ue");
	wert = wert.replace(/\u00df/g, "ss");
	wert = wert.replace(/ /g, "_");
	return wert;
}
