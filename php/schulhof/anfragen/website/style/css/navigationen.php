// HELL;

.cms_kopfnavigation {
	text-align: right;
}

.cms_kopfnavigation li {
	list-style-type: none;
	display: @hauptnavigation_anzeigekategorie;
	margin-left: 3px;
	margin-right: 0px;
}

.cms_kopfnavigation li:first-child {
	display: @suche_anzeige;
}

.cms_optimierung_P .cms_fussnavigation, .cms_optimierung_T .cms_fussnavigation {
	margin-bottom: @haupt_absatzschulhof;
}

.cms_optimierung_H .cms_fussnavigation {
	padding-right: 0px;
	min-height: auto;
	text-align: center;
}

.cms_optimierung_H .cms_auszeichnung {
	position: static !important;
	top: auto !important;
	right: auto !important;
	margin-bottom: @haupt_absatzschulhof !important;
	text-align: center;
}

.cms_optimierung_H #cms_fusszeile_i p {text-align: center;}

.cms_fussnavigation li {
list-style: none;
	display: inline-block;
	margin-right: 3px;
	margin-left: 0px;
}

.cms_hauptnavigation {
	text-align: right;
	position: absolute;
	bottom: @hauptnavigation_abstandvonunten;
	top: @hauptnavigation_abstandvonoben;
	left: @hauptnavigation_abstandvonlinks;
	right: @hauptnavigation_abstandvonrechts;
	margin-top: 0px;
	margin-bottom: 0px;
}

.cms_hauptnavigation > li {
	list-style-type: none;
	display: inline-block;
	margin: @hauptnavigation_aussenabstandkategorie;
}

.cms_hauptnavigation .cms_kategorie1,
#cms_kopfnavigation > li > a, #cms_kopfnavigation > li > span {
	border-top-right-radius: @hauptnavigation_kategorieradiusor;
	border-top-left-radius: @hauptnavigation_kategorieradiusol;
	border-bottom-right-radius: @hauptnavigation_kategorieradiusur;
	border-bottom-left-radius: @hauptnavigation_kategorieradiusul;
	display: inline-block;
	background-color: @h_hauptnavigation_kategoriehintergrund;
	color: @h_hauptnavigation_kategoriefarbe;
	padding: @hauptnavigation_kategorieinnenabstand;
	font-weight: bold;
	line-height: 20px;
	margin-bottom: 0px;
user-select: none;
	transition: 250ms ease-in-out;
}

.cms_unternavigation_o {
	position: fixed;
	top: @unternavigation_abstandvonoben;
	left: 0px;
	width: 100%;
	transition: 500ms ease-in-out;
	overflow: hidden;
	z-index: 21;
	max-height: 0px;
}

.cms_hauptnavigation > li:hover .cms_unternavigation_o {
	max-height: 500px;
}

#cms_kopfnavigation a:hover,
#cms_kopfnavigation span:hover {
	transform: translate(0px) !important;
}

#cms_hauptnavigation > li:hover > span.cms_kategorie1,
#cms_kopfnavigation li:hover > a,
#cms_kopfnavigation li:hover > span {
	background-color: @h_hauptnavigation_kategoriehintergrundhover;
	color: @h_hauptnavigation_kategoriefarbehover;
}

#cms_kopfnavigation > li {
	position: relative;
	height: @hauptnavigation_kategoriehoehe;
}

#cms_kopfnavigation span {
	transition: 500ms ease-in-out;
}

#cms_kopfnavigation span:hover {
	cursor: pointer;
}

#cms_kopfnavigation > li > .cms_naviuntermenue {
	background: @h_haupt_abstufung1;
	width: 200px;
	position: absolute;
	right: @schulhofnavigation_abstandvonrechts;
	left: @schulhofnavigation_abstandvonlinks;
	top: @schulhofnavigation_abstandvonoben;
	bottom: @schulhofnavigation_abstandvonunten;
	overflow: hidden;
	max-height: 0px;
	transition: 500ms ease-in-out;
}

#cms_kopfnavigation > li ul {
	border-top: 3px solid @h_hauptnavigation_akzentfarbe;
	border-bottom: 3px solid @h_hauptnavigation_akzentfarbe;
	padding: 5px;
}

#cms_kopfnavigation li:hover > .cms_naviuntermenue {
	max-height: 600px;
}

#cms_kopfnavigation > li ul li {
	list-style-type: none;
	display: block;
	text-align: left;
	padding: 0px;
	margin: @haupt_absatzschulhof 0px @haupt_absatzschulhof 0px;
}

