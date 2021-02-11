function cms_link (ziel, neuerTab) {
	neuerTab = neuerTab || false;
	var link = CMS_DOMAIN+'/'+ziel;
	if(ziel.substring(0, 4) == "http") {
		link = ziel;
	}

	if(!neuerTab) {
		window.location.href = link;
	} else {
		window.open(link, '_blank');
	}
}

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
			feld.innerHTML = '<p class="cms_notiz">'+cms_ladeicon()+'</p>';
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

			cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
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

function cms_drucken(seite) {
	seite = seite || "Drucken";
	var iframe = document.createElement("iframe");
  iframe.onload = function () {
    container.innerHTML = iframe.contentWindow.document.body.innerHTML;
    document.body.removeChild(this);
    window.print();
  }
	iframe.style.position = "fixed";
	iframe.style.right = "0";
	iframe.style.bottom = "0";
	iframe.style.width = "0";
	iframe.style.height = "0";
	iframe.style.border = "0";
	iframe.src = seite;
	document.body.appendChild(iframe);
  var container = document.getElementsByClassName("cms_nur_drucken")[0] || document.createElement("div");
  container.classList = "cms_nur_drucken";
  document.getElementsByClassName("cms_nur_drucken")[0] || document.body.appendChild(container);
}
