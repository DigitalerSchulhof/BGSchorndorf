// HELL;

.cms_stundenplan_box {
	display: block;
	position: relative;
}

.cms_stundenplan_ueberschrift {
	position: absolute;
	display: inline-block;
	padding: 0px 1px 0px 1px;
}

.cms_stundenplan_spalte {
	position: relative;
	display: block;
	height: 100%;
	float: left;
	border-left: 1px solid @h_haupt_hintergrund;
	border-right: 1px solid @h_haupt_hintergrund;
}

.cms_stundenplan_spalte .cms_stundenplan_spaltentitel {
	position: absolute;
	font-weight: bold;
	line-height: 20px;
	height: 20px;
	color: @h_haupt_abstufung2;
	display: block;
	text-align: center;
	padding: 0px 1px 0px 1px;
}

.cms_stundenplan_zeitliniebez {
	background: @h_haupt_abstufung2;
	font-weight: bold;
	font-size: 150%;
	position: absolute;
	color: @h_haupt_schriftfarbenegativ;
	width: 35px;
	text-align: center;
	line-height: 100%;
	text-overflow: ellipsis;
	left: 0px;
}

.cms_stundenplan_zeitliniebeginn, .cms_stundenplan_zeitlinieende {
	width: 100%;
	display: block;
	position: absolute;
	border-top: 1px solid @h_haupt_abstufung2;
}

.cms_stundenplan_zeitlinietext {
	font-weight: bold;
	font-size: 70%;
	position: absolute;
	padding: 3px;
	left: 35px;
	color: @h_haupt_abstufung2;
	text-align: left;
}

.cms_stundenplan_zeitliniebeginn .cms_stundenplan_zeitlinietext {
	border-bottom-right-radius: 3px;
	top: 0px;
}

.cms_stundenplan_zeitlinieende .cms_stundenplan_zeitlinietext {
	border-top-right-radius: 3px;
	bottom: 0px;
}

.cms_vollbild {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	min-height: 100%;
	background: @h_haupt_hintergrund;
	z-index: 5001;
	padding: 0px 0px 20px 0px;
	overflow-y: scroll;
}

.cms_stundenplanung_stundenfeld, .cms_stundenplan_stundenfeld {
	display: flex;
	height: 100%;
	position: absolute;
	top: 30px;
	width: 100%;
	transition: 250ms ease-in-out;
}

.cms_stundenfeld_blockiert {
	background: @h_haupt_abstufung2;
}

.cms_stundenplanung_markiert {
	background: @h_haupt_abstufung1;
}

.cms_vplan_stunde_markiert {
	border: 2px dashed @h_haupt_abstufung2;
}

.cms_stundenplanung_stundenfeld:hover {
	cursor: crosshair;
}

.cms_stundenplanung_stunde {
	display: inline-block;
	width: 100%;
	background: @h_haupt_abstufung1;
	height: 100%;
	text-align: center;
	position: relative;
	opacity: .75;
	transition: 250ms ease-in-out;
	font-size: 75%;
	padding: 2px 0px;
}

.cms_stundenplan_stunde, .cms_stundenplan_stunde_entfall,
.cms_stundenplan_stunde_entfallgeaendert, .cms_stundenplan_stunde_ausfall,
.cms_stundenplan_stunde_geloest, .cms_stundenplan_stunde_konflikt,
.cms_stundenplan_stunde_ueberschneidung, .cms_stundenplan_stunde_geaendert {
	display: inline-block;
	height: 100%;
	width: 100%;
	text-align: center;
	position: relative;
	font-size: 75%;
	padding: 2px 0px;
	overflow-y: hidden;
}

.cms_stundenplan_stunde[onclick], .cms_stundenplan_stunde_entfall[onclick],
.cms_stundenplan_stunde_entfallgeaendert[onclick], .cms_stundenplan_stunde_ausfall[onclick],
.cms_stundenplan_stunde_geloest[onclick], .cms_stundenplan_stunde_konflikt[onclick],
.cms_stundenplan_stunde_ueberschneidung[onclick], .cms_stundenplan_stunde_geaendert[onclick] {
	cursor: pointer;
}

.cms_stundenplan_stunde {background: @h_haupt_abstufung1;}
.cms_stundenplan_stunde_entfall {border: 2px dashed @h_haupt_meldunginfohinter; background: @h_haupt_hintergrund;}
.cms_stundenplan_stunde_entfallgeaendert {border: 2px dashed @h_haupt_meldungerfolghinter; background: @h_haupt_hintergrund;}
.cms_stundenplan_stunde_ausfall {color: @h_haupt_schriftfarbepositiv;background: @h_haupt_meldungfehlerhinter;}
.cms_stundenplan_stunde_geloest {background: @h_haupt_meldunginfohinter;}
.cms_stundenplan_stunde_geaendert {background: @h_haupt_meldungerfolghinter;}
.cms_stundenplan_stunde_konflikt {background: @h_haupt_meldungfehlerhinter;}
.cms_stundenplan_stunde_ueberschneidung {background: @h_haupt_meldungwarnunghinter;}

