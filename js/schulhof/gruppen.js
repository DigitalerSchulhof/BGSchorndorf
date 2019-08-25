function cms_gruppe_abonnieren(art, id) {
  var abo = document.getElementById('cms_gruppe_abonnieren').value;
  var fehler = false;
  if (!cms_check_toggle(abo)) {fehler = true;}

  if (!fehler) {
    if (abo == '1') {cms_laden_an('Gruppe abonnieren', 'Das Abo wird eingerichtet.');}
    else if (abo == '0') {cms_laden_an('Gruppenabo beenden', 'Das Abo wird entfernt.');}

    var formulardaten = new FormData();
    formulardaten.append('anfragenziel', '129');
    formulardaten.append('art', art);
    formulardaten.append('id', id);
    formulardaten.append('abo', abo);

    function anfragennachbehandlung(rueckgabe) {
  		if (rueckgabe == "ERFOLG") {
        if (abo == '1') {
          cms_meldung_an('erfolg', 'Gruppe abonnieren', '<p>Die Gruppe wurde abonniert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück zur Übersicht</span></p>');
        }
        else if (abo == '0') {
          cms_meldung_an('erfolg', 'Gruppenabo beenden', '<p>Die Abo wurde entfernt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück zur Übersicht</span></p>');
        }

      }
  		else {
        cms_fehlerbehandlung(rueckgabe);
      }
  	}
  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
  else {
    cms_meldung_fehler();
  }
}

function cms_blogeintraegeintern_eingabenpruefen() {
	var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	var genehmigt = document.getElementById('cms_blogeintrag_genehmigt').value;
	var notifikationen = document.getElementById('cms_blogeintrag_notifikationen').value;
	var aktiv = document.getElementById('cms_blogeintrag_aktiv').value;
	var datumT = document.getElementById('cms_blogeintrag_datum_T').value;
	var datumM = document.getElementById('cms_blogeintrag_datum_M').value;
	var datumJ = document.getElementById('cms_blogeintrag_datum_J').value;

	var bezeichnung = document.getElementById('cms_blogeintrag_bezeichnung').value;
	var zusammenfassung = document.getElementById('cms_blogeintrag_zusammenfassung').value;
	var autor = document.getElementById('cms_blogeintrag_autor').value;
	var downloadanzahl = document.getElementById('cms_downloads_anzahl').value;
	var downloadids = document.getElementById('cms_downloads_ids').value;
	var beschluesseanzahl = document.getElementById('cms_beschluesse_anzahl').value;
	var beschluesseids = document.getElementById('cms_beschluesse_ids').value;

	var formulardaten = new FormData();

	var inhalt = document.getElementsByClassName('note-editable');
	inhalt = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(genehmigt)) {
		meldung += '<li>Die Eingabe für die Genehmigung ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(notifikationen)) {
		meldung += '<li>Die Eingabe für Notifikationen ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>Die Eingabe für die Aktivität ist ungültig.</li>';
		fehler = true;
	}

	// Downloads
	var dtitelf = false;
	var dpfadf = false;
	var ddateinamef = false;
	var ddateigroessef = false;
	var dfehler = false;
	if (downloadanzahl > 0) {
		ids = downloadids.split('|');
		for (i=1; i<ids.length; i++) {
			var did = ids[i];

			var dtitel = document.getElementById('cms_download_titel_'+did);
			var dbeschreibung = document.getElementById('cms_download_beschreibung_'+did);
			var dpfad = document.getElementById('cms_download_datei_'+did);
			var ddateiname = document.getElementById('cms_cms_download_dateiname_'+did);
			var ddateigroesse = document.getElementById('cms_cms_download_dateigroesse_'+did);

			if (dtitel) {if ((dtitel.value).length > 0) {formulardaten.append("dtitel_"+did,  dtitel.value);} else {dtitelf = true;}} else {dfehler = true;}
			if (dbeschreibung) {formulardaten.append("dbeschreibung_"+did,  dbeschreibung.value);} else {dfehler = true;}
			if (dpfad) {if ((dpfad.value).length > 0) {formulardaten.append("dpfad_"+did,  dpfad.value);} else {dpfadf = true;}} else {dfehler = true;}
			if (ddateiname) {if ((ddateiname.value == 1) || (ddateiname.value == 0)) {formulardaten.append("ddateiname_"+did,  ddateiname.value);} else {ddateinamef = true;}} else {dfehler = true;}
			if (ddateigroesse) {if ((ddateigroesse.value == 1) || (ddateigroesse.value == 0)) {formulardaten.append("ddateigroesse_"+did,  ddateigroesse.value);} else {ddateigroessef = true;}} else {dfehler = true;}
		}
	}

	if (dfehler) {
		meldung += '<li>bei der Erstellung der Downloads ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (dtitelf) {
		meldung += '<li>bei mindestens einem Download ist der Titel nicht angegeben.</li>';
		fehler = true;
	}
	if (dpfadf) {
		meldung += '<li>bei mindestens einem Download wurde keine Datei ausgewählt.</li>';
		fehler = true;
	}
	if (ddateinamef) {
		meldung += '<li>bei mindestens einem Download ist die Eingabe für die Anzeige des Dateinamens ungültig.</li>';
		fehler = true;
	}
	if (ddateigroessef) {
		meldung += '<li>bei mindestens einem Download ist die Eingabe für die Anzeige der Dateigröße ungültig.</li>';
		fehler = true;
	}

  // Beschluesse
	var btitelf = false;
	var bstimmenf = false;
	var blangfristigf = false;
	var bfehler = false;
	if (beschluesseanzahl > 0) {
		ids = beschluesseids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var btitel = document.getElementById('cms_beschluss_titel_'+bid);
			var bbeschreibung = document.getElementById('cms_beschluss_beschreibung_'+bid);
			var blangfristig = document.getElementById('cms_cms_beschluss_langfristig_'+bid);
			var bpro = document.getElementById('cms_beschluss_pro_'+bid);
			var benthaltung = document.getElementById('cms_beschluss_enthaltung_'+bid);
			var bcontra = document.getElementById('cms_beschluss_contra_'+bid);

			if (btitel) {if ((btitel.value).length > 0) {formulardaten.append("btitel_"+bid,  btitel.value);} else {btitelf = true;}} else {bfehler = true;}
			if (bbeschreibung) {formulardaten.append("bbeschreibung_"+bid,  bbeschreibung.value);} else {bfehler = true;}
      if (blangfristig) {if (cms_check_toggle(blangfristig.value)) {formulardaten.append("blangfristig_"+bid,  blangfristig.value);} else {blangfristigf = true;}} else {bfehler = true;}
      if (bpro) {if (cms_check_ganzzahl(bpro.value, 0)) {formulardaten.append("bpro_"+bid,  bpro.value);} else {bstimmenf = true;}} else {bfehler = true;}
      if (benthaltung) {if (cms_check_ganzzahl(benthaltung.value, 0)) {formulardaten.append("benthaltung_"+bid,  benthaltung.value);} else {bstimmenf = true;}} else {bfehler = true;}
      if (bcontra) {if (cms_check_ganzzahl(bcontra.value, 0)) {formulardaten.append("bcontra_"+bid,  bcontra.value);} else {bstimmenf = true;}} else {bfehler = true;}
		}
	}

	if (bfehler) {
		meldung += '<li>bei der Erstellung der Beschlüsse ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (btitelf) {
		meldung += '<li>bei mindestens einem Beschluss ist der Titel nicht angegeben.</li>';
		fehler = true;
	}
	if (blangfristigf) {
		meldung += '<li>bei mindestens einem Beschluss ist die Auswahl für die Langfristigkeit ungültig.</li>';
		fehler = true;
	}
	if (bstimmenf) {
		meldung += '<li>bei mindestens einem Beschluss ist die Eingabe für die Stimmen ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("genehmigt",  			  genehmigt);
		formulardaten.append("notifikationen",    notifikationen);
		formulardaten.append("aktiv",  					  aktiv);
		formulardaten.append("datumT",  				  datumT);
		formulardaten.append("datumM",					  datumM);
		formulardaten.append("datumJ",  				  datumJ);
		formulardaten.append("bezeichnung",  		  bezeichnung);
		formulardaten.append("autor",  					  autor);
		formulardaten.append("inhalt",  				  inhalt);
		formulardaten.append("zusammenfassung",   zusammenfassung);
		formulardaten.append("downloadanzahl",    downloadanzahl);
		formulardaten.append("downloadids", 		  downloadids);
		formulardaten.append("beschluesseanzahl", beschluesseanzahl);
		formulardaten.append("beschluesseids", 		beschluesseids);
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}

