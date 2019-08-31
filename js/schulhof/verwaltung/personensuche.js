function cms_personensuche(id, art, gruppe) {
  var meldung = document.getElementById(id+'_suchergebnis');
  meldung.innerHTML = '<img src="res/laden/standard.gif"><br><br>Die Suche läuft. Bitte warten ...';
  document.getElementById(id+'_suchergebnis').style.textAlign = 'center';

  var vorname = document.getElementById(id+'_personensuche_vorname').value;
	var nachname = document.getElementById(id+'_personensuche_nachname').value;
	var gewaehlt  = document.getElementById(id+'_personensuche_gewaehlt').value;
  var fehler = false;

	var schueler = "-";
	var eltern = "-";
	var lehrer = "-";
	var verwaltung = "-";
	var extern = "-";
	var listen = "-";

  if (vorname.length > 0) {
    if (!cms_check_name(vorname)) {
      fehler = true;
      meldung.innerHTML = "Im Suchmuster des Vornamens sind ungültige Zeichen enthalten.";
    }
  }
  if (nachname.length > 0) {
    if (!cms_check_name(nachname)) {
      fehler = true;
      meldung.innerHTML = "Im Suchmuster des Nachnamens sind ungültige Zeichen enthalten.";
    }
  }

  if ((art != 'mitglieder') && (art != 'aufsicht')) {
    fehler = true;
    meldung.innerHTML = "Da hat jemand am Code rumgebastelt und war sehr böse!!";
  }

	if (document.getElementById(id+'_personensuche_s')) 	{schueler   = document.getElementById(id+'_personensuche_s').value;}
	if (document.getElementById(id+'_personensuche_e'))   {eltern     = document.getElementById(id+'_personensuche_e').value;}
	if (document.getElementById(id+'_personensuche_l')) 	{lehrer     = document.getElementById(id+'_personensuche_l').value;}
	if (document.getElementById(id+'_personensuche_v')) 	{verwaltung = document.getElementById(id+'_personensuche_v').value;}
	if (document.getElementById(id+'_personensuche_x')) 	{extern     = document.getElementById(id+'_personensuche_x').value;}

	var formulardaten = new FormData();
	formulardaten.append("schueler",   		schueler);
	formulardaten.append("eltern",     		eltern);
	formulardaten.append("lehrer",     		lehrer);
	formulardaten.append("verwaltung", 		verwaltung);
	formulardaten.append("extern", 		    extern);
	formulardaten.append("vorname",    		vorname);
	formulardaten.append("nachname",   		nachname);
  formulardaten.append("gewaehlt",      gewaehlt);
	formulardaten.append("feld",     	    id);
	formulardaten.append("art",           art);
	formulardaten.append("gruppe",        gruppe);
	formulardaten.append("anfragenziel", 	'130');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "FEHLER") {
      meldung.innerHTML = "Es ist ein Fehler aufgetreten, Bitte den <a href=\"Website/Feedback\">Administrator informieren</a>.";
    }
		else if (rueckgabe.slice(0,6) == 'ERFOLG') {
      var ausgabe = "";
      var personen = rueckgabe.split(';');
      for (var i=1; i<personen.length-1; i++) {
        var person = personen[i].split(',');
        var personenid = person[0];
        var personenart = person[1];
        var personenname = person[2];
        var personenbez = "Schüler";
        var personenicon = "schueler.png";
        if (personenart == 'l') {personenbez = 'Lehrer'; personenicon = 'lehrer.png';}
        if (personenart == 's') {personenbez = 'Schüler'; personenicon = 'schueler.png';}
        if (personenart == 'e') {personenbez = 'Eltern'; personenicon = 'elter.png';}
        if (personenart == 'v') {personenbez = 'Verwaltungsangestellte'; personenicon = 'verwaltung.png';}
        if (personenart == 'x') {personenbez = 'Extern'; personenicon = 'extern.png';}
        ausgabe += "<span class=\"cms_button\" onclick=\"cms_personensuche_wahl_"+art+"('"+id+"', '"+personenid+"', '"+personenart+"', '"+personenname+"', '"+gruppe+"');\"><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">"+personenbez+"</span><img src=\"res/icons/klein/"+personenicon+"\"></span> "+personenname+"</span>";
      }
      meldung.style.textAlign = 'left';
      meldung.innerHTML = ausgabe;
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
	}

	cms_ajaxanfrage (fehler, formulardaten, anfragennachbehandlung);
}


