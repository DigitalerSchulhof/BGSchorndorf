/* LADEN EINBLENDEN - titel wird als Überschrift angezeigt, taetigkeit dient der näheren Beschreibung */
function cms_laden_an (titel, taetigkeit) {
	var blende = '<div class="cms_spalte_i">';
		blende += '<h2 id="cms_laden_ueberschrift">'+titel+'</h2>';
		blende += '<p id="cms_laden_meldung_vorher">Bitte warten...</p>';
		blende += '<img class="cms_laden" src="res/laden/standard.gif">';
		blende += '<p id="cms_laden_meldung_nachher">'+taetigkeit+'</p>';
	blende += '</div>';

	document.getElementById('cms_blende_i').innerHTML = blende;
	cms_einblenden('cms_blende_o');
}

/* LADEN AUSBLENDEN */
function cms_laden_aus () {
	cms_ausblenden('cms_blende_o');
}

/* MELDUNG EINBLENDEN */
function cms_meldung_an (art, titel, text, nachher) {
	var blende = '<div class="cms_spalte_i">';
		blende += '<div class="cms_meldung cms_meldung_'+art+'">';
			blende += '<h4>'+titel+'</h4>';
			blende += text;
		blende += '</div>';
		blende += nachher;
	blende += '</div>';

	document.getElementById('cms_blende_i').innerHTML = blende;
	cms_einblenden('cms_blende_o');

	if ((art == 'erfolg') && (nachher.match(/onclick=\"cms_meldung_aus/))) {
		var aus = setTimeout(function() {cms_meldung_aus ();}, 1000);
	}

}

function cms_meldung_code (art, titel, text) {
	var code = '<div class="cms_meldung cms_meldung_'+art+'">';
			code += '<h4>'+titel+'</h4>';
			code += text;
		code += '</div>';

	return code;
}

function cms_meldung_berechtigung() {
	if (CMS_BENUTZERART == "s") {
        meldung = '<p>Du bist nicht berechtigt, diese Aktion auszuführen!</p>';
    }
    else {
        meldung = '<p>Sie sind nicht berechtigt, diese Aktion auszuführen!</p>';
    }
	cms_meldung_an('fehler', 'Zugriff verweigert!', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
}

function cms_meldung_firewall() {
	meldung = '<p>Aus Diesem Netz kein kein Zugriff auf diese Daten erfolgen.</p>';
	cms_meldung_an('firewall', 'Zugriff verweigert!', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
}

function cms_meldung_fehler() {
	cms_meldung_an('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
}

function cms_meldung_bastler() {
	cms_meldung_an('warnung', 'Unvollständige Informationen', '<p>Diese Seite wurde nicht über einen Link im System aufgerufen, sondern durch direkte Eingabe. Daher sind nicht genügend Informationen vorhanden, um sie anzuzeigen. Aus Sicherheits- und Datenschutzgründen enthalten Links nicht alle Informationen, um Seiten mit sensiblen Informationen zu öffnen.</p><p>Bitte verwenden Sie die Benutzeroberfläche, um die gewünschten Seiten aufzurufen.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
}

function cms_meldung_laden(text) {
	text = text || '';
	var ergebnis = '<div class=\"cms_meldung_laden\"><p><img src="res/laden/standard.gif"></p>';
	if (text.length > 0) {ergebnis += '<p>'+text+'</p>';}
	ergebnis += '</div>';
	return ergebnis;
}

function cms_meldung_fehler_code() {
	return cms_meldung_code('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!</p>');
}

/* MELDUNG AUSBLENDEN */
function cms_meldung_aus () {
	cms_ausblenden('cms_blende_o');
}

function cms_dsgvo_datenschutz() {
	var anzeige = document.getElementById('cms_dsgvo_datenschutz');
	// Versuch, Person in der Datenbank zu finden
	var anfrage = new XMLHttpRequest();
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'0');
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);

	anzeige.parentNode.removeChild(anzeige);
	document.getElementById('cms_geraetewahl').innerHTML = "Anzeige optimieren für: <a href=\"javascript:cms_geraet_aendern('P');\">Computer</a>, <a href=\"javascript:cms_geraet_aendern('T');\">Tablets</a> oder <a href=\"javascript:cms_geraet_aendern('H');\">Smartphones</a>.";
}


function cms_vollbild_oeffnen(id) {
	feld = document.getElementById(id);
	feld.style.display = 'block';
	feld.style.position = 'fixed';
	feld.style.width = '100%';
	feld.style.height = '100%';
	feld.style.paddingBottom = '15px';
	feld.style.left = '0px';
	feld.style.top = '0px';
	feld.style.zIndex = '20';
	feld.style.overflow = 'scroll';
	button = document.getElementById(id+'_schliessen');
	button.style.display = "block";
	button = document.getElementById(id+'_oeffnen');
	button.style.display = "none";
}

function cms_vollbild_schliessen (id) {
	feld = document.getElementById(id);
	feld.style.display = '';
	feld.style.position = '';
	feld.style.width = '';
	feld.style.height = '';
	feld.style.paddingBottom = '';
	feld.style.left = '';
	feld.style.top = '';
	feld.style.zIndex = '';
	feld.style.overflow = '';
	button = document.getElementById(id+'_schliessen');
	button.style.display = "none";
	button = document.getElementById(id+'_oeffnen');
	button.style.display = "inline-block";
}
