// Funktion, die einen Benutzer anmeldet
function cms_anmelden () {
	cms_laden_an(s("schulhof.seite.anmeldung.anmeldung.laden.anmelden.kopf"), s("schulhof.seite.anmeldung.anmeldung.laden.anmelden.inhalt"));
	var benutzername = document.getElementById('cms_schulhof_anmeldung_bentuzer').value;
	var passwort = document.getElementById('cms_schulhof_anmeldung_passwort').value;

	var meldung = '<p>'+s("schulhof.seite.anmeldung.anmeldung.fehler.meldung")+'</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (benutzername.length < 6) {
		meldung += '<li>'+s("schulhof.seite.anmeldung.anmeldung.fehler.benutzername")+'</li>';
		fehler = true;
	}
	if (passwort.length < 2) {
		meldung += '<li>'+s("schulhof.seite.anmeldung.anmeldung.fehler.passwort")+'</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', s("schulhof.seite.anmeldung.anmeldung.meldung.fehler.kopf"), meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">'+s("schulhof.seite.anmeldung.anmeldung.meldung.fehler.aktion.zurueck")+'</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("benutzername", 	benutzername);
		formulardaten.append("passwort", 			passwort);
		formulardaten.append("anfragenziel", 	'45');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				s("schulhof.seite.anmeldung.anmeldung.rueckgabe.fehler").forEach(function(v) {
					meldung += '<li>'+v+'</li>';
				})
				cms_meldung_an('fehler', s("schulhof.seite.anmeldung.anmeldung.meldung.fehler.kopf"), meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">'+s("schulhof.seite.anmeldung.anmeldung.meldung.fehler.aktion.zurueck")+'</span></p>');
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
	if (CMS_BENUTZERART == "s") {meldung = s("schulhof.seite.nutzerkonto.abmeldung.meldung.bestaetigung.inhalt.du");}
	else {meldung = s("schulhof.seite.nutzerkonto.abmeldung.meldung.bestaetigung.inhalt.sie");}

	if (((CMS_BENUTZERART == 'l') || (CMS_BENUTZERART == 'v'))) {
		if (CMS_IMLN) {
			meldung += s("schulhof.seite.nutzerkonto.abmeldung.meldung.bestaetigung.inhalt.ln");
		}
	}

	cms_meldung_an('warnung', s("schulhof.seite.nutzerkonto.abmeldung.meldung.bestaetigung.kopf"), meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">'+s("schulhof.seite.nutzerkonto.abmeldung.meldung.bestaetigung.aktion.zurueck")+'</span> <span class="cms_button_nein" onclick="cms_abmelden();">'+s("schulhof.seite.nutzerkonto.abmeldung.meldung.bestaetigung.aktion.abmelden")+'</span></p>');
}

// Funktion, die einen Benutzer abmeldet
function cms_abmelden(art) {
	art = art || 'gewollt';
	var meldung;
	if (CMS_BENUTZERART == "s")
		meldung = s("schulhof.seite.nutzerkonto.abmeldung.laden.abmeldung.inhalt.du");
	else
		meldung = s("schulhof.seite.nutzerkonto.abmeldung.laden.abmeldung.inhalt.sie");

	cms_laden_an(s("schulhof.seite.nutzerkonto.abmeldung.laden.abmeldung.kopf"), meldung);

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

	var zeit = {"%ende%": uhrzeit_ende, "%minuten%": Math.floor(uebrig % 60), "%stunden%": Math.floor(uebrig / 60)};

	var text = s("schulhof.seite.nutzerkonto.timeout.ende", zeit);

	if(uebrig < 1)
		text += s("schulhof.seite.nutzerkonto.timeout.minuten.letzte", zeit);
	else if(uebrig < 2)
		text += s("schulhof.seite.nutzerkonto.timeout.minuten.eine", zeit);
	else if(uebrig < 60)
		text += s("schulhof.seite.nutzerkonto.timeout.minuten.default", zeit);
	else {
		var h = uebrig / 60;
		var m = uebrig % 60;
		if(Math.floor(h) == 1)
			text += s("schulhof.seite.nutzerkonto.timeout.stunden.basis.eine", zeit);
		else
			text += s("schulhof.seite.nutzerkonto.timeout.stunden.basis.default", zeit);
		if(Math.floor(m) == 1)
			text += s("schulhof.seite.nutzerkonto.timeout.stunden.minuten.eine", zeit);
		else if(m > 1)
			text += s("schulhof.seite.nutzerkonto.timeout.stunden.minuten.default", zeit);
	}

	if (aktiv_text) {aktiv_text.innerHTML = text;}
	if (aktiv_text_mobil) {aktiv_text_mobil.innerHTML = text;}
	if (aktiv_text_profil) {aktiv_text_profil.innerHTML = text;}

	if (uebrig <= 0) {
		cms_abmelden('auto');
	}
	else if (uebrig < 1) {
		if (CMS_BENUTZERART == "s")
			meldung = s("schulhof.seite.nutzerkonto.meldung.timeout.inhalt.du");
		else
			meldung = s("schulhof.seite.nutzerkonto.meldung.timeout.inhalt.sie");
		cms_meldung_an('warnung', s("schulhof.seite.nutzerkonto.meldung.timeout.kopf"), meldung, '<p><span class="cms_button_ja" onclick="cms_timeout_verlaengern();">'+s("schulhof.seite.nutzerkonto.meldung.timeout.aktion.verlaengern")+'</span>');
	}
}

// Aktivitätszeit verlängern
function cms_timeout_verlaengern() {
	cms_laden_an(s("schulhof.seite.nutzerkonto.laden.verlaengern.kopf"), s("schulhof.seite.nutzerkonto.laden.verlaengern.inhalt"));
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'48');

	function anfragennachbehandlung(rueckgabe) {
		if (Number.isInteger(Number(rueckgabe))) {
				CMS_SESSIONTIMEOUT = rueckgabe;
				cms_timeout_aktualisieren();
				cms_meldung_an('erfolg', s("schulhof.seite.nutzerkonto.meldung.verlaengern.kopf"), '<p class="cms">'+s("schulhof.seite.nutzerkonto.meldung.verlaengern.inhalt")+'</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">'+s("schulhof.seite.nutzerkonto.meldung.verlaengern.aktion.ok")+'</span>');
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

	var meldung = '<p>'+s("schulhof.seite.anmeldung.vergessen.fehler.meldung")+'</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (benutzername.length < 6) {
		meldung += '<li>'+s("schulhof.seite.anmeldung.vergessen.fehler.benutzername")+'</li>';
		fehler = true;
	}
	if (!cms_check_mail(mail)) {
		meldung += '<li>'+s("schulhof.seite.anmeldung.vergessen.fehler.mail")+'</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', s("schulhof.seite.anmeldung.vergessen.meldung.fehler.kopf"), meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">'+s("schulhof.seite.anmeldung.vergessen.meldung.fehler.aktion.zurueck")+'</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("benutzername", 	benutzername);
		formulardaten.append("mail", 			mail);
		formulardaten.append("anfragenziel", 	'49');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				s("schulhof.seite.anmeldung.vergessen.rueckgabe.fehler").forEach(function(v) {
					meldung += '<li>'+v+'</li>';
				})
				cms_meldung_an('fehler', s("schulhof.seite.anmeldung.vergessen.meldung.fehler.kopf"), meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">'+s("schulhof.seite.anmeldung.vergessen.meldung.fehler.aktion.zurueck")+'</span></p>');
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
