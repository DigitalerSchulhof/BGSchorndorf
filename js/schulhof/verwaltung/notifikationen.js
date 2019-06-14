function cms_notifikationen_loeschen() {
	cms_laden_an('Notifikationen schließen', 'Notifikationen werden geschlossen');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'119');

	function anfragennachbehandlung(rueckgabe) {if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Nutzerkonto');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_neuigkeit_schliessen(id) {
	cms_laden_an('Notifikation schließen', 'Notifikation wird geschlossen');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'118');
	formulardaten.append("id", 	id);

	function anfragennachbehandlung(rueckgabe) {if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Nutzerkonto');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
