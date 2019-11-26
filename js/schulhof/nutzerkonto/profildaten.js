/* BENUTZERNAME WIRD GEÄNDERT */
function cms_schulhof_nutzerkonto_benutzerkonto_aendern () {
	cms_laden_an('Nutzerkonto ändern', 'Die Eingaben werden überprüft.');
	var benutzername = document.getElementById('cms_schulhof_nutzerkonto_profildaten_benutzername').value;
	var mail = document.getElementById('cms_schulhof_nutzerkonto_profildaten_email').value;

	var meldung = '<p>Das Nutzerkonto konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (benutzername.length < 6) {
		meldung += '<li>der Benutzername ist kürzer als 6 Zeichen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(mail)) {
		meldung += '<li>die eingegebene eMailadresse ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Nutzerkonto ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Nutzerkonto ändern', 'Die neuen persönlichen Daten werden verschlüsselt gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("benutzername", benutzername);
		formulardaten.append("mail", mail);
		formulardaten.append("modus", '0');
		formulardaten.append("anfragenziel", 	'65');

    function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				if (CMS_BENUTZERART != "s") {
					meldung += '<li>der Benutzername <i>'+benutzername+'</i> ist bereits vergeben. Bitte wählen Sie einen anderen aus.</li>';
				}
				else {
					meldung += '<li>der Benutzername <i>'+benutzername+'</i> ist bereits vergeben. Bitte wähle einen anderen aus.</li>';
				}
				cms_meldung_an('fehler', 'Nutzerkonto ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Nutzerkonto ändern', '<p>Das Nutzerkonto wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Mein_Profil\');">OK</span></p>');
			}
  		else {
        cms_fehlerbehandlung(rueckgabe);
      }
  	}
  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

