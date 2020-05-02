// HELL;

// NORMALE BUTTONS
.cms_button, .cms_button_ja, .cms_button_nein, .cms_button_wichtig,
.cms_toggle, .cms_toggle_aktiv, .cms_toggle_inaktiv, .cms_iconbutton, .cms_iconbutton_ja,
.cms_iconbutton_nein, .cms_button_passiv, .cms_button_passivda, .cms_button_gesichert,
.cms_iconbutton_gesichert, .cms_toggle_aktiv_fest, .cms_datentypwahl,
.cms_fussnavigation a, .cms_fussnavigation span {
	border: 1px solid transparent;
	display: inline-block;
	border-radius: @button_rundeecken;
	padding: 3px 7px;
	margin-bottom: 2px;
	transition: 250ms ease-in-out;
	position: relative;
	line-height: 1.5em;
	text-align: center;
}

.cms_konfliktstunde {font-size: 75%;}

.cms_button:hover, .cms_button_ja:hover, .cms_button_nein:hover, .cms_button_wichtig:hover,
.cms_iconbutton, .cms_iconbutton_ja,
.cms_iconbutton_nein, .cms_aktion_klein:hover, .cms_datentypwahl:hover,
.cms_fussnavigation a:hover, .cms_fussnavigation span:hover {
	transform: translate(0px) !important;
	cursor: pointer;
}

.cms_toggle:hover, .cms_toggle_inaktiv:hover {
	transform: translate(0px) !important;
	cursor: pointer;
	background-color: @h_haupt_meldungerfolgakzent;
	color: @h_haupt_schriftfarbenegativ !important;
}

.cms_toggle_aktiv:hover {
	transform: translate(0px) !important;
	cursor: pointer;
	background-color: @h_haupt_meldungfehlerakzent !important;
	color: @h_haupt_schriftfarbenegativ !important;
}

.cms_button, .cms_iconbutton, .cms_aktion_klein, .cms_datentypwahl, .cms_toggle,
.cms_toggle_aktiv, .cms_toggle_inaktiv {
	background-color: @h_button_hintergrund;
	border: 1px solid @h_haupt_hintergrund;
	color: @h_button_schrift;
}

.cms_button:hover, .cms_iconbutton:hover, .cms_aktion_klein:hover, .cms_datentypwahl:hover {
	background-color: @h_button_hintergrundhover;
	border: 1px solid transparent;
	color: @h_button_schrifthover;
}

.cms_unternavigation_i .cms_button {
	background-color: @h_haupt_hintergrund !important;
	color: @h_button_schrift !important;
}

.cms_unternavigation_i .cms_button:hover {
	background-color: @h_haupt_abstufung2 !important;
	color: @h_button_schrifthover !important;
}

.cms_button_ja, .cms_aktion_ja, .cms_iconbutton_ja, .cms_ja {
	background-color: @h_haupt_meldungerfolghinter !important;
	color: @h_button_schrift !important;
}

.cms_button_ja:hover, .cms_aktion_ja:hover, .cms_iconbutton_ja:hover, .cms_ja:hover {
	background-color: @h_haupt_meldungerfolgakzent !important;
	color: @h_button_schrifthover !important;
}

.cms_button_nein, .cms_aktion_nein, .cms_iconbutton_nein {
	background-color: @h_haupt_meldungfehlerhinter;
	color: @h_button_schrift;
}

.cms_button_nein:hover, .cms_aktion_nein:hover, .cms_iconbutton_nein:hover {
	background-color: @h_haupt_meldungfehlerakzent;
	color: @h_button_schrifthover;
}

.cms_button_wichtig, .cms_aktion_wichtig {
	background-color: @h_haupt_meldungwarnunghinter;
	color: @h_button_schrift;
}

.cms_button_wichtig:hover, .cms_aktion_wichtig:hover {
	background-color: @h_haupt_meldungwarnungakzent;
	color: @h_button_schrifthover;
}

