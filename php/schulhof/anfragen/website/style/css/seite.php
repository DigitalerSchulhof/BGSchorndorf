// HELL;

* {
	font-family: @haupt_schriftart, sans-serif;
	font-size: @haupt_schriftgroesse;
	color: @h_haupt_schriftfarbepositiv;
	font-weight: normal;
	padding: 0px;
	margin: 0px;
	list-style-type: none;
	line-height: 1.2em;
	text-decoration: none;
	box-sizing: border-box;
}

body {
	background: @h_haupt_koerperhintergrund;
}

.cms_seite_normal #cms_kopfzeile_o {
	margin-top: @kopfzeile_aussenabstand;
	background: @h_kopfzeile_hintergrund;
	position: @kopfzeile_positionierung;
	top: @kopfzeile_abstandvonoben;
	border-bottom: @kopfzeile_linienstaerkeunten solid @h_kopfzeile_schattenfarbe;
	left: 0px;
	width: 100%;
	z-index: 20;
	box-shadow: @kopfzeile_schattenausmasse @h_kopfzeile_schattenfarbe;
}

#cms_platzhalter_bild {
	margin-top: @kopfzeile_platzhalter;
	height: 0px;
	width: 100%;
}

#cms_website_bearbeiten_o {
	background: @h_haupt_meldungwarnunghinter;
}

#cms_hauptbild_o {
	background: @h_haupt_koerperhintergrund;
	margin: 0px;
	padding: 0px;
	position: relative;
}

#cms_hauptteil_o {
	background: @h_haupt_hintergrund;
}

#cms_fusszeile_o {
	border-top: @fusszeile_linienstaerkeoben solid @h_kopfzeile_schattenfarbe;;
	background: @h_fusszeile_hintergrund;
}

.cms_seite_normal #cms_kopfzeile_i {
	padding: 10px 10px 0px 10px;
	height: @kopfzeile_hoehe;
	position: relative;
}

#cms_website_bearbeiten_i {
	padding: 0px 0px 0px 0px;
	position: relative;
}

#cms_hauptteil_i {
	padding: 10px 0px 10px 0px;
	position: relative;
}

#cms_fusszeile_i {
	padding: 10px 10px 30px 10px;
	position: relative;
}

.cms_seite_normal #cms_logo {
	position: absolute;
	left: 10px;
	top: 10px;
	display: inline-block;
}

#cms_logo_bild {
	float: left;
	padding-right: 10px;
	width: @logo_breite;
}

#cms_logo_schrift {
	float: left;
	display: @logo_anzeige;
}

#cms_logo_o, #cms_logo_u {
	position: relative;
	color: @h_logo_schriftfarbe;
	font-size: 170%;
	padding: 2px 0px 0px 0px;
	display: block;
}

#cms_logo_o {
	font-weight: bold;
}

#cms_netzcheckstatus {
	position: fixed;
	left: 0px;
	bottom: 0px;
	width: 100%;
	font-size: 10px;
	font-family: 'robl', sans-serif;
	padding: 2px 5px;
	text-align: center;
	z-index: 5000;
	background: @h_haupt_meldunginfohinter;
}

.cms_netzcheckstatus_lehrer {
	background: @h_haupt_meldungwarnunghinter !important;
}

.cms_spalte_i {
	padding: 10px;
}

.cms_spalte_icon {
	padding-right: 52px;
	min-height: 32px;
	position: relative;
}

.cms_clear {
	clear: both;
}

.cms_bild {
	text-align: center;
}

.cms_bild img {
	max-width: 100%;
}

.cms_vollbild {
	background: @h_haupt_hintergrund;
	position: relative;
}

.cms_vollbild .cms_rechtsbuendig {
	position: fixed;
	right: 10px;
	top: 10px;
	z-index: 2;
}

.cms_vollbild .cms_vollbild_innen > .cms_spalte_i:first-child {
	padding: 0px;
}

.cms_button_vollbild_schliessen, .cms_button_vollbild_oeffnen {
	position: absolute !important;
	top: 10px;
	right: 10px;
}

.cms_button_vollbild_schliessen {
	display: none;
}

// COMPUTER & TABLET
.cms_spalte_2, .cms_spalte_23, .cms_spalte_25, .cms_spalte_15, .cms_spalte_3, .cms_spalte_6,
.cms_spalte_60, .cms_spalte_40, .cms_spalte_20, .cms_spalte_4, .cms_spalte_34, .cms_spalte_45 {
	position: relative;
}

.cms_optimierung_P .cms_spalte_2,
.cms_optimierung_T .cms_spalte_2 {
	float: left;
	width: 50%;
}
.cms_optimierung_P .cms_spalte_23,
.cms_optimierung_T .cms_spalte_23 {
	float: left;
	width: 66.66%;
}
.cms_optimierung_P .cms_spalte_25,
.cms_optimierung_T .cms_spalte_25 {
	float: left;
	width: 40%;
}
.cms_optimierung_P .cms_spalte_15,
.cms_optimierung_T .cms_spalte_15 {
	float: left;
	width: 20%;
}

.cms_optimierung_P .cms_spalte_3,
.cms_optimierung_T .cms_spalte_3 {
	float: left;
	width: 33.33%;
}

.cms_optimierung_P .cms_spalte_6,
.cms_optimierung_T .cms_spalte_6 {
	float: left;
	width: 66.66%;
}

.cms_optimierung_P .cms_spalte_60,
.cms_optimierung_T .cms_spalte_60 {
	float: left;
	width: 60%;
}

