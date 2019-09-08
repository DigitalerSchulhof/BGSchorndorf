function cms_neuer_newsletter(ziel) {
	cms_laden_an('Neuer Newsletter vorbereiten', 'Vorbereitungen für den neuen Newsletter werden getroffen.');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'282');
	formulardaten.append("ziel", 	ziel);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Website/Newsletter/Neuer_Newsletter');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_newsletter_bearbeiten_vorbereiten(id, ziel) {
	cms_laden_an('Newsletter bearbeiten', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
	formulardaten.append("anfragenziel", 	'283');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Newsletter/Newsletter_bearbeiten');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_newsletter_details_vorbereiten(id, ziel) {
	cms_laden_an('Newsletter ansehen', 'Die notwendigen Daten werden gesammelt.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("ziel", ziel);
	formulardaten.append("anfragenziel", 	'286');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Website/Newsletter/Newsletter_ansehen');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_newsletter_empfaenger_anlegen(id) {
	var liste = $("#cms_newsletter_empfaenger_liste");
	liste.find(".cms_leer").hide();
	$("#cms_empfaenger_aktionen").hide();

	var r = $("<tr></tr>").append(	// #jQuery
		$("<td></td>").html($("<img></img>", {src: "res/icons/klein/neuerempfaenger.png"})),
		$("<td></td>").html($("<input>", {id: "cms_newsletter_empfaenger_neu_name"})),
		$("<td></td>").html($("<input>", {id: "cms_newsletter_empfaenger_neu_email"})),
		$("<td></td>").html(
			$("<span>", {class: "cms_aktion_klein"}).html($("<img></img>", {src: "res/icons/klein/richtig.png"})).click(function() {cms_newsletter_empfaenger_anlegen_ok(id);})
		).append(" ").append(
			$("<span>", {class: "cms_aktion_klein"}).html($("<img></img>", {src: "res/icons/klein/loeschen.png"})).click(function() {location.reload()})
		)
	).appendTo(liste);
}

function cms_newsletter_empfaenger_anlegen_ok(id) {
	cms_laden_an("Empfänger hinzufügen", "Daten werden gesammelt");

	var name = $("#cms_newsletter_empfaenger_neu_name").val();
	var mail = $("#cms_newsletter_empfaenger_neu_email").val();
	var meldung = '<p>Der Empfänger konnte nicht hinzugefügt werden, denn ...</p><ul>';
	var fehler = false;

	if (!cms_check_name(name)) {
		meldung += '<li>der Name ist ungültig.</li>';
		fehler = true;
	}

	if (!cms_check_mail(mail)) {
		meldung += '<li>der eMailadresse ist ungültig.</li>';
		fehler = true;
	}

	var formulardaten = new FormData();
	formulardaten.append("name", 					name);
	formulardaten.append("mail", 					mail);
	formulardaten.append("id", 						id);
	formulardaten.append("anfragenziel", 	'287');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				location.reload();
			}
			else if (rueckgabe.match(/MAIL/)) {
				var meldung = '<p>Der Empfänger konnte nicht hinzugefügt werden, denn ...</p><ul>';
				meldung += '<ul><li>es existiert bereits ein Empfänger mit dieser eMailadresse.</li></ul>';
				cms_meldung_an('fehler', 'Empfänger hinzufügen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Empfänger hinzufügen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}

}

function cms_newsletter_empfaenger_loeschen_vorbereiten(id) {
	cms_meldung_an("warnung", "Empfänger entfernen", "<p>Soll der Empfänger wirklich entfernt werden?", '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_newsletter_empfaenger_loeschen('+id+')">Entfernen</span></p>');
}

function cms_newsletter_empfaenger_loeschen(id) {
	cms_laden_an("Empfänger entfernen", "Daten werden gesammelt");

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", '288');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			location.reload();
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);

}

function cms_newsletter_eingabenpruefen() {
	var meldung = '<p>Der Newsletter konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

	var bezeichnung = document.getElementById('cms_newsletter_bezeichnung').value;

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

	if (!fehler) {
		formulardaten.append("bezeichnung",  		bezeichnung);
	}

	var rueckgabe = [meldung, fehler, formulardaten];
	return rueckgabe;
}

function cms_newsletter_neu_speichern(ziel) {
	cms_laden_an('Neuen Newsletter anlegen', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_newsletter_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("ziel", 					ziel);
	formulardaten.append("anfragenziel", 	'284');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.startsWith("ERFOLG")) {
				cms_meldung_an('erfolg', 'Neuen Newsletter anlegen', '<p>Der Newsletter wurde angelegt.</p>', '<p><span class="cms_button" onclick="'+rueckgabe.replace("ERFOLG", "")+'">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Newsletter konnte nicht erstellt werden, denn ...</p><ul>';
				meldung += '<ul><li>es existiert bereits ein Newsletter mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Newsletter anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Neuen Newsletter anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_newsletter_bearbeiten_speichern(ziel) {
	cms_laden_an('Newsletter bearbeiten', 'Die Eingaben werden überprüft.');

	var rueckgabe = cms_newsletter_eingabenpruefen();

	var meldung = rueckgabe[0];
	var fehler = rueckgabe[1];
	var formulardaten = rueckgabe[2];
	formulardaten.append("ziel", 					ziel);
	formulardaten.append("anfragenziel",	'285');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.startsWith("ERFOLG")) {
				cms_meldung_an('erfolg', 'Neuen Newsletter anlegen', '<p>Der Newsletter wurde angelegt.</p>', '<p><span class="cms_button" onclick="'+rueckgabe.replace("ERFOLG", "")+'">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Newsletter konnte nicht bearbeitet werden, denn ...</p><ul>';
				meldung += '<ul><li>es existiert bereits ein Newsletter mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Neuen Newsletter anlegen', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
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
	cms_galerie_bild_box_machen($(this).data("pfad"));
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
																																														 // ACHTUNG: "-" wegen Einigkeit überall fehlend
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