function cms_personensuche_wahl_mitglieder(id, personenid, personenart, personenname, gruppe) {
  var tabelleF = document.getElementById(id+'_F');
  var vorsitzF = document.getElementById(id+'_vorsitz_F');

  var tabellencode = "";
  var icon = "schueler.png";
  if (personenart == 'l') {icon = 'lehrer.png';}
  if (personenart == 'e') {icon = 'elter.png';}
  if (personenart == 'v') {icon = 'verwaltung.png';}
  if (personenart == 'x') {icon = 'extern.png';}
  var vorsitzcode = cms_togglebutton_generieren(id+'_personensuche_vorsitz_'+personenid, personenname, 0, 'cms_personensuche_vorsitz_aktualisieren(\''+id+'\')')+' ';
  tabellencode += "<tr id=\""+id+'_personensuche_mitglieder_'+personenid+"\">";
  tabellencode += "<td><span class=\"cms_tabellenicon\"><img src=\"res/icons/klein/"+icon+"\"></span></td>";
  tabellencode += "<td>"+personenname+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_upload_'+personenid,  0)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_download_'+personenid,  1)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_loeschen_'+personenid,  0)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_umbenennen_'+personenid,  0)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_termine_'+personenid,  0)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_blogeintraege_'+personenid,  0)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_chatten_'+personenid,  1)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_chat_loeschen_'+personenid,  0)+"</td>";
  tabellencode += "<td>"+cms_schieber_generieren(id+'_personensuche_mitglieder_chat_bannen_'+personenid,  0)+"</td>";
  tabellencode += "<td><span class=\"cms_button_nein\" onclick=\"cms_personensuche_entfernen_mitglieder('"+id+"', '"+personenid+"', '"+gruppe+"')\"><span class=\"cms_hinweis\">Person entfernen</span>–</span></td>";
  tabellencode += "</tr>";

  cms_id_eintragen(id+'_personensuche_gewaehlt', personenid);
  tabelleF.innerHTML += tabellencode;
  vorsitzF.innerHTML += vorsitzcode;
  cms_personensuche(id, 'mitglieder', gruppe);
}

function cms_personensuche_entfernen_mitglieder(id, personenid, gruppe) {
  cms_id_entfernen(id+'_personensuche_gewaehlt', personenid);
  cms_id_entfernen(id+'_personensuche_gewaehlt2', personenid);

  var tabelle = document.getElementById(id+'_F');
  var zeile = document.getElementById(id+'_personensuche_mitglieder_'+personenid);
  tabelle.removeChild(zeile);
  var vorsitzF = document.getElementById(id+'_vorsitz_F');
  var vorsitzK = document.getElementById(id+'_personensuche_vorsitz_'+personenid+'_K');
  var vorsitzKF = document.getElementById(id+'_personensuche_vorsitz_'+personenid);
  vorsitzF.removeChild(vorsitzK);
  vorsitzF.removeChild(vorsitzKF);

  cms_personensuche(id, 'mitglieder', gruppe);
}

function cms_personensuche_vorsitz_aktualisieren(id) {
  var allemitglieder = document.getElementById(id+'_personensuche_gewaehlt').value;
  var mitglieder = allemitglieder.split('|');
  var vorsitz = "";
  for (var i = 1; i<mitglieder.length; i++) {
    var feld = document.getElementById(id+'_personensuche_vorsitz_'+mitglieder[i]);
    if (feld.value == '1') {
      vorsitz += '|'+mitglieder[i];
    }
  }
  document.getElementById(id+'_personensuche_gewaehlt2').value = vorsitz;
}

function cms_personensuche_wahl_aufsicht(id, personenid, personenart, personenname, gruppe) {
  var aufsichtF = document.getElementById(id+'_F');

  var aufsichtcode = cms_togglebutton_generieren(id+'_personensuche_aufsicht_'+personenid, personenname, 1, 'cms_personensuche_entfernen_aufsicht(\''+id+'\', \''+personenid+'\', \''+gruppe+'\')')+' ';
  cms_id_eintragen(id+'_personensuche_gewaehlt', personenid);
  aufsichtF.innerHTML += aufsichtcode;
  cms_personensuche(id, 'aufsicht', gruppe);
}

