function cms_geraete_problembericht_aktiv() {
	var ids = document.getElementById('cms_geraete_meldung_ids').value;
	var id = ids.split('|');

	var aktiv = 0;
	for (var i=1; i<id.length; i++) {
		var idaktiv = document.getElementById('cms_geraete_'+id[i]+'_id').value;
		if (idaktiv == 1) {aktiv++;}
	}
	if (aktiv > 0) {cms_einblenden('cms_geraete_bericht', 'inline-block');}
	else {cms_ausblenden('cms_geraete_bericht');}
}


function cms_geraete_problembericht(standort, art, ziel) {
	var app = app || 'nein';
	cms_laden_an('Probleme melden', 'Der Problembericht wird zusammengestellt.');
	var geraeteids = document.getElementById('cms_geraete_meldung_ids').value;

	var formulardaten = new FormData();
	formulardaten.append("geraeteids", geraeteids);
	formulardaten.append("standort", standort);
	formulardaten.append("art", art);

	var fehler = false;
	var meldung = '<p>Die Meldung konnte nicht verschickt werden, denn ...</p><ul>';

	if ((art != 'r') && (art != 'l')) {
		fehler = true;
		meldung += '<li>Es können nur Meldungen für die Ausstattung in Räumen und für Leihgeräte gemacht werden.</li>';
	}
	if (!cms_check_ganzzahl(standort, 0)) {
		fehler = true;
		meldung += '<li>Der Gerätestandort ist ungültig.</li>';
	}

	var ids = geraeteids.split('|');
	var aktiv, nmeldung, ameldung;
	var meldungsfehler;
	for (var i=1; i<ids.length; i++) {
		aktiv = document.getElementById('cms_geraete_'+ids[i]+'_id').value;
		formulardaten.append("aktiv_"+ids[i], aktiv);
		ameldung = document.getElementById('cms_geraete_meldunga_'+ids[i]).innerHTML;
    nmeldung = document.getElementById('cms_geraete_meldungn_'+ids[i]).value;
    if (ameldung.length > 0) {nmeldung = ameldung+' - '+nmeldung;}
		formulardaten.append("meldung_"+ids[i], nmeldung);
		if ((nmeldung.length == 0) && (aktiv == 1)) {
			meldungsfehler = true;
		}
	}
	formulardaten.append("anfragenziel", 	'84');

	if (meldungsfehler) {
		fehler = true;
		meldung += '<li>Bei mindestens einem gemedleten Gerät wurde keine Problembeschreibung eingetragen.</li>';
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Probleme melden', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Probleme melden', 'Der Problembericht wird verschickt.');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Probleme melden', '<p>Der Problembericht wurde verschickt.</p>', '<p><span class="cms_button" onclick="cms_link(\''+ziel+'\');">Zurück zur Übersicht</span></p>');
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}


function cms_geraete_problembericht_loeschen_anzeigen (standort, art, bezeichnung) {
	if (art == 'r') {var ort = 'Raum';}
	if (art == 'l') {var ort = 'Leihgeräte';}
	cms_meldung_an('warnung', 'Problembericht löschen', '<p>Sollen die Meldungen über Mängel für <b>'+ort+' » '+bezeichnung+'</b> wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_geraete_problembericht_loeschen(\''+standort+'\', \''+art+'\', \''+bezeichnung+'\')">Löschung durchführen</span></p>');
}


