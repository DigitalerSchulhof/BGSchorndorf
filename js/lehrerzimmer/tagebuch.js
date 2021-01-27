function cms_notfallzustand_anzeigen(wert, app) {
  var app = app || 'nein';
  if (wert == '1') {
    cms_meldung_notfall('warnung', 'Notfallzustand ausrufen', '<p><b>Achtung!</b> Sie sind dabei den Notfallzustand auszurufen. Alle Schüler, Lehrer, Verwaltungsangestellte und Externe im Schulhof werden angewiesen <b>das Schulgebäude umgehend zu verlassen</b>. Alle Lehrer werden zudem angewiesen, die Vollzähligkeit bzw. Abwesenheit einzelner ihre Schüler der Einsatzleitung zu melden!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_wichtig" onclick="cms_notfallzustand(\''+wert+'\', \''+app+'\')">Notfallzustand ausrufen</span></p>');
  }
  if (wert == '0') {
    cms_meldung_notfall('warnung', 'Notfallzustand aufheben', '<p>Möchten Sie den Notfallzustand wirklich aufheben? Alle Notfallmeldungen werden damit gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_wichtig" onclick="cms_notfallzustand(\''+wert+'\', \''+app+'\')">Notfallzustand aufheben</span></p>');
  }
}

function cms_notfallzustand(wert, app) {
  var app = app ||' nein';
  var meldung = '<p>Die Notfallzustand kann nichg geändert werden, denn ...</p><ul>';
  var fehler = false;

  if (!cms_check_toggle(wert)) {
    meldung += '<li>die Eingabe für den aktuellen Notfallzustand ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
     cms_meldung_an ('fehler', 'Notfallzustand ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var formulardaten = new FormData();
  	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
    formulardaten.append("notfall", wert);
    formulardaten.append("anfragenziel", '33');

  	function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == 'ERFOLG') {
        if (app != 'app') {cms_link('Schulhof/Nutzerkonto');}
        else {cms_link('App');}
      }
      else {
        cms_fehlerbehandlung(rueckgabe);
      }
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}

function cms_stundenerzeugen_vorbereiten(sjid) {
  cms_laden_an('Stunden und Tagebücher erzeugen', 'Die Zeiträume werden geladen ...');

  var formulardaten = new FormData();
  formulardaten.append('sjid', sjid);
  formulardaten.append("anfragenziel", 	'293');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Stunden_und_Tagebücher_erzeugen');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_stundenerzeugen_speichern() {
  cms_laden_an('Stunden und Tagebücher erzeugen', 'Die Erzeugung wird vorbereitet ...');
  var kurse = document.getElementById('cms_kurse').value;
  var zeitraeume = document.getElementById('cms_zeitraeume').value;
  var schuljahr = document.getElementById('cms_schuljahr').value;
  var tag = document.getElementById('cms_zeitraum_erzeugen_ab_T').value;
  var monat = document.getElementById('cms_zeitraum_erzeugen_ab_M').value;
  var jahr = document.getElementById('cms_zeitraum_erzeugen_ab_J').value;

  var meldung = '<p>Die Stunden und Tagebücher konnten nicht erzeugt werden, denn ...</p><ul>';
  var fehler = false;

  var jetzt = new Date();

  if (!cms_check_ganzzahl(tag,1,31) || !cms_check_ganzzahl(monat,1,12) || !cms_check_ganzzahl(jahr,0)) {
    meldung += '<li>das eingegebene Datum ist ungültig.</li>'
    fehler = true;
  }

  if (!fehler) {
    var aenderungsdatum = new Date(jahr, monat-1, tag, 0,0,0,0);
    if (aenderungsdatum <= jetzt) {
      meldung += '<li>das Änderungsdatum muss in der Zukunft liegen!</li>'
      fehler = true;
    }
  }

  if (!cms_check_ganzzahl(schuljahr,0)) {
    meldung += '<li>die das Schuljahres ist ungültig.</li>'
    fehler = true;
  }

  if (!cms_check_idfeld(zeitraeume)) {
    meldung += '<li>die die Zeiträume sind ungültig.</li>'
    fehler = true;
  }

  if ((!cms_check_idfeld(kurse)) && (kurse.length != 0)) {
    meldung += '<li>die Kurse des Schuljahres sind ungültig.</li>'
    fehler = true;
  }

  zeitraeume = zeitraeume.substr(1);
  var zt = zeitraeume.split('|');
  var ks = (kurse.substr(1)).split('|');
  // Falls keine Stufen angelegt
  if ((ks.length == 1) && (ks[0].length == 0)) {ks = new Array();}

  // Prüfen, welche Zeiträume erstellt werden sollen
  var ztanlegen = new Array();
  var ztindex = 0;
  var zeitraumfehler = false;
  for (var z = 0; z < zt.length; z++) {
    if (zt[z].length > 0) {
      var feld = document.getElementById('cms_zeitraum_erzeugen_'+zt[z]);
      if (feld) {
        if (!cms_check_toggle(feld.value)) {zeitraumfehler = true;}
        else {if (feld.value == 1) {ztanlegen[ztindex] = zt[z]; ztindex++;}}
      }
      else {zeitraumfehler = true;}
    }
  }

  if (zeitraumfehler) {
    meldung += '<li>die Auswahl der Zeiträume ist ungültig.</li>'
    fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Stunden und Tagebücher erzeugen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    var anzkurse = ks.length;
    var anzzeitraeume = ztanlegen.length;
    var kursenr = 0;
    var zeitraumnr = 0;

    if ((anzzeitraeume > 0) && (ks.length > 0)) {
      var feld = document.getElementById('cms_blende_i');
      var neuemeldung = '<div class="cms_spalte_i">';
      neuemeldung += '<h2 id="cms_laden_ueberschrift">Stunden und Tagebücher erzeugen</h2>';
      neuemeldung += '<p id="cms_laden_meldung_vorher">Bitte warten ...</p>';
      neuemeldung += '<h4>Gesamtfortschritt</h4>';
      neuemeldung += '<div class="cms_fortschritt_o">';
        neuemeldung += '<div class="cms_fortschritt_i" id="cms_hochladen_balken_gesamt" style="width: 0%;"></div>';
      neuemeldung += '</div>';
      neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Zeiträume: <span id="cms_stundnerezeugen_ztaktuell">0</span>/'+anzzeitraeume+' abgeschlossen</p>';
      neuemeldung += '<div class="cms_fortschritt_box">';
        neuemeldung += '<h4>Fortschritt in diesem Zeitraum</h4>';
        neuemeldung += '<div class="cms_fortschritt_o">';
          neuemeldung += '<div class="cms_fortschritt_i" id="cms_hochladen_balken_aktuell" style="width: 0%;"></div>';
        neuemeldung += '</div>'
        neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Kurse: <span id="cms_stundnerezeugen_ksaktuell">0</span>/'+anzkurse+' abgeschlossen</p>';
      neuemeldung += '</div></div>';
      feld.innerHTML = neuemeldung;

      var formulardaten = new FormData();
      cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
      formulardaten.append("kurs",       ks[kursenr]);
      formulardaten.append("zeitraum", 	  ztanlegen[zeitraumnr]);
      formulardaten.append("schuljahr", 	schuljahr);
      formulardaten.append("tag", 	      tag);
      formulardaten.append("monat", 	    monat);
      formulardaten.append("jahr", 	      jahr);
      formulardaten.append("erster", 	    'j');
      formulardaten.append("anfragenziel", 	'36');

      function anfragennachbehandlung(rueckgabe) {
        if (rueckgabe == "ERFOLG") {
          // Abgeschlossene ids erhöhen:
          kursenr++;
          if (kursenr == anzkurse) {kursenr = 0; zeitraumnr++;}
          // Anzeige aktualisieren
          document.getElementById('cms_stundnerezeugen_ztaktuell').innerHTML = zeitraumnr;
          document.getElementById('cms_stundnerezeugen_ksaktuell').innerHTML = kursenr;
          document.getElementById('cms_hochladen_balken_gesamt').style.width = (100*zeitraumnr)/anzzeitraeume+'%';
          document.getElementById('cms_hochladen_balken_aktuell').style.width = (100*kursenr)/anzkurse+'%';

          if (zeitraumnr == anzzeitraeume) {
            cms_meldung_an('erfolg', 'Stunden und Tagebücher erzeugen', '<p>Die Unterrichtsstunden und Tagebücher wurden erzeugt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung\');">OK</span></p>');
          }
          else {
            // Nächste Stufe/Zeitraum starten
            var formulardaten = new FormData();
            cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
            formulardaten.append("kurs", ks[kursenr]);
            formulardaten.append("zeitraum", 	ztanlegen[zeitraumnr]);
            formulardaten.append("schuljahr", 	schuljahr);
            formulardaten.append("tag", 	      tag);
            formulardaten.append("monat", 	    monat);
            formulardaten.append("jahr", 	      jahr);
            formulardaten.append("erster", 	    'n');
            formulardaten.append("anfragenziel", 	'36');

            cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
          }
        }
        else {cms_fehlerbehandlung(rueckgabe);}
      }

      cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
    }
    else {
      cms_meldung_an('erfolg', 'Stunden und Tagebücher erzeugen', '<p>Es war nichts zu erzeugen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung\');">OK</span></p>');
    }
  }
}


function cms_tagebuch_eintragen(unterricht) {
  cms_laden_an('Tagebucheintrag vornehmen', 'Der Tagebucheintrag wird geladen ...');

  var formulardaten = new FormData();
  formulardaten.append('unterricht', unterricht);
  formulardaten.append("anfragenziel", 	'430');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Nutzerkonto/Tagebuch/Eintragen');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_tagebuch_einsehen(id, art) {
  cms_laden_an('Tagebucheintrag einsehen', 'Das Tagebuch wird geladen ...');

  var formulardaten = new FormData();
  formulardaten.append('gruppenid', id);
  formulardaten.append('gruppenart', art);
  formulardaten.append("anfragenziel", 	'432');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Nutzerkonto/Tagebuch/Einsehen');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_eintrag_fzdazu(beginn, ende, ln) {
  var ln = ln || '-';
  var box = document.getElementById('cms_eintrag_fehlzeiten');
	var anzahl = document.getElementById('cms_eintrag_fzan');
	var nr = document.getElementById('cms_eintrag_fznr');
	var ids = document.getElementById('cms_eintrag_fzids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;
  var personen = document.getElementById('cms_eintrag_vorlage').innerHTML;

  var b = new Date(parseInt(beginn)*1000);
  var e = new Date((parseInt(ende)+1)*1000);

	var code = "";
	code += "<tr><th>Person:</th><td><select name=\"cms_eintrag_fz_person_"+neueid+"\" id=\"cms_eintrag_fz_person_"+neueid+"\">"+personen+"</select></td></tr>";
  var von = cms_uhrzeit_eingabe("cms_eintrag_fz_von_"+neueid, b.getHours(), b.getMinutes());
  var bis = cms_uhrzeit_eingabe("cms_eintrag_fz_bis_"+neueid, e.getHours(), e.getMinutes());
  if (ln == 'ln') {
    var ganztaegig = "<span class=\"cms_button\" onclick=\"cms_eintrag_ganztaegig('"+neueid+"')\">Ganztägig</span>";
  } else {
    var ganztaegig = "";
  }
  code += "<tr><th>Zeitraum:</th><td>"+von+" – "+bis+" "+ganztaegig+"</td></tr>";
  code += "<tr><th>Bemerkung:</th><td><input type=\"text\" name=\"cms_eintrag_fz_bemerkung_"+neueid+"\" id=\"cms_eintrag_fz_bemerkung_"+neueid+"\"></td></tr>";
	code += "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_eintrag_fzweg('"+neueid+"');\">– Fehlzeit entfernen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_eintrag_fz_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_eintrag_ganztaegig(id) {
  document.getElementById("cms_eintrag_fz_von_"+id+"_h").value = "00";
  document.getElementById("cms_eintrag_fz_von_"+id+"_m").value = "00";
  document.getElementById("cms_eintrag_fz_bis_"+id+"_h").value = "23";
  document.getElementById("cms_eintrag_fz_bis_"+id+"_m").value = "59";
}

function cms_eintrag_fzweg(id) {
  var box = document.getElementById('cms_eintrag_fehlzeiten');
	var anzahl = document.getElementById('cms_eintrag_fzan');
	var ids = document.getElementById('cms_eintrag_fzids');
	var fz = document.getElementById('cms_eintrag_fz_'+id);

	box.removeChild(fz);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}

function cms_tagebuch_klassewechseln(klasse, uid) {
  var alle = document.getElementById('cms_tagebuch_alleklassen').value;
  alle = alle.split("|");
  for (var i=0; i<alle.length; i++) {
    document.getElementById('cms_klassen_'+alle[i]).className = "cms_button";
  }
  document.getElementById('cms_klassen_'+klasse).className = "cms_button_ja";
  document.getElementById('cms_tagebuch_klasseg').value = klasse;
  cms_tagebuch_tagesansicht('j', uid);
}

function cms_eintrag_ltdazu() {
  var box = document.getElementById('cms_eintrag_lobundtadel');
	var anzahl = document.getElementById('cms_eintrag_ltan');
	var nr = document.getElementById('cms_eintrag_ltnr');
	var ids = document.getElementById('cms_eintrag_ltids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;
  var personen = document.getElementById('cms_eintrag_vorlage').innerHTML;

	var code = "";
	code += "<tr><th>Person:</th><td><select name=\"cms_eintrag_lt_person_"+neueid+"\" id=\"cms_eintrag_lt_person_"+neueid+"\">"+personen+"<option value=\"a\">ganzer Kurs</option></select></td></tr>";
	code += "<tr><th>Art:</th><td><select name=\"cms_eintrag_lt_art_"+neueid+"\" id=\"cms_eintrag_lt_art_"+neueid+"\"><option value=\"m\">Mitarbeits-Tadel</option><option value=\"v\">Verhaltens-Tadel</option><option value=\"l\">Lob</option></select></td></tr>";
  code += "<tr><th>Bemerkung:</th><td><textarea name=\"cms_eintrag_lt_bemerkung_"+neueid+"\" id=\"cms_eintrag_lt_bemerkung_"+neueid+"\"></textarea></td></tr>";
	code += "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_eintrag_ltweg('"+neueid+"');\">– Lob / Tadel entfernen</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_eintrag_lt_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_eintrag_ltweg(id) {
  var box = document.getElementById('cms_eintrag_lobundtadel');
	var anzahl = document.getElementById('cms_eintrag_ltan');
	var ids = document.getElementById('cms_eintrag_ltids');
	var lt = document.getElementById('cms_eintrag_lt_'+id);

	box.removeChild(lt);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}

function cms_eintrag_laden(uid) {
  var formulardaten = new FormData();
  cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("anfragenziel", '31');
  formulardaten.append("unterricht", uid);

  function anfragennachbehandlung(rueckgabe) {
    var box = document.getElementById("cms_eintrag_lehrernetz");
    box.innerHTML = rueckgabe;
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_tagebuch_eintrag_speichern(ln, eintrag) {
  var ln = ln || '-';
  var eintrag = eintrag || '-';
  cms_laden_an('Tagebucheintrag speichern', 'Die Eingaben werden überprüft.');
	var inhalt = document.getElementById('cms_eintrag_inhalt').value;
	var hausaufgaben = document.getElementById('cms_eintrag_hausi').value;
	var leistungsmessung = document.getElementById('cms_eintrag_leistungsmessung').value;
	var freigabe = document.getElementById('cms_eintrag_freigabe').value;
  var fzids = document.getElementById('cms_eintrag_fzids').value;
  var fids = fzids.split("|");
  var ltids = document.getElementById('cms_eintrag_ltids').value;
  var lids = ltids.split("|");

	var meldung = '<p>Der Tagebucheintrag konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (inhalt.length == 0) {
		meldung += '<li>es wurde kein Stundeninhalt eingegeben.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(leistungsmessung)) {
		meldung += '<li>die Leistungsmessung ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(freigabe)) {
		meldung += '<li>die Freigabe ist ungültig.</li>';
		fehler = true;
	}

  // Fehlzeiten analysieren
  var fehlzeitenfehler = false;

  for (var i=1; i<fids.length; i++) {
    var fzid = fids[i];
    var person = document.getElementById("cms_eintrag_fz_person_"+fzid).value;
    var zeitbh = parseInt(document.getElementById("cms_eintrag_fz_von_"+fzid+"_h").value);
    var zeitbm = parseInt(document.getElementById("cms_eintrag_fz_von_"+fzid+"_m").value);
    var zeiteh = parseInt(document.getElementById("cms_eintrag_fz_bis_"+fzid+"_h").value);
    var zeitem = parseInt(document.getElementById("cms_eintrag_fz_bis_"+fzid+"_m").value);
    var zeitb = 60*zeitbh + zeitbm;
    var zeite = 60*zeiteh + zeitem;
    if (zeite <= zeitb) {fehlzeitenfehler = true;}
    if (!cms_check_ganzzahl(person,0)) {fehlzeitenfehler = true;}
  }
  if (fehlzeitenfehler) {
    meldung += '<li>mindestens eine Fehlzeit ist ungültig. Die Enduhrzeit der Fehlzeit muss nach deren Beginn liegen und es muss eine Person ausgewählt sein.</li>';
    fehler = true;
  }

  var lobtadelfehler = false;
  for (var i=1; i<lids.length; i++) {
    var ltid = lids[i];
    var person = document.getElementById("cms_eintrag_lt_person_"+ltid).value;
    var art = document.getElementById("cms_eintrag_lt_art_"+ltid).value;
    if (!cms_check_ganzzahl(person,0) && (person != "a")) {lobtadelfehler = true;}
  }
  if (lobtadelfehler) {
    meldung += '<li>mindestens ein Lob/tadel ist ungültig. Es muss eine Person ausgewählt sein.</li>';
    fehler = true;
  }

	if (fehler) {
		cms_meldung_an('fehler', 'Tagebucheintrag speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Tagebucheintrag speichern', 'Der Tagebucheintrag wird gespeichert');

		var formulardaten = new FormData();
    formulardaten.append("inhalt", inhalt);
		formulardaten.append("hausaufgaben", hausaufgaben);
    formulardaten.append("leistungsmessung", leistungsmessung);
    formulardaten.append("freigabe", freigabe);
    formulardaten.append("fzids", fzids);
    formulardaten.append("ltids", ltids);
    for (var i=1; i<fids.length; i++) {
      var fzid = fids[i];
      var person = document.getElementById("cms_eintrag_fz_person_"+fzid).value;
      var zeitbh = parseInt(document.getElementById("cms_eintrag_fz_von_"+fzid+"_h").value);
      var zeitbm = parseInt(document.getElementById("cms_eintrag_fz_von_"+fzid+"_m").value);
      var zeiteh = parseInt(document.getElementById("cms_eintrag_fz_bis_"+fzid+"_h").value);
      var zeitem = parseInt(document.getElementById("cms_eintrag_fz_bis_"+fzid+"_m").value);
      var bemerkung = document.getElementById("cms_eintrag_fz_bemerkung_"+fzid).value;
      formulardaten.append("fzperson_"+fzid, person);
      formulardaten.append("fzzeitbh_"+fzid, zeitbh);
      formulardaten.append("fzzeitbm_"+fzid, zeitbm);
      formulardaten.append("fzzeiteh_"+fzid, zeiteh);
      formulardaten.append("fzzeitem_"+fzid, zeitem);
      formulardaten.append("fzbemerkung_"+fzid, bemerkung);
    }
    for (var i=1; i<lids.length; i++) {
      var ltid = lids[i];
      var person = document.getElementById("cms_eintrag_lt_person_"+ltid).value;
      var art = document.getElementById("cms_eintrag_lt_art_"+ltid).value;
      var bemerkung = document.getElementById("cms_eintrag_lt_bemerkung_"+ltid).value;
      formulardaten.append("ltperson_"+ltid, person);
      formulardaten.append("ltart_"+ltid, art);
      formulardaten.append("ltbemerkung_"+ltid, bemerkung);
    }

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Tagebucheintrag speichern', '<p>Der Tagebucheintrag wurde gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Tagebuch\');">Zurück zur Übersicht</span></p>');
			} else if (rueckgabe == "FEHLERFEHLZEIT") {
        meldung += '<li>Fehlzeiten desselben Schülers überschneiden sich oder liegen oder liegen außerhalb der Unterrichtsstunde.</li>';
        cms_meldung_an('fehler', 'Tagebucheintrag speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      } else if (rueckgabe == "FEHLERZUORDNUNG") {
        meldung += '<li>Zugeordnete Schülerinnen und Schüler sind nicht in diesem Kurs.</li>';
        cms_meldung_an('fehler', 'Tagebucheintrag speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {cms_fehlerbehandlung(rueckgabe);}
		}

    if (ln != 'ln') {
      formulardaten.append("anfragenziel", 	'431');
      cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
    } else {
      cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
      formulardaten.append("eintrag", 	eintrag);
      formulardaten.append("anfragenziel", 	'35');
      cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
    }


	}
}


function cms_tagebuch_tagesansicht(veraenderung, eintrag) {
  if (((veraenderung == '+') || (veraenderung == '-') || (veraenderung == 'j')) && (cms_check_ganzzahl(eintrag,0))) {
    var tag = document.getElementById('cms_tagebuch_tagesansicht_tag_T');
    var monat = document.getElementById('cms_tagebuch_tagesansicht_tag_M');
    var jahr = document.getElementById('cms_tagebuch_tagesansicht_tag_J');
    var klasse = document.getElementById('cms_tagebuch_klasseg');
    var tagwert = parseInt(tag.value);


    if (klasse) {
      cms_gesichert_laden('cms_tagebuch_tagesansicht');

      if (veraenderung == '+') {
        tag.value = tagwert+1;
        cms_datumcheck('cms_tagebuch_tagesansicht_tag');
      } else if (veraenderung == '-') {
        tag.value = tagwert-1;
        cms_datumcheck('cms_tagebuch_tagesansicht_tag');
      }

      var formulardaten = new FormData();
      cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
      formulardaten.append("tag", tag.value);
      formulardaten.append("monat", monat.value);
      formulardaten.append("jahr", jahr.value);
      formulardaten.append("klasse", klasse.value);
      formulardaten.append("unterricht", eintrag);
      formulardaten.append("anfragenziel", 	'34');

      function anfragennachbehandlung(rueckgabe) {
        var box = document.getElementById('cms_tagebuch_tagesansicht');
        box.innerHTML = rueckgabe;
      }

      cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
    }
  }
}



function cms_tagebuch_tagesansicht(veraenderung, eintrag) {
  if (((veraenderung == '+') || (veraenderung == '-') || (veraenderung == 'j')) && (cms_check_ganzzahl(eintrag,0))) {
    var tag = document.getElementById('cms_tagebuch_tagesansicht_tag_T');
    var monat = document.getElementById('cms_tagebuch_tagesansicht_tag_M');
    var jahr = document.getElementById('cms_tagebuch_tagesansicht_tag_J');
    var klasse = document.getElementById('cms_tagebuch_klasseg');
    var tagwert = parseInt(tag.value);


    if (klasse) {
      cms_gesichert_laden('cms_tagebuch_tagesansicht');

      if (veraenderung == '+') {
        tag.value = tagwert+1;
        cms_datumcheck('cms_tagebuch_tagesansicht_tag');
      } else if (veraenderung == '-') {
        tag.value = tagwert-1;
        cms_datumcheck('cms_tagebuch_tagesansicht_tag');
      }

      var formulardaten = new FormData();
      cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
      formulardaten.append("tag", tag.value);
      formulardaten.append("monat", monat.value);
      formulardaten.append("jahr", jahr.value);
      formulardaten.append("klasse", klasse.value);
      formulardaten.append("unterricht", eintrag);
      formulardaten.append("anfragenziel", 	'34');

      function anfragennachbehandlung(rueckgabe) {
        var box = document.getElementById('cms_tagebuch_tagesansicht');
        box.innerHTML = rueckgabe;
      }

      cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
    }
  }
}

function cms_tagebuch_wochenansicht(veraenderung) {
  if (((veraenderung == '+') || (veraenderung == '-') || (veraenderung == 'j'))) {
    var gruppenid = document.getElementById('cms_tagebuchgruppenid');
    var gruppenart = document.getElementById('cms_tagebuchgruppenart');
    cms_gesichert_laden('cms_tagebuch_tagesansicht');

    var formulardaten = new FormData();
    cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);

    if (gruppenart.value == 'klasse') {
      var beginn = document.getElementById('cms_tagebuch_wochenansicht_datum');
      var datum = document.getElementById('cms_tagebuch_wochenansicht_datum_text');
      var ansicht = document.getElementById('cms_tagebuchansicht');
      var beginnwert = parseInt(beginn.value);

      if (veraenderung == '+') {
        var alt = new Date(beginnwert*1000);
        var neu = new Date(alt.getFullYear(), alt.getMonth(), alt.getDate()+7, 0, 0, 0, 0);
        var neuende = new Date(alt.getFullYear(), alt.getMonth(), alt.getDate()+13, 23, 59, 59, 0);
        beginn.value = neu.getTime()/1000;
        datum.innerHTML = "MO "+cms_fuehrendenull(neu.getDate())+"."+cms_fuehrendenull((neu.getMonth()+1))+"."+neu.getFullYear()+" – SO "+cms_fuehrendenull(neuende.getDate())+"."+cms_fuehrendenull((neuende.getMonth()+1))+"."+neuende.getFullYear();
      } else if (veraenderung == '-') {
        var alt = new Date(beginnwert*1000);
        var neu = new Date(alt.getFullYear(), alt.getMonth(), alt.getDate()-7, 0, 0, 0, 0);
        var neuende = new Date(alt.getFullYear(), alt.getMonth(), alt.getDate()-1, 23, 59, 59, 0);
        beginn.value = neu.getTime()/1000;
        datum.innerHTML = "MO "+cms_fuehrendenull(neu.getDate())+"."+cms_fuehrendenull((neu.getMonth()+1))+"."+neu.getFullYear()+" – SO "+cms_fuehrendenull(neuende.getDate())+"."+cms_fuehrendenull((neuende.getMonth()+1))+"."+neuende.getFullYear();
      }

      formulardaten.append("beginn", beginn.value);
      formulardaten.append("ansicht", ansicht.value);
    }

    formulardaten.append("gruppenid", gruppenid.value);
    formulardaten.append("gruppenart", gruppenart.value);
    formulardaten.append("anfragenziel", 	'32');

    function anfragennachbehandlung(rueckgabe) {
      var box = document.getElementById('cms_tagebuch_tagesansicht');
      box.innerHTML = rueckgabe;
    }

    cms_ajaxanfrage (formulardaten, anfragennachbehandlung, CMS_LN_DA);
  }
}


function cms_tagebuchansicht_aendern(art) {
  if (art == 'v' || art == 'w') {
    if (art == 'v') {
      document.getElementById('cms_tagebuchansicht_v').className = "cms_button_ja";
      document.getElementById('cms_tagebuchansicht_w').className = "cms_button";
    } else {
      document.getElementById('cms_tagebuchansicht_w').className = "cms_button_ja";
      document.getElementById('cms_tagebuchansicht_v').className = "cms_button";
    }
    document.getElementById('cms_tagebuchansicht').value = art;
    cms_tagebuch_wochenansicht('j');
  }
}