.cms_toggle_aktiv_fest {
	background-color: @h_haupt_meldungerfolgakzent;
	color: @h_haupt_schriftfarbenegativ;
}

.cms_button_gesichert, .cms_iconbutton_gesichert {
	color: @h_haupt_abstufung2;
	border: 1px dashed @h_haupt_meldungfehlerhinter;
	background-color: @h_haupt_hintergrund;
}

.cms_button_passiv {
	color: @h_haupt_abstufung1;
	border: 1px solid @h_haupt_abstufung1;
	background-color: @h_haupt_hintergrund;
}

.cms_button_passivda {
	color: @d_haupt_schriftfarbenegativ;
	border: 1px solid @d_haupt_schriftfarbenegativ;
	background-color: @h_haupt_hintergrund;
}

.cms_button_gesichert:hover, .cms_iconbutton_gesichert:hover,
.cms_button_passiv:hover, .cms_button_passivda {
	cursor: default;
}

#cms_kopfzeile_i .cms_button {
	background-color: @h_kopfzeile_buttonhintergrund;
	color: @h_kopfzeile_buttonschrift;
}

#cms_kopfzeile_i .cms_button:hover, #cms_kopfzeile_i .cms_button_aktiv {
	background-color: @h_kopfzeile_buttonhintergrundhover;
	color: @h_kopfzeile_buttonschrifthover;
}

#cms_fusszeile_i .cms_button, .cms_fussnavigation a, .cms_fussnavigation span {
	background-color: @h_fusszeile_buttonhintergrund;
	color: @h_fusszeile_buttonschrift;
}

#cms_fusszeile_i li.cms_footer_feedback a {
	color: @h_haupt_meldungfehlerhinter;
}

#cms_fusszeile_i li.cms_footer_feedback a:hover {
	background-color: @h_fusszeile_buttonhintergrundhover;
	color: @h_fusszeile_buttonschrifthover;
}

#cms_fusszeile_i .cms_button:hover, .cms_fussnavigation a:hover,
.cms_fussnavigation span:hover {
	background-color: @h_fusszeile_buttonhintergrundhover;
	color: @h_fusszeile_buttonschrifthover;
}

// TOGGLES
.cms_toggle {
	background-color: @h_haupt_abstufung1;
}

.cms_toggle_aktiv {
	background-color: @h_haupt_meldungerfolghinter;
}

#cms_geraeteproblem .cms_toggle_aktiv {
	background-color: @h_haupt_meldungfehlerhinter;
}

#cms_geraeteproblem .cms_toggle_aktiv:hover {
	background-color: @h_haupt_meldungfehlerakzent;
	color: @h_button_schrifthover;
}

