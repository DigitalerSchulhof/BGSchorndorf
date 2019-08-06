function cms_reiter(id, nummer, maximum, hashSetzen) {
	if(hashSetzen !== true)
		hashSetzen = false;
	for (var i=0; i<=maximum; i++) {
		// Alle Reiterfenster deaktivieren
		document.getElementById('cms_reiterfenster_'+id+'_'+i).style.display = 'none';
		// Alle Reitertitel deaktivieren
		$(document.getElementById('cms_reiter_'+id+'_'+i)).removeClass("cms_reiter_aktiv").addClass("cms_reiter");
	}
	// Aktivieren des Fensters mit der Nummer nummer
	document.getElementById('cms_reiterfenster_'+id+'_'+nummer).style.display = 'block';

	// Aktivieren des Reitertitels mit der Nummer nummer
	$(document.getElementById('cms_reiter_'+id+'_'+nummer)).addClass('cms_reiter_aktiv');

	// Reiter als Hash speichern
	if(hashSetzen)
  	location.hash = '#tab-' + (nummer+1);
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
var cms_reiter_hash_event_bound = false;
function cms_reiter_laden(id) {

	max = -1;
	while($("#cms_reiter_"+id+"_"+(++max)).removeClass("cms_reiter_aktiv").length)
		$("#cms_reiterfenster_"+id+"_"+max).css("display", "none");

	if(!cms_reiter_hash_event_bound)
		$(window).bind("hashchange", function(e) {
			cms_reiter_hash_event_bound = true;
			cms_reiter_laden(id);
		});

	if(!location.hash)
		nummer = 1;
	else
		if(location.hash.startsWith("#tab-"))
			nummer = parseInt(location.hash.replace("#tab-", ""));
	nummer--;
	$("#cms_reiter_"+id+"_"+nummer).addClass("cms_reiter_aktiv");
	$("#cms_reiterfenster_"+id+"_"+nummer).css("display", "block");
	if(!$("#cms_reiter_"+id+"_"+nummer).length) {
		$("#cms_reiter_"+id+"_0").addClass("cms_reiter_aktiv");
		$("#cms_reiterfenster_"+id+"_0").css("display", "block");
	}
}
