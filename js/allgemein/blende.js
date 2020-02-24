/* LADEN EINBLENDEN - titel wird als Überschrift angezeigt, taetigkeit dient der näheren Beschreibung */
function cms_laden_an (titel, taetigkeit) {
	var blende = '<div class="cms_spalte_i">';
		blende += '<h2 id="cms_laden_ueberschrift">'+titel+'</h2>';
		blende += '<p id="cms_laden_meldung_vorher">Bitte warten...</p>';
		blende += cms_ladeicon();
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
		var aus = setTimeout(function() {cms_meldung_aus ();}, 3000);
	}

}

function cms_meldung_notfall (art, titel, text, nachher) {
	var blende = '<div class="cms_spalte_i">';
		blende += '<div class="cms_neuigkeit_notfall">';
			blende += '<span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/alarm.png\"></span>';
			blende += '<span class=\"cms_neuigkeit_inhalt\"><h4>'+titel+'</h4>';
			blende += text;
		blende += '</span></div>';
		blende += nachher;
	blende += '</div>';

	document.getElementById('cms_blende_i').innerHTML = blende;
	cms_einblenden('cms_blende_o');

	if ((art == 'erfolg') && (nachher.match(/onclick=\"cms_meldung_aus/))) {
		var aus = setTimeout(function() {cms_meldung_aus ();}, 3000);
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

function cms_meldung_berechtigung_code() {
	if (CMS_BENUTZERART == "s") {
        meldung = '<p>Du bist nicht berechtigt, diese Aktion auszuführen!</p>';
    }
    else {
        meldung = '<p>Sie sind nicht berechtigt, diese Aktion auszuführen!</p>';
    }
	return cms_meldung_code('fehler', 'Zugriff verweigert!', meldung);
}

function cms_meldung_firewall_code() {
	meldung = '<p>Aus Diesem Netz kein kein Zugriff auf diese Daten erfolgen.</p>';
	return cms_meldung_code('firewall', 'Zugriff verweigert!', meldung);
}

function cms_meldung_fehler_code() {
	return cms_meldung_code('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!</p>');
}

function cms_meldung_bastler_code() {
	return cms_meldung_code('warnung', 'Unvollständige Informationen', '<p>Diese Seite wurde nicht über einen Link im System aufgerufen, sondern durch direkte Eingabe. Daher sind nicht genügend Informationen vorhanden, um sie anzuzeigen. Aus Sicherheits- und Datenschutzgründen enthalten Links nicht alle Informationen, um Seiten mit sensiblen Informationen zu öffnen.</p><p>Bitte verwenden Sie die Benutzeroberfläche, um die gewünschten Seiten aufzurufen.</p>');
}

function cms_meldung_laden(text) {
	text = text || '';
	var ergebnis = '<div class=\"cms_meldung_laden\">'+cms_ladeicon();
	if (text.length > 0) {ergebnis += '<p>'+text+'</p>';}
	ergebnis += '</div>';
	return ergebnis;
}

function cms_meldung_fehler_code() {
	return cms_meldung_code('fehler', 'Unbekannter Fehler', '<p>Es ist ein unbekannter Fehler aufgetreten. Bitte den Administrator mit einem detailierten Bericht benachrichtigen, damit dieser Fehler behoben werden kann!</p>');
}

function cms_meldung_keinkonto() {
	cms_meldung_an('info', 'Kein Nutzerkonto', '<p>Die gewählte Person verfügt über kein Nutzerkonto.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
}

function cms_meldung_nichtschreiben() {
	cms_meldung_an('info', 'Schreiben nicht möglich', '<p>Die gewählte Person verfügt über ein Nutzerkonto, ihr kann aber nicht geschrieben werden.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
}

/* MELDUNG AUSBLENDEN */
function cms_meldung_aus () {
	cms_ausblenden('cms_blende_o');
}

function cms_dsgvo_datenschutz(fenster, eA, eB) {

	var fehler = false;
	if ((fenster != '-') && (fenster != 'j') && (fenster != 'n')) {fehler = true;}
	if ((eA != '-') && (eA != 'j') && (eA != 'n')) {fehler = true;}
	if ((eB != '-') && (eB != 'j') && (eB != 'n')) {fehler = true;}

	if (!fehler) {
		// Versuch, Person in der Datenbank zu finden
		var anfrage = new XMLHttpRequest();
		var formulardaten = new FormData();
		formulardaten.append("anfragenziel", 	'0');
		formulardaten.append("fenster", 	fenster);
		formulardaten.append("einwilligungA", 	eA);
		formulardaten.append("einwilligungB", 	eB);
		anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
		anfrage.send(formulardaten);

		var anzeige = document.getElementById('cms_dsgvo_datenschutz');
		if ((anzeige) && (fenster == 'j')) {
			anzeige.parentNode.removeChild(anzeige);
		}

		if (eA == 'j') {
			var eAfeld = document.getElementById('cms_datenschutz_einwilligungA');
			if (eAfeld) {
				eAfeld.innerHTML = "Einwilligung A erteilt";
				eAfeld.className = "cms_datenschutz_einwilligungerteilt";
				eAfeld.onclick = function() {cms_dsgvo_datenschutz('-', 'n', '-');};
			}

			document.getElementById('cms_geraetewahl').innerHTML = "Anzeige optimieren für: <a href=\"javascript:cms_geraet_aendern('P');\">Computer</a>, <a href=\"javascript:cms_geraet_aendern('T');\">Tablets</a> oder <a href=\"javascript:cms_geraet_aendern('H');\">Smartphones</a>.";
		}
		if (eA == 'n') {
			var eAfeld = document.getElementById('cms_datenschutz_einwilligungA');
			if (eAfeld) {
				eAfeld.innerHTML = "Einwilligung A verweigert";
				eAfeld.className = "cms_datenschutz_einwilligungverweigert";
				eAfeld.onclick = function() {cms_dsgvo_datenschutz('-', 'j', '-');};
			}

			document.getElementById('cms_geraetewahl').innerHTML = "";
		}

		if (eB == 'j') {
			var eBfeld = document.getElementById('cms_datenschutz_einwilligungB');
			if (eBfeld) {
				eBfeld.innerHTML = "Einwilligung B erteilt";
				eBfeld.className = "cms_datenschutz_einwilligungerteilt";
				eBfeld.onclick = function() {cms_dsgvo_datenschutz('-', '-', 'n');};
			}
		}
		if (eB == 'n') {
			var eBfeld = document.getElementById('cms_datenschutz_einwilligungB');
			if (eBfeld) {
				eBfeld.innerHTML = "Einwilligung B verweigert";
				eBfeld.className = "cms_datenschutz_einwilligungverweigert";
				eBfeld.onclick = function() {cms_dsgvo_datenschutz('-', '-', 'j');};
			}
		}

		var dsgvo_a = document.getElementsByClassName('cms_himweis_dsgvo_a').length;
		var dsgvo_b = document.getElementsByClassName('cms_himweis_dsgvo_b').length;
		if ((dsgvo_a && eA == 'j') ||(dsgvo_b && eB == 'j')) {location.reload();}
	}
}


function cms_vollbild(id) {
	var feld = document.getElementById(id);
	if (feld) {
		if (cms_check_toggle(feld.value)) {
			if (feld.value == 1) {cms_klasse_dazu(id+'_F', 'cms_vollbild');}
			else {cms_klasse_weg(id+'_F', 'cms_vollbild');}
		}
	}
}

function cms_aktionsschicht_ein(kid) {
	cms_aktionsschicht_aus();
	var feld = document.getElementById(kid);
	if (feld) {
		// Sichtbar machen
		feld.style.display = 'block';
		document.getElementById('cms_aktionsschicht_o').style.display = 'block';
	}
}

function cms_aktionsschicht_aus() {
	var aschichten = document.getElementById('cms_aktionsschicht_ids').value;
	if (aschichten.length > 0) {
		aschichten = aschichten.substr(1).split(',');
		for (var i=0; i<aschichten.length; i++) {
			document.getElementById(aschichten[i]).style.display = 'none';
		}
	}
	document.getElementById('cms_aktionsschicht_o').style.display = 'none';
}
