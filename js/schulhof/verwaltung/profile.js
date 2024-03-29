function cms_profile_vorbereiten(id) {
  cms_laden_an('Profile vorbereiten', 'Die Profile des Schuljahres werden vorbereitet ...');

  var formulardaten = new FormData();
  formulardaten.append('id', id);
  formulardaten.append("anfragenziel", 	'330');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_link('Schulhof/Verwaltung/Planung/Profile');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
  }

  cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}


function cms_profile_eingabencheck() {
	var bezeichnung = document.getElementById('cms_profil_bezeichnung').value;
  var stufe = document.getElementById('cms_profil_stufe').value;
  var art = document.getElementById('cms_profil_art').value;
  var faecherids = document.getElementById('cms_profil_faecherids').value;

	// Pflichteingaben prüfen
	var formulardaten = new FormData();
	var meldung = '';
	var fehler = false;
  var datumfehler = false;

	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Die Bezeichnung des Profils‚ enthält ungültige Zeichen.</li>';
		fehler = true;
	}

  if (!cms_check_ganzzahl(stufe)) {
		fehler = true;
		meldung += '<li>Die gewählte Stufe ist ungültig.</li>';
	}

  if ((art != 'p') && (art != 'w')) {
		fehler = true;
		meldung += '<li>Die gewählte Profilart ist ungültig.</li>';
	}

	// Wahlfächer prüfen
  var wfehler = false;
  if (faecherids.length > 0) {
    var fids = (faecherids.substr(1)).split('|');
    var feld;
    for (var i = 0; i < fids.length; i++) {
      feld = document.getElementById('cms_profil_fach_'+fids[i]);
      if (feld) {
        if (!cms_check_toggle(feld.value)) {wfehler = true;}
        else {
          formulardaten.append("fach_"+fids[i], feld.value);
        }
      }
      else {wfehler = true;}
    }
  }
  else {
    fehler = true;
    meldung += '<li>Es wurden keine Wahlfächer ausgewählt.</li>';
  }

  if (wfehler) {
		fehler = true;
		meldung += '<li>Die Wahlfächer sind ungültig.</li>';
	}

	formulardaten.append("bezeichnung", bezeichnung);
	formulardaten.append("stufe", 	    stufe);
	formulardaten.append("art", 	      art);
	formulardaten.append("faecherids",  faecherids);

	var rueckgabe = new Array();
	rueckgabe[0] = fehler;
	rueckgabe[1] = meldung;
	rueckgabe[2] = formulardaten;
	return rueckgabe;
}


function cms_profile_neu_speichern() {
	cms_laden_an('Neues Profil anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_profil_bezeichnung').value;
	var meldung = '<p>Das Profil konnte nicht erstellt werden, denn ...</p><ul>';

	var rueckgabe = cms_profile_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Neues Profil anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neues Profil anlegen', 'Das neue Profil wird angelegt');

		formulardaten.append("anfragenziel", 	'331');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/FACH/)) {
				meldung += '<li>dieses Profil enthält ungültige Fächer.</li>';
				cms_meldung_an('fehler', 'Neues Profil anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			if (rueckgabe.match(/STUFE/)) {
				meldung += '<li>dieses Profil wurde einer ungültigen Stufe zugeordnet.</li>';
				cms_meldung_an('fehler', 'Neues Profil anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neues Profil anlegen', '<p>Das Profil <b>'+bezeichnung+'</b> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Profile\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

function cms_profile_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Profil löschen', '<p>Soll das Profil <b>'+anzeigename+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_profile_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}

function cms_profile_loeschen(anzeigename, id) {
	cms_laden_an('Profil löschen', 'Das Profil <b>'+anzeigename+'</b> wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		id);
	formulardaten.append("anfragenziel", 	'332');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Profil löschen', '<p>Das Profil wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Profile\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_multiselect_profile_loeschen_anzeigen () {
	cms_meldung_an('warnung', 'Profile löschen', '<p>Sollen die Profile wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_multiselect_profile_loeschen()">Löschung durchführen</span></p>');
}

function cms_multiselect_profile_loeschen () {
  cms_multianfrage(332, ["Profile löschen", "Die Profile werden gelöscht"], {id: cms_multiselect_ids()}).then((rueckgabe) => {
    if(rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Profile löschen', '<p>Die Profile wurden gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Profile\');">OK</span></p>');
    } else {cms_fehlerbehandlung(rueckgabe);}
  });
}

function cms_profile_bearbeiten_vorbereiten (id) {
	cms_laden_an('Profil bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'333');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Planung/Profile/Profil_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_profile_bearbeiten_speichern () {
	cms_laden_an('Profil bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_profil_bezeichnung').value;
	var meldung = '<p>Das Profil konnte nicht geändert werden, denn ...</p><ul>';

	var rueckgabe = cms_profile_eingabencheck();

	var fehler = rueckgabe[0];
	meldung = meldung + rueckgabe[1];
	var formulardaten = rueckgabe[2];

	if (fehler) {
		cms_meldung_an('fehler', 'Profil bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Profil bearbeiten', 'Das Profil wird bearbeitet');

		formulardaten.append("anfragenziel", 	'334');

		function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe.match(/FACH/)) {
				meldung += '<li>dieses Profil enthält ungültige Fächer.</li>';
				cms_meldung_an('fehler', 'Profil bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			if (rueckgabe.match(/STUFE/)) {
				meldung += '<li>dieses Profil wurde einer ungültigen Stufe zugeordnet.</li>';
				cms_meldung_an('fehler', 'Profil bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Profil bearbeiten', '<p>Das Profil <b>'+bezeichnung+'</b> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Planung/Profile\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
