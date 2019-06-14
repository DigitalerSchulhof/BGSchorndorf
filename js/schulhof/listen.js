function cms_listen_klassenwahl(id) {
  cms_laden_an('Klassenliste aktualisieren', 'Die Klassenliste wird aktualisiert');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append('anfragenziel', '202');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Listen/Klassen_und_Kurse');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_listen_stufenwahl(id) {
  cms_laden_an('Stufenliste aktualisieren', 'Die Stufenliste wird aktualisiert');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append('anfragenziel', '203');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Listen/Stufen');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
