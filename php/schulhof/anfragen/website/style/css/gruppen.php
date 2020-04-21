// HELL;

.cms_uebersicht .cms_ersteller {
	color: @h_haupt_abstufung2;
	display: none;
}

.cms_uebersicht .cms_blogeintrag:hover .cms_ersteller,
.cms_uebersicht tr:hover .cms_ersteller  {
	display: block;
}

.cms_blogeintrag, .cms_beschlusseintrag {
	padding-left: 5px !important;
	transition: 250ms ease-in-out;
	position: relative;
}

.cms_beschlusseintrag {
	padding-right: 26px !important;
	min-height: 45px;
}

.cms_blogeintrag:hover, .cms_beschlusseintrag:hover {
	cursor: pointer;
	background-color: @h_haupt_abstufung1;
}

.cms_blogliste_details:hover {
	cursor: pointer;
}

.cms_blogeintrag p.cms_inhaltvorschau, .cms_beschlusseintrag p.cms_inhaltvorschau {
	margin-bottom: 0px;
}

.cms_beschluss_angenommen {color: @h_haupt_meldungerfolgakzent;}
.cms_beschluss_abgelehnt {color: @h_haupt_meldungfehlerakzent;}
.cms_beschluss_vertagt {color: @h_haupt_abstufung2;}

.cms_beschlusseintrag p.cms_beschlussicons {
	margin: 0px;
	position: absolute;
	top: 5px;
	right: 5px;
	width: 16px;
}

.cms_beschluss_icon {
	display: inline-block;
	margin-bottom: 5px;
	position: relative;
}

.cms_beschluss_icon:last-child {
	margin-bottom: 0px;
}

.cms_aktionen_uebersicht li > p {
	padding: 4px 5px 4px 5px;
	margin: 0px;
}

.cms_aktionen_uebersicht li .cms_beschlusseintrag {
	border-bottom: none !important;
}

