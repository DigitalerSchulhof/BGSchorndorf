function cms_neuer_blogeintrag(ziel) {
	cms_laden_an('Neuen Blogeintrag vorbereiten', 'Vorbereitungen für den neuen Blogeintrag werden getroffen.');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'71');
	formulardaten.append("ziel", 	ziel);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Website/Blogeinträge/Neuer_Blogeintrag');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_blogeintraege_bearbeiten_vorbereiten(id, ziel) {
	cms_laden_an('Blogeintrag bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
	formulardaten.append("anfragenziel", 	'74');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Blogeinträge/Blogeintrag_bearbeiten');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_blogeintraege_eingabenpruefen() {
	var meldung = '<p>Der Blogeintrag konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	var oeffentlichkeit = document.getElementById('cms_oeffentlichkeit').value;
	var notifikationen = document.getElementById('cms_blogeintrag_notifikationen').value;
	var genehmigt = document.getElementById('cms_blogeintrag_genehmigt').value;
	var aktiv = document.getElementById('cms_blogeintrag_aktiv').value;
	var datumT = document.getElementById('cms_blogeintrag_datum_T').value;
	var datumM = document.getElementById('cms_blogeintrag_datum_M').value;
	var datumJ = document.getElementById('cms_blogeintrag_datum_J').value;

	var bezeichnung = document.getElementById('cms_blogeintrag_bezeichnung').value;
	var vorschaubild = document.getElementById('cms_blogeintrag_vorschaubild').value;
	var zusammenfassung = document.getElementById('cms_blogeintrag_zusammenfassung').value;
	var autor = document.getElementById('cms_blogeintrag_autor').value;
	var downloadanzahl = document.getElementById('cms_downloads_anzahl').value;
	var downloadids = document.getElementById('cms_downloads_ids').value;

	var formulardaten = new FormData();
	for (var i=0; i<CMS_GRUPPEN.length; i++) {
		var gruppek = cms_textzudb(CMS_GRUPPEN[i]);
		var wert = document.getElementById('cms_zuorndung_zugeordnetegruppen_'+gruppek);
		formulardaten.append('z'+gruppek, wert.value);
	}

	var inhalt = document.getElementsByClassName('note-editable');
	inhalt = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if ((oeffentlichkeit != 0) && (oeffentlichkeit != 1) && (oeffentlichkeit != 2) && (oeffentlichkeit != 3) && (oeffentlichkeit != 4)) {
		meldung += '<li>Die Eingabe für die Öffentlichkeit ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(notifikationen)) {
		meldung += '<li>Die Eingabe für Notifikationen ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(genehmigt)) {
		meldung += '<li>Die Eingabe für die Genehmigung ist ungültig.</li>';
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

	if (!fehler) {
		formulardaten.append("oeffentlichkeit", oeffentlichkeit);
		formulardaten.append("notifikationen", 	notifikationen);
		formulardaten.append("genehmigt",  			genehmigt);
		formulardaten.append("aktiv",  					aktiv);
		formulardaten.append("datumT",  				datumT);
		formulardaten.append("datumM",					datumM);
		formulardaten.append("datumJ",  				datumJ);
		formulardaten.append("bezeichnung",  		bezeichnung);
		formulardaten.append("autor",  					autor);
		formulardaten.append("inhalt",  				inhalt);
		formulardaten.append("vorschaubild",  	vorschaubild);
		formulardaten.append("zusammenfassung", zusammenfassung);
		formulardaten.append("downloadanzahl",  downloadanzahl);
		formulardaten.append("downloadids", 		downloadids);
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}


function cms_blogeintraege_neu_speichern(ziel) {
	cms_laden_an('Neuen Blogeintrag anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_blogeintraege_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '72');

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

function cms_blogeintraege_bearbeiten_speichern(ziel) {
	cms_laden_an('Blogeintrag bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_blogeintraege_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '75');

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

function cms_blogeintraege_loeschen_vorbereiten(id, bezeichnung, ziel) {
	cms_meldung_an('warnung', 'Blogeintrag löschen', '<p>Soll der Blogeintrag <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_blogeintraege_loeschen(\''+id+'\',\''+ziel+'\')">Löschung durchführen</span></p>');
}


function cms_blogeintraege_loeschen(id, ziel) {
	cms_laden_an('Blogeintrag löschen', 'Der Blogeintrag wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'73');

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

function cms_blogeintraege_jahr_loeschen_vorbereiten() {
  var jahr = document.getElementById('cms_verwaltung_blogeintraege_jahr_angezeigt').value;
	cms_meldung_an('warnung', 'Blogeinträge des Jahres '+jahr+' löschen', '<p>Sollen <b>alle</b> Blogeinträge des Jahres '+jahr+' wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_blogeintraege_jahr_loeschen()">Löschung durchführen</span></p>');
}

function cms_blogeintraege_jahr_loeschen() {
  var jahr = document.getElementById('cms_verwaltung_blogeintraege_jahr_angezeigt').value;
	cms_laden_an('Blogeinträge des Jahres '+jahr+' löschen', 'Alle Blogeinträge des Jahres '+jahr+' werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("jahr", 	jahr);
	formulardaten.append("anfragenziel", 	'79');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Blogeinträge des Jahres '+jahr+' löschen', '<p>Die Blogeinträge wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Blogeinträge\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_blogeintragverwaltung(jahr, spalten, anfang, ende) {
  var feld = document.getElementById('cms_verwaltung_blogeintraege_jahr');
  feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\"><img src="res/laden/standard.gif"><br>Die Blogeinträge für das Jahr '+jahr+' werden geladen. Je nach Verbindung und Blogeintraganzahl kann dies etwas dauern.</td></tr>';

  for (var i=anfang; i<=ende; i++) {
    var toggle = document.getElementById('cms_verwaltung_blogeintraege_jahr_'+i);
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
  	formulardaten.append("anfragenziel", 	'78');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.slice(0,4) == '<tr>') {
				var toggle = document.getElementById('cms_verwaltung_blogeintraege_jahr_'+jahr);
				toggle.className = 'cms_toggle_aktiv';
				document.getElementById('cms_verwaltung_blogeintraege_jahr_angezeigt').value = jahr;
				feld.innerHTML = rueckgabe;
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}

function cms_blog_genehmigen(gruppe, id, ziel) {
	cms_laden_an('Blogeintrag genehmigen', 'Der Blogeintrag wird genehmigt.');
	var ziel = ziel || 'Schulhof/Aufgaben/Blogeinträge_genehmigen';
	var formulardaten = new FormData();
	formulardaten.append("gruppe",         gruppe);
	formulardaten.append("id",         		 id);
	formulardaten.append("anfragenziel", 	'76');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Blogeintrag genehmigen', '<p>Der Blogeintrag wurde genehmigt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_blog_ablehnen(gruppe, id, ziel) {
	cms_laden_an('Blogeintrag ablehnen', 'Der Blogeintrag wird abgelehnt.');
	var ziel = ziel || 'Schulhof/Aufgaben/Blogeinträge_genehmigen';
	var formulardaten = new FormData();
	formulardaten.append("gruppe",         gruppe);
	formulardaten.append("id",         		 id);
	formulardaten.append("anfragenziel", 	'77');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Blogeintrag ablehnen', '<p>Der Blogeintrag wurde abgelehnt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
