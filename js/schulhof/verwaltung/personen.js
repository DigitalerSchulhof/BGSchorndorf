/* PERSON CLEVER ANLEGEN - VOREINSTELLUGEN BEI PERSONENART */
function cms_schulhof_verwaltung_personen_neu_art_wechsel() {
	var art = document.getElementById('cms_schulhof_verwaltung_personen_neu_art').value;

	// Felder die eingeblendet werden sollen, oder nicht
	var lehrerkuerzelFid = 'cms_schulhof_verwaltung_person_neu_sonstiges';
	var heute = new Date();
	if (art == 's') {
		cms_ausblenden(lehrerkuerzelFid);
	}
	else if (art == 'l') {
		cms_einblenden(lehrerkuerzelFid);
	}
	else if (art == 'e') {
		cms_ausblenden(lehrerkuerzelFid);
	}
	else if (art == 'v') {
		cms_ausblenden(lehrerkuerzelFid);
	}
	else {
		cms_ausblenden(lehrerkuerzelFid);
	}
}


/* PERSON WIRD GESPEICHERT */
function cms_schulhof_verwaltung_personen_neu_speichern () {
	cms_laden_an('Neue Person anlegen', 'Die Eingaben werden überprüft.');
	var art = document.getElementById('cms_schulhof_verwaltung_personen_neu_art').value;
	var titel = document.getElementById('cms_schulhof_verwaltung_personen_neu_titel').value;
	var vorname = document.getElementById('cms_schulhof_verwaltung_personen_neu_vorname').value;
	var nachname = document.getElementById('cms_schulhof_verwaltung_personen_neu_nachname').value;
	var geschlecht = document.getElementById('cms_schulhof_verwaltung_personen_neu_geschlecht').value;
	var lehrerkuerzel = document.getElementById('cms_schulhof_verwaltung_personen_neu_lehrerkuerzel').value;
	var stundenplan = document.getElementById('cms_schulhof_verwaltung_personen_neu_stundenplan').value;

	var meldung = '<p>Die Person konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (vorname.length == 0) {
		meldung += '<li>es wurde kein Vorname eingegeben.</li>';
		fehler = true;
	}
	if (!cms_check_name(vorname)) {
		meldung += '<li>für den Vornamen wurden ungültige Zeichen verwendet.</li>';
		fehler = true;
	}

	if (nachname.length == 0) {
		meldung += '<li>es wurde kein Nachname eingegeben.</li>';
		fehler = true;
	}
	if (!cms_check_name(nachname)) {
		meldung += '<li>für den Nachnamen wurden ungültige Zeichen verwendet.</li>';
		fehler = true;
	}

	if (!cms_check_nametitel(titel)) {
		meldung += '<li>für den Titel wurden ungültige Zeichen verwendet.</li>';
		fehler = true;
	}

	if ((geschlecht != 'w') && (geschlecht != "m") && (geschlecht != "u")) {
		meldung += '<li>es wurde kein Geschlecht ausgewählt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Person anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Person anlegen', 'Die Person wird angelegt.');

		var formulardaten = new FormData();
		formulardaten.append("art",   					art);
		formulardaten.append("titel",     			titel);
		formulardaten.append("vorname",     		vorname);
		formulardaten.append("nachname", 				nachname);
		formulardaten.append("geschlecht",   		geschlecht);
		formulardaten.append("lehrerkuerzel",   lehrerkuerzel);
		formulardaten.append("stundenplan",   stundenplan);
		formulardaten.append("anfragenziel", 	'121');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Person anlegen', '<p>Die Person <i>'+vorname+' '+nachname+'</i> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

