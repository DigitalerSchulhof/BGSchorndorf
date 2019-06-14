/* ROLLE WIRD GESPEICHERT */
function cms_schulhof_verwaltung_zulaessig() {
	cms_laden_an('Zulässige Dateien', 'Die Eingaben werden überprüft.');
	var groesse = parseInt(document.getElementById('cms_schulhof_zulaessig_groesse').value);
	var einheit = document.getElementById('cms_schulhof_zulaessig_einheit').value;
	var max = document.getElementById('cms_schulhof_zulaessig_max').value;

	var meldung = '<p>Die Zulässigen Dateien konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if ((!Number.isInteger(groesse)) || (groesse < 1)) {
		meldung += '<li>die maximale Dateigröße ist keine Zahl.</li>';
		fehler = true;
	}

	if ((einheit != "B") && (einheit != "KB") && (einheit != "MB") && (einheit != "GB") && (einheit != "TB") && (einheit != "PB") && (einheit != "EB")) {
		meldung += '<li>es wurde eine ungültige Einehit angegeben.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Zulässige Dateien', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Zulässige Dateien', 'Die Änderungen werden übernommen.');

		var formulardaten = new FormData();
		formulardaten.append("groesse",    	groesse);
		formulardaten.append("einheit", 	einheit);
		formulardaten.append("max", 		max);
		formulardaten.append("anfragenziel", 	'178');
		for (i=0; i<=max; i++) {formulardaten.append('endung'+i, document.getElementById('cms_schulhof_zulaessig_'+i).value);}

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Zulässige Dateien', '<p>Die Einstellungen für die zulässige Dateien wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Zulässige_Dateien\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
