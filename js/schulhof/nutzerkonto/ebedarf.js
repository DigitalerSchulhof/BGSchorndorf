function cms_ebestellung_aktualisieren() {
  var wert = document.getElementById('cms_ebestellung_bedarf').value;

  if (wert == '1') {
    cms_einblenden('cms_ebestellung_geraete');
    cms_einblenden('cms_ebestellung_kontakt');
    cms_einblenden('cms_ebestellung_bedingung', 'table-row');
    cms_einblenden('cms_ebestellung_bedingung_akzept', 'table-row');
    document.getElementById('cms_ebestellung_speichern').innerHTML = "Zahlungspflichtig bestellen";
    cms_ebestellung_neuberechnen();
  }
  else if (wert == '2') {
    cms_ausblenden('cms_ebestellung_geraete');
    cms_einblenden('cms_ebestellung_kontakt');
    cms_ausblenden('cms_ebestellung_bedingung');
    cms_ausblenden('cms_ebestellung_bedingung_akzept');
    document.getElementById('cms_ebestellung_speichern').innerHTML = "Leihe beantragen";
    cms_ebestellung_neuberechnen();
  }
  else {
    cms_ausblenden('cms_ebestellung_geraete');
    cms_ausblenden('cms_ebestellung_kontakt');
    cms_ausblenden('cms_ebestellung_bedingung');
    cms_ausblenden('cms_ebestellung_bedingung_akzept');
    document.getElementById('cms_ebestellung_speichern').innerHTML = "Keinen Bedarf anzeigen";
    cms_ebestellung_neuberechnen();
  }
}

function cms_ebestellung_neuberechnen() {
  // Laden anschalten
  document.getElementById('cms_bestellzusammenfassung').innerHTML = '<td colspan="4" class=\"cms_zentriert\">'+cms_ladeicon()+'</td>';
  // Werte auslesen
  var bedarf = document.getElementById('cms_ebestellung_bedarf').value;
  var geraeteids = document.getElementById('cms_bestellen_geraeteids').value;
  var formulardaten = new FormData();

  var fehler = false;

  if (bedarf != '1') {
    if (geraeteids.length > 0) {
      if (cms_check_idfeld(geraeteids)) {
        geraetids = geraeteids.substr(1);
        gids = geraetids.split('|');
        for (var i=0; i<gids.length; i++) {
          if (document.getElementById('cms_ebestellung_geraet_'+gids[i])) {
            document.getElementById('cms_ebestellung_geraet_'+gids[i]).value='0';
          }
          else {fehler = true;}
        }
      }
      else {fehler = true;}
    }
  }

  if (bedarf == '1') {
    if (geraeteids.length > 0) {
      if (cms_check_idfeld(geraeteids)) {
        geraetids = geraeteids.substr(1);
        gids = geraetids.split('|');
        for (var i=0; i<gids.length; i++) {
          if (document.getElementById('cms_ebestellung_geraet_'+gids[i])) {
            var wert = document.getElementById('cms_ebestellung_geraet_'+gids[i]).value;
            formulardaten.append('geraet'+gids[i], wert);
            if (!cms_check_ganzzahl(wert, 0,5)) {
              fehler = true;
            }
          }
          else {fehler = true;}
        }
      }
      else {fehler = true;}
    }
  }

  if (fehler) {
    document.getElementById('cms_bestellzusammenfassung').innerHTML = '<td colspan="4" class=\"cms_zentriert cms_notiz\">Ein Fehler ist aufgetreten. Bitte überprüfen Sie Ihre Eingaben.</td>';
  }
  else {
    formulardaten.append("bedarf",  bedarf);
    formulardaten.append("geraeteids",   geraeteids);
    formulardaten.append("anfragenziel", 	'397');

    function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<tr>/)) {
        document.getElementById('cms_bestellzusammenfassung').innerHTML = rueckgabe;
			}
			else {
				document.getElementById('cms_bestellzusammenfassung').innerHTML = rueckgabe;//'<td colspan="4" class=\"cms_zentriert cms_notiz\">Ein Fehler ist aufgetreten. Bitte überprüfen Sie Ihre Eingaben.</td>';
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }

}



