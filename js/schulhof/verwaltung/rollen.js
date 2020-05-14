/* ROLLE WIRD GESPEICHERT */
function cms_schulhof_rolle_neu_speichern() {
	cms_laden_an('Neue Rolle anlegen', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_schulhof_rolle_bezeichnung').value;

	var meldung = '<p>Die Rolle konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}
	var rechte = [];
	var rekpruefen = function(e, pfad) {
		if(!e.length)
			return;
		e.each(function() {
			e = $(this);
			if(e.is(".cms_recht_rolle"))
				rechte.push((pfad+"."+e.data("knoten")+(e.is(".cms_hat_kinder")?".*":"")).substr(2));
			else
				if(e.is(".cms_hat_kinder"))
					rekpruefen(e.find(">.cms_rechtekinder>.cms_rechtebox>.cms_recht"), pfad+"."+e.data("knoten"));
		});
	};
	rekpruefen($("#cms_rechtepapa>.cms_recht"), "");

	if (rechte.length == 0) {
		meldung += '<li>es wurden keine Rechte ausgewählt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Neue Rolle anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Neue Rolle anlegen', 'Es wird geprüft, ob bereits eine Rolle mit der Bezeichnung <i>'+bezeichnung+'</i> existiert.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("rechte", 			rechte);
		formulardaten.append("anfragenziel", 	'141');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits eine Rolle mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Neue Rolle anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "BEZEICHNUNG") {
				meldung += '<li>die Bezeichnung enthält ungültige Zeichen.</li>';
				cms_meldung_an('fehler', 'Neue Rolle anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Rolle anlegen', '<p>Die Rolle <i>'+bezeichnung+'</i> wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Rollen\');">Zurück zur Übersicht</span></p>');
			}else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

/* ROLLE WIRD ZUM BEARBEITEN VORBEREITET */
function cms_schulhof_rolle_bearbeiten_vorbereiten (id) {
	cms_laden_an('Rolle bearbeiten', 'Die Berechtigung wird geprüft.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'142');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Verwaltung/Rollen/Rolle_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_schulhof_rolle_loeschen_anzeigen (anzeigename, id) {
	cms_meldung_an('warnung', 'Rolle löschen', '<p>Soll die Rolle <b>'+anzeigename+'</b> wirklich gelöscht werden?</p><p>Jede Person, die diese Rolle inne hat, verliert sie und alle damit verbundenen Rechte!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_schulhof_rolle_loeschen(\''+anzeigename+'\','+id+')">Löschung durchführen</span></p>');
}


function cms_schulhof_rolle_loeschen(anzeigename, id) {
	cms_laden_an('Rolle löschen', 'Die Rolle <b>'+anzeigename+'</b> wird gelöscht.');

	var fehler = false;
	var meldung = '';

	// Pflichteingaben prüfen
	if (id == 0) {
		fehler = true;
		meldung += '<li>Die Rolle <b>Administrator</b> kann nicht gelöscht werden.</li>';
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Rolle löschen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {

		var formulardaten = new FormData();
		formulardaten.append("id",     		id);
		formulardaten.append("anfragenziel", 	'143');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Rolle löschen', '<p>Die Rolle wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Rollen\');">OK</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}

/* BEARBEITETE ROLLE WIRD GESPEICHERT */
function cms_schulhof_rolle_bearbeiten_speichern() {
	cms_laden_an('Rolle bearbeiten', 'Die Eingaben werden überprüft.');
	var bezeichnung = document.getElementById('cms_schulhof_rolle_bezeichnung').value;

	var meldung = '<p>Die Rolle konnte nicht bearbeitet werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if (bezeichnung.length == 0) {
		meldung += '<li>es wurde keine Bezeichnung eingegeben.</li>';
		fehler = true;
	}

	var rechte = [];
  var rekpruefen = function(e, pfad) {
    if(!e.length)
      return;
    e.each(function() {
      e = $(this);
      if(e.is(".cms_recht_rolle"))
        rechte.push((pfad+"."+e.data("knoten")+(e.is(".cms_hat_kinder")?".*":"")).substr(2));
      else
        if(e.is(".cms_hat_kinder"))
          rekpruefen(e.find(">.cms_rechtekinder>.cms_rechtebox>.cms_recht"), pfad+"."+e.data("knoten"));
    });
  };
  rekpruefen($("#cms_rechtepapa>.cms_recht"), "");

	if (rechte.length == 0) {
		meldung += '<li>es wurden keine Rechte ausgewählt.</li>';
		fehler = true;
	}


	if (fehler) {
		cms_meldung_an('fehler', 'Rolle bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Rolle bearbeiten', 'Es wird geprüft, ob bereits eine Rolle mit der Bezeichnung <i>'+bezeichnung+'</i> existiert.');

		var formulardaten = new FormData();
		formulardaten.append("bezeichnung", bezeichnung);
		formulardaten.append("rechte", 			rechte);
		formulardaten.append("anfragenziel", 	'144');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DOPPELTFEHLER") {
				meldung += '<li>es gibt bereits eine Rolle mit dieser Bezeichnung.</li>';
				cms_meldung_an('fehler', 'Rolle bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "BEZEICHNUNG") {
				meldung += '<li>die Bezeichnung enthält ungültige Zeichen.</li>';
				cms_meldung_an('fehler', 'Neue Rolle anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "BERECHTIGUNG") {
				cms_meldung_berechtigung();
			}
			else if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Rolle bearbeiten', '<p>Die Rolle <i>'+bezeichnung+'</i> wurde geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Rollen\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
}
