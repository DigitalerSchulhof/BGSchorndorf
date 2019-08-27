function cms_postfach_senden() {
	cms_laden_an('Nachricht versenden', 'Die Nachricht wird versendet');

	var empfaenger = document.getElementById('cms_postfach_empfaenger_personensuche_gewaehlt').value;
	var betreff = document.getElementById('cms_postfach_betreff').value;
	var nachricht = document.getElementsByClassName('note-editable');
	nachricht = nachricht[0].innerHTML;

	var meldung = '<p>Die Nachricht konnte nicht versendet werden, denn ...</p><ul>';
	var fehler = false;

	if (empfaenger.length <2) {
		meldung += '<li>Es wurde kein Empfänger eingegeben.</li>';
		fehler = true;
	}

	if (betreff.length < 1) {
		meldung += '<li>Es wurde kein Betreff eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Nachricht versenden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("empfaenger",  empfaenger);
		formulardaten.append("betreff",     betreff);
		formulardaten.append("nachricht",   nachricht);
		formulardaten.append("anfragenziel", 	'50');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "EMPFAENGERFEHLER") {
				meldung += '<li>Mindestens ein Empfänger hat kein Nutzerkonto.</li>';
				cms_meldung_an('fehler', 'Nachricht versenden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Nachricht versenden', '<p>Die Nachricht wurde versendet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Posteingang\');">OK</span></p>');
			}
			else if (rueckgabe.match(/POOL/)) {
				alert(rueckgabe);
				meldung += '<li>Mindestens einem Empfänger darf nicht geschrieben werden.</li>';
				cms_meldung_an('fehler', 'Nachricht versenden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_postfach_entwurfspeichern() {
	cms_laden_an('Nachricht speichern', 'Die Nachricht wird als Entwurf gespeichert');

	var empfaenger = document.getElementById('cms_postfach_empfaenger_personensuche_gewaehlt').value;
	var betreff = document.getElementById('cms_postfach_betreff').value;
	var nachricht = document.getElementsByClassName('note-editable');
	nachricht = nachricht[0].innerHTML;

	var formulardaten = new FormData();
	formulardaten.append("empfaenger",  empfaenger);
	formulardaten.append("betreff",     betreff);
	formulardaten.append("nachricht",   nachricht);
	formulardaten.append("anfragenziel", 	'51');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Nachricht als Entwurf speichern', '<p>Die Nachricht wurde als Entwurf gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Posteingang\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


/* NACHRICHTENANSICHT WIRD FÜR DAS LESEN DER NACHRICHT VORBEREITET UND ANSCHLIESSEND GEÖFFNET */
function cms_postfach_nachricht_lesen (modus, anzeigename, betreff, datum, uhrzeit, id) {
	cms_laden_an('Nachricht lesen', 'Die Nachricht <br><b>'+anzeigename+'</b> – <b>'+betreff+'</b><br> vom '+datum+' um '+uhrzeit+'<br> wird vorbereitet.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("modus", modus);
	formulardaten.append("anfragenziel", 	'52');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Nutzerkonto/Postfach/Nachricht_lesen');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_schulhof_postfach_nachricht_papierkorb_anzeige (modus, betreff, datum, id) {
	cms_meldung_an('warnung', 'Nachricht in den Papierkorb legen', '<p>Soll die Nachricht <br><b>'+betreff+'</b> vom '+datum+'<br>wirklich in den Papierkorb gelegt werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_postfach_nachricht_papierkorb(\''+modus+'\',\''+betreff+'\',\''+datum+'\','+id+')">In den Papierkorb legen</span></p>');
}


function cms_schulhof_postfach_nachricht_papierkorb (modus, betreff, datum, id) {
	cms_laden_an('Nachricht in den Papierkorb legen', 'Die Nachricht <br><b>'+betreff+'</b> vom '+datum+'<br>wird in den Papierkorb gelegt.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("modus",     modus);
	formulardaten.append("anfragenziel", 	'53');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			var ziel = '';
			if (modus == 'eingang') {ziel = 'Posteingang';}
			if (modus == 'entwurf') {ziel = 'Entwürfe';}
			if (modus == 'ausgang') {ziel = 'Postausgang';}
			cms_link('Schulhof/Nutzerkonto/Postfach/'+ziel);
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_schulhof_postfach_nachricht_loeschen_anzeige (modus, betreff, datum, id) {
	cms_meldung_an('warnung', 'Nachricht endgültig löschen', '<p>Soll die Nachricht <br><b><b>'+betreff+'</b><br> vom '+datum+'<br>wirklich in endgültig gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_postfach_nachricht_loeschen(\''+modus+'\',\''+betreff+'\',\''+datum+'\','+id+')">Endgültig löschen</span></p>');
}


function cms_schulhof_postfach_nachricht_loeschen (modus, betreff, datum, id) {
	cms_laden_an('Nachricht endgültig löschen', 'Die Nachricht von <br><b><b>'+betreff+'</b> vom '+datum+'<br>wird endgültig gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("modus",     modus);
	formulardaten.append("anfragenziel", 	'54');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Nutzerkonto/Postfach/Papierkorb');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_schulhof_postfach_nachricht_zuruecklegen (modus, betreff, datum, id) {
	cms_laden_an('Nachricht zurücklegen', 'Die Nachricht <br><b>'+betreff+'</b> vom '+datum+'<br>wird aus dem Papierkorb zurückgelegt.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("modus",    	modus);
	formulardaten.append("anfragenziel", 	'55');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			var ziel = '';
			if (modus == 'eingang') {ziel = 'Posteingang';}
			if (modus == 'entwurf') {ziel = 'Entwürfe';}
			if (modus == 'ausgang') {ziel = 'Postausgang';}
			cms_meldung_an('erfolg', 'Nachricht zurücklegen', '<p>Die Nachricht wurde zurückgelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/'+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


/* Nachrichtenansicht wird für Benutzung vorbereitet */
function cms_schulhof_postfach_nachricht_vorbereiten (aktion, id, modus, empfaenger, gruppe, gruppenid) {
	var gruppe = gruppe || '-';
	var gruppenid = gruppenid || '-';
	cms_laden_an('Nachricht schreiben', 'Die neue Nachricht wird vorbereitet');

	var formulardaten = new FormData();
	formulardaten.append("aktion", aktion);
	formulardaten.append("id", id);
	formulardaten.append("modus", modus);
	formulardaten.append("empfaenger", empfaenger);
	formulardaten.append("gruppe", gruppe);
	formulardaten.append("gruppenid", gruppenid);
	formulardaten.append("anfragenziel", 	'56');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Nutzerkonto/Postfach/Neue_Nachricht');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_postfach_signatur_aendern() {
	cms_laden_an('Signatur ändern', 'Die Signatur wird geändert');

	var signatur = document.getElementById('cms_postfach_signatur').value;

	var formulardaten = new FormData();
	formulardaten.append("signatur",  signatur);
	formulardaten.append("anfragenziel", 	'57');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Signatur ändern', '<p>Die Signatur wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Signatur\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_postfach_neuertag() {
	cms_laden_an('Neuen Tag anlegen', 'Die Eingaben werden überprüft.');

	var titel = document.getElementById('cms_postach_tag_titel').value;
	var farbe = document.getElementById('cms_postach_tag_farbe').value;
	var meldung = '<p>Der neue Tag konnte nicht angelegt werden, denn ...</p><ul>';
	var fehler = false;

	if ((isNaN(farbe)) || (farbe % 1 != 0) || (farbe > 47)) {
		meldung += '<li>es wurde keine Farbe ausgewählt.</li>';
		fehler = true;
	}

	if (titel.length < 1) {
		meldung += '<li>es wurde kein Titel eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neuen Tag anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("titel",  titel);
		formulardaten.append("farbe",  farbe);
		formulardaten.append("anfragenziel", 	'58');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neuen Tag anlegen', '<p>Der Tag wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Tags\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_postfach_tag_loeschen_anzeigen (id, anzeigename) {
	cms_meldung_an('warnung', 'Tag löschen', '<p>Soll der Tag <b>'+anzeigename+'</b> wirklich gelöscht werden? Nachrichten, die mit diesem Tag markiert wurden, bleiben erhalten, lediglich der Tag wird entfernt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_postfach_tag_loeschen(\''+id+'\',\''+anzeigename+'\')">Löschen</span></p>');
}


function cms_postfach_tag_loeschen (id, anzeigename) {
	cms_laden_an('Tag löschen', 'Der Tag <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'59');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Tag löschen', '<p>Der Tag wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Tags\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_postfach_tag_bearbeiten_vorbereiten (id, anzeigename) {
	cms_laden_an('Tag bearbeiten', 'Der Tag <b>'+anzeigename+'</b> wird vorbereitet.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'60');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Nutzerkonto/Postfach/Tags/Tag_bearbeiten');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_postfach_tag_bearbeiten() {
	cms_laden_an('Tag bearbeiten', 'Die Eingaben werden überprüft.');

	var titel = document.getElementById('cms_postach_tag_titel').value;
	var farbe = document.getElementById('cms_postach_tag_farbe').value;
	var meldung = '<p>Der Tag konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if ((isNaN(farbe)) || (farbe % 1 != 0) || (farbe > 47)) {
		meldung += '<li>es wurde keine Farbe ausgewählt.</li>';
		fehler = true;
	}

	if (titel.length < 1) {
		meldung += '<li>es wurde kein Titel eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Tag bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("titel",  titel);
		formulardaten.append("farbe",  farbe);
		formulardaten.append("anfragenziel", 	'61');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Tag bearbeiten', '<p>Der Tag wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Tags\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_postfach_nachricht_taggen(anschalten, tagid) {
	if (anschalten == 1) {
		cms_laden_an('Nachricht taggen', 'Der Tag wird der Nachricht zugewiesen.');
	}
	else {
		cms_laden_an('Nachricht taggen', 'Der Tag wird von der Nachricht entfernt.');
	}


	var formulardaten = new FormData();
	formulardaten.append("tagid",  		tagid);
	formulardaten.append("anschalten",  anschalten);
	formulardaten.append("anfragenziel", 	'62');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Nutzerkonto/Postfach/Nachricht_lesen');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_postfach_nachrichten_laden(modus, papierkorb, id) {
	var tabbody = document.getElementById('cms_postfach_'+modus+'_liste');
	tabbody.innerHTML = '<tr><td class=\"cms_notiz\" colspan=\"6\">'+cms_ladeicon()+'<br>Die Suche wird verarbeitet. Je nach Verbindung und Schulgröße kann dies etwas dauern.</td></tr>';

	var nachname   	= document.getElementById('cms_postfach_filter_nachname'+id).value;
	var vorname     = document.getElementById('cms_postfach_filter_vorname'+id).value;
	var betreff     = document.getElementById('cms_postfach_filter_betreff'+id).value;
	var vonT 		  = document.getElementById('cms_postfach_filter_zeitraumv'+id+'_T').value;
	var vonM 		  = document.getElementById('cms_postfach_filter_zeitraumv'+id+'_M').value;
	var vonJ 		  = document.getElementById('cms_postfach_filter_zeitraumv'+id+'_J').value;
	var bisT   		= document.getElementById('cms_postfach_filter_zeitraumb'+id+'_T').value;
	var bisM   		= document.getElementById('cms_postfach_filter_zeitraumb'+id+'_M').value;
	var bisJ   		= document.getElementById('cms_postfach_filter_zeitraumb'+id+'_J').value;
	var tags   		= document.getElementById('cms_postfach_filter_tags'+id).value;
	var nummer   	= document.getElementById('cms_postfach_filter_nummer'+id).value;
	var limit   	= document.getElementById('cms_postfach_filter_limit'+id).value;

	var formulardaten = new FormData();
	formulardaten.append("modus",  		modus);
	formulardaten.append("papierkorb",  papierkorb);
	formulardaten.append("nachname",  	nachname);
	formulardaten.append("vorname",  	vorname);
	formulardaten.append("betreff",  	betreff);
	formulardaten.append("vonT",  		vonT);
	formulardaten.append("vonM",  		vonM);
	formulardaten.append("vonJ",  		vonJ);
	formulardaten.append("bisT",  		bisT);
	formulardaten.append("bisM",  		bisM);
	formulardaten.append("bisJ",  		bisJ);
	formulardaten.append("nummer",  	nummer);
	formulardaten.append("limit",  		limit);
	formulardaten.append("anfragenziel", 	'63');

	// alle Tagfelder auslesen
	var taguebergabe = "";
	tags = tags.split("|");
	for (i=1; i<tags.length; i++) {
		var feld;
		if (feld = document.getElementById('cms_postfach_filter_tag'+id+'_'+tags[i])) {
			if (feld.value == '1') {
				taguebergabe = taguebergabe + '|' + tags[i];
			}
		}
	}
	formulardaten.append("tags",  		taguebergabe);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.slice(0,3) == '<tr') {
				tabbody.innerHTML = rueckgabe;
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_postfach_papierkorb_leeren_anzeigen (modus) {
	var anzeigename = '';
	if (modus == 'eingang') {anzeigename = 'Posteingang';}
	else if (modus == 'entwurf') {anzeigename = 'Entwürfe';}
	else if (modus == 'ausgang') {anzeigename = 'Postausgang';}
	cms_meldung_an('warnung', 'Papierkorb leeren', '<p>Soll der gesamte Papierkorb <b>'+anzeigename+'</b> wirklich geleert werden?</p><p>Es wird der gesamte Papierkorb geleert! Wenn Filter ausgewählt sind, werden möglicherweise mehr Nachrichten gelöscht, als momentan sichtbar sind!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_postfach_papierkorb_leeren(\''+modus+'\')">Löschen</span></p>');
}


function cms_postfach_papierkorb_leeren (modus) {
	var anzeigename = '';
	if (modus == 'eingang') {anzeigename = 'Posteingang';}
	else if (modus == 'entwurf') {anzeigename = 'Entwürfe';}
	else if (modus == 'ausgang') {anzeigename = 'Postausgang';}
	cms_laden_an('Papierkorb leeren', 'Der Papierkorb <b>'+anzeigename+'</b> wird geleert.');

	var formulardaten = new FormData();
	formulardaten.append("modus",    modus);
	formulardaten.append("anfragenziel", 	'64');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Papierkorb leeren', '<p>Der Papierkorb wurde geleert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Postfach/Papierkorb\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_postfach_eigennachricht() {
	cms_meldung_an('info', 'Nachricht an sich selbst', '<p>Es ist nicht möglich eine Nachricht an sich selbst zu verschicken.</p>', '<p><span class="cms_button" onclick="cms_laden_aus();">OK</span></p>');
}

function cms_postfach_nachrichten_seite(id, nr, modus, papierkorb) {
	var limit = document.getElementById('cms_postfach_filter_limit'+id).value;
	document.getElementById('cms_postfach_filter_nummer'+id).value = (nr-1)*limit;
	cms_postfach_nachrichten_laden(modus, papierkorb, id);
}
