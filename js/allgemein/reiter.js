function cms_reiter(id, nummer, maximum) {
	for (var i=0; i<=maximum; i++) {
		// Alle Reiterfenster deaktivieren
		document.getElementById('cms_reiterfenster_'+id+'_'+i).style.display = 'none';
		// Alle Reitertitel deaktivieren
		document.getElementById('cms_reiter_'+id+'_'+i).className = 'cms_reiter';
	}
	// Aktivieren des Fensters mit der Nummer nummer
	document.getElementById('cms_reiterfenster_'+id+'_'+nummer).style.display = 'block';

	// Aktivieren des Reitertitels mit der Nummer nummer
	document.getElementById('cms_reiter_'+id+'_'+nummer).className = 'cms_reiter_aktiv';
}

function cms_einblendebox_ein (id) {
	var box = document.getElementById('cms_einblendebox_'+id);
	var einblendeknopf = document.getElementById('cms_einblendeknopf_'+id);
	einblendeknopf.style.display = 'none';
	box.style.display = 'block';
}

function cms_einblendebox_aus (id) {
	var box = document.getElementById('cms_einblendebox_'+id);
	var einblendeknopf = document.getElementById('cms_einblendeknopf_'+id);
	box.style.display = 'none';
	einblendeknopf.style.display = 'block';
}