#cms_kopfnavigation > li ul li a,
#cms_kopfnavigation > li ul li span {
	color: @h_haupt_schriftfarbepositiv;
	display: block;
	padding: 3px 7px;
	border-radius: @button_rundeecken;
}

#cms_kopfnavigation > li ul li a:hover,
#cms_kopfnavigation > li ul li span:hover {
	color: @h_haupt_schriftfarbenegativ;
	cursor: pointer;
}

.cms_unternavigation_m {
	background: @h_haupt_abstufung1;
	border-bottom: 3px solid @h_hauptnavigation_akzentfarbe;
	border-top: 3px solid @h_hauptnavigation_akzentfarbe;
	width: @haupt_seitenbreite;
	margin: 0 auto;
}

.cms_unternavigation_i {
	text-align: left;
	position: relative;
}

.cms_unternavigation_i li {
	list-style-type: none;
	display: inline-block;
	margin-top: 0px;
	margin-bottom: 3px;
	margin-left: 0px;
}

.cms_navigation, .cms_navigation ul {
	margin: 0px;
	padding: 0px;
}

.cms_navigation li {
	margin: 0px;
	padding: 0px;
	list-style-type: none;
}

.cms_navigation > li {
	border-top: 1px solid @h_haupt_abstufung1;
}

.cms_navigation > li:last-child {
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.cms_navigation > li a,
.cms_navigation > li span {
	color: @h_haupt_schriftfarbepositiv;
	display: block;
	padding: 10px 5px;
	transition: 500ms ease-in-out;
}

.cms_navigation > li a:hover,
.cms_navigation > li span:hover {
	color: @h_haupt_schriftfarbepositiv;
	background: @h_haupt_abstufung1;
	transform: translate(0px) !important;
	cursor: pointer;
}

.cms_navigation > li > .cms_navigation_aktiveseite {
	color: @h_haupt_schriftfarbenegativ;
	background: @h_hauptnavigation_akzentfarbe;
}

.cms_navigation > li > .cms_navigation_aktiveseite:hover {
	color: @h_haupt_schriftfarbenegativ;
	background: @h_hauptnavigation_akzentfarbe;
}

.cms_navigation > li li .cms_navigation_aktiveseite {
	background: @h_haupt_abstufung1;
}

.cms_navigation .cms_naviuntermenue {
	margin-left: 10px;
}

.cms_navigation .cms_naviuntermenue a,
.cms_navigation .cms_naviuntermenue span {
	padding: 5px;
	font-size: 12px;
}

#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {
	width: 100%;
	background: @h_haupt_meldungerfolghinter;
	transition: 100ms ease-in-out;
}

#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {
	height: 5px;
}

#cms_aktivitaet_in_profil, .cms_fortschritt_i {
	height: 10px;
}

#cms_aktivitaet_out, #cms_maktivitaet_out, #cms_aktivitaet_out_profil, .cms_fortschritt_o {
	width: 100%;
	border-radius: 3px;
	margin-bottom: 3px;
	background: @h_haupt_meldungfehlerhinter;
	overflow:hidden;
}

#cms_aktivitaet_out, #cms_maktivitaet_out {
	border: 1px solid @h_haupt_hintergrund;
}

.cms_optimierung_P #cms_mobilnavigation {display: none;}
.cms_optimierung_T #cms_hauptnavigation, .cms_optimierung_H #cms_hauptnavigation,
.cms_optimierung_T #cms_kopfnavigation, .cms_optimierung_H #cms_kopfnavigation
{display: none !important;}

#cms_mobilnavigation {
	border-top-right-radius: 5px;
	border-top-left-radius: 5px;
	display: inline-block;
	background-color: @h_mobilnavigation_iconhintergrund !important;
	color: @h_haupt_schriftfarbenegativ !important;
	padding: 4px 10px;
	font-weight: bold;
	line-height: 20px;
	margin-bottom: 0px;
user-select: none;
	position: absolute;
	right: 10px;
	bottom: 0px;
}

#cms_mobilnavigation:hover {
	cursor: pointer !important;
	background-color: @h_mobilnavigation_iconhintergrundhover !important;
}

.cms_menuicon {
	width: 20px;
	height: 4px;
	border-radius: 2px;
	background-color: @h_haupt_schriftfarbenegativ;
	margin: 4px 0;
	display: block;
}

#cms_mobilmenue_a {
// display: none;
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	background: @h_haupt_abstufung1;
	z-index: 100000;
	overflow-y: scroll;
}

#cms_mobilmenue_i {padding: 10px 10px 20px 10px;}

