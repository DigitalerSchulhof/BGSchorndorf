/* ROLLE WIRD GESPEICHERT */
function cms_schuldetails_speichern() {
	cms_laden_an('Schuldetails ändern', 'Die Eingaben werden überprüft.');
	var schulename = document.getElementById('cms_details_schulname').value;
	var schulenamegenitiv = document.getElementById('cms_details_schulnamegenitiv').value;
	var schuleort = document.getElementById('cms_details_schulort').value;
	var schulestrasse = document.getElementById('cms_details_schulstrasse').value;
	var schuleplzort = document.getElementById('cms_details_schulplzort').value;
	var schuletelefon = document.getElementById('cms_details_telefon').value;
	var schulefax = document.getElementById('cms_details_telefax').value;
	var schulemail = document.getElementById('cms_details_email').value;
	var schuledomain = document.getElementById('cms_details_schuldomain').value;
	var schulleitungname = document.getElementById('cms_details_nameschulleitung').value;
	var schulleitungmail = document.getElementById('cms_details_mailschulleitung').value;
	var datenschutzname = document.getElementById('cms_details_namedatenschutz').value;
	var datenschutzmail = document.getElementById('cms_details_maildatenschutz').value;
	var pressename = document.getElementById('cms_details_namepresse').value;
	var pressemail = document.getElementById('cms_details_mailpresse').value;
	var webmastername = document.getElementById('cms_details_namewebmaster').value;
	var webmastermail = document.getElementById('cms_details_mailwebmaster').value;
	var administratorname = document.getElementById('cms_details_nameadmin').value;
	var administratormail = document.getElementById('cms_details_mailadmin').value;

	var meldung = '<p>Die Schuldetails konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (schulename.length < 3) {
		meldung += '<li>der Schulname ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (schulenamegenitiv.length < 3) {
		meldung += '<li>der Genitiv des Schulnamens ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (schuleort.length < 3) {
		meldung += '<li>der Schulort ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (schulestrasse.length < 3) {
		meldung += '<li>die Eingabe für Straße und Hausnummer der Schule ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (schuleplzort.length < 3) {
		meldung += '<li>die Eingabe für Postleitzahl und Ort der Schule ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (schuletelefon.length < 3) {
		meldung += '<li>die Schultelefonnummer ist ungültig. Sie muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (schulefax.length < 3) {
		meldung += '<li>die Schulfaxnummer ist ungültig. Sie muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(schulemail)) {
		meldung += '<li>die Schulmailadresse ist ungültig.</li>';
		fehler = true;
	}
	if (schuledomain.length < 3) {
		meldung += '<li>die Schuldomain ist ungültig.</li>';
		fehler = true;
	}
	if (schulleitungname.length < 3) {
		meldung += '<li>der Name der Schulleitung ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(schulleitungmail)) {
		meldung += '<li>die Mailadresse der Schulleitung ist ungültig.</li>';
		fehler = true;
	}
	if (datenschutzname.length < 3) {
		meldung += '<li>der Name des Datenschutzbeauftragten ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(datenschutzmail)) {
		meldung += '<li>die Mailadresse der Schulleitung ist ungültig.</li>';
		fehler = true;
	}
	if (pressename.length < 3) {
		meldung += '<li>der Name des Verantwortlichen im Sinne des Presserechts ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(pressemail)) {
		meldung += '<li>die Mailadresse der Verantwortlichen im Sinne des Presserechts ist ungültig.</li>';
		fehler = true;
	}
	if (webmastername.length < 3) {
		meldung += '<li>der Name des Webmasters ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(webmastermail)) {
		meldung += '<li>die Mailadresse des Webmasters ist ungültig.</li>';
		fehler = true;
	}
	if (administratorname.length < 3) {
		meldung += '<li>der Name des Administrators ist ungültig. Er muss aus mindestens drei Zeichen bestehen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(administratormail)) {
		meldung += '<li>die Mailadresse des Administrators ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Adressen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schuldetails ändern', 'Die Änderungen werden übernommen.');

		var formulardaten = new FormData();
		var schulename = document.getElementById('cms_details_schulname').value;
		var schulenamegenitiv = document.getElementById('cms_details_schulnamegenitiv').value;
		var schuleort = document.getElementById('cms_details_schulort').value;
		var schulestrasse = document.getElementById('cms_details_schulstrasse').value;
		var schuleplzort = document.getElementById('cms_details_schulplzort').value;
		var schuletelefon = document.getElementById('cms_details_telefon').value;
		var schulefax = document.getElementById('cms_details_telefax').value;
		var schulemail = document.getElementById('cms_details_email').value;
		var schuledomain = document.getElementById('cms_details_schuldomain').value;
		var schulleitungname = document.getElementById('cms_details_nameschulleitung').value;
		var schulleitungmail = document.getElementById('cms_details_mailschulleitung').value;
		var datenschutzname = document.getElementById('cms_details_namedatenschutz').value;
		var datenschutzmail = document.getElementById('cms_details_maildatenschutz').value;
		var pressename = document.getElementById('cms_details_namepresse').value;
		var pressemail = document.getElementById('cms_details_mailpresse').value;
		var webmastername = document.getElementById('cms_details_namewebmaster').value;
		var webmastermail = document.getElementById('cms_details_mailwebmaster').value;
		var administratorname = document.getElementById('cms_details_nameadmin').value;
		var administratormail = document.getElementById('cms_details_mailadmin').value;
		formulardaten.append("schulename",    		schulename);
		formulardaten.append("schulenamegenitiv", schulenamegenitiv);
		formulardaten.append("schuleort", 				schuleort);
		formulardaten.append("schulestrasse", 		schulestrasse);
		formulardaten.append("schuleplzort", 			schuleplzort);
		formulardaten.append("schuletelefon", 		schuletelefon);
		formulardaten.append("schulefax", 				schulefax);
		formulardaten.append("schulemail",    		schulemail);
		formulardaten.append("schuledomain", 			schuledomain);
		formulardaten.append("schulleitungname",	schulleitungname);
		formulardaten.append("schulleitungmail", 	schulleitungmail);
		formulardaten.append("datenschutzname", 	datenschutzname);
		formulardaten.append("datenschutzmail", 	datenschutzmail);
		formulardaten.append("pressename", 				pressename);
		formulardaten.append("pressemail",    		pressemail);
		formulardaten.append("webmastername", 		webmastername);
		formulardaten.append("webmastermail", 		webmastermail);
		formulardaten.append("administratorname", administratorname);
		formulardaten.append("administratormail", administratormail);
		formulardaten.append("anfragenziel", 	'145');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Schuldetails ändern', '<p>Die Schuldetails wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schuldetails\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_schulmailer_speichern() {
	cms_laden_an('Mailadresse des Schulhofs ändern', 'Die Eingaben werden überprüft.');
	var absender = document.getElementById('cms_mailer_absender').value;
	var host = document.getElementById('cms_mailer_smtphost').value;
	var benutzer = document.getElementById('cms_mailer_benutzer').value;
	var passwort = document.getElementById('cms_mailer_passwort').value;
	var smtpauth = document.getElementById('cms_mailer_authentifizierung').value;
	var signaturtext = document.getElementById('cms_mailer_signatur_text').value;
	var signaturhtml = document.getElementsByClassName('note-editable');
	signaturhtml = signaturhtml[0].innerHTML;

	var meldung = '<p>Die eMailadresse des Schulhofs konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_mail(absender)) {
		meldung += '<li>die eMailadresse des Absenders ist ungültig.</li>';
		fehler = true;
	}

	if (host.length < 3) {
		meldung += '<li>der eingegebene SMTP-Host ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(smtpauth)) {
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
		formulardaten.append("signaturtext", 	signaturtext);
		formulardaten.append("signaturhtml", 	signaturhtml);
		formulardaten.append("anfragenziel", 	'146');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Mailadresse des Schulhofs ändern', '<p>Die eMailadresse des Schulhofs wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulhofmailer\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_schulhof_verwaltung_testmail() {
	cms_laden_an('Testmail versenden', 'Die Eingaben werden überprüft.');
	var absender = document.getElementById('cms_mailer_absender').value;
	var host = document.getElementById('cms_mailer_smtphost').value;
	var benutzer = document.getElementById('cms_mailer_benutzer').value;
	var passwort = document.getElementById('cms_mailer_passwort').value;
	var smtpauth = document.getElementById('cms_mailer_authentifizierung').value;


	var meldung = '<p>Die Testmail konnte nicht versendet werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_mail(absender)) {
		meldung += '<li>die eMailadresse des Absenders ist ungültig.</li>';
		fehler = true;
	}

	if (host.length < 3) {
		meldung += '<li>der eingegebene SMTP-Host ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(smtpauth)) {
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

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
