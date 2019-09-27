function cms_neue_galerie(ziel) {
	cms_laden_an('Neue Galerie vorbereiten', 'Vorbereitungen für die neue Galerie werden getroffen.');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'301');
	formulardaten.append("ziel", 	ziel);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Website/Galerien/Neue_Galerie');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_galerie_bearbeiten_vorbereiten(id, ziel) {
	cms_laden_an('Galerie bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
	formulardaten.append("anfragenziel", 	'302');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Galerien/Galerie_bearbeiten');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_galerie_eingabenpruefen() {
	var meldung = '<p>Die Galerie konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;
	var notifikationen = document.getElementById('cms_galerie_notifikationen').value;
	var genehmigt = document.getElementById('cms_galerie_genehmigt').value;
	var aktiv = document.getElementById('cms_galerie_aktiv').value;
	var datumT = document.getElementById('cms_galerie_datum_T').value;
	var datumM = document.getElementById('cms_galerie_datum_M').value;
	var datumJ = document.getElementById('cms_galerie_datum_J').value;

	var bezeichnung = document.getElementById('cms_galerie_bezeichnung').value;
	var beschreibung = document.getElementById('cms_galerie_beschreibung').value;
	var vorschaubild = document.getElementById('cms_galerie_vorschaubild').value;
	var autor = document.getElementById('cms_galerie_autor').value;
	var bilderanzahl = document.getElementById('cms_bilder_anzahl').value;
	var bilderids = document.getElementById('cms_bilder_ids').value;

	var formulardaten = new FormData();
	for (var i=0; i<CMS_GRUPPEN.length; i++) {
		var gruppek = cms_textzudb(CMS_GRUPPEN[i]);
		var wert = document.getElementById('cms_zuorndung_zugeordnetegruppen_'+gruppek);
		formulardaten.append('z'+gruppek, wert.value);
	}

	// Pflichteingaben prüfen
	if (!cms_check_titel(bezeichnung)) {
		meldung += '<li>Bezeichnungen dürfen nur aus lateinischen Buchtaben, Umlauten, Ziffern, Leerzeichen und »-« bestehen und müssen mindestens ein Zeichen lang sein.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(notifikationen)) {
		meldung += '<li>die Eingabe für Notifikationen ist ungültig.</li>';
		fehler = true;
	}


	if (!cms_check_toggle(genehmigt)) {
		meldung += '<li>die Eingabe für die Genehmigung ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_toggle(aktiv)) {
		meldung += '<li>die Eingabe für die Aktivität ist ungültig.</li>';
		fehler = true;
	}

	var bpfadf = false;
	var bfehler = false;
	if (bilderanzahl > 0) {
		ids = bilderids.split('|');
		for (i=1; i<ids.length; i++) {
			var bid = ids[i];

			var bbeschreibung = document.getElementById('cms_bild_beschreibung_'+bid);
			var bpfad = document.getElementById('cms_bild_datei_'+bid);

			if (bbeschreibung) {formulardaten.append("bbeschreibung_"+bid,  bbeschreibung.value);} else {bfehler = true;}
			if (bpfad) {if ((bpfad.value).length > 0) {formulardaten.append("bpfad_"+bid,  bpfad.value);} else {bpfadf = true;}} else {bfehler = true;}
		}
	}

	if (bfehler) {
		meldung += '<li>bei der Erfassung der Bilder ist ein unbekannter Fehler aufgetreten. Bitte den <a href="Website/Feedback">Administrator informieren</a>.</li>';
		fehler = true;
	}
	if (bpfadf) {
		meldung += '<li>bei mindestens einem Bild wurde keine Datei ausgewählt.</li>';
		fehler = true;
	}

	if (!fehler) {
		formulardaten.append("notifikationen", 	notifikationen);
		formulardaten.append("genehmigt",  			genehmigt);
		formulardaten.append("aktiv",  					aktiv);
		formulardaten.append("datumT",  				datumT);
		formulardaten.append("datumM",					datumM);
		formulardaten.append("datumJ",  				datumJ);
		formulardaten.append("bezeichnung",  		bezeichnung);
		formulardaten.append("autor",  					autor);
		formulardaten.append("beschreibung", 		beschreibung);
		formulardaten.append("vorschaubild",  	vorschaubild);
		formulardaten.append("bildanzahl",  		bilderanzahl);
		formulardaten.append("bildids", 				bilderids);
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}

function cms_galerie_neu_speichern(ziel) {
	cms_laden_an('Neue Galerie anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_galerie_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '303');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Neue Galerie anlegen', '<p>Die Galerie wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Die Galerie konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, auf den die Galerie datiert ist, existiert bereits eine Galerie mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Neue Galerie anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Die Galerie konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>die Galerie enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neue Galerie anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neue Galerie anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_galerie_bearbeiten_speichern(ziel) {
	cms_laden_an('Galerie bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_galerie_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("anfragenziel", '304');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Galerie bearbeiten', '<p>Die Änderungen wurden übernommen</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Die Galerie konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>an dem Tag, auf den die Galerie datiert ist, existiert bereits eine Galerie mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Galerie bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe.match(/BÖSE/)) {
				var meldung = '<p>Die Galerie konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>die Galerie enthält verboten Code. Der Verstoß wurde protokolliert.</li></ul>';
				cms_meldung_an('fehler', 'Neue Galerie anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Galerie bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_galerie_loeschen_vorbereiten(id, bezeichnung, ziel) {
	cms_meldung_an('warnung', 'Galerie löschen', '<p>Soll die Galerie <b>'+bezeichnung+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_galerie_loeschen(\''+id+'\',\''+ziel+'\')">Löschung durchführen</span></p>');
}

function cms_galerie_loeschen(id, ziel) {
	cms_laden_an('Galerie löschen', 'Die Galerie wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'305');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Galerie löschen', '<p>Die Galerie wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_galerien_jahr_loeschen_vorbereiten() {
  var jahr = document.getElementById('cms_verwaltung_galerien_jahr_angezeigt').value;
	cms_meldung_an('warnung', 'Galerien des Jahres '+jahr+' löschen', '<p>Sollen <b>alle</b> Galerien des Jahres '+jahr+' wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_galerien_jahr_loeschen()">Löschung durchführen</span></p>');
}

function cms_galerien_jahr_loeschen() {
  var jahr = document.getElementById('cms_verwaltung_galerien_jahr_angezeigt').value;
	cms_laden_an('Galerien des Jahres '+jahr+' löschen', 'Alle Galerien des Jahres '+jahr+' werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("jahr", 	jahr);
	formulardaten.append("anfragenziel", 	'306');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Galerien des Jahres '+jahr+' löschen', '<p>Die Galerien wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Galerien\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_galerieverwaltung(jahr, spalten, anfang, ende) {
  var feld = document.getElementById('cms_verwaltung_galerien_jahr');
  feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\">'+cms_ladeicon()+'<br>Die Galerien für das Jahr '+jahr+' werden geladen. Je nach Verbindung und Galerienanzehl kann dies etwas dauern.</td></tr>';

  for (var i=anfang; i<=ende; i++) {
    var toggle = document.getElementById('cms_verwaltung_galerien_jahr_'+i);
    toggle.className = 'cms_toggle';
  }

  var fehler = false;

  if ((!Number.isInteger(parseInt(jahr))) || (!Number.isInteger(parseInt(spalten)))) {
    fehler = true;
  }

  if (fehler) {
    feld.innerHTML = '<tr><td colspan="'+spalten+'" class=\"cms_notiz\">– ungültige Anfrage –</td></tr>';
  }
  else {
    var formulardaten = new FormData();
  	formulardaten.append("jahr",         jahr);
  	formulardaten.append("anfragenziel", 	'307');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.slice(0,4) == '<tr>') {
				var toggle = document.getElementById('cms_verwaltung_galerien_jahr_'+jahr);
				toggle.className = 'cms_toggle_aktiv';
				document.getElementById('cms_verwaltung_galerien_jahr_angezeigt').value = jahr;
				feld.innerHTML = rueckgabe;
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}

function cms_galerie_genehmigen(id, ziel) {
	cms_laden_an('Galerie genehmigen', 'Die Galerie wird genehmigt.');
	var ziel = ziel || 'Schulhof/Aufgaben/Galerien_genehmigen';
	var formulardaten = new FormData();
	formulardaten.append("id",         		 id);
	formulardaten.append("anfragenziel", 	'308');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Galerie genehmigen', '<p>Die Galerie wurde genehmigt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_galerie_ablehnen(id, ziel) {
	cms_laden_an('Galerie ablehnen', 'Die Galerie wird abgelehnt.');
	var ziel = ziel || 'Schulhof/Aufgaben/Galerien_genehmigen';
	var formulardaten = new FormData();
	formulardaten.append("id",         		 id);
	formulardaten.append("anfragenziel", 	'309');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Galerie ablehnen', '<p>Die Galerie wurde abgelehnt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

$(document).ready(function () {
	$("#cms_galerien_dateien_inhalt_tabelle .cms_dateisystem_dateiwahl").attr("onclick", null).off("click").click(cms_galerie_bild_hinzufuegen);
});

function cms_galerie_bild_hinzufuegen() {
	cms_laden_an("Bild hinzufügen", "Das Bild wird hinzugefügt");	// Gibt den Nutzer Feedback, dass der Klick erfasst wurde, da sich nicht unbedingt sichtbar etwas tut
	cms_galerie_bild_box_machen($(this).data("pfad"));
	setTimeout(function() {
		cms_laden_aus();
	}, 100);
}

function cms_galerie_bild_box_machen(pfad) {
	var box = $('#cms_bilder');
	var anzahl = $('#cms_bilder_anzahl');
	var nr = $('#cms_bilder_nr');
	var ids = $('#cms_bilder_ids');
	var anzahlneu = parseInt(anzahl.val())+1;
	var nrneu = parseInt(nr.val())+1;
	var neueid = 'temp'+nrneu;

	var code = "";
	code += "<tr><th>Datei:</th>";
	code += "<td colspan=\"4\"><input id=\"cms_bild_datei_"+neueid+"\" name=\"cms_bild_datei_"+neueid+"\" type=\"hidden\" value=\""+pfad+"\">";
	code += "<p class=\"cms_notiz cms_vorschau\" id=\"cms_bild_datei_"+neueid+"_vorschau\"><img src=\""+pfad+"\"></p>";
		code += "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'galerien', '-', 'galerien', 'cms_bild_datei_"+neueid+"', 'vorschaubild', '-', '-')\">Bild auswählen</span></p>";
		code += "<p id=\"cms_bild_datei_"+neueid+"_verzeichnis\"></p>";
	code += "</td></tr>";
	code += "<tr><th>Beschreibung:</th><td colspan=\"4\"><textarea name=\"cms_bild_beschreibung_"+neueid+"\" id=\"cms_bild_beschreibung_"+neueid+"\"></textarea></td></tr>";
	code += "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_bild_entfernen('"+neueid+"')\">- Bild entfernen</span></td></tr>";
	var knoten = $("<table></table>", {class: "cms_formular", id: "cms_bild"+neueid}).html(code);
	box.append(knoten);
	anzahl.val(anzahlneu);
	nr.val(nrneu);
	ids.val(ids.val()+'|'+neueid);
}

function cms_bild_entfernen(id) {
	var box = document.getElementById('cms_bilder');
	var anzahl = document.getElementById('cms_bilder_anzahl');
	var ids = document.getElementById('cms_bilder_ids');
	var bild = document.getElementById('cms_bild'+id);

	box.removeChild(bild);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;

}