function cms_personensuche_entfernen_aufsicht(id, personenid, gruppe) {
  cms_id_entfernen(id+'_personensuche_gewaehlt', personenid);

  var aufsichtF = document.getElementById(id+'_F');
  var aufsichtK = document.getElementById(id+'_personensuche_aufsicht_'+personenid+'_K');
  var aufsichtKF = document.getElementById(id+'_personensuche_aufsicht_'+personenid);
  aufsichtF.removeChild(aufsichtK);
  aufsichtF.removeChild(aufsichtKF);

  cms_personensuche(id, 'aufsicht', gruppe);
}

function cms_personenprofil(id) {
  cms_laden_an('Das Profil wird vorbereitet', 'Die Eingaben werden überprüft.');
	var formulardaten = new FormData();
	formulardaten.append("person",   		id);
	formulardaten.append("anfragenziel", 	'240');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == 'ERFOLG') {
      cms_link('Schulhof/Profile');
    }
    else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
// 0 = bereit, 1 = countdown, 2 = gesandt
var cms_umarmen_s = 0;
var countdown;
function cms_umarmen(id, anonym) {
  var k = $("#cms_umarmen");
  // Muss gesetzt sein
  if(anonym === true || anonym === false) {
    cms_laden_an('Die Person wird umarmt', 'Die Eingaben werden überprüft.');
    var formulardaten = new FormData();
    formulardaten.append("person",   		   id);
    formulardaten.append("anonym",   		   anonym);
    formulardaten.append("anfragenziel", 	 '340');

    function anfragennachbehandlung(rueckgabe) {
      if (rueckgabe == 'ERFOLG') {
        // Nichts tun :)
        cms_laden_aus();
      }
      else if(rueckgabe == "HALT") {
        cms_laden_aus();
        cms_meldung_an('warnung', 'Nicht so schnell', '<p>Es sind zu schnell Anfragen eingegangen</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Okay</span></p>');
      }
      else {cms_fehlerbehandlung(rueckgabe);}
    }

    cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
    k.html("Umarmt! (>‿♡)");
    k.removeClass("cms_button_anonymFragezeichen");
    k.click(function() {
      // [E]
      console.log("Da freut sich nun jemand!");
    })
    cms_umarmen_s = 2;
    return;
  }
  if(cms_umarmen_s == 1) {
    // Anonym
    clearInterval(countdown);
    cms_umarmen(id, true);
  } else if(cms_umarmen_s == 0) {
    cms_umarmen_s = 1;
    var anonym = 3;

    k.html("Anonym umarmen? 3");
    k.addClass("cms_button_anonymFragezeichen");

    countdown = setInterval(function() {
      k.html("Anonym umarmen? " + --anonym);
      if(anonym == 0) {
        clearInterval(countdown);
        cms_umarmen(id, false);
      }
    }, 1000);
  }
}

