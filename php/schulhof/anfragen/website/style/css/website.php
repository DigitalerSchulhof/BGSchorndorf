// HELL;

.cms_termine_jahrueberischt_knoepfe_vorher {
	display: inline-block;
	text-align: left;
	width: 20%;
}

.cms_termine_jahrueberischt_knoepfe_jahr {
	display: inline-block;
	text-align: center;
	width: 60%;
	font-weight: bold;
	color: @h_haupt_abstufung2;
}

.cms_termine_jahrueberischt_knoepfe_nachher {
	display: inline-block;
	text-align: right;
	width: 20%;
}

.cms_termine_jahrueberischt_knoepfe_vorher .cms_button,
.cms_termine_jahrueberischt_knoepfe_nachher .cms_button {
	font-weight: bold !important;
	color: @h_haupt_abstufung2!important;
}

.cms_termine_jahrueberischt_knoepfe_vorher .cms_button:hover,
.cms_termine_jahrueberischt_knoepfe_nachher .cms_button:hover {
	font-weight: bold !important;
	color: @h_haupt_schriftfarbenegativ!important;
}

.cms_termine_jahrueberischt_allemonate_namen {
	padding-top: 5px;
	border-top: 1px solid @h_haupt_abstufung1;
}

.cms_termine_jahrueberischt_monat {
	width: 6.5%;
	margin-right: 2%;
	text-align: center;
	float: left;
	display: block;
	color: @h_haupt_abstufung2;
	font-weight: bold;
}

.cms_termine_jahrueberischt_monat.cms_letzte {
	margin-right: 0%;
}

.cms_termine_jahrueberischt_allemonate_balken {
	padding: 10px 0px 0px 0px;
	display: block;
}

.cms_termine_jahrueberischt_monathoeheF {
	width: 6.5%;
	margin-right: 2%;
	float: left;
	display: block;
	height: 100px;
	position: relative;
}

.cms_termine_jahrueberischt_monathoeheF.cms_letzte {
	margin-right: 0%;
}

.cms_termine_jahrueberischt_monathoehe {
	width: 100%;
	float: left;
	display: block;
	background-color: @h_zeitdiagramm_balken;
	border-top-left-radius: @zeitdiagramm_radiusoben;
	border-top-right-radius: @zeitdiagramm_radiusoben;
	border-bottom-left-radius: @zeitdiagramm_radiusunten;
	border-bottom-right-radius: @zeitdiagramm_radiusunten;
	bottom: 0px;
	position: absolute;
	transition: 500ms ease-in-out;
}

.cms_termine_jahrueberischt_monathoehe.cms_jahresuebersicht_aktuell,
.cms_termine_jahrueberischt_monathoehe:hover {
	background-color: @h_zeitdiagramm_balkenhover;
	transform: translate(0px) !important;
	cursor: pointer
}

