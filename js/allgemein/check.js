function cms_check_mail (mail) {
	return mail.match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/);
}

function cms_check_uhrzeit (uhrzeit) {
	return uhrzeit.match(/^[0-9]{1,2}:[0-9]{1,2}$/);
}

function cms_check_name (name) {
	return name.match(/^[\-a-zA-ZÄÖÜäöüßáÁàÀâÂéÉèÈêÊíÍìÌîÎïÏóÓòÒôÔúÚùÙûÛçÇøØæÆœŒåÅ ]+$/);
}

function cms_check_dateiname (datei) {
	return datei.match(/^[\-\_a-zA-Z0-9]{1,244}\.((tar\.gz)|([a-zA-Z0-9]{2,10}))$/);
}

function cms_check_nametitel (titel) {
	return titel.match(/^[a-zA-ZÄÖÜäöüßáÁàÀâÂéÉèÈêÊíÍìÌîÎïÏóÓòÒôÔúÚùÙûÛçÇøØæÆœŒåÅ. ]*$/);
}

function cms_check_titel(titel) {
	return titel.match(/^[\.\-a-zA-Z0-9äöüßÄÖÜ ]+$/);
}

function cms_check_buchstaben(text) {
	return text.match(/^[a-zA-ZÄÖÜäöüß]+$/);
}

function cms_check_toggle(wert) {
	if ((wert != '1') && (wert != "0")) {return false;}
	else {return true;}
}

function cms_check_ganzzahl(wert, min, max) {
	if(!(min || min === 0))
		min = false;
	if(!(max || max === 0))
		max = false;
	wert = wert.toString();
	if (wert.match(/^-{0,1}[0-9]+$/)) {
		if (min !== false) {if (parseInt(wert) < min) {return false;}}
		if (max !== false) {if (parseInt(wert) > max) {return false;}}
		return true;
	}
	else {return false;}
}


/* PRÜFT, OB DIE EINGEGEBENE MAILADRESSE KORREKT IST UND ZEIGT EIN ICON AN */
function cms_check_mail_wechsel(id) {
	var mail = document.getElementById('cms_schulhof_'+id).value;
	var iconF = document.getElementById('cms_schulhof_'+id+'_icon');
	if (cms_check_mail(mail)) {
		iconF.innerHTML = '<img src="res/icons/klein/richtig.png">';
	}
	else {
		iconF.innerHTML = '<img src="res/icons/klein/falsch.png">';
	}
}


