function cms_wnewsletter_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Newsletter laden', 'Newsletter wird geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'281');

  function anfragennachbehandlung(rueckgabe) {
    if ((rueckgabe != "FEHLER") && (rueckgabe != "BERECHTIGUNG")) {
      document.getElementById('cms_website_'+feld+'_element_'+spalte+'_'+position).innerHTML = rueckgabe;
      cms_einblenden('cms_website_'+feld+'_element_'+spalte+'_'+position);
      cms_meldung_aus();
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}

function cms_wnewsletter_neu_speichern(zusatz) {
  cms_laden_an('Neues Anmeldeformular anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_newsletter_aktiv').value;
  var position = document.getElementById('cms_website_element_newsletter_position').value;
  var typ = document.getElementById('cms_website_element_newsletter_typ').value;
  var bezeichnung = document.getElementById('cms_website_element_newsletter_bezeichnung').value;
  var beschreibung = document.getElementById('cms_website_element_newsletter_beschreibung').value;

  var meldung = '<p>Das Anmeldeformular konnte nicht erstellt werden, denn ...</p><ul>';
	var fehler = false;

  if ((aktiv != 0) && (aktiv != 1)) {
    meldung += '<li>der Aktivitätsgrad ist ungültig.</li>';
		fehler = true;
  }

  var positionfehler = false;
	if (!Number.isInteger(parseInt(position))) {
		positionfehler = true;
	}
	else if (position < 0) {
		positionfehler = true;
	}

	if (positionfehler) {
		meldung += '<li>es wurde eine ungültige Position angegeben.</li>';
		fehler = true;
	}

  if(!cms_check_titel(bezeichnung)) {
    meldung += "<li>die Bezeichnung ist ungültig.</li>";
    fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Neues Anmeldeformular anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neues Anmeldeformular anlegen', 'Das neue Anmeldeformular wird angelegt.');

		var formulardaten = new FormData();
		formulardaten.append("aktiv",           aktiv);
		formulardaten.append("position",        position);
    formulardaten.append("typ",             typ);
    formulardaten.append("bezeichnung",     bezeichnung);
    formulardaten.append("beschreibung",  beschreibung);

		formulardaten.append("anfragenziel", 	'361');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neues Anmeldeformular anlegen', '<p>Das Anmeldeformular wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}

function cms_wnewsletter_bearbeiten_speichern(zusatz) {
  cms_laden_an('Anmeldeformular bearbeiten', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_newsletter_aktiv').value;
  var position = document.getElementById('cms_website_element_newsletter_position').value;
  var typ = document.getElementById('cms_website_element_newsletter_typ').value;
  var bezeichnung = document.getElementById('cms_website_element_newsletter_bezeichnung').value;
  var beschreibung = document.getElementById('cms_website_element_newsletter_beschreibung').value;

  var meldung = '<p>Das Anmeldeformular konnte nicht bearbeitet werden, denn ...</p><ul>';
	var fehler = false;

  if ((aktiv != 0) && (aktiv != 1)) {
    meldung += '<li>der Aktivitätsgrad ist ungültig.</li>';
		fehler = true;
  }

  var positionfehler = false;
	if (!Number.isInteger(parseInt(position))) {
		positionfehler = true;
	}
	else if (position < 0) {
		positionfehler = true;
	}

	if (positionfehler) {
		meldung += '<li>es wurde eine ungültige Position angegeben.</li>';
		fehler = true;
	}

  if(!cms_check_titel(bezeichnung)) {
    meldung += "<li>die Bezeichnung ist ungültig.</li>";
    fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Anmeldeformular bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Anmeldeformular bearbeiten', 'Das Anmeldeformular wird geändert.');

		var formulardaten = new FormData();
    formulardaten.append("aktiv",           aktiv);
		formulardaten.append("position",        position);
    formulardaten.append("typ",             typ);
    formulardaten.append("bezeichnung",     bezeichnung);
    formulardaten.append("beschreibung",    beschreibung);

		formulardaten.append("anfragenziel", 	'362');


    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Anmeldeformular bearbeiten', '<p>Das Anmeldeformular wurde bearbeitet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}

function cms_wnewsletter_anmelden(element) {
  var box = $(element).parents("table.cms_formular");

  var id = box.find(".cms_newsletter_id").val();
  var name = box.find(".cms_newsletter_name").val();
  var mail = box.find(".cms_newsletter_mail").val();

  var uid = box.find(".cms_spamschutz").data("uuid");
  var code = box.find(".cms_spamverhinderung").val();

  var meldung = '<p>Die Anmeldung konnte nicht gesendet werden, denn ...</p><ul>';
  var fehler = false;

  if(!cms_check_name(name)) {
    meldung += '<li>der Name ungültig.</li>';
    fehler = true;
  }

  if(!cms_check_mail(mail)) {
    meldung += '<li>die eMailadresse ungültig.</li>';
    fehler = true;
  }

  if(!code) {
    meldung += '<li>die Sicherheitsabfrage wurde nicht eingegeben.</li>';
    fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Anmeldung absenden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Zum Newsletter anmelden', 'Die Eingaben werden überprüft.');

    var formulardaten = new FormData();
    formulardaten.append("id", 	          id);
    formulardaten.append("name", 	        name);
    formulardaten.append("mail", 	        mail);
    formulardaten.append("uid", 	        uid);
    formulardaten.append("code", 	        code);

    formulardaten.append("anfragenziel", 	'287');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Zum Newsletter anmelden', '<p>Die Anmeldung wurde erfolgreich abgesandt!</p>', '<p><span class="cms_button" onclick="location.reload()">Zurück</span></p>');
      } else if(rueckgabe == "MAIL") {
        cms_meldung_an('fehler', 'Zum Newsletter anmelden', '<p>Die eMailadresse ist bereits registriert!</p>', '<p><span class="cms_button" onclick=" cms_meldung_aus()">Zurück</span></p>');
      }	else if (rueckgabe == "CODE") {
        cms_meldung_an('fehler', 'Kontaktformular absenden', '<p>Der Sicherheitscode ist nicht korrekt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus(); cms_neue_captcha(\''+uid+'\')">Korrigieren</span></p>');
      } else {cms_fehlerbehandlung(rueckgabe);}
    }

    cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
  }
}