.cms_aktionen_uebersicht li:last-child > p {
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.cms_gruppen_oeffentlich_art {
	padding: 0px;
	width: 16px;
	height: 16px;
	position: absolute;
	right: 5px;
	top: 5px;
	display: inline-block;
}

.cms_oe, .cms_in {
	display: block;
	width: 16px;
	height: 16px;
	padding: 0px;
	margin: 0px;
	border-radius: 8px;
}

.cms_oe {background: @h_haupt_meldunginfohinter;}
.cms_in {background: @h_haupt_meldungwarnunghinter;}

.cms_beschluss {
	color: @h_haupt_schriftfarbepositiv;
	padding: 5px;
	margin-bottom: @haupt_absatzschulhof;
	display: block;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_beschluss h4, .cms_beschluss p {
	display: block;
	width: 100%;
}

.cms_beschluss:hover {
	color: @h_haupt_schriftfarbepositiv;
	display: block;
}

.cms_beschluss_pro {
	border-left: 3px solid @h_haupt_meldungerfolgakzent;
	background: @h_haupt_meldungerfolghinter;
}

.cms_beschluss_contra {
	border-left: 3px solid @h_haupt_meldungfehlerakzent;
	background: @h_haupt_meldungfehlerhinter;
}

.cms_beschluss_enthaltung {
	border-left: 3px solid @h_haupt_abstufung2;
	background: @h_haupt_abstufung1;
}

.cms_beschluss_stimmen {
	font-size: 80%;
	margin-bottom: 0px;
}

.cms_beschluss_stimmen_pro {background: @h_haupt_meldungerfolgakzent;}
.cms_beschluss_stimmen_contra {background: @h_haupt_meldungfehlerakzent;}
.cms_beschluss_stimmen_enthaltung {background: @h_haupt_abstufung2;}

.cms_beschluss_stimmen_pro, .cms_beschluss_stimmen_contra, .cms_beschluss_stimmen_enthaltung, .cms_beschluss_langfristig {
	display: inline-block;
	padding: 2px 7px;
	font-weight: bold;
	color: @h_haupt_schriftfarbenegativ;
	text-align: center;
	min-width: 25px;
}

.cms_beschluss_stimmen_pro {
	border-top-left-radius: 7px;
	border-bottom-left-radius: 7px;
}

.cms_beschluss_stimmen_contra {
	border-top-right-radius: 7px;
	border-bottom-right-radius: 7px;
}

.cms_beschluss_langfristig {
	margin-left: 10px;
	border-radius: 7px;
	color: @h_haupt_schriftfarbepositiv;
	background: @h_haupt_hintergrund;
}

.cms_beschlussuebersicht_jahr {
	display: flex;
}

.cms_beschlussuebersicht_jahr .cms_beschluss {
	display: flex;
	flex-wrap: wrap;
	width: 25%;
	border-right: 10px solid @h_haupt_hintergrund;
}

#cms_chat #cms_chat_nachrichten {
	width: 100%;
	padding: 5px 20px;
	max-height: 500px;
	overflow-y: auto
}

#cms_chat #cms_chat_nachrichten .cms_chat_datum {
	text-align: center;
	margin-bottom: 10px
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen {
	width: 100%;
	margin-bottom: 15px;
	float: left
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen {
	position: relative;
	min-width: 40%;
	max-width: 60%;
	background-color: @h_chat_gegenueber;
	border-radius: @haupt_radiusmittel;
	float: left;
	padding: 5px
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion {
	position: absolute;
	top: 0;
	right: 0;
	padding: inherit
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=sendend] {
	display: none
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] {
	cursor: pointer
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion {
color: @h_haupt_schriftfarbepositiv !important;
background: @h_hinweis_hintergrund;
	padding: 0 5px 0 5px;
	position: absolute;
	font-family: 'robl';
	font-weight: normal !important;
	display: none;
	border-radius: @hinweis_radius;
	z-index: 50;
	width: 150px;
	overflow: visible;
	left: 0;
	bottom: 25px;
	overflow: hidden;
	text-align: left;
	z-index: 5
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion p {
	padding: 5px
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion img {
	height: 16px;
	width: 16px
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_id {
	display: none
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_autor {
	font-size: 90%
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_nachricht {
	padding-left: 5px;
	font-size: 110%;
	white-space: pre-wrap;
	word-break: break-word
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_zeit {
	float: right
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen {
	float: right;
	background-color: @h_chat_eigen
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] {
	display: none
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion {
	text-align: right;
	left: unset;
	right: 0
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_sendend {
	opacity: .8
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_sendend .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=sendend] {
	display: block
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_sendend .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] {
	display: none
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet {
	opacity: .8
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet .cms_chat_nachricht_innen {
	background-color: @h_haupt_meldungfehlerhinter
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] [data-mehr=melden] {
	opacity: .7;
	cursor: default
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_geloescht {
	opacity: .7
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_geloescht .cms_chat_nachricht_innen .cms_chat_nachricht_aktion {
	display: none
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_geloescht .cms_chat_nachricht_innen .cms_chat_nachricht_nachricht {
	font-style: italic;
	font-size: 90%
}

#cms_chat #cms_chat_nachricht_verfassen {
	width: 100%
}

#cms_chat #cms_chat_nachricht_verfassen label {
	cursor: pointer
}

#cms_chat #cms_chat_nachricht_verfassen textarea {
	width: 90%;
	width: calc(100% - 26px)
}

#cms_chat #cms_chat_nachricht_verfassen .cms_meldung_fehler {
	display: none
}

#cms_chat #cms_chat_nachricht_verfassen div:not(.cms_meldung_fehler) {
	display: inline-block;
	float: right;
	width: auto
}

#cms_chat #cms_chat_nachricht_verfassen div:not(.cms_meldung_fehler) img {
	padding: 5px;
	cursor: pointer
}

#cms_chat #cms_chat_mehr {
	color: @h_link_schrift;
	cursor: pointer
}

#cms_chat #cms_chat_status,
#cms_chat #cms_chat_laden,
#cms_chat #cms_chat_leer,
#cms_chat #cms_chat_mehr {
	text-align: center;
	margin-top: 10px;
	margin-bottom: 20px
}

#cms_chat #cms_chat_status h3,
#cms_chat #cms_chat_laden h3,
#cms_chat #cms_chat_leer h3,
#cms_chat #cms_chat_mehr h3 {
	margin-top: 0
}

#cms_chat #cms_chat_status,
#cms_chat #cms_chat_laden,
#cms_chat #cms_chat_berechtigung,
#cms_chat #cms_chat_leer,
#cms_chat #cms_chat_mehr,
#cms_chat #cms_chat_stumm {
	display: none
}

#cms_chat.cms_chat_leer #cms_chat_leer {
	display: block
}

#cms_chat.cms_chat_mehr #cms_chat_mehr {
	display: block
}

#cms_chat.cms_chat_stumm>#cms_chat_nachricht_verfassen {
	display: none
}

#cms_chat.cms_chat_stumm #cms_chat_stumm {
	display: block
}

#cms_chat.cms_chat_status>* {
	display: none
}

#cms_chat.cms_chat_status #cms_chat_status {
	display: block
}

#cms_chat.cms_chat_laden #cms_chat_laden {
	display: block
}

#cms_chat.cms_chat_berechtigung>* {
	display: none
}

