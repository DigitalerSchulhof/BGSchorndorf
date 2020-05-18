function cms_produkt_eingabencheck() {
	var titel = document.getElementById('cms_produkt_titel').value;
	var bild = document.getElementById('cms_produkt_bild').value;
	var preis = document.getElementById('cms_produkt_preis').value;
	var anzahl = document.getElementById('cms_produkt_stk').value;
	var lieferzeit = document.getElementById('cms_produkt_lieferzeit').value;
	var inhalt = document.getElementsByClassName('note-editable');
	var beschreibung = inhalt[0].innerHTML;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;

	if (titel.length < 1) {
		meldung += '<li>Der Titel ist ungültig.</li>';
		fehler = true;
	}
	if (bild.length < 1) {
		meldung += '<li>Der Bilderlink ist ungültig.</li>';
		fehler = true;
	}
	if (!preis.match(/^[0-9]+,[0-9]{2}$/)) {
		meldung += '<li>Der Preis ist ungültig.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(anzahl,1)) {
		meldung += '<li>Die Anzahl ist ungültig.</li>';
		fehler = true;
	}

	formulardaten.append("titel", titel);
	formulardaten.append("bild", bild);
	formulardaten.append("preis", 	preis);
	formulardaten.append("lieferzeit", 	lieferzeit);
	formulardaten.append("anzahl", 	anzahl);
	formulardaten.append("beschreibung", 	beschreibung);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_produkt_neu_speichern() {
	cms_laden_an('Neues Produkt anlegen', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Das Produkt konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_produkt_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neues Produkt anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neues Produkt anlegen', 'Das neue Produkt wird angelegt');

		formulardaten.append("anfragenziel", 	'412');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Produkt anlegen', '<p>Das Produkt wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Produkte\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_produkt_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Produkt löschen', '<p>Soll das Produkt wirklich gelöscht werden?</p><p>Alle Posten aus Bestellungen, die auf dieses Produkt verweisen, werden ebenfalls entfernt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_produkt_loeschen('+id+')">Löschung durchführen</span></p>');
}


function cms_produkt_loeschen(id) {
	cms_laden_an('Produkt löschen', 'Das Produkt wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'415');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Produkt löschen', '<p>Das Produkt wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Produkte\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_produkt_bearbeiten_vorbereiten (id) {
	cms_laden_an('Produkt bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'413');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Produkte/Produkt_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_produkt_bearbeiten () {
	cms_laden_an('Produkt bearbeiten', 'Die Eingaben werden überprüft.');
	var meldung = '<p>Das Produkt konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_produkt_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Produkt bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Produkt bearbeiten', 'Das Produkt wird bearbeitet');

		formulardaten.append("anfragenziel", 	'414');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Produkt bearbeiten', '<p>Das Produkt wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Produkte\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_bestellung_loeschen_anzeigen (id) {
	cms_meldung_an('warnung', 'Bestellung löschen', '<p>Soll die Bestellung wirklich gelöscht werden?</p><p>Alle Posten dieser Bestellung, werden ebenfalls entfernt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_bestellung_loeschen('+id+')">Löschung durchführen</span></p>');
}

function cms_bestellung_loeschen(id) {
	cms_laden_an('Bestellung löschen', 'Die Bestellung wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'418');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Bestellung löschen', '<p>Die Besetellung wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Bestellungen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_allebestellungen_loeschen_anzeigen() {
	cms_meldung_an('warnung', 'Alle Bestellungen löschen', '<p>Sollen alle Bestellungen wirklich gelöscht werden?</p><p>Alle Posten Bestellposten, werden ebenfalls entfernt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_allebestellungen_loeschen()">Löschung durchführen</span></p>');
}

function cms_allebestellungen_loeschen() {
	cms_laden_an('Alle Bestellungen löschen', 'Alle Bestellungen werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'419');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Alle Bestellungen löschen', '<p>Alle Besetellungen wurden gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Bestellungen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_bestellung_status(id, status, zielanhang) {
	var zielanhang = zielanhang || "";
	cms_laden_an('Bestellstatus ändern', 'Der Bestellstatus wird geändert.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("status",     		status);
	formulardaten.append("anfragenziel", 	'416');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Bestellungen'+zielanhang);
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_bestellung_sehen(id) {
	cms_laden_an('Bestellung ansehen', 'Bestelldetails werden geladen');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'417');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Bestellungen/Bestellung_ansehen');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