.cms_toggle:hover {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_toggle_aktiv:hover {
	background-color: @h_haupt_meldungerfolgakzent;
	color: @h_button_schrifthover;
}

// ICONBUTTONS
.cms_iconbutton, .cms_iconbutton_nein, .cms_iconbutton_ja, .cms_iconbutton_gesichert {
	padding-top: 38px;
	background-position: center 3px;
	background-repeat: no-repeat;
	text-align: center;
}

// KLEINE BUTTONS
.cms_aktion_klein {
	padding: 2px 2px 3px 3px;
	border-radius: @button_rundeecken;
	display: inline-block;
	line-height: 0px;
	position: relative;
	overflow: visible;
	margin-bottom: 2px;
	transition: 250ms ease-in-out;
}

td>.cms_aktion_klein {
	margin-bottom: 0;
}

// FARBBEISPIELE
.cms_farbbeispiel, .cms_farbbeispiel_aktiv {
	cursor: pointer;
	border-radius: 11px;
	border: 2px solid transparent;
	display: inline-block;
	width: 18px;
	height: 18px;
	margin: 1px 5px 1px 1px;
	transition: 250ms;
}

.cms_farbbeispiel:hover {
	width: 20px;
	height: 20px;
	margin: 0 4px 0 0;
	border-radius: 8px;
}

.cms_farbbeispiel_aktiv {
	width: 20px;
	height: 20px;
	margin: 0 4px 0 0;
	border: 2px solid @h_haupt_hintergrund;
}

// ICONAUSWAHL
.cms_kategorie_icon_aktiv {
	border: 2px solid @h_haupt_hintergrund;
	background-color: @h_haupt_hintergrund;
}

.cms_kategorie_icon {
	border: 2px solid @h_haupt_abstufung1;
}

.cms_kategorie_icon.cms_icon_verwendet {
	background-color: @h_haupt_abstufung2;
}

.cms_kategorie_icon_aktiv:hover, .cms_kategorie_icon:hover {
	border: 2px solid @d_haupt_schriftfarbenegativ;
	cursor: pointer;
}

.cms_kategorie_icon_aktiv, .cms_kategorie_icon {
	border-radius: 5px;
	display: inline-block;
	padding: 3px 2px 2px 2px;
	margin-right: 5px;
	line-height: 0px;
	transition: 250ms;
}

.cms_kategorie_icon_aktiv img, .cms_kategorie_icon img {
	display: block;
}

.cms_meldezahl {
	opacity: .5;
	position: relative;
	bottom: 1px;
	padding: 2px 5px;
	border-radius: 10px;
	background: @h_haupt_abstufung2;
	color: @h_haupt_schriftfarbenegativ;
	font-size: 70%;
	transition: 250ms ease-in-out;
	display: inline-block;
	margin-left: 5px;
}

.cms_meldezahl_wichtig {
	background: @h_haupt_meldungfehlerakzent;
}

.cms_button:hover .cms_meldezahl {
	opacity: 1;
}

.cms_datentypwahl p:first-child {
	text-align: center;
}

.cms_button_schliessen {
	position: absolute;
	top: -15px;
	right: 0px;
	font-weight: bold;
}

h1+.cms_toggleeinblenden,
h2+.cms_toggleeinblenden,
h3+.cms_toggleeinblenden,
h4+.cms_toggleeinblenden,
h5+.cms_toggleeinblenden,
h6+.cms_toggleeinblenden {
	margin-top: 0px !important;
}

// DUNKEL;

.cms_toggle:hover, .cms_toggle_inaktiv:hover {
	background-color: @d_haupt_meldungerfolgakzent;
	color: @d_haupt_schriftfarbenegativ !important;
}

.cms_toggle_aktiv:hover {
	background-color: @d_haupt_meldungfehlerakzent !important;
	color: @d_haupt_schriftfarbenegativ !important;
}

.cms_button, .cms_iconbutton, .cms_aktion_klein, .cms_datentypwahl, .cms_toggle,
.cms_toggle_aktiv, .cms_toggle_inaktiv {
	background-color: @d_button_hintergrund;
	border: 1px solid @d_haupt_hintergrund;
	color: @d_button_schrift;
}

.cms_button:hover, .cms_iconbutton:hover, .cms_aktion_klein:hover, .cms_datentypwahl:hover {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_unternavigation_i .cms_button {
	background-color: @d_haupt_hintergrund !important;
	color: @d_button_schrift !important;
}

.cms_unternavigation_i .cms_button:hover {
	background-color: @d_haupt_abstufung2 !important;
	color: @d_button_schrifthover !important;
}

.cms_button_ja, .cms_aktion_ja, .cms_iconbutton_ja, .cms_ja {
	background-color: @d_haupt_meldungerfolghinter !important;
	color: @d_button_schrift !important;
}

.cms_button_ja:hover, .cms_aktion_ja:hover, .cms_iconbutton_ja:hover, .cms_ja:hover {
	background-color: @d_haupt_meldungerfolgakzent !important;
	color: @d_button_schrifthover !important;
}

.cms_button_nein, .cms_aktion_nein, .cms_iconbutton_nein {
	background-color: @d_haupt_meldungfehlerhinter;
	color: @d_button_schrift;
}

.cms_button_nein:hover, .cms_aktion_nein:hover, .cms_iconbutton_nein:hover {
	background-color: @d_haupt_meldungfehlerakzent;
	color: @d_button_schrifthover;
}

.cms_button_wichtig, .cms_aktion_wichtig {
	background-color: @d_haupt_meldungwarnunghinter;
	color: @d_button_schrift;
}

.cms_button_wichtig:hover, .cms_aktion_wichtig:hover {
	background-color: @d_haupt_meldungwarnungakzent;
	color: @d_button_schrifthover;
}

.cms_toggle_aktiv_fest {
	background-color: @d_haupt_meldungerfolgakzent;
	color: @d_haupt_schriftfarbenegativ;
}

.cms_button_gesichert, .cms_iconbutton_gesichert {
	color: @d_haupt_abstufung2;
	border: 1px dashed @d_haupt_meldungfehlerhinter;
	background-color: @d_haupt_hintergrund;
}

.cms_button_passiv {
	color: @d_haupt_abstufung1;
	border: 1px solid @d_haupt_abstufung1;
	background-color: @d_haupt_hintergrund;
}

.cms_button_passivda {
	color: @d_haupt_schriftfarbepositiv;
	border: 1px solid @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_hintergrund;
}

#cms_kopfzeile_i .cms_button {
	background-color: @d_kopfzeile_buttonhintergrund;
	color: @d_kopfzeile_buttonschrift;
}

#cms_kopfzeile_i .cms_button:hover, #cms_kopfzeile_i .cms_button_aktiv {
	background-color: @d_kopfzeile_buttonhintergrundhover;
	color: @d_kopfzeile_buttonschrifthover;
}