function cms_termineintern_eingabenpruefen(modus) {
	var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	var genehmigt = document.getElementById('cms_termin_genehmigt').value;
	var notifikationen = document.getElementById('cms_termin_notifikationen').value;
	var aktiv = document.getElementById('cms_termin_aktiv').value;
	var mehrtaegigt = document.getElementById('cms_termin_mehrtaegig').value;
	var uhrzeitbt = document.getElementById('cms_termin_uhrzeitb').value;
	var uhrzeitet = document.getElementById('cms_termin_uhrzeite').value;
	var ortt = document.getElementById('cms_termin_ortt').value;
	var beginnT = document.getElementById('cms_termin_beginn_datum_T').value;
	var beginnM = document.getElementById('cms_termin_beginn_datum_M').value;
	var beginnJ = document.getElementById('cms_termin_beginn_datum_J').value;
	var endeT = document.getElementById('cms_termin_ende_datum_T').value;
	var endeM = document.getElementById('cms_termin_ende_datum_M').value;
	var endeJ = document.getElementById('cms_termin_ende_datum_J').value;
	var beginnh = document.getElementById('cms_termin_beginn_zeit_h').value;
	var beginnm = document.getElementById('cms_termin_beginn_zeit_m').value;
	var endeh = document.getElementById('cms_termin_ende_zeit_h').value;
	var endem = document.getElementById('cms_termin_ende_zeit_m').value;
	var bezeichnung = document.getElementById('cms_termin_bezeichnung').value;
	var downloadanzahl = document.getElementById('cms_downloads_anzahl').value;
	var downloadids = document.getElementById('cms_downloads_ids').value;
	var ort = document.getElementById('cms_termin_ort').value;
	if (modus == 'neu') {
		var wdh = document.getElementById('cms_termin_wdh_datumbeginn').value;
	}
	var formulardaten = new FormData();


	var inhalt = document.getElementsByClassName('note-editable');
	inhalt = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(genehmigt)) {
		meldung += '<li>Die Eingabe für die Genehmigung ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(notifikationen)) {
		meldung += '<li>Die Eingabe für die Notifikationen ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>Die Eingabe für die Aktivität ist ungültig.</li>';
		fehler = true;
	}

	if (mehrtaegigt == 1) {
		beginnd = new Date(beginnJ, beginnM-1, beginnT, 0, 0, 0, 0);
		ended = new Date(endeJ, endeM-1, endeT, 23, 59, 59, 999);
		if (uhrzeitbt == 1) {beginnd = new Date(beginnJ, beginnM-1, beginnT, beginnh, beginnm, 0, 0);}
		if (uhrzeitet == 1) {ended = new Date(endeJ, endeM-1, endeT, endeh, endem, 59, 999);}
	}
	else {
		beginnd = new Date(beginnJ, beginnM-1, beginnT, 0, 0, 0, 0);
		ended = new Date(beginnJ, beginnM-1, beginnT, 23, 59, 59, 999);
		if (uhrzeitbt == 1) {beginnd = new Date(beginnJ, beginnM-1, beginnT, beginnh, beginnm, 0, 0);}
		if (uhrzeitet == 1) {ended = new Date(beginnJ, beginnM-1, beginnT, endeh, endem, 59, 999);}
	}

	if (beginnd-ended >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum eingegeben.</li>';
		fehler = true;
	}

	if (ortt == 1) {
		if (ort.length == 0) {
			meldung += '<li>es wurde kein gültiger Ort eingegeben.</li>';
			fehler = true;
		}
	}

	// Downloads
	var dtitelf = false;
	var dpfadf = false;
	var ddateinamef = false;
	var ddateigroessef = false;
	var dfehler = false;
	if (downloadanzahl > 0) {
		ids = downloadids.split('|');
		for (i=1; i<ids.length; i++) {
			var did = ids[i];

			var dtitel = document.getElementById('cms_download_titel_'+did);
			var dbeschreibung = document.getElementById('cms_download_beschreibung_'+did);
			var dpfad = document.getElementById('cms_download_datei_'+did);
			var ddateiname = document.getElementById('cms_cms_download_dateiname_'+did);
			var ddateigroesse = document.getElementById('cms_cms_download_dateigroesse_'+did);

			if (dtitel) {if ((dtitel.value).length > 0) {formulardaten.append("dtitel_"+did,  dtitel.value);} else {dtitelf = true;}} else {dfehler = true;}
			if (dbeschreibung) {formulardaten.append("dbeschreibung_"+did,  dbeschreibung.value);} else {dfehler = true;}
			if (dpfad) {if ((dpfad.value).length > 0) {formulardaten.append("dpfad_"+did,  dpfad.value);} else {dpfadf = true;}} else {dfehler = true;}
			if (ddateiname) {if ((ddateiname.value == 1) || (ddateiname.value == 0)) {formulardaten.append("ddateiname_"+did,  ddateiname.value);} else {ddateinamef = true;}} else {dfehler = true;}
			if (ddateigroesse) {if ((ddateigroesse.value == 1) || (ddateigroesse.value == 0)) {formulardaten.append("ddateigroesse_"+did,  ddateigroesse.value);} else {ddateigroessef = true;}} else {dfehler = true;}
		}
	}

	if (dfehler) {
		meldung += '<li>bei der Erstellung der Downloads ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (dtitelf) {
		meldung += '<li>bei mindestens einem Download ist der Titel nicht angegeben.</li>';
		fehler = true;
	}
	if (dpfadf) {
		meldung += '<li>bei mindestens einem Download wurde keine Datei ausgewählt.</li>';
		fehler = true;
	}
	if (ddateinamef) {
		meldung += '<li>bei mindestens einem Download ist die Eingabe für die Anzeige des Dateinamens ungültig.</li>';
		fehler = true;
	}
	if (ddateigroessef) {
		meldung += '<li>bei mindestens einem Download ist die Eingabe für die Anzeige der Dateigröße ungültig.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("genehmigt",  			genehmigt);
		formulardaten.append("notifikationen",  notifikationen);
		formulardaten.append("aktiv",  					aktiv);
		formulardaten.append("mehrtaegigt", 		mehrtaegigt);
		formulardaten.append("uhrzeitbt",  			uhrzeitbt);
		formulardaten.append("uhrzeitet", 			uhrzeitet);
		formulardaten.append("ortt",  					ortt);
		formulardaten.append("beginnT",  				beginnT);
		formulardaten.append("beginnM",					beginnM);
		formulardaten.append("beginnJ",  				beginnJ);
		formulardaten.append("endeT",  					endeT);
		formulardaten.append("endeM",  					endeM);
		formulardaten.append("endeJ",  					endeJ);
		formulardaten.append("beginnh",  				beginnh);
		formulardaten.append("beginnm",  				beginnm);
		formulardaten.append("endeh",  					endeh);
		formulardaten.append("endem",  					endem);
		formulardaten.append("bezeichnung",  		bezeichnung);
		formulardaten.append("ort",  						ort);
		formulardaten.append("inhalt",  				inhalt);
		formulardaten.append("downloadanzahl",  downloadanzahl);
		formulardaten.append("downloadids", 		downloadids);
		if (modus == 'neu') {formulardaten.append("wdh", wdh);}
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}