function cms_geraete_problembericht_loeschen(standort, art, bezeichnung) {
	if (art == 'raum') {var ort = 'Raum';}
	if (art == 'leihgeraet') {var ort = 'Leihgeräte';}
	cms_laden_an('Problembericht löschen', 'Die Meldungen über Mängel für <b>'+ort+' » '+bezeichnung+'</b> werden gelöscht.');

	var formulardaten = new FormData();
	formulardaten.append("id",     		standort);
	formulardaten.append("art",     	art);
	formulardaten.append("anfragenziel", 	'85');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_meldung_an('erfolg', 'Problembericht löschen', '<p>Der Problembericht wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Aufgaben/Geräte_verwalten\');">OK</span></p>');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


/* RAUM WIRD ZUM BEARBEITEN VORBEREITET */
function cms_geraete_problembericht_bearbeiten_vorbereiten (standort, art, bezeichnung) {
	if (art == 'raum') {var ort = 'Raum';}
	if (art == 'leihgerät') {var ort = 'Leihgeräte';}
	cms_laden_an('Problembericht bearbeiten', 'Die Meldungen über Mängel für <b>'+ort+' » '+bezeichnung+'</b> werden geladen.');

	var formulardaten = new FormData();
	formulardaten.append("id", standort);
	formulardaten.append("art", art);
	formulardaten.append("anfragenziel", 	'86');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Aufgaben/Geräte_verwalten/Problembericht_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_geraete_problembericht_funktioniert (id, art) {
	cms_laden_an('Gerät freigeben', 'Das Gerät wird freigegeben.');

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("art", art);
	formulardaten.append("anfragenziel", 	'87');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Aufgaben/Geräte_verwalten/Problembericht_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_geraete_problembericht_aendern (id) {
	cms_laden_an('Gerätestatus ändern', 'Der Gerätestatus wird geändert.');

	var fehler = false;
	var meldung = '<p>Der Gerätestatus konnte nicht geändert werden, denn ...</p><ul>';

	var status = document.getElementById('cms_geraete_status_'+id);
	if (status) {status = status.value;} else {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Status angegeben.</li>';
	}

	if ((status != 1) && (status != 2) && (status != 3) && (status != 4)) {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Status ausgewählt.</li>';
	}

	var kommentar = document.getElementById('cms_geraete_kommentar_'+id);
	if (kommentar) {kommentar = kommentar.value;} else {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Kommentar angegeben.</li>';
	}

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("kommentar", kommentar);
	formulardaten.append("status", status);
	formulardaten.append("anfragenziel", 	'88');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Aufgaben/Geräte_verwalten/Problembericht_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}



function cms_geraete_problembericht_extern (id, ansprechpartner) {
	cms_laden_an('Gerätestatus ändern', 'Der Gerätestatus wird geändert.');
	var fehler = false;
	var meldung = '<p>Der Gerätestatus konnte nicht geändert werden, denn ...</p><ul>';

	var kommentar = document.getElementById('cms_geraete_kommentar_'+id);
	if (kommentar) {kommentar = kommentar.value;} else {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Kommentar angegeben.</li>';
	}

	if ((ansprechpartner != '1') && (ansprechpartner != '2')) {fehler = true;}

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("kommentar", kommentar);
	formulardaten.append("ansprechpartner", ansprechpartner);
	formulardaten.append("anfragenziel", 	'89');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "MAIL") {
			cms_meldung_an('warnung', 'Gerätestatus aendern', '<p>Der Gerätestatus wurde geändert. Es ist aber keine externe Geräteverwaltung hinterlegt. Es wurde also niemand benachrichtigt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Aufgaben/Geräte_verwalten/Problembericht_bearbeiten\');">OK</span></p>');
		}
		else if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Aufgaben/Geräte_verwalten/Problembericht_bearbeiten');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_geraete_problembericht_hausmeister (id) {
	cms_laden_an('Gerätestatus ändern', 'Der Gerätestatus wird geändert.');
	var fehler = false;
	var meldung = '<p>Der Gerätestatus konnte nicht geändert werden, denn ...</p><ul>';

	var status = document.getElementById('cms_geraete_status_'+id);
	if (status) {status = status.value;} else {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Status angegeben.</li>';
	}

	if ((status != 1) && (status != 2) && (status != 3) && (status != 4)) {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Status ausgewählt.</li>';
	}

	var kommentar = document.getElementById('cms_geraete_kommentar_'+id);
	if (kommentar) {kommentar = kommentar.value;} else {
		fehler = true;
		meldung += '<li>Es wurde kein gültiger Kommentar angegeben.</li>';
	}

	if (!cms_check_ganzzahl(id, 0)) {fehler = true;}

	var formulardaten = new FormData();
	formulardaten.append("id", id);
	formulardaten.append("kommentar", kommentar);
	formulardaten.append("status", status);
	formulardaten.append("anfragenziel", 	'378');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe == "ERFOLG") {
			cms_link('Schulhof/Hausmeister');
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_schulhof_ausstattung_neues_geraet() {
	var box = document.getElementById('cms_ausstattung_geraete');
	var anzahl = document.getElementById('cms_ausstattung_geraete_anzahl');
	var nr = document.getElementById('cms_ausstattung_geraete_nr');
	var ids = document.getElementById('cms_ausstattung_geraete_ids');
	var anzahlneu = parseInt(anzahl.value)+1;
	var nrneu = parseInt(nr.value)+1;
	var neueid = 'temp'+nrneu;

	var code = "";

	code += "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_ausstattung_geraete_bezeichnung_"+neueid+"\" id=\"cms_ausstattung_geraete_bezeichnung_"+neueid+"\" value=\"\"> <input type=\"hidden\" name=\"cms_ausstattung_geraete_id_"+neueid+"\" id=\"cms_ausstattung_geraete_id_"+neueid+"\" value=\"-\"></td>";
	code += "<td><span class=\"cms_button_nein\" onclick=\"cms_schulhof_ausstattung_geraet_entfernen('"+neueid+"');\">&times;</span></td></tr>";
	var knoten = document.createElement("TABLE");
	knoten.className = 'cms_formular';
	knoten.setAttribute('id', 'cms_ausstattung_geraete_'+neueid);
	knoten.innerHTML = code;
	box.appendChild(knoten);
	anzahl.value = anzahlneu;
	nr.value = nrneu;
	ids.value = ids.value+'|'+neueid;
}

function cms_schulhof_ausstattung_geraet_entfernen(id) {
	var box = document.getElementById('cms_ausstattung_geraete');
	var anzahl = document.getElementById('cms_ausstattung_geraete_anzahl');
	var ids = document.getElementById('cms_ausstattung_geraete_ids');
	var beschluss = document.getElementById('cms_ausstattung_geraete_'+id);

	box.removeChild(beschluss);
	anzahl.value = anzahl.value-1;
	var neueids = (ids.value+'|').replace('|'+id+'|', '|');
	neueids = neueids.substr(0, neueids.length-1);
	ids.value = neueids;
}
