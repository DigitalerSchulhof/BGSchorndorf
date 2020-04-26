function cms_ebestand_wechsel() {
  var wert = document.getElementById('cms_ebedarf_bestehend').value;
  if (wert == '1') {
    cms_ausblenden('cms_eBedarf0');
    cms_einblenden('cms_eBedarf1', 'table-row');
    cms_einblenden('cms_eBedarf2', 'table-row');
    cms_einblenden('cms_eBedarf3', 'table-row');
    cms_einblenden('cms_eBedarf4', 'table-row');
    cms_einblenden('cms_eBedarf5', 'table-row');
  }
  else if (wert == '2') {
    cms_einblenden('cms_eBedarf0', 'table-row');
    cms_ausblenden('cms_eBedarf1');
    cms_einblenden('cms_eBedarf2', 'table-row');
    cms_einblenden('cms_eBedarf3', 'table-row');
    cms_einblenden('cms_eBedarf4', 'table-row');
    cms_einblenden('cms_eBedarf5', 'table-row');
  }
  else {
    cms_ausblenden('cms_eBedarf0');
    cms_ausblenden('cms_eBedarf1');
    cms_ausblenden('cms_eBedarf2');
    cms_ausblenden('cms_eBedarf3');
    cms_ausblenden('cms_eBedarf4');
    cms_ausblenden('cms_eBedarf5');
  }
}

function cms_ebedarf_speichern() {
	cms_laden_an('Bedarf speichern', 'Die Angaben werden geprüft');

	var bedarf = document.getElementById('cms_ebedarf_bestehend').value;
	var preis = document.getElementById('cms_ebedarf_preis').value;
	var telefon1 = document.getElementById('cms_schulhof_ebedarf_telefon').value;
	var telefon2 = document.getElementById('cms_schulhof_ebedarf_telefon_wiederholen').value;
	var mail1 = document.getElementById('cms_schulhof_ebedarf_mail').value;
	var mail2 = document.getElementById('cms_schulhof_ebedarf_mail_wiederholen').value;

	var meldung = '<p>Die Angeben konnten nicht gespeichert werden, denn</p><ul>';
	var fehler = false;

  if ((bedarf != '0') && (bedarf != '1') && (bedarf != '2')) {
    meldung += '<li>die Eingabe für den Bedarf ist ungültig.</li>';
		fehler = true;
  }

  if ((bedarf == '1') || (bedarf == '2')) {
    if (telefon1.length < 4) {
  		meldung += '<li>die Telefonnummer ist ungültig.</li>';
  		fehler = true;
  	}

  	if (telefon1 != telefon2) {
  		meldung += '<li>die Telefonnummern sind nicht identisch.</li>';
  		fehler = true;
  	}

    if (!cms_check_mail(mail1)) {
  		meldung += '<li>die eMailadresse ist ungültig.</li>';
  		fehler = true;
  	}

  	if (mail1 != mail2) {
  		meldung += '<li>die eMailadressen sind nicht identisch.</li>';
  		fehler = true;
  	}
  }

  if (bedarf == '1') {
    if (!cms_check_ganzzahl(preis, 1)) {
  		meldung += '<li>die Telefonnummer ist ungültig.</li>';
  		fehler = true;
  	}
  }


	if (fehler) {
		cms_meldung_an('fehler', 'Bedarf speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();
		formulardaten.append("bedarf",  bedarf);
		formulardaten.append("preis",     preis);
		formulardaten.append("telefon1",   telefon1);
		formulardaten.append("telefon2",   telefon2);
		formulardaten.append("mail1",   mail1);
		formulardaten.append("mail2",   mail2);
		formulardaten.append("anfragenziel", 	'394');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Bedarf speichern', '<p>Die Angaben wurden übermittelt. Vielen Dank!</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto/Bedarfsabfrage\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
