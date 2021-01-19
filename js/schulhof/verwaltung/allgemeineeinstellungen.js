function cms_einstellungen_anfragennachbehandlung(rueckgabe) {
	if (rueckgabe == "ERFOLG") {
		cms_meldung_an('erfolg', 'Einstellungen ändern', '<p>Die Änderungen wurden gespeichert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück zur Übersicht</span></p>');
	}
	else {cms_fehlerbehandlung(rueckgabe);}
}

function cms_einstellungen_rechte_aendern() {
	cms_laden_an('Rechte-Einstellungen ändern', 'Die Eingaben werden überprüft.');

	var rekpruefen = function(e, pfad, rechte) {
		if(!e.length)
			return;
		e.each(function() {
			e = $(this);
			if(e.is(".cms_recht_rolle"))
				rechte.push((pfad+"."+e.data("knoten")+(e.is(".cms_hat_kinder")?".*":"")).substr(2));
			else
				if(e.is(".cms_hat_kinder"))
					rekpruefen(e.find(">.cms_rechtekinder>.cms_rechtebox>.cms_recht"), pfad+"."+e.data("knoten"), rechte);
		});
	};

	var rechtelehrer = [];
	var rechteschueler = [];
	var rechteverwaltung = [];
	var rechteeltern = [];
	var rechteexterne = [];

	rekpruefen($("#cms_rechtepapa_lehrer>.cms_recht"), "", rechtelehrer);
	rekpruefen($("#cms_rechtepapa_schueler>.cms_recht"), "", rechteschueler);
	rekpruefen($("#cms_rechtepapa_verwaltung>.cms_recht"), "", rechteverwaltung);
	rekpruefen($("#cms_rechtepapa_eltern>.cms_recht"), "", rechteeltern);
	rekpruefen($("#cms_rechtepapa_externe>.cms_recht"), "", rechteexterne);

	var formulardaten = new FormData();
	cms_laden_an('Rechte-Einstellungen ändern', 'Die Änderungen werden übernommen.');
	formulardaten.append("rechtelehrer", 			rechtelehrer);
	formulardaten.append("rechteschueler", 		rechteschueler);
	formulardaten.append("rechteverwaltung", 	rechteverwaltung);
	formulardaten.append("rechteeltern", 			rechteeltern);
	formulardaten.append("rechteexterne", 		rechteexterne);
	formulardaten.append("anfragenziel", 			'230');
	cms_ajaxanfrage (formulardaten, cms_einstellungen_anfragennachbehandlung);
}


function cms_einstellungen_gruppen_aendern() {
	cms_laden_an('Gruppen-Einstellungen ändern', 'Die Eingaben werden überprüft.');

	var gruppen = ['gremien','fachschaften','klassen','kurse','stufen','arbeitsgemeinschaften','arbeitskreise','fahrten','wettbewerbe','ereignisse','sonstigegruppen'];
	var optionen = ['mitglieder','aufsicht'];
	var personen = ['lehrer', 'eltern', 'schueler', 'verwaltungsangestellte', 'externe'];
	var fehler = false;
	var formulardaten = new FormData();
	var wert = "";

	for (var o=0; o<optionen.length; o++) {
		for (var p=0; p<personen.length; p++) {
			for (var g=0; g<gruppen.length; g++) {
				wert = document.getElementById('cms_gruppen_'+optionen[o]+'_'+gruppen[g]+'_'+personen[p]).value;
				if (!cms_check_toggle(wert)) {fehler = true;}
				else {
					formulardaten.append(optionen[o]+personen[p]+gruppen[g], wert);
				}
			}
		}
	}

	for (var g=0; g<gruppen.length; g++) {
		wert = document.getElementById('cms_gruppen_genehmigung_'+gruppen[g]+'_termine').value;
		if (!cms_check_toggle(wert)) {fehler = true;}
		else {
			formulardaten.append('genehmigung'+gruppen[g]+'termine', wert);
		}
		wert = document.getElementById('cms_gruppen_genehmigung_'+gruppen[g]+'_blogeintraege').value;
		if (!cms_check_toggle(wert)) {fehler = true;}
		else {
			formulardaten.append('genehmigung'+gruppen[g]+'blogeintraege', wert);
		}
	}

	var download = document.getElementById('cms_sichtbardownload').value;
	if (!cms_check_toggle(download)) {fehler = true;}
	else {
		formulardaten.append('sichtbardownload', download);
	}

	var nachrichtloeschen = document.getElementById('cms_nachrichtloeschen').value;
	if (!cms_check_ganzzahl(nachrichtloeschen, 0, 365)) {fehler = true;}
	else {
		formulardaten.append('nachrichtloeschen', nachrichtloeschen);
	}

	var objekte = ['termine','blog'];

	if (fehler) {
		cms_meldung_an('fehler', 'Gruppen-Einstellungen ändern', '<p>Die Gruppen-Einstellungen konnten nicht geändert werden, da die Eingaben ungültig sind.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Gruppen-Einstellungen ändern', 'Die Änderungen werden übernommen.');
		formulardaten.append("anfragenziel", 	'225');
		cms_ajaxanfrage (formulardaten, cms_einstellungen_anfragennachbehandlung);
	}
}

