function cms_listen_personenliste_laden(art) {
  var tabelle = document.getElementById('cms_personenliste');
  tabelle.innerHTML = '<tr><td class="cms_notiz">'+cms_ladeicon()+'<br><br>Die Suche läuft. Bitte warten ...</td></tr>';

  var postfach = document.getElementById('cms_personenliste_postfach').value;
  var leer = document.getElementById('cms_personenliste_leer').value;
  var elternF = document.getElementById('cms_personenliste_eltern');
  var kinderF = document.getElementById('cms_personenliste_kinder');
  var klassenF = document.getElementById('cms_personenliste_klassenzugehoerigkeit');
  var reliF = document.getElementById('cms_personenliste_reliunterricht');
  var profilF = document.getElementById('cms_personenliste_profil');
  var adresseF = document.getElementById('cms_personenliste_adresse');
  var kontaktdatenF = document.getElementById('cms_personenliste_kontaktdaten');
  var geburtsdatumF = document.getElementById('cms_personenliste_kontaktdaten');
  var konfessionF = document.getElementById('cms_personenliste_kontaktdaten');

  if (elternF) {var eltern = elternF.value;} else {var eltern = '0';}
  if (kinderF) {var kinder = kinderF.value;} else {var kinder = '0';}
  if (klassenF) {var klassen = klassenF.value;} else {var klassen = '0';}
  if (reliF) {var reli = reliF.value;} else {var reli = '0';}
  if (profilF) {var profil = profilF.value;} else {var profil = '0';}
  if (adresseF) {var adresse = adresseF.value;} else {var adresse = '0';}
  if (kontaktdatenF) {var kontaktdaten = kontaktdatenF.value;} else {var kontaktdaten = '0';}
  if (geburtsdatumF) {var geburtsdatum = geburtsdatumF.value;} else {var geburtsdatum = '0';}
  if (konfessionF) {var konfession = konfessionF.value;} else {var konfession = '0';}

  var formulardaten = new FormData();
  formulardaten.append('art', art);
  formulardaten.append('postfach', postfach);
  formulardaten.append('leer', leer);
  formulardaten.append('eltern', eltern);
  formulardaten.append('kinder', kinder);
  formulardaten.append('klassen', klassen);
  formulardaten.append('reli', reli);
  formulardaten.append('profil', profil);
  formulardaten.append('adresse', adresse);
  formulardaten.append('kontaktdaten', kontaktdaten);
  formulardaten.append('geburtsdatum', geburtsdatum);
  formulardaten.append('konfession', konfession);
  formulardaten.append('anfragenziel', '202');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.match(/^<tr>/)) {
			tabelle.innerHTML = rueckgabe;
		}
    else if (rueckgabe == 'BERECHTIGUNG') {tabelle.innerHTML = '<tr><td class="cms_notiz">Für diese Liste besteht keine Berechtigung.</td></tr>';}
    else {tabelle.innerHTML = '<tr><td class="cms_notiz">Bei der Erstellung der Liste ist ein Fehler aufgetreten.</td></tr>';}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}

function cms_listen_gruppenliste_laden(gruppe, gruppenid) {
  var tabelle = document.getElementById('cms_gruppenliste');
  tabelle.innerHTML = '<p class="cms_notiz cms_zentriert">'+cms_ladeicon()+'<br><br>Die Suche läuft. Bitte warten ...</p>';

  var postfach = document.getElementById('cms_gruppenliste_postfach').value;
  var leer = document.getElementById('cms_gruppenliste_leer').value;
  var elternF = document.getElementById('cms_gruppenliste_eltern');
  var kinderF = document.getElementById('cms_gruppenliste_kinder');
  var klassenF = document.getElementById('cms_gruppenliste_klassenzugehoerigkeit');
  var reliF = document.getElementById('cms_gruppenliste_reliunterricht');
  var profilF = document.getElementById('cms_gruppenliste_profil');
  var adresseF = document.getElementById('cms_gruppenliste_adresse');
  var kontaktdatenF = document.getElementById('cms_gruppenliste_kontaktdaten');
  var geburtsdatumF = document.getElementById('cms_gruppenliste_kontaktdaten');
  var konfessionF = document.getElementById('cms_gruppenliste_kontaktdaten');

  var rollenm = document.getElementById('cms_gruppenliste_rm').value;
  var rollenv = document.getElementById('cms_gruppenliste_rv').value;
  var rollena = document.getElementById('cms_gruppenliste_ra').value;

  var personens = document.getElementById('cms_gruppenliste_ps').value;
  var personenl = document.getElementById('cms_gruppenliste_pl').value;
  var personene = document.getElementById('cms_gruppenliste_pe').value;
  var personenv = document.getElementById('cms_gruppenliste_pv').value;
  var personenx = document.getElementById('cms_gruppenliste_px').value;

  if (elternF) {var eltern = elternF.value;} else {var eltern = '0';}
  if (kinderF) {var kinder = kinderF.value;} else {var kinder = '0';}
  if (klassenF) {var klassen = klassenF.value;} else {var klassen = '0';}
  if (reliF) {var reli = reliF.value;} else {var reli = '0';}
  if (adresseF) {var adresse = adresseF.value;} else {var adresse = '0';}
  if (kontaktdatenF) {var kontaktdaten = kontaktdatenF.value;} else {var kontaktdaten = '0';}
  if (geburtsdatumF) {var geburtsdatum = geburtsdatumF.value;} else {var geburtsdatum = '0';}
  if (konfessionF) {var konfession = konfessionF.value;} else {var konfession = '0';}
  if (profilF) {var profil = profilF.value;} else {var profil = '0';}

  var formulardaten = new FormData();
  formulardaten.append('postfach', postfach);
  formulardaten.append('leer', leer);
  formulardaten.append('eltern', eltern);
  formulardaten.append('kinder', kinder);
  formulardaten.append('klassen', klassen);
  formulardaten.append('reli', reli);
  formulardaten.append('profil', profil);
  formulardaten.append('adresse', adresse);
  formulardaten.append('kontaktdaten', kontaktdaten);
  formulardaten.append('geburtsdatum', geburtsdatum);
  formulardaten.append('konfession', konfession);

  formulardaten.append('rollenm', rollenm);
  formulardaten.append('rollenv', rollenv);
  formulardaten.append('rollena', rollena);

  formulardaten.append('personens', personens);
  formulardaten.append('personenl', personenl);
  formulardaten.append('personene', personene);
  formulardaten.append('personenv', personenv);
  formulardaten.append('personenx', personenx);

  formulardaten.append('gruppe', gruppe);
  formulardaten.append('gruppenid', gruppenid);
  formulardaten.append('anfragenziel', '203');

	function anfragennachbehandlung(rueckgabe) {
		if (rueckgabe.match(/^<h2>/)) {
			tabelle.innerHTML = rueckgabe;
		}
    else if (rueckgabe == 'BERECHTIGUNG') {tabelle.innerHTML = '<p class="cms_notiz cms_zentriert">Für diese Liste besteht keine Berechtigung.</p>';}
    else if (rueckgabe == '') {tabelle.innerHTML = '<p class="cms_notiz cms_zentriert">Keine Datensätze gefunden.</p>';}
    else {tabelle.innerHTML = '<p class="cms_notiz cms_zentriert">Bei der Erstellung der Liste ist ein Fehler aufgetreten.</p>';}
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
