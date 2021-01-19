function cms_websuche_schliessen (suche, ergebnis) {
	var feld = document.getElementById(ergebnis);
	var suchfeld = document.getElementById(suche);
	var ergebnisfeld = document.getElementById(ergebnis+'_inhalt');
	feld.style.display = "none";
	suchfeld.value = "";
	ergebnisfeld.innerHTML = "Bitte warten ...";
}

function cms_websuche_suchen (suche, ergebnis) {
	var feld = document.getElementById(ergebnis);
	var ergebnisfeld = document.getElementById(ergebnis+'_inhalt');
	feld.style.display = "block";
	ergebnisfeld.innerHTML = "<p class=\"cms_notiz\">Bitte warten...</p>";
	var suchfeld = document.getElementById(suche);

	var formulardaten = new FormData();
	formulardaten.append("suchbegriff", suchfeld.value);
	formulardaten.append("anfragenziel", 	'3');

	function anfragennachbehandlung(rueckgabe) {
		ergebnisfeld.innerHTML = rueckgabe;
	}

	cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
}