/* BENUTZERNAME WIRD GEÄNDERT */
function cms_schulhof_verwaltung_personen_benutzerkonto_aendern () {
	cms_laden_an('Benutzerkonto ändern', 'Die Eingaben werden überprüft.');
	var benutzername = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_benutzername').value;
	var mail = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_email').value;
	var id = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_benutzer_id').value;

	var meldung = '<p>Der Benutzername konnte nicht geändert werden, denn ...</p><ul>';
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
		cms_meldung_an('fehler', 'Benutzerkonto ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Benutzerkonto ändern', 'Die neuen persönlichen Daten werden verschlüsselt gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("benutzername", benutzername);
		formulardaten.append("mail", mail);
		formulardaten.append("modus", 1);
		formulardaten.append("id",	  id);
		formulardaten.append("anfragenziel", 	'122');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "FEHLER") {
				meldung += '<li>der Benutzername <i>'+benutzername+'</i> ist bereits vergeben.</li>';
				cms_meldung_an('fehler', 'Benutzerkonto ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Benutzerkonto ändern', '<p>Das Benutzerkonto wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen/Details\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}




/* BENUTZERNAME WIRD GEÄNDERT */
function cms_schulhof_verwaltung_personen_lehrerkuerzel_aendern () {
	cms_laden_an('Lehrerkürzel ändern', 'Die Eingaben werden überprüft.');
	var lehrerkuerzel = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_lehrerkuerzel').value;
	var splan = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_stundenplan');
	if (splan) {stundenplan = splan.value;} else {stundenplan = '';}
	var id = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_lehrerkuerzel_id').value;


	var formulardaten = new FormData();
	formulardaten.append("lehrerkuerzel", lehrerkuerzel);
	formulardaten.append("stundenplan", stundenplan);
	formulardaten.append("modus", 1);
	formulardaten.append("id",	  id);
	formulardaten.append("anfragenziel", 	'123');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Lehrerkürzel ändern', '<p>Das Lehrerkürzel wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen/Details\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);


	cms_laden_an('Lehrerkürzel ändern', 'Die neuen persönlichen Daten werden verschlüsselt gespeichert.');
}



function cms_schulhof_verwaltung_personen_persoenlich_aendern(art) {
	cms_laden_an('Persönliche Daten ändern', 'Die Eingaben werden überprüft.');
	var titel = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_titel').value;
	var vorname = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_vorname').value;
	var nachname = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_nachname').value;
	var geschlecht = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_geschlecht').value;
	var id = '';
	var modus = 0;
	if (document.getElementById('cms_schulhof_verwaltung_personen_profildaten_id')) {
		id = document.getElementById('cms_schulhof_verwaltung_personen_profildaten_id').value;
		modus = 1;
	}

	var meldung = '<p>Die Änderungen an den persönlichen Daten konnten nicht vorgenommen werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_name(vorname)) {
		meldung += '<li>es wurde ein ungültiger Vorname eingegeben.</li>';
		fehler = true;
	}
	if (!cms_check_name(nachname)) {
		meldung += '<li>es wurde ein ungültiger Nachname eingegeben.</li>';
		fehler = true;
	}
	if (!cms_check_nametitel(titel)) {
		meldung += '<li>es wurde ein ungültiger Titel eingegeben.</li>';
		fehler = true;
	}
	if ((geschlecht != 'w') && (geschlecht != "m") && (geschlecht != "u")) {
		meldung += '<li>es wurde kein Geschlecht ausgewählt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Persönliche Daten ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Persönliche Daten ändern', 'Die neuen persönlichen Daten werden verschlüsselt gespeichert.');

		var formulardaten = new FormData();
		formulardaten.append("titel",     		titel);
		formulardaten.append("vorname",     	vorname);
		formulardaten.append("nachname", 		nachname);
		formulardaten.append("geschlecht",   	geschlecht);
		formulardaten.append("id",    			id);
		formulardaten.append("modus",			modus);
		formulardaten.append("anfragenziel", 	'124');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Persönliche Daten ändern', '<p>Die persönlichen Daten wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen/Details\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_schulhof_verwaltung_person_loeschen_anzeige(anzeigename, id) {
	cms_meldung_an('warnung', 'Person löschen', '<p>Achtung! Sie sind im Begriff die Person <br><b>'+anzeigename+'</b><br> zu löschen! Sind Sie sicher, dass Sie fortfahren möchten?<br>Alle Daten, die mit dieser Person in Verbindung stehen, werden unwiderruflich gelöscht!<br>Die Person wird über die Löschung benachrichtigt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_verwaltung_person_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_verwaltung_person_loeschen(anzeigename, id) {
	cms_laden_an('Person löschen', 'Die Löschung von <br><b>'+anzeigename+'</b><br> wird durchgeführt. Dies kan einen Moment dauern. Danach wird die Person über die Löschung per eMail informiert.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'125');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ADMINFEHLER") {
			cms_meldung_an('fehler', 'Person löschen', '<p>Die Person konnte nicht gelöscht werden. Es muss immer mindestens einen Administrator geben.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Person löschen', '<p>Die Person wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

// Rolle vergeben
function cms_schulhof_verwaltung_personen_rolle_vergeben(anschalten, rolle) {
	if (anschalten == 1) {
		cms_laden_an('Rolle vergeben', 'Die Rolle wird vergeben.');
	}
	else {
		cms_laden_an('Rolle vergeben', 'Die Rolle wird entzogen.');
	}

	var formulardaten = new FormData();
	formulardaten.append("rolle",  		rolle);
	formulardaten.append("anschalten",  anschalten);
	formulardaten.append("anfragenziel", 	'126');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.match(/ADMINFEHLER/)) {
			cms_meldung_an('fehler', 'Rolle entfernen', '<p>Es muss immer einen Administrator geben. Diese Rolle darf nicht von allen Personen entfernt werden.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Personen/Rollen_und_Rechte');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


// Rechte vergeben
function cms_schulhof_verwaltung_personen_recht_vergeben(anschalten, recht) {
	if (anschalten == 1) {
		cms_laden_an('Recht vergeben', 'Das Recht wird vergeben.');
	}
	else {
		cms_laden_an('Recht vergeben', 'Das Recht wird entzogen.');
	}

	var formulardaten = new FormData();
	formulardaten.append("recht",  		recht);
	formulardaten.append("anschalten",  anschalten);
	formulardaten.append("anfragenziel", 	'127');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Personen/Rollen_und_Rechte');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


// Rechte vergeben
function cms_schulhof_verwaltung_personen_rollenundrechtevergabe() {
	cms_meldung_an('erfolg', 'Rollen und Rechte vergeben', '<p>Bereits während der Auswahl der Rollen und Rechte wurden die Änderungen gespeichert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">OK</span></p>');

}


/* PERSON WIRD FÜR NUTZERKONTOEINSTELLUNGEN VORBEREITET UND GEÖFFNET */
function cms_schulhof_verwaltung_personen_einstellungen (id) {
	cms_laden_an('Einstellungen des Nutzerkontos', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'128');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Personen/Einstellungen_des_Nutzerkontos');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}




function cms_schulhof_verwaltung_personen_einstellungen_aendern() {
	cms_laden_an('Einstellungen ändern', 'Die Eingaben werden überprüft.');
	var postmail = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_postmail').value;
	var postalletage = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_alle_tage').value;
	var postpapierkorbtage = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_papierkorb_tage').value;
	var notifikationsmail = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_notifikationmail').value;
	var vertretungsmail = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_vertretungsmail').value;
	var uebersichtsanzahl = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_uebersichtsanzahl').value;
	var id = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_id').value;
	var inaktivitaetszeit = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_inaktivitaetszeit').value;
	var terminoeffentlich = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_terminoeffentlich').value;
	var blogoeffentlich = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_blogoeffentlich').value;
	var galerieoeffentlich = document.getElementById('cms_schulhof_verwaltung_personen_einstellungen_galerieoeffentlich').value;

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
	if (!cms_check_ganzzahl(postalletage,1,1000)) {
		meldung += '<li>die Eingabe an Tagen zur automatischen Löschung von Nachrichten ist keine Zahl.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(postpapierkorbtage,0,100)) {
		meldung += '<li>die Eingabe an Tagen zur automatischen Löschung von Nachrichten im Papierkorb ist keine Zahl.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(uebersichtsanzahl,1,25)) {
		meldung += '<li>die Eingabe der Anzahl von Elementen in Übersichten ist keine Zahl.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(inaktivitaetszeit,5,300)) {
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
		formulardaten.append("vertretungsmail", 	vertretungsmail);
		formulardaten.append("uebersichtsanzahl", uebersichtsanzahl);
		formulardaten.append("inaktivitaetszeit", 	inaktivitaetszeit);
		formulardaten.append("terminoeffentlich", 	terminoeffentlich);
		formulardaten.append("blogoeffentlich", 		blogoeffentlich);
		formulardaten.append("galerieoeffentlich", 	galerieoeffentlich);
		formulardaten.append("id", 					id);
		formulardaten.append("modus", 				1);
		formulardaten.append("anfragenziel", 	'68');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {cms_meldung_an('erfolg', 'Einstellungen ändern', '<p>Die neuen Einstellungen wurden übernommen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen/Einstellungen_des_Nutzerkontos\');">OK</span></p>');}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_persondazu(feldid, id, art, selbst, hinzufuegeart) {
	cms_laden_an('Person hinzufügen', 'Die Person wird hinzugefügt');
	var formulardaten = new FormData();
	formulardaten.append("id",  id);
	formulardaten.append("art", art);
	formulardaten.append("feldid", feldid);
	formulardaten.append("selbst", selbst);
	formulardaten.append("hinzufuegeart", hinzufuegeart);
	formulardaten.append("anfragenziel", 	'131');

	function anfragennachbehandlung(rueckgabe) {
		if ((rueckgabe.slice(0,5) == '<span') || (rueckgabe.slice(0,3) == '<tr')) {
			var antwort = rueckgabe;
			antwort = antwort.split('/');
			if (document.getElementById('cms_'+feldid+'_keine')) {
				document.getElementById('cms_'+feldid+'_keine').style.display = 'none';
			}
			var zielF = document.getElementById('cms_'+feldid+'F');
			var vorhanden = zielF.innerHTML;
			zielF.innerHTML = vorhanden + antwort[0];

			var zielidF = document.getElementById('cms_'+feldid+'');
			var vorhanden = zielidF.value;
			zielids = antwort[1];
			zielidF.value = vorhanden+zielids;
			cms_ausblenden('cms_'+feldid+'_suchfeld');
			document.getElementById('cms_'+feldid+'_nnamesuche').value = "";
			document.getElementById('cms_'+feldid+'_vnamesuche').value = "";
			if (document.getElementById('cms_'+feldid+'_verwaltung')) {
				document.getElementById('cms_'+feldid+'_verwaltung').value = "0";
				cms_klasse_weg('cms_toggle_'+feldid+'_verwaltung', 'cms_toggle_aktiv');
			}
			if (document.getElementById('cms_'+feldid+'_schueler')) {
				document.getElementById('cms_'+feldid+'_schueler').value = "0";
				cms_klasse_weg('cms_toggle_'+feldid+'_schueler', 'cms_toggle_aktiv');
			}
			if (document.getElementById('cms_'+feldid+'_lehrer')) {
				document.getElementById('cms_'+feldid+'_lehrer').value = "0";
				cms_klasse_weg('cms_toggle_'+feldid+'_lehrer', 'cms_toggle_aktiv');
			}
			if (document.getElementById('cms_'+feldid+'_eltern')) {
				document.getElementById('cms_'+feldid+'_eltern').value = "0";
				cms_klasse_weg('cms_toggle_'+feldid+'_eltern', 'cms_toggle_aktiv');
			}
			cms_personensuche(feldid, selbst, hinzufuegeart);
			cms_laden_aus();
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_personweg(feldid, id, selbst, hinzufuegeart) {
	var zielF = document.getElementById('cms_'+feldid+'_person'+id);
	var idsF = document.getElementById('cms_'+feldid);
	var ids = idsF.value;
	ids = ids+'|';
	ids = ids.replace("|"+id+"|", "|");
	ids = ids.slice(0,(ids.length-1));
	idsF.value = ids;
	zielF.remove();
	if (document.getElementById('cms_'+feldid+'F')) {
		if ((document.getElementById('cms_'+feldid+'F').innerHTML).length == 0) {
			document.getElementById('cms_'+feldid+'_keine').style.display = 'table-row';
		}
	}
	cms_personensuche(feldid, selbst, hinzufuegeart);
}


// Schuljahr vergeben
function cms_schulhof_verwaltung_person_schuljahr_einstellen(schuljahr, person) {
	cms_laden_an('Schuljahr umstellen', 'Das aktive Schuljahr wird umgestellt.');

	var formulardaten = new FormData();
	formulardaten.append("schuljahr",  		schuljahr);
	formulardaten.append("id",  			person);
	formulardaten.append("modus",  			1);
	formulardaten.append("anfragenziel", 	'132');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Verwaltung/Personen/Details');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


/* PERSON WIRD FÜR ZUORDNUNG VORBEREITET UND GEÖFFNET */
function cms_schulhof_verwaltung_personen_verknuepfung () {
	cms_laden_an('Schüler und Eltern verknüpfen', 'Die Berechtigung wird geprüft.');
	var zuordnung = document.getElementById('cms_schuereltern_zuordnung_personensuche_gewaehlt').value;

	var formulardaten = new FormData();
	formulardaten.append("zuordnung", zuordnung);
	formulardaten.append("anfragenziel", 	'133');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "PERSONENFEHLER") {
			var meldung = '<p>Das Schuljahr konnte nicht erstellt werden, denn ...</p><ul>';
			meldung += '<li>es wurden Personen zugeordnet, die für diese Funktion nicht in Frage kommen.</li>';
			cms_meldung_an('fehler', 'Schüler und Eltern verknüpfen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else if (rueckgabe == "ERFOLG"){
			cms_meldung_an('erfolg', 'Schüler und Eltern verknüpfen', '<p>Schüler und Eltern wurden verknüpft.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_schulhof_neueperson_nutzerkonto() {
	var nutzerkonto = document.getElementById('cms_schulhof_verwaltung_personen_neu_nutzerkonto').value;
	var feld = document.getElementById('cms_schulhof_verwaltung_personen_neu_nutzerkonto_feld');
	if (nutzerkonto == 1) {feld.style.display = 'block';}
	else {feld.style.display = 'none';}
}

function cms_schulhof_kein_nutzerkonto(anrede) {
	cms_meldung_an('info', 'Kein Nutzerkonto verfügbar', '<p>Für '+anrede+' ist kein Notzerkonto angelegt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
}

function cms_schulhof_verwaltung_details_vorbreiten(anrede, id, ziel) {
	cms_laden_an('Persönliche Daten aufrufen', 'Die Berechtigung wird geprüft.');
	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'134');

	var meldung = '<p>Die persönlichen Details konnten nicht aufgerufen werden, denn ...</p><ul>';
	var fehler = false;

	if ((ziel != 'Neues_Nutzerkonto_anlegen') && (ziel != 'Nutzerkonto_bearbeiten') && (ziel != 'Lehrerkürzel_ändern') && (ziel != 'Rollen_und_Rechte')
	    && (ziel != 'Schüler_und_Eltern_verknüpfen') && (ziel != 'Details') && (ziel != 'Stundenplan') && (ziel != 'Bearbeiten') && (ziel != 'IDs_bearbeiten')) {
		meldung += '<li>das gewünschte Ziel ist ungültig.</li>';
		fehler = true;
	}

	// Prüfen, ob der Nutzer die Rechte besitzt, um diese Daten abzurufen
	if (fehler) {
		cms_meldung_an('warnung', 'Persönliche Daten aufrufen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_link('Schulhof/Verwaltung/Personen/'+ziel);
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_schulhof_verwaltung_nutzerkonto_neu_speichern() {
	cms_laden_an('Neues Nutzerkonto anlegen', 'Die Eingaben werden überprüft.');
	var benutzername = document.getElementById('cms_schulhof_verwaltung_personen_neu_benutzername').value;
	var email = document.getElementById('cms_schulhof_verwaltung_personen_neu_mail').value;

	var meldung = '<p>Der Benutzername konnte nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (benutzername.length < 6) {
		meldung += '<li>der Benutzername ist kürzer als 6 Zeichen.</li>';
		fehler = true;
	}
	if (!cms_check_mail(email)) {
		meldung += '<li>es wurde keine gültige eMailadresse eingegeben.</li>';
		fehler = true;
	}


	if (fehler) {
		cms_meldung_an('fehler', 'Neues Nutzerkonto anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neues Nutzerkonto anlegen', 'Das Nutzerkonto wird angelegt.');

		var formulardaten = new FormData();
		formulardaten.append("benutzername",  benutzername);
		formulardaten.append("email",     		email);
		formulardaten.append("anfragenziel", 	'135');

		function anfragennachbehandlung(rueckgabe) {

			if (rueckgabe == "BENUTZERDOPPELT") {
				meldung += '<li>es gibt bereits einen Benutzer mit diesem Benutzernamen.</li>';
				cms_meldung_fehler('fehler', 'Neues Nutzerkonto anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Nutzerkonto anlegen', '<p>Das Nutzerkonto wurde angelegt. Ein Passwort wurde generiert und verschickt. Es ist für 24 Stunden gültig. Danach kann über <i>Passwort vergessen</i> ein neues Passwort angefordert werden.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_schulhof_verwaltung_nutzerkonto_loeschen_anzeige(anzeigename, id) {
	cms_meldung_an('warnung', 'Nutzerkonto löschen', '<p>Achtung! Sie sind im Begriff das Nutzerkonto von <br><b>'+anzeigename+'</b><br> zu löschen! Sind Sie sicher, dass Sie fortfahren möchten?<br>Alle Daten, die mit dieser Person in Verbindung stehen, werden unwiderruflich gelöscht!<br>Die Person wird über die Löschung benachrichtigt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_verwaltung_nutzerkonto_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_verwaltung_nutzerkonto_loeschen(anzeigename, id) {
	cms_laden_an('Nutzerkonto löschen', 'Die Löschung von <br><b>'+anzeigename+'</b><br> wird durchgeführt. Dies kan einen Moment dauern. Danach wird die Person über die Löschung per eMail informiert.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'136');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ADMINFEHLER") {
			cms_meldung_an('fehler', 'Nutzerkonto löschen', '<p>Das Nutzerkonto konnte nicht gelöscht werden. Es muss immer mindestens einen Administrator geben.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Nutzerkonto löschen', '<p>Das Nutzerkonto wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_personen_ids_aendern() {
	cms_laden_an('Personen-IDs ändern', 'Die Eingaben werden überprüft.');
	var zweit = document.getElementById('cms_personen_zweitid').value;
	var dritt = document.getElementById('cms_personen_drittid').value;
	var viert = document.getElementById('cms_personen_viertid').value;
	var meldung = '<p>Die Personen-IDs konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(zweit,0) && (zweit.length != 0)) {
		meldung += '<li>es wurde keine gültige Zweit-ID eingegeben.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(dritt,0) && (dritt.length != 0)) {
		meldung += '<li>es wurde keine gültige Dritt-ID eingegeben.</li>';
		fehler = true;
	}
	if (!cms_check_ganzzahl(viert,0) && (viert.length != 0)) {
		meldung += '<li>es wurde keine gültige Viert-ID eingegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Personen-IDs ändern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Personen-IDs ändern', 'Die Personen-IDs werden geändert.');

		var formulardaten = new FormData();
		formulardaten.append("zweit",  zweit);
		formulardaten.append("dritt",  dritt);
		formulardaten.append("viert",  viert);
		formulardaten.append("anfragenziel", 	'373');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Personen-IDs ändern', '<p>Die Personen-IDs wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_kurszuordnung_zuruecksetzen_anzeigen() {
	cms_meldung_an('warnung', 'Kurszuordnungen zurücksetzen', '<p>Achtung! Sie sind im Begriff alle Zuordnungen von Schülern und Lehrern in Kurse im gewählten Schuljahr zu entfernen! Sind Sie sicher, dass Sie fortfahren möchten?</p><p>Die Kurse und darin enthaltene Eintragungen (Blogs, Termine, ...) und Dateien bleiben bestehen.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_kurszuordnung_zuruecksetzen()">Zurücksetzen durchführen</span></p>');
}


function cms_kurszuordnung_zuruecksetzen() {
	cms_laden_an('Kurszuordnungen zurücksetzen', 'Die Eingaben werden überprüft.');
	var sj = document.getElementById('cms_kurszuordnung_zurueck_schuljahr').value;
	var meldung = '<p>Die Kurszuordnungen konnten nicht zurückgesetzt werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(sj,0)) {
		meldung += '<li>es wurde kein gültiges Schuljahr gewählt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Kurszuordnungen zurücksetzen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Kurszuordnungen zurücksetzen', 'Die Kurszuordnungen werden zurückgesetzt.');

		var formulardaten = new FormData();
		formulardaten.append("sj",  sj);
		formulardaten.append("anfragenziel", 	'374');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Kurszuordnungen zurücksetzen', '<p>Die Kurszuordnungen wurden zurückgesetzt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_personenimport_speichern() {
	cms_laden_an('Personen-IDs importieren', 'Die Eingaben werden überprüft.');
	var personenart = document.getElementById('cms_personenimport_art').value;
	var idslot = document.getElementById('cms_personenimport_idslot').value;
	var id = document.getElementById('cms_personenimport_id').value;
	var vornach = document.getElementById('cms_personenimport_vornach').value;
	var nachvor = document.getElementById('cms_personenimport_nachvor').value;
	var nach = document.getElementById('cms_personenimport_nach').value;
	var vor = document.getElementById('cms_personenimport_vor').value;

	var csv = document.getElementById('cms_personenimport_csv').value;
	var trennung = document.getElementById('cms_personenimport_trennung').value;

	var meldung = '<p>Die Personen-IDs konnten nicht importiert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (csv.length == 0) {
		meldung += '<li>es wurden keine Datensätze eingegeben.</li>';
		fehler = true;
	}

	if (trennung.length == 0) {
		meldung += '<li>es wurde kein Trennsymbol eingegeben.</li>';
		fehler = true;
	}

  var maxspalten = 0;
  if (!fehler) {
    // Inhalte analysieren
    var csvanalyse = csv.split("\n");
    for (var i=0; i<csvanalyse.length; i++) {
      var aktspalten = csvanalyse[i].split(trennung).length;
      if (aktspalten > maxspalten) {maxspalten = aktspalten;}
    }
  }

	if ((personenart != 's') && (personenart != 'l') && (personenart != 'x') && (personenart != 'e') && (personenart != 'v')) {
		meldung += '<li>die Auswahl für die Personenart ist ungültig.</li>';
		fehler = true;
	}

	if ((idslot != 'zweitid') && (idslot != 'drittid') && (idslot != 'viertid')) {
		meldung += '<li>die Auswahl für den ID-Slot ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(id, 1, maxspalten)) {
		meldung += '<li>die Auswahl für die ID ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(vornach, 1, maxspalten) && (vornach != '-')) {
		meldung += '<li>die Auswahl für »Vorname, Nachname« ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(nachvor, 1, maxspalten) && (nachvor != '-')) {
		meldung += '<li>die Auswahl für »Nachname, Vorname« ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(nach, 1, maxspalten) && (nach != '-')) {
		meldung += '<li>die Auswahl für den Nachnamen ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(vor, 1, maxspalten) && (vor != '-')) {
		meldung += '<li>die Auswahl für den Vorname ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	var kombinationsfehler = true;
	if (((vornach != '-') && (nachvor == '-') && (vor == '-') && (nach == '-')) ||
	    ((vornach == '-') && (nachvor != '-') && (vor == '-') && (nach == '-')) ||
	 	  ((vornach == '-') && (nachvor == '-') && (vor != '-') && (nach != '-')))
	{
		kombinationsfehler = false;
	}
	if (kombinationsfehler) {
		meldung += '<li>es muss entweder eine Spalte für Vor- und Nachname zusammen, oder beide Spalten zu Vor- und Nachnamen einzeln angegeben werden.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Personen-IDs importieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
    var formulardaten = new FormData();
    formulardaten.append("csv", csv);
    formulardaten.append("trennung", trennung);
    formulardaten.append("personenart", personenart);
    formulardaten.append("idslot", idslot);
    formulardaten.append("id", id);
    formulardaten.append("vornach", vornach);
    formulardaten.append("nachvor", nachvor);
    formulardaten.append("vor", vor);
    formulardaten.append("nach", nach);
    formulardaten.append("anfragenziel", 	'372');

    function anfragennachbehandlung(rueckgabe) {
      var ergebnis = rueckgabe.split("\n\n\n");
      var meldung = ergebnis[0];
      var problemfaelle = ergebnis[1];
      if (meldung == "ERFOLG") {
				if (problemfaelle.length > 0) {
					cms_meldung_an('warnung', 'Personen-IDs importieren', '<p>Die Personenids wurden erfolgreich importiert.</p><p>Einigen Personen konnte keine ID zugewiesen werden:</p><ul>'+problemfaelle+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
				}
				else {
					cms_meldung_an('erfolg', 'Personen-IDs importieren', '<p>Die Personenids wurden erfolgreich importiert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
				}
      }
      else {
				cms_meldung_an('fehler', 'Personen-IDs importieren', '<p>Die Importdatei genügt nicht den Anforderungen an das Eingabeformat.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
    }

    cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_kurszuordnungimport_stufen_laden() {
	var feld = document.getElementById('cms_kurszuordnungimport_stufe');
	feld.innerHTML = "";
	var sj = document.getElementById('cms_kurszuordnungimport_schuljahr').value;
	var fehler = false;
	if (!cms_check_ganzzahl(sj, 0)) {fehler = true;}

	if (!fehler) {
    var formulardaten = new FormData();
    formulardaten.append("sj", 	sj);
    formulardaten.append("anfragenziel", 	'376');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe.match(/^<option/)) {
				feld.innerHTML = rueckgabe;
      }
    }
    cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_kurszuordnungklassenregelunterricht_speichern() {
	cms_laden_an('Kurszuordnung durch Klassen und Regelunterricht', 'Die Eingaben werden überprüft.');
	var sj = document.getElementById('cms_kurszuordnungls_schuljahr').value;
	var meldung = '<p>Die Kurszuordnungen konnten nicht durchgeführt werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_ganzzahl(sj,0)) {
		meldung += '<li>es wurde kein gültiges Schuljahr gewählt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Kurszuordnung durch Klassen und Regelunterricht', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Kurszuordnung durch Klassen und Regelunterricht', 'Die Kurszuordnungen werden durchgeführt.');

		var formulardaten = new FormData();
		formulardaten.append("sj",  sj);
		formulardaten.append("anfragenziel", 	'377');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Kurszuordnung durch Klassen und Regelunterricht', '<p>Die Kurszuordnungen wurden durchgeführt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_kurszuordnungimport_speichern() {
	cms_laden_an('Kurszuordnung importieren', 'Die Eingaben werden überprüft.');
	var sj = document.getElementById('cms_kurszuordnungimport_schuljahr').value;
	var stufe = document.getElementById('cms_kurszuordnungimport_stufe').value;
	var kurs = document.getElementById('cms_kurszuordnungimport_kurs').value;
	var tutor = document.getElementById('cms_kurszuordnungimport_tutor').value;
	var schueler = document.getElementById('cms_kurszuordnungimport_schueler').value;
	var idart = document.getElementById('cms_kurszuordnungimport_idart').value;

	var csv = document.getElementById('cms_kurszuordnungimport_csv').value;
	var trennung = document.getElementById('cms_kurszuordnungimport_trennung').value;

	var meldung = '<p>Die Kurszuordnung konnten nicht importiert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (csv.length == 0) {
		meldung += '<li>es wurden keine Datensätze eingegeben.</li>';
		fehler = true;
	}

	if (trennung.length == 0) {
		meldung += '<li>es wurde kein Trennsymbol eingegeben.</li>';
		fehler = true;
	}

  var maxspalten = 0;
  if (!fehler) {
    // Inhalte analysieren
    var csvanalyse = csv.split("\n");
    for (var i=0; i<csvanalyse.length; i++) {
      var aktspalten = csvanalyse[i].split(trennung).length;
      if (aktspalten > maxspalten) {maxspalten = aktspalten;}
    }
  }

	if (!cms_check_ganzzahl(sj, 0)) {
		meldung += '<li>die Auswahl für das Schuljahr ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(stufe, 0)) {
		meldung += '<li>die Auswahl für die Stufe ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(kurs, 1, maxspalten)) {
		meldung += '<li>die Auswahl für den Kurs ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(tutor, 1, maxspalten) && (tutor != '-')) {
		meldung += '<li>die Auswahl für den Tutor ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if (!cms_check_ganzzahl(schueler, 1, maxspalten)) {
		meldung += '<li>die Auswahl für die Schüler ist ungültig oder wurde nicht getätigt.</li>';
		fehler = true;
	}

	if ((idart != 'zweit') && (idart != 'dritt') && (idart != 'viert') && (idart != 'sh')) {
		meldung += '<li>die Auswahl für die Vergleichs-ID ist ungültig.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Kurszuordnung importieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
    var formulardaten = new FormData();
    formulardaten.append("csv", csv);
    formulardaten.append("trennung", trennung);
    formulardaten.append("sj", sj);
    formulardaten.append("stufe", stufe);
    formulardaten.append("kurs", kurs);
    formulardaten.append("tutor", tutor);
    formulardaten.append("schueler", schueler);
    formulardaten.append("idart", idart);
    formulardaten.append("anfragenziel", 	'375');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Kurszuordnung importieren', '<p>Die Kurszuordnung wurde erfolgreich importiert.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
      }
      else {
				cms_meldung_an('fehler', 'Kurszuordnung importieren', '<p>Die Importdatei genügt nicht den Anforderungen an das Eingabeformat.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
    }

    cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_nicht_zugeordnet_schueler_laden() {
	var schuelerfeld = document.getElementById('cms_nicht_zugeordnet_schueler_feld');
	schuelerfeld.innerHTML = cms_ladeicon();

	var sj = document.getElementById('cms_nicht_zugeordnet_schuljahr').value;
	var fehler = false;

	if (!cms_check_ganzzahl(sj,0)) {fehler = true;}

	if (fehler) {
		schuelerfeld.innerHTML = '<p class=\"cms_notiz\">-- Beim Laden der Schüler ist ein Fehler aufgetreten. --</p>';
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("sj",  sj);
		formulardaten.append("anfragenziel", 	'379');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<p/) || rueckgabe.match(/^<ul/)) {
				schuelerfeld.innerHTML = rueckgabe;
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}


function cms_nicht_zugeordnet_loeschen_anzeigen() {
	cms_meldung_an('warnung', 'Nicht zugeordnete Schüler löschen', '<p>Achtung! Sie sind im Begriff alle Nutzerkonten und Personeneinträge der Schüler zu löschen, die vorher aufgeführt wurden. Die Personen werden per Mail von der Löschung benachrichtigt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_nicht_zugeordnet_loeschen()">Löschung durchführen</span></p>');
}


function cms_nicht_zugeordnet_loeschen() {
	var personenids = document.getElementById('cms_nicht_zugeordnet_schueler').value;
	if (personenids.length > 0) {
		var personen = (personenids.substr(1)).split('|');
		var personennr = 0;
		var feld = document.getElementById('cms_blende_i');
		var neuemeldung = '<div class="cms_spalte_i">';
		neuemeldung += '<h2 id="cms_laden_ueberschrift">Nicht zugeordnete Schüler löschen</h2>';
		neuemeldung += '<p id="cms_laden_meldung_vorher">Bitte warten ...</p>';
		neuemeldung += '<h4>Gesamtfortschritt</h4>';
		neuemeldung += '<div class="cms_fortschritt_o">';
			neuemeldung += '<div class="cms_fortschritt_i" id="cms_personen_fortschritt_balken" style="width: 0%;"></div>';
		neuemeldung += '</div>';
		neuemeldung += '<p class="cms_hochladen_fortschritt_anzeige">Personen: <span id="cms_personen_fortschritt">0</span>/<span id="cms_personen_alle">'+personen.length+'</span> abgeschlossen</p>';
		neuemeldung += '</div>';
		feld.innerHTML = neuemeldung;
	}

	var formulardaten = new FormData();
	formulardaten.append("id",     		   personen[personennr]);
	formulardaten.append("anfragenziel", '125');

	function anfragennachbehandlung(rueckgabe) {
		personennr++;
		var prozent = (100 * personennr)/personen.length;
		document.getElementById('cms_personen_fortschritt_balken').style.width = prozent+'%';
		document.getElementById('cms_personen_fortschritt').innerHTML = personennr;

		if (personennr < personen.length) {
			var formulardaten = new FormData();
			formulardaten.append("id",     		   personen[personennr]);
			formulardaten.append("anfragenziel", '125');
			cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
		}
		else {
			cms_meldung_an('erfolg', 'Nicht zugeordnete Schüler löschen', '<p>Die Personen wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Personen\');">OK</span></p>');
		}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
