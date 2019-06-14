function cms_schulhof_website_hauptnavigationen_bearbeiten() {
	cms_laden_an('Hauptnavigationen bearbeiten', 'Die Eingaben werden überprüft.');
	var h_ebene = document.getElementById('cms_navigation_hauptnavigation_h_ebene').value;
	var h_ebenenzusatze = document.getElementById('cms_navigation_hauptnavigation_h_ebenenzusatz_e').value;
	var h_ebenenzusatzs = document.getElementById('cms_navigation_hauptnavigation_h_ebenenzusatz_s').value;
	var h_tiefe = document.getElementById('cms_navigation_hauptnavigation_h_tiefe').value;
	var s_ebene = document.getElementById('cms_navigation_hauptnavigation_s_ebene').value;
	var s_ebenenzusatze = document.getElementById('cms_navigation_hauptnavigation_s_ebenenzusatz_e').value;
	var s_ebenenzusatzs = document.getElementById('cms_navigation_hauptnavigation_s_ebenenzusatz_s').value;
	var s_tiefe = document.getElementById('cms_navigation_hauptnavigation_s_tiefe').value;
	var f_ebene = document.getElementById('cms_navigation_hauptnavigation_f_ebene').value;
	var f_ebenenzusatze = document.getElementById('cms_navigation_hauptnavigation_f_ebenenzusatz_e').value;
	var f_ebenenzusatzs = document.getElementById('cms_navigation_hauptnavigation_f_ebenenzusatz_s').value;
	var f_tiefe = document.getElementById('cms_navigation_hauptnavigation_f_tiefe').value;

	var meldung = '<p>Die Hauptnavigationen konnten nicht bearbeitet werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichtiengaben prüfen
	// HAUPTNAVIGATION
	if ((h_ebene != 'd') && (h_ebene != 'u') && (h_ebene != 's') && (h_ebene != 'e')) {
		meldung += '<li>bei der Kopfzeilennavigation wurde keine gültige Ebene ausgewählt.</li>';
		fehler = true;
	}

	if (h_ebene == 'e') {
		var h_enrfehler = false;
		if (!Number.isInteger(parseInt(h_ebenenzusatze))) {h_enrfehler = true;}
		else if (h_ebenenzusatze < 0) {h_enrfehler = true;}
		if (h_enrfehler) {
			meldung += '<li>bei der Kopfzeilennavigation wurde keine gültige Ebenennummer ausgewählt.</li>';
			fehler = true;
		}
	}

	if (h_ebene == 's') {
		var h_snrfehler = false;
		if (!Number.isInteger(parseInt(h_ebenenzusatzs))) {h_snrfehler = true;}
		else if (h_ebenenzusatzs < 0) {h_snrfehler = true;}
		if (h_snrfehler) {
			meldung += '<li>bei der Kopfzeilennavigation wurde keine gültige Seite ausgewählt.</li>';
			fehler = true;
		}
	}

	if ((h_tiefe != '0') && (h_tiefe != '1') && (h_tiefe != '2') && (h_tiefe != '3') && (h_tiefe != '4')) {
		meldung += '<li>bei der Kopfzeilennavigation wurde keine gültige maximale Tiefe ausgewählt.</li>';
		fehler = true;
	}


	// SIDEBAR
	if ((s_ebene != 'd') && (s_ebene != 'u') && (s_ebene != 's') && (s_ebene != 'e')) {
		meldung += '<li>bei der Sidebarnavigation wurde keine gültige Ebene ausgewählt.</li>';
		fehler = true;
	}

	if (s_ebene == 'e') {
		var s_enrfehler = false;
		if (!Number.isInteger(parseInt(s_ebenenzusatze))) {s_enrfehler = true;}
		else if (s_ebenenzusatze < 0) {s_enrfehler = true;}
		if (s_enrfehler) {
			meldung += '<li>bei der Sidebarnavigation wurde keine gültige Ebenennummer ausgewählt.</li>';
			fehler = true;
		}
	}

	if (s_ebene == 's') {
		var s_snrfehler = false;
		if (!Number.isInteger(parseInt(s_ebenenzusatzs))) {s_snrfehler = true;}
		else if (s_ebenenzusatzs < 0) {s_snrfehler = true;}
		if (s_snrfehler) {
			meldung += '<li>bei der Sidebarnavigation wurde keine gültige Seite ausgewählt.</li>';
			fehler = true;
		}
	}

	if ((s_tiefe != '0') && (s_tiefe != '1') && (s_tiefe != '2') && (s_tiefe != '3') && (s_tiefe != '4')) {
		meldung += '<li>bei der Sidebarnavigation wurde keine gültige maximale Tiefe ausgewählt.</li>';
		fehler = true;
	}


	// FUSSZEILE
	if ((f_ebene != 'd') && (f_ebene != 'u') && (f_ebene != 's') && (f_ebene != 'e')) {
		meldung += '<li>bei der Fußzeilennavigation wurde keine gültige Ebene ausgewählt.</li>';
		fehler = true;
	}

	if (f_ebene == 'e') {
		var f_enrfehler = false;
		if (!Number.isInteger(parseInt(f_ebenenzusatze))) {f_enrfehler = true;}
		else if (f_ebenenzusatze < 0) {f_enrfehler = true;}
		if (f_enrfehler) {
			meldung += '<li>bei der Fußzeilennavigation wurde keine gültige Ebenennummer ausgewählt.</li>';
			fehler = true;
		}
	}

	if (f_ebene == 's') {
		var f_snrfehler = false;
		if (!Number.isInteger(parseInt(f_ebenenzusatzs))) {f_snrfehler = true;}
		else if (s_ebenenzusatzs < 0) {f_snrfehler = true;}
		if (f_snrfehler) {
			meldung += '<li>bei der Fußzeilennavigation wurde keine gültige Seite ausgewählt.</li>';
			fehler = true;
		}
	}

	if ((f_tiefe != '0') && (f_tiefe != '1') && (f_tiefe != '2') && (f_tiefe != '3') && (f_tiefe != '4')) {
		meldung += '<li>bei der Fußzeilennavigation wurde keine gültige maximale Tiefe ausgewählt.</li>';
		fehler = true;
	}


	if (fehler) {
		cms_meldung_an('fehler', 'Hauptnavigationen bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Hauptnavigationen bearbeiten', 'Die Hauptnavigationen werden bearbeitet.');
		var formulardaten = new FormData();
		formulardaten.append("h_ebene", h_ebene);
		formulardaten.append("h_ebenenzusatze", h_ebenenzusatze);
		formulardaten.append("h_ebenenzusatzs", h_ebenenzusatzs);
		formulardaten.append("h_tiefe", h_tiefe);
		formulardaten.append("s_ebene", s_ebene);
		formulardaten.append("s_ebenenzusatze", s_ebenenzusatze);
		formulardaten.append("s_ebenenzusatzs", s_ebenenzusatzs);
		formulardaten.append("s_tiefe", s_tiefe);
		formulardaten.append("f_ebene", f_ebene);
		formulardaten.append("f_ebenenzusatze", f_ebenenzusatze);
		formulardaten.append("f_ebenenzusatzs", f_ebenenzusatzs);
		formulardaten.append("f_tiefe", f_tiefe);
		formulardaten.append("anfragenziel", 	'233');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Hauptnavigationen bearbeiten', '<p>Die Hauptnavigationen  wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Hauptnavigationen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_navigation_ebenenwechsel(id) {
	var ebene = document.getElementById(id+'_ebene').value;
	if (ebene == 'e') {
		document.getElementById(id+'_ebenenzusatz_eF').style.display = 'table-row';
		document.getElementById(id+'_ebenenzusatz_sF').style.display = 'none';
		document.getElementById(id+'_tiefeF').style.display = 'table-row';
	}
	else if (ebene == 's') {
		document.getElementById(id+'_ebenenzusatz_eF').style.display = 'none';
		document.getElementById(id+'_ebenenzusatz_sF').style.display = 'table-row';
		document.getElementById(id+'_tiefeF').style.display = 'table-row';
	}
	else {
		document.getElementById(id+'_ebenenzusatz_eF').style.display = 'none';
		document.getElementById(id+'_ebenenzusatz_sF').style.display = 'none';
		document.getElementById(id+'_tiefeF').style.display = 'none';
	}
}