.cms_stundenplan_stunde_rythmus {
	position: absolute;
	bottom: 0px;
	right: 3px;
	opacity: .4;
	color: #ffffff;
	font-size: 250%;
	font-weight: bold;
}

.cms_stundenplanung_stundeinfo {
	color: @h_haupt_schriftfarbenegativ;
	background: @h_hinweis_hintergrund;
	padding: 0px 5px 0px 5px;
	position: absolute;
	font-family: 'robl';
	font-weight: normal !important;
	display: block;
	border-radius: @button_rundeecken;
	z-index: 50;
	width: 100px;
	left: 0px;
	bottom: 10px;
	transition: 250ms ease-in-out;
	height: 0px;
	overflow: hidden;
	text-align: center;
	font-size: 90%;
	opacity: 0;
}

.cms_stundenplanung_stunde:hover .cms_stundenplanung_stundeinfo {height: auto; opacity: 1;}

.cms_stundenplanung_stunde:hover {opacity: 1;}

.cms_wochentag_rythmus, .cms_wochentag_rythmus_schulfrei,
.cms_wochentag_rythmus_ferien, .cms_wochentag_rythmus_leer {
	padding: 2px 5px;
	border-radius: 5px;
	display: inline-block;
	width: 40px;
	text-align: center;
	border: 2px solid @h_haupt_abstufung2;
}

.cms_stundenplan_stunde_gewaehlt {
	border: 2px dotted @h_haupt_meldungerfolgakzent;
}

.cms_wochentag_rythmus_leer {background: @h_haupt_abstufung1; border-color: @h_haupt_abstufung1;}
.cms_wochentag_rythmus {background: @h_haupt_hintergrund;}
.cms_wochentag_rythmus_schulfrei {background: @h_haupt_meldunginfohinter;}
.cms_wochentag_rythmus_ferien {background: @h_haupt_meldungerfolghinter;}

.cms_stundenplan_spaltentitel .cms_notiz {color: inherit !important;}

.cms_vplan_geloest {
	color: @h_haupt_meldunginfoakzent !important;
}

#cms_spalte_wochenplaene, #cms_spalte_konflikte, #cms_spalte_lehrer,
#cms_spalte_raeume, #cms_spalte_klassen {
	overflow: hidden !important;
}

#cms_vplan_konflikte_liste {
	max-height: 650px;
	overflow: scroll;
	position: relative;
}

.cms_stundenplan_spalte_trenner {
	border-left: 1px solid @h_haupt_abstufung2 !important;
}

.cms_vplan_konfliktplan_wahl {width: 100%;}
.cms_vplan_konfliktplan_wahl td {width: 50%;}

.cms_vplan_entfall {
	opacity: .5;
}

tr.cms_vplan_ausgewaehlt td {
	background-color: @h_haupt_abstufung2;
}

tr.cms_vplan_ausgewaehlt:hover td {
	background-color: @h_haupt_abstufung2 !important;
}

.cms_vplan_konfliktgrund {
	background-color: @h_haupt_meldungfehlerhinter !important;
}

.cms_vplan_konfliktwarnung {
	background-color: @h_haupt_meldungwarnunghinter !important;
}

#cms_lvplan_heute {
	padding: 10px;
	position: absolute;
	width: 40%;
	left: 0%;
	height: 100%;
	overflow: hidden;
}

#cms_lvplan_heute p, #cms_lvplan_heute td, #cms_lvplan_heute h4,
#cms_lvplan_morgen p, #cms_lvplan_morgen td, #cms_lvplan_morgen h4,
#cms_svplan_heute p, #cms_svplan_heute td, #cms_svplan_heute h4,
#cms_svplan_morgen p, #cms_svplan_morgen td, #cms_svplan_morgen h4,
#cms_lvplan_geraete p, #cms_lvplan_geraete td, #cms_lvplan_geraete h4 {font-size: 110% !important;}
#cms_lvplan_geraete p.cms_notiz {font-size: 80% !important;}
#cms_lvplan_heute h2, #cms_lvplan_morgen h2, #cms_lvplan_geraete h2,
#cms_svplan_heute h2, #cms_svplan_morgen h2, #cms_svplan_geraete h2 {font-size: 150% !important;}

#cms_lvplan_geraete {
	padding: 10px;
	position: absolute;
	width: 20%;
	left: 40%;
	height: 100%;
	overflow: hidden;
}

#cms_lvplan_morgen {
	padding: 10px;
	position: absolute;
	width: 40%;
	left: 60%;
	height: 100%;
	overflow: hidden;
}

#cms_svplan_heute {
	padding: 10px;
	position: absolute;
	width: 50%;
	left: 0%;
	height: 100%;
	overflow: hidden;
}

#cms_svplan_morgen {
	padding: 10px;
	position: absolute;
	width: 50%;
	left: 50%;
	height: 100%;
	overflow: hidden;
}

#cms_ausplanung_ausgeplant_l, #cms_ausplanung_ausgeplant_r,
#cms_ausplanung_ausgeplant_k, #cms_ausplanung_ausgeplant_s {
	margin-top: 10px;
}

