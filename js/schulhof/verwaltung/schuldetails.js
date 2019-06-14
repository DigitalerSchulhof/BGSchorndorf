/* ROLLE WIRD GESPEICHERT */
function cms_schulhof_verwaltung_adressen() {
	cms_laden_an('Adressen ändern', 'Die Eingaben werden überprüft.');
	var name = document.getElementById('cms_schulhof_adressen_schule').value;
	var namegenitiv = document.getElementById('cms_schulhof_adressen_schulegenitiv').value;
	var ort = document.getElementById('cms_schulhof_adressen_ort').value;
	var strasse = document.getElementById('cms_schulhof_adressen_strasse').value;
	var plzort = document.getElementById('cms_schulhof_adressen_plzort').value;
	var webmaster = document.getElementById('cms_schulhof_adressen_webmaster').value;
	var domain = document.getElementById('cms_schulhof_adressen_domain').value;

	var meldung = '<p>Die Adressen konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_mail(webmaster)) {
		meldung += '<li>die Mailadresse des Webmasters ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Adressen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Adressen ändern', 'Die Änderungen werden übernommen.');

		var formulardaten = new FormData();
		formulardaten.append("name",    	name);
		formulardaten.append("namegenitiv", namegenitiv);
		formulardaten.append("ort", 		ort);
		formulardaten.append("strasse", 	strasse);
		formulardaten.append("plzort", 		plzort);
		formulardaten.append("webmaster", 	webmaster);
		formulardaten.append("domain", 	domain);
		formulardaten.append("anfragenziel", 	'145');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Adressen ändern', '<p>Die Adressen wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuldetails\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schulhof_verwaltung_schulmail() {
	cms_laden_an('Mailadresse des Schulhofs ändern', 'Die Eingaben werden überprüft.');
	var absender = document.getElementById('cms_schulhof_schulmail_absender').value;
	var host = document.getElementById('cms_schulhof_schulmail_host').value;
	var benutzer = document.getElementById('cms_schulhof_schulmail_benutzer').value;
	var passwort = document.getElementById('cms_schulhof_schulmail_passwort').value;
	var smtpauth = document.getElementById('cms_schulhof_schulmail_smtpauth').value;


	var meldung = '<p>Die Mailadresse des Schulhofs konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_mail(absender)) {
		meldung += '<li>die Mailadresse des Absenders ist ungültig.</li>';
		fehler = true;
	}

	if ((smtpauth != 1) && (smtpauth != 0)) {
		meldung += '<li>die SMTP-Authentifizierung ist nicht zulässig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Mailadresse des Schulhofs ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Adressen ändern', 'Die Änderungen werden übernommen.');

		var formulardaten = new FormData();
		formulardaten.append("absender",    absender);
		formulardaten.append("host", 		host);
		formulardaten.append("benutzer", 	benutzer);
		formulardaten.append("passwort", 	passwort);
		formulardaten.append("smtpauth", 	smtpauth);
		formulardaten.append("anfragenziel", 	'146');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Mailadresse des Schulhofs ändern', '<p>Die Mailadresse des Schulhofs wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuldetails\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_schulhof_verwaltung_testmail() {
	cms_laden_an('Testmail versenden', 'Die Eingaben werden überprüft.');
	var absender = document.getElementById('cms_schulhof_schulmail_absender').value;
	var host = document.getElementById('cms_schulhof_schulmail_host').value;
	var benutzer = document.getElementById('cms_schulhof_schulmail_benutzer').value;
	var passwort = document.getElementById('cms_schulhof_schulmail_passwort').value;
	var smtpauth = document.getElementById('cms_schulhof_schulmail_smtpauth').value;


	var meldung = '<p>Die Testmail konnte nicht versendet werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_mail(absender)) {
		meldung += '<li>die Mailadresse des Absenders ist ungültig.</li>';
		fehler = true;
	}

	if ((smtpauth != 1) && (smtpauth != 0)) {
		meldung += '<li>die SMTP-Authentifizierung ist nicht zulässig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Mailadresse des Schulhofs ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Testmail senden', 'Die Nachricht wird verschickt.');

		var formulardaten = new FormData();
		formulardaten.append("absender",    absender);
		formulardaten.append("host", 		host);
		formulardaten.append("benutzer", 	benutzer);
		formulardaten.append("passwort", 	passwort);
		formulardaten.append("smtpauth", 	smtpauth);
		formulardaten.append("anfragenziel", 	'147');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Testmail senden', '<p>Die Testmail wurde verschickt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