function cms_personensuche_mail(id) {
  var meldung = document.getElementById(id+'_suchergebnis');
  meldung.innerHTML = '<img src="res/laden/standard.gif"><br><br>Die Suche läuft. Bitte warten ...';
  document.getElementById(id+'_suchergebnis').style.textAlign = 'center';

  var vorname = document.getElementById(id+'_personensuche_vorname').value;
	var nachname = document.getElementById(id+'_personensuche_nachname').value;
  var schueler   = document.getElementById(id+'_personensuche_s').value;
	var eltern     = document.getElementById(id+'_personensuche_e').value;
	var lehrer     = document.getElementById(id+'_personensuche_l').value;
	var verwaltung = document.getElementById(id+'_personensuche_v').value;
	var extern = document.getElementById(id+'_personensuche_x').value;
	var gewaehlt  = document.getElementById(id+'_personensuche_gewaehlt').value;
  var fehler = false;

	if (vorname.length > 0) {
    if (!cms_check_name(vorname)) {
      fehler = true;
      meldung.innerHTML = "Im Suchmuster des Vornamens sind ungültige Zeichen enthalten.";
    }
  }
  if (nachname.length > 0) {
    if (!cms_check_name(nachname)) {
      fehler = true;
      meldung.innerHTML = "Im Suchmuster des Nachnamens sind ungültige Zeichen enthalten.";
    }
  }

  if ((!cms_check_toggle(schueler)) || (!cms_check_toggle(eltern)) || (!cms_check_toggle(lehrer)) || (!cms_check_toggle(verwaltung)) || (!cms_check_toggle(extern))) {
    fehler = true;
    meldung.innerHTML = "Bei den Personengruppen wurde eine ungültige Auswahl getroffen.";
  }

  if (gewaehlt.split('|').length - 1 > 2) {document.getElementById('cms_bloghinweis').style.display = 'block';}
  else {document.getElementById('cms_bloghinweis').style.display = 'none';}

	var formulardaten = new FormData();
	formulardaten.append("schueler",   		schueler);
	formulardaten.append("eltern",     		eltern);
	formulardaten.append("lehrer",     		lehrer);
	formulardaten.append("verwaltung", 		verwaltung);
	formulardaten.append("extern", 	    	extern);
	formulardaten.append("vorname",    		vorname);
	formulardaten.append("nachname",   		nachname);
  formulardaten.append("gewaehlt",      gewaehlt);
	formulardaten.append("feld",     	    id);
	formulardaten.append("anfragenziel", 	'16');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "FEHLER") {
      meldung.innerHTML = "Es ist ein Fehler aufgetreten, Bitte den <a href=\"Website/Feedback\">Administrator informieren</a>.";
    }
		else if (rueckgabe.slice(0,6) == 'ERFOLG') {
      var ausgabe = "";
      var personen = rueckgabe.split(';');
      for (var i=1; i<personen.length-1; i++) {
        var person = personen[i].split(',');
        var personenid = person[0];
        var personenart = person[1];
        var personenname = person[2];
        var personenbez = "Schüler";
        var personenicon = "schueler.png";
        if (personenart == 'l') {personenbez = 'Lehrer'; personenicon = 'lehrer.png';}
        if (personenart == 's') {personenbez = 'Schüler'; personenicon = 'schueler.png';}
        if (personenart == 'e') {personenbez = 'Eltern'; personenicon = 'elter.png';}
        if (personenart == 'v') {personenbez = 'Verwaltungsangestellte'; personenicon = 'verwaltung.png';}
        if (personenart == 'x') {personenbez = 'Externe'; personenicon = 'extern.png';}
        ausgabe += "<span class=\"cms_button\" onclick=\"cms_personensuche_mail_wahl('"+id+"', '"+personenid+"', '"+personenart+"', '"+personenname+"');\"><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">"+personenbez+"</span><img src=\"res/icons/klein/"+personenicon+"\"></span> "+personenname+"</span>";
      }
      meldung.style.textAlign = 'left';
      meldung.innerHTML = ausgabe;
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
	}

	cms_ajaxanfrage (fehler, formulardaten, anfragennachbehandlung);
}



function cms_personensuche_mail_wahl(id, personenid, personenart, personenname) {
  var mailF = document.getElementById(id+'_F');
  var icon = '';
  var hinweis = ''
  if (personenart == 'l') {icon = 'lehrer'; hinweis = 'Lehrer';}
  else if (personenart == 's') {icon = 'schueler'; hinweis = 'Schüler';}
  else if (personenart == 'e') {icon = 'eltern'; hinweis = 'Eltern';}
  else if (personenart == 'v') {icon = 'verwaltung'; hinweis = 'Verwaltungsangestellte';}
  else if (personenart == 'x') {icon = 'extern'; hinweis = 'Externe';}
  var code = '<span class="cms_icon_klein_o"><span class="cms_hinweis">'+hinweis+'</span><img src="res/icons/klein/'+icon+'.png"></span> '+personenname;
  var mailcode = cms_togglebutton_generieren(id+'_personensuche_mail_'+personenid, code, 1, 'cms_personensuche_entfernen_mail(\''+id+'\', \''+personenid+'\')')+' ';
  cms_id_eintragen(id+'_personensuche_gewaehlt', personenid);
  mailF.innerHTML += mailcode;
  cms_personensuche_mail(id);
}