function cms_einstellungen_stundenplaene_aendern() {
	cms_laden_an('Stundenplan-Einstellungen ändern', 'Die Eingaben werden überprüft.');
	var vplanextern = document.getElementById('cms_vertretungsplan_extern').value;
	var vplanpersoenlich = document.getElementById('cms_vertretungsplan_persoenlich').value;
	var vplanschueleraktuell = document.getElementById('cms_vertretungsplan_schueler_aktuell').value;
	var vplanschuelerfolgetag = document.getElementById('cms_vertretungsplan_schueler_folgetag').value;
	var vplanlehreraktuell = document.getElementById('cms_vertretungsplan_lehrer_aktuell').value;
	var vplanlehrerfolgetag = document.getElementById('cms_vertretungsplan_lehrer_folgetag').value;
	var vplanskennung = document.getElementById('cms_schulhof_intern_svplankennung').value;
	var vplanlkennung = document.getElementById('cms_schulhof_intern_lvplankennung').value;
	var lehrerstundenplaene = document.getElementById('cms_lehrerstundenplaene').value;
	var klassenstundenplaene = document.getElementById('cms_klassenstundenplaene').value;
	var raumstundenplaene = document.getElementById('cms_raumstundenplaene').value;
	var buchungsbeginnS = document.getElementById('cms_buchungsbeginn_h').value;
	var buchungsbeginnM = document.getElementById('cms_buchungsbeginn_m').value;
	var buchungsendeS = document.getElementById('cms_buchungsende_h').value;
	var buchungsendeM = document.getElementById('cms_buchungsende_m').value;

	var meldung = '<p>Die Stundenplan-Einstellungen konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_toggle(vplanextern)) {
		meldung += '<li>Entweder wird der Untis-Vertretungsplan verwendet, oder nicht.</li>';
		fehler = true;
	}

	if ((vplanpersoenlich != 'Klassen') && (vplanpersoenlich != 'Kursen')) {
		meldung += '<li>Die Auswahl für die Ausgabe des persönlichen Vertretungsplans ist ungültig.</li>';
		fehler = true;
	}

	if (vplanextern == 1) {
		if ((vplanschueleraktuell.length == 0) || (vplanschuelerfolgetag.length == 0) || (vplanlehreraktuell.length == 0) || (vplanlehrerfolgetag.length == 0)) {
			meldung += '<li>Es müssen für den aktuellen und den Folgetag Dateien für den Vertretungsplan sowohl für Schüler, als auch für Lehrer ausgewählt werden.</li>';
			fehler = true;
		}
	}

	if (!cms_check_titel(vplanskennung)) {
		meldung += '<li>die Vertretungsplankennung für den Schülervertretungsplan enthält ungültige Zeichen.</li>';
		fehler = true;
	}

	if (!cms_check_titel(vplanlkennung)) {
		meldung += '<li>die Vertretungsplankennung für den Lehrervertretungsplan enthält ungültige Zeichen.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(lehrerstundenplaene)) {
		meldung += '<li>Lehrerstundenpläne können etweder extern eingestellt werden, oder nicht.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(klassenstundenplaene)) {
		meldung += '<li>Lehrerstundenpläne können etweder extern eingestellt werden, oder nicht.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(klassenstundenplaene)) {
		meldung += '<li>Lehrerstundenpläne können etweder extern eingestellt werden, oder nicht.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(buchungsbeginnS,0,23)) {
		meldung += '<li>Die Stundenangabe des Buchungsbeginns ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(buchungsbeginnM,0,59)) {
		meldung += '<li>Die Minutenangabe des Buchungsbeginns ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(buchungsendeS,0,23)) {
		meldung += '<li>Die Stundenangabe des Buchungsendes ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(buchungsendeM,0,59)) {
		meldung += '<li>Die Minutenangabe des Buchungsendes ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Stundenplan-Einstellungen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Stundenplan-Einstellungen ändern', 'Die Änderungen werden übernommen.');
		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'226');
		formulardaten.append("vplanextern", 								vplanextern);
		formulardaten.append("vplanschueleraktuell", 				vplanschueleraktuell);
		formulardaten.append("vplanschuelerfolgetag", 			vplanschuelerfolgetag);
		formulardaten.append("vplanlehreraktuell", 					vplanlehreraktuell);
		formulardaten.append("vplanlehrerfolgetag", 				vplanlehrerfolgetag);
		formulardaten.append("vplanskennung", 							vplanskennung);
		formulardaten.append("vplanlkennung", 							vplanlkennung);
		formulardaten.append("lehrerstundenplaene", 				lehrerstundenplaene);
		formulardaten.append("klassenstundenplaene", 				klassenstundenplaene);
		formulardaten.append("raumstundenplaene", 					raumstundenplaene);
		formulardaten.append("buchungsbeginnS", 						buchungsbeginnS);
		formulardaten.append("buchungsbeginnM", 						buchungsbeginnM);
		formulardaten.append("buchungsendeS", 							buchungsendeS);
		formulardaten.append("buchungsendeM", 							buchungsendeM);
		cms_ajaxanfrage (formulardaten, cms_einstellungen_anfragennachbehandlung);
	}
}