function cms_ebestellung_speichern() {
	cms_laden_an('Bedarf speichern', 'Die Angaben werden geprüft');

	var bedarf = document.getElementById('cms_ebestellung_bedarf').value;

	var anrede = document.getElementById('cms_ebestellung_anrede').value;
	var vorname = document.getElementById('cms_ebestellung_vorname').value;
	var nachname = document.getElementById('cms_ebestellung_nachname').value;
	var strasse = document.getElementById('cms_ebestellung_strasse').value;
	var hausnr = document.getElementById('cms_ebestellung_hausnr').value;
	var plz = document.getElementById('cms_ebestellung_plz').value;
	var ort = document.getElementById('cms_ebestellung_ort').value;

	var telefon1 = document.getElementById('cms_schulhof_ebestellung_telefon').value;
	var telefon2 = document.getElementById('cms_schulhof_ebestellung_telefon_wiederholen').value;
	var mail1 = document.getElementById('cms_schulhof_ebestellung_mail').value;
	var mail2 = document.getElementById('cms_schulhof_ebestellung_mail_wiederholen').value;
	var bedingungen = document.getElementById('cms_bedingungen').value;
	var geraeteids = document.getElementById('cms_bestellen_geraeteids').value;
  var formulardaten = new FormData();

	var meldung = '<p>Die Angeben konnten nicht gespeichert werden, denn</p><ul>';
	var fehler = false;

  if ((bedarf != '0') && (bedarf != '1') && (bedarf != '2')) {
    meldung += '<li>die Eingabe für den Bedarf ist ungültig.</li>';
		fehler = true;
  }

  if ((bedarf == '1') || (bedarf == '2')) {
    if ((anrede != '-') && (anrede != 'Frau') && (anrede != 'Herr')) {
  		meldung += '<li>die Anrede ist ungültig.</li>';
  		fehler = true;
  	}

    if (vorname.length < 1) {
  		meldung += '<li>der Vorname ist ungültig.</li>';
  		fehler = true;
  	}

    if (nachname.length < 1) {
  		meldung += '<li>der Nachname ist ungültig.</li>';
  		fehler = true;
  	}

    if (strasse.length < 1) {
  		meldung += '<li>die Straße ist ungültig.</li>';
  		fehler = true;
  	}

    if (hausnr.length < 1) {
  		meldung += '<li>die Hausnummer ist ungültig.</li>';
  		fehler = true;
  	}

    if (!cms_check_ganzzahl(plz, 0, 99999)) {
  		meldung += '<li>die Postleitzahl ist ungültig.</li>';
  		fehler = true;
  	}

    if (ort.length < 1) {
  		meldung += '<li>der Ort ist ungültig.</li>';
  		fehler = true;
  	}

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
    if (bedingungen != '1') {
  		meldung += '<li>die Bestellbedingungen müssen akzeptiert werden.</li>';
  		fehler = true;
  	}

    if (geraeteids.length > 0) {
      var geraetefehler = false;
      if (!cms_check_idfeld(geraeteids)) {
        meldung += '<li>die Geräteids sind ungültig.</li>';
    		fehler = true;
      }
      else {
        geraetids = geraeteids.substr(1);
        gids = geraetids.split('|');
        for (var i=0; i<gids.length; i++) {
          if (document.getElementById('cms_ebestellung_geraet_'+gids[i])) {
            var wert = document.getElementById('cms_ebestellung_geraet_'+gids[i]).value;
            formulardaten.append('geraet'+gids[i], wert);
            if (!cms_check_ganzzahl(wert, 0,5)) {
              geraetefehler = true;
            }
          }
        }
      }
      if (geraetefehler) {
        meldung += '<li>die Bestellmenge pro Gerät muss zwischen 0 und 5 liegen. Dieser Wert stimmt bei mindestens einem Gerät nicht.</li>';
        fehler = true;
      }
    }
  }


	if (fehler) {
		cms_meldung_an('fehler', 'Bedarf speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		formulardaten.append("bedarf",  bedarf);
		formulardaten.append("anrede",     anrede);
		formulardaten.append("vorname",     vorname);
		formulardaten.append("nachname",     nachname);
		formulardaten.append("strasse",     strasse);
		formulardaten.append("hausnr",     hausnr);
		formulardaten.append("plz",     plz);
		formulardaten.append("ort",     ort);
		formulardaten.append("bedingungen",     bedingungen);
		formulardaten.append("telefon1",   telefon1);
		formulardaten.append("telefon2",   telefon2);
		formulardaten.append("mail1",   mail1);
		formulardaten.append("mail2",   mail2);
		formulardaten.append("geraeteids",   geraeteids);
		formulardaten.append("anfragenziel", 	'394');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Bestellung speichern', '<p>Die Bestellung/Leihe wurde übermittelt. Vielen Dank!</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto\');">OK</span></p>');
			}
			else if (rueckgabe == "VERFUEGBAR") {
				cms_meldung_an('fehler', 'Bestellung speichern', '<p>Die Bestellung konnte nicht gespeichert werden, da mindestens eines der gewählten Geräte nicht mehr verfügbar ist.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
			else if (rueckgabe == "STATUS") {
				cms_meldung_an('fehler', 'Bestellung speichern', '<p>Die Bestellung konnte nicht gespeichert werden, da bereits eine Bestellung vorliegt, die schon aufgegeben wurde.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_ebestellung_speichern2() {
	cms_laden_an('Bedarf speichern', 'Die Angaben werden geprüft');

	var bedarf = document.getElementById('cms_ebestellung_bedarf').value;
	var bestellnr = document.getElementById('cms_ebestellung_bestellnr').value;
	var anz_leihe = document.getElementById('cms_ebestellung_anz_leiheubuntu').value;
	var anz_lapu = document.getElementById('cms_ebestellung_anz_laptopubuntu').value;
	var anz_lapw = document.getElementById('cms_ebestellung_anz_laptopwindows').value;
	var anz_kombim = document.getElementById('cms_ebestellung_anz_kombimittel').value;
	var anz_kombig = document.getElementById('cms_ebestellung_anz_kombigut').value;

	var anrede = document.getElementById('cms_ebestellung_anrede').value;
	var vorname = document.getElementById('cms_ebestellung_vorname').value;
	var nachname = document.getElementById('cms_ebestellung_nachname').value;
	var strasse = document.getElementById('cms_ebestellung_strasse').value;
	var hausnr = document.getElementById('cms_ebestellung_hausnr').value;
	var plz = document.getElementById('cms_ebestellung_plz').value;
	var ort = document.getElementById('cms_ebestellung_ort').value;
	var bedingungen = document.getElementById('cms_bedingungen').value;

	var telefon1 = document.getElementById('cms_schulhof_ebestellung_telefon').value;
	var telefon2 = document.getElementById('cms_schulhof_ebestellung_telefon_wiederholen').value;
	var mail1 = document.getElementById('cms_schulhof_ebestellung_mail').value;
	var mail2 = document.getElementById('cms_schulhof_ebestellung_mail_wiederholen').value;

	var meldung = '<p>Die Angeben konnten nicht gespeichert werden, denn</p><ul>';
	var fehler = false;

  if ((bedarf != '0') && (bedarf != '1') && (bedarf != '2')) {
    meldung += '<li>die Eingabe für den Bedarf ist ungültig.</li>';
		fehler = true;
  }
  if (!cms_check_ganzzahl(bestellnr,0,9999999999)) {
    meldung += '<li>die Eingabe für den Bedarf ist ungültig.</li>';
		fehler = true;
  }

  if ((bedarf == '1') || (bedarf == '2')) {
    if ((anrede != '-') && (anrede != 'Frau') && (anrede != 'Herr')) {
  		meldung += '<li>die Anrede ist ungültig.</li>';
  		fehler = true;
  	}

    if (vorname.length < 1) {
  		meldung += '<li>der Vorname ist ungültig.</li>';
  		fehler = true;
  	}

    if (nachname.length < 1) {
  		meldung += '<li>der Nachname ist ungültig.</li>';
  		fehler = true;
  	}

    if (strasse.length < 1) {
  		meldung += '<li>die Straße ist ungültig.</li>';
  		fehler = true;
  	}

    if (hausnr.length < 1) {
  		meldung += '<li>die Hausnummer ist ungültig.</li>';
  		fehler = true;
  	}

    if (!cms_check_ganzzahl(plz, 0, 99999)) {
  		meldung += '<li>die Postleitzahl ist ungültig.</li>';
  		fehler = true;
  	}

    if (ort.length < 1) {
  		meldung += '<li>der Ort ist ungültig.</li>';
  		fehler = true;
  	}

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
    if (!cms_check_ganzzahl(anz_lapu,0,5)) {
  		meldung += '<li>die Anzahl an »Inspirion 14 3000 – Linux (Ubuntu)« ist ungültig. Es können 0-5 Geräte bestellt werden.</li>';
  		fehler = true;
  	}

    if (!cms_check_ganzzahl(anz_lapw,0,5)) {
  		meldung += '<li>die Anzahl an »Inspirion 14 3000 – Windows« ist ungültig. Es können 0-5 Geräte bestellt werden.</li>';
  		fehler = true;
  	}

    if (!cms_check_ganzzahl(anz_kombim,0,5)) {
  		meldung += '<li>die Anzahl an »Inspirion 14 5000 M Tablet und Laptop in einem – Windows« ist ungültig. Es können 0-5 Geräte bestellt werden.</li>';
  		fehler = true;
  	}

    if (!cms_check_ganzzahl(anz_kombig,0,5)) {
  		meldung += '<li>die Anzahl an »Inspirion 14 5000 G Tablet und Laptop in einem – Windows« ist ungültig. Es können 0-5 Geräte bestellt werden.</li>';
  		fehler = true;
  	}

    if (bedingungen != 1) {
  		meldung += '<li>die Bestellbedingungen wurden nicht akzeptiert.</li>';
  		fehler = true;
  	}
  }

  if (bedarf == '2') {
    if (!cms_check_ganzzahl(anz_leihe,1,1)) {
  		meldung += '<li>die Anzahl an »Inspirion 14 3000 – Linux (Ubuntu)« ist ungültig. Es kann nur ein Gerät pro Schüler entliehen werden.</li>';
  		fehler = true;
  	}
  }


	if (fehler) {
		cms_meldung_an('fehler', 'Bedarf speichern', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		var formulardaten = new FormData();

		formulardaten.append("bedarf",  bedarf);
		formulardaten.append("bestellnr", bestellnr);
		formulardaten.append("anz_leihe",     anz_leihe);
		formulardaten.append("anz_lapu",     anz_lapu);
		formulardaten.append("anz_lapw",     anz_lapw);
		formulardaten.append("anz_kombim",     anz_kombim);
		formulardaten.append("anz_kombig",     anz_kombig);
		formulardaten.append("anrede",     anrede);
		formulardaten.append("vorname",     vorname);
		formulardaten.append("nachname",     nachname);
		formulardaten.append("strasse",     strasse);
		formulardaten.append("hausnr",     hausnr);
		formulardaten.append("plz",     plz);
		formulardaten.append("ort",     ort);
		formulardaten.append("bedingungen",     bedingungen);
		formulardaten.append("telefon1",   telefon1);
		formulardaten.append("telefon2",   telefon2);
		formulardaten.append("mail1",   mail1);
		formulardaten.append("mail2",   mail2);
		formulardaten.append("anfragenziel", 	'394');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Bestellung speichern', '<p>Die Bestellung wurde übermittelt. Vielen Dank!<br>Im Falle eines Kaufs, denken Sie bitte daran, die Zahlung innerhalb der Frist vorzunehmen.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Nutzerkonto\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
