function cms_intern_geraetezustand() {
	var kennung = document.getElementById('cms_intern_kennung').value;
	cms_link('Intern/Gerätezustand/'+kennung)
}

function cms_intern_geraetezustand_laden() {
  var box = document.getElementById('cms_geraetezustand_vollbild');
  var kennung = document.getElementById('cms_geraetezustand_kennung').value;

  var formulardaten = new FormData();
  formulardaten.append("kennung", kennung);
  formulardaten.append("anfragenziel", '300');

  function anfragennachbehandlung(rueckgabe) {
    box.innerHTML = rueckgabe;
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