function cms_einstellungen_tagebuch_aendern() {
	cms_laden_an('Tagebuch-Einstellungen ändern', 'Die Eingaben werden überprüft.');
	var abwesend = document.getElementById('cms_schulhof_tagebuch_abwesend_frist').value;
	var inhalt = document.getElementById('cms_schulhof_tagebuch_inhalt_frist').value;
	var lobtadel = document.getElementById('cms_schulhof_tagebuch_lobtadel_frist').value;
	var hausaufgaben = document.getElementById('cms_schulhof_tagebuch_hausaufgaben_frist').value;
	var entschuldigungen = document.getElementById('cms_schulhof_tagebuch_entschuldigungen_frist').value;
	var abwesenheitsmin = document.getElementById('cms_schulhof_tagebuch_abwesenheitsminimum').value;

	var meldung = '<p>Die Tagebuch-Einstellungen konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!abwesend.match(/^([-st1234567]|14)$/)) {
		meldung += '<li>Die Frist für die Abwesenheiten ist ungültig.</li>';
		fehler = true;
	}

	if (!inhalt.match(/^([-st1234567]|14)$/)) {
		meldung += '<li>Die Frist für die inhaltlichen Einträge ist ungültig.</li>';
		fehler = true;
	}

	if (!lobtadel.match(/^([-st1234567]|14)$/)) {
		meldung += '<li>Die Frist für Lob und Tadel ist ungültig.</li>';
		fehler = true;
	}

	if (!hausaufgaben.match(/^([-st1234567]|14)$/)) {
		meldung += '<li>Die Frist für die Eintragung von Hausaufgaben ist ungültig.</li>';
		fehler = true;
	}

	if (!entschuldigungen.match(/^([-st1234567]|14)$/)) {
		meldung += '<li>Die Frist für die Entschuldigungen ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(abwesenheitsmin,0)) {
		meldung += '<li>Die Mindestabwesenheit für die Entschuldigungspflicht ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Tagebuch-Einstellungen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Tagebuch-Einstellungen ändern', 'Die Änderungen werden übernommen.');
		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'153');
		formulardaten.append("abwesenheit", 					abwesend);
		formulardaten.append("inhalt", 								inhalt);
		formulardaten.append("lobtadel", 							lobtadel);
		formulardaten.append("hausaufgaben", 					hausaufgaben);
		formulardaten.append("entschuldigungen", 			entschuldigungen);
		formulardaten.append("mindestabwesenheit", 		abwesenheitsmin);
		cms_ajaxanfrage (formulardaten, cms_einstellungen_anfragennachbehandlung);
	}
}


