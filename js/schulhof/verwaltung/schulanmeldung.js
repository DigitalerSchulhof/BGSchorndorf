function cms_schulanmeldung_einstellungen_aendern() {
  cms_laden_an('Einstellungen der Schulanmeldung ändern', 'Die Eingaben werden überprüft.');
	var aktiv = document.getElementById('cms_voranmeldung_aktiv').value;
	var vorbeginnT = document.getElementById('cms_voranmeldung_beginn_T').value;
	var vorbeginnM = document.getElementById('cms_voranmeldung_beginn_M').value;
	var vorbeginnJ = document.getElementById('cms_voranmeldung_beginn_J').value;
	var vorbeginns = document.getElementById('cms_voranmeldung_beginn_h').value;
	var vorbeginnm = document.getElementById('cms_voranmeldung_beginn_m').value;
	var vorendeT = document.getElementById('cms_voranmeldung_ende_T').value;
	var vorendeM = document.getElementById('cms_voranmeldung_ende_M').value;
	var vorendeJ = document.getElementById('cms_voranmeldung_ende_J').value;
	var vorendes = document.getElementById('cms_voranmeldung_ende_h').value;
	var vorendem = document.getElementById('cms_voranmeldung_ende_m').value;
	var perbeginnT = document.getElementById('cms_voranmeldung_persoenlich_beginn_T').value;
	var perbeginnM = document.getElementById('cms_voranmeldung_persoenlich_beginn_M').value;
	var perbeginnJ = document.getElementById('cms_voranmeldung_persoenlich_beginn_J').value;
	var perendeT = document.getElementById('cms_voranmeldung_persoenlich_ende_T').value;
	var perendeM = document.getElementById('cms_voranmeldung_persoenlich_ende_M').value;
	var perendeJ = document.getElementById('cms_voranmeldung_persoenlich_ende_J').value;
	var ueberhang = document.getElementById('cms_voranmeldung_ueberhang').value;
	var eintritt = document.getElementById('cms_voranmeldung_eintritt').value;
	var einschulung = document.getElementById('cms_voranmeldung_einschulung').value;
	var klasse = document.getElementById('cms_voranmeldung_klassenstufe').value;
  var einleitung = document.getElementsByClassName('note-editable');
	einleitung = einleitung[0].innerHTML;

	var meldung = '<p>Die Änderungen konnten nicht vorgenommen werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>die Eingabe für die Aktivität der Voranmeldung ist ungültig.</li>';
		fehler = true;
	}

  var vorbeginn = new Date(vorbeginnJ, vorbeginnM, vorbeginnT, vorbeginns, vorbeginnm, 0, 0);
	var vorende = new Date(vorendeJ, vorendeM, vorendeT, vorendes, vorendem, 59, 999);

  if (vorbeginn-vorende >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum für die Online-Anmeldung eingegeben.</li>';
		fehler = true;
	}

  var perbeginn = new Date(perbeginnJ, perbeginnM, perbeginnT, 0, 0, 0, 0);
	var perende = new Date(perendeJ, perendeM, perendeT, 23, 59, 59, 999);

  if (perbeginn-perende >= 0) {
		meldung += '<li>es wurde kein gültiger Zeitraum für die persönliche Anmeldung eingegeben.</li>';
		fehler = true;
	}

  if (!cms_check_ganzzahl(ueberhang, 1, 1000)) {
    meldung += '<li>Die Löschungsfrist entspricht nicht den Vorgaben.</li>';
		fehler = true;
  }

  if (!cms_check_ganzzahl(eintritt, 1, 100)) {
    meldung += '<li>Das Eintrittsalter entspricht nicht den Vorgaben.</li>';
		fehler = true;
  }

  if (!cms_check_ganzzahl(einschulung, 1, 100)) {
    meldung += '<li>Das Einschulungsalter entspricht nicht den Vorgaben.</li>';
		fehler = true;
  }

  if (!cms_check_ganzzahl(klasse, 1, 20)) {
    meldung += '<li>Die letzte Klassenstufe entspricht nicht den Vorgaben.</li>';
		fehler = true;
  }

	if (fehler) {
		cms_meldung_an('fehler', 'Einstellungen der Schulanmeldung ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Einstellungen der Schulanmeldung ändern', 'Die Änderungen werden übernommen');

		var formulardaten = new FormData();
		formulardaten.append("aktiv", aktiv);
		formulardaten.append("vorbeginnT", vorbeginnT);
		formulardaten.append("vorbeginnM", vorbeginnM);
		formulardaten.append("vorbeginnJ", vorbeginnJ);
		formulardaten.append("vorbeginns", vorbeginns);
		formulardaten.append("vorbeginnm", vorbeginnm);
		formulardaten.append("vorendeT", vorendeT);
		formulardaten.append("vorendeM", vorendeM);
		formulardaten.append("vorendeJ", vorendeJ);
		formulardaten.append("vorendes", vorendes);
		formulardaten.append("vorendem", vorendem);
		formulardaten.append("perbeginnT", perbeginnT);
		formulardaten.append("perbeginnM", perbeginnM);
		formulardaten.append("perbeginnJ", perbeginnJ);
		formulardaten.append("perendeT", perendeT);
		formulardaten.append("perendeM", perendeM);
		formulardaten.append("perendeJ", perendeJ);
		formulardaten.append("ueberhang", ueberhang);
		formulardaten.append("eintritt", eintritt);
		formulardaten.append("einschulung", einschulung);
		formulardaten.append("klasse", klasse);
		formulardaten.append("einleitung", einleitung);
		formulardaten.append("anfragenziel", 	'247');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Einstellungen der Schulanmeldung ändern', '<p>Die Änderungen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulanmeldung\');">Zurück zur Schulanmeldung</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_schulanmeldung_neu_speichern() {
  cms_laden_an('Schulanmeldung speichern', 'Die Eingaben werden überprüft.');

  var formulardaten = new FormData();
  var rueckgabe = cms_schulanmeldung_eingabenpruefen();
  var fehler = rueckgabe[0];
  formulardaten = rueckgabe[1];
  var meldung = rueckgabe[2];

  if (!fehler) {
    formulardaten.append("anfragenziel", '248');

    function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Verwaltung/Schulanmeldung');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Anmeldung speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_schulanmeldung_bearbeiten_vorbereiten(id) {
  cms_laden_an('Schulanmeldung bearbeiten', 'Daten werden gesammelt.');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", '249');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Verwaltung/Schulanmeldung/Anmeldung_bearbeiten');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulanmeldung_bearbeiten_speichern() {
  cms_laden_an('Schulanmeldung speichern', 'Die Eingaben werden überprüft.');

  var formulardaten = new FormData();
  var rueckgabe = cms_schulanmeldung_eingabenpruefen();
  var fehler = rueckgabe[0];
  formulardaten = rueckgabe[1];
  var meldung = rueckgabe[2];

  if (!fehler) {
    formulardaten.append("anfragenziel", '250');

    function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Verwaltung/Schulanmeldung');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Anmeldung speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_schulanmeldung_loeschen_anzeigen(name, id) {
  cms_meldung_an('warnung', 'Anmeldung löschen', '<p>Soll die Anmeldung von <b>'+name+'</b> wirklich gelöscht werden? Sie kann nach der Löschung nicht wiederhergesetllt werden.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulanmeldung_loeschen(\''+name+'\','+id+')">Löschung durchführen</span></p>');
}

function cms_schulanmeldung_loeschen(name, id) {
	cms_laden_an('Anmeldung löschen', 'Die Anmeldung von <b>'+name+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'251');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Anmeldung löschen', '<p>Die Anmeldung wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulanmeldung\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_schulanmeldung_alleloeschen_anzeigen() {
  cms_meldung_an('warnung', 'Alle Anmeldungen löschen', '<p>Sollen wirklich <b>alle Anmeldungen</b> unwiederbringlich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulanmeldung_alleloeschen()">Löschung durchführen</span></p>');
}

function cms_schulanmeldung_alleloeschen() {
	cms_laden_an('Alle Anmeldungen löschen', 'Alle Anmeldungen werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'252');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Alle Anmeldungen löschen', '<p>Alle Anmeldungen wurden gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulanmeldung\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulanmeldung_aufnehmen(name, id) {
  cms_laden_an('Schüler aufnehmen', '<b>'+name+'</b> wird an der Schule aufgenommen.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'253');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Schüler aufnehmen', '<p><b>'+name+'</b> wurde an der Schule aufgenommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulanmeldung\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulanmeldung_ablehnen(name, id) {
  cms_laden_an('Schüler ablehnen', 'Die Aufnahme von <b>'+name+'</b> wird zurückgenommen.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'254');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Schüler ablehnen', '<p>Die Aufnahme von <b>'+name+'</b> wurde zurückgenommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulanmeldung\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulanmeldung_drucken(id) {
  cms_laden_an('Schulanmeldung drucken', 'Die Druckansicht wird vorbereitet.');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", '255');

  function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Schulanmeldung drucken', '<p>Die Druckansicht wurde erstelt.</p>', '<p><a class="cms_button" target="_blank" href="drucken.php">Druckansicht öffnen</a> <span class="cms_button_nein" onclick="cms_ausblenden(\'cms_blende_o\')">Fenster schließen</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulanmeldung_exportieren(id) {
  cms_laden_an('Schulanmeldungen exportieren', 'Die Datensätze werden gesammelt.');
  var gruppe = document.getElementById('cms_voranmeldung_exportauswahl').value;
  var klasse = document.getElementById('cms_voranmeldung_klasse').value;

  var fehler = false;
  var meldung = '<p>Der Export konnte nicht abgeschlossen werden, denn ...</p><ul>';
  if ((gruppe != 'alle') && (gruppe != 'auf') && (gruppe != 'aufohne') && (gruppe != 'aufbili') && (gruppe != 'abgelehnt')) {
    fehler = true;
    meldung += '<li>die gewählte Gruppe ist ungültig.</li>';
  }
  if (!cms_check_titel(klasse)) {
    fehler = true;
    meldung += '<li>die Klassenbezeichnung ist ungültig.</li>';
  }

  if (!fehler) {
    var formulardaten = new FormData();
    formulardaten.append('id', id);
    formulardaten.append('gruppe', gruppe);
    formulardaten.append('klasse', klasse);
    formulardaten.append("anfragenziel", '256');

    function anfragennachbehandlung(rueckgabe) {
      if ((rueckgabe != "FEHLER") && (rueckgabe != 'BERECHTIGUNG')) {
        document.getElementById('cms_voranmeldung_exportergebnis').value = rueckgabe;
        cms_meldung_aus();
      }
      else {cms_fehlerbehandlung(rueckgabe);}
    }
    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
  else {
    cms_meldung_an('fehler', 'Schulanmeldungen exportieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }

}

function cms_schulanmeldung_eingabenpruefen() {
  var formulardaten = new FormData();
  var akzeptiert = document.getElementById('cms_voranemldung_aufgenommen').value;
  var nachname = document.getElementById('cms_voranmeldung_schueler_nachname').value;
	var vorname = document.getElementById('cms_voranmeldung_schueler_vorname').value;
	var rufname = document.getElementById('cms_voranmeldung_schueler_rufname').value;
	var geburtsdatumT = document.getElementById('cms_vornameldung_schueler_geburtsdatum_T').value;
	var geburtsdatumM = document.getElementById('cms_vornameldung_schueler_geburtsdatum_M').value;
	var geburtsdatumJ = document.getElementById('cms_vornameldung_schueler_geburtsdatum_J').value;
	var geburtsort = document.getElementById('cms_voranmeldung_schueler_geburtsort').value;
	var geburtsland = document.getElementById('cms_voranmeldung_schueler_geburtsland').value;
	var muttersprache = document.getElementById('cms_voranmeldung_schueler_muttersprache').value;
	var verkehrssprache = document.getElementById('cms_voranmeldung_schueler_verkehrssprache').value;
	var geschlecht = document.getElementById('cms_voranmeldung_schueler_geschlecht').value;
	var religion = document.getElementById('cms_voranmeldung_schueler_religion').value;
	var religionsunterricht = document.getElementById('cms_voranmeldung_schueler_religionsunterricht').value;
	var religionsunterricht = document.getElementById('cms_voranmeldung_schueler_religionsunterricht').value;
	var land1 = document.getElementById('cms_voranmeldung_schueler_land1').value;
	var land2 = document.getElementById('cms_voranmeldung_schueler_land2').value;
	var strasse = document.getElementById('cms_voranmeldung_schueler_strasse').value;
	var hausnummer = document.getElementById('cms_voranmeldung_schueler_hausnummer').value;
	var plz = document.getElementById('cms_voranmeldung_schueler_postleitzahl').value;
	var ort = document.getElementById('cms_voranmeldung_schueler_ort').value;
	var teilort = document.getElementById('cms_voranmeldung_schueler_teilort').value;
	var telefon1 = document.getElementById('cms_voranmeldung_schueler_telefon1').value;
	var telefon2 = document.getElementById('cms_voranmeldung_schueler_telefon2').value;
	var handy1 = document.getElementById('cms_voranmeldung_schueler_handy1').value;
	var handy2 = document.getElementById('cms_voranmeldung_schueler_handy2').value;
	var mail = document.getElementById('cms_schulhof_voranmeldung_schueler_mail').value;
	var einschulungT = document.getElementById('cms_vornameldung_schueler_einschulung_T').value;
	var einschulungM = document.getElementById('cms_vornameldung_schueler_einschulung_M').value;
	var einschulungJ = document.getElementById('cms_vornameldung_schueler_einschulung_J').value;
	var vorigeschule = document.getElementById('cms_voranmeldung_vorigeschule').value;
	var klasse = document.getElementById('cms_voranmeldung_klasse').value;
	var profil = document.getElementById('cms_voranmeldung_profil').value;
  var vorname1 = document.getElementById('cms_voranmeldung_ansprechpartner1_vorname').value;
	var nachname1 = document.getElementById('cms_voranmeldung_ansprechpartner1_nachname').value;
	var geschlecht1 = document.getElementById('cms_voranmeldung_ansprechpartner1_geschlecht').value;
	var sorgerecht1 = document.getElementById('cms_voranmeldung_ansprechpartner1_sorgerecht').value;
	var briefe1 = document.getElementById('cms_voranmeldung_ansprechpartner1_briefe').value;
	var strasse1 = document.getElementById('cms_voranmeldung_ansprechpartner1_strasse').value;
	var hausnummer1 = document.getElementById('cms_voranmeldung_ansprechpartner1_hausnummer').value;
	var plz1 = document.getElementById('cms_voranmeldung_ansprechpartner1_postleitzahl').value;
	var ort1 = document.getElementById('cms_voranmeldung_ansprechpartner1_ort').value;
	var teilort1 = document.getElementById('cms_voranmeldung_ansprechpartner1_teilort').value;
	var telefon11 = document.getElementById('cms_voranmeldung_ansprechpartner1_telefon1').value;
	var telefon21 = document.getElementById('cms_voranmeldung_ansprechpartner1_telefon2').value;
	var handy11 = document.getElementById('cms_voranmeldung_ansprechpartner1_handy1').value;
	var mail1 = document.getElementById('cms_schulhof_voranmeldung_ansprechpartner1_mail').value;
	var ansprechpartner2 = document.getElementById('cms_ansprechpartner2').value;
	var vorname2 = document.getElementById('cms_voranmeldung_ansprechpartner2_vorname').value;
	var nachname2 = document.getElementById('cms_voranmeldung_ansprechpartner2_nachname').value;
	var geschlecht2 = document.getElementById('cms_voranmeldung_ansprechpartner2_geschlecht').value;
	var sorgerecht2 = document.getElementById('cms_voranmeldung_ansprechpartner2_sorgerecht').value;
	var briefe2 = document.getElementById('cms_voranmeldung_ansprechpartner2_briefe').value;
	var strasse2 = document.getElementById('cms_voranmeldung_ansprechpartner2_strasse').value;
	var hausnummer2 = document.getElementById('cms_voranmeldung_ansprechpartner2_hausnummer').value;
	var plz2 = document.getElementById('cms_voranmeldung_ansprechpartner2_postleitzahl').value;
	var ort2 = document.getElementById('cms_voranmeldung_ansprechpartner2_ort').value;
	var teilort2 = document.getElementById('cms_voranmeldung_ansprechpartner2_teilort').value;
	var telefon12 = document.getElementById('cms_voranmeldung_ansprechpartner2_telefon1').value;
	var telefon22 = document.getElementById('cms_voranmeldung_ansprechpartner2_telefon2').value;
	var handy12 = document.getElementById('cms_voranmeldung_ansprechpartner2_handy1').value;
	var mail2 = document.getElementById('cms_schulhof_voranmeldung_ansprechpartner2_mail').value;

	var meldung = '<p>Die Schülerdaten konnten nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
	var jetzt = new Date();

	if (!cms_check_toggle(akzeptiert)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Aufnahme ist ungültig.</li>';
	}

	if (!cms_check_name(vorname)) {
		fehler = true;
		meldung += '<li>Die Eingabe für den Vornamen ist ungültig.</li>';
	}

	if (!cms_check_name(nachname)) {
		fehler = true;
		meldung += '<li>Die Eingabe für den Nachname ist ungültig.</li>';
	}

	if (!cms_check_name(rufname)) {
		fehler = true;
		meldung += '<li>Die Eingabe für den Rufnamen ist ungültig.</li>';
	}

	var geburtsdatum = new Date(geburtsdatumJ, geburtsdatumM-1, geburtsdatumT);
	if (geburtsdatum >= jetzt) {
		fehler = true;
		meldung += '<li>Das Geburtsdatum muss in der Vergangenheit liegen.</li>';
	}

	if (geburtsort.length <= 0) {
		fehler = true;
		meldung += '<li>Der Geburtsort ist ungültig.</li>';
	}

	if (geburtsland.length <= 0) {
		fehler = true;
		meldung += '<li>Das Geburtsland ist ungültig.</li>';
	}

	if (muttersprache.length <= 0) {
		fehler = true;
		meldung += '<li>Die Muttersprache ist ungültig.</li>';
	}

	if (verkehrssprache.length <= 0) {
		fehler = true;
		meldung += '<li>Die Verkehrssprache ist ungültig.</li>';
	}

	if ((geschlecht != 'm') && (geschlecht != 'w') && (geschlecht != 'd')) {
		fehler = true;
		meldung += '<li>Das Geschlecht ist ungültig.</li>';
	}

	if (religion.length <= 0) {
		fehler = true;
		meldung += '<li>Die Religionsauswahl ist ungültig.</li>';
	}

	if (religionsunterricht.length <= 0) {
		fehler = true;
		meldung += '<li>Die Auswahl für den Religionsunterricht ist ungültig.</li>';
	}

	if (land1.length <= 0) {
		fehler = true;
		meldung += '<li>Die Staatsangehörigkeit ist ungültig.</li>';
	}

	if (strasse.length <= 0) {
		fehler = true;
		meldung += '<li>Die Straße ist ungültig.</li>';
	}

	if (hausnummer.length <= 0) {
		fehler = true;
		meldung += '<li>Die Hausnummer ist ungültig.</li>';
	}

	if (plz.length <= 0) {
		fehler = true;
		meldung += '<li>Die Postleitzahl ist ungültig.</li>';
	}

	if (ort.length <= 0) {
		fehler = true;
		meldung += '<li>Der Ort ist ungültig.</li>';
	}

	if ((telefon1.length <= 0) && (telefon2.length <= 0) && (handy1.length <= 0) && (handy2.length <= 0)) {
		fehler = true;
		meldung += '<li>Es muss mindestens eine Telefonnummer angegeben werden.</li>';
	}

	if (mail.length) {
		if (!cms_check_mail(mail)) {
			fehler = true;
			meldung += '<li>Die eingegebene Mailadresse ist ungültig.</li>';
		}
	}

	var einschulung = new Date(einschulungJ, einschulungM-1, einschulungT);
	if (einschulung >= jetzt) {
		fehler = true;
		meldung += '<li>Das Einschulungsdatum muss in der Vergangenheit liegen.</li>';
	}

	if (vorigeschule.length <= 0) {
		fehler = true;
		meldung += '<li>Die vorige Schule ist ungültig.</li>';
	}

	if (klasse.length <= 0) {
		fehler = true;
		meldung += '<li>Die vorige Klasse ist ungültig.</li>';
	}

	if (profil.length <= 0) {
		fehler = true;
		meldung += '<li>Das gewünschte Profil ist ungültig.</li>';
	}

  if (!cms_check_name(vorname1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für den Vornamen des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (!cms_check_name(nachname1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für den Nachname des ersten Ansprechpartners ist ungültig.</li>';
	}

	if ((geschlecht1 != 'm') && (geschlecht1 != 'w') && (geschlecht1 != 'd')) {
		fehler = true;
		meldung += '<li>Das Geschlecht des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (!cms_check_toggle(sorgerecht1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für das Sorgerecht des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (!cms_check_toggle(briefe1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Einbeziehung des ersten Ansprechpartners in Breife ist ungültig.</li>';
	}

	if (strasse1.length <= 0) {
		fehler = true;
		meldung += '<li>Die Straße des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (hausnummer1.length <= 0) {
		fehler = true;
		meldung += '<li>Die Hausnummer des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (plz1.length <= 0) {
		fehler = true;
		meldung += '<li>Die Postleitzahl des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (ort1.length <= 0) {
		fehler = true;
		meldung += '<li>Der Ort des ersten Ansprechpartners  ist ungültig.</li>';
	}

	if ((telefon11.length <= 0) && (telefon21.length <= 0) && (handy11.length <= 0)) {
		fehler = true;
		meldung += '<li>Es muss mindestens eine Telefonnummer für den  ersten Ansprechpartner angegeben werden.</li>';
	}

	if (mail1.length) {
		if (!cms_check_mail(mail1)) {
			fehler = true;
			meldung += '<li>Die eingegebene Mailadresse des ersten Ansprechpartners ist ungültig.</li>';
		}
	}

	if (!cms_check_toggle(ansprechpartner2)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Ersetllung des zweiten Ansprechpartners ist ungültig.</li>';
	}

	if (ansprechpartner2 == '1') {
		if (!cms_check_name(vorname2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für den Vornamen des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (!cms_check_name(nachname2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für den Nachname des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if ((geschlecht2 != 'm') && (geschlecht2 != 'w') && (geschlecht2 != 'd')) {
			fehler = true;
			meldung += '<li>Das Geschlecht des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (!cms_check_toggle(sorgerecht2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für das Sorgerecht des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (!cms_check_toggle(briefe2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für die Einbeziehung des zweiten Ansprechpartners in Breife ist ungültig.</li>';
		}

		if (strasse2.length <= 0) {
			fehler = true;
			meldung += '<li>Die Straße des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (hausnummer2.length <= 0) {
			fehler = true;
			meldung += '<li>Die Hausnummer des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (plz2.length <= 0) {
			fehler = true;
			meldung += '<li>Die Postleitzahl des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (ort2.length <= 0) {
			fehler = true;
			meldung += '<li>Der Ort des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if ((telefon12.length <= 0) && (telefon22.length <= 0) && (handy12.length <= 0)) {
			fehler = true;
			meldung += '<li>Es muss mindestens eine Telefonnummer für den zweiten Ansprechpartner angegeben werden.</li>';
		}

		if (mail2.length) {
			if (!cms_check_mail(mail2)) {
				fehler = true;
				meldung += '<li>Die eingegebene Mailadresse des zweiten Ansprechpartners ist ungültig.</li>';
			}
		}
	}

	formulardaten.append("akzeptiert", akzeptiert);
	formulardaten.append("nachname", nachname);
	formulardaten.append("vorname", vorname);
	formulardaten.append("rufname", rufname);
	formulardaten.append("geburtsdatumT", geburtsdatumT);
	formulardaten.append("geburtsdatumM", geburtsdatumM);
	formulardaten.append("geburtsdatumJ", geburtsdatumJ);
	formulardaten.append("geburtsort", geburtsort);
	formulardaten.append("geburtsland", geburtsland);
	formulardaten.append("muttersprache", muttersprache);
	formulardaten.append("verkehrssprache", verkehrssprache);
	formulardaten.append("geschlecht", geschlecht);
	formulardaten.append("religion", religion);
	formulardaten.append("religionsunterricht", religionsunterricht);
	formulardaten.append("land1", land1);
	formulardaten.append("land2", land2);
	formulardaten.append("strasse", strasse);
	formulardaten.append("hausnummer", hausnummer);
	formulardaten.append("plz", plz);
	formulardaten.append("ort", ort);
	formulardaten.append("teilort", teilort);
	formulardaten.append("telefon1", telefon1);
	formulardaten.append("telefon2", telefon2);
	formulardaten.append("handy1", handy1);
	formulardaten.append("handy2", handy2);
	formulardaten.append("mail", mail);
	formulardaten.append("einschulungT", einschulungT);
	formulardaten.append("einschulungM", einschulungM);
	formulardaten.append("einschulungJ", einschulungJ);
	formulardaten.append("vorigeschule", vorigeschule);
	formulardaten.append("klasse", klasse);
	formulardaten.append("profil", profil);
  formulardaten.append("vorname1", vorname1);
  formulardaten.append("nachname1", nachname1);
  formulardaten.append("geschlecht1", geschlecht1);
  formulardaten.append("sorgerecht1", sorgerecht1);
  formulardaten.append("briefe1", briefe1);
  formulardaten.append("strasse1", strasse1);
  formulardaten.append("hausnummer1", hausnummer1);
  formulardaten.append("plz1", plz1);
  formulardaten.append("ort1", ort1);
  formulardaten.append("teilort1", teilort1);
  formulardaten.append("telefon11", telefon11);
  formulardaten.append("telefon21", telefon21);
  formulardaten.append("handy11", handy11);
  formulardaten.append("mail1", mail1);
  formulardaten.append("ansprechpartner2", ansprechpartner2);
  formulardaten.append("vorname2", vorname2);
  formulardaten.append("nachname2", nachname2);
  formulardaten.append("geschlecht2", geschlecht2);
  formulardaten.append("sorgerecht2", sorgerecht2);
  formulardaten.append("briefe2", briefe2);
  formulardaten.append("strasse2", strasse2);
  formulardaten.append("hausnummer2", hausnummer2);
  formulardaten.append("plz2", plz2);
  formulardaten.append("ort2", ort2);
  formulardaten.append("teilort2", teilort2);
  formulardaten.append("telefon12", telefon12);
  formulardaten.append("telefon22", telefon22);
  formulardaten.append("handy12", handy12);
  formulardaten.append("mail2", mail2);

  var rueckgabe = new Array();
  rueckgabe[0] = fehler;
  rueckgabe[1] = formulardaten;
  rueckgabe[2] = meldung;
  return rueckgabe;
}
