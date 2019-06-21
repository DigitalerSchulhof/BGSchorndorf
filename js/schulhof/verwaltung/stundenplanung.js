function cms_zeitraum_neue_schulstunde() {
	var box = document.getElementById('cms_zeitraum_schulstunden');
	var anzahl = document.getElementById('cms_zeitraum_schulstunden_anzahl');
	var nr = document.getElementById('cms_zeitraum_schulstunden_nr');
	var ids = document.getElementById('cms_zeitraum_schulstunden_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";
	code +="<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_zeitraum_bezeichnung_"+neueid+"\" id=\"cms_zeitraum_bezeichnung_"+neueid+"\" value=\"\"/></td></tr>";
	code +="<tr><th>Beginn:</th><td><input class=\"cms_input_h\" type=\"text\" name=\"cms_zeitraum_beginn_"+neueid+"_h\" id=\"cms_zeitraum_beginn_"+neueid+"_h\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_beginn_"+neueid+"')\" value=\"07\"> : <input class=\"cms_input_m\" type=\"text\" name=\"cms_zeitraum_beginn_"+neueid+"_m\" id=\"cms_zeitraum_beginn_"+neueid+"_m\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_beginn_"+neueid+"')\" value=\"30\"></td></tr>";
	code +="<tr><th>Ende:</th><td><input class=\"cms_input_h\" type=\"text\" name=\"cms_zeitraum_ende_"+neueid+"_h\" id=\"cms_zeitraum_ende_"+neueid+"_h\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_ende_"+neueid+"')\" value=\"08\"> : <input class=\"cms_input_m\" type=\"text\" name=\"cms_zeitraum_ende_"+neueid+"_m\" id=\"cms_zeitraum_ende_"+neueid+"_m\" onchange=\"cms_uhrzeitcheck('cms_zeitraum_ende_"+neueid+"')\" value=\"15\"></td></tr>";
	code +="<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_zeitraum_schulstunden_entfernen('"+neueid+"');\">Schulstunde löschen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_zeitraum_schulstunden_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_zeitraum_schulstunden_entfernen(id) {
	var box = document.getElementById('cms_zeitraum_schulstunden');
	var anzahl = document.getElementById('cms_zeitraum_schulstunden_anzahl');
	var ids = document.getElementById('cms_zeitraum_schulstunden_ids');
	var download = document.getElementById('cms_zeitraum_schulstunden_'+id);

	box.removeChild(download);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}

function cms_zeitraum_jahr_laden(jahr, spalten) {
  var feld = document.getElementById('cms_zeitraum_schulstunden_jahr');
	var jahrids = (document.getElementById('cms_zeitraum_ids').value).split('|');
  feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\"><img src="res/laden/standard.gif"><br>Die Zeiträume werden geladen.</td></tr>';

  for (var i=1; i<jahrids.length; i++) {
    var toggle = document.getElementById('cms_zeitraum_'+jahrids[i]);
    toggle.className = 'cms_toggle';
  }

  var fehler = false;

  if ((!Number.isInteger(parseInt(jahr))) || (!Number.isInteger(parseInt(spalten)))) {
    fehler = true;
  }

	if (fehler) {
    feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\">– ungültige Anfrage –</td></tr>';
  }
  else {
    var formulardaten = new FormData();
  	formulardaten.append("jahr",         jahr);
  	formulardaten.append("spalten",      spalten);
  	formulardaten.append("anfragenziel", 	'155');

  	// Anzahl Elemente suchen
    var anfrage = new XMLHttpRequest();
    anfrage.onreadystatechange = function() {
      if (anfrage.readyState==4 && anfrage.status==200) {
        if ((anfrage.responseText).slice(0,4) == '<tr>') {
          var toggle = document.getElementById('cms_zeitraum_'+jahr);
          toggle.className = 'cms_toggle_aktiv';
          feld.innerHTML = anfrage.responseText;
        }
        else {
          cms_debug(anfrage);
        }
      }
    };
  	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
    anfrage.send(formulardaten);
  }
}


function cms_stundenplanung_zeitraeume_neu_speichern() {
	cms_laden_an('Neuen Zeitraum anlegen', 'Die Eingaben werden überprüft.');
	var fehler = false;
	var meldung = '<p>Der Zeitraum konnte nicht erstellt werden, denn ...</p><ul>';
	var schuljahr = document.getElementById('cms_zeitraum_schuljahr').value;
	var beginnT = document.getElementById('cms_zeitraum_beginn_T').value;
	var beginnM = document.getElementById('cms_zeitraum_beginn_M').value;
	var beginnJ = document.getElementById('cms_zeitraum_beginn_J').value;
	var endeT = document.getElementById('cms_zeitraum_ende_T').value;
	var endeM = document.getElementById('cms_zeitraum_ende_M').value;
	var endeJ = document.getElementById('cms_zeitraum_ende_J').value;
	var aktiv = document.getElementById('cms_zeitraum_aktiv').value;
	var tagmo = document.getElementById('cms_zeitraum_mo').value;
	var tagdi = document.getElementById('cms_zeitraum_di').value;
	var tagmi = document.getElementById('cms_zeitraum_mi').value;
	var tagdo = document.getElementById('cms_zeitraum_do').value;
	var tagfr = document.getElementById('cms_zeitraum_fr').value;
	var tagsa = document.getElementById('cms_zeitraum_sa').value;
	var tagso = document.getElementById('cms_zeitraum_so').value;
	var schulstundenanzahl = document.getElementById('cms_zeitraum_schulstunden_anzahl').value;
	var schulstundenids = document.getElementById('cms_zeitraum_schulstunden_ids').value;

	// Pflichteingaben prüfen
	if ((aktiv != 0) && (aktiv != 1)) {
		meldung += '<li>die Eingabe für die Aktivität ist ungültig.</li>';
		fehler = true;
	}

	if (((tagmo != 0) && (tagmo != 1)) || ((tagdi != 0) && (tagdi != 1)) ||
	    ((tagmi != 0) && (tagmi != 1)) || ((tagdo != 0) && (tagdo != 1)) ||
			((tagfr != 0) && (tagfr != 1)) || ((tagsa != 0) && (tagsa != 1)) ||
			((tagso != 0) && (tagso != 1))) {
		meldung += '<li>die Eingabe für mindestens einen Schultag ist ungültig.</li>';
		fehler = true;
	}

	var beginn = new Date(beginnJ, beginnM, beginnT, 0, 0, 0, 0);
	var ende = new Date(endeJ, endeM, endeT, 23, 59, 59, 999);

	if (beginn > ende) {
		meldung += '<li>der Zeitraum kann nicht später beginnen als er endet.</li>';
		fehler = true;
	}

	var formulardaten = new FormData();
	// Prüfen, ob Downloads dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var sbezeichnungf = false;
	var szeitf = false;
	var sfehler = false;
	if (schulstundenanzahl > 0) {
		ids = schulstundenids.split('|');
		for (i=1; i<ids.length; i++) {
			var sid = ids[i];

			var sbezeichnung = document.getElementById('cms_zeitraum_bezeichnung_'+sid);
			var sbeginnstd = document.getElementById('cms_zeitraum_beginn_'+sid+'_h');
			var sbeginnmin = document.getElementById('cms_zeitraum_beginn_'+sid+'_m');
			var sbeginn = 0;
			var sendestd = document.getElementById('cms_zeitraum_ende_'+sid+'_h');
			var sendemin = document.getElementById('cms_zeitraum_ende_'+sid+'_m');
			var sende = 0;

			if (sbezeichnung) {if ((sbezeichnung.value).length > 0) {formulardaten.append("sbezeichnung_"+sid,  sbezeichnung.value);} else {sbezeichnungf = true;}} else {sfehler = true;}
			if (sbeginnstd) {formulardaten.append("sbeginnstd_"+sid,  sbeginnstd.value);} else {sfehler = true;}
			if (sbeginnmin) {formulardaten.append("sbeginnmin_"+sid,  sbeginnmin.value);} else {sfehler = true;}
			if (!sfehler) {sbeginn = new Date(200,1,1,sbeginnstd.value,sbeginnmin.value,0,0);}
			if (sendestd) {formulardaten.append("sendestd_"+sid,  sendestd.value);} else {sfehler = true;}
			if (sendemin) {formulardaten.append("sendemin_"+sid,  sendemin.value);} else {sfehler = true;}
			if (!sfehler) {sende = new Date(200,1,1,sendestd.value,sendemin.value,0,0);}
			if (sbeginn > sende) {szeitf = true;}
		}
	}

	if (sfehler) {
		meldung += '<li>bei der Erstellung der Schulstunden ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (sbezeichnungf) {
		meldung += '<li>bei mindestens einer Schulstunde wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}
	if (szeitf) {
		meldung += '<li>mindestens eine Schulstunde beginnt früher als eine andere.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("schuljahr",  schuljahr);
		formulardaten.append("beginnT",  beginnT);
		formulardaten.append("beginnM",  beginnM);
		formulardaten.append("beginnJ",  beginnJ);
		formulardaten.append("endeT",  endeT);
		formulardaten.append("endeM",  endeM);
		formulardaten.append("endeJ",  endeJ);
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("tagmo", tagmo);
		formulardaten.append("tagdi", tagdi);
		formulardaten.append("tagmi", tagmi);
		formulardaten.append("tagdo", tagdo);
		formulardaten.append("tagfr", tagfr);
		formulardaten.append("tagsa", tagsa);
		formulardaten.append("tagso", tagso);
		formulardaten.append("schulstundenanzahl", schulstundenanzahl);
		formulardaten.append("schulstundenids", schulstundenids);
		formulardaten.append("anfragenziel", '156');
	}


	if (fehler) {
		cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var anfrage = new XMLHttpRequest();
	  anfrage.onreadystatechange = function() {
    if (anfrage.readyState==4 && anfrage.status==200) {
      if (anfrage.responseText == "FEHLER") {
      		cms_meldung_fehler();
        }
        else if (anfrage.responseText == "BERECHTIGUNG") {
          cms_meldung_berechtigung();
        }
        else if (anfrage.responseText == 'ERFOLG') {
					cms_meldung_an('erfolg', 'Neuen Zeitraum anlegen', '<p>Der Zeitraum wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Stundenplanung\');">OK</span></p>');
				}
        else {
					fehler = false;
					if (anfrage.responseText.match(/SCHULJAHR/)) {
						meldung += '<li>dieser Zeitraume liegt nicht im angegebenen Schuljahr.</li>';
						fehler = true;
					}
					if (anfrage.responseText.match(/DOPPELT/)) {
						meldung += '<li>dieser Zeiraum überschneidet sich mit einem anderen.</li>';
						fehler = true;
					}
					if (anfrage.responseText.match(/STUNDEN/)) {
						meldung += '<li>die Schulstunden dieses Zeitraumes überschneiden sich.</li>';
						fehler = true;
					}
					if (fehler) {cms_meldung_an('fehler', 'Neuen Zeitraum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');}
					else {cms_debug(anfrage);}
        }
      }
  	};
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
    anfrage.send(formulardaten);
	}
}

function cms_stundenplanung_zeitraeume_loeschen_vorbereiten(id) {
	cms_meldung_an('warnung', 'Zeitraum löschen', '<p>Soll der Zeitraum inklusive aller zugrhörigen Schulstunden und Stundenpläne wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_stundenplanung_zeitraeume_loeschen(\''+id+'\')">Löschung durchführen</span></p>');
}

function cms_stundenplanung_zeitraeume_loeschen(id) {
	cms_laden_an('Zeitraum löschen', 'Der Zeitraum wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'157');
	// Versuch, Person zu löschen
  var anfrage = new XMLHttpRequest();
  anfrage.onreadystatechange = function() {
	  if (anfrage.readyState==4 && anfrage.status==200) {
      if (anfrage.responseText == "FEHLER") {
        cms_meldung_fehler();
      }
      else if  (anfrage.responseText == "BERECHTIGUNG") {
        cms_meldung_berechtigung();
      }
      else if (anfrage.responseText == "ERFOLG") {
				cms_meldung_an('erfolg', 'Zeitraum löschen', '<p>Der Zeitraum wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Stundenplanung\');">OK</span></p>');
      }
      else {
        cms_debug(anfrage);
      }
	  }
  };
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
  anfrage.send(formulardaten);
}


function cms_stundenplanung_zeitraeume_bearbeiten_vorbereiten(id) {
	cms_laden_an('Zeitraum bearbeiten', 'Die Berechtigung wird geprüft.');
	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'158');

	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (anfrage.responseText == "BASTLER") {
				cms_meldung_bastler();
			}
			else if (anfrage.responseText == "FEHLER") {
				cms_meldung_fehler();
			}
			else if (anfrage.responseText == "ERFOLG") {
				cms_link('Schulhof/Verwaltung/Stundenplanung/Zeitraum_bearbeiten');
			}
			else {
				cms_debug(anfrage);
			}
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}


function cms_stundenplanung_zeitraeume_bearbeiten_speichern() {
	cms_laden_an('Zeitraum bearbeiten', 'Die Eingaben werden überprüft.<br><br>Da Zeitraumänderungen Auswirkungen auf viele Unterrichtsstunden haben können, kann eine Zeitraumänderung etwas Zeit in Anspruch nehmen.');
	var fehler = false;
	var meldung = '<p>Der Zeitraum konnte nicht bearbeitet werden, denn ...</p><ul>';
	var schuljahr = document.getElementById('cms_zeitraum_schuljahr').value;
	var beginnT = document.getElementById('cms_zeitraum_beginn_T').value;
	var beginnM = document.getElementById('cms_zeitraum_beginn_M').value;
	var beginnJ = document.getElementById('cms_zeitraum_beginn_J').value;
	var endeT = document.getElementById('cms_zeitraum_ende_T').value;
	var endeM = document.getElementById('cms_zeitraum_ende_M').value;
	var endeJ = document.getElementById('cms_zeitraum_ende_J').value;
	var aktiv = document.getElementById('cms_zeitraum_aktiv').value;
	var tagmo = document.getElementById('cms_zeitraum_mo').value;
	var tagdi = document.getElementById('cms_zeitraum_di').value;
	var tagmi = document.getElementById('cms_zeitraum_mi').value;
	var tagdo = document.getElementById('cms_zeitraum_do').value;
	var tagfr = document.getElementById('cms_zeitraum_fr').value;
	var tagsa = document.getElementById('cms_zeitraum_sa').value;
	var tagso = document.getElementById('cms_zeitraum_so').value;
	var schulstundenanzahl = document.getElementById('cms_zeitraum_schulstunden_anzahl').value;
	var schulstundenids = document.getElementById('cms_zeitraum_schulstunden_ids').value;

	// Pflichteingaben prüfen
	if ((aktiv != 0) && (aktiv != 1)) {
		meldung += '<li>die Eingabe für die Aktivität ist ungültig.</li>';
		fehler = true;
	}
	if (((tagmo != 0) && (tagmo != 1)) || ((tagdi != 0) && (tagdi != 1)) ||
	    ((tagmi != 0) && (tagmi != 1)) || ((tagdo != 0) && (tagdo != 1)) ||
			((tagfr != 0) && (tagfr != 1)) || ((tagsa != 0) && (tagsa != 1)) ||
			((tagso != 0) && (tagso != 1))) {
		meldung += '<li>die Eingabe für mindestens einen Schultag ist ungültig.</li>';
		fehler = true;
	}

	var beginn = new Date(beginnJ, beginnM, beginnT, 0, 0, 0, 0);
	var ende = new Date(endeJ, endeM, endeT, 23, 59, 59, 999);

	if (beginn > ende) {
		meldung += '<li>der Zeitraum kann nicht später beginnen als er endet.</li>';
		fehler = true;
	}

	var formulardaten = new FormData();
	// Prüfen, ob Downloads dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var sbezeichnungf = false;
	var szeitf = false;
	var sfehler = false;
	if (schulstundenanzahl > 0) {
		ids = schulstundenids.split('|');
		for (i=1; i<ids.length; i++) {
			var sid = ids[i];

			var sbezeichnung = document.getElementById('cms_zeitraum_bezeichnung_'+sid);
			var sbeginnstd = document.getElementById('cms_zeitraum_beginn_'+sid+'_h');
			var sbeginnmin = document.getElementById('cms_zeitraum_beginn_'+sid+'_m');
			var sbeginn = 0;
			var sendestd = document.getElementById('cms_zeitraum_ende_'+sid+'_h');
			var sendemin = document.getElementById('cms_zeitraum_ende_'+sid+'_m');
			var sende = 0;

			if (sbezeichnung) {if ((sbezeichnung.value).length > 0) {formulardaten.append("sbezeichnung_"+sid,  sbezeichnung.value);} else {sbezeichnungf = true;}} else {sfehler = true;}
			if (sbeginnstd) {formulardaten.append("sbeginnstd_"+sid,  sbeginnstd.value);} else {sfehler = true;}
			if (sbeginnmin) {formulardaten.append("sbeginnmin_"+sid,  sbeginnmin.value);} else {sfehler = true;}
			if (!sfehler) {sbeginn = new Date(200,1,1,sbeginnstd.value,sbeginnmin.value,0,0);}
			if (sendestd) {formulardaten.append("sendestd_"+sid,  sendestd.value);} else {sfehler = true;}
			if (sendemin) {formulardaten.append("sendemin_"+sid,  sendemin.value);} else {sfehler = true;}
			if (!sfehler) {sende = new Date(200,1,1,sendestd.value,sendemin.value,0,0);}
			if (sbeginn > sende) {szeitf = true;}
		}
	}

	if (sfehler) {
		meldung += '<li>bei der Änderung der Schulstunden ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (sbezeichnungf) {
		meldung += '<li>bei mindestens einer Schulstunde wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}
	if (szeitf) {
		meldung += '<li>mindestens eine Schulstunde beginnt früher als eine andere.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("schuljahr",  schuljahr);
		formulardaten.append("beginnT",  beginnT);
		formulardaten.append("beginnM",  beginnM);
		formulardaten.append("beginnJ",  beginnJ);
		formulardaten.append("endeT",  endeT);
		formulardaten.append("endeM",  endeM);
		formulardaten.append("endeJ",  endeJ);
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("tagmo", tagmo);
		formulardaten.append("tagdi", tagdi);
		formulardaten.append("tagmi", tagmi);
		formulardaten.append("tagdo", tagdo);
		formulardaten.append("tagfr", tagfr);
		formulardaten.append("tagsa", tagsa);
		formulardaten.append("tagso", tagso);
		formulardaten.append("schulstundenanzahl", schulstundenanzahl);
		formulardaten.append("schulstundenids", schulstundenids);
		formulardaten.append("anfragenziel", '159');
	}


	if (fehler) {
		cms_meldung_an('fehler', 'Zeitraum bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var anfrage = new XMLHttpRequest();
	  anfrage.onreadystatechange = function() {
    if (anfrage.readyState==4 && anfrage.status==200) {
      if (anfrage.responseText == "FEHLER") {
      		cms_meldung_fehler();
        }
        else if (anfrage.responseText == "BERECHTIGUNG") {
          cms_meldung_berechtigung();
        }
        else if (anfrage.responseText == 'ERFOLG') {
					cms_meldung_an('erfolg', 'Zeitraum bearbeiten', '<p>Der Zeitraum wurde grändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Stundenplanung\');">OK</span></p>');
				}
        else {
					fehler = false;
					if (anfrage.responseText.match(/SCHULJAHR/)) {
						meldung += '<li>dieser Zeitraume liegt nicht im angegebenen Schuljahr.</li>';
						fehler = true;
					}
					if (anfrage.responseText.match(/DOPPELT/)) {
						meldung += '<li>dieser Zeiraum überschneidet sich mit einem anderen.</li>';
						fehler = true;
					}
					if (anfrage.responseText.match(/STUNDEN/)) {
						meldung += '<li>die Schulstunden dieses Zeitraumes überschneiden sich.</li>';
						fehler = true;
					}
					if (fehler) {cms_meldung_an('fehler', 'Zeitraum bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');}
					else {cms_debug(anfrage);}
        }
      }
  	};
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
    anfrage.send(formulardaten);
	}
}


function cms_stundenplanung_stundenplaene_bearbeiten_vorbereiten(id) {
	cms_laden_an('Stundenplanung vorbereiten', 'Die Berechtigung wird geprüft.');
	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'160');

	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (anfrage.responseText == "BASTLER") {
				cms_meldung_bastler();
			}
			else if (anfrage.responseText == "FEHLER") {
				cms_meldung_fehler();
			}
			else if (anfrage.responseText == "ERFOLG") {
				cms_link('Schulhof/Verwaltung/Stundenplanung/Stundenpläne');
			}
			else {
				cms_debug(anfrage);
			}
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}

function cms_stundenplan_stunde_hervorheben(tag, stunde) {
	$('.cms_stundenplan_'+tag+'_'+stunde).addClass('cms_stunde_aktiv');
}

function cms_stundenplan_stunde_normalisieren(tag, stunde) {
	$('.cms_stundenplan_'+tag+'_'+stunde).removeClass('cms_stunde_aktiv');
}

function cms_stundenplan_kurse_laden() {
	cms_laden_an('Kurse laden', 'Die Kurse dieser Klasse werden geladen.');
	var klasse = document.getElementById('cms_stundenplanung_klasse').value;

	var formulardaten = new FormData();
	formulardaten.append("klasse", klasse);
	formulardaten.append("anfragenziel", 	'161');

	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (anfrage.responseText == "BASTLER") {
				cms_meldung_bastler();
			}
			else if (anfrage.responseText == "FEHLER") {
				cms_meldung_fehler();
			}
			else if (anfrage.responseText == "KEIN") {
				cms_meldung_an('info', 'Keine Kurse', '<p>Dieser Klasse wurden noch keine Kurse zugeordnet.</p>', '<p><span class="cms_button" onclick="cms_link(\'/Schulhof/Verwaltung/Stundenplanung/Stundenpläne\');">Zurück</span></p>');
			}
			else if (anfrage.responseText.match(/<option/)) {
				document.getElementById('cms_stundenplanung_kurs').innerHTML = anfrage.responseText;
				cms_stundenplan_neuerstundenplan('k');
				cms_meldung_aus();
			}
			else {
				alert(anfrage.responseText);
				cms_debug(anfrage);
			}
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}


function cms_stundenplan_neuestundenplaene() {
	document.getElementById('cms_stundenplanung_lehrerslot').innerHTML = '<p class="cms_stundenplan_laden"><img src="res/laden/standard.gif"><br>Der Stundenplan wird aktualisiert.</p>';
	document.getElementById('cms_stundenplanung_raumslot').innerHTML = '<p class="cms_stundenplan_laden"><img src="res/laden/standard.gif"><br>Der Stundenplan wird aktualisiert.</p>';
	document.getElementById('cms_stundenplanung_klassenslot').innerHTML = '<p class="cms_stundenplan_laden"><img src="res/laden/standard.gif"><br>Der Stundenplan wird aktualisiert.</p>';

	cms_stundenplan_neuerstundenplan('l', 'nein');
	cms_stundenplan_neuerstundenplan('r', 'nein');
	cms_stundenplan_neuerstundenplan('k', 'nein');
}

function cms_stundenplan_neuerstundenplan(art, meldung) {
	meldung = meldung || 'ja';
	if (meldung == 'ja') {
		cms_laden_an('Stundenpläne aktualisieren', 'Die Stundenpläne werden aktualisert.');
	}
	cms_stundenplan_stunde_auswahlentfernen();

	if ((art != 'l') && (art != 'r') && (art != 'k')) {
		cms_meldung_fehler();
	}
	else {
		var lehrer = document.getElementById('cms_stundenplanung_lehrkraft').value;
		var raum = document.getElementById('cms_stundenplanung_raum').value;
		var klasse = document.getElementById('cms_stundenplanung_klasse').value;
		var kurs = document.getElementById('cms_stundenplanung_kurs').value;

		var formulardaten = new FormData();
		formulardaten.append("art", art);
		formulardaten.append("lehrer", lehrer);
		formulardaten.append("raum", raum);
		formulardaten.append("klasse", klasse);
		formulardaten.append("kurs", kurs);
		formulardaten.append("anfragenziel", 	'162');

		var anfrage = new XMLHttpRequest();
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (anfrage.responseText == "BERECHTIGUNG") {
					cms_meldung_berechtigung();
				}
				else if (anfrage.responseText == "BASTLER") {
					cms_meldung_bastler();
				}
				else if (anfrage.responseText.match(/<table/)) {
					if (art == 'l') {document.getElementById('cms_stundenplanung_lehrerslot').innerHTML = anfrage.responseText;}
					else if (art == 'r') {document.getElementById('cms_stundenplanung_raumslot').innerHTML = anfrage.responseText;}
					else if (art == 'k') {document.getElementById('cms_stundenplanung_klassenslot').innerHTML = anfrage.responseText;}
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
}

function cms_stundenplan_stunde_auswahlentfernen() {
	$('.cms_stundenplan_stunde').removeClass('cms_stunde_gewaehlt');
	document.getElementById('cms_stundenplanung_gewaehlte_stunde').innerHTML = '<p class=\"cms_notiz\">Keine Schulstunde ausgewählt.</p>';
}

function cms_stundenplan_stunde_auswaehlen(tag, stunde) {
	$('.cms_stundenplan_stunde').removeClass('cms_stunde_gewaehlt');
	$('.cms_stundenplan_stunde .cms_stundenplan_stunden_teilbox').css('overflow', 'hidden');
	$('.cms_stundenplan_'+tag+'_'+stunde).toggleClass('cms_stunde_gewaehlt');
	$('.cms_stundenplan_'+tag+'_'+stunde+'  .cms_stundenplan_stunden_teilbox').css('overflow', 'visible');

	cms_laden_an('Stunde laden', 'Unterrichtsstunden zu dieser Zeit werden geladen.');
	var lehrer = document.getElementById('cms_stundenplanung_lehrkraft').value;
	var raum = document.getElementById('cms_stundenplanung_raum').value;
	var klasse = document.getElementById('cms_stundenplanung_klasse').value;
	var kurs = document.getElementById('cms_stundenplanung_kurs').value;

	var formulardaten = new FormData();
	formulardaten.append("lehrer", lehrer);
	formulardaten.append("raum", raum);
	formulardaten.append("klasse", klasse);
	formulardaten.append("kurs", kurs);
	formulardaten.append("tag", tag);
	formulardaten.append("stunde", stunde);
	formulardaten.append("anfragenziel", 	'163');

	var anfrage = new XMLHttpRequest();
	anfrage.onreadystatechange = function() {
		if (anfrage.readyState==4 && anfrage.status==200) {
			if (anfrage.responseText == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (anfrage.responseText == "BASTLER") {
				cms_meldung_bastler();
			}
			else if (anfrage.responseText == "ZUORDNUNG") {
				cms_meldung_an('info', 'Stunde laden', '<p>Die Stunde konnte nicht geladen werden, da die in den Details angegebenen Daten ungültig sind. Dieser Meldung ging gegebenenfalls voraus, dass zur gesuchten Klasse keine Kurse vefügbar sind.</p>', '<p><span class="cms_button" onclick="cms_link(\'/Schulhof/Verwaltung/Stundenplanung/Stundenpläne\')">Zurück</span></p>');
			}
			else if (anfrage.responseText == "FEHLER") {
				cms_meldung_fehler();
			}
			else if (anfrage.responseText.match(/<table/)) {
				document.getElementById('cms_stundenplanung_gewaehlte_stunde').innerHTML = anfrage.responseText;
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

function cms_stundenplanung_stunde_neu_speichern() {
	cms_laden_an('Neue Stunde anlegen', 'Die Eingaben werden überprüft.');
	var lehrer = document.getElementById('cms_stundenplanung_lehrkraft').value;
	var raum = document.getElementById('cms_stundenplanung_raum').value;
	var kurs = document.getElementById('cms_stundenplanung_kurs').value;
	var tag = document.getElementById('cms_stundenplanung_tag').value;
	var stunde = document.getElementById('cms_stundenplanung_stunde').value;

	var formulardaten = new FormData();
	formulardaten.append("lehrer", lehrer);
	formulardaten.append("raum", raum);
	formulardaten.append("kurs", kurs);
	formulardaten.append("tag", tag);
	formulardaten.append("stunde", stunde);
	formulardaten.append("anfragenziel", '164');

	var anfrage = new XMLHttpRequest();
  anfrage.onreadystatechange = function() {
	  if (anfrage.readyState==4 && anfrage.status==200) {
	    if (anfrage.responseText == "FEHLER") {
	    	cms_meldung_fehler();
	    }
	    else if (anfrage.responseText == "BERECHTIGUNG") {
	      cms_meldung_berechtigung();
	    }
	    else if (anfrage.responseText == 'ERFOLG') {
				cms_meldung_an('erfolg', 'Neue Stunde anlegen', '<p>Die Stunde wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_stundenplan_neuestundenplaene()">OK</span></p>');
			}
	    else {
				cms_debug(anfrage);
	    }
		}
	};
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}

function cms_stundenplanung_stunde_loeschen_vorbereiten(lehrer, raum, kurs, tag, stunde) {
	cms_meldung_an('warnung', 'Stunde löschen', '<p>Soll diese Stunde wirklich gelöscht werden?<br>(Es werden nur künftige Stunden entfernt.)</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_stundenplanung_stunde_loeschen(\''+lehrer+'\', \''+raum+'\', \''+kurs+'\', \''+tag+'\', \''+stunde+'\')">Löschung durchführen</span></p>');
}

function cms_stundenplanung_stunde_loeschen(lehrer, raum, kurs, tag, stunde) {
	cms_laden_an('Stunde löschen', 'Die Stunde wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("lehrer", lehrer);
	formulardaten.append("raum", raum);
	formulardaten.append("kurs", kurs);
	formulardaten.append("tag", tag);
	formulardaten.append("stunde", stunde);
	formulardaten.append("anfragenziel", 	'165');
	// Versuch, Person zu löschen
  var anfrage = new XMLHttpRequest();
  anfrage.onreadystatechange = function() {
	  if (anfrage.readyState==4 && anfrage.status==200) {
      if (anfrage.responseText == "FEHLER") {
        cms_meldung_fehler();
      }
      else if  (anfrage.responseText == "BERECHTIGUNG") {
        cms_meldung_berechtigung();
      }
      else if (anfrage.responseText == "ERFOLG") {
				cms_meldung_an('erfolg', 'Stunde löschen', '<p>Die Stunde wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_stundenplan_neuestundenplaene()">OK</span></p>');
      }
      else {
        cms_debug(anfrage);
      }
	  }
  };
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
  anfrage.send(formulardaten);
}
