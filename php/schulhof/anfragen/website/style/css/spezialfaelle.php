// HELL;

#cms_neuerungenverlauf p + h4 {
	margin-top: @haupt_absatzschulhof;
}

.cms_sichtbar {
	max-height: 2500px !important;
}

#cms_dsgvo_datenschutz {
	position:fixed;
	bottom:0px;
	width: 100%;
	left: 0px;
	padding:10px;
	z-index: 2;
}

#cms_dsgvo_datenschutz .cms_meldung {
	margin: 0px;
	box-shadow: 0px 0px 10px @h_haupt_meldungfehlerakzent;
}

#cms_netzcheckstatus span {font-size: inherit;}

.cms_filter_ein {
	display: none;
}

.cms_absender {text-align: right;}
.cms_empfaenger, .cms_anhangtitel {color: @h_haupt_abstufung2;}

.cms_postfach_papierkorb_aussen {
	width: 6px;
	height: 23px;
	background: @h_haupt_meldungfehlerhinter;
	border-radius: @button_rundeecken;
	display: inline-block;
	line-height: 0px;
	position: relative;
	bottom: -6px;
	margin-bottom: 2px;
}

.cms_postfach_papierkorb_innen {
	width: 6px;
	height: 23px;
	background: @h_haupt_meldungerfolghinter;
	border-radius: @button_rundeecken;
	display: block;
	position: absolute;
	bottom: 0px;
	left: 0px;
}

.cms_button .cms_postfach_anhang {
	margin-right: 0px !important;
}

.cms_postfach_anhang {
	display: inline-block;
	margin-right: 10px;
	margin-bottom: 3px;
	position: relative;
}

.cms_postfach_anhang img {
	position: relative;
	top: 3px;
}

.cms_postfach_anhang .cms_button_nein {
	position: absolute;
	left: -3px;
	z-index: 1;
	display: none;
}

.cms_postfach_anhang:hover .cms_button_nein {
	display: block;
}

.cms_signatur, .cms_originalnachricht_meta {
	margin-top: 15px;
	padding-top:5px;
	border-top: 1px dotted @h_haupt_abstufung2;
	color: @h_haupt_abstufung2;
	font-size: 80%;
}

.cms_originalnachricht {
	padding-left: 5px;
	border-left: 2px solid @h_haupt_abstufung1;
}

.cms_versteckt {
	display: none;
}

#cms_debug {
	bottom: 0px;
	left: 0px;
	z-index: 1000;
	padding: 10px 10px 30px 10px;
	background: @h_haupt_meldungwarnunghinter;
	display: none;
}

.cms_geraeteproblem_meldung {
	margin-top: 20px;
}

.cms_geraeteproblem_meldung textarea {
	height: 50px !important;
}

#cms_kurse_kursklassen .cms_notiz {
	text-align: center;
}

.cms_optimierung_P ul.cms_bloguebersicht a p img {
	width: 100%;
}

.cms_optimierung_H ul.cms_bloguebersicht a p img {
	width: 100%;
}

#cms_gruppe_icon_auswahl {
	display: block;
	position: absolute;
	top: 0px;
	left: 0px;
	z-index: 2;
	width: 100%;
	max-width: 800px;
	background: @h_haupt_abstufung1;
	padding: 10px;
	margin-bottom: 15px;
	border-radius: 10px;
}

.cms_nicht_genehmigt {
	opacity: .35;
}

.cms_genehmigungausstehend {
	font-weight: bold;
	padding: 5px !important;
	border-radius: 5px;
	color: @h_haupt_schriftfarbenegativ;
	background-color: @h_haupt_meldungfehlerakzent;
}

.cms_auftragausstehend {
	padding: 5px !important;
	border-radius: 5px;
	color: @h_haupt_schriftfarbenegativ;
	background-color: @h_haupt_meldungfehlerakzent;
	display: inline-block;
	text-align: center;
	font-size: 80%;
}

.cms_auftragerledigt {
	padding: 5px !important;
	border-radius: 5px;
	color: @h_haupt_schriftfarbenegativ;
	background-color: @h_haupt_meldungerfolgakzent;
	display: inline-block;
	text-align: center;
	font-size: 80%;
}

.cms_spamschutz {
	border-radius: 5px;
	border: 1px solid @h_haupt_hintergrund;
}

.cms_vorlaeufig {
	opacity: .5;
}

