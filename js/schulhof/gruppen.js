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
  var artikellinkanzahl = document.getElementById('cms_artikellinks_anzahl').value;
	var artikellinkids = document.getElementById('cms_artikellinks_ids').value;
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

  // Links
	var ltitelf = false;
	var llinkf = false;
	var lfehler = false;
	if (artikellinkanzahl > 0) {
		ids = artikellinkids.split('|');
		for (i=1; i<ids.length; i++) {
			var lid = ids[i];

			var ltitel = document.getElementById('cms_artikellink_titel_'+lid);
			var lbeschreibung = document.getElementById('cms_artikellink_beschreibung_'+lid);
			var llink = document.getElementById('cms_artikellink_link_'+lid);

			if (ltitel) {if ((ltitel.value).length > 0) {formulardaten.append("ltitel_"+lid,  ltitel.value);} else {ltitelf = true;}} else {lfehler = true;}
			if (lbeschreibung) {formulardaten.append("lbeschreibung_"+lid,  lbeschreibung.value);} else {lfehler = true;}
			if (llink) {if ((llink.value).length > 0) {formulardaten.append("llink_"+lid,  llink.value);} else {llinkf = true;}} else {lfehler = true;}
		}
	}

	if (lfehler) {
		meldung += '<li>bei der Erstellung der zugehörigen Links ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (ltitelf) {
		meldung += '<li>bei mindestens einem Link ist der Titel nicht angegeben.</li>';
		fehler = true;
	}
	if (llinkf) {
		meldung += '<li>bei mindestens einem Link wurde keine Adresse angegeben.</li>';
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
    formulardaten.append("artikellinkanzahl",	artikellinkanzahl);
		formulardaten.append("artikellinkids", 		artikellinkids);
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

function cms_gruppe_pinnwand_speichern(gruppe, gruppenid, ziel) {
  cms_laden_an('Pinnwand ändern', 'Der Inhalt der Pinnwand wird geändert.');
  var pinnwand = document.getElementById('cms_gruppenpinnwand').value;

	var formulardaten = new FormData();
	formulardaten.append("gruppe", gruppe);
	formulardaten.append("gruppenid", gruppenid);
	formulardaten.append("pinnwand", pinnwand);
	formulardaten.append("anfragenziel", 	'294');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Pinnwand ändern', '<p>Der Inhalt der Pinnwand wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



/*
  0: Init
  1: Verbinden
  2: Neu Verbinden...
  3: Verbunden
  4: Authentifikation
  5: Chat laden
  6: Erfolg
*/
/* Socket Zeug */
var socketChat = {
  socket: null,
  g: null,
  gid: null,
  chat: null,
  nachrichten: null,
  verbindenInterval: null,
  intervalUebrig: null,
  sendend: null,
  status: 0,
  rechte: {
    loeschen: false,
    stummschalten: false
  },
  server: {
    ip: null,
    port: null
  },
  stummTimeout: null,
  statusSetzen: function(nachricht, laden) {
    laden !== true && laden !== false ? laden = true : laden;
    socketChat.chat.removeClass("cms_chat_status cms_chat_laden cms_chat_stumm cms_chat_leer cms_chat_mehr");
    if(nachricht === null)
      return;

    if(nachricht) {
      socketChat.chat.addClass("cms_chat_status");
      socketChat.chat.find("#cms_chat_status").html("");
    }
    if(laden === true || laden === false)
      laden ? socketChat.chat.addClass("cms_chat_laden") : socketChat.chat.removeClass("cms_chat_laden")
    if($.isArray(nachricht))
      $.each(nachricht, function(i, n) {
        socketChat.chat.find("#cms_chat_status").append("<h3>"+n+"</h3>");
      })
    else
      socketChat.chat.find("#cms_chat_status").html("<h3>"+nachricht+"</h3>");
  },
  eventsSetzten: function(socket) {
    socket = socket || socketChat.socket;
    socket.onopen = socketChat.events.open;
    socket.onclose = socketChat.events.close;
    socket.onmessage = socketChat.events.nachricht;
  },
  verbinden: function() {
    socketChat.statusSetzen("Verbindung zum Webchat aufbauen...");
    socketChat.status = 1;
    if(socketChat.verbindenInterval)
      clearInterval(socketChat.verbindenInterval)
    socketChat.socket = new WebSocket("wss://"+socketChat.server.ip+":"+socketChat.server.port);
    socketChat.eventsSetzten();
  },
  senden: function(nachricht) {
    if(!socketChat.socket && !socketChat.verbindenInterval) {
      socketChat.neuVerbinden();
      return;
    }
    socketChat.socket.send(JSON.stringify(nachricht));
  },
  authentifizieren: function() {
    socketChat.statusSetzen("Authentifizieren...")
    auth = {
      "status": "0",
      "g": socketChat.g,
      "gid": socketChat.gid,
      "sessid": CMS_SESSIONID
    };
    socketChat.senden(auth);
  },
  neuVerbinden: function() {
    var s = socketChat.status;
    socketChat.status = 2;
    socketChat.socket = null;
    if(socketChat.verbindenInterval)
      clearInterval(socketChat.verbindenInterval)
    socketChat.intervalUebrig = 5;
    socketChat.verbindenInterval = setInterval(function() {
      var n = s == 4 ? "Authentifikation fehlgeschlagen..." : "Verbindung verloren...";

      socketChat.statusSetzen([n, "Neuer Versuch in "+socketChat.intervalUebrig], false);
      if(socketChat.intervalUebrig-- <= 0)
        socketChat.verbinden();
    }, 1000);
  },
  init: function(g, gid) {
    socketChat.g = g;
    socketChat.gid = gid;
    socketChat.status = 0;
    socketChat.chat = $("#cms_chat");
    socketChat.nachrichten = socketChat.chat.find("#cms_chat_nachrichten");
    socketChat.sendend = [];
    socketChat.verbinden();
  },
  datum: function(tag, nachgeladen) {
    nachgeladen = nachgeladen || false;

    var andere = socketChat.nachrichten.find(".cms_chat_datum").filter(function() {return $(this).html() == tag});  // Andere Datum Meldungen
    if(nachgeladen) {
      andere.remove();
      socketChat.chat.find("#cms_chat_nachrichten").prepend($("<div></div>", {class: "cms_chat_datum cms_notiz"}).text(tag));
    } else
      if(!andere.length)
        socketChat.chat.find("#cms_chat_nachrichten").append($("<div></div>", {class: "cms_chat_datum cms_notiz"}).text(tag));
  },
  aktionen: function(n) {
    var r = "";
    if(!n.eigen)
      r += "<p data-mehr=\"melden\" onclick=\"cms_chat_nachricht_melden_anzeigen(this, '"+socketChat.g+"', '"+socketChat.gid+"')\">Nachricht melden</p>";
    if(socketChat.rechte.loeschen)
      r += "<p data-mehr=\"loeschen\" onclick=\"cms_chat_nachricht_loeschen_anzeigen(this)\">Nachricht löschen</p>";
    if(socketChat.rechte.stummschalten)
      r += "<p data-mehr=\"bannen\" onclick=\"cms_chat_nutzer_stummschalten_anzeigen(this)\">Sender stummschalten</p>";
    return r;
  },
  nachrichtAnzeigen: function(n, nachgeladen) {
    nachgeladen = nachgeladen || false;
    var aktionen = socketChat.aktionen(n);
    if(!nachgeladen)
      socketChat.datum(n.tag);

    var nachr = $("<div></div>", {class: "cms_chat_nachricht_aussen"+(n.selbstgemeldet?" cms_chat_nachricht_gemeldet":""), id: "cms_chat_nachricht_"+n.id}).append(
      $("<div></div>", {class: "cms_chat_nachricht_innen"}).append(
        $("<div></div>", {class: "cms_chat_nachricht_id"}).text(n.id),
        $("<div></div>", {class: "cms_chat_nachricht_aktion", "data-aktion": "mehr"}).html("&vellip;<span class=\"cms_chat_aktion\">"+aktionen+"</span>"),
        $("<div></div>", {class: "cms_chat_nachricht_autor"}).html(n.name),
        $("<div></div>", {class: "cms_chat_nachricht_nachricht"}).html(n.inhalt),
        $("<div></div>", {class: "cms_chat_nachricht_zeit"}).html(n.zeit)
      )
    );
    if(nachgeladen)
      nachr.prependTo(socketChat.nachrichten);
    else
      nachr.appendTo(socketChat.nachrichten);

    if(nachgeladen)
      socketChat.datum(n.tag, true);  // Reigenfolge entscheidend

    nachr.setClass("cms_chat_nachricht_eigen", n.eigen).setClass("cms_chat_nachricht_geloescht", n.geloescht);
    if(aktionen)
      nachr.find(".cms_chat_nachricht_aktion").click(cms_chat_aktion);

    socketChat.chat.removeClass("cms_chat_leer");

    socketChat.nachrichten.scrollTop(nachgeladen ? 0 : socketChat.nachrichten.prop("scrollHeight"));
    return nachr;
  },
  nachrichtSenden: function(c) {
    do {
      cid = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    } while(socketChat.sendend.includes(cid));
    socketChat.sendend.push(cid);

    var daten = {
      "status": "1",
      "nachricht": c,
      "cid": cid
    };


    socketChat.senden(daten);

    return cid;
  },
  nachladen: function() {
    socketChat.statusSetzen("Nachrichten laden...");
    socketChat.senden({
      "status": "2",
      "lid": socketChat.nachrichten.find(".cms_chat_nachricht_id").first().text()
    });
  },
  nachrichtLoeschen: function(id) {
    cms_meldung_aus();
    socketChat.statusSetzen("Nachricht löschen...");
    socketChat.senden({
      "status": "3",
      "lid": id
    });
  },
  nutzerStummschalten: function(id) {
    cms_meldung_aus();
    socketChat.statusSetzen("Nutzer stummschalten...");
    var daten = {"status": "4", "lid": id};
    var banndauer, bannbis = null;
    bannbis = Math.floor(new Date($("#cms_bannbis_J").val(), $("#cms_bannbis_M").val()-1, $("#cms_bannbis_T").val(), $("#cms_bannbis_h").val(), $("#cms_bannbis_m").val(), 0).getTime()/1000);
    banndauer = ($("#cms_banndauer_T").val() * 24*60*60 + $("#cms_banndauer_h").val() * 60 * 60 + $("#cms_banndauer_m").val() * 60);
    if(bannbis && $("#cms_reiter_bann_0").hasClass("cms_reiter_aktiv"))
      daten["bannbis"] = bannbis;
    if(banndauer && $("#cms_reiter_bann_1").hasClass("cms_reiter_aktiv"))
      daten["banndauer"] = banndauer;
    socketChat.senden(daten);
  },
  stummschalten: function(dauer) {
    if(dauer) {
      socketChat.stummTimeout = setTimeout(function() {
        socketChat.chat.removeClass("cms_chat_stumm");
        socketChat.stummTimeout = null;
      }, dauer*1000-new Date().getTime());
    }
    if(socketChat.stummTimeout != null)
      socketChat.chat.addClass("cms_chat_stumm");
  },
  events: {
    close: function(e) {
      socketChat.neuVerbinden();
    },
    open: function(e) {
      socketChat.status = 3;
    },
    nachricht: function(e) {
      var daten = JSON.parse(e.data);
      switch(daten["status"]) {
        case "-3":
          socketChat.neuVerbinden();
          break;
        case "-2":
          $("#cms_chat").addClass("cms_chat_berechtigung");
          break;
        case "-1":
          cms_meldung_fehler();
          break;
        case "0":
          socketChat.status = 4;
          socketChat.authentifizieren();
          break;
        case "1":
          socketChat.status = 5;
          socketChat.statusSetzen("Daten laden...");
          break;
        case "2":
          socketChat.status = 6;
          socketChat.statusSetzen(null);

          socketChat.chat.setClass("cms_chat_leer", daten.leer).setClass("cms_chat_mehr", daten.mehr).setClass("cms_chat_stumm", daten.stumm);

          socketChat.rechte.loeschen = daten.loeschen;
          socketChat.rechte.stummschalten = daten.stummschalten;
          if(daten.stumm) {
            socketChat.stummschalten(daten.stumm);
          }
          socketChat.nachrichten.html("");

          $.each(daten.nachrichten, function(k, v) { socketChat.nachrichtAnzeigen(v); });

          break;
        case "3":
          if(daten["cid"]) {
            $("#cms_chat_nachricht_sendend_"+daten["cid"]).remove();
            socketChat.sendend.splice(socketChat.sendend.indexOf(daten["cid"]), 1);
          }

          socketChat.nachrichtAnzeigen(daten);
          break;
        case "4":
          socketChat.chat.removeClass("cms_chat_status cms_chat_laden");
          var lid = daten["lid"];
          socketChat.nachrichten.find("#cms_chat_nachricht_"+lid).removeClass("cms_chat_nachricht_gemeldet").addClass("cms_chat_nachricht_geloescht").find(".cms_chat_nachricht_nachricht").html(daten["inhalt"]);
          socketChat.stummschalten();
          break;
        case "5":
          if(daten.stumm)
            socketChat.stummschalten(daten.stumm)
          else
            cms_meldung_fehler();
          break;
        case "6":
          socketChat.chat.removeClass("cms_chat_status cms_chat_laden");
          socketChat.chat.setClass("cms_chat_mehr", daten.mehr);

          $.each(daten.nachrichten, function(k, v) { socketChat.nachrichtAnzeigen(v, true); });
          socketChat.stummschalten();

          break;
        case "7":
          socketChat.chat.removeClass("cms_chat_status cms_chat_laden");
          break;
        default:
          break;
      }
    }
  }
}
/* Nachricht senden */
function cms_chat_nachricht_senden(art, id) {
  if($("#cms_chat_nachricht_verfassen").hasClass("cms_chat_gebannt"))
    return;
  var chat = $("#cms_chat_nachrichten");
  var d = new Date();
  var nachricht = $("#cms_chat_neue_nachricht").val();
  $("#cms_chat_neue_nachricht").val("").focus();
  if(!nachricht)
    return;

  var cid = socketChat.nachrichtSenden(nachricht);

  var tag = $("<div></div>", {class: "cms_chat_datum cms_notiz"}).html(cms_tagnamekomplett(d.getDay())+", den "+cms_datumzweistellig(d.getDate())+" "+cms_monatsnamekomplett(d.getMonth()+1));
  if(!chat.find(".cms_chat_datum").filter(function() {return $(this).html() == tag.html()}).length)
    tag.appendTo(chat);
  chat.find("#cms_chat_leer").remove();

  var nachr = $("<div></div>", {id: "cms_chat_nachricht_sendend_"+cid, class: "cms_chat_nachricht_aussen cms_chat_nachricht_eigen cms_chat_nachricht_sendend"}).append(
    $("<div></div>", {class: "cms_chat_nachricht_innen"}).append(
      $("<div></div>", {class: "cms_chat_nachricht_id"}),
      $("<div></div>", {class: "cms_chat_nachricht_autor"}).html(CMS_BENUTZERTITEL+" "+CMS_BENUTZERVORNAME+" "+CMS_BENUTZERNACHNAME),
      $("<div></div>", {class: "cms_chat_nachricht_nachricht"}).text(nachricht),
      $("<div></div>", {class: "cms_chat_nachricht_zeit"}).html(("0"+d.getHours()).slice(-2)+":"+("0"+d.getMinutes()).slice(-2))
    )
  ).appendTo(chat);
  nachr.find(".cms_chat_nachricht_aktion").click(cms_chat_aktion);

  chat.scrollTop(chat.prop("scrollHeight"));
}
/* Aktionen zeigen */
function cms_chat_aktion() {
  if($(this).data("aktion") == "mehr") {
    var h = $(this).find(".cms_chat_aktion");
    $(this).parents("#cms_chat_nachrichten").find(".cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion").not(function() {return $(this).is(h)}).slideUp();
    h.slideToggle();
  }
}
/* Tastendruck */
function cms_chat_enter(e, art, id) {
  if(e.keyCode && e.keyCode === 10)
    if(e.ctrlKey) {
      cms_chat_nachricht_senden(art, id);
      return false;
    }
  return true;
}
/* Nachricht melden - Bestätigung */
function cms_chat_nachricht_melden_anzeigen(t, art, gid) {
  var p = $(t).parents(".cms_chat_nachricht_aussen");
  if(p.hasClass("cms_chat_nachricht_gemeldet")) {
    cms_meldung_an("warnung", "Nachricht gemeldet", "<p>Diese Nachricht wurde schon gemeldet.</p>", '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
    return;
  }
  var id = p.find(".cms_chat_nachricht_id").html();
  cms_meldung_an('warnung', 'Nachricht melden', '<p>Soll die Nachricht wirklich gemeldet werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_chat_nachricht_melden(\''+art+'\', \''+gid+'\', \''+id+'\')">Melden</span></p>');
}
/* Nachricht melden - Senden */
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
/* Nachricht löschen - Bestätigung */
function cms_chat_nachricht_loeschen_anzeigen(t) {
  var p = $(t).parents(".cms_chat_nachricht_aussen");
  if(p.hasClass("cms_chat_nachricht_geloescht")) {
    cms_meldung_an("warnung", "Nachricht gelöscht", "<p>Diese Nachricht wurde schon gelöscht.</p>", '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
    return;
  }
  var id = p.find(".cms_chat_nachricht_id").html();
  cms_meldung_an('warnung', 'Nachricht löschen', '<p>Soll die Nachricht wirklich für alle gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="socketChat.nachrichtLoeschen(\''+id+'\')">Löschen</span></p>');
}
/* Nutzer stummschalten - Bestätigung */
function cms_chat_nutzer_stummschalten_anzeigen(t) {
  var p = $(t).parents(".cms_chat_nachricht_aussen");
  var id = p.find(".cms_chat_nachricht_id").html();

  var menue = '<ul class="cms_reitermenue"><li><span id="cms_reiter_bann_0" class="cms_reiter cms_reiter_aktiv" onclick="cms_reiter(\'bann\', 0, 1)">Nach Datum stummschalten</span></li> <li><span id="cms_reiter_bann_1" class="cms_reiter" onclick="cms_reiter(\'bann\', 1, 1)">Nach Dauer stummschalten</span></li></ul>';

  var bannbis = cms_datum_eingabe("cms_bannbis") + " - " + cms_uhrzeit_eingabe("cms_bannbis", new Date().getHours()+1);
  bannbis = '<p>Bis wann soll der Nutzer stummgeschalten werden?</p>'+bannbis;
  bannbis = '<div class="cms_reitermenue_o" id="cms_reiterfenster_bann_0" style="display: block"><div class="cms_reitermenue_i">'+bannbis+'</div></div>';

  var banndauer  = '<input class="cms_input_T" type="text" name="cms_banndauer_T" id="cms_banndauer_T" onkeyup="function a(t) {if(!cms_check_ganzzahl(t.value, 0)) {t.value = 0;}$(\'#cms_banndauer_T_tage\').html(\'Tag\'+(t.value == 1?\'\':\'e\'))};a(this)" value="1"> <span id="cms_banndauer_T_tage">Tag</span>  ';
  banndauer += '<input class="cms_input_h" type="text" name="cms_banndauer_h" id="cms_banndauer_h" onkeyup="function a(t) {if(!cms_check_ganzzahl(t.value, 0)) {t.value = 0;}$(\'#cms_banndauer_h_stunden\').html(\'Stunde\'+(t.value == 1?\'\':\'n\'))};a(this)" value="1"> <span id="cms_banndauer_h_stunden">Stunde</span>  ';
  banndauer += '<input class="cms_input_m" type="text" name="cms_banndauer_m" id="cms_banndauer_m" onkeyup="function a(t) {if(!cms_check_ganzzahl(t.value, 0)) {t.value = 0;}$(\'#cms_banndauer_m_minuten\').html(\'Minute\'+(t.value == 1?\'\':\'n\'))};a(this)" value="1"> <span id="cms_banndauer_m_minuten">Minute</span>  ';
  banndauer = '<p>Wie lange soll der Nutzer stummgeschalten werden?</p>'+banndauer;
  banndauer = '<div class="cms_reitermenue_o" id="cms_reiterfenster_bann_1"><div class="cms_reitermenue_i">'+banndauer+'</div></div>';

  cms_meldung_an('warnung', 'Nutzer stummschalten', menue+bannbis+banndauer, '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="socketChat.nutzerStummschalten(\''+id+'\')">Stummschalten</span></p>');
}
