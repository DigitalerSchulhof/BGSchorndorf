function cms_raeume_eingabencheck() {
	var bezeichnung = document.getElementById('cms_raeume_bezeichnung').value;
	var buchbar = document.getElementById('cms_raeume_buchbar').value;
	var verfuegbar = document.getElementById('cms_raeume_verfuegbar').value;
	var extern = document.getElementById('cms_raeume_extern').value;
	var stundenplan = document.getElementById('cms_raeume_stundenplan').value;
	var geraeteanzahl = document.getElementById('cms_ausstattung_geraete_anzahl').value;
	var geraeteids = document.getElementById('cms_ausstattung_geraete_ids').value;
	var blockierunganzahl = document.getElementById('cms_blockierungen_anzahl').value;
	var blockierungids = document.getElementById('cms_blockierungen_ids').value;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(verfuegbar)) {
		meldung += '<li>Räume sind entweder verfügbar oder nicht.</li>';
		fehler = true;
	}
	if (!cms_check_toggle(buchbar)) {
		meldung += '<li>Räume sind entweder buchbar oder nicht.</li>';
		fehler = true;
	}

	// Prüfen, ob Geräte dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var gtitelf = false;
	var gfehler = false;
	if (geraeteanzahl > 0) {
		ids = geraeteids.split('|');
		for (i=1; i<ids.length; i++) {
			var gid = ids[i];

			var gbezeichnung = document.getElementById('cms_ausstattung_geraete_bezeichnung_'+gid);
			var gidid = document.getElementById('cms_ausstattung_geraete_id_'+gid);

			if (gbezeichnung) {
				if ((gbezeichnung.value).length > 0) {formulardaten.append("gbezeichnung_"+gid,  gbezeichnung.value);}
				else {gtitelf = true;}
			}
			else {gfehler = true;}
			if (gidid) {
				if ((gidid.value).length > 0) {formulardaten.append("gid_"+gid,  gidid.value);}
				else {gfehler = true;}
			}
			else {gfehler = true;}
		}
	}

	if (gfehler) {
		meldung += '<li>bei den Geräten ist ein unbekannter Fehler aufgetreten. Bitte den Administrator über den Link in der Fußzeile informieren.</li>';
		fehler = true;
	}
	if (gtitelf) {
		meldung += '<li>bei mindestens einem Gerät ist der Titel nicht angegeben oder nicht eindeutig.</li>';
		fehler = true;
	}

	// Prüfen, ob Blockierungen dabei sind und wenn ja, deren Rechtmäßigkeit prüfen
	var bwochentagf = false;
	var bzeitf = false;
	var bgrundf = false;
	var bferienf = false;
	var bfehler = false;
	if (blockierunganzahl > 0) {
		ids = blockierungids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var bwochentag = document.getElementById('cms_blockierungen_wochentag_'+bid);
			var bidid = document.getElementById('cms_blockierungen_id_'+bid);
			var bbeginns = document.getElementById('cms_blockierungen_beginn_'+bid+'_h');
			var bbeginnm = document.getElementById('cms_blockierungen_beginn_'+bid+'_m');
			var bendes = document.getElementById('cms_blockierungen_ende_'+bid+'_h');
			var bendem = document.getElementById('cms_blockierungen_ende_'+bid+'_m');
			var bgrund = document.getElementById('cms_blockierungen_grund_'+bid);
			var bferien = document.getElementById('cms_blockierungen_ferien_'+bid);

			if (bwochentag) {
				if (cms_check_ganzzahl(bwochentag.value, 1,7)) {formulardaten.append("bwochentag_"+bid,  bwochentag.value);}
				else {bwochentagf = true;}
			}
			else {bfehler = true;}

			if ((bbeginns) && (bbeginnm) && (bendes) && (bendem)) {
				if (cms_check_ganzzahl(bbeginns.value, 0,23) && cms_check_ganzzahl(bbeginnm.value, 0,59) && cms_check_ganzzahl(bendes.value, 0,23) && cms_check_ganzzahl(bendem.value, 0,59)) {
					if ((bendes.value > bbeginns.value) || ((bendes.value == bbeginns.value) && (bendem.value > bbeginnm.value))) {
						formulardaten.append("bbeginns_"+bid,  bbeginns.value);
						formulardaten.append("bbeginnm_"+bid,  bbeginnm.value);
						formulardaten.append("bendes_"+bid,  bendes.value);
						formulardaten.append("bendem_"+bid,  bendem.value);
					}
					else {bzeitf = true;}
				}
				else {bzeitf = true;}
			}
			else {bfehler = true;}

			if (bgrund) {
				if ((bgrund.value).length > 0) {formulardaten.append("bgrund_"+bid,  bgrund.value);}
				else {bgrundf = true;}
			}
			else {bfehler = true;}

			if (bferien) {
				if (cms_check_toggle(bferien.value)) {formulardaten.append("bferien_"+bid,  bferien.value);}
				else {bferienf = true;}
			}
			else {bfehler = true;}

			if (bidid) {
				if ((bidid.value).length > 0) {formulardaten.append("bid_"+bid,  bidid.value);}
				else {bfehler = true;}
			}
			else {bfehler = true;}
		}
	}

	if (bfehler) {
		meldung += '<li>bei den Blockierungen ist ein unbekannter Fehler aufgetreten. Bitte den Administrator über den Link in der Fußzeile informieren.</li>';
		fehler = true;
	}
	if (bwochentagf) {
		meldung += '<li>bei mindestens einer Blockierung ist der Wochentag ungültig.</li>';
		fehler = true;
	}
	if (bzeitf) {
		meldung += '<li>bei mindestens einer Blockierung sind die Zeiträume ungültig.</li>';
		fehler = true;
	}
	if (bgrundf) {
		meldung += '<li>bei mindestens einer Blockierung ist der Grund ungültig.</li>';
		fehler = true;
	}
	if (bferienf) {
		meldung += '<li>bei mindestens einer Blockierung ist die Eingabe bezüglich der Blockierung in den Ferien ungültig.</li>';
		fehler = true;
	}

	formulardaten.append("bezeichnung", bezeichnung);
	formulardaten.append("buchbar", 	buchbar);
	formulardaten.append("verfuegbar", 	verfuegbar);
	formulardaten.append("extern", extern);
	formulardaten.append("stundenplan", stundenplan);
	formulardaten.append("geraeteanzahl", geraeteanzahl);
	formulardaten.append("geraeteids", geraeteids);
	formulardaten.append("geraeteanzahl", geraeteanzahl);
	formulardaten.append("geraeteids", geraeteids);
	formulardaten.append("blockierunganzahl", blockierunganzahl);
	formulardaten.append("blockierungids", blockierungids);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_schulhof_raum_neu_speichern() {
	cms_laden_an('Neuen Raum anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_raeume_bezeichnung').value;
	var meldung = '<p>Der Raum konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_raeume_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neuen Raum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neuen Raum anlegen', 'Der neue Raum wird angelegt');

		formulardaten.append("anfragenziel", 	'137');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits einen Raum mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neuen Raum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "BLOCKFEHLER") {
				meldung += '<li>Blockierungen dieses Raumes überschneiden sich.</li>';
				cms_meldung_an('fehler', 'Neuen Raum anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Raum anlegen', '<p>Der Raum <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Räume\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_schulhof_raum_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Raum löschen', '<p>Soll der Raum <b>'+anzeigename+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_raum_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_raum_loeschen(anzeigename, id) {
	cms_laden_an('Raum löschen', 'Der Raum <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'138');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Raum löschen', '<p>Der Raum wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Räume\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_raum_bearbeiten_vorbereiten (id) {
	cms_laden_an('Raum bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'139');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Räume/Raum_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_raum_bearbeiten () {
	cms_laden_an('Raum bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_raeume_bezeichnung').value;
	var meldung = '<p>Der Raum konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_raeume_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Raum bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Raum bearbeiten', 'Der Raum wird bearbeitet');

		formulardaten.append("anfragenziel", 	'140');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits einen Raum mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Raum bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "BLOCKFEHLER") {
				meldung += '<li>Blockierungen dieses Raumes überschneiden sich.</li>';
				cms_meldung_an('fehler', 'Raum bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Raum bearbeiten', '<p>Der Raum <b>'+bezeichnung+'</b> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Räume\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