.cms_markierte_liste_0 td {background: @h_haupt_hintergrund;}
.cms_markierte_liste_1 td {background: @h_haupt_abstufung1; border-bottom: 1px solid @h_haupt_hintergrund;}

.cms_vplanliste_entfall td:first-child {border-left: 4px solid @h_haupt_meldungfehlerhinter;}
.cms_vplanliste_neu td:first-child {border-left: 4px solid @h_haupt_meldungerfolghinter;}

.cms_auswaehlen {
	position: relative;
}

.cms_konflikte_liste_menue {
	background: @h_hinweis_hintergrund;
	position: absolute;
	border-radius: @button_rundeecken;
	top: 0px;
	left: 30px;
	padding: 3px 3px 0px 3px;
	transition: 250ms ease-in-out;
	transition-delay: 1s;
	opacity: 0;
	z-index: 2;
}

// DUNKEL;

.cms_stundenplan_spalte {
	border-left: 1px solid @d_haupt_hintergrund;
	border-right: 1px solid @d_haupt_hintergrund;
}

.cms_stundenplan_spalte .cms_stundenplan_spaltentitel {
	color: @d_haupt_abstufung2;
}

.cms_stundenplan_zeitliniebez {
	background: @d_haupt_abstufung2;
	color: @d_haupt_schriftfarbenegativ;
}

.cms_stundenplan_zeitliniebeginn, .cms_stundenplan_zeitlinieende {
	border-top: 1px solid @d_haupt_abstufung2;
}

.cms_stundenplan_zeitlinietext {
	color: @d_haupt_abstufung2;
}

.cms_vollbild {
	background: @d_haupt_hintergrund;
}

.cms_stundenfeld_blockiert {
	background: @d_haupt_abstufung2;
}

.cms_stundenplanung_markiert {
	background: @d_haupt_abstufung1;
}

.cms_vplan_stunde_markiert {
	border: 2px dashed @d_haupt_abstufung2;
}

.cms_stundenplanung_stunde {
	background: @d_haupt_abstufung1;
}

.cms_stundenplan_stunde {background: @d_haupt_abstufung1;}
.cms_stundenplan_stunde_entfall {border: 2px dashed @d_haupt_meldunginfohinter; background: @d_haupt_hintergrund;}
.cms_stundenplan_stunde_entfallgeaendert {border: 2px dashed @d_haupt_meldungerfolghinter; background: @d_haupt_hintergrund;}
.cms_stundenplan_stunde_ausfall {color: @d_haupt_schriftfarbepositiv;background: @d_haupt_meldungfehlerhinter;}
.cms_stundenplan_stunde_geloest {background: @d_haupt_meldunginfohinter;}
.cms_stundenplan_stunde_geaendert {background: @d_haupt_meldungerfolghinter;}
.cms_stundenplan_stunde_konflikt {background: @d_haupt_meldungfehlerhinter;}
.cms_stundenplan_stunde_ueberschneidung {background: @d_haupt_meldungwarnunghinter;}

.cms_stundenplanung_stundeinfo {
	color: @d_haupt_schriftfarbenegativ;
	background: @d_hinweis_hintergrund;
}

.cms_wochentag_rythmus, .cms_wochentag_rythmus_schulfrei,
.cms_wochentag_rythmus_ferien, .cms_wochentag_rythmus_leer {
	border: 2px solid @d_haupt_abstufung2;
}

.cms_stundenplan_stunde_gewaehlt {
	border: 2px dotted @d_haupt_meldungerfolgakzent;
}

.cms_wochentag_rythmus_leer {background: @d_haupt_abstufung1; border-color: @d_haupt_abstufung1;}
.cms_wochentag_rythmus {background: @d_haupt_hintergrund;}
.cms_wochentag_rythmus_schulfrei {background: @d_haupt_meldunginfohinter;}
.cms_wochentag_rythmus_ferien {background: @d_haupt_meldungerfolghinter;}

.cms_vplan_geloest {
	color: @d_haupt_meldunginfoakzent !important;
}

.cms_stundenplan_spalte_trenner {
	border-left: 1px solid @d_haupt_abstufung2 !important;
}

tr.cms_vplan_ausgewaehlt td {
	background-color: @d_haupt_abstufung2;
}

tr.cms_vplan_ausgewaehlt:hover td {
	background-color: @d_haupt_abstufung2 !important;
}

.cms_vplan_konfliktgrund {
	background-color: @d_haupt_meldungfehlerhinter !important;
}

.cms_vplan_konfliktwarnung {
	background-color: @d_haupt_meldungwarnunghinter !important;
}

.cms_markierte_liste_0 td {background: @d_haupt_hintergrund;}
.cms_markierte_liste_1 td {background: @d_haupt_abstufung1; border-bottom: 1px solid @d_haupt_hintergrund;}

.cms_vplanliste_entfall td:first-child {border-left: 4px solid @d_haupt_meldungfehlerhinter;}
.cms_vplanliste_neu td:first-child {border-left: 4px solid @d_haupt_meldungerfolghinter;}

.cms_konflikte_liste_menue {
	background: @d_hinweis_hintergrund;
}