#cms_mobilmenue_i p.cms_mobilmenue_knoepfe {
	display: flex;
	flex-wrap: wrap;
}

#cms_mobilmenue_i p.cms_mobilmenue_knoepfe span, #cms_mobilmenue_i p.cms_mobilmenue_knoepfe a {
	width: 50%;
	text-align: center;
}

#cms_mobilmenue_i p.cms_mobilmenue_knoepfe span,
#cms_mobilmenue_i p.cms_mobilmenue_knoepfe a:last-child {
	border-top-left-radius: 0px;
	border-bottom-left-radius: 0px;
}

#cms_mobilmenue_i p.cms_mobilmenue_knoepfe a:first-child {
	border-top-right-radius: 0px;
	border-bottom-right-radius: 0px;
}

#cms_mobilmenue_seiten p.cms_notiz {
	text-align: center;
}

#cms_mobilmenue_seiten {
	margin-top: 30px;
}

#cms_mobilmenue_seiten > div div {
	padding-left: 10px;
	display: none;
}

#cms_mobilmenue_seiten ul {
	padding: 0px;
	margin: 0px;
	width: 100%;
}

#cms_mobilmenue_seiten li {
	list-style-type: none;
	padding: 0px;
	margin: 0px;
}

#cms_mobilmenue_seiten > div > ul > li {border-top: 1px solid @h_haupt_abstufung2;}
#cms_mobilmenue_seiten > div > ul > li:last-child {border-bottom: 1px solid @h_haupt_abstufung2;}

#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {
	padding: 5px;
	transition: 250ms ease-in-out;
	color: @h_haupt_schriftfarbepositiv;
	display: inline-block;
	border-radius: @button_rundeecken;
	position: relative;
}

#cms_mobilmenue_seiten li .cms_meldezahl {
	position: absolute;
	padding: 3px 5px;
	background: @h_haupt_abstufung2;
	border-radius: 10px;
	opacity: 1;
	color: @h_haupt_schriftfarbenegativ;
	display: block;
	top: 3px;
	right: 4px;
	height: 18px;
	line-height: 12px;
	width: auto;
	min-width: 30px;
	text-align: center;
}

#cms_mobilmenue_seiten li .cms_meldezahl_wichtig {
	background: @h_haupt_meldungfehlerakzent !important;
}

#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {
	transform: translate(0px) !important;
	cursor: pointer;
	color: @h_haupt_schriftfarbenegativ;
}

#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {width: 90%;}
#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen {
	width: 10%;
	text-align: center;
}

#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {
	background-color: @h_mobilnavigation_iconhintergrundhover;
}
#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen:hover {
	background-color: @h_haupt_abstufung2;
}

#cms_mobilmenue_seiten li .cms_mobilnavi_passiv {
	cursor: default !important;
	color: @h_haupt_schriftfarbepositiv !important;
	background-color: transparent !important;
}

.cms_unternavigation_schliessen {
	position: absolute !important;
	right: 10px;
	top: 5px;
	opacity: 0;
	transition: 500ms ease-in-out;
}

.cms_aktionen_liste li {
	margin-left: 0px;
	list-style-type: none;
	display: inline-block;
	margin-top: 0px;
	margin-bottom: 3px;
}

.cms_uebersicht {
	margin-left: 0px;
	margin-right: 0px;
}

.cms_uebersicht li {
	list-style-type: none;
	margin-left: 0px;
	margin-bottom: 0px;
	padding: 0px;
	position: relative;
}

.cms_uebersicht li a,
.cms_uebersicht li span.cms_appmenue_element,
.cms_uebersicht li span.cms_uebersicht_notifikation,
.cms_uebersicht li span.cms_uebersicht_verwaltung_termin,
.cms_uebersicht li span.cms_blogeintrag,
.cms_uebersicht li span.cms_beschlusseintrag,
.cms_uebersicht li span.cms_uebersicht_verwaltung_gremien,
.cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften,
.cms_uebersicht li span.cms_uebersicht_verwaltung_ferien,
.cms_uebersicht li span.cms_uebersicht_vertretungsplanung {
	display: block;
	padding: 5px 5px 5px 47px;
	border-top: 1px solid @h_haupt_abstufung1;
	background-repeat: no-repeat;
	background-position: 5px 5px;
	min-height: 42px;
	color: @h_haupt_schriftfarbepositiv;
	position: relative;
}

.cms_uebersicht li a.cms_blogvorschau_ohneicon {
	padding: 5px !important;
}