#cms_fusszeile_i .cms_button, .cms_fussnavigation a, .cms_fussnavigation span {
	background-color: @d_fusszeile_buttonhintergrund;
	color: @d_fusszeile_buttonschrift;
}

#cms_fusszeile_i li.cms_footer_feedback a {
	color: @d_haupt_meldungfehlerhinter;
}

#cms_fusszeile_i li.cms_footer_feedback a:hover {
	background-color: @d_fusszeile_buttonhintergrundhover;
	color: @d_fusszeile_buttonschrifthover;
}

#cms_fusszeile_i .cms_button:hover, .cms_fussnavigation a:hover,
.cms_fussnavigation span:hover {
	background-color: @d_fusszeile_buttonhintergrundhover;
	color: @d_fusszeile_buttonschrifthover;
}

// TOGGLES
.cms_toggle {
	background-color: @d_haupt_abstufung1;
}

.cms_toggle_aktiv {
	background-color: @d_haupt_meldungerfolghinter;
}

#cms_geraeteproblem .cms_toggle_aktiv {
	background-color: @d_haupt_meldungfehlerhinter;
}

#cms_geraeteproblem .cms_toggle_aktiv:hover {
	background-color: @d_haupt_meldungfehlerakzent;
	color: @d_button_schrifthover;
}

.cms_toggle:hover {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_toggle_aktiv:hover {
	background-color: @d_haupt_meldungerfolgakzent;
	color: @d_button_schrifthover;
}

// FARBBEISPIELE
.cms_farbbeispiel, .cms_farbbeispiel_aktiv {
	border: 2px solid @d_haupt_hintergrund;
}

/* ICONAUSWAHL */
.cms_kategorie_icon_aktiv {
	border: 2px solid @d_haupt_hintergrund;
	background-color: @d_haupt_hintergrund;
}

.cms_kategorie_icon {
	border: 2px solid @d_haupt_abstufung1;
}

.cms_kategorie_icon.cms_icon_verwendet {
	background-color: @d_haupt_abstufung2;
}

.cms_kategorie_icon_aktiv:hover, .cms_kategorie_icon:hover {
	border: 2px solid @d_haupt_schriftfarbepositiv;
	cursor: pointer;
}

.cms_meldezahl {
	background: @d_haupt_abstufung2;
	color: @d_haupt_schriftfarbenegativ;
}

.cms_meldezahl_wichtig {
	background: @d_haupt_meldungfehlerakzent;
}
