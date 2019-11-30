function cms_einblenden(id, art) {
	art = art || 'block';
	document.getElementById(id).style.display = art;
}

// jQuery-Objekt möglich
function cms_ausblenden(id) {
<<<<<<< ours
	if(id.jquery)	// Truthy Version-String wenn gültiges jQuery Objekt
		id.css("display", "none");
	else
		$("#"+id).css("display", "none");
=======
	if (document.getElementById(id)) {
		document.getElementById(id).style.display = 'none';
	}
>>>>>>> theirs
}

function cms_toggle_anzeigen(id, art) {
	var element = document.getElementById(id);
	art = art || 'block';
  if (element.style.display == "none") {element.style.display = art;}
	else {element.style.display = "none";}
}


function cms_togglebutton_anzeigen(id, idknopf, inhaltaus, inhaltan, art) {
	var element = document.getElementById(id);
	var knopf = document.getElementById(idknopf);
	art = art || 'block';

  if (element.style.display == "none") {
		element.style.display = art;
		knopf.innerHTML = inhaltan;
	}
	else {
		element.style.display = "none";
		knopf.innerHTML = inhaltaus;
	}
}


function cms_toggle_klasse(idknopf, klasse, idfeld, eingabe) {
	eingabe = eingabe || false;
	idfeld = idfeld || '';
	var element = document.getElementById(idknopf);
  var klassen = element.className.split(" ");
  var i = klassen.indexOf(klasse);

  if (i >= 0) {
		klassen.splice(i, 1);
    element.className = klassen.join(" ");
		if (eingabe) {
			var feld = document.getElementById(idfeld);
			feld.value = 0;
		}
	}
	else {
		klassen.push(klasse);
    element.className = klassen.join(" ");
		if (eingabe) {
			var feld = document.getElementById(idfeld);
			feld.value = 1;
		}
	}
}

function cms_klasse_dazu (id, klassedazu) {
	var element = document.getElementById(id);
  var klassen = element.className.split(" ");
  var i = klassen.indexOf(klassedazu);

  if (i < 0) {
		klassen.push(klassedazu);
    element.className = klassen.join(" ");
	}
}

function cms_klasse_dazu_wennklasse (klassenname, klassedazu) {
	var elemente = document.getElementsByClassName(klassenname);
	for (var j = 0; j < elemente.length; j++) {
		var klassen = elemente[j].className.split(" ");
	  var i = klassen.indexOf(klassedazu);

	  if (i < 0) {
			klassen.push(klassedazu);
	    elemente[j].className = klassen.join(" ");
		}
	}
}

function cms_klasse_weg (id, klasseweg) {
	var element = document.getElementById(id);
  var klassen = element.className.split(" ");
  var i = klassen.indexOf(klasseweg);

  if (i >= 0) {
		klassen.splice(i, 1);
    element.className = klassen.join(" ");
	}
}

function cms_klasse_weg_wennklasse (klassenname, klasseweg) {
	var elemente = document.getElementsByClassName(klassenname);
	for (var j = 0; j < elemente.length; j++) {
		var klassen = elemente[j].className.split(" ");
	  var i = klassen.indexOf(klasseweg);

	  if (i >= 0) {
			klassen.splice(i, 1);
	    elemente[j].className = klassen.join(" ");
		}
	}
}

function cms_schulhof_mehr (klasse) {
	var felder = document.getElementsByClassName(klasse+'_mehrF');
	for (var i = 0; i < felder.length; i++) {
	    felder[i].style.display = "table-row";
	}
	cms_ausblenden(klasse+'_mehr');
	cms_einblenden(klasse+'_weniger', 'inline-block');
}

function cms_schulhof_weniger (klasse) {
	var felder = document.getElementsByClassName(klasse+'_mehrF');
	for (var i = 0; i < felder.length; i++) {
	    felder[i].style.display = "none";
	}
	cms_ausblenden(klasse+'_weniger');
	cms_einblenden(klasse+'_mehr', 'inline-block');
}


function cms_hauptnavigation_einblenden(id) {
	var feldo = document.getElementById('cms_hauptnavigation_'+id+'_o');
	var feldl = document.getElementById('cms_hauptnavigation_'+id+'_l');

	feldo.style.maxHeight = '1000px';
	feldl.style.opacity = '1';
}


function cms_hauptnavigation_ausblenden(id) {
	var feldo = document.getElementById('cms_hauptnavigation_'+id+'_o');
	var feldl = document.getElementById('cms_hauptnavigation_'+id+'_l');

	feldo.style.maxHeight = '';
	feldl.style.opacity = '';
}


function cms_schulhof_ausklappen (knopffeldid, klasse) {
	var felder = document.getElementsByClassName(klasse);
	for (var i = 0; i < felder.length; i++) {
	    felder[i].style.display = "block";
	}
	var knopffeld = document.getElementById(knopffeldid);
	var code = '<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_einklappen(\''+knopffeldid+'\', \''+klasse+'\');\"><span class=\"cms_hinweis\">Einklappen</span><img src=\"res/icons/klein/einklappen.png\"></span>';
	knopffeld.innerHTML = code;
}


function cms_schulhof_einklappen (knopffeldid, klasse) {
	var felder = document.getElementsByClassName(klasse);
	for (var i = 0; i < felder.length; i++) {
	    felder[i].style.display = "none";
	}
	var knopffeld = document.getElementById(knopffeldid);
	var code = '<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_ausklappen(\''+knopffeldid+'\', \''+klasse+'\');\"><span class=\"cms_hinweis\">Ausklappen</span><img src=\"res/icons/klein/ausklappen.png\"></span>';
	knopffeld.innerHTML = code;
}


function cms_geraet_aendern(geraet) {
	cms_laden_an('Anzeige optimieren', 'Die Eingaben werden überprüft.');

	var meldung = '<p>Die Anzeige konnte nicht optimiert werden, denn ...</p><ul>';
	var fehler = false;

	// Pflichteingaben prüfen
	if ((geraet != 'H') && (geraet != 'T') && (geraet != 'P')) {
		meldung += '<li>es wurde kein gültiges Gerät ausgewählt.</li>';
		fehler = true;
	}

	if (!fehler) {
		var formulardaten = new FormData();
		formulardaten.append("geraet", geraet);
		formulardaten.append("anfragenziel", 	'4');
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Anzeige optimieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Anzeige optimieren', 'Die neuen Einstellungen werden übernommen.');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "DATENSCHUTZ") {
				meldung += '<li>die Speicherung von Cookies wurde noch nicht akzeptiert. Ohne Cookies können diese Einstellungen nicht wirksam werden.</li>';
				cms_meldung_an('fehler', 'Anzeige optimieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "FEHLER") {
				meldung += '<li>die Einstellungsänderung wurde über einen ungültigen Link aufgerufen.</li>';
				cms_meldung_an('fehler', 'Anzeige optimieren', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
			}
			else if (rueckgabe == "ERFOLG") {
				location.reload();
			}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}

function cms_vornegross(text) {
	return (text.substr(0,1)).toUpperCase()+(text.substr(1)).toLowerCase();
}
