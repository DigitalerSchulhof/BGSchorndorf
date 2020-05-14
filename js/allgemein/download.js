function cms_download (datei) {
	cms_laden_an('Download', 'Der Download wird vorbereitet.');

	var formulardaten = new FormData();
	formulardaten.append("datei", 	datei);
	formulardaten.append("anfragenziel", '15');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "FEHLER") {
			cms_meldung_an('fehler', 'Download', '<p>Die angeforderte Datei existiert nicht.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else if (rueckgabe == "ERFOLG"){
			cms_link('php/oeffentlich/seiten/download/downloadwebsite.php');
			cms_meldung_aus();
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
