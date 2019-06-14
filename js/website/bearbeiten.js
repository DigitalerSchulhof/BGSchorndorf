function cms_menuebearbeiten_ausblenden(spalte) {
  var max = document.getElementById('cms_website_neu_maxpos').value;
  for (var i = 0; i <= max; i++) {
    if (document.getElementById('cms_website_neu_element_'+spalte+'_'+i)) {
      document.getElementById('cms_website_neu_element_'+spalte+'_'+i).innerHTML = "";
      cms_ausblenden('cms_website_neu_element_'+spalte+'_'+i);
      cms_ausblenden('cms_website_neu_menue_'+spalte+'_'+i);
    }
    if (document.getElementById('cms_website_bearbeiten_element_'+spalte+'_'+i)) {
      document.getElementById('cms_website_bearbeiten_element_'+spalte+'_'+i).innerHTML = "";
      cms_ausblenden('cms_website_bearbeiten_element_'+spalte+'_'+i);
      cms_ausblenden('cms_website_bearbeiten_menue_'+spalte+'_'+i);
    }
  }
}

function cms_element_freigeben(art, id, modus, zusatz) {
  cms_laden_an('Freigabe erteilen', 'Die Freigabe wird erteilt.');
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("art", art);
  formulardaten.append("anfragenziel", 	'26');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Freigabe erteilen', '<p>Die Freigabe wurde erteilt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/'+modus+'/'+zusatz+'\');">Zurück zur Seite</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_element_ablehnen(art, id, modus, zusatz) {
  cms_laden_an('Änderungen ablehnen', 'Die Änderung wird abgelehnt.');
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("art", art);
  formulardaten.append("anfragenziel", 	'27');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Änderungen ablehnen', '<p>Die Änderung wurde abgelehnt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/'+modus+'/'+zusatz+'\');">Zurück zur Seite</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_element_aktivieren(art, id, modus, zusatz) {
  cms_laden_an('Aktivieren', 'Die Aktivierung wird durchgeführt.');
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("art", art);
  formulardaten.append("anfragenziel", 	'28');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Aktivieren', '<p>Die Aktivierung wurde durchgeführt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/'+modus+'/'+zusatz+'\');">Zurück zur Seite</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_element_alleaktivieren(seite, modus, zusatz) {
  cms_laden_an('Aktivieren', 'Die Aktivierung wird durchgeführt.');
  var formulardaten = new FormData();
  formulardaten.append("seite", seite);
  formulardaten.append("anfragenziel", 	'29');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Aktivieren', '<p>Die Aktivierung wurde durchgeführt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/'+modus+'/'+zusatz+'\');">Zurück zur Seite</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_element_allefreigeben(seite, modus, zusatz) {
  cms_laden_an('Freigabe erteilen', 'Die Freigabe wird erteilt.');
  var formulardaten = new FormData();
  formulardaten.append("seite", seite);
  formulardaten.append("anfragenziel", 	'30');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Freigabe erteilen', '<p>Die Freigabe wurde erteilt.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/'+modus+'/'+zusatz+'\');">Zurück zur Seite</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}


function cms_element_loeschen_anzeigen(art, id, modus, zusatz) {
	cms_meldung_an('warnung', 'Element löschen', '<p>Soll dieses Element wirklich gelöscht werden?</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_nein" onclick="cms_element_loeschen(\''+art+'\', \''+id+'\', \''+modus+'\', \''+zusatz+'\')">Löschung durchführen</span></p>');
}


function cms_element_loeschen(art, id, modus, zusatz) {
  cms_laden_an('Element löschen', 'Das Element wird gelöscht.');
  var formulardaten = new FormData();
  formulardaten.append("id", id);
  formulardaten.append("art", art);
  formulardaten.append("anfragenziel", 	'31');

  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Element löschen', '<p>Das Element wurde gelöscht.</p>', '<p><span class="cms_button" onclick="cms_link(\'Website/Bearbeiten/'+modus+'/'+zusatz+'\');">Zurück zur Seite</span></p>');
    }
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