.cms_vollbild {
	position: fixed;
	width: 100%;
	height: 100%;
	background: @h_haupt_hintergrund;
	top: 0px;
	left: 0px;
	z-index: 10000000;
	display: block;
	padding: 20px;
	font-size: 120%;
}

.cms_gesichert {
	margin-top: 20px;
	margin-bottom: 20px;
}

.cms_reitermenue_i .cms_gesichert, .cms_reitermenue_i .cms_meldung_laden {
	margin-top: 0px;
	margin-bottom: 0px;
}

.cms_fortschritt_box {
	margin-top:15px;
}

.cms_legende {
	display: flex;
	flex-wrap: wrap;
}

.cms_legende span {
	width: 33.3333333%;
	display: inline-block;
}

#cms_speicherplatz_frei {
	width: 100%;
	height: 30px;
	margin-bottom: 3px;
	background: @h_haupt_abstufung1;
	line-height: 1;
	overflow:hidden;
	border-radius: @haupt_radiussehrgross;
}

#cms_speicherplatz_system_balken, #cms_speicherplatz_website_balken,
#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_gruppen_balken,
#cms_speicherplatz_personen_balken {
	height: 100%;
	width: 0%;
	display: inline-block;
	line-height: 1;
	transition: 250ms ease-in-out;
}

#cms_speicherplatz_system_icon, #cms_speicherplatz_website_icon,
#cms_speicherplatz_schulhof_icon, #cms_speicherplatz_gruppen_icon,
#cms_speicherplatz_personen_icon {
	height: 15px;
	width: 15px;
	display: inline-block;
	border-radius: 10px;
}

#cms_speicherplatz_system_balken, #cms_speicherplatz_system_icon {background: @h_haupt_meldungfehlerhinter;}
#cms_speicherplatz_website_balken, #cms_speicherplatz_website_icon {background: @h_haupt_meldungwarnunghinter;}
#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_schulhof_icon {background: @h_haupt_meldunginfohinter;}
#cms_speicherplatz_gruppen_balken, #cms_speicherplatz_gruppen_icon {background: @h_haupt_meldungerfolghinter;}
#cms_speicherplatz_personen_balken, #cms_speicherplatz_personen_icon {background: @h_haupt_abstufung2;}

// DUNKEL;

#cms_dsgvo_datenschutz .cms_meldung {
	box-shadow: 0px 0px 10px @d_haupt_meldungfehlerakzent;
}

.cms_empfaenger, .cms_anhangtitel {color: @d_haupt_abstufung2;}

.cms_postfach_papierkorb_aussen {
	background: @d_haupt_meldungfehlerhinter;
}

.cms_postfach_papierkorb_innen {
	background: @d_haupt_meldungerfolghinter;
}

.cms_signatur, .cms_originalnachricht_meta {
	border-top: 1px dotted @d_haupt_abstufung2;
	color: @d_haupt_abstufung2;
}

.cms_originalnachricht {
	border-left: 2px solid @d_haupt_abstufung1;
}

#cms_debug {
	background: @d_haupt_meldungwarnunghinter;
}

#cms_gruppe_icon_auswahl {
	background: @d_haupt_abstufung1;
}

.cms_genehmigungausstehend {
	color: @d_haupt_schriftfarbenegativ;
	background-color: @d_haupt_meldungfehlerakzent;
}

.cms_auftragausstehend {
	color: @d_haupt_schriftfarbenegativ;
	background-color: @d_haupt_meldungfehlerakzent;
}

.cms_auftragerledigt {
	color: @d_haupt_schriftfarbenegativ;
	background-color: @d_haupt_meldungerfolgakzent;
}

.cms_spamschutz {
	border: 1px solid @d_haupt_hintergrund;
}

.cms_vollbild {
	background: @d_haupt_hintergrund;
}



#cms_speicherplatz_frei {
	background: @d_haupt_abstufung1;
}

#cms_speicherplatz_system_balken, #cms_speicherplatz_system_icon {background: @d_haupt_meldungfehlerhinter;}
#cms_speicherplatz_website_balken, #cms_speicherplatz_website_icon {background: @d_haupt_meldungwarnunghinter;}
#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_schulhof_icon {background: @d_haupt_meldunginfohinter;}
#cms_speicherplatz_gruppen_balken, #cms_speicherplatz_gruppen_icon {background: @d_haupt_meldungerfolghinter;}
#cms_speicherplatz_personen_balken, #cms_speicherplatz_personen_icon {background: @d_haupt_abstufung2;}
