function cms_appanmeldung(art, benutzer, passwort) {
  var code = "";
  var fehler = false;
  if (art != 'VPLAN') {
    code = cms_meldung_code ('fehler', 'Unbekannter Dienst', '<p>Der angeforderte Dienst wird nicht angeboten.</p>');
    document.getElementById('cms_appinhalt').innerHTML = code;
    fehler = true;
  }
  else if ((benutzer.legth == 0) || (passwort.length == 0)) {
    code = cms_meldung_code ('info', 'Falsche Zugangsdaten', '<p>Die angegebenen Zugangsdaten sind nicht korrekt und müssen in den Profileinstellungen angepasst werden.</p>');
    document.getElementById('cms_appinhalt').innerHTML = code;
    fehler = true;
  }

  if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("art", art);
		formulardaten.append("benutzer", benutzer);
		formulardaten.append("passwort", passwort);
		formulardaten.append("anfragenziel", 	'168');

		function anfragennachbehandlung(rueckgabe) {
			document.getElementById('cms_appinhalt').innerHTML = rueckgabe;
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