.cms_uebersicht li:last-child a,
.cms_uebersicht li:last-child span.cms_appmenue_element,
.cms_uebersicht li:last-child span.cms_uebersicht_notifikation,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_termin,
.cms_uebersicht li:last-child span.cms_blogeintrag,
.cms_uebersicht li:last-child span.cms_beschlusseintrag,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_gremien,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_fachschaften,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_ferien,
.cms_uebersicht li:last-child span.cms_uebersicht_vertretungsplanung {
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.cms_uebersicht li a p,
.cms_uebersicht li span.cms_appmenue_element p,
.cms_uebersicht li span.cms_uebersicht_notifikation p,
.cms_uebersicht li span.cms_uebersicht_verwaltung_termin p,
.cms_uebersicht li span.cms_blogeintrag p,
.cms_uebersicht li span.cms_beschlusseintrag p,
.cms_uebersicht li span.cms_uebersicht_verwaltung_gremien p,
.cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften p,
.cms_uebersicht li span.cms_uebersicht_verwaltung_ferien p,
.cms_uebersicht .cms_blogliste_details p {
	font-size: 90%;
	margin-top: 2px;
}

.cms_uebersicht li a h3,
.cms_uebersicht li span.cms_appmenue_element h3,
.cms_uebersicht li span.cms_uebersicht_notifikation h3,
.cms_uebersicht li span.cms_uebersicht_verwaltung_termin h3,
.cms_uebersicht li span.cms_blogeintrag h3,
.cms_uebersicht li span.cms_beschlusseintrag h3,
.cms_uebersicht li span.cms_uebersicht_verwaltung_gremien h3,
.cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften h3,
.cms_uebersicht li span.cms_uebersicht_verwaltung_ferien h3,
.cms_uebersicht .cms_blogliste_details h3 {
	font-size: 110%;
	margin-bottom: 3px;
	margin-top: 2px;
}

.cms_uebersicht_notifikation .cms_notifikation_schliessen {
	position: absolute;
	right: 5px !important;
	top: 5px !important;
	opacity: 0;
	transition: 250ms ease-in-out;
}

.cms_uebersicht_notifikation:hover .cms_notifikation_schliessen {
	opacity: 1;
}

.cms_uebersicht li {
	margin-top: 0px;
}

.cms_uebersicht a, .cms_uebersicht span.cms_appmenue_element {
	transition: 500ms ease-in-out;
}

.cms_uebersicht a:hover, .cms_uebersicht span.cms_appmenue_element:hover {
	background-color: @h_haupt_abstufung1;
	transform: translateX(0) translateY(0);
	cursor: pointer;
}

.cms_uebersicht .cms_blog_keinhover {
	cursor: default !important;
	background-color: @h_haupt_hintergrund !important;
}


.cms_anteilbalken_innen {
	width: 100%;
	background: @h_haupt_meldungfehlerhinter;
	transition: 100ms ease-in-out;
}

.cms_anteilbalken_innen {
	height: 10px;
}

.cms_anteilbalken_aussen {
	width: 100%;
	border-radius: 5px;
	margin-bottom: 3px;
	overflow: hidden;
	background: @h_haupt_abstufung1;
}

.cms_anteilbalken_notiz {
	text-align: center;
	margin-bottom: 20px;
	font-size: 70%;
	color: @h_haupt_abstufung2;
}

// DUNKEL;

.cms_hauptnavigation .cms_kategorie1,
#cms_kopfnavigation > li > a, #cms_kopfnavigation > li > span {
	background-color: @d_hauptnavigation_kategoriehintergrund;
	color: @d_hauptnavigation_kategoriefarbe;
}

#cms_hauptnavigation > li:hover > span.cms_kategorie1,
#cms_kopfnavigation li:hover > a,
#cms_kopfnavigation li:hover > span {
	background-color: @d_hauptnavigation_kategoriehintergrundhover;
	color: @d_hauptnavigation_kategoriefarbehover;
}

#cms_kopfnavigation > li > .cms_naviuntermenue {
	background: @d_haupt_abstufung1;
}

#cms_kopfnavigation > li ul {
	border-top: 3px solid @d_hauptnavigation_akzentfarbe;
	border-bottom: 3px solid @d_hauptnavigation_akzentfarbe;
}

#cms_kopfnavigation > li ul li a,
#cms_kopfnavigation > li ul li span {
	color: @d_haupt_schriftfarbepositiv;
}

#cms_kopfnavigation > li ul li a:hover,
#cms_kopfnavigation > li ul li span:hover {
	color: @d_haupt_schriftfarbepositiv;
	cursor: pointer;
}

