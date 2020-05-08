/* ROLLE WIRD GESPEICHERT */
function cms_schulnetze_lehrernetz_laden() {
	var feld = document.getElementById('cms_netze_lehrernetz');

	if (CMS_IMLN) {
		formulardaten = new FormData();
		cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		formulardaten.append("anfragenziel", 	'37');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h3>Datenbanken<\/h3><h4>Lehrer<\/h4><table/)) {
				feld.innerHTML = rueckgabe;
			}
			else {feld.innerHTML = rueckgabe;}
		}

		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
	else {
		cms_meldung_firewall();
	}
}


function cms_schulnetze_speichern() {
	if (CMS_IMLN) {
		cms_laden_an('Schulnetze verwalten', 'Die Eingaben werden überprüft.');
		var shost = document.getElementById('cms_netze_host_sh').value;
		var sbenutzer = document.getElementById('cms_netze_benutzer_sh').value;
		var spass = document.getElementById('cms_netze_passwort_sh').value;
		var sdb = document.getElementById('cms_netze_datenbank_sh').value;

		var phost = document.getElementById('cms_netze_host_pers').value;
		var pbenutzer = document.getElementById('cms_netze_benutzer_pers').value;
		var ppass = document.getElementById('cms_netze_passwort_pers').value;
		var pdb = document.getElementById('cms_netze_datenbank_pers').value;
		var base = document.getElementById('cms_netze_basisverzeichnis_sh').value;
		var lehrer = document.getElementById('cms_netze_lehrerverzeichnis_sh').value;
		var vpn = document.getElementById('cms_netze_vpn').value;
		var hosts = document.getElementById('cms_netze_hostingpartner_sh').value;
		var hostl = document.getElementById('cms_netze_hostingpartner_ls').value;
		var socketip = document.getElementById('cms_netze_socketip').value;
		var socketport = document.getElementById('cms_netze_socketport').value;
		var offizielle_version = document.getElementById('cms_netze_offizielle_version').value;
		var github_benutzer = document.getElementById('cms_netze_github_benutzer').value;
		var github_repo = document.getElementById('cms_netze_github_repo').value;
		var github_oauth = document.getElementById('cms_netze_github_oauth').value;

		var lhost = document.getElementById('cms_netze_host_lsh').value;
		var lbenutzer = document.getElementById('cms_netze_benutzer_lsh').value;
		var lpass = document.getElementById('cms_netze_passwort_lsh').value;
		var ldb = document.getElementById('cms_netze_datenbank_lsh').value;
		var schueler = document.getElementById('cms_netze_schuelerverzeichnis_lsh').value;

		var meldung = '<p>Die Schulnetze konnten nicht geändert werden, denn ...</p><ul>';
		var fehler = false;

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

		if (phost.length == 0) {
			meldung += '<li>der Host der Personen-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (pbenutzer.length == 0) {
			meldung += '<li>der Benutzer der Personen-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (pdb.length == 0) {
			meldung += '<li>die Datenbank der Personen-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (base.length == 0) {
			meldung += '<li>das Basisverzeichnis ist nicht korrekt.</li>';
			fehler = true;
		}

		if (lehrer.length == 0) {
			meldung += '<li>der Lehrerserver ist nicht korrekt.</li>';
			fehler = true;
		}

		if (!cms_check_toggle(vpn)) {
			meldung += '<li>die Angabe zur VPN-Anleitung ist nicht korrekt.</li>';
			fehler = true;
		}

		if (hosts.length == 0) {
			meldung += '<li>der Hostingpartner des Schülernetzes ist nicht korrekt.</li>';
			fehler = true;
		}

		if (hostl.length == 0) {
			meldung += '<li>der Hostingpartner des Lehrernetzes ist nicht korrekt.</li>';
			fehler = true;
		}

		if (!cms_check_toggle(offizielle_version)) {
			meldung += '<li>die Angabe zur offiziellen Version ist nicht korrekt.</li>';
			fehler = true;
		}

		if(offizielle_version == 0) {
			if(github_benutzer.length == 0) {
				meldung += '<li>der GitHub Benutzer ist nicht korrekt.</li>';
				fehler = true;
			}
			if(github_repo.length == 0) {
				meldung += '<li>die GitHub Repository ist nicht korrekt.</li>';
				fehler = true;
			}
		}

		if (lhost.length == 0) {
			meldung += '<li>der Host der Lehrer-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (lbenutzer.length == 0) {
			meldung += '<li>der Benutzer der Lehrer-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (ldb.length == 0) {
			meldung += '<li>die Datenbank der Lehrer-Datenbank ist nicht korrekt.</li>';
			fehler = true;
		}

		if (schueler.length == 0) {
			meldung += '<li>der Schülerserver ist nicht korrekt.</li>';
			fehler = true;
		}

		if (fehler) {
			cms_meldung_an('fehler', 'Schulnetze verwalten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
		}
		else {
			cms_laden_an('Schulnetze verwalten', 'Die Änderungen werden im Schülernetz übernommen.');
			var formulardaten = new FormData();
			formulardaten.append("shost",     		shost);
			formulardaten.append("sbenutzer", 		sbenutzer);
			formulardaten.append("spass", 				spass);
			formulardaten.append("sdb", 					sdb);
			formulardaten.append("phost",     		phost);
			formulardaten.append("pbenutzer", 		pbenutzer);
			formulardaten.append("ppass", 				ppass);
			formulardaten.append("pdb", 					pdb);
			formulardaten.append("anfragenziel", 	'152');

			function anfragennachbehandlung(rueckgabe) {
				if (rueckgabe == "ERFOLG") {
					cms_laden_an('Schulnetze verwalten', 'Die Änderungen werden in die Datenbank geschrieben.');
					var formulardatendb = new FormData();
					formulardatendb.append("base", 								base);
					formulardatendb.append("lehrer", 							lehrer);
					formulardatendb.append("vpn", 								vpn);
					formulardatendb.append("hosts", 							hosts);
					formulardatendb.append("hostl", 							hostl);
					formulardatendb.append("socketip", 						socketip);
					formulardatendb.append("socketport", 					socketport);
					formulardatendb.append("offizielle_version", 	offizielle_version);
					formulardatendb.append("github_benutzer", 		github_benutzer);
					formulardatendb.append("github_repo", 				github_repo);
					formulardatendb.append("github_oauth", 				github_oauth);
					formulardatendb.append("anfragenziel", 	'395');

					function anfragennachbehandlungdatenbank(rueckgabe) {
						if (rueckgabe == "ERFOLG") {
							cms_laden_an('Schulnetze verwalten', 'Die Änderungen werden im Lehrernetz übernommen.');
							var formulardatenl = new FormData();
							cms_lehrerdatenbankzugangsdaten_schicken(formulardatenl);
							formulardatenl.append("shost",     		shost);
							formulardatenl.append("sbenutzer", 		sbenutzer);
							formulardatenl.append("spass", 				spass);
							formulardatenl.append("sdb", 					sdb);
							formulardatenl.append("lhost",     		lhost);
							formulardatenl.append("lbenutzer", 		lbenutzer);
							formulardatenl.append("lpass", 				lpass);
							formulardatenl.append("ldb", 					ldb);
							formulardatenl.append("schueler", 		schueler);
							formulardatenl.append("anfragenziel", 	'38');

							function anfragennachbehandlunglehrer(rueckgabe) {
								if (rueckgabe == "ERFOLG"){
									cms_meldung_an('erfolg', 'Schulnetze verwalten', '<p>Die Schulnetzdaten wurden geändert.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Verwaltung/Schulnetze\');">OK</span></p>');
								}
								else if (rueckgabe.match(/^DATEI/)) {
									meldung += '<li>die neue Konfigurationsdatei konnte nicht geschrieben werden.</li>';
									cms_meldung_an('fehler', 'Schulnetze verwalten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
								}
								else {cms_fehlerbehandlung(rueckgabe);}
							}
							cms_ajaxanfrage (false, formulardatenl, anfragennachbehandlunglehrer, lehrer);

							cms_laden_aus();
						}
						else {cms_fehlerbehandlung(rueckgabe);}
					}
					cms_ajaxanfrage (false, formulardatendb, anfragennachbehandlungdatenbank);
				}
				else if (rueckgabe.match(/^DATEI/)) {
					meldung += '<li>die neue Konfigurationsdatei konnte nicht geschrieben werden.</li>';
					cms_meldung_an('fehler', 'Schulnetze verwalten', meldung+'</ul>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
				}
				else {cms_fehlerbehandlung(rueckgabe);}
			}
			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
		}
	}
	else {
		cms_meldung_firewall();
	}

}
