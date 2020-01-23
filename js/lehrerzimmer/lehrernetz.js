function cms_gesichert_laden(id) {
	document.getElementById(id).innerHTML = '<div class=\"cms_meldung_laden\">'+cms_ladeicon()+'<p>Inhalte werden geladen...</p></div>';
}

function cms_lehrerdatenbankzugangsdaten_schicken(formulardaten) {
	formulardaten.append("nutzerid",    	CMS_BENUTZERID);
	formulardaten.append("sessionid", 		CMS_SESSIONID);
	return formulardaten;
}

function cms_netzcheck(zeigen) {
	var zeigen = zeigen || 'j';
	var anfrage = new XMLHttpRequest();
	if (zeigen == 'j') {
		var feld = document.getElementById('cms_netzcheckstatus');
		var lzfeld = document.getElementById('cms_netzcheckstatus_lz');
	}

	if (CMS_LN_DA) {
		var anfrage = new XMLHttpRequest();
		anfrage.timeout = 2000;
		anfrage.onreadystatechange = function() {
			if (anfrage.readyState==4 && anfrage.status==200) {
				if (zeigen == 'j') {
					lzfeld.innerHTML = ", Lehrerzimmer";
					feld.style.backgroundColor = "#ffd95a";
				}
				CMS_IMLN = true;
				var formulardaten = new FormData();
				formulardaten.append("anfragenziel", '169');
				formulardaten.append("status", '1');
				cms_ajaxanfrage (false, formulardaten, null);
			}
			else if (anfrage.readyState==4) {
				if (zeigen == 'j') {
					lzfeld.innerHTML = "";
					feld.style.backgroundColor = "#94d1ff";
				}
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



var gruendeplan = new Array();
function cms_vplan_gruende (id, zeit, art) {
	var kennung = '';
	if (cms_check_ganzzahl(zeit, 0) && cms_check_ganzzahl(id, 1,4) && ((art == 'a') || (art == 'k'))) {
		if (art == 'k') {
			kennung = document.getElementById('cms_lvplan_kennung').value;
		}
		gruendeplan[id] = document.getElementById('cms_vplan_gruende_'+id);
		formulardaten = new FormData();
		if (art == 'a') {
			cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
		}
		formulardaten.append("zeit", 	zeit);
		formulardaten.append("kennung", 	 kennung);
		formulardaten.append("art", 	art);
		formulardaten.append("anfragenziel", 	'27');
		// VERTRETUNSTEXTE LADEN
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<div class=/)) {
				gruendeplan[id].innerHTML = rueckgabe;
			}
			else {gruendeplan[id].innerHTML = '';}
		}
		cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
	}
}
