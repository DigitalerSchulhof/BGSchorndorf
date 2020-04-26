function cms_voranmeldung_beginnen() {
	cms_laden_an('Voranmeldung beginnen', 'Voraussetzungen werden überprüft');

	var verbindlichkeit = document.getElementById('cms_voranmeldung_verbindlichkeit').value;
	var gleichbehandlung = document.getElementById('cms_voranmeldung_gleichbehandlung').value;
	var datenschutz = document.getElementById('cms_voranmeldung_datenschutz').value;
	var cookies = document.getElementById('cms_voranmeldung_cookies').value;
	var meldung = '<p>Mit der Voranmeldung konnte nicht begonnen werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_toggle(verbindlichkeit)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Verbindlichkeit ist ungültig.</li>';
	}
	if (verbindlichkeit != '1') {
		fehler = true;
		meldung += '<li>Sie müssen bestätigen, dass die Voranmeldung unverbindlich ist.</li>';
	}

	if (!cms_check_toggle(gleichbehandlung)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Gleichbehandlung ist ungültig.</li>';
	}
	if (gleichbehandlung != '1') {
		fehler = true;
		meldung += '<li>Sie müssen bestätigen, dass die Gleichbehandlung mit oder ohne Voranmeldung gewahrt bleibt.</li>';
	}

	if (!cms_check_toggle(datenschutz)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Datenschutzhinweise ist ungültig.</li>';
	}
	if (datenschutz != '1') {
		fehler = true;
		meldung += '<li>Sie müssen bestätigen, dass Sie mit den Datenschutzhinweisen einverstanden sind.</li>';
	}

	if (!cms_check_toggle(cookies)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Cookies ist ungültig.</li>';
	}
	if (cookies != '1') {
		fehler = true;
		meldung += '<li>Sie müssen bestätigen, dass Sie mit der Verwendung von Cookies einverstanden sind.</li>';
	}

	var formulardaten = new FormData();
	formulardaten.append("verbindlichkeit", verbindlichkeit);
	formulardaten.append("gleichbehandlung", gleichbehandlung);
	formulardaten.append("datenschutz", datenschutz);
	formulardaten.append("cookies", cookies);
	formulardaten.append("anfragenziel", '242');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link('Website/Voranmeldung/Schülerdaten');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Voranmeldung beginnen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_schuelerdaten_speichern() {
	cms_laden_an('Schülerdaten speichern', 'Eingaben werden überprüft');

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
	var impfung = document.getElementById('cms_voranmeldung_schueler_impfung').value;
	var strasse = document.getElementById('cms_voranmeldung_schueler_strasse').value;
	var hausnummer = document.getElementById('cms_voranmeldung_schueler_hausnummer').value;
	var plz = document.getElementById('cms_voranmeldung_schueler_postleitzahl').value;
	var ort = document.getElementById('cms_voranmeldung_schueler_ort').value;
	var staat = document.getElementById('cms_voranmeldung_schueler_staat').value;
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

	var meldung = '<p>Die Schülerdaten konnten nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
	var jetzt = new Date();

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

	if (!cms_check_toggle(impfung)) {
		fehler = true;
		meldung += '<li>Die Eingabe zur Masernimpfung ist ungültig.</li>';
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

	if (staat.length <= 0) {
		fehler = true;
		meldung += '<li>Der Staat des Wohnorts ist ungültig.</li>';
	}

	if ((telefon1.length <= 0) && (telefon2.length <= 0) && (handy1.length <= 0) && (handy2.length <= 0)) {
		fehler = true;
		meldung += '<li>Es muss mindestens eine Telefonnummer angegeben werden.</li>';
	}

	if (mail.length) {
		if (!cms_check_mail(mail)) {
			fehler = true;
			meldung += '<li>Die eingegebene eMailadresse ist ungültig.</li>';
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

	if (!fehler) {
		var formulardaten = new FormData();
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
		formulardaten.append("impfung", impfung);
		formulardaten.append("strasse", strasse);
		formulardaten.append("hausnummer", hausnummer);
		formulardaten.append("plz", plz);
		formulardaten.append("ort", ort);
		formulardaten.append("staat", staat);
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
		formulardaten.append("anfragenziel", '243');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link('Website/Voranmeldung/Ansprechpartner');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Schülerdaten speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_ansprechpartnerdaten_speichern() {
	cms_laden_an('Ansprechpartner speichern', 'Eingaben werden überprüft');

	var vorname1 = document.getElementById('cms_voranmeldung_ansprechpartner1_vorname').value;
	var nachname1 = document.getElementById('cms_voranmeldung_ansprechpartner1_nachname').value;
	var geschlecht1 = document.getElementById('cms_voranmeldung_ansprechpartner1_geschlecht').value;
	var sorgerecht1 = document.getElementById('cms_voranmeldung_ansprechpartner1_sorgerecht').value;
	var briefe1 = document.getElementById('cms_voranmeldung_ansprechpartner1_briefe').value;
	var haupt1 = document.getElementById('cms_voranmeldung_ansprechpartner1_haupt').value;
	var rolle1 = document.getElementById('cms_voranmeldung_ansprechpartner1_rolle').value;
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
	var haupt2 = document.getElementById('cms_voranmeldung_ansprechpartner2_haupt').value;
	var rolle2 = document.getElementById('cms_voranmeldung_ansprechpartner2_rolle').value;
	var strasse2 = document.getElementById('cms_voranmeldung_ansprechpartner2_strasse').value;
	var hausnummer2 = document.getElementById('cms_voranmeldung_ansprechpartner2_hausnummer').value;
	var plz2 = document.getElementById('cms_voranmeldung_ansprechpartner2_postleitzahl').value;
	var ort2 = document.getElementById('cms_voranmeldung_ansprechpartner2_ort').value;
	var teilort2 = document.getElementById('cms_voranmeldung_ansprechpartner2_teilort').value;
	var telefon12 = document.getElementById('cms_voranmeldung_ansprechpartner2_telefon1').value;
	var telefon22 = document.getElementById('cms_voranmeldung_ansprechpartner2_telefon2').value;
	var handy12 = document.getElementById('cms_voranmeldung_ansprechpartner2_handy1').value;
	var mail2 = document.getElementById('cms_schulhof_voranmeldung_ansprechpartner2_mail').value;

	var meldung = '<p>Die Ansprechpartner konnten nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
	var jetzt = new Date();

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

	if ((rolle1 != 'Mu') && (rolle1 != 'Va') && (rolle1 != 'Pf')) {
		fehler = true;
		meldung += '<li>Die Eingabe für der Rolle des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (!cms_check_toggle(sorgerecht1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für das Sorgerecht des ersten Ansprechpartners ist ungültig.</li>';
	}

	if (!cms_check_toggle(briefe1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für die Einbeziehung des ersten Ansprechpartners in Breife ist ungültig.</li>';
	}

	if (!cms_check_toggle(haupt1)) {
		fehler = true;
		meldung += '<li>Die Eingabe für den Hauptansprechpartner beim ersten Ansprechpartner ist ungültig.</li>';
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
			meldung += '<li>Die eingegebene eMailadresse des ersten Ansprechpartners ist ungültig.</li>';
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

		if ((rolle2 != 'Mu') && (rolle2 != 'Va') && (rolle2 != 'Pf')) {
			fehler = true;
			meldung += '<li>Die Eingabe für der Rolle des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (!cms_check_toggle(sorgerecht2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für das Sorgerecht des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (!cms_check_toggle(briefe2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für die Einbeziehung des zweiten Ansprechpartners in Breife ist ungültig.</li>';
		}

		if (!cms_check_toggle(haupt2)) {
			fehler = true;
			meldung += '<li>Die Eingabe für den Hauptansprechpartner beim zweiten Ansprechpartner ist ungültig.</li>';
		}

		if (strasse2.length <= 0) {
			fehler = true;
			meldung += '<li>Die Straße des zweiten Ansprechpartners ist ungültig.</li>';
		}

		if (hausnummer2.length <= 0) {
			fehler = true;
			meldung += '<li>Die Hausnummer des ersten Ansprechpartners ist ungültig.</li>';
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
				meldung += '<li>Die eingegebene eMailadresse des zweiten Ansprechpartners ist ungültig.</li>';
			}
		}
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("vorname1", vorname1);
		formulardaten.append("nachname1", nachname1);
		formulardaten.append("geschlecht1", geschlecht1);
		formulardaten.append("sorgerecht1", sorgerecht1);
		formulardaten.append("briefe1", briefe1);
		formulardaten.append("haupt1", haupt1);
		formulardaten.append("rolle1", rolle1);
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
		formulardaten.append("haupt2", haupt2);
		formulardaten.append("rolle2", rolle2);
		formulardaten.append("strasse2", strasse2);
		formulardaten.append("hausnummer2", hausnummer2);
		formulardaten.append("plz2", plz2);
		formulardaten.append("ort2", ort2);
		formulardaten.append("teilort2", teilort2);
		formulardaten.append("telefon12", telefon12);
		formulardaten.append("telefon22", telefon22);
		formulardaten.append("handy12", handy12);
		formulardaten.append("mail2", mail2);
		formulardaten.append("anfragenziel", '244');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link('Website/Voranmeldung/Zusammenfassung');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Ansprechpartner speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}


function cms_voranmeldung_abbrechen_anzeigen() {
	cms_meldung_an('warnung', 'Voranmeldung abbrechen', '<p>Sind Sie sicher, dass Sie die Voranmeldung abbrechen möchten?</p><p>Wenn Sie fortfahren, werden alle bisher eingegeben Daten gelöscht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_voranmeldung_abbrechen()">Voranmeldung abrechenn</span></p>');
}


function cms_voranmeldung_abbrechen() {
	cms_laden_an('Voranmeldung abbrechen', 'Daten werden gelöscht ...');

	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", '245');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Voranmeldung abbrechen', '<p>Die Daten wurden gelöscht</p>', '<p><span class="cms_button" onclick="cms_link(\'\');">Zurück zur Website</span> <span class="cms_button" onclick="cms_link(\'Website/Voranmeldung\');">Zurück zur Voranmeldung</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_voranmeldung_speichern() {
	cms_laden_an('Voranmeldung abschließen', 'Eingaben werden überprüft');

	var korrekt = document.getElementById('cms_korrekt').value;
	var code = document.getElementById('cms_spamverhinderung').value;
	var uid = $("img.cms_spamschutz").data("uuid");
	var meldung = '<p>Die Ansprechpartner konnten nicht gespeichert werden, denn ...</p><ul>';
	var fehler = false;
	var jetzt = new Date();

	if (korrekt != '1') {
		fehler = true;
		meldung += '<li>Sie müssen versichern, dass die Angaben korrekt und gewissenhaft gemacht wurden.</li>';
	}

	if (code.length <= 0) {
		fehler = true;
		meldung += '<li>Die Sicherheitsabfrage wurde nicht eingegeben.</li>';
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("code", 		code);
		formulardaten.append("korrekt", korrekt);
		formulardaten.append("uid", 		uid);
		formulardaten.append("anfragenziel", '246');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_link('Website/Voranmeldung/Fertig');}
			else if (rueckgabe == "CODE") {
				cms_meldung_an('fehler', 'Voranmeldung abschließen', '<p>Der Sicherheitscode ist nicht korrekt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Voranmeldung/Zusammenfassung\');">Korrigieren</span></p>');
			}
			else if (rueckgabe == "ZEITRAUM") {
				cms_meldung_an('fehler', 'Voranmeldung abschließen', '<p>Der Zeitraum für die Online Voranmeldung ist abgelaufen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Voranmeldung/Zusammenfassung\');">Korrigieren</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Voranmeldung abschließen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}
