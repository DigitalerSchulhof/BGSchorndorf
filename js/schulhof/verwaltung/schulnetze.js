/* ROLLE WIRD GESPEICHERT */
function cms_schulhof_verwaltung_schulnetze(lehrernetz) {
	lehrernetz 	= false || lehrernetz;
	cms_laden_an('Schulnetze verwalten', 'Die Eingaben werden überprüft.');
	var shost = document.getElementById('cms_schulhof_verwaltung_schulnetz_shost').value;
	var sbenutzer = document.getElementById('cms_schulhof_verwaltung_schulnetz_sbenutzer').value;
	var spass = document.getElementById('cms_schulhof_verwaltung_schulnetz_spass').value;
	var sdb = document.getElementById('cms_schulhof_verwaltung_schulnetz_sdb').value;
	var base = document.getElementById('cms_schulhof_verwaltung_schulnetz_base').value;

	var meldung = '<p>Die Schulnetze konnten nicht geändert werden, denn ...</p><ul>';
	var fehler = false;

	if (lehrernetz) {
		var lhost = document.getElementById('cms_schulhof_verwaltung_schulnetz_lhost').value;
		var lbenutzer = document.getElementById('cms_schulhof_verwaltung_schulnetz_lbenutzer').value;
		var lpass = document.getElementById('cms_schulhof_verwaltung_schulnetz_lpass').value;
		var ldb = document.getElementById('cms_schulhof_verwaltung_schulnetz_ldb').value;
		var shserver = document.getElementById('cms_schulhof_verwaltung_schulnetz_shserver').value;

		if (lhost.length == 0) {
			meldung += '<li>der Host der Lehrerzimmer-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (lbenutzer.length == 0) {
			meldung += '<li>der Benutzer der Lehrerzimmer-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (ldb.length == 0) {
			meldung += '<li>die Datenbank der Lehrerzimmer-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (shserver.length == 0) {
			meldung += '<li>der Ausgangsserver ist nicht korrekt.</li>';
			fehler = true;
		}
	}

	var lnzbvpn = document.getElementById('cms_schulhof_schulnetz_lnzb_vpn').value;
	var lnda = document.getElementById('cms_schulhof_verwaltung_schulnetz_ldaten').value;

	if (shost.length == 0) {
		meldung += '<li>der Host der Schulhof-Datenbank ist nicht korrekt.</li>';
		fehler = true;
	}

	if (sbenutzer.length == 0) {
		meldung += '<li>der Benutzer der Schulhof-Datenbank ist nicht korrekt.</li>';
		fehler = true;
	}

	if (sdb.length == 0) {
		meldung += '<li>die Datenbank der Schulhof-Datenbank ist nicht korrekt.</li>';
		fehler = true;
	}

	if (fehler) {
		cms_meldung_an('fehler', 'Schulnetze verwalten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
	else {
		cms_laden_an('Schulnetze verwalten', 'Die Änderungen werden übernommen.');

		var formulardaten = new FormData();
		formulardaten.append("shost",     	shost);
		formulardaten.append("sbenutzer", 	sbenutzer);
		formulardaten.append("spass", 		spass);
		formulardaten.append("sdb", 		sdb);
		formulardaten.append("base", 		base);
		formulardaten.append("lnzbvpn", 	lnzbvpn);
		formulardaten.append("lnda", 		lnda);
		formulardaten.append("anfragenziel", 	'152');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				if (lehrernetz) {
					// Anfrage an die Lehrerseite
					var formulardaten2 = new FormData();
					formulardaten2.append("lhost",     		lhost);
					formulardaten2.append("lbenutzer", 		lbenutzer);
					formulardaten2.append("lpass", 				lpass);
					formulardaten2.append("ldb", 					ldb);
					formulardaten2.append("shserver", 		shserver);
					formulardaten2.append("nutzerid",    	CMS_BENUTZERID);
					formulardaten2.append("sessionid", 		CMS_SESSIONID);
					formulardaten2.append("dbshost", 		shost);
					formulardaten2.append("dbsuser", 		sbenutzer);
					formulardaten2.append("dbspass", 		spass);
					formulardaten2.append("dbsdb", 			sdb);
					formulardaten2.append("dbsschluessel", 	CMS_DBS_SCHLUESSEL);
					formulardaten2.append("anfragenziel", 	'153');

					function anfragennachbehandlunglehrer(rueckgabe) {
						if (rueckgabe == "ERFOLG"){
							cms_meldung_an('erfolg', 'Schulnetze verwalten', '<p>Die Schulnetzdaten wurden geändert.</p><p>Aus Sicherheitsgründen ist eine Weiterleitung zur Verwaltungsübersicht notwendig. Danach ist der Schulhof wieder ganz normal benutzbar.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulnetze\');">OK</span></p>');
						}
						else {cms_fehlerbehandlung(rueckgabe);}
					}

					cms_ajaxanfrage (false, formulardaten2, anfragennachbehandlunglehrer, CMS_LN_DA);
				}
				else {
					cms_meldung_an('erfolg', 'Schulnetze verwalten', '<p>Die Schulnetzdaten wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulnetze\');">OK</span></p>');
				}
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
