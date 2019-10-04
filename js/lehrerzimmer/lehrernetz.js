function cms_gesichert_laden(id) {
	document.getElementById(id).innerHTML = '<div class=\"cms_meldung_laden\">'+cms_ladeicon()+'<p>Inhalte werden geladen...</p></div>';
}

function cms_lehrerdatenbankzugangsdaten_schicken(formulardaten) {
	formulardaten.append("nutzerid",    	CMS_BENUTZERID);
	formulardaten.append("sessionid", 		CMS_SESSIONID);
	formulardaten.append("dbshost", 			CMS_DBS_HOST);
	formulardaten.append("dbsuser", 			CMS_DBS_USER);
	formulardaten.append("dbspass", 			CMS_DBS_PASS);
	formulardaten.append("dbsdb", 				CMS_DBS_DB);
	formulardaten.append("dbsschluessel", CMS_DBS_SCHLUESSEL);
	return formulardaten;
}

function cms_lehrerzimmer_laden(id, datei, entitaet) {
	var entitaet = entitaet || '-';
	var feld = document.getElementById(id);

	var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
	formulardaten.append("id", entitaet);

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe != "BERECHTIGUNG") {
			cms_gesichertedaten_inhalte(feld, rueckgabe);
		}
		else {cms_fehlerbehandlung(rueckgabe);}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}

function cms_netzcheck() {
	var anfrage = new XMLHttpRequest();
	var feld = document.getElementById('cms_netzcheckstatus');
	var lzfeld = document.getElementById('cms_netzcheckstatus_lz');

	if (CMS_LN_DA) {
		var anfrage = new XMLHttpRequest();
		anfrage.timeout = 2000;
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				lzfeld.innerHTML = ", Lehrerzimmer";
				feld.style.backgroundColor = "#ffd95a";
				CMS_IMLN = true;
				var formulardaten = new FormData();
				formulardaten.append("anfragenziel", '169');
				formulardaten.append("status", '1');
				cms_ajaxanfrage (false, formulardaten, null);
			}
			else if (anfrage.readyState==4) {
				lzfeld.innerHTML = "";
				feld.style.backgroundColor = "#94d1ff";
				CMS_IMLN = false;
				var formulardaten = new FormData();
				formulardaten.append("anfragenziel", '169');
				formulardaten.append("status", '0');
				cms_ajaxanfrage (false, formulardaten, null);
			}
		};
		anfrage.open("POST",CMS_LN_DA+"php/oeffentlich/anfragen/echo.php",true);
		anfrage.send();
	}
	else {
		return false;
	}
}
