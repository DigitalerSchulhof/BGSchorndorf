function cms_neuer_newsletter(ziel) {
	cms_laden_an('Neuer Newsletter vorbereiten', 'Vorbereitungen für den neuen Newsletter werden getroffen.');
	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'282');
	formulardaten.append("ziel", 	ziel);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {cms_link('Schulhof/Website/Newsletter/Neuer_Newsletter');}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_newsletter_empfaenger_anlegen(id) {
	var liste = $("#cms_newsletter_empfaenger_liste");
	liste.find(".cms_leer").hide();
	$("#cms_empfaenger_aktionen").hide();

	var r = $("<tr></tr>").append(	// #jQuery
		$("<td></td>").html($("<img></img>", {src: "res/icons/klein/hinzufuegen.png"})),
		$("<td></td>").html($("<input>", {id: "cms_newsletter_empfaenger_neu_name"})),
		$("<td></td>").html($("<input>", {id: "cms_newsletter_empfaenger_neu_email"})),
		$("<td></td>").html(
			$("<span>", {class: "cms_aktion_klein"}).html(
				$("<span></span>", {class: "cms_hinweis"}).html("Empfänger anlegen")
			).append(
				$("<img></img>", {src: "res/icons/klein/richtig.png"})
			).click(function() {cms_newsletter_empfaenger_anlegen_ok(id);})
		).append(" ").append(
			$("<span>", {class: "cms_aktion_klein cms_button_wichtig"}).html(
				$("<span></span>", {class: "cms_hinweis"}).html("Abbrechen")
			).append(
				$("<img></img>", {src: "res/icons/klein/abbrechen.png"})
			).click(function() {location.reload()})
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
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Empfänger hinzufügen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}

}

function cms_newsletter_empfaenger_bearbeiten(id) {
	var tr = $("#cms_newsletter_empfaenger_"+id);
	tr.find(".cms_newsletter_empfaenger_name").html($("<input />").val(tr.find(".cms_newsletter_empfaenger_name").text()));
	tr.find(".cms_newsletter_empfaenger_mail").html($("<input />").val(tr.find(".cms_newsletter_empfaenger_mail").text()));
	tr.find(".cms_newsletter_empfaenger_aktionen").html(
		$("<span>", {class: "cms_aktion_klein"}).html(
			$("<span></span>", {class: "cms_hinweis"}).html("Änderungen speichern")
		).append(
			$("<img></img>", {src: "res/icons/klein/richtig.png"})
		).click(function() {cms_newsletter_empfaenger_bearbeiten_ok(id);})
	).append(" ").append(
		$("<span>", {class: "cms_aktion_klein cms_button_wichtig"}).html(
			$("<span></span>", {class: "cms_hinweis"}).html("Abbrechen")
		).append(
			$("<img></img>", {src: "res/icons/klein/abbrechen.png"})
		).click(function() {location.reload()})
	)
}

function cms_newsletter_empfaenger_bearbeiten_ok(id) {
	cms_laden_an("Empfänger bearbeiten", "Daten werden gesammelt");

	var name = $("#cms_newsletter_empfaenger_"+id).find(".cms_newsletter_empfaenger_name>input").val();
	var mail = $("#cms_newsletter_empfaenger_"+id).find(".cms_newsletter_empfaenger_mail>input").val();
	var meldung = '<p>Der Empfänger konnte nicht bearbeiten werden, denn ...</p><ul>';
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
	formulardaten.append("anfragenziel", 	'289');

	if (!fehler) {
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				location.reload();
			}
			else if (rueckgabe.match(/MAIL/)) {
				var meldung = '<p>Der Empfänger konnte nicht bearbeitet werden, denn ...</p><ul>';
				meldung += '<ul><li>es existiert bereits ein Empfänger mit dieser eMailadresse.</li></ul>';
				cms_meldung_an('fehler', 'Empfänger bearbeitet', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Empfänger bearbeitet', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
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
	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);

}

function cms_newsletter_empfaenger_loeschen_alle_vorbereiten(id) {
	cms_meldung_an("warnung", "Alle Empfänger entfernen", "<p>Sollen alle Empfänger wirklich entfernt werden?", '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_newsletter_empfaenger_loeschen_alle('+id+')">Entfernen</span></p>');
}

function cms_newsletter_empfaenger_loeschen_alle(id) {
	cms_laden_an("Alle Empfänger entfernen", "Daten werden gesammelt");

	var formulardaten = new FormData();
	formulardaten.append("id", 					  id);
	formulardaten.append("anfragenziel", '364');
	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			location.reload();
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);

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
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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
				cms_meldung_an('erfolg', 'Newsletter bearbeiten', '<p>Der Newsletter wurde bearbeitet.</p>', '<p><span class="cms_button" onclick="'+rueckgabe.replace("ERFOLG", "")+'">OK</span></p>');
			}
			else if (rueckgabe.match(/DOPPELT/)) {
				var meldung = '<p>Der Newsletter konnte nicht bearbeitet werden, denn ...</p><ul>';
				meldung += '<ul><li>es existiert bereits ein Newsletter mit dieser Bezeichnung.</li></ul>';
				cms_meldung_an('fehler', 'Newsletter bearbeiten', meldung, '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Galerie bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_newsletter_loeschen_vorbereiten(id, bezeichnung, ziel) {
	cms_meldung_an('warnung', 'Newsletter löschen', '<p>Soll der Newsletter <b>'+bezeichnung+'</b> wirklich gelöscht werden?<br>Dadurch werden alle Anmeldeformulare auf der Website automatisch entfernt. Die Empfängerliste des Newsletters wird <b>unwiderruflich</b> geleert!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_newsletter_loeschen(\''+id+'\',\''+ziel+'\')">Löschung durchführen</span></p>');
}

function cms_newsletter_loeschen(id, ziel) {
	cms_laden_an('Newsletter löschen', 'Der Newsletter wird gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("anfragenziel", 	'365');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Newsletter löschen', '<p>Der Newsletter wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_multiselect_newsletter_loeschen_anzeigen(ziel) {
	cms_meldung_an('warnung', 'Newsletter löschen', '<p>Sollen die Newsletter wirklich gelöscht werden?<br>Dadurch werden alle Anmeldeformulare auf der Website automatisch entfernt. Die Empfängerlisten der Newsletter werden <b>unwiderruflich</b> geleert!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_multiselect_newsletter_loeschen()">Löschung durchführen</span></p>');
}

function cms_multiselect_newsletter_loeschen(ziel) {
	cms_multianfrage(365, ["Newsletter löschen", "Die Newsletter werden gelöscht"], {id: cms_multiselect_ids()}).then((rueckgabe) => {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Newsletter löschen', '<p>Der Newsletter wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	});
}

function cms_newsletter_alle_loeschen_vorbereiten() {
	cms_meldung_an('warnung', 'Alle Newsletter löschen', '<p>Sollen <b>alle</b> Newsletter gelöscht werden?<br>Sämtliche Anmeldeformulare auf der Website werden automatisch entfernt. Alle Empfängerlisten werden <b>unwiderruflich</b> geleert!</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_newsletter_alle_loeschen()">Löschung durchführen</span></p>');
}

function cms_newsletter_alle_loeschen() {
	cms_laden_an('Alle Newsletter löschen', 'Alle Newsletter werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("anfragenziel", 	'360');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == 'ERFOLG') {
			cms_meldung_an('erfolg', 'Newsletter löschen', '<p>Die Newsletter wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Newsletter\');">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}
	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_newsletter_senden(id) {
	cms_laden_an("Newsletter schreiben", "Daten werden gesammelt");

	var text = $("#cms_newsletter_text").val();

	var formulardaten = new FormData();
	formulardaten.append("id", 						id);
	formulardaten.append("text", 					text);
	formulardaten.append("anfragenziel", 	'363');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Newsletter schreiben', '<p>Der Newsletter wurde gesandt.</p>', '<p><span class="cms_button" onclick="location.reload();">OK</span></p>');
		} else if(rueckgabe == "BÖSE"){
			cms_meldung_an('fehler', 'Newsletter schreiben', '<p>Die Nachricht enthält verbotenen Code!<br>Der Vorfall wurde protokolliert</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">OK</span></p>');
		}
		else {
			cms_fehlerbehandlung(rueckgabe);
		}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
