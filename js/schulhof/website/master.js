function cms_website_master_bearbeiten() {
	cms_laden_an('Master bearbeiten', 'Die Eingaben werden überprüft.');

	var texte = document.getElementsByClassName('note-editable');
	var fuss = texte[0].innerHTML;
	var anmelden = texte[1].innerHTML;

	var formulardaten = new FormData();
	formulardaten.append("fuss", fuss);
	formulardaten.append("anmelden", anmelden);
	formulardaten.append("anfragenziel", '396');

	cms_laden_an('Master bearbeiten', 'Neue Masterelemente werden erzeugt.');
	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Master bearbeiten', '<p>Die neuen Masterelemente wurden angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Masterelemente_bearbeiten\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);

}
