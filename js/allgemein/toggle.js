// Bei Klick ändert der Toggle seinen Wert von 1->0 oder von 0->1
// DEPRECATED - HAS TO BE REMOVED
function cms_toggle(id) {
	var wert = document.getElementById('cms_'+id);
	var feld = document.getElementById('cms_toggle_'+id);

	// Wenn inaktiv, aktivieren
	if (wert.value == 0) {
		wert.value = 1;
		feld.className = "cms_toggle_aktiv";
	}
	// Sonst deaktivieren
	else {
		wert.value = 0;
		feld.className = "cms_toggle_inaktiv";
	}
}

// Neue Funktionen
function cms_buttonwechsel(id, aktiv) {
  // Buttons deaktivieren
  var inaktiv = (document.getElementById(id+'_alle').value).split('|');
  for (var i=1; i<inaktiv.length; i++) {
    document.getElementById(id+'_'+inaktiv[i]).className = "cms_button";
  }
  // Button aktivieren
  document.getElementById(id+'_'+aktiv).className = "cms_button_ja";
  document.getElementById(id+'_aktiv').value = aktiv;
}

function cms_togglebutton(id) {
	var wert = document.getElementById(id);
	var feld = document.getElementById(id+'_K');

	// Wenn inaktiv, aktivieren
	if (wert.value == 0) {
		wert.value = 1;
		feld.className = "cms_toggle_aktiv";
	}
	// Sonst deaktivieren
	else {
		wert.value = 0;
		feld.className = "cms_toggle_inaktiv";
	}
}

function cms_toggleiconbutton(id) {
	var wert = document.getElementById(id);
	var feld = document.getElementById(id+'_K');

	// Wenn inaktiv, aktivieren
	if (wert.value == 0) {
		wert.value = 1;
		feld.className = "cms_iconbutton cms_toggle_aktiv";
	}
	// Sonst deaktivieren
	else {
		wert.value = 0;
		feld.className = "cms_iconbutton cms_toggle_inaktiv";
	}
}

function cms_toggleiconbuttontext(id, text1, text0) {
	var wert = document.getElementById(id);
	var feld = document.getElementById(id+'_K');

	// Wenn inaktiv, aktivieren
	if (wert.value == 0) {
		wert.value = 1;
		feld.className = "cms_iconbutton cms_toggle_aktiv";
		feld.innerHTML = text1;
	}
	// Sonst deaktivieren
	else {
		wert.value = 0;
		feld.className = "cms_iconbutton cms_toggle_inaktiv";
		feld.innerHTML = text0;
	}
}

function cms_toggleeinblenden(id, text1, text0) {
	var wert = document.getElementById(id);
	var knopf = document.getElementById(id+'_K');
	var feld = document.getElementById(id+'_F');

	// Wenn inaktiv, aktivieren
	if (wert.value == 0) {
		wert.value = 1;
		knopf.className = "cms_toggle_aktiv";
		feld.style.display = "block";
		knopf.innerHTML = text0;
	}
	// Sonst deaktivieren
	else {
		wert.value = 0;
		knopf.className = "cms_toggle_inaktiv";
		feld.style.display = "none";
		knopf.innerHTML = text1;
	}
}


// Bei Klick ändert der Schieber seinen Wert von 1->0 oder von 0->1
function cms_schieber(id) {
	var wert = document.getElementById('cms_'+id);
	var feld = document.getElementById('cms_schieber_'+id);

	// Wenn inaktiv, aktivieren
	if (wert.value == 0) {
		wert.value = 1;
		feld.className = "cms_schieber_o_aktiv";
	}
	// Sonst deaktivieren
	else {
		wert.value = 0;
		feld.className = "cms_schieber_o_inaktiv";
	}
}
