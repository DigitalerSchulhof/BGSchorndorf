// Funktion, die einen Benutzer anmeldet
function cms_anmelden () {
	cms_laden_an('Anmelden', 'Die eingegebenen Daten werden überprüft, die Anmeldung wird durchgeführt.');
	var benutzername = document.getElementById('cms_schulhof_anmeldung_bentuzer').value;
	var passwort = document.getElementById('cms_schulhof_anmeldung_passwort').value;

	var meldung = '<p>Die Anmeldung wurde abgebrochen, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (benutzername.length < 6) {
		meldung += '<li>der Benutzername ist zu kurz.</li>';
		fehler = true;
	}
	if (passwort.length < 2) {
		meldung += '<li>das Passwort ist zu kurz.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Anmelden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("benutzername", 	benutzername);
		formulardaten.append("passwort", 			passwort);
		formulardaten.append("anfragenziel", 	'45');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				meldung += '<li>entweder wurde der Benutzername nicht gefunden</li>';
				meldung += '<li>oder das Passwort passt nicht zum Benutzernamen</li>';
				meldung += '<li>oder das Passwort ist nicht mehr gültig.</li>';
				cms_meldung_an('fehler', 'Anmelden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				if(location.pathname.includes("Schulhof/Anmeldung"))
					cms_link('Schulhof/Nutzerkonto');
				else
					location.href = location.pathname;
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

// Funktion, die einen Benutzer fragt, ob er abgemeldet werden will
function cms_abmelden_frage () {
	var meldung;
	if (CMS_BENUTZERART == "s") {meldung = '<p>Willst Du dich wirklich abmelden?</p>';}
	else {meldung = '<p>Wollen Sie sich wirklich abmelden?</p>';}

	if (((CMS_BENUTZERART == 'l') || (CMS_BENUTZERART == 'v'))) {
		if (CMS_IMLN) {
			meldung += '<p><b>Bitte melden Sie sich nach der Abmeldung vom Schulhof auch aus gesicherten Netzen ab!</b></p>';
		}
	}

	cms_meldung_an('warnung', 'Wirklich abmelden?', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span> <span class="cms_button_nein" onclick="cms_abmelden();">Abmelden</span></p>');
}

// Funktion, die einen Benutzer abmeldet
function cms_abmelden(art) {
	art = art || 'gewollt';
	var meldung;
	if (CMS_BENUTZERART == "s") {meldung = '<p>Du wirst abgemeldet...</p>';}
	else {meldung = '<p>Sie werden abgemeldet...</p>';}
	cms_laden_an('Abmelden', meldung);

	// Die Person wird abgemeldet
		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'47');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				if (art == 'auto') {
					clearInterval(CMS_BEARBEITUNGSART);
					cms_link('Schulhof/Anmeldung/Automatische_Abmeldung');
				}
				else {
					clearInterval(CMS_BEARBEITUNGSART);
					cms_link('Schulhof/Anmeldung/Bis_bald!');
				}
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

// Aktivitätsanzeige anpassen
function cms_timeout_aktualisieren () {
  // Breite für die Anzeige der Leiste berechnen
  var jetzt = new Date();
  jetzt = jetzt.getTime()/1000;
  var uebrig = (CMS_SESSIONTIMEOUT - jetzt)/60;

  var aktiv_text = document.getElementById('cms_aktivitaet_text');
  var aktiv_innen = document.getElementById('cms_aktivitaet_in');

  var aktiv_text_mobil = document.getElementById('cms_maktivitaet_text');
  var aktiv_innen_mobil = document.getElementById('cms_maktivitaet_in');

  var aktiv_text_profil = document.getElementById('cms_aktivitaet_text_profil');
  var aktiv_innen_profil = document.getElementById('cms_aktivitaet_in_profil');

  // Breite berechnen und anwenden
  var prozent = uebrig/CMS_SESSIONAKTIVITAET*100;
  if (aktiv_innen) {aktiv_innen.style.width = prozent+"%";}
  if (aktiv_innen_mobil) {aktiv_innen_mobil.style.width = prozent+"%";}
  if (aktiv_innen_profil) {aktiv_innen_profil.style.width = prozent+"%";}

  // Texte ausgeben
  var ende = new Date(CMS_SESSIONTIMEOUT*1000);
  var uhrzeit_ende;
  if (ende.getHours() < 10) {uhrzeit_ende = "0"+ende.getHours()+":";}
  else {uhrzeit_ende = ende.getHours()+":";}

  if (ende.getMinutes() < 10) {uhrzeit_ende += "0"+ende.getMinutes();}
  else {uhrzeit_ende += ende.getMinutes();}

	if (uebrig < 1)
		var text = "aktiv bis "+uhrzeit_ende+" Uhr - die letzte Minute läuft";
	else if (uebrig < 2)
		var text = "aktiv bis "+uhrzeit_ende+" Uhr - noch "+(Math.floor(uebrig))+" Minute";
	else if (uebrig < 60)
		var text = "aktiv bis "+uhrzeit_ende+" Uhr - noch "+(Math.floor(uebrig))+" Minuten";
	else {
		var h = uebrig / 60;
		var m = uebrig % 60;
		var text = "aktiv bis "+uhrzeit_ende+" Uhr - noch "+(Math.floor(h))+" Stunde";
		if(h >= 2)
			text += "n";
		if(m >= 1)
			text += " und "+(Math.floor(m))+" Minute";
		if(m >= 2)
			text += "n";
	}

	if (aktiv_text) {aktiv_text.innerHTML = text;}
	if (aktiv_text_mobil) {aktiv_text_mobil.innerHTML = text;}
	if (aktiv_text_profil) {aktiv_text_profil.innerHTML = text;}

	if (uebrig <= 0) {
		cms_abmelden('auto');
	}
	else if (uebrig < 1) {
		// Neue Daten laden
		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'295');
		function anfragennachbehandlung(rueckgabe) {
			if (cms_check_ganzzahl(rueckgabe,0)) {
				CMS_SESSIONTIMEOUT = rueckgabe;
				uebrig = (CMS_SESSIONTIMEOUT - jetzt)/60;

				if (uebrig < 1) {
					if (CMS_BENUTZERART == "s") {meldung = '<p>Die letzte Minute läuft. Du wirst aus Sicherheitsgründen bald abgemeldet! Verlängere jetzt deine Aktivitätszeit!</p>';}
					else {meldung = '<p>Die letzte Minute läuft. Sie werden aus Sicherheitsgründen bald abgemeldet! Verlängern Sie jetzt Ihre Aktivitätszeit!</p>';}
					cms_meldung_an('warnung', 'Die Zeit läuft ab!', meldung, '<p><span class="cms_button_ja" onclick="cms_timeout_verlaengern();">Verlängern</span>');
				}
				else {
					prozent = uebrig/CMS_SESSIONAKTIVITAET*100;
				  if (aktiv_innen) {aktiv_innen.style.width = prozent+"%";}
				  if (aktiv_innen_mobil) {aktiv_innen_mobil.style.width = prozent+"%";}
				  if (aktiv_innen_profil) {aktiv_innen_profil.style.width = prozent+"%";}

					if (uebrig < 1)
						var text = "aktiv bis "+uhrzeit_ende+" Uhr - die letzte Minute läuft";
					else if (uebrig < 2)
						var text = "aktiv bis "+uhrzeit_ende+" Uhr - noch "+(Math.floor(uebrig))+" Minute";
					else if (uebrig < 60)
						var text = "aktiv bis "+uhrzeit_ende+" Uhr - noch "+(Math.floor(uebrig))+" Minuten";
					else {
						var h = uebrig / 60;
						var m = uebrig % 60;
						var text = "aktiv bis "+uhrzeit_ende+" Uhr - noch "+(Math.floor(h))+" Stunde";
						if(h >= 2)
							text += "n";
						if(m >= 1)
							text += " und "+(Math.floor(m))+" Minute";
						if(m >= 2)
							text += "n";
					}

					if (aktiv_text) {aktiv_text.innerHTML = text;}
					if (aktiv_text_mobil) {aktiv_text_mobil.innerHTML = text;}
					if (aktiv_text_profil) {aktiv_text_profil.innerHTML = text;}
				}
			}
			else if (rueckgabe == "BERECHTIGUNG") {
				cms_abmelden('auto');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

// Aktivitätszeit verlängern
function cms_timeout_verlaengern() {
	cms_laden_an('Aktivitätszeit verlängern', 'Die Sitzung wird verlängert.');

		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'48');

		function anfragennachbehandlung(rueckgabe) {
			if (Number.isInteger(Number(rueckgabe))) {
					CMS_SESSIONTIMEOUT = rueckgabe;
					cms_timeout_aktualisieren();
					cms_meldung_an('erfolg', 'Aktivitätszeit verlängern', '<p class="cms">Die Verlängerung wurde durchgeführt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span>');
			}
			else if (rueckgabe.length == 0) {
				cms_abmelden('auto');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



// Funktion, die einem Benutzer ein neues Passwort zuschickt, wenn er sein Passwort vergessen hat
function cms_passwort_vergessen () {
	cms_laden_an('Neues Passwort zuschicken', 'Die eingegebenen Daten werden überprüft.');
	var benutzername = document.getElementById('cms_schulhof_anmeldung_passwortvergessen_bentuzer').value;
	var mail = document.getElementById('cms_schulhof_anmeldung_passwortvergessen_mail').value;

	var meldung = '<p>Es wurde kein neues Passwort verschickt, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (benutzername.length < 6) {
		meldung += '<li>der Benutzername ist zu kurz.</li>';
		fehler = true;
	}
	if (!cms_check_mail(mail)) {
		meldung += '<li>es wurde keine gültige eMailadresse eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neues Passwort zuschicken', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("benutzername", 	benutzername);
		formulardaten.append("mail", 			mail);
		formulardaten.append("anfragenziel", 	'49');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				meldung += '<li>entweder wurde der Benutzername nicht gefunden</li>';
				meldung += '<li>oder die eMailadresse passt nicht zum Benutzernamen.</li>';
				cms_meldung_an('fehler', 'Neues Passwort zuschicken', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_link('Schulhof/Anmeldung/Zugeschickt!');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
