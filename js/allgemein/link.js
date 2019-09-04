function cms_link (ziel, neuerTab, ersetzen) {
	neuerTab = neuerTab || false;	// False -> False, True -> True
	if(ersetzen !== false)
		ersetzen = true;
	if(!neuerTab) {
		ziel = CMS_DOMAIN+'/'+ziel;
		if(ersetzen) {
			cms_laden_an("Seite laden", "Die neue Seite wird geladen");
			$.get(ziel, function(m) {
				var s = /<body( |)(class="cms_optimierung_(.)"|)>/
				var e = "</body>";
		 		 	 is = m.search(s);
			     ie = m.search(e);
				var	b = m.substring(m.search(s)+(s.exec(m)[0].length), ie);
				$("body").html(b);
				cms_laden_aus();
				window.history.pushState({}, m.substring(m.search("<title>")+"<title>".length, m.search("</title>	")), ziel);
			});
		} else {
			window.location.href = ziel;
		}
	}
	else
		window.open(CMS_DOMAIN+"/"+ziel, '_blank');
}

// Links umwandeln
$(document).on("click", "a", function(e) {
	if($(this).is("[data-cms_refresh]"))
		return;
	var href = $(this).attr("href");
	if(/javascript:/g.test(href) || /(http(s|)|www.):/g.test(href))
		return;
	e.preventDefault();
	cms_link(href, false);
})

function cms_bezzulink(bezeichnung) {
	bezeichnung = bezeichnung.replace(/\s/g, '_');
	return bezeichnung;
}

function cms_mobinavi_aendern(id) {
	if (Number.isInteger(parseInt(id))) {
		var feld = document.getElementById('cms_mobilmenue_seite_'+id);
		var knopf = document.getElementById('cms_mobilmenue_knopf_'+id);
		if (feld.style.display == 'none') {
			feld.style.display = 'block';
			feld.innerHTML = '<p class="cms_notiz"><img src="res/laden/standard.gif"></p>';
			var formulardaten = new FormData();
			formulardaten.append("id", id);
			formulardaten.append("anfragenziel", 	'1');

			function anfragennachbehandlung(rueckgabe) {
				if (rueckgabe.substr(0,4) == "<ul>") {
					feld.innerHTML = rueckgabe;
					knopf.innerHTML = '&#8632;';
					cms_mobinavi_zwischenspeichern();
				}
				else {cms_fehlerbehandlung(rueckgabe);}
			}

			cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
		}
		else {
			feld.style.display = 'none';
			knopf.innerHTML = '&#8628;';
			cms_mobinavi_zwischenspeichern();
		}
	}
}

function cms_mobinavi_zwischenspeichern() {
	var navi = document.getElementById('cms_mobilmenue_seiten').innerHTML;
	var formulardaten = new FormData();
	formulardaten.append("navi", navi);
	formulardaten.append("anfragenziel", 	'2');
	var anfrage = new XMLHttpRequest();
	anfrage.open("POST","php/oeffentlich/anfragen/anfrage.php",true);
	anfrage.send(formulardaten);
}

function cms_mobinavi_zeigen (id) {
	var feld = document.getElementById('cms_mobilmenue_seite_'+id);
	var knopf = document.getElementById('cms_mobilmenue_knopf_'+id);
	if (feld.style.display == 'none') {
		feld.style.display = 'block';
		knopf.innerHTML = '&#8632;';
	}
	else {
		feld.style.display = 'none';
		knopf.innerHTML = '&#8628;';
	}
}