function cms_blogeintraegeintern_neu_speichern(ziel) {
	cms_laden_an('Neuen Blogeintrag anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_blogeintraegeintern_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '17');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Blogeintrag anlegen', '<p>Der Blogeintrag wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
      else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, auf den der Blogeintrag datiert ist, existiert bereits ein Blogeintrag mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Blogeintrag anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
      else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>der Blogeintrag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Blogeintrag anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neuen Blogeintrag anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_blogeintraegeintern_bearbeiten_vorbereiten(id, ziel, gruppe, gruppenid, schuljahr, gruppenname) {
  var gruppe = gruppe || null;
  var gruppenid = gruppenid || null;
  var schuljahr = schuljahr || null;
  var gruppenname = gruppenname || null;
	cms_laden_an('Blogeintrag bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
  formulardaten.append("gruppe", gruppe);
	formulardaten.append("gruppenid", gruppenid);
	formulardaten.append("anfragenziel", 	'22');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
      cms_link(ziel+'/Blogeintrag_bearbeiten');
		}
    if (rueckgabe == "ERFOLG") {
      if ((gruppe === null) && (gruppenid === null)) {cms_link(ziel+'/Blogeintrag_bearbeiten');}
      else {cms_link('Schulhof/Gruppen/'+cms_textzulink(schuljahr)+'/'+cms_textzulink(gruppe)+'/'+cms_textzulink(gruppenname)+'/Blog/Blogeintrag_bearbeiten');}
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_blogeintraegeintern_bearbeiten_speichern(ziel) {
	cms_laden_an('Blogeintrag bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_blogeintraegeintern_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '23');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Blogeintrag bearbeiten', '<p>Die Änderungen wurden übernommen</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, auf den der Blogeintrag datiert ist, existiert bereits ein Blogeintrag mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Blogeintrag bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
      else if (rueckgabe.match(/BÖSE/)) {
        var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
        meldung += '<ul><li>der Blogeintrag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
        cms_meldung_an('fehler', 'Neuen Blogeintrag anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Blogeintrag bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_blogeintraegeintern_loeschen_vorbereiten(id, gruppe, gruppenid, bezeichnung, ziel) {
	cms_meldung_an('warnung', 'Blogeintrag löschen', '<p>Soll der Blogeintrag <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_blogeintraegeintern_loeschen(\''+id+'\',\''+gruppe+'\',\''+gruppenid+'\',\''+ziel+'\')">Löschung durchführen</span></p>');
}

function cms_blogeintraegeintern_loeschen(id, gruppe, gruppenid, ziel) {
	cms_laden_an('Blogeintrag löschen', 'Der Blogeintrag wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("gruppe", gruppe);
	formulardaten.append("gruppenid", gruppenid);
	formulardaten.append("anfragenziel", 	'24');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Blogeintrag löschen', '<p>Der Blogeintrag wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_termineintern_neu_speichern(ziel) {
	cms_laden_an('Neuen Termin anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_termineintern_eingabenpruefen('neu');

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '204');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Termin anlegen', '<p>Der Termin wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, an dem der Termin beginnt, existiert bereits ein Termin mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Termin anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
      else if (rueckgabe.match(/BÖSE/)) {
        var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
        meldung += '<ul><li>der Blogeintrag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
        cms_meldung_an('fehler', 'Neuen Blogeintrag anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neuen Termin anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_termineintern_bearbeiten_vorbereiten(id, ziel, gruppe, gruppenid, schuljahr, gruppenname) {
  var gruppe = gruppe || null;
  var gruppenid = gruppenid || null;
  var schuljahr = schuljahr || null;
  var gruppenname = gruppenname || null;
	cms_laden_an('Termin bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
	formulardaten.append("gruppe", gruppe);
	formulardaten.append("gruppenid", gruppenid);
	formulardaten.append("anfragenziel", 	'205');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
      if ((gruppe === null) && (gruppenid === null)) {cms_link(ziel+'/Termin_bearbeiten');}
      else {cms_link('Schulhof/Gruppen/'+cms_textzulink(schuljahr)+'/'+cms_textzulink(gruppe)+'/'+cms_textzulink(gruppenname)+'/Termine/Termin_bearbeiten');}
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_termineintern_bearbeiten_speichern(ziel) {
	cms_laden_an('Termin bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_termineintern_eingabenpruefen('bearbeiten');

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '206');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Termin bearbeiten', '<p>Die Änderungen wurden übernommen</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Termin konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, an dem der Termin beginnt, existiert bereits ein Termin mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Termin bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
      else if (rueckgabe.match(/BÖSE/)) {
        var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
        meldung += '<ul><li>der Blogeintrag enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
        cms_meldung_an('fehler', 'Neuen Blogeintrag anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Termin bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_termineintern_loeschen_vorbereiten(id, gruppe, gruppenid, bezeichnung, ziel) {
	cms_meldung_an('warnung', 'Termin löschen', '<p>Soll der Termine <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_termineintern_loeschen(\''+id+'\',\''+gruppe+'\',\''+gruppenid+'\',\''+ziel+'\')">Löschung durchführen</span></p>');
}

function cms_termineintern_loeschen(id, gruppe, gruppenid, ziel) {
	cms_laden_an('Termin löschen', 'Der Termin wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("gruppe", gruppe);
	formulardaten.append("gruppenid", gruppenid);
	formulardaten.append("anfragenziel", 	'207');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Termin löschen', '<p>Der Termin wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_chat_nachricht_senden(art, id) {
  var chat = $("#cms_chat_nachrichten");
  var d = new Date();
  var nachricht = $("#cms_chat_neue_nachricht").val();
  $("#cms_chat_neue_nachricht").val("").focus();
  if(!nachricht)
    return;

  var tag = $("<div></div>", {class: "cms_chat_datum cms_notiz"}).html(cms_tagnamekomplett(d.getDay())+", den "+cms_datumzweistellig(d.getDate())+" "+cms_monatsnamekomplett(d.getMonth()+1));
  if(!chat.find(".cms_chat_datum").filter(function() {return $(this).html() == tag.html()}).length)
    tag.appendTo(chat);
  chat.find("#cms_chat_leer").remove();

  var nachr = $("<div></div>", {class: "cms_chat_nachricht_aussen cms_chat_nachricht_eigen cms_chat_nachricht_sendend"}).append(
    $("<div></div>", {class: "cms_chat_nachricht_innen"}).append(
      $("<div></div>", {class: "cms_chat_nachricht_id"}),
      $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "sendend"}).html($("<img></img>", {src: "res/laden/standard.gif"})),
      $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "mehr"}).html("&vellip;<span class=\"cms_hinweis\"><p data-mehr=\"melden\" onclick=\"cms_chat_nachricht_melden_anzeigen(this, '"+art+"', '"+id+"')\">Nachricht melden</p></span>"),
      $("<div></div>", {class: "cms_chat_nachricht_autor"}).html(CMS_BENUTZERTITEL+" "+CMS_BENUTZERVORNAME+" "+CMS_BENUTZERNACHNAME),
      $("<div></div>", {class: "cms_chat_nachricht_nachricht"}).text(nachricht),
      $("<div></div>", {class: "cms_chat_nachricht_zeit"}).html(("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2))
    )
  ).appendTo(chat);
  nachr.find(".cms_chat_nachricht_aktion").click(cms_chat_aktion);
  chat.scrollTop(chat.prop("scrollHeight"));

  var formulardaten = new FormData();
	formulardaten.append("nachricht", nachricht);
  formulardaten.append("gruppe", art);
	formulardaten.append("gruppenid", id);
	formulardaten.append("anfragenziel", 	'269');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.startsWith("ERFOLG")) {
      nachr.removeClass("cms_chat_nachricht_sendend");
      nachr.find(".cms_chat_nachricht_id").html(rueckgabe.replace("ERFOLG", ""));
		} else if(rueckgabe == "BERECHTIGUNG") {
      nachr.remove();
      cms_fehlerbehandlung(rueckgabe);
    }
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

$(window).on("load", function() {
  var chat = $("#cms_chat_nachrichten");
  chat.scrollTop(chat.prop("scrollHeight"));
  $(".cms_chat_nachricht_aktion").click(cms_chat_aktion);
});

function cms_chat_aktion() {
  if($(this).data("aktion") == "mehr") {
    var h = $(this).find(".cms_hinweis");
    $(this).parents("#cms_chat_nachrichten").find(".cms_chat_nachricht_aktion[data-aktion=mehr] .cms_hinweis").not(function() {return $(this).is(h)}).removeClass("cms_hinweis_sichtbar");
    h.toggleClass("cms_hinweis_sichtbar");
  }
}

function cms_chat_enter(e, art, id) {
  if(e.keyCode && e.keyCode === 10)
    if(e.ctrlKey) {
      cms_chat_nachricht_senden(art, id);
      return false;
    }
  return true;
}

function cms_chat_aktualisieren(art, id) {
  var formulardaten = new FormData();
  formulardaten.append("gruppe", art);
  formulardaten.append("gruppenid", id);
  formulardaten.append("anfragenziel", 	'270');
  var chat = $("#cms_chat_nachrichten");
  var gid = id;

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "BERECHTIGUNG" || rueckgabe == "FEHLER")
      cms_fehlerbehandlung(rueckgabe);
    else {
      if(!rueckgabe)
        return;
      var r = rueckgabe.split(String.fromCharCode(29));
      r.pop();
      chat.find("#cms_chat_leer").remove();
      var id, p, d, dt, i, m, tag, nachr;
      while(r.length) {
        id   = r.shift();         // Id
        p    = r.shift();         // Person
        dt   = r.shift()          // Datum
        d    = new Date(dt*1000); // Datum als Date()
        i    = r.shift();         // Inhalt
        tag = $("<div></div>", {class: "cms_chat_datum cms_notiz"}).html(cms_tagnamekomplett(d.getDay())+", den "+cms_datumzweistellig(d.getDate())+" "+cms_monatsnamekomplett(d.getMonth()+1));
        if(!chat.find(".cms_chat_datum").filter(function() {return $(this).html() == tag.html()}).length)
          tag.appendTo(chat);
        nachr = $("<div></div>", {class: "cms_chat_nachricht_aussen"}).append(
          $("<div></div>", {class: "cms_chat_nachricht_innen"}).append(
            $("<div></div>", {class: "cms_chat_nachricht_id"}).html(id),
            $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "sendend"}).html($("<img></img>", {src: "res/laden/standard.gif"})),
            $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "mehr"}).html("&vellip;<span class=\"cms_hinweis\"><p data-mehr=\"melden\" onclick=\"cms_chat_nachricht_melden_anzeigen(this, '"+art+"', '"+gid+"')\">Nachricht melden</p></span>"),
            $("<div></div>", {class: "cms_chat_nachricht_autor"}).html(p),
            $("<div></div>", {class: "cms_chat_nachricht_nachricht"}).html(i),
            $("<div></div>", {class: "cms_chat_nachricht_zeit"}).html(("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2))
          )
        ).appendTo(chat).find(".cms_chat_nachricht_aktion").click(cms_chat_aktion);
        chat.scrollTop(chat.prop("scrollHeight"));
      }
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_chat_nachricht_melden_anzeigen(t, art, gid) {
  var p = $(t).parents(".cms_chat_nachricht_aussen");
  if(p.hasClass("cms_chat_nachricht_gemeldet")) {
    cms_meldung_an("warnung", "Nachricht gemeldet", "<p>Diese Nachricht wurde schon gemeldet.</p>", '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span>');
    return;
  }
  var id = p.find(".cms_chat_nachricht_id").html();
  cms_meldung_an('warnung', 'Nachricht melden', '<p>Soll die Nachricht wirklich gemeldet werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_chat_nachricht_melden(\''+art+'\', \''+gid+'\', \''+id+'\')">Melden</span></p>');
}

function cms_chat_nachricht_melden(art, gid, id) {
  cms_laden_an("Nachricht melden", "Die Nachricht wird gemeldet.");
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("gruppe", art);
  formulardaten.append("gruppenid", gid);
  formulardaten.append("anfragenziel", 	'271');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_laden_aus();
      $("#cms_chat").find(".cms_chat_nachricht_id").filter(function () {return $(this).html() == id}).parents(".cms_chat_nachricht_aussen").addClass("cms_chat_nachricht_gemeldet");
    } else if(rueckgabe == "BERECHTIGUNG") {
      cms_fehlerbehandlung(rueckgabe);
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_chat_nachrichten_nachladen(art, id, anzahl) {
  cms_laden_an("Altere Nachrichten laden", "Nachrichten werden geladen")
  var formulardaten = new FormData();
  formulardaten.append("gruppe", art);
  formulardaten.append("gruppenid", id);
  formulardaten.append("anzahl", anzahl);
  formulardaten.append("anfragenziel", 	'273');
  var chat = $("#cms_chat_nachrichten");
  var gid = id;

  function anfragennachbehandlung(rueckgabe) {
    if(rueckgabe == "BERECHTIGUNG" || rueckgabe == "FEHLER")
      cms_fehlerbehandlung(rueckgabe);
    else {
      if(!rueckgabe)
        return;
      var r = rueckgabe.split(String.fromCharCode(29));
      r.pop();
      var id, p, d, dt, i, m, e, tag, nachr, mehr = r.shift();;
      while(r.length) {
        id   = r.shift();
        p    = r.shift();
        dt   = r.shift()
        d    = new Date(dt*1000);
        i    = r.shift();
        m    = r.shift();
        e    = r.shift();

        tag = $("<div></div>", {class: "cms_chat_datum cms_notiz"}).html(cms_tagnamekomplett(d.getDay())+", den "+cms_datumzweistellig(d.getDate())+" "+cms_monatsnamekomplett(d.getMonth()+1));
        var anderet = chat.find(".cms_chat_datum").filter(function() {return $(this).html() == tag.html()});  // Andere Datum Meldungen
        anderet.remove();
        nachr = $("<div></div>", {class: "cms_chat_nachricht_aussen"+(e?" cms_chat_nachricht_eigen":"")}).append(
          $("<div></div>", {class: "cms_chat_nachricht_innen"}).append(
            $("<div></div>", {class: "cms_chat_nachricht_id"}).html(id),
            $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "sendend"}).html($("<img></img>", {src: "res/laden/standard.gif"})),
            $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "mehr"}).html("&vellip;<span class=\"cms_hinweis\"><p data-mehr=\"melden\" onclick=\"cms_chat_nachricht_melden_anzeigen(this, '"+art+"', '"+gid+"')\">Nachricht melden</p></span>"),
            $("<div></div>", {class: "cms_chat_nachricht_autor"}).html(p),
            $("<div></div>", {class: "cms_chat_nachricht_nachricht"}).html(i),
            $("<div></div>", {class: "cms_chat_nachricht_zeit"}).html(("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2))
          )
        ).prependTo(chat).find(".cms_chat_nachricht_aktion").click(cms_chat_aktion);
        tag.prependTo(chat);
      }
      if(mehr == true)
        $("#cms_chat_nachrichten_nachladen").show().remove().prependTo(chat);
      else
        $("#cms_chat_nachrichten_nachladen").hide();
      cms_laden_aus();
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