function cms_personensuche_entfernen_mail(id, personenid) {
  cms_id_entfernen(id+'_personensuche_gewaehlt', personenid);

  var mailF = document.getElementById(id+'_F');
  var mailK = document.getElementById(id+'_personensuche_mail_'+personenid+'_K');
  var mailKF = document.getElementById(id+'_personensuche_mail_'+personenid);
  mailF.removeChild(mailK);
  mailF.removeChild(mailKF);

  cms_personensuche_mail(id);
}


function cms_personenliste_laden() {
	var tabbody = document.getElementById('cms_personenliste');
	tabbody.innerHTML = '<tr><td class=\"cms_notiz\" colspan=\"8\"><img src="res/laden/standard.gif"><br>Die Suche wird verarbeitet. Je nach Verbindung und Schulgröße kann dies etwas dauern.</td></tr>';
	var schueler   = document.getElementById('cms_personenliste_s').value;
	var eltern     = document.getElementById('cms_personenliste_e').value;
	var lehrer     = document.getElementById('cms_personenliste_l').value;
	var verwaltung = document.getElementById('cms_personenliste_v').value;
	var extern     = document.getElementById('cms_personenliste_x').value;
	var nname      = document.getElementById('cms_personenliste_nname').value;
	var vname      = document.getElementById('cms_personenliste_vname').value;
	var klasse     = document.getElementById('cms_personenliste_klasse').value;

	var formulardaten = new FormData();
	formulardaten.append("schueler",   schueler);
	formulardaten.append("lehrer",     lehrer);
	formulardaten.append("eltern",     eltern);
	formulardaten.append("verwaltung", verwaltung);
	formulardaten.append("extern", extern);
	formulardaten.append("nname",   nname);
	formulardaten.append("vname",   vname);
	formulardaten.append("klasse",  klasse);
	formulardaten.append("anfragenziel", 	'120');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.slice(0,4) == '<tr>') {
			tabbody.innerHTML = rueckgabe;
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_personensuche_schuljahr(id) {
  var meldung = document.getElementById(id+'_suchergebnis');
  meldung.innerHTML = '<img src="res/laden/standard.gif"><br><br>Die Suche läuft. Bitte warten ...';
  document.getElementById(id+'_suchergebnis').style.textAlign = 'center';

  var vorname    = document.getElementById(id+'_personensuche_vorname').value;
	var nachname   = document.getElementById(id+'_personensuche_nachname').value;
	var erlaubt    = document.getElementById(id+'_personensuche_erlaubt').value;
	var gewaehlt   = document.getElementById(id+'_personensuche_gewaehlt').value;
  var fehler = false;

  if (erlaubt.match(/e/)) {var eltern = document.getElementById(id+'_personensuche_e').value;} else {var eltern = '0';}
  if (erlaubt.match(/s/)) {var schueler = document.getElementById(id+'_personensuche_s').value;} else {var schueler = '0';}
  if (erlaubt.match(/l/)) {var lehrer = document.getElementById(id+'_personensuche_l').value;} else {var lehrer = '0';}
  if (erlaubt.match(/v/)) {var verwaltung = document.getElementById(id+'_personensuche_v').value;} else {var verwaltung = '0';}
  if (erlaubt.match(/x/)) {var extern = document.getElementById(id+'_personensuche_x').value;} else {var extern = '0';}

	if (vorname.length > 0) {
    if (!cms_check_name(vorname)) {
      fehler = true;
      meldung.innerHTML = "Im Suchmuster des Vornamens sind ungültige Zeichen enthalten.";
    }
  }
  if (nachname.length > 0) {
    if (!cms_check_name(nachname)) {
      fehler = true;
      meldung.innerHTML = "Im Suchmuster des Nachnamens sind ungültige Zeichen enthalten.";
    }
  }

  if ((!cms_check_toggle(schueler)) || (!cms_check_toggle(eltern)) || (!cms_check_toggle(lehrer)) || (!cms_check_toggle(verwaltung)) || (!cms_check_toggle(extern))) {
    fehler = true;
    meldung.innerHTML = "Bei den Personengruppen wurde eine ungültige Auswahl getroffen.";
  }

	var formulardaten = new FormData();
	formulardaten.append("erlaubt",       erlaubt);
	formulardaten.append("schueler",   		schueler);
	formulardaten.append("eltern",     		eltern);
	formulardaten.append("lehrer",     		lehrer);
	formulardaten.append("verwaltung", 		verwaltung);
	formulardaten.append("extern", 	    	extern);
	formulardaten.append("vorname",    		vorname);
	formulardaten.append("nachname",   		nachname);
  formulardaten.append("gewaehlt",      gewaehlt);
	formulardaten.append("feld",     	    id);
	formulardaten.append("anfragenziel", 	'208');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "FEHLER") {
      meldung.innerHTML = "Es ist ein Fehler aufgetreten, Bitte den <a href=\"Website/Feedback\">Administrator informieren</a>.";
    }
		else if (rueckgabe.slice(0,6) == 'ERFOLG') {
      var ausgabe = "";
      var personen = rueckgabe.split(';');
      for (var i=1; i<personen.length-1; i++) {
        var person = personen[i].split(',');
        var personenid = person[0];
        var personenart = person[1];
        var personenname = person[2];
        var personenbez = "Schüler";
        var personenicon = "schueler.png";
        if (personenart == 'l') {personenbez = 'Lehrer'; personenicon = 'lehrer.png';}
        if (personenart == 's') {personenbez = 'Schüler'; personenicon = 'schueler.png';}
        if (personenart == 'e') {personenbez = 'Eltern'; personenicon = 'elter.png';}
        if (personenart == 'v') {personenbez = 'Verwaltungsangestellte'; personenicon = 'verwaltung.png';}
        if (personenart == 'x') {personenbez = 'Externe'; personenicon = 'extern.png';}
        ausgabe += "<span class=\"cms_button\" onclick=\"cms_personensuche_personhinzu_wahl('"+id+"', '"+personenid+"', '"+personenart+"', '"+personenname+"');\"><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">"+personenbez+"</span><img src=\"res/icons/klein/"+personenicon+"\"></span> "+personenname+"</span>";
      }
      meldung.style.textAlign = 'left';
      meldung.innerHTML = ausgabe;
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
	}

	cms_ajaxanfrage (fehler, formulardaten, anfragennachbehandlung);
}


