function cms_intern_geraetezustand() {
	var kennung = document.getElementById('cms_intern_kennung').value;
	cms_link('Intern/Gerätezustand/'+kennung);
}

function cms_intern_vplan(art) {
	var kennung = document.getElementById('cms_intern_kennung').value;
	if (art == 's') {cms_link('Intern/Schülervertretungsplan/'+kennung);}
	else if (art == 'l') {cms_link('Intern/Lehrervertretungsplan/'+kennung);}
}

function cms_intern_vplan_laden(art) {
	if ((art == 'l') || (art == 's')) {
		var heute = document.getElementById('cms_'+art+'vplan_heute');
		var morgen = document.getElementById('cms_'+art+'vplan_morgen');
	  var kennung = document.getElementById('cms_'+art+'vplan_kennung').value;

	  var formulardatenh = new FormData();
	  formulardatenh.append("art", art);
	  formulardatenh.append("kennung", kennung);
	  formulardatenh.append("zeit", "h");
	  formulardatenh.append("anfragenziel", '30');

	  var formulardatenm = new FormData();
	  formulardatenm.append("art", art);
	  formulardatenm.append("kennung", kennung);
	  formulardatenm.append("zeit", "m");
	  formulardatenm.append("anfragenziel", '30');

		if (art == 'l') {
			var geraete = document.getElementById('cms_lvplan_geraete');
			var formulardateng = new FormData();
		  formulardateng.append("art", art);
		  formulardateng.append("kennung", kennung);
		  formulardateng.append("anfragenziel", '300');
		}

	  function heutenachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h2/)) {
				heute.innerHTML = rueckgabe;
			}
	  }

		function morgennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h2/)) {
				morgen.innerHTML = rueckgabe;
			}
	  }

		function geraetenachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^<h2/)) {
				geraete.innerHTML = rueckgabe;
			}
	  }

	  cms_ajaxanfrage (false, formulardatenh, heutenachbehandlung, CMS_LN_DA);
	  cms_ajaxanfrage (false, formulardatenm, morgennachbehandlung, CMS_LN_DA);
		if (art == 'l') {
			cms_ajaxanfrage (false, formulardateng, geraetenachbehandlung);
		}
	}
}
