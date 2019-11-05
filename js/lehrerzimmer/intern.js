function cms_intern_geraetezustand() {
	var kennung = document.getElementById('cms_intern_kennung').value;
	cms_link('Intern/Gerätezustand/'+kennung);
}

function cms_intern_vplan(art) {
	alert(1);
	var kennung = document.getElementById('cms_intern_kennung').value;
	if (art == 's') {cms_link('Intern/Schülervertretungsplan/'+kennung);}
	else if (art == 'l') {cms_link('Intern/Lehrervertretungsplan/'+kennung);}
}

function cms_intern_vplan_laden(art) {
	var kennung = document.getElementById('cms_intern_kennung').value;
	if (art == 's') {cms_link('Intern/Schülervertretungsplan/'+kennung);}
	else if (art == 'l') {cms_link('Intern/Lehrervertretungsplan/'+kennung);}
}

function cms_intern_vplan_laden(art) {
	if ((art == 'l') || (art == 's')) {
		var box = document.getElementById('cms_'+art+'vplan_vollbild');
	  var kennung = document.getElementById('cms_'+art+'vplan_kennung').value;

	  var formulardaten = new FormData();
	  formulardaten.append("art", art);
	  formulardaten.append("kennung", kennung);
	  formulardaten.append("anfragenziel", '300');

	  function anfragennachbehandlung(rueckgabe) {
	    box.innerHTML = rueckgabe;
	  }

	  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
	}
}