.cms_element_editor {
	margin-top: @haupt_absatzschulhof;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_artikellink_anzeige {
	display: block !important;
	color: inherit;
	width: 100%;
}

.cms_download_anzeige,
.cms_artikellink_anzeige {
	padding: 5px 5px 5px 42px;
	background-position: 5px 5px;
	min-height: 44px;
	background-repeat: no-repeat;
	background-color: @h_haupt_abstufung1;
	border: 1px solid @h_haupt_abstufung1;
	transition: 500ms ease-in-out;
	border-left: 3px solid @h_haupt_abstufung2;
	line-height: 1em !important;
}

.cms_download_anzeige p, .cms_download_anzeige h4,
.cms_artikellink_anzeige p, .cms_artikellink_anzeige h4 {
	line-height: 1em !important;
	transition: 500ms ease-in-out;
	overflow:hidden;
	text-overflow: ellipsis;
}

.cms_download_anzeige:hover,
.cms_artikellink_anzeige:hover {
	background-color: @h_haupt_abstufung2;
	cursor: pointer;
}

.cms_download_anzeige:hover p, .cms_download_anzeige:hover h4 {color: @h_haupt_schriftfarbenegativ;}
.cms_artikellink_anzeige:hover p, .cms_artikellink_anzeige:hover h4 {color: @h_haupt_schriftfarbenegativ;}

.cms_download_inaktiv {opacity: .5;}
.cms_download_inaktiv:hover {cursor: not-allowed;}

.cms_website_menuepunkte {
	padding: 5px !important;
	min-height: 0 !important;
}

.cms_website_menuepunkte_ja {
	display: block;
	padding: 5px;
	background: @h_haupt_meldungerfolghinter;
	border-top: 1px solid @h_haupt_abstufung1;
	border-bottom: 1px solid @h_haupt_abstufung1;
	font-weight: bold;
	transition: 500ms ease-in-out;
}

.cms_website_menuepunkte_ja:hover {
	background: @h_haupt_meldungerfolgakzent;
	color: @h_haupt_schriftfarbenegativ;
	cursor: pointer;
}

.cms_website_menuepunkte h3:last-child {margin-bottom: 0px;}

.cms_zugehoerig {
	background: @h_haupt_abstufung1;
	padding: 5px;
	border-top-right-radius: @zugehoerig_radius;
	border-top-left-radius: @zugehoerig_radius;
	border-bottom-right-radius: @zugehoerig_radius;
	border-bottom-left-radius: @zugehoerig_radius;
	margin-top: 10px;
	opacity: 0;
	text-align: center !important;
	transition: 250ms ease-in-out;
}

.cms_zugehoerig h3, .cms_zugehoerig p {text-align: center !important;}

.cms_zugehoerig h3 img {
	margin-right: 3px;
	position: relative;
	top: 2px;
}

.cms_zugehoerig table {width: 100%; padding: 0px; margin:0px;}
.cms_zugehoerig table td {font-size: 70%;}

.cms_zugehoerig td:nth-child(1) {text-align: left;}
.cms_zugehoerig td:nth-child(2) {text-align: center;}
.cms_zugehoerig td:nth-child(3) {text-align: right;}

.cms_zugehoerig h4 {margin-top: 10px; color: @h_haupt_abstufung2; text-align: left; padding-left: 5px;}
.cms_zugehoerig ul {padding: 0px; margin: 0px;}
.cms_zugehoerig li {padding: 0px; margin: 0px; list-style-type: none;}
.cms_zugehoerig li a {
	display: block;
	transition: 250ms ease-in-out;
	padding: 2px 5px;
	margin-bottom: 6px;
	border-radius: @button_rundeecken;
	background: @h_haupt_abstufung1;
	color: @h_haupt_schriftfarbepositiv;
	text-align: left;
}
.cms_zugehoerig li a:hover {
	cursor: pointer;
	background: @h_zugehoerig_hintergrundhover;
	color: @h_zugehoerig_farbehover;
}
.cms_zugehoerig li:last-child a {margin-bottom: 0px;}
.cms_zugehoerig li a span.cms_zugehoerig_datum {
	font-size: 70%;
}

.cms_box_u input, .cms_box_n input {width: 100% !important;}

.cms_boxen_n {
	display: flex;
	flex-wrap: wrap;
}

.cms_boxen_u {
	display: block;
}

.cms_box_u, .cms_box_n {
	background-color: @h_haupt_hintergrund;
	border-radius: @button_rundeecken;
}

.cms_box_n {
	float: left;
	margin-right: 10px;
	margin-bottom: 10px;
}

.cms_box_u {
	width: 100%;
	margin-bottom: @haupt_absatzschulhof;
	display: flex;
}

.cms_box_titel .cms_formular {
	width: 100%;
}

.cms_box_u .cms_box_titel,
.cms_box_u .cms_box_inhalt {
	float: left;
}

.cms_box_n .cms_box_titel,
.cms_box_n .cms_box_inhalt {
	display: block;
}

.cms_box_titel, .cms_box_inhalt {padding: 10px;}

.cms_box_u .cms_box_titel {
	border-top-left-radius: @button_rundeecken;
	border-bottom-left-radius: @button_rundeecken;
	border-right: 1px solid @h_haupt_schriftfarbepositiv;
}

.cms_box_u .cms_box_inhalt {
	border-top-right-radius: @button_rundeecken;
	border-bottom-right-radius: @button_rundeecken;
}

.cms_box_n .cms_box_titel {
	border-top-left-radius: @button_rundeecken;
	border-top-right-radius: @button_rundeecken;
	border-bottom: 1px solid @h_haupt_schriftfarbepositiv;
}

.cms_box_n .cms_box_inhalt {
	border-bottom-left-radius: @button_rundeecken;
	border-bottom-right-radius: @button_rundeecken;
}

.cms_box_1 .cms_box_titel, .cms_box_1 .cms_box_inhalt {background-color: @h_haupt_hintergrund;}
.cms_box_2 .cms_box_titel, .cms_box_3 .cms_box_titel,
.cms_box_4 .cms_box_titel, .cms_box_5 .cms_box_titel
{background-color: @h_haupt_abstufung1;}

.cms_box_2 .cms_box_inhalt, .cms_box_2 {background-color: @h_haupt_hintergrund;}
.cms_box_3 .cms_box_inhalt, .cms_box_3 {background-color: @h_haupt_thema1;}
.cms_box_4 .cms_box_inhalt, .cms_box_4 {background-color: @h_haupt_thema2;}
.cms_box_5 .cms_box_inhalt, .cms_box_5 {background-color: @h_haupt_thema3;}

.cms_box_3 .cms_box_inhalt *, .cms_box_4 .cms_box_inhalt *,
.cms_box_5 .cms_box_inhalt *, .cms_box_3 .cms_box_inhalt .cms_button:hover,
.cms_box_4 .cms_box_inhalt .cms_button:hover, .cms_box_5 .cms_box_inhalt .cms_button:hover
{color: @h_haupt_schriftfarbenegativ;}

.cms_box_3 .cms_box_inhalt .cms_button, .cms_box_4 .cms_box_inhalt .cms_button,
.cms_box_5 .cms_box_inhalt .cms_button, .cms_box_3 .cms_box_inhalt .note-editor *,
.cms_box_4 .cms_box_inhalt .note-editor *, .cms_box_5 .cms_box_inhalt .note-editor *
{color: @h_haupt_schriftfarbepositiv!important;}

.cms_optimierung_H .cms_eventuebersicht_box_termine,
.cms_optimierung_T .cms_eventuebersicht_aussen_t .cms_eventuebersicht_box_termine,
.cms_optimierung_P .cms_eventuebersicht_aussen_t .cms_eventuebersicht_box_termine {width: 100%;}
.cms_optimierung_H .cms_eventuebersicht_box_blog,
.cms_optimierung_T .cms_eventuebersicht_aussen_b .cms_eventuebersicht_box_blog,
.cms_optimierung_P .cms_eventuebersicht_aussen_b .cms_eventuebersicht_box_blog {width: 100%;}
.cms_optimierung_H .cms_eventuebersicht_box_galerien,
.cms_optimierung_T .cms_eventuebersicht_aussen_g .cms_eventuebersicht_box_galerien,
.cms_optimierung_P .cms_eventuebersicht_aussen_g .cms_eventuebersicht_box_galerien {width: 100%;}

.cms_optimierung_T .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_blog,
.cms_optimierung_P .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_blog {width: 66.66666666%;}
.cms_optimierung_T .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_termine,
.cms_optimierung_P .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_termine {width: 33.33333333%;}

.cms_optimierung_T .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_galerien,
.cms_optimierung_P .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_galerien {width: 66.66666666%;}
.cms_optimierung_T .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_termine,
.cms_optimierung_P .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_termine {width: 33.33333333%;}

.cms_optimierung_T .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_galerien,
.cms_optimierung_T .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_termine,
.cms_optimierung_P .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_galerien,
.cms_optimierung_P .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_termine {width: 50%;}

.cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_blog,
.cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_blog {width: 40%;}
.cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_galerien,
.cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_galerien {width: 40%;}
.cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_termine,
.cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_termine {width: 20%;}

.cms_optimierung_H .cms_eventuebersicht_box_a, .cms_optimierung_T .cms_eventuebersicht_box_a {margin-bottom: 30px;}

.cms_eventuebersicht_box_a {float: left;}
.cms_eventuebersicht_box_i {
	padding-left: 10px;
	padding-right: 10px;
}

.cms_eventuebersicht_box_a:first-child .cms_eventuebersicht_box_i {padding-left: 0px;}
.cms_eventuebersicht_box_a:last-child .cms_eventuebersicht_box_i {padding-right: 0px;}

#cms_hauptteil_i img, #cms_hauptteil_i video, #cms_hauptteil_i audio {max-width: 100%;}
.cms_liste td > img, .cms_formular td > img, .cms_dateisystem_tabelle td > img, .cms_icon_klein_o > img {max-width: none !important;}

.cms_kopfnavigation .cms_websitesuche {
	margin-right: 10px;
	position: relative;
	width: 250px;
}

#cms_mobilmenue_i .cms_websitesuche {
	margin-right: 10px;
	position: relative;
	width: 100%;
}