.cms_optimierung_P .cms_spalte_40,
.cms_optimierung_T .cms_spalte_40 {
	float: left;
	width: 40%;
}

.cms_optimierung_P .cms_spalte_20,
.cms_optimierung_T .cms_spalte_20 {
	float: left;
	width: 20%;
}

.cms_optimierung_P .cms_spalte_4,
.cms_optimierung_T .cms_spalte_4 {
	float: left;
	width: 25%;
}

.cms_optimierung_P .cms_spalte_34,
.cms_optimierung_T .cms_spalte_34 {
	float: left;
	width: 75%;
}

.cms_optimierung_P .cms_spalte_45,
.cms_optimierung_T .cms_spalte_45 {
	float: left;
	width: 80%;
}

// COMPUTER
.cms_optimierung_P.cms_seite_normal #cms_kopfzeile_m,
.cms_optimierung_P.cms_seite_normal #cms_hauptteil_m,
.cms_optimierung_P.cms_seite_normal #cms_website_bearbeiten_m,
.cms_optimierung_P.cms_seite_normal #cms_fusszeile_m {
	width: @haupt_seitenbreite;
	margin: 0px auto;
}

// TABLET & HANDY
.cms_optimierung_H.cms_seite_normal #cms_kopfzeile_m,
.cms_optimierung_H.cms_seite_normal #cms_hauptteil_m,
.cms_optimierung_H.cms_seite_normal #cms_website_bearbeiten_m,
.cms_optimierung_H.cms_seite_normal #cms_fusszeile_m,
.cms_optimierung_T.cms_seite_normal #cms_kopfzeile_m,
.cms_optimierung_T.cms_seite_normal #cms_hauptteil_m,
.cms_optimierung_T.cms_seite_normal #cms_website_bearbeiten_m,
.cms_optimierung_T.cms_seite_normal #cms_fusszeile_m {
	width: 100%;
	max-width: @haupt_seitenbreite !important;
	margin: 0px auto;
}

// HANDY
.cms_optimierung_H.cms_seite_normal .cms_spalte_2,
.cms_optimierung_H.cms_seite_normal .cms_spalte_3,
.cms_optimierung_H.cms_seite_normal .cms_spalte_60,
.cms_optimierung_H.cms_seite_normal .cms_spalte_40,
.cms_optimierung_H.cms_seite_normal .cms_spalte_4,
.cms_optimierung_H.cms_seite_normal .cms_spalte_34,
.cms_optimierung_H .cms_seite_normal .cms_spalte_45,
.cms_optimierung_H.cms_seite_normal .cms_spalte_6 {
	float: none;
	width: 100%;
}

.cms_groesseaendern {
	right: 0px;
	position: absolute;
	height: 100% !important;
	width: 4px;
	transition: 250ms ease-in-out;
	z-index: 1;
}

.cms_groesseaendern:hover {
	background-color: @h_haupt_abstufung2;
}

.cms_groesseaendern:hover {cursor: ew-resize;}

textarea {
	transition: 0s;
}

// DUNKEL;

* {
	color: @d_haupt_schriftfarbepositiv;
}

body {
	background: @d_fusszeile_hintergrund;
}

.cms_seite_normal #cms_kopfzeile_o {
	background: @d_kopfzeile_hintergrund;
	box-shadow: @kopfzeile_schattenausmasse @d_kopfzeile_schattenfarbe;
}

#cms_website_bearbeiten_o {
	background: @d_haupt_meldungwarnunghinter;
}

#cms_hauptbild_o {
	background: @d_haupt_koerperhintergrund;
}

#cms_hauptteil_o {
	background: @d_haupt_hintergrund;
}

#cms_fusszeile_o {
	background: @d_fusszeile_hintergrund;
}

.cms_seite_normal #cms_logo #cms_logo_schrift #cms_logo_o,
.cms_seite_normal #cms_logo #cms_logo_schrift #cms_logo_u {
	color: @d_logo_schriftfarbe;
}

#cms_netzcheckstatus {
	background: @d_haupt_meldunginfohinter;
}

.cms_netzcheckstatus_lehrer {
	background: @d_haupt_meldungwarnunghinter !important;
}

.cms_vollbild {
	background: @d_haupt_hintergrund;
}

.cms_groesseaendern:hover {
	background-color: @d_haupt_abstufung2;
}

// DRUCKEN;

.cms_spalte_i {
	padding: 10px;
}

.cms_spalte_2, .cms_spalte_23, .cms_spalte_25, .cms_spalte_15, .cms_spalte_3, .cms_spalte_6,
.cms_spalte_60, .cms_spalte_40, .cms_spalte_20, .cms_spalte_4, .cms_spalte_34 {
	padding: 0;
	position: relative;
}

.cms_spalte_2 {
	float: left;
	width: 50%;
}
.cms_spalte_23 {
	float: left;
	width: 66.66%;
}
.cms_spalte_25 {
	float: left;
	width: 40%;
}
.cms_spalte_15 {
	float: left;
	width: 20%;
}

.cms_spalte_3 {
	float: left;
	width: 33.33%;
}

.cms_spalte_6 {
	float: left;
	width: 66.66%;
}

.cms_spalte_60 {
	float: left;
	width: 60%;
}

.cms_spalte_40 {
	float: left;
	width: 40%;
}

.cms_spalte_20 {
	float: left;
	width: 20%;
}

.cms_spalte_4 {
	float: left;
	width: 25%;
}

.cms_spalte_34 {
	float: left;
	width: 75%;
}