function cms_einstellungen_website_aendern() {
	cms_laden_an('Website-Einstellungen ändern', 'Die Eingaben werden überprüft.');
	var darkmode = document.getElementById('cms_darkmodeverfuegbar').value;
	var menueseitenweiterleiten = document.getElementById('cms_menueseitenweiterleiten').value;
	var fehlermeldungaktiv = document.getElementById('cms_fehlermeldungenaktiv').value;
	var fehlermeldungangemeldet = document.getElementById('cms_fehlermeldungenangemeldet').value;
	var feedbackaktiv = document.getElementById('cms_feedbackaktiv').value;
	var feedbackangemeldet = document.getElementById('cms_feedbackangemeldet').value;

	var gruppen = ['termine','blog','galerien'];
	var personen = ['lehrer', 'eltern', 'schueler', 'verwaltungsangestellte', 'externe'];
	var fehler = false;
	var formulardaten = new FormData();
	var wert = "";

	var meldung = '<p>Die Website-Einstellungen konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (fehler) {
		meldung += '<li>Die Eingaben für die öffentlichen Termine, Blogeinträge und Galerien sind ungültig.</li>';
	}

	if (!cms_check_toggle(darkmode)) {
		meldung += '<li>Der Darkmode kann entweder zur Vefügung stehen, oder nicht.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(menueseitenweiterleiten)) {
		meldung += '<li>Menüs werden entweder weitergleitet, oder nicht.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(fehlermeldungaktiv)) {
		meldung += '<li>Fehlermeldungen sind entweder aktiv, oder nicht.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(fehlermeldungangemeldet)) {
		meldung += '<li>Eine Anmeldung für eine Fehlermeldung ist entweder notwendig, oder nicht.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(feedbackaktiv)) {
		meldung += '<li>Feedback ist entweder aktiv, oder nicht.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(feedbackangemeldet)) {
		meldung += '<li>Eine Anmeldung für Feedback ist entweder notwendig, oder nicht.</li>';
		fehler = true;
	}


	if (fehler) {
		cms_meldung_an('fehler', 'Website-Einstellungen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Website-Einstellungen ändern', 'Die Änderungen werden übernommen.');
		formulardaten.append("anfragenziel", 	'227');
		formulardaten.append("darkmode", 									darkmode);
		formulardaten.append("menueseitenweiterleiten", 	menueseitenweiterleiten);
		formulardaten.append("fehlermeldungaktiv", 				fehlermeldungaktiv);
		formulardaten.append("fehlermeldungangemeldet", 	fehlermeldungangemeldet);
		formulardaten.append("feedbackaktiv", 						feedbackaktiv);
		formulardaten.append("feedbackangemeldet", 				feedbackangemeldet);
		cms_ajaxanfrage (formulardaten, cms_einstellungen_anfragennachbehandlung);
	}
}