/* PRÜFT, OB DIE EINGEGEBENEN PASSWÄRTER IDENTISCH SIND UND ZEIGT EIN ICON AN */
function cms_check_passwort_staerke(id) {
	var passwort = document.getElementById('cms_schulhof_'+id).value;
	var iconF = document.getElementById('cms_schulhof_'+id+'_staerke_icon');

	var score = 0;

	if (passwort.length >= 6) {score += 1;}
	if (passwort.length >= 15) {score += 1;}
	if (passwort.length >= 30) {score += 1;}
	if (/\d/.test(passwort)) {score += 1;}		// Enthält Zahlen
	if (/[A-Z]/.test(passwort)) {score += 1;}  // Enthält Großbuchstaben
	if (/[a-z]/.test(passwort)) {score += 1;}  // Enthält Kleinbuchstaben
	if (/[ß\_\-\!\§\$\%\&\?\@ \.\,]/.test(passwort)) {score += 2;}  // Enthält Sonderzeichen
	if (/[\+\*\#\(\)\[\]\{\}\\\\\/\"\|\=\€]/.test(passwort)) {score += 3;}  // Enthält seltene Sonderzeichen

	if (passwort.length < 2) {
		iconF.innerHTML = '<img src="res/icons/klein/falsch.png">';
	}
	else if (score < 2) {
		iconF.innerHTML = '<img src="res/icons/klein/rot.png">';
	}
	else if (score < 4) {
		iconF.innerHTML = '<img src="res/icons/klein/orange.png">';
	}
	else if (score < 6) {
		iconF.innerHTML = '<img src="res/icons/klein/gelb.png">';
	}
	else {
		iconF.innerHTML = '<img src="res/icons/klein/gruen.png">';
	}
}


/* PRÜFT, OB DIE EINGEGEBENEN PASSWÄRTER IDENTISCH SIND UND ZEIGT EIN ICON AN */
function cms_check_passwort_gleich(id) {
	var passwort1 = document.getElementById('cms_schulhof_'+id).value;
	var passwort2 = document.getElementById('cms_schulhof_'+id+'_wiederholen').value;
	var iconF = document.getElementById('cms_schulhof_'+id+'_gleich_icon');
	if (passwort1 == passwort2) {
		iconF.innerHTML = '<img src="res/icons/klein/richtig.png">';
	}
	else {
		iconF.innerHTML = '<img src="res/icons/klein/falsch.png">';
	}
}


function cms_nur_ganzzahl (id, standard, min, max) {
	standard = standard || 0;
	min = min || false;
	max = max || false;
	var feld = document.getElementById(id);
	var wert = feld.value;
	if (!isNaN(wert)) {
		wert = Math.floor(wert);
		if (max) {if (wert > max) {wert = max;}}
		if (min) {if (wert < min) {wert = min;}}
	}
	else {
		wert = standard;
	}
	feld.value = wert;
}

function cms_check_ip (ip) {
	// check auf IPv4
	var ipv4richtig = true;
	var ipv6richtig = true;

	var ipv4 = ip.split('.');
	// Vier stellen?
	if (ipv4.length != 4) {ipv4richtig = false;}
	// Jede Stelle Integer und zwischen 0 und 255
	else {
		for (i=0; i<4; i++) {
			if ((Number.isInteger(ipv4[i])) && (ipv4 <= 255) && (ipv4 >= 0)) {ipv4richtig = false;}
		}
	}

	var ipv6 = ip.replace(/\#/g, '0');
	ipv6 = ipv6.split(':');
	// Acht stellen?
	if (ipv6.length != 8) {ipv6richtig = false;}
	else {
		for (i=0; i<8; i++) {
			// Jede Stelle hat 4 Zeichen?
			if (ipv6[i].length != 4) {ipv6richtig = false;}
			// Jede Stelle besteht nur aus den Zeichen 0-9 bzw. a-f/A-F
			if (!ipv6[i].match(/^[a-fA-F0-9]+$/)) {ipv6richtig = false;}
		}
	}

	return ipv4richtig || ipv6richtig;
}

function cms_groesse_umrechnen(bytes) {
	if (isNaN(bytes)) {return 0;}

    if (bytes/1024 > 1) {
        bytes = bytes/1024;
        if (bytes/1024 > 1) {
            bytes = bytes/1024;
            if (bytes/1024 > 1) {
                bytes = bytes/1024;
                if (bytes/1024 > 1) {
                    bytes = bytes/1024;
                    if (bytes/1024 > 1) {
                        bytes = bytes/1024;
                        if (bytes/1024 > 1) {
                            bytes = bytes/1024;
                            bytes = ((Math.round(bytes * 100)/100).toString()).replace(/\./, ',');
                            return bytes+" EB";
                        }
                        bytes = ((Math.round(bytes * 100)/100).toString()).replace(/\./, ',');
                        return bytes+" PB";
                    }
                    bytes = ((Math.round(bytes * 100)/100).toString()).replace(/\./, ',');
                    return bytes+" TB";
                }
                bytes = ((Math.round(bytes * 100)/100).toString()).replace(/\./, ',');
                return bytes+" GB";
            }
            bytes = ((Math.round(bytes * 100)/100).toString()).replace(/\./, ',');
            return bytes+" MB";
        }
        bytes = ((Math.round(bytes * 100)/100).toString()).replace(/\./, ',');
        return bytes+" KB";
    }
    return bytes+" B";
}

function cms_uhrzeitcheck(id) {
	var hfeld = document.getElementById(id+'_h');
	var mfeld = document.getElementById(id+'_m');
	var h = parseInt(hfeld.value,10);
	var m = parseInt(mfeld.value,10);

	if (isNaN(h)) {h = 0;}
	if (isNaN(m)) {m = 0;}

	var datum = new Date(0, 0, 0, h, m, 0, 0);

	h = datum.getHours();
	m = datum.getMinutes();

	if (h < 10) {h = '0'+h;}
	if (m < 10) {m = '0'+m;}

	hfeld.value = h;
	mfeld.value = m;
}

function cms_datumcheck(id) {
	var Tbezfeld = document.getElementById(id+'_Tbez');
	var Tfeld = document.getElementById(id+'_T');
	var Mfeld = document.getElementById(id+'_M');
	var Jfeld = document.getElementById(id+'_J');
	var tbez = '';
	var t = parseInt(Tfeld.value,10);
	var m = parseInt(Mfeld.value,10);
	var j = parseInt(Jfeld.value,10);

	if (isNaN(t)) {t = 1;}
	if (isNaN(m)) {m = 1;}
	if (isNaN(j)) {j = 1;}

	var datum = new Date(j, m-1, t, 0, 0, 0, 0);

	tbez = cms_tagname(datum.getDay());
	t = datum.getDate();
	m = datum.getMonth()+1;
	j = datum.getFullYear();

	if (t < 10) {t = '0'+t;}
	if (m < 10) {m = '0'+m;}
	if (j < 1000) {j = '0'+j;}
	else if (j < 100) {j = '00'+j;}
	else if (j < 10) {j = '000'+j;}

	Tbezfeld.innerHTML = tbez;
	Tfeld.value = t;
	Mfeld.value = m;
	Jfeld.value = j;
}

function cms_tagname(t) {
  if (t == 0) {return 'SO';}
  else if (t == 1) {return 'MO';}
  else if (t == 2) {return 'DI';}
  else if (t == 3) {return 'MI';}
  else if (t == 4) {return 'DO';}
  else if (t == 5) {return 'FR';}
  else if (t == 6) {return 'SA';}
  else if (t == 7) {return 'SO';}
  else {return false;}
}

function cms_check_idfeld(text) {
	if (text == '') {return true;}
	return text.match(/^(\|[0-9]+)+$/);
}
