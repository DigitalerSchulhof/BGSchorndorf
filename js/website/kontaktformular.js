function cms_kontaktformulare_anzeigen (id, spalte, position, modus, zusatz) {
  cms_laden_an('Kontaktformular laden', 'Kontaktformular wird geladen.');
  if (id == '-') {var feld = 'neu';}
  else {var feld = 'bearbeiten';}
  cms_einblenden('cms_website_'+feld+'_menue_'+spalte+'_'+position);

  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("spalte", spalte);
  formulardaten.append("position", position);
  formulardaten.append("modus", modus);
  formulardaten.append("zusatz", zusatz);
  formulardaten.append("anfragenziel", 	'264');

  function anfragennachbehandlung(rueckgabe) {
    if ((rueckgabe != "FEHLER") && (rueckgabe != "BERECHTIGUNG")) {
      document.getElementById('cms_website_'+feld+'_element_'+spalte+'_'+position).innerHTML = rueckgabe;
      cms_einblenden('cms_website_'+feld+'_element_'+spalte+'_'+position);
      cms_meldung_aus();
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_kontaktformulare_neu_speichern(zusatz) {
  cms_laden_an('Neues Kontaktformular anlegen', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_kontaktformular_aktiv').value;
  var position = document.getElementById('cms_website_element_kontaktformular_position').value;
  var betreff = document.getElementById('cms_website_element_kontaktformular_betreff').value;
  var kopie = document.getElementById('cms_website_element_kontaktformular_kopie').value;
  var anhang = document.getElementById('cms_website_element_kontaktformular_anhang').value;
  var namen = [];
  var mails = [];
  var beschreibungen = [];

  var empfbox = $("#cms_kontaktformular_empfaenger");

  empfbox.children("table").each(function() {
    var name = $(this).find(".cms_kontaktformular_empfaenger_name").val();
    var mail = $(this).find(".cms_kontaktformular_empfaenger_mail").val();
    var beschreibung = $(this).find(".cms_kontaktformular_empfaenger_beschreibung").val();
    namen.push(name);
    mails.push(mail);
    beschreibungen.push(beschreibung);
  });

  var meldung = '<p>Das Kontaktformular konnte nicht erstellt werden, denn ...</p><ul>';
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

  if ((kopie != 0) && (kopie != 1) && (kopie != 2)) {
    meldung += '<li>die Kopieauswahl ist ungültig.</li>';
		fehler = true;
  }

  if ((anhang != 0) && (anhang != 1)) {
    meldung += '<li>die Anhangwahl ist ungültig.</li>';
		fehler = true;
  }

  if(!namen.length || !mails.length || !beschreibungen.length){
    meldung += '<li>es sind nicht genug Empfänger angegeben.</li>';
		fehler = true;
  }

  $.each(namen, function(i, n) {
    if(!cms_check_nametitel(n) || !n) {
      meldung += '<li>der Name eines Empfängers ist ungültig.</li>';
      fehler = true;
      return false;
    }
  })

  $.each(mails, function(i, n) {
    if(!cms_check_mail(n)  || !n) {
      meldung += '<li>die E-Mail-Adresse eines Empfängers ist ungültig.</li>';
      fehler = true;
      return false;
    }
  })

  var bes = []; // b64 Kopie von beschreibungen
  $.each(beschreibungen, function(i, b) {
    bes.push(btoa(encodeURIComponent(b)))
  })

  if (fehler) {
    cms_meldung_an('fehler', 'Neues Kontaktformular anlegen', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Neue Kontaktformular anlegen', 'Das neue Kontaktformular wird angelegt.');

		var formulardaten = new FormData();
		formulardaten.append("aktiv",           aktiv);
		formulardaten.append("position",        position);
		formulardaten.append("betreff",         betreff);
		formulardaten.append("kopie",           kopie);
		formulardaten.append("anhang",          anhang);
    formulardaten.append("namen",           namen);
    formulardaten.append("mails",           mails);
    formulardaten.append("beschreibungen",  bes);

		formulardaten.append("anfragenziel", 	'265');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Neues Kontaktformular anlegen', '<p>Das Kontaktformular wurde angelegt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}

function cms_kontaktformulare_bearbeiten_speichern(zusatz) {
  cms_laden_an('Kontaktformular bearbeiten', 'Die Eingaben werden überprüft.');
  var aktiv = document.getElementById('cms_website_element_kontaktformular_aktiv').value;
  var position = document.getElementById('cms_website_element_kontaktformular_position').value;
  var betreff = document.getElementById('cms_website_element_kontaktformular_betreff').value;
  var kopie = document.getElementById('cms_website_element_kontaktformular_kopie').value;
  var anhang = document.getElementById('cms_website_element_kontaktformular_anhang').value;
  var ids = [];
  var namen = [];
  var mails = [];
  var beschreibungen = [];

  var empfbox = $("#cms_kontaktformular_empfaenger");

  empfbox.children("table").each(function() {
    var id = $(this).find(".cms_kontaktformular_empfaenger_id").val();
    var name = $(this).find(".cms_kontaktformular_empfaenger_name").val();
    var mail = $(this).find(".cms_kontaktformular_empfaenger_mail").val();
    var beschreibung = $(this).find(".cms_kontaktformular_empfaenger_beschreibung").val();
    ids.push(id);
    namen.push(name);
    mails.push(mail);
    beschreibungen.push(beschreibung);
  });

  var meldung = '<p>Das Kontaktformular konnte nicht bearbeitet werden, denn ...</p><ul>';
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

  if ((kopie != 0) && (kopie != 1) && (kopie != 2)) {
    meldung += '<li>die Kopieauswahl ist ungültig.</li>';
		fehler = true;
  }

  if ((anhang != 0) && (anhang != 1)) {
    meldung += '<li>die Anhangwahl ist ungültig.</li>';
		fehler = true;
  }

  if(!namen.length || !mails.length || !beschreibungen.length){
    meldung += '<li>es sind nicht genug Empfänger angegeben.</li>';
		fehler = true;
  }

  $.each(namen, function(i, n) {
    if(!cms_check_nametitel(n) || !n) {
      meldung += '<li>der Name eines Empfängers ist ungültig.</li>';
      fehler = true;
      return false;
    }
  })

  $.each(mails, function(i, n) {
    if(!cms_check_mail(n)  || !n) {
      meldung += '<li>die E-Mail-Adresse eines Empfängers ist ungültig.</li>';
      fehler = true;
      return false;
    }
  })

  var bes = []; // b64 Kopie von beschreibungen
  $.each(beschreibungen, function(i, b) {
    bes.push(btoa(encodeURIComponent(b)))
  })

  if (fehler) {
    cms_meldung_an('fehler', 'Kontaktformular bearbeiten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Kontaktformular bearbeiten', 'Das Kontaktformular wird geändert.');

		var formulardaten = new FormData();
    formulardaten.append("aktiv",           aktiv);
		formulardaten.append("position",        position);
		formulardaten.append("betreff",         betreff);
		formulardaten.append("kopie",           kopie);
		formulardaten.append("anhang",          anhang);
    formulardaten.append("ids",             ids);
    formulardaten.append("namen",           namen);
    formulardaten.append("mails",           mails);
    formulardaten.append("beschreibungen",  bes);
		formulardaten.append("anfragenziel", 	'266');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Kontaktformular bearbeiten', '<p>Das Kontaktformular wurde bearbeitet.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/Aktuell/'+zusatz+'\');">Zurück zur Seite</span></p>');
      }
  		else {cms_fehlerbehandlung(rueckgabe);}
  	}

  	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}

function cms_kontaktformular_empfaenger_hinzufuegen() {
  var box = $("#cms_kontaktformular_empfaenger");

  var form = $("<table></table>", {class: "cms_formular"})
    .append("<tr><th>Name: </th><td><input type=\"text\" class=\"cms_kontaktformular_empfaenger_name\"></td></tr>")
    .append("<tr><th>E-Mail-Adresse: </th><td><input type=\"text\" class=\"cms_kontaktformular_empfaenger_mail\"></td></tr>")
    .append("<tr><th>Beschreibung: </th><td><textarea class=\"cms_kontaktformular_empfaenger_beschreibung\"></textarea></td></tr>")
    .append("<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_kontaktformular_empfaenger_loeschen(this);\">- Empfänger löschen</span></td></tr>");

  box.append(form);
}

function cms_kontaktformular_empfaenger_loeschen(element) {
  $(element).parents("table").remove();
}

function cms_kontaktformular_absenden(element) {
  var box = $(element).parents("table.cms_formular");

  var id = box.find(".cms_kontaktformular_id").val();
  var absender = box.find(".cms_kontaktformular_absender").val();
  var mail = box.find(".cms_kontaktformular_mail").val();
  var betreff = box.find(".cms_kontaktformular_betreff").val();
  var nachricht = box.find(".cms_kontaktformular_nachricht").val();
  var anhang = box.find(".cms_kontaktformular_anhang").prop("files");
  var anhang = null;
  if(box.find(".cms_kontaktformular_anhang").length)
    anhang = box.find(".cms_kontaktformular_anhang").prop("files");
  var kopie = null;
  if(box.find(".cms_kontaktformular_kopie").length)
    kopie = box.find(".cms_kontaktformular_kopie").val();
  var empfaenger = box.find(".cms_kontaktformular_empfaenger").val();
  var uid = box.find(".cms_spamschutz").data("uuid");
  var code = box.find(".cms_spamverhinderung").val();

  var meldung = '<p>Das Kontaktformular konnte nicht gesendet werden, denn ...</p><ul>';
	var fehler = false;

  if(!cms_check_nametitel(absender) || !absender) {
    meldung += '<li>der Absender ungültig.</li>';
    fehler = true;
  }

  if(!cms_check_mail(mail)) {
    meldung += '<li>die E-Mail-Adresse ungültig.</li>';
    fehler = true;
  }

  if(!betreff) {
    meldung += '<li>der Betreff ungültig.</li>';
    fehler = true;
  }

  if(!nachricht) {
    meldung += '<li>die Nachricht ungültig.</li>';
    fehler = true;
  }

  if(!cms_check_ganzzahl(empfaenger, 0)) {
    meldung += '<li>der Empfänger ungültig.</li>';
    fehler = true;
  }

  if(!code) {
    meldung += '<li>die Sicherheitsabfrage wurde nicht eingegeben.</li>';
    fehler = true;
  }

  if (fehler) {
    cms_meldung_an('fehler', 'Kontaktformular absenden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
  }
  else {
    cms_laden_an('Kontaktformular absenden', 'Die Eingaben werden überprüft.');

    var formulardaten = new FormData();
    formulardaten.append("id", 	          id);
    formulardaten.append("absender", 	    absender);
    formulardaten.append("mail", 	        mail);
    formulardaten.append("betreff", 	    betreff);
    formulardaten.append("nachricht", 	  nachricht);
    if(kopie)
      formulardaten.append("kopie", 	    kopie);

    var max = 0;
    if(anhang && anhang.length) {
      for(var i = 0; i < anhang.length; i++) {
        var a = anhang[i];
        formulardaten.append(("anhang_"+i),  a);
        max++;
      }
    }
    formulardaten.append("anhaenge",       max)
    formulardaten.append("empfaenger", 	  empfaenger);
    formulardaten.append("uid", 	        uid);
    formulardaten.append("code", 	        code);
    formulardaten.append("anfragenziel", 	'267');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == "ERFOLG") {
        cms_meldung_an('erfolg', 'Kontaktformular absenden', '<p>Das Kontaktformular erfolgreich abgesandt!</p>', '<p><span class="cms_button" onclick=" cms_meldung_aus(); cms_neue_captcha(\''+uid+'\')">Zurück</span></p>');
      }	else if (rueckgabe == "CODE") {
        cms_meldung_an('fehler', 'Kontaktformular absenden', '<p>Der Sicherheitscode ist nicht korrekt.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus(); cms_neue_captcha(\''+uid+'\')">Korrigieren</span></p>');
      } else if (rueckgabe == "BÖSE") {
        cms_meldung_an('fehler', 'Kontaktformular absenden', '<p>Die Nachricht enthält verboten Programmcode.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus(); cms_neue_captcha(\''+uid+'\')">Korrigieren</span></p>');
      }

      else {cms_fehlerbehandlung(rueckgabe);}
    }

    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
  }
}