function cms_einstellungen_geraeteverwaltung_aendern() {
	cms_laden_an('Geräteverwaltung-Einstellungen ändern', 'Die Eingaben werden überprüft.');
	var extexistiert1 = document.getElementById('cms_externegeraete1_existiert').value;
	var extgeschlecht1 = document.getElementById('cms_externegeraete1_geschlecht').value;
	var exttitel1 = document.getElementById('cms_externegeraete1_titel').value;
	var extvorname1 = document.getElementById('cms_externegeraete1_vorname').value;
	var extnachname1 = document.getElementById('cms_externegeraete1_nachname').value;
	var extmail1 = document.getElementById('cms_schulhof_externegeraete1_mail').value;
	var extexistiert2 = document.getElementById('cms_externegeraete2_existiert').value;
	var extgeschlecht2 = document.getElementById('cms_externegeraete2_geschlecht').value;
	var exttitel2 = document.getElementById('cms_externegeraete2_titel').value;
	var extvorname2 = document.getElementById('cms_externegeraete2_vorname').value;
	var extnachname2 = document.getElementById('cms_externegeraete2_nachname').value;
	var extmail2 = document.getElementById('cms_schulhof_externegeraete2_mail').value;

	var meldung = '<p>Die Geräteverwaltung-Einstellungen konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_toggle(extexistiert1)) {
		meldung += '<li>der erste Ansprechpartner kann entweder existieren, oder nicht.</li>';
		fehler = true;
	}

	if (extexistiert1 == 1) {
		if ((extgeschlecht1 != 'm') && (extgeschlecht1 != 'w') && (extgeschlecht1 != 'u')) {
			meldung += '<li>das Geschlecht des ersten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_nametitel(exttitel1)) {
			meldung += '<li>der Titel des ersten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_name(extvorname1)) {
			meldung += '<li>der Vorname des ersten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_name(extnachname1)) {
			meldung += '<li>der Vorname des ersten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_mail(extmail1)) {
			meldung += '<li>die eMailadresse des ersten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}
	}

	if (!cms_check_toggle(extexistiert2)) {
		meldung += '<li>der zweite Ansprechpartner kann entweder existieren, oder nicht.</li>';
		fehler = true;
	}

	if (extexistiert2 == 1) {
		if ((extgeschlecht2 != 'm') && (extgeschlecht2 != 'w') && (extgeschlecht2 != 'u')) {
			meldung += '<li>das Geschlecht des zweiten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_nametitel(exttitel2)) {
			meldung += '<li>der Titel des zweiten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_name(extvorname2)) {
			meldung += '<li>der Vorname des zweiten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_name(extnachname2)) {
			meldung += '<li>der Vorname des zweiten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}

		if (!cms_check_mail(extmail2)) {
			meldung += '<li>die eMailadresse des zwieten Ansprechpartners ist ungültig.</li>';
			fehler = true;
		}
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Geräteverwaltung-Einstellungen ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Geräteverwaltung-Einstellungen ändern', 'Die Änderungen werden übernommen.');
		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'228');
		formulardaten.append("extexistiert1",    	extexistiert1);
		formulardaten.append("extgeschlecht1", 		extgeschlecht1);
		formulardaten.append("exttitel1", 				exttitel1);
		formulardaten.append("extvorname1", 			extvorname1);
		formulardaten.append("extnachname1", 			extnachname1);
		formulardaten.append("extmail1", 					extmail1);
		formulardaten.append("extexistiert2",    	extexistiert2);
		formulardaten.append("extgeschlecht2", 		extgeschlecht2);
		formulardaten.append("exttitel2", 				exttitel2);
		formulardaten.append("extvorname2", 			extvorname2);
		formulardaten.append("extnachname2", 			extnachname2);
		formulardaten.append("extmail2", 					extmail2);
		cms_ajaxanfrage (formulardaten, cms_einstellungen_anfragennachbehandlung);
	}
}

function cms_zufaelligeZahl(von, bis) {
	return (Math.floor(Math.random() * (bis - von)) + von);
}

function cms_zufaelliges_zeichen() {
	var verfuegbar = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	return verfuegbar.charAt(cms_zufaelligeZahl(0, verfuegbar.length));
}


function cms_kennung_generieren(id) {
	var feld = document.getElementById(id);
	var kennung = "";
	for (var i = 1; i < 150; i++) {
		kennung += cms_zufaelliges_zeichen();
	}
	feld.value = kennung;
}


function cms_vertretungsplan_einstellungen_anzeigen() {
	var extern = document.getElementById('cms_vertretungsplan_extern').value;
	if (extern == '1') {
		document.getElementById('cms_vertretungsplan_schueler_aktuell_F').style.display = 'table-row';
		document.getElementById('cms_vertretungsplan_schueler_folgetag_F').style.display = 'table-row';
		document.getElementById('cms_vertretungsplan_lehrer_aktuell_F').style.display = 'table-row';
		document.getElementById('cms_vertretungsplan_lehrer_folgetag_F').style.display = 'table-row';
	}
	else {
		document.getElementById('cms_vertretungsplan_schueler_aktuell_F').style.display = 'none';
		document.getElementById('cms_vertretungsplan_schueler_folgetag_F').style.display = 'none';
		document.getElementById('cms_vertretungsplan_lehrer_aktuell_F').style.display = 'none';
		document.getElementById('cms_vertretungsplan_lehrer_folgetag_F').style.display = 'none';
	}
}

function cms_allgemeineeinstellungen_externegeraeteverwaltung_anzeigen(nr) {
	var extern = document.getElementById('cms_externegeraete'+nr+'_existiert').value;
	if (extern == '1') {
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_geschlechtF').style.display = 'table-row';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_titelF').style.display = 'table-row';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_vornameF').style.display = 'table-row';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_nachnameF').style.display = 'table-row';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_mailF').style.display = 'table-row';
	}
	else {
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_geschlechtF').style.display = 'none';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_titelF').style.display = 'none';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_vornameF').style.display = 'none';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_nachnameF').style.display = 'none';
		document.getElementById('cms_allgemeineeinstellungen_externegeraeteverwaltung'+nr+'_mailF').style.display = 'none';
	}
}
