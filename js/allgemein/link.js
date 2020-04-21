function cms_link (ziel, neuerTab) {
	neuerTab = neuerTab || false;
	if(!neuerTab)
		window.location.href = CMS_DOMAIN+'/'+ziel;
	else
		window.open(CMS_DOMAIN+"/"+ziel, '_blank');
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

function cms_drucken(seite) {
	seite = seite || "Drucken";
	var iframe = document.createElement("iframe");
	iframe.onload = function() {
		this.contentWindow.__container__ = this;
  	this.contentWindow.onbeforeunload = function() {document.body.removeChild(this.__container__)};
  	this.contentWindow.onafterprint = function() {document.body.removeChild(this.__container__)};
  	this.contentWindow.focus();
  	this.contentWindow.print();
	};
	iframe.style.position = "fixed";
	iframe.style.right = "0";
	iframe.style.bottom = "0";
	iframe.style.width = "0";
	iframe.style.height = "0";
	iframe.style.border = "0";
	iframe.src = seite;
	document.body.appendChild(iframe);
}
