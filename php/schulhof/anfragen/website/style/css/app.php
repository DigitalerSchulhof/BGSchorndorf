// HELL;

body.cms_seite_app {
	background: @h_haupt_hintergrund !important;
}

.cms_seite_app #cms_kopfzeile_i {
	padding: 10px;
	position: relative;
}

.cms_seite_app #cms_logo {
	position: static;
	display: inline-block;
	text-align: left;
}

#cms_logo_bild {
	float: left;
	padding-right: 10px;
	width: @logo_breite;
}

#cms_logo_schrift {
	float: left;
	display: @logo_anzeige !important;
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

#cms_appnavigation {
	border-radius: 5px;
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
	top: 10px;
}

#cms_appzurueck {
	display: inline-block;
	position: absolute;
	right: 10px;
	bottom: 10px;
}

#cms_appnavigation:hover {
	cursor: pointer !important;
	background-color: @h_mobilnavigation_iconhintergrundhover !important;
}

#cms_appmenue_a {
	border-left: 10px solid @h_haupt_abstufung2;
	display: none;
	padding: 10px 10px 20px 10px;
	position: fixed;
	right: 0px;
	top: 0px;
	width: 80%;
	height: 100%;
	background: @h_haupt_abstufung1;
	z-index: 10;
	box-shadow: 0px 0px 10px @h_haupt_abstufung2;
}

#cms_appmenue_a .cms_uebersicht li {
	border-bottom: 1px solid @h_haupt_hintergrund;
}

#cms_appmenue_a .cms_uebersicht li:first-child {
	border-top: 1px solid @h_haupt_hintergrund;
}

#cms_appmenue_schliessen {
	position: fixed;
	width: 20%;
	height: 100px;
	line-height: 100px;
	text-align: center;
	left: 0px;
	top: 0px;
	background: @h_haupt_abstufung2;
	font-size: 500%;
	font-weight: bold;
	color: @h_haupt_schriftfarbenegativ;
	transition: 250ms ease-in-out;
	box-shadow: 0px 0px 10px @h_haupt_abstufung2;
}

#cms_appmenue_schliessen:hover {
	cursor: pointer;
	background: @h_haupt_meldungfehlerhinter;
}

.cms_appmenue_uliste {
	display: inline-block !important;
	padding: 3px 0px 3px 0px !important;
	margin: 0px 15px 0px 0px !important;
	border: none !important;
	min-height: auto !important;
	background: none !important;
}

.cms_appmenue_uliste:hover {background: none !important;}

#cms_app_impressum {
	text-align: right;
	padding: 20px 0px 0px 15%;
	font-size: 70%;
	color: @h_haupt_abstufung2;
}

// DUNKEL;

body.cms_seite_app {
	background: @d_haupt_hintergrund !important;
}

.cms_seite_app #cms_logo #cms_logo_schrift #cms_logo_o,
.cms_seite_app #cms_logo #cms_logo_schrift #cms_logo_u {
	color: @d_logo_schriftfarbe;
}

#cms_appnavigation {
	background-color: @d_mobilnavigation_iconhintergrund !important;
	color: @d_haupt_schriftfarbenegativ !important;
}

#cms_appnavigation:hover {
	background-color: @d_mobilnavigation_iconhintergrundhover !important;
}

#cms_appmenue_a {
	border-left: 10px solid @d_haupt_abstufung2;
	background: @d_haupt_abstufung1;
	box-shadow: 0px 0px 10px @d_haupt_abstufung2;
}

#cms_appmenue_a .cms_uebersicht li {
	border-bottom: 1px solid @d_haupt_hintergrund;
}

#cms_appmenue_a .cms_uebersicht li:first-child {
	border-top: 1px solid @d_haupt_hintergrund;
}

#cms_appmenue_schliessen {
	background: @d_haupt_abstufung2;
	color: @d_haupt_schriftfarbenegativ;
	box-shadow: 0px 0px 10px @d_haupt_abstufung2;
}

#cms_appmenue_schliessen:hover {
	background: @h_haupt_meldungfehlerakzent;
}

#cms_app_impressum {
	color: @d_haupt_abstufung2;
}