/* LEHRERKÜRZEL WIRD GEÄNDERT */
function cms_schulhof_nutzerkonto_lehrerkuerzel_aendern () {
	cms_laden_an('Lehrerkürzel ändern', 'Die Eingaben werden überprüft.');
	var lehrerkuerzel = document.getElementById('cms_schulhof_nutzerkonto_profildaten_lehrerkuerzel').value;
	var stundenplan = document.getElementById('cms_schulhof_nutzerkonto_profildaten_stundenplan').value;

	var formulardaten = new FormData();
	formulardaten.append("lehrerkuerzel", lehrerkuerzel);
	formulardaten.append("stundenplan", stundenplan);
	formulardaten.append("modus", '0');
	formulardaten.append("anfragenziel", 	'66');

	cms_laden_an('Lehrerkürzel ändern', 'Die neuen persönlichen Daten werden verschlüsselt gespeichert.');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Lehrerkürzel ändern', '<p>Das Lehrerkürzel wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Mein_Profil\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

/* PASSWORT WIRD GEÄNDERT */
function cms_schulhof_nutzerkonto_passwort_aendern () {
	cms_laden_an('Passwort ändern', 'Die Eingaben werden überprüft.');
	var passwortalt = document.getElementById('cms_schulhof_nutzerkonto_profildaten_passwort_alt').value;
	var passwort1 = document.getElementById('cms_schulhof_nutzerkonto_profildaten_passwort').value;
	var passwort2 = document.getElementById('cms_schulhof_nutzerkonto_profildaten_passwort_wiederholen').value;

	var meldung = '<p>Das Passwort konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (passwortalt.length < 2) {
		meldung += '<li>das alte Passwort ist kürzer als 2 Zeichen.</li>';
		fehler = true;
	}
	if (passwort1.length < 2) {
		meldung += '<li>das neue Passwort ist kürzer als 2 Zeichen.</li>';
		fehler = true;
	}
	if (passwort1 != passwort2) {
		meldung += '<li>die eingegebenen Passwörter sind nicht identisch.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Passwort ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Passwort ändern', 'Das neue Passwort wird verschlüsselt gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("passwortalt", passwortalt);
		formulardaten.append("passwort1", passwort1);
		formulardaten.append("passwort2", passwort2);
		formulardaten.append("anfragenziel", 	'67');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				meldung += '<li>das eingegebene alte Passwort ist falsch.</li>';
				cms_meldung_an('fehler', 'Passwort ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG"){
				cms_meldung_an('erfolg', 'Passwort ändern', '<p>Das Passwort wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Mein_Profil\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

/* PASSWORT WIRD GEÄNDERT */
function cms_nutzerkonto_identitaetsdiebstahl() {
	cms_laden_an('Indetitäsdiebstahl melden', 'Die Eingaben werden überprüft.');
	var passwortalt = document.getElementById('cms_schulhof_nutzerkonto_profildaten_passwort_alt').value;
	var diebstahl = document.getElementById('cms_schulhof_nutzerkonto_profildaten_diebstahl').value;
	var passwort1 = document.getElementById('cms_schulhof_nutzerkonto_profildaten_passwort').value;
	var passwort2 = document.getElementById('cms_schulhof_nutzerkonto_profildaten_passwort_wiederholen').value;

	var meldung = '<p>Das Passwort konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (passwortalt.length < 2) {
		meldung += '<li>das alte Passwort ist kürzer als 2 Zeichen.</li>';
		fehler = true;
	}
	if (passwort1.length < 2) {
		meldung += '<li>das neue Passwort ist kürzer als 2 Zeichen.</li>';
		fehler = true;
	}
	if (passwort1 != passwort2) {
		meldung += '<li>die eingegebenen Passwörter sind nicht identisch.</li>';
		fehler = true;
	}
	if (diebstahl != '1') {
		meldung += '<li>es muss akzeptiert werden, dass diese Meldung einen Identitätsdiebstahl zur Anzeige bringt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Passwort ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Identitäsdiebstahl melden', 'Das neue Passwort wird verschlüsselt gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("diebstahl", diebstahl);
		formulardaten.append("passwortalt", passwortalt);
		formulardaten.append("passwort1", passwort1);
		formulardaten.append("passwort2", passwort2);
		formulardaten.append("anfragenziel", 	'231');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Identitäsdiebstahl melden', '<p>Das Passwort wurde geändert. Der Identitätsdiebstahl wurde gemeldet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		if(fehler) {
			meldung += '<li>das eingegebene alte Passwort ist falsch.</li>';
			cms_meldung_an('fehler', 'Identitäsdiebstahl melden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else {
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
		}
	}
}

function cms_identitaetsdiebstahl_loeschen_anzeigen (id, zeit, name, datum) {
	cms_meldung_an('warnung', 'Identitätsdiebstahl löschen', '<p>Sollen die Meldungen über einen Identitätsdiebstahl für <b>'+name+' vom '+datum+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_identitaetsdiebstahl_loeschen(\''+id+'\', \''+zeit+'\')">Löschung durchführen</span></p>');
}

function cms_identitaetsdiebstahl_loeschen(id, zeit) {
	cms_laden_an('Identitätsdiebstahl löschen', 'Die Meldung über den Identitätsdiebstahl wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("zeit",     	zeit);
	formulardaten.append("anfragenziel", 	'232');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Identitäsdiebstahl löschen', '<p>Die Meldung über einen Identitätsdiebstahl wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Aufgaben/Identitätsdiebstähle_behandeln\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_nutzerkonto_einstellungen_aendern() {
	cms_laden_an('Einstellungen ändern', 'Die Eingaben werden überprüft.');
	var postmail = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_postmail').value;
	var postalletage = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_alle_tage').value;
	var postpapierkorbtage = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_papierkorb_tage').value;
	var notifikationsmail = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_notifikationmail').value;
	var vertretungsmail = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_vertretungsmail').value;
	var uebersichtsanzahl = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_uebersichtsanzahl').value;
	var inaktivitaetszeit = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_inaktivitaetszeit').value;
	var terminoeffentlich = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_terminoeffentlich').value;
	var blogoeffentlich = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_blogoeffentlich').value;
	var galerieoeffentlich = document.getElementById('cms_schulhof_nutzerkonto_einstellungen_galerieoeffentlich').value;

	var meldung = '<p>Die Änderungen konnten nicht vorgenommen werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_toggle(postmail)) {
		meldung += '<li>die Eingabe für die Benachrichtigung bei neuen Nachrichten ist ungültig.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(notifikationsmail)) {
		meldung += '<li>die Eingabe für die Benachrichtigung bei Neuigkeiten ist ungültig.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(vertretungsmail)) {
		meldung += '<li>die Eingabe für die Benachrichtigung bei neuen Vertretungen ist ungültig.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(terminoeffentlich)) {
		meldung += '<li>die Eingabe für den Erhalt von Neuigkeiten bei öffentlichen Terminen ist ungültig.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(blogoeffentlich)) {
		meldung += '<li>die Eingabe für den Erhalt von Neuigkeiten bei öffentlichen Blogeinträgen ist ungültig.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(galerieoeffentlich)) {
		meldung += '<li>die Eingabe für den Erhalt von Neuigkeiten bei öffentlichen Galerien ist ungültig.</li>';
		fehler = true;
	}
	if (isNaN(postalletage)) {
		meldung += '<li>die Eingabe an Tagen zur automatischen Löschung von Nachrichten ist keine Zahl.</li>';
		fehler = true;
	}
	if (isNaN(postpapierkorbtage)) {
		meldung += '<li>die Eingabe an Tagen zur automatischen Löschung von Nachrichten im Papierkorb ist keine Zahl.</li>';
		fehler = true;
	}
	if (isNaN(uebersichtsanzahl)) {
		meldung += '<li>die Eingabe der Anzahl von Elementen in Übersichten ist keine Zahl.</li>';
		fehler = true;
	}
	if (isNaN(inaktivitaetszeit)) {
		meldung += '<li>die Eingabe der zulässigen Inaktivitätszeit ist keine Zahl.</li>';
		fehler = true;
	}
	if (!fehler) {
		if (postalletage < 1) {
			meldung += '<li>die Zahl an Tagen zur automatischen Löschung von Nachrichten ist zu klein.</li>';
			fehler = true;
		}
		if (postpapierkorbtage < 1) {
			meldung += '<li>die Zahl an Tagen zur automatischen Löschung von Nachrichten im Papierkorb ist zu klein.</li>';
			fehler = true;
		}
		if ((uebersichtsanzahl < 1) || (uebersichtsanzahl > 20)) {
			meldung += '<li>die Zahl von Elementen in Übersichten muss zwischen 1 und 20 liegen.</li>';
			fehler = true;
		}
		if (inaktivitaetszeit < 5) {
			meldung += '<li>die minimale Inaktivitätszeit beträgt 5 Minuten.</li>';
			fehler = true;
		}
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Einstellungen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Einstellungen ändern', 'Das neue Passwort wird verschlüsselt gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("postmail", 			postmail);
		formulardaten.append("postalletage", 		Math.floor(postalletage));
		formulardaten.append("postpapierkorbtage", 	Math.floor(postpapierkorbtage));
		formulardaten.append("notifikationsmail", 	notifikationsmail);
		formulardaten.append("vertretungsmail", 		vertretungsmail);
		formulardaten.append("uebersichtsanzahl", 	uebersichtsanzahl);
		formulardaten.append("inaktivitaetszeit", 	inaktivitaetszeit);
		formulardaten.append("terminoeffentlich", 	terminoeffentlich);
		formulardaten.append("blogoeffentlich", 		blogoeffentlich);
		formulardaten.append("galerieoeffentlich", 	galerieoeffentlich);
		formulardaten.append("modus", 						  0);
		formulardaten.append("anfragenziel", 	'68');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_meldung_an('erfolg', 'Einstellungen ändern', '<p>Die neuen Einstellungen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Einstellungen\');">OK</span></p>');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

// Schuljahr vergeben
function cms_schulhof_nutzerkonto_schuljahr_einstellen(schuljahr) {
	cms_laden_an('Schuljahr umstellen', 'Das aktive Schuljahr wird umgestellt.');

	var formulardaten = new FormData();
	formulardaten.append("schuljahr",  		schuljahr);
	formulardaten.append("modus", '0');
	formulardaten.append("anfragenziel", 	'69');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG"){
			cms_link('Schulhof/Nutzerkonto/Mein_Profil');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_persoenliche_notizen_speichern() {
	cms_laden_an('Notizen speichern', 'Die neuen Notizen werden gespeichert.');
	var notizen = document.getElementById('cms_persoenlichenotizen').value;
	var formulardaten = new FormData();
	formulardaten.append("notizen",  		notizen);
	formulardaten.append("anfragenziel", 	'229');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			if (notizen.length > 0) {document.getElementById('cms_persoenlichenotizen').className = "cms_notizzettel";}
			else {document.getElementById('cms_persoenlichenotizen').className = "cms_notizzettel cms_notizzettelleer";}
			cms_meldung_an('erfolg', 'Notizen speichern', '<p>Die Änderugnen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_favorisieren(url) {
	var istFavorit = $(".cms_favorisieren>img").attr("src").endsWith("favorit.png");

	cms_laden_an((istFavorit?'Entf':'F')+'avorisieren', 'Die Seite wird '+(istFavorit?'ent':'')+'favorisiert.');
	var formulardaten = new FormData();
	formulardaten.append("seite",  				url);
	formulardaten.append("status",  			!istFavorit);
	formulardaten.append("anfragenziel", 	'281');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_laden_aus();
			src = istFavorit ? "res/icons/klein/favorisieren.png" : "res/icons/klein/favorit.png";
			$(".cms_favorisieren>img").attr("src", src);
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
