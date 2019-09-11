function cms_schuljahrfabrik_vorbereiten(id, ziel, zielschuljahr) {
  var ziel = ziel || 'Grundlagen';
  var zielschuljahr = zielschuljahr || null;
  cms_laden_an('Schuljahrfabrik vorbereiten', 'Die Schuljahresfabrik des Schuljahres werden vorbereitet ...');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append('zielschuljahr', zielschuljahr);
  formulardaten.append("anfragenziel", 	'339');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Schuljahrfabrik/'+ziel);
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


// Konvertiert alle möglichen IPs in gewählte IPs unter Angabe der Feldid des
// Feldes in dem der die möglichen IPs stehen
function cms_auswahl_check(id) {
  var alle = document.getElementById(id);
  var gewaehlt = '';
  var fehler = false;

  if (alle) {
    if ((alle.value).length > 0) {
      einzeln = (alle.value).split('|');
      for (var i = 1; i < einzeln.length; i++) {
        var eins = document.getElementById(id+'_'+einzeln[i]);
        if (eins) {if (eins.value == '1') {gewaehlt += '|'+einzeln[i];}}
        else {fehler = true;}
      }
    }
  }
  else {fehler = true;}

  if (fehler) {return "FEHLER";}
  else {return gewaehlt;}
}


function cms_schuljahrfabrik_grundlagen() {
	cms_laden_an('Schuljahrfabrik – Grundlagen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_sjfabrik_sjbez').value;
	var beginnt = document.getElementById('cms_sjfabrik_jsbeginn_T').value;
	var beginnm = document.getElementById('cms_sjfabrik_jsbeginn_M').value;
	var beginnj = document.getElementById('cms_sjfabrik_jsbeginn_J').value;
	var endet = document.getElementById('cms_sjfabrik_jsende_T').value;
	var endem = document.getElementById('cms_sjfabrik_jsende_M').value;
	var endej = document.getElementById('cms_sjfabrik_jsende_J').value;
	var schulleitung = document.getElementById('cms_sjfabrik_sjschulleitung_personensuche_gewaehlt').value;
	var stellschulleitung = document.getElementById('cms_sjfabrik_sjstellvertretendeschulleitung_personensuche_gewaehlt').value;
	var abteilungsleitung = document.getElementById('cms_sjfabrik_sjabteilungsleitung_personensuche_gewaehlt').value;
	var sekretariat = document.getElementById('cms_sjfabrik_sjsekretariat_personensuche_gewaehlt').value;
	var sozialarbeit = document.getElementById('cms_sjfabrik_sjsozialarbeit_personensuche_gewaehlt').value;
	var oberstufenberatung = document.getElementById('cms_sjfabrik_sjoberstufenberatung_personensuche_gewaehlt').value;
	var beratungslehrer = document.getElementById('cms_sjfabrik_sjberatungslehrkraefte_personensuche_gewaehlt').value;
	var verbindungslehrer = document.getElementById('cms_sjfabrik_sjverbindungslehrkraefte_personensuche_gewaehlt').value;
	var schuelersprecher = document.getElementById('cms_sjfabrik_sjschuelersprecher_personensuche_gewaehlt').value;
	var elternbeirat = document.getElementById('cms_sjfabrik_sjelternbeiratsvorsitzende_personensuche_gewaehlt').value;
	var vertretungsplanung = document.getElementById('cms_sjfabrik_sjvertretungsplanung_personensuche_gewaehlt').value;
	var datenschutz = document.getElementById('cms_sjfabrik_sjdatenschutzbeauftragter_personensuche_gewaehlt').value;
	var hausmeister = document.getElementById('cms_sjfabrik_sjhausmeister_personensuche_gewaehlt').value;

	var meldung = '<p>Das Schuljahr konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>es wurde eine ungültige Bezeichnung eingegeben. Es dürfen nur lateinische Buchstaben, Umlaute, »ß«, »-«, sowie die Zahlen von 0 bis 9 verwendet werden.</li>';
		fehler = true;
	}
	if (bezeichnung.tolowercase == "schuljahrübergreifend") {
		meldung += '<li>es wurde eine ungültige Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	beginnd = new Date(beginnj, beginnm, beginnt, 0, 0, 0, 0);
	ended = new Date(endej, endem, endet, 23, 59, 59, 999);

	if (beginnd-ended >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum eingegeben.</li>';
		fehler = true;
	}

  var faechergewaehlt = cms_auswahl_check('cms_sjfabrik_faecher');
  if (faechergewaehlt == "FEHLER") {
		meldung += '<li>die Fächerauswahl ist ungültig.</li>';
		fehler = true;
	}

  var gremiengewaehlt = cms_auswahl_check('cms_sjfabrik_gremien');
  if (gremiengewaehlt == "FEHLER") {
		meldung += '<li>die Gremienauswahl ist ungültig.</li>';
		fehler = true;
	}

  var fachschaftengewaehlt = cms_auswahl_check('cms_sjfabrik_fachschaften');
  if (fachschaftengewaehlt == "FEHLER") {
		meldung += '<li>die Fachschaftenauswahl ist ungültig.</li>';
		fehler = true;
	}

  var stufengewaehlt = cms_auswahl_check('cms_sjfabrik_stufen');
  if (stufengewaehlt == "FEHLER") {
		meldung += '<li>die Stufenauswahl ist ungültig.</li>';
		fehler = true;
	}

  var klassengewaehlt = cms_auswahl_check('cms_sjfabrik_klassen');
  if (klassengewaehlt == "FEHLER") {
		meldung += '<li>die Klassenauswahl ist ungültig.</li>';
		fehler = true;
	}

  var arbeitsgemeinschaftengewaehlt = cms_auswahl_check('cms_sjfabrik_arbeitsgemeinschaften');
  if (arbeitsgemeinschaftengewaehlt == "FEHLER") {
		meldung += '<li>die Arbeitsgemeinschaftenauswahl ist ungültig.</li>';
		fehler = true;
	}

  var arbeitskreisegewaehlt = cms_auswahl_check('cms_sjfabrik_arbeitskreise');
  if (arbeitskreisegewaehlt == "FEHLER") {
		meldung += '<li>die Arbeitskreisauswahl ist ungültig.</li>';
		fehler = true;
	}

  var fahrtengewaehlt = cms_auswahl_check('cms_sjfabrik_fahrten');
  if (fahrtengewaehlt == "FEHLER") {
		meldung += '<li>die Fahrteinauswahl ist ungültig.</li>';
		fehler = true;
	}

  var wettbewerbegewaehlt = cms_auswahl_check('cms_sjfabrik_wettbewerbe');
  if (wettbewerbegewaehlt == "FEHLER") {
		meldung += '<li>die Wettbewerbsauswahl ist ungültig.</li>';
		fehler = true;
	}

  var ereignissegewaehlt = cms_auswahl_check('cms_sjfabrik_ereignisse');
  if (ereignissegewaehlt == "FEHLER") {
		meldung += '<li>die Ereignisauswahl ist ungültig.</li>';
		fehler = true;
	}

  var sonstigegruppengewaehlt = cms_auswahl_check('cms_sjfabrik_sonstigegruppen');
  if (sonstigegruppengewaehlt == "FEHLER") {
		meldung += '<li>die Auswahl der sonstigen Gruppen ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahrfabrik – Grundlagen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schuljahrfabrik – Grundlagen', 'Eingaben werden geprüft.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung",     	bezeichnung);
		formulardaten.append("beginnj", 				beginnj);
		formulardaten.append("beginnm", 				beginnm);
		formulardaten.append("beginnt", 				beginnt);
		formulardaten.append("endej", 				endej);
		formulardaten.append("endem", 				endem);
		formulardaten.append("endet", 				endet);
		formulardaten.append("schulleitung", 		schulleitung);
		formulardaten.append("stellschulleitung", 	stellschulleitung);
		formulardaten.append("abteilungsleitung", 	abteilungsleitung);
		formulardaten.append("sekretariat", 		sekretariat);
		formulardaten.append("sozialarbeit", 		sozialarbeit);
		formulardaten.append("oberstufenberatung", 	oberstufenberatung);
		formulardaten.append("beratungslehrer", 	beratungslehrer);
		formulardaten.append("verbindungslehrer", 	verbindungslehrer);
		formulardaten.append("schuelersprecher", 	schuelersprecher);
		formulardaten.append("elternbeirat", 		elternbeirat);
		formulardaten.append("vertretungsplanung",	vertretungsplanung);
		formulardaten.append("datenschutz",	datenschutz);
		formulardaten.append("hausmeister",	hausmeister);
		formulardaten.append("faechergewaehlt",	faechergewaehlt);
		formulardaten.append("gremiengewaehlt",	gremiengewaehlt);
		formulardaten.append("fachschaftengewaehlt",	fachschaftengewaehlt);
		formulardaten.append("stufengewaehlt",	stufengewaehlt);
		formulardaten.append("klassengewaehlt",	klassengewaehlt);
		formulardaten.append("arbeitsgemeinschaftengewaehlt",	arbeitsgemeinschaftengewaehlt);
		formulardaten.append("arbeitskreisegewaehlt",	arbeitskreisegewaehlt);
		formulardaten.append("fahrtengewaehlt",	fahrtengewaehlt);
		formulardaten.append("wettbewerbegewaehlt",	wettbewerbegewaehlt);
		formulardaten.append("ereignissegewaehlt",	ereignissegewaehlt);
		formulardaten.append("sonstigegruppengewaehlt",	sonstigegruppengewaehlt);
		formulardaten.append("anfragenziel", 	'342');

		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;
      if (rueckgabe.match(/DOPPELT/)) {
        nachfehler = true;
			  meldung += '<li>es gibt bereits ein Schuljahr in diesem Zeitraum.</li>';
      }
		  if (rueckgabe.match(/PERSONEN/)) {
        nachfehler = true;
			  meldung += '<li>es wurden Personen Schlüsselpositionen zugeordnet, die diese nicht inne haben dürfen.</li>';
      }

      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schuljahrfabrik – Grundlagen', '<p>Die Grundlagen zum neuen Schuljahr wurden erfasst.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile\');">Weiter zu »Profile«</span></p>');
			}
      else if (nachfehler) {
        cms_meldung_an('fehler', 'Schuljahrfabrik – Grundlagen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schuljahrfabrik_profile() {
	cms_laden_an('Schuljahrfabrik – Profile', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Profile konnten nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	var profilegewaehlt = cms_auswahl_check('cms_sjfabrik_profile');
  if (profilegewaehlt == "FEHLER") {
		meldung += '<li>die Profilwahl ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahrfabrik – Profile', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schuljahrfabrik – Profile', 'Eingaben werden geprüft.');

		var formulardaten = new FormData();
		formulardaten.append("profilegewaehlt",	profilegewaehlt);
		formulardaten.append("anfragenziel", 	'343');

		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;
      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schuljahrfabrik – Profile', '<p>Die Profile wurden in das neue Schuljahr übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\');">Weiter zu »Schüler in Gruppen«</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_schuljahrfabrik_schueleruebernehmen(feldid, bestandsid, gruppe, gruppenid) {
  var bestand = document.getElementById(bestandsid+'_personensuche_gewaehlt').value;
  var feld = document.getElementById(feldid);
  feld.innerHTML = cms_ladeicon()+"<p>Schüler werden aktualisiert...</p>";
  var meldung = '<p>Die Schüler konnten nicht übernommen werden, denn ...</p><ul>';
	var fehler = false;

  if (bestand != '' && !cms_check_idfeld(bestand)) {
    meldung += '<li>die bestehende Schülerliste ist ungültig.</li>';
		fehler = true;
  }

	if (fehler) {
    feld.innerHTML = cms_meldung_code ('fehler', 'Fehler beim Laden der Schüler', meldung+'</ul>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("gruppe",	gruppe);
		formulardaten.append("gruppenid",	gruppenid);
		formulardaten.append("bestand",	bestand);
		formulardaten.append("feldid",	bestandsid);
		formulardaten.append("anfragenziel", 	'344');

		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;
      if (rueckgabe.match(/^<p id=/)) {
				feld.innerHTML = rueckgabe;
		  }
      else {
        cms_fehlerbehandlungfeld(feld, rueckgabe);
      }
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schuljahrfabrik_schueleringruppen() {
  cms_laden_an('Schuljahrfabrik – Schüler in Gruppen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Personen konnten nicht zugeordnet werden, denn ...</p><ul>';
	var fehler = false;

  var gruppenids = new Array();
  gruppenids[0] = new Array();
  gruppenids[1] = new Array();
  gruppenids[2] = new Array();
  gruppenids[3] = new Array();
  gruppenids[4] = new Array();
  gruppenids[5] = new Array();
  gruppenids[6] = new Array();
  gruppenids[7] = new Array();
  gruppenids[8] = new Array();
  gruppenids[9] = new Array();
  gruppenids[0][0] = 'klassen';
  gruppenids[0][1] = document.getElementById('cms_sjfabrik_klassen').value;
  gruppenids[1][0] = 'stufen';
  gruppenids[1][1] = document.getElementById('cms_sjfabrik_stufen').value;
  gruppenids[2][0] = 'gremien';
  gruppenids[2][1] = document.getElementById('cms_sjfabrik_gremien').value;
  gruppenids[3][0] = 'fachschaften';
  gruppenids[3][1] = document.getElementById('cms_sjfabrik_fachschaften').value;
  gruppenids[4][0] = 'arbeitsgemeinschaften';
  gruppenids[4][1] = document.getElementById('cms_sjfabrik_arbeitsgemeinschaften').value;
  gruppenids[5][0] = 'arbeitskreise';
  gruppenids[5][1] = document.getElementById('cms_sjfabrik_arbeitskreise').value;
  gruppenids[6][0] = 'fahrten';
  gruppenids[6][1] = document.getElementById('cms_sjfabrik_fahrten').value;
  gruppenids[7][0] = 'wettbewerbe';
  gruppenids[7][1] = document.getElementById('cms_sjfabrik_wettbewerbe').value;
  gruppenids[8][0] = 'ereignisse';
  gruppenids[8][1] = document.getElementById('cms_sjfabrik_ereignisse').value;
  gruppenids[9][0] = 'sonstigegruppen';
  gruppenids[9][1] = document.getElementById('cms_sjfabrik_sonstigegruppen').value;
  var loeschen = document.getElementById('cms_sjfabrik_loeschenpersonen_personensuche_gewaehlt').value;

  // Fehler checken
  var gruppenidsfehler = false;
  for (var i=0; i<gruppenids.length; i++) {
    if (!cms_check_idfeld(gruppenids[i][1])) {gruppenidsfehler = true;}
  }
  if (gruppenidsfehler) {
    meldung += '<li>einzelne Gruppen wurden falsch ausgewählt.</li>';
    fehler = true;
  }

  if (!cms_check_idfeld(loeschen)) {
    meldung += '<li>die zu löschenden Personen sind ungültig.</li>';
    fehler = true;
  }

  var gname = "";
  var gids = "";
  var gruppenfehler = false;

  if (!fehler) {
    var formulardaten = new FormData();

    for (var i=0; i<gruppenids.length; i++) {
      gname = gruppenids[i][0];
      gids = gruppenids[i][1];
      formulardaten.append(gname,	gids);

      // Mitglieder der einzelnen Gruppen laden
      var gid = (gids.substr(1)).split('|');
      for (var j=0; j<gid.length; j++) {
        if (gid[j].length > 0) {
          var gruppenpersonen = document.getElementById('cms_sjfabrik_'+gname+'schueler_'+gid[j]+'_personensuche_gewaehlt');
          if (gruppenpersonen) {
            if (!cms_check_idfeld(gruppenpersonen.value)) {gruppenfehler = true;}
            else {
              formulardaten.append(gname+'_'+gid[j],	gruppenpersonen.value);
            }
          }
          else {gruppenfehler = true;}
        }
      }
    }
  }

  if (gruppenfehler) {
    meldung += '<li>mindestens einer Gruppe wurden Personen unzulässig zugeordnet.</li>';
    fehler = true;
  }

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahrfabrik – Schüler in Gruppen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schuljahrfabrik – Schüler in Gruppen', 'Eingaben werden geprüft.');

    formulardaten.append("loeschen",	loeschen);
		formulardaten.append("anfragenziel", 	'345');

		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;

      if (rueckgabe.match(/PERSONEN/)) {
        nachfehler = true;
        meldung += '<li>es wurden Personen ungültig zugeordnet.</li>';
      }
      else if (rueckgabe.match(/GRUPPEN/)) {
        nachfehler = true;
        meldung += '<li>es wurden Gruppen ungültig zugeordnet.</li>';
      }

      if (rueckgabe.match(/^ERFOLG/)) {
        // Personenlöschen
        personenloeschen = "";
        loeschenfehler = rueckgabe.substr(6);
        if (loeschenfehler.length > 0) {
          personenloeschen = "<p>Folgende Personen konnten nicht gelöscht werden:</p><ul>";
          personenloeschen += "<li>"+(loeschenfehler.substr(1)).replace(/\;/g, "</li><li>")+"</li>";
          personenloeschen += "</ul>";
        }
				cms_meldung_an('erfolg', 'Schuljahrfabrik – Schüler in Gruppen', '<p>Die Personen wurden zugeordnet.</p>'+personenloeschen, '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\');">Weiter zu »Klassenkurse«</span></p>');
			}
      else if (nachfehler) {
        cms_meldung_an('fehler', 'Schuljahrfabrik – Kurse für Klassen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
      else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schuljahrfabrik_klassenkurse() {
  cms_laden_an('Schuljahrfabrik – Kurse für Klassen anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Kurse konnten nicht angelegt werden, denn ...</p><ul>';
	var fehler = false;

  var stufen = document.getElementById('cms_sjfabrik_stufen').value;
  var faecher = document.getElementById('cms_sjfabrik_faecher').value;

  // Fehler checken
  if (!cms_check_idfeld(stufen) || !cms_check_idfeld(faecher)) {
    meldung += '<li>die Eingaben sind fehlerhaft.</li>';
    fehler = true;
  }

  var sid = (stufen.substr(1)).split('|');
  var sinfo = new Array();
  var sinfonr = 0;
  var fid = (faecher.substr(1)).split('|');

  var formulardaten = new FormData();
  var klassenfehler = false;

  for (var s=0; s<sid.length; s++) {

    sinfo[s] = new Array();
    sinfo[s][0] = sid[s];
    if (sid[s].length > 0) {
      var klassenids = document.getElementById('cms_sjfabrik_stufen_'+sid[s]+'_klassen');
      if (klassenids) {
        if (!cms_check_idfeld(klassenids.value)) {klassenfehler = true;}
        else {
          sinfo[s][1] = ((klassenids.value).substr(1)).split('|');
          formulardaten.append('stufenklassen_'+sid[s], klassenids.value);
        }
      }
      else {klassenfehler = true;}
    }
    sinfonr++;
  }

  if (klassenfehler) {
    meldung += '<li>mindestens eine Klasse wurde unzulässig zugeordnet.</li>';
    fehler = true;
  }

  if (!fehler) {
    formulardaten.append("stufen", stufen);
    formulardaten.append("faecher", faecher);
    formulardaten.append("anfragenziel", 	'274');

    // Senden vorbereiten - Fächer pro Klasse
    faecherproklassefehler = false;
    for (var s=0; s<sinfo.length; s++) {
      for (var k=0; k<sinfo[s][1].length; k++) {
        if (sinfo[s][1][k].length > 0) {
          for (var f=0; f<fid.length; f++) {
            if (fid[f].length > 0) {
              var feld = document.getElementById('cms_sjfabrik_kurse_klassen_'+sinfo[s][1][k]+'_'+fid[f]);
              if (feld) {
                if (!cms_check_toggle(feld.value)) {faecherproklassefehler = true;}
                else {
                  formulardaten.append('kursenachklassen_'+sinfo[s][1][k]+'_'+fid[f], feld.value);
                }
              }
              else {faecherproklassefehler = true;}
            }
          }
        }
      }
    }

    if (faecherproklassefehler) {
      meldung += '<li>bei der Erstellung von Kursen nach Klassen ist ein Fehler aufgetreten.</li>';
      fehler = true;
    }

  }

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahrfabrik – Kurse für Klassen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;
      if (rueckgabe.match(/KLASSEN/)) {
        nachfehler = true;
        meldung += '<li>es wurden Klassen ungültig zugeordnet.</li>';
      }
      else if (rueckgabe.match(/FAECHER/)) {
        nachfehler = true;
        meldung += '<li>es wurden Fächer ungültig zugeordnet.</li>';
      }

      if (rueckgabe.match(/^ERFOLG/)) {
        var formulardateninnen = new FormData();
        formulardateninnen.append("uebertragungsid", rueckgabe.substr(6));
        formulardateninnen.append("anfragenziel", 	'346');

        function anfragennachbehandlunginnen(rueckgabe) {
          var nachfehler = false;
          if (rueckgabe.match(/KLASSEN/)) {
            nachfehler = true;
            meldung += '<li>es wurden Klassen ungültig zugeordnet.</li>';
          }
          else if (rueckgabe.match(/FAECHER/)) {
            nachfehler = true;
            meldung += '<li>es wurden Fächer ungültig zugeordnet.</li>';
          }

          if (rueckgabe.match(/^ERFOLG/)) {
    				cms_meldung_an('erfolg', 'Schuljahrfabrik – Kurse für Klassen anlegen', '<p>Die Kurse wurden angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\');">Weiter zu »Stufenkurse«</span></p>');
    			}
          else if (nachfehler) {
            cms_meldung_an('fehler', 'Schuljahrfabrik – Kurse für Klassen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
          }
    			else {cms_fehlerbehandlung(rueckgabe);}
    		}
        cms_laden_an('Schuljahrfabrik – Kurse für Klassen anlegen', 'Mitglieder werden übertragen');
    		cms_ajaxanfrage (false, formulardateninnen, anfragennachbehandlunginnen);
			}
      else if (nachfehler) {
        cms_meldung_an('fehler', 'Schuljahrfabrik – Kurse für Klassen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {cms_fehlerbehandlung(rueckgabe);}
		}
    cms_laden_an('Schuljahrfabrik – Kurse für Klassen anlegen', 'Die Daten werden verarbeitet');
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schuljahrfabrik_stufenkurse() {
  cms_laden_an('Schuljahrfabrik – Kurse für Stufen anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Kurse konnten nicht angelegt werden, denn ...</p><ul>';
	var fehler = false;

  var stufen = document.getElementById('cms_sjfabrik_stufen').value;
  var altestufen = document.getElementById('cms_sjfabrik_altestufen').value;
  var faecher = document.getElementById('cms_sjfabrik_faecher').value;

  // Fehler checken
  if (!cms_check_idfeld(stufen) || !cms_check_idfeld(altestufen) || !cms_check_idfeld(faecher)) {
    meldung += '<li>die Eingaben sind fehlerhaft.</li>';
    fehler = true;
  }

  var sid = (stufen.substr(1)).split('|');
  if ((sid.length == 1) && (sid[0].length == 0)) {sid = new Array();}
  var sinfo = new Array();
  var sinfonr = 0;
  var asid = (altestufen.substr(1)).split('|');
  var fid = (faecher.substr(1)).split('|');

  var formulardaten = new FormData();

  if (!fehler) {
    var anzstufen = sid.length;
    var stufennr = 0;

    if (sid.length > 0) {
      var feld = document.getElementById('cms_blende_i');
      var neuemeldung = '<div class="cms_spalte_i">';
      neuemeldung += '<h2 id="cms_laden_ueberschrift">Schuljahrfabrik – Kurse für Stufen anlegen</h2>';
      neuemeldung += '<p id="cms_laden_meldung_vorher">Bitte warten ...</p>';
      neuemeldung += '<h4>Fortschritt</h4>';
      neuemeldung += '<div class="cms_hochladen_fortschritt_o">';
        neuemeldung += '<div class="cms_hochladen_fortschritt_i" id="cms_hochladen_balken_gesamt" style="width: 0%;"></div>';
      neuemeldung += '</div>';
      neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Stufen: <span id="cms_stundnerezeugen_ztaktuell">0</span>/'+anzstufen+' abgeschlossen</p>';
      neuemeldung += '</div>';
      feld.innerHTML = neuemeldung;

      var formulardaten = new FormData();
      formulardaten.append("stufe", sid[stufennr]);
      formulardaten.append("altestufen", altestufen);
      formulardaten.append("faecher", faecher);
      formulardaten.append("erster", 'j');
      formulardaten.append("anfragenziel", 	'347');

      // Senden vorbereiten: Kurse nach Stufen
      var faecherprostufe = false;
      var s = stufennr;
      if (sid[s].length > 0) {
        for (var f=0; f<fid.length; f++) {
          if (fid[f].length > 0) {
            var anzahl1 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_anzahl1');
            var anzahl2 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_anzahl2');
            var anzahl3 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_anzahl3');
            var zusatz1 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_zusatz1');
            var zusatz2 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_zusatz2');
            var zusatz3 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_zusatz3');
            if (anzahl1 && anzahl2 && anzahl3 && zusatz1 && zusatz2 && zusatz3) {
              if (cms_check_ganzzahl(anzahl1.value,0) && (anzahl1.value == '0' || cms_check_buchstaben(zusatz1.value)) &&
                  cms_check_ganzzahl(anzahl2.value,0) && (anzahl2.value == '0' || cms_check_buchstaben(zusatz2.value)) &&
                  cms_check_ganzzahl(anzahl3.value,0) && (anzahl3.value == '0' || cms_check_buchstaben(zusatz3.value))) {
                formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_anzahl1', anzahl1.value);
                formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_zusatz1', zusatz1.value);
                formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_anzahl2', anzahl2.value);
                formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_zusatz2', zusatz2.value);
                formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_anzahl3', anzahl3.value);
                formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_zusatz3', zusatz3.value);
              }
              else {faecherprostufe = true;}
            }
            else {faecherprostufe = true;}
          }
        }
      }

      if (faecherprostufe) {
        meldung += '<li>bei der Erstellung von Kursen nach Stufen ist ein Fehler aufgetreten.</li>';
        fehler = true;
      }

      // Senden vorbereiten: Stufen übertragen
      var stufenuebertragenfehler = false;
      var j = stufennr;
      for (var i=0; i<asid.length; i++) {
        if (asid[i].length > 0) {
          if (sid[j].length > 0) {
            var feld = document.getElementById('cms_sjfabrik_kurse_stufenuebertragen_'+asid[i]+'_'+sid[j]);
            if (feld) {
              if (!cms_check_toggle(feld.value)) {stufenuebertragenfehler = true;}
              else {
                formulardaten.append('kurseuebertragen_'+asid[i]+'_'+sid[j], feld.value);
              }
            }
            else {stufenuebertragenfehler = true;}
          }
        }
      }

      if (stufenuebertragenfehler) {
        meldung += '<li>bei der Übertragung der Stufen ist ein Fehler aufgetreten.</li>';
        fehler = true;
      }

      if (fehler) {
    		cms_meldung_an('fehler', 'Schuljahrfabrik – Kurse für Stufen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
    	}
    	else {
    		function anfragennachbehandlung(rueckgabe) {
          var nachfehler = false;

          if (rueckgabe.match(/FAECHER/)) {
            nachfehler = true;
            meldung += '<li>es wurden Fächer ungültig zugeordnet.</li>';
          }

          if (rueckgabe.match(/^ERFOLG/)) {
            // Abgeschlossene ids erhöhen:
            stufennr++;
            if (stufennr == anzstufen) {
              cms_meldung_an('erfolg', 'Schuljahrfabrik – Kurse für Stufen anlegen', '<p>Die Kurse der Stufen wurden erzeugt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\');">Weiter zu »Personen in Kursen«</span></p>');
            }
            else {
              // Nächste Stufe/Zeitraum starten
              var formulardaten = new FormData();
              formulardaten.append("stufe", sid[stufennr]);
              formulardaten.append("altestufen", altestufen);
              formulardaten.append("faecher", faecher);
              formulardaten.append("erster", 'n');
              formulardaten.append("anfragenziel", 	'347');

              // Senden vorbereiten: Kurse nach Stufen
              var faecherprostufe = false;
              var s = stufennr;
              if (sid[s].length > 0) {
                for (var f=0; f<fid.length; f++) {
                  if (fid[f].length > 0) {
                    var anzahl1 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_anzahl1');
                    var anzahl2 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_anzahl2');
                    var anzahl3 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_anzahl3');
                    var zusatz1 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_zusatz1');
                    var zusatz2 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_zusatz2');
                    var zusatz3 = document.getElementById('cms_sjfabrik_kurse_stufen_'+sid[s]+'_'+fid[f]+'_zusatz3');
                    if (anzahl1 && anzahl2 && anzahl3 && zusatz1 && zusatz2 && zusatz3) {
                      if (cms_check_ganzzahl(anzahl1.value,0) && (anzahl1.value == '0' || cms_check_buchstaben(zusatz1.value)) &&
                          cms_check_ganzzahl(anzahl2.value,0) && (anzahl2.value == '0' || cms_check_buchstaben(zusatz2.value)) &&
                          cms_check_ganzzahl(anzahl3.value,0) && (anzahl3.value == '0' || cms_check_buchstaben(zusatz3.value))) {
                        formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_anzahl1', anzahl1.value);
                        formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_zusatz1', zusatz1.value);
                        formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_anzahl2', anzahl2.value);
                        formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_zusatz2', zusatz2.value);
                        formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_anzahl3', anzahl3.value);
                        formulardaten.append('kursenachstufen_'+sid[s]+'_'+fid[f]+'_zusatz3', zusatz3.value);
                      }
                      else {faecherprostufe = true;}
                    }
                    else {faecherprostufe = true;}
                  }
                }
              }

              if (faecherprostufe) {
                meldung += '<li>bei der Erstellung von Kursen nach Stufen ist ein Fehler aufgetreten.</li>';
                fehler = true;
              }

              // Senden vorbereiten: Stufen übertragen
              var stufenuebertragenfehler = false;
              var j = stufennr;
              for (var i=0; i<asid.length; i++) {
                if (asid[i].length > 0) {
                  if (sid[j].length > 0) {
                    var feld = document.getElementById('cms_sjfabrik_kurse_stufenuebertragen_'+asid[i]+'_'+sid[j]);
                    if (feld) {
                      if (!cms_check_toggle(feld.value)) {stufenuebertragenfehler = true;}
                      else {
                        formulardaten.append('kurseuebertragen_'+asid[i]+'_'+sid[j], feld.value);
                      }
                    }
                    else {stufenuebertragenfehler = true;}
                  }
                }
              }

              if (stufenuebertragenfehler) {
                meldung += '<li>bei der Übertragung der Stufen ist ein Fehler aufgetreten.</li>';
                fehler = true;
              }

              cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
            }
    			}
          else if (nachfehler) {
            cms_meldung_an('fehler', 'Schuljahrfabrik – Kurse für Stufen anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
          }
    			else {cms_fehlerbehandlung(rueckgabe);}
    		}
    	}

      cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
    }
    else {
      cms_meldung_an('erfolg', 'Schuljahrfabrik – Kurse für Stufen anlegen', '<p>Es war nichts zu erzeugen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\');">Weiter zu »Personen in Kursen«</span></p>');
    }
  }
}


function cms_schuljahrfabrik_personeninkursen() {
  cms_laden_an('Schuljahrfabrik – Personen in Kursen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Personen konnten nicht eigeordnet werden, denn ...</p><ul>';
	var fehler = false;

  var kurse = document.getElementById('cms_sjfabrik_kurse').value;

  // Fehler checken
  if (!cms_check_idfeld(kurse)) {
    meldung += '<li>die Eingaben sind fehlerhaft.</li>';
    fehler = true;
  }

  var kid = (kurse.substr(1)).split('|');
  var formulardaten = new FormData();
  if (!fehler) {
    formulardaten.append("kurse", kurse);
    formulardaten.append("anfragenziel", 	'348');

    // Senden vorbereiten: Personen in Kursen
    var personenfehler = false;
    for (var k=0; k<kid.length; k++) {
      if (kid[k].length > 0) {
        var schueler = document.getElementById('cms_sjfabrik_schueler_kurs_'+kid[k]+'_personensuche_gewaehlt');
        var lehrer = document.getElementById('cms_sjfabrik_lehrer_kurs_'+kid[k]+'_personensuche_gewaehlt');
        if (schueler && lehrer) {
          if (cms_check_idfeld(schueler.value) && cms_check_idfeld(lehrer.value)) {
            formulardaten.append('schueler_'+kid[k], schueler.value);
            formulardaten.append('lehrer_'+kid[k], lehrer.value);
          }
          else {personenfehler = true;}
        }
        else {personenfehler = true;}
      }
    }

    if (personenfehler) {
      meldung += '<li>bei der Zuweisung der Personen ist ein Fehler aufgetreten.</li>';
      fehler = true;
    }

  }

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahrfabrik – Personen in Kursen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;
      if (rueckgabe.match(/KURSE/)) {
        nachfehler = true;
        meldung += '<li>es wurden Kurse ungültig zugeordnet.</li>';
      }
      else if (rueckgabe.match(/PERSONEN/)) {
        nachfehler = true;
        meldung += '<li>es wurden Personen ungültig zugeordnet.</li>';
      }

      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schuljahrfabrik – Personen in Kursen', '<p>Die Personen wurden den Kursen zugewiesen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\');">Weiter zu »Lehraufträgen«</span></p>');
			}
      else if (nachfehler) {
        cms_meldung_an('fehler', 'Schuljahrfabrik – Personen in Kursen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schuljahrfabrik_lehrauftraege() {
  cms_laden_an('Schuljahrfabrik – Lehraufträge', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Die Personen konnten nicht eigeordnet werden, denn ...</p><ul>';
	var fehler = false;

  var klassen = document.getElementById('cms_sjfabrik_klassen').value;
  var kurse = document.getElementById('cms_sjfabrik_kurse').value;

  // Fehler checken
  if (!cms_check_idfeld(klassen) || !cms_check_idfeld(kurse)) {
    meldung += '<li>die Eingaben sind fehlerhaft.</li>';
    fehler = true;
  }

  var klid = (klassen.substr(1)).split('|');
  var kuid = (kurse.substr(1)).split('|');
  var formulardaten = new FormData();
  if (!fehler) {
    formulardaten.append("klassen", klassen);
    formulardaten.append("kurse", kurse);
    formulardaten.append("anfragenziel", 	'278');

    // Senden vorbereiten: Klassenlehrer
    var personenfehler = false;
    for (var k=0; k<klid.length; k++) {
      if (klid[k].length > 0) {
        var lehrer = document.getElementById('cms_sjfabrik_lehrer_klasse_'+klid[k]+'_personensuche_gewaehlt');
        if (lehrer) {
          if (cms_check_idfeld(lehrer.value)) {
            formulardaten.append('klasse_lehrer_'+klid[k], lehrer.value);
          }
          else {personenfehler = true;}
        }
        else {personenfehler = true;}
      }
    }
    for (var k=0; k<kuid.length; k++) {
      if (kuid[k].length > 0) {
        var lehrer = document.getElementById('cms_sjfabrik_lehrer_kurs_'+kuid[k]+'_personensuche_gewaehlt');
        if (lehrer) {
          if (cms_check_idfeld(lehrer.value)) {
            formulardaten.append('kurs_lehrer_'+kuid[k], lehrer.value);
          }
          else {personenfehler = true;}
        }
        else {personenfehler = true;}
      }
    }

    if (personenfehler) {
      meldung += '<li>bei der Zuweisung der Personen ist ein Fehler aufgetreten.</li>';
      fehler = true;
    }

  }

	if (fehler) {
		cms_meldung_an('fehler', 'Schuljahrfabrik – Lehraufträge', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		function anfragennachbehandlung(rueckgabe) {
      var nachfehler = false;
      if (rueckgabe.match(/KURSE/)) {
        nachfehler = true;
        meldung += '<li>es wurden Kurse ungültig zugeordnet.</li>';
      }
      if (rueckgabe.match(/KLASSEN/)) {
        nachfehler = true;
        meldung += '<li>es wurden Klassen ungültig zugeordnet.</li>';
      }
      else if (rueckgabe.match(/PERSONEN/)) {
        nachfehler = true;
        meldung += '<li>es wurden Personen ungültig zugeordnet.</li>';
      }

      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schuljahrfabrik – Lehraufträge', '<p>Die Lehraufträge wurden verteilt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung\');">Zurück zur Übersicht</span> <span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Stundenplanung\');">Weiter zu »Stundenplanung«</span></p>');
			}
      else if (nachfehler) {
        cms_meldung_an('fehler', 'Schuljahrfabrik – Lehraufträge', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