function cms_personensuche_personhinzu_wahl(id, personenid, personenart, personenname) {
  var mailF = document.getElementById(id+'_F');
  var icon = '';
  var hinweis = ''
  if (personenart == 'l') {icon = 'lehrer'; hinweis = 'Lehrer';}
  else if (personenart == 's') {icon = 'schueler'; hinweis = 'Schüler';}
  else if (personenart == 'e') {icon = 'eltern'; hinweis = 'Eltern';}
  else if (personenart == 'v') {icon = 'verwaltung'; hinweis = 'Verwaltungsangestellte';}
  else if (personenart == 'x') {icon = 'extern'; hinweis = 'Externe';}
  var code = '<span class="cms_icon_klein_o"><span class="cms_hinweis">'+hinweis+'</span><img src="res/icons/klein/'+icon+'.png"></span> '+personenname;
  var mailcode = cms_togglebutton_generieren(id+'_personensuche_schuljahr_'+personenid, code, 1, 'cms_personensuche_personhinzu_entfernen(\''+id+'\', \''+personenid+'\')')+' ';
  cms_id_eintragen(id+'_personensuche_gewaehlt', personenid);
  mailF.innerHTML += mailcode;
  cms_personensuche_schuljahr(id);
}

function cms_personensuche_personhinzu_entfernen(id, personenid) {
  cms_id_entfernen(id+'_personensuche_gewaehlt', personenid);

  var mailF = document.getElementById(id+'_F');
  var mailK = document.getElementById(id+'_personensuche_schuljahr_'+personenid+'_K');
  var mailKF = document.getElementById(id+'_personensuche_schuljahr_'+personenid);
  mailF.removeChild(mailK);
  mailF.removeChild(mailKF);

  cms_personensuche_schuljahr(id);
}
