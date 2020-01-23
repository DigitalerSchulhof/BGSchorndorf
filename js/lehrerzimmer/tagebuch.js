function cms_tagebuchmeldung_laden() {
  var formulardaten = new FormData();
	cms_lehrerdatenbankzugangsdaten_schicken(formulardaten);
  formulardaten.append("anfragenziel", '31');

	function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe != 'keine') {
      var feld = document.getElementById('cms_tagebuchneuigkeit_inhalt');
      feld.innerHTML = '<h4>Tagebucheinträge</h4><p>'+rueckgabe+'</p>';
    }
		else {
      var tagebuchmeldung = document.getElementById('cms_tagebuchneuigkeit');
      tagebuchmeldung.parentNode.removeChild(tagebuchmeldung);
    }
	}

	cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung, CMS_LN_DA);
}