.cms_unternavigation_m {
	background: @d_haupt_abstufung1;
	border-bottom: 3px solid @d_hauptnavigation_akzentfarbe;
	border-top: 3px solid @d_hauptnavigation_akzentfarbe;
}

.cms_navigation > li {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_navigation > li:last-child {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_navigation > li a,
.cms_navigation > li span {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_navigation > li a:hover,
.cms_navigation > li span:hover {
	color: @d_haupt_schriftfarbepositiv;
	background: @d_haupt_abstufung1;
}

.cms_navigation > li > .cms_navigation_aktiveseite {
	color: @d_haupt_schriftfarbenegativ;
	background: @d_hauptnavigation_akzentfarbe;
}

.cms_navigation > li > .cms_navigation_aktiveseite:hover {
	color: @d_haupt_schriftfarbenegativ;
	background: @d_hauptnavigation_akzentfarbe;
}

.cms_navigation > li li .cms_navigation_aktiveseite {
	background: @d_haupt_abstufung1;
}

#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {
	background: @d_haupt_meldungerfolghinter;
}

#cms_aktivitaet_out, #cms_maktivitaet_out, #cms_aktivitaet_out_profil, .cms_fortschritt_o {
	background: @d_haupt_meldungfehlerhinter;
}

#cms_aktivitaet_out, #cms_maktivitaet_out {
	border: 1px solid @d_haupt_hintergrund;
}

#cms_mobilnavigation {
	background-color: @d_mobilnavigation_iconhintergrund !important;
	color: @d_haupt_schriftfarbenegativ !important;
}

#cms_mobilnavigation:hover {
	cursor: pointer !important;
	background-color: @d_mobilnavigation_iconhintergrundhover !important;
}

.cms_menuicon {
	background-color: @d_haupt_schriftfarbenegativ;
}

#cms_mobilmenue_a {
	background: @d_haupt_abstufung1;
}

#cms_mobilmenue_seiten > div > ul > li {border-top: 1px solid @d_haupt_abstufung2;}
#cms_mobilmenue_seiten > div > ul > li:last-child {border-bottom: 1px solid @d_haupt_abstufung2;}

#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {
	color: @d_haupt_schriftfarbepositiv;
}

#cms_mobilmenue_seiten li .cms_meldezahl {
	background: @d_haupt_abstufung2;
	color: @d_haupt_schriftfarbenegativ;
}

#cms_mobilmenue_seiten li .cms_meldezahl_wichtig {
	background: @d_haupt_meldungfehlerakzent !important;
}

#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {
	color: @d_haupt_schriftfarbenegativ;
}

#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {
	background-color: @d_mobilnavigation_iconhintergrundhover;
}
#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen:hover {
	background-color: @d_haupt_abstufung2;
}

#cms_mobilmenue_seiten li .cms_mobilnavi_passiv {
	color: @d_haupt_schriftfarbepositiv !important;
}

.cms_uebersicht li a,
.cms_uebersicht li span.cms_appmenue_element,
.cms_uebersicht li span.cms_uebersicht_notifikation,
.cms_uebersicht li span.cms_uebersicht_verwaltung_termin,
.cms_uebersicht li span.cms_blogeintrag,
.cms_uebersicht li span.cms_beschlusseintrag,
.cms_uebersicht li span.cms_uebersicht_verwaltung_gremien,
.cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften,
.cms_uebersicht li span.cms_uebersicht_verwaltung_ferien,
.cms_uebersicht li span.cms_uebersicht_vertretungsplanung {
	border-top: 1px solid @d_haupt_abstufung1;
	color: @d_haupt_schriftfarbepositiv;
}

.cms_uebersicht li:last-child a,
.cms_uebersicht li:last-child span.cms_appmenue_element,
.cms_uebersicht li:last-child span.cms_uebersicht_notifikation,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_termin,
.cms_uebersicht li:last-child span.cms_blogeintrag,
.cms_uebersicht li:last-child span.cms_beschlusseintrag,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_gremien,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_fachschaften,
.cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_ferien,
.cms_uebersicht li:last-child span.cms_uebersicht_vertretungsplanung {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_uebersicht a:hover, .cms_uebersicht span.cms_appmenue_element:hover {
	background-color: @d_haupt_abstufung1;
}

.cms_uebersicht .cms_blog_keinhover {
	background-color: @d_haupt_hintergrund !important;
}

.cms_anteilbalken_innen {
	background: @d_haupt_meldungfehlerhinter;
}

.cms_anteilbalken_aussen {
	background: @d_haupt_abstufung1;
}

.cms_anteilbalken_notiz {
	color: @d_haupt_abstufung2;
}

// DRUCKEN;