#cms_chat.cms_chat_berechtigung #cms_chat_berechtigung {
	display: block
}

// DUNKEL;

.cms_uebersicht .cms_ersteller {
	color: @d_haupt_abstufung2;
}

.cms_blogeintrag:hover, .cms_beschlusseintrag:hover {
	background-color: @d_haupt_abstufung1;
}

.cms_beschluss_angenommen {color: @d_haupt_meldungerfolgakzent;}
.cms_beschluss_abgelehnt {color: @d_haupt_meldungfehlerakzent;}
.cms_beschluss_vertagt {color: @d_haupt_abstufung2;}

.cms_aktionen_uebersicht li:last-child > p {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_oe {background: @d_haupt_meldunginfohinter;}
.cms_in {background: @d_haupt_meldungwarnunghinter;}

.cms_beschluss {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_beschluss:hover {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_beschluss_pro {
	border-left: 3px solid @d_haupt_meldungerfolgakzent;
	background: @d_haupt_meldungerfolghinter;
}

.cms_beschluss_contra {
	border-left: 3px solid @d_haupt_meldungfehlerakzent;
	background: @d_haupt_meldungfehlerhinter;
}

.cms_beschluss_enthaltung {
	border-left: 3px solid @d_haupt_abstufung2;
	background: @d_haupt_abstufung1;
}

.cms_beschluss_stimmen_pro {background: @d_haupt_meldungerfolgakzent;}
.cms_beschluss_stimmen_contra {background: @d_haupt_meldungfehlerakzent;}
.cms_beschluss_stimmen_enthaltung {background: @d_haupt_abstufung2;}

.cms_beschluss_stimmen_pro, .cms_beschluss_stimmen_contra, .cms_beschluss_stimmen_enthaltung, .cms_beschluss_langfristig {
	color: @d_haupt_schriftfarbenegativ;
}

.cms_beschluss_langfristig {
	color: @d_haupt_schriftfarbepositiv;
	background: @d_haupt_hintergrund;
}

.cms_beschlussuebersicht_jahr .cms_beschluss {
	border-right: 10px solid @d_haupt_hintergrund;
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion {
color: @d_haupt_schriftfarbepositiv !important;
background: @d_hinweis_hintergrund
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen {
	background-color: @d_chat_eigen
}


#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet .cms_chat_nachricht_innen {
	background-color: @d_haupt_meldungfehlerhinter
}

#cms_chat #cms_chat_mehr {
	color: @d_link_schrift;
}

#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen {
	background-color: @d_chat_gegenueber
}
