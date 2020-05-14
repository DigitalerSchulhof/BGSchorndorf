var CMS_DBSPEICHER_ABS_BELEGT = 0;
var CMS_DATSPEICHER_ABS_BELEGT = 0;
var CMS_GESSPEICHER_BELEGT = 0;

function cms_speicherplatzstatistik(bereich, gesamtspeicher) {
	if ((bereich == 'system') || (bereich == 'website') || (bereich == 'schulhof') || (bereich == 'gruppen') || (bereich == 'personen')) {
		var formulardaten = new FormData();
		formulardaten.append("bereich", 	bereich);
		formulardaten.append("anfragenziel", 	'70');

		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe.match(/^ERFOLG(\|[0-9]+\|[0-9]+\.?[0-9]*\|[0-9]+,?[0-9]{0,2} (B|KB|MB|GB|TB|PB|EB)\|[0-9]+,?[0-9]{0,2} %){3}$/)) {
				var werte = rueckgabe.split("|");
				// DB
				document.getElementById("cms_speicherplatz_"+bereich+"_db_absolut").innerHTML = werte[3];
				document.getElementById("cms_speicherplatz_"+bereich+"_db_prozentual").innerHTML = werte[4];
				// Dateien
				document.getElementById("cms_speicherplatz_"+bereich+"_dat_absolut").innerHTML = werte[7];
				document.getElementById("cms_speicherplatz_"+bereich+"_dat_prozentual").innerHTML = werte[8];
				// Gesamt
				document.getElementById("cms_speicherplatz_"+bereich+"_ges_absolut").innerHTML = werte[11];
				document.getElementById("cms_speicherplatz_"+bereich+"_ges_prozentual").innerHTML = werte[12];
				document.getElementById("cms_speicherplatz_"+bereich+"_balken").style.width = werte[10]+"%";
				// Berechnungen
				CMS_DBSPEICHER_ABS_BELEGT += parseInt(werte[1]);
				var belegt_db_absolut = cms_groesse_umrechnen(CMS_DBSPEICHER_ABS_BELEGT);
				var belegt_db_prozentual = Math.round((CMS_DBSPEICHER_ABS_BELEGT/gesamtspeicher)*10000)/100;
				belegt_db_prozentual = belegt_db_prozentual.toString().replace(".", ",")+" %";
				CMS_DATSPEICHER_ABS_BELEGT += parseInt(werte[5]);
				var belegt_dat_absolut = cms_groesse_umrechnen(CMS_DATSPEICHER_ABS_BELEGT);
				var belegt_dat_prozentual = Math.round((CMS_DATSPEICHER_ABS_BELEGT/gesamtspeicher)*10000)/100;
				belegt_dat_prozentual = belegt_dat_prozentual.toString().replace(".", ",")+" %";
				CMS_GESSPEICHER_BELEGT += parseInt(werte[9]);
				var belegt_ges_absolut = cms_groesse_umrechnen(CMS_GESSPEICHER_BELEGT);
				var belegt_ges_prozentual = Math.round((CMS_GESSPEICHER_BELEGT/gesamtspeicher)*10000)/100;
				var frei_ges_absolut = cms_groesse_umrechnen(gesamtspeicher-CMS_GESSPEICHER_BELEGT);
				var frei_ges_prozentual = 100-belegt_ges_prozentual;
				belegt_ges_prozentual = belegt_ges_prozentual.toString().replace(".", ",")+" %";
				frei_ges_prozentual = frei_ges_prozentual.toString().replace(".", ",")+" %";
				// Balken
				document.getElementById("cms_speicherplatz_belegt_absolut").innerHTML = belegt_ges_absolut;
				document.getElementById("cms_speicherplatz_belegt_prozentual").innerHTML = belegt_ges_prozentual;
				document.getElementById("cms_speicherplatz_frei_absolut").innerHTML = frei_ges_absolut;
				document.getElementById("cms_speicherplatz_frei_prozentual").innerHTML = frei_ges_prozentual;
				// Gesamtzeile
				document.getElementById("cms_speicherplatz_belegt_db_absolut").innerHTML = belegt_db_absolut;
				document.getElementById("cms_speicherplatz_belegt_db_prozentual").innerHTML = belegt_db_prozentual;
				document.getElementById("cms_speicherplatz_belegt_dat_absolut").innerHTML = belegt_dat_absolut;
				document.getElementById("cms_speicherplatz_belegt_dat_prozentual").innerHTML = belegt_dat_prozentual;
				document.getElementById("cms_speicherplatz_belegt_ges_absolut").innerHTML = belegt_ges_absolut;
				document.getElementById("cms_speicherplatz_belegt_ges_prozentual").innerHTML = belegt_ges_prozentual;
				document.getElementById("cms_speicherplatz_frei_ges_absolut").innerHTML = frei_ges_absolut;
				document.getElementById("cms_speicherplatz_frei_ges_prozentual").innerHTML = frei_ges_prozentual;
			}
			else {cms_fehlerbehandlung(rueckgabe);}
		}

		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}

}