#cms_mobilmenue_i .cms_websitesuche input {
	border: 1px solid @h_haupt_abstufung2!important;
}

.cms_websitesuche input {
	background-color: @h_haupt_hintergrund;
	padding: 0px;
	margin: 0px;
	width: 100%;
	padding: 2px 6px;
	border: 1px solid @h_haupt_abstufung1;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-bottom-left-radius: 0px;
	border-bottom-right-radius: 0px;
}

.cms_websitesuche input:hover {
	border: 1px solid @h_haupt_abstufung2;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse {
	border-top: 3px solid @h_kopfzeile_buttonhintergrundhover;
	padding: 7px;
	width: 100%;
	background-color: @h_haupt_abstufung1;
	border-top-left-radius: 0px;
	border-top-right-radius: 0px;
	border-bottom-left-radius: 3px;
	border-bottom-right-radius: 3px;
	max-height: 400px;
	overflow-y: scroll;
	z-index: 5;
	text-align: left;
	display: none;
	min-height: 35px;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse {position: absolute;}
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse {
	position: relative;
	border-left: 1px solid @h_haupt_abstufung2;
	border-right: 1px solid @h_haupt_abstufung2;
	border-bottom: 1px solid @h_haupt_abstufung2;
	background-color: @h_haupt_abstufung1;
}

.cms_websitesuche_schliessen {
	position: absolute;
	right: 7px;
	top: 5px;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul {
	margin: 0px;
	padding: 0px;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li {
	margin: 0px 0px @haupt_absatzschulhof 0px;
	padding: 0px;
	display: block;
	width: 100%;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li:last-child,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li:last-child {margin-bottom: 0px;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a {
	display: block;
	padding: 5px;
	width: 100%;
	background: @h_haupt_abstufung1;
	color: @h_haupt_schriftfarbepositiv;
	border-radius: @button_rundeecken;
	transition: 250ms ease-in-out;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover {
	background: @h_kopfzeile_suchehintergrundhover;
	color: @d_haupt_schriftfarbepositiv;
	transition: none;
}

.cms_websitesuche h3 {
	color: @h_haupt_abstufung2;
	padding: 0px 5px;
}
.cms_websitesuche .cms_notiz {
	line-height: 1.2;
	color: inherit !important;
	margin-bottom: 3px;
}

.cms_websitesuche p:last-child {margin:0px;}

.cms_optimierung_P .cms_auszeichnung,
.cms_optimierung_T .cms_auszeichnung {
	text-align: right;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_optimierung_H .cms_auszeichnung {
	text-align: center !important;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_auszeichnung li {
	padding: 0px;
	margin: @auszeichnung_aussenabstand;
	display: inline-block;
}

.cms_auszeichnung a {
	padding: 10px;
	display: block;
	margin: 0px;
	text-align: center;
	border-top-left-radius: @auszeichnung_radius;
	border-top-right-radius: @auszeichnung_radius;
	border-bottom-left-radius: @auszeichnung_radius;
	border-bottom-right-radius: @auszeichnung_radius;
	background: @h_auszeichnung_hintergrund;
}

.cms_auszeichnung p, .cms_auszeichnung b {
	line-height: 1.5em !important;
	font-size: 10px !important;
	transition: 250ms ease-in-out;
	color: @h_auszeichnung_schrift;
}

.cms_auszeichnung img {max-height: 150px;}

.cms_auszeichnung b {
	padding: 0px !important;
	margin: 0px !important;
}

.cms_auszeichnung a:hover {
	transform: translate(0px) !important;
	background: @h_auszeichnung_hintergrundhover;
	color: @h_auszeichnung_schrifthover;
}


.cms_datenschutz_einwilligungerteilt {background: @h_haupt_meldungerfolgakzent;}
.cms_datenschutz_einwilligungverweigert {background: @h_haupt_meldungfehlerakzent;}

.cms_datenschutz_einwilligungerteilt, .cms_datenschutz_einwilligungverweigert {
	color: @h_haupt_schriftfarbenegativ;
	padding: 3px 5px 2px 5px;
	border-radius: @button_rundeecken;
}

.cms_datenschutz_einwilligungerteilt:hover,
.cms_datenschutz_einwilligungverweigert:hover {
	cursor: pointer;
}

.cms_kontakt_visitenkarten {
	padding: 0px;
	margin-bottom: @haupt_absatzschulhof;
	display: flex;
	flex-wrap: wrap;
}

.cms_kontakt_visitenkarte {
	width: 25%;
	padding: 10px;
	list-style-type: none;
	margin: 0px;
	background: @h_haupt_abstufung1;
	display: inline-block;
	transition: 250ms ease-in-out;
	position: relative;
}

.cms_kontakt_visitenkarte:hover {
	background: @h_haupt_thema2;
	color: @h_haupt_schriftfarbenegativ;
	display: inline-block;
	transition: 250ms ease-in-out;
	cursor: pointer;
}

// DUNKEL;

.cms_termine_jahrueberischt_knoepfe_jahr {
	color: @d_haupt_abstufung2;
}

.cms_termine_jahrueberischt_knoepfe_vorher .cms_button,
.cms_termine_jahrueberischt_knoepfe_nachher .cms_button {
	color: @d_haupt_abstufung2!important;
}

.cms_termine_jahrueberischt_knoepfe_vorher .cms_button:hover,
.cms_termine_jahrueberischt_knoepfe_nachher .cms_button:hover {
	color: @d_haupt_schriftfarbenegativ!important;
}

.cms_termine_jahrueberischt_allemonate_namen {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_termine_jahrueberischt_monat {
	color: @d_haupt_abstufung2;
}

.cms_termine_jahrueberischt_monathoehe {
	background-color: @d_zeitdiagramm_balken;
}

.cms_termine_jahrueberischt_monathoehe.cms_jahresuebersicht_aktuell,
.cms_termine_jahrueberischt_monathoehe:hover {
	background-color: @d_zeitdiagramm_balkenhover;
}

.cms_download_anzeige,
.cms_artikellink_anzeige {
	background-color: @d_haupt_abstufung1;
	border: 1px solid @d_haupt_abstufung1;
	border-left: 3px solid @d_haupt_abstufung2;
}

.cms_download_anzeige:hover,
.cms_artikellink_anzeige:hover {
	background-color: @d_haupt_abstufung2;
}

.cms_download_anzeige:hover p, .cms_download_anzeige:hover h4 {color: @d_haupt_schriftfarbenegativ;}
.cms_artikellink_anzeige:hover p, .cms_artikellink_anzeige:hover h4 {color: @d_haupt_schriftfarbenegativ;}

.cms_website_menuepunkte_ja {
	background: @d_haupt_meldungerfolghinter;
	border-top: 1px solid @d_haupt_abstufung1;
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_website_menuepunkte_ja:hover {
	background: @d_haupt_meldungerfolgakzent;
	color: @d_haupt_schriftfarbenegativ;
}

.cms_zugehoerig {
	background: @d_haupt_abstufung1;
}

.cms_zugehoerig h4 {color: @d_haupt_abstufung2;}
.cms_zugehoerig li a {
	background: @d_haupt_abstufung1;
	color: @d_haupt_schriftfarbepositiv;
}

.cms_zugehoerig li a:hover {
	background: @d_zugehoerig_hintergrundhover;
	color: @d_zugehoerig_farbehover;
}

.cms_box_u, .cms_box_n {
	background-color: @d_haupt_hintergrund;
}

.cms_box_u .cms_box_titel {
	border-right: 1px solid @d_haupt_schriftfarbepositiv;
}

.cms_box_n .cms_box_titel {
	border-bottom: 1px solid @d_haupt_schriftfarbepositiv;
}

.cms_box_1 .cms_box_titel, .cms_box_1 .cms_box_inhalt {background-color: @d_haupt_hintergrund;}
.cms_box_2 .cms_box_titel, .cms_box_3 .cms_box_titel,
.cms_box_4 .cms_box_titel, .cms_box_5 .cms_box_titel
{background-color: @d_haupt_abstufung1;}

.cms_box_2 .cms_box_inhalt, .cms_box_2 {background-color: @d_haupt_hintergrund;}

.cms_box_3 .cms_box_inhalt *, .cms_box_4 .cms_box_inhalt *,
.cms_box_5 .cms_box_inhalt *, .cms_box_3 .cms_box_inhalt .cms_button:hover,
.cms_box_4 .cms_box_inhalt .cms_button:hover, .cms_box_5 .cms_box_inhalt .cms_button:hover
{color: @d_haupt_schriftfarbenegativ;}

.cms_box_3 .cms_box_inhalt .cms_button, .cms_box_4 .cms_box_inhalt .cms_button,
.cms_box_5 .cms_box_inhalt .cms_button, .cms_box_3 .cms_box_inhalt .note-editor *,
.cms_box_4 .cms_box_inhalt .note-editor *, .cms_box_5 .cms_box_inhalt .note-editor *
{color: @d_haupt_schriftfarbepositiv!important;}

#cms_mobilmenue_i .cms_websitesuche input {
	border: 1px solid @d_haupt_abstufung2!important;
}

.cms_websitesuche input {
	background-color: @d_haupt_hintergrund;
	border: 1px solid @d_haupt_abstufung1;
}

.cms_websitesuche input:hover {
	border: 1px solid @d_haupt_abstufung2;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse {
	border-top: 3px solid @d_kopfzeile_buttonhintergrundhover;
	background-color: @d_haupt_abstufung1;
}

.cms_websitesuche #cms_websitesuche_mobil_ergebnisse {
	border-left: 1px solid @d_haupt_abstufung2;
	border-right: 1px solid @d_haupt_abstufung2;
	border-bottom: 1px solid @d_haupt_abstufung2;
	background-color: @d_haupt_abstufung1;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a {
	background: @d_haupt_abstufung1;
	color: @d_haupt_schriftfarbepositiv;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover {
	background: @d_kopfzeile_suchehintergrundhover;
}

.cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover p,
.cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover p {
	color: @d_haupt_schriftfarbenegativ !important;
}

.cms_websitesuche h3 {
	color: @d_haupt_abstufung2;
}

.cms_auszeichnung a {
	background: @d_auszeichnung_hintergrund;
}

.cms_auszeichnung a:hover {
	background: @d_auszeichnung_hintergrundhover;
	color: @d_auszeichnung_schrifthover;
}

.cms_datenschutz_einwilligungerteilt {background: @d_haupt_meldungerfolgakzent;}
.cms_datenschutz_einwilligungverweigert {background: @d_haupt_meldungfehlerakzent;}

.cms_datenschutz_einwilligungerteilt, .cms_datenschutz_einwilligungverweigert {
	color: @d_haupt_schriftfarbenegativ;
}

.cms_kontakt_visitenkarte {
	background: @d_haupt_abstufung1;
}

.cms_kontakt_visitenkarte:hover {
	background: @d_haupt_thema2;
	color: @d_haupt_schriftfarbenegativ;
}

// DRUCKEN;

.cms_download_anzeige,
.cms_artikellink_anzeige {
	padding: 5px 5px 5px 42px;
	background-position: 5px 5px;
	min-height: 44px;
	background-repeat: no-repeat;
	border-left: 3px solid @h_haupt_abstufung2;
	line-height: 1em !important;
}

.cms_download_anzeige p, .cms_download_anzeige h4,
.cms_artikellink_anzeige p, .cms_artikellink_anzeige h4 {
	overflow:hidden;
	text-overflow: ellipsis;
}

.cms_download_inaktiv {opacity: .5;}

.cms_artikellink_anzeige {
	color: inherit;
	display: block;
	width: 100%;
}
