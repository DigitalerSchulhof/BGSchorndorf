<?php
fwrite($hell, ".cms_uebersicht .cms_ersteller {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht .cms_blogeintrag:hover .cms_ersteller,\n");
fwrite($hell, ".cms_uebersicht tr:hover .cms_ersteller  {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_blogeintrag, .cms_beschlusseintrag {\n");
fwrite($hell, "padding-left: 5px !important;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschlusseintrag {\n");
fwrite($hell, "padding-right: 26px !important;\n");
fwrite($hell, "min-height: 45px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_blogeintrag:hover, .cms_beschlusseintrag:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_blogliste_details:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_blogeintrag p.cms_inhaltvorschau, .cms_beschlusseintrag p.cms_inhaltvorschau {\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_angenommen {color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_beschluss_abgelehnt {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($hell, ".cms_beschluss_vertagt {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");

fwrite($hell, ".cms_beschlusseintrag p.cms_beschlussicons {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "right: 5px;\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_icon {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-bottom: 5px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_icon:last-child {\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_aktionen_uebersicht li > p {\n");
fwrite($hell, "padding: 4px 5px 4px 5px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_aktionen_uebersicht li .cms_beschlusseintrag {\n");
fwrite($hell, "border-bottom: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_aktionen_uebersicht li:last-child > p {\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_gruppen_oeffentlich_art {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "height: 16px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 5px;\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_oe, .cms_in {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "height: 16px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-radius: 8px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_oe {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}\n");
fwrite($hell, ".cms_in {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");

fwrite($hell, ".cms_beschluss {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss h4, .cms_beschluss p {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_pro {\n");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_contra {\n");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_enthaltung {\n");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_stimmen {\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_stimmen_pro {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_beschluss_stimmen_contra {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($hell, ".cms_beschluss_stimmen_enthaltung {background: ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");

fwrite($hell, ".cms_beschluss_stimmen_pro, .cms_beschluss_stimmen_contra, .cms_beschluss_stimmen_enthaltung, .cms_beschluss_langfristig {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "padding: 2px 7px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "min-width: 25px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_stimmen_pro {\n");
fwrite($hell, "border-top-left-radius: 7px;\n");
fwrite($hell, "border-bottom-left-radius: 7px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_stimmen_contra {\n");
fwrite($hell, "border-top-right-radius: 7px;\n");
fwrite($hell, "border-bottom-right-radius: 7px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschluss_langfristig {\n");
fwrite($hell, "margin-left: 10px;\n");
fwrite($hell, "border-radius: 7px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschlussuebersicht_jahr {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_beschlussuebersicht_jahr .cms_beschluss {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "width: 25%;\n");
fwrite($hell, "border-right: 10px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_chat #cms_chat_nachrichten {");
fwrite($hell, "	width: 100%;");
fwrite($hell, "	padding: 5px 20px;");
fwrite($hell, "	max-height: 500px;");
fwrite($hell, "	overflow-y: auto");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_datum {");
fwrite($hell, "	text-align: center;");
fwrite($hell, "	margin-bottom: 10px");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen {");
fwrite($hell, "	width: 100%;");
fwrite($hell, "	margin-bottom: 15px;");
fwrite($hell, "	float: left");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen {");
fwrite($hell, "	position: relative;");
fwrite($hell, "	min-width: 40%;");
fwrite($hell, "	max-width: 60%;");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_chat_gegenueber'].";");
fwrite($hell, "	border-radius: ".$_POST['cms_style_haupt_radiusmittel'].";");
fwrite($hell, "	float: left;");
fwrite($hell, "	padding: 5px");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion {");
fwrite($hell, "	position: absolute;");
fwrite($hell, "	top: 0;");
fwrite($hell, "	right: 0;");
fwrite($hell, "	padding: inherit");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=sendend] {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] {");
fwrite($hell, "	cursor: pointer");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";");
fwrite($hell, "	padding: 0 5px 0 5px;");
fwrite($hell, "	position: absolute;");
fwrite($hell, "	font-family: 'robl';");
fwrite($hell, "	font-weight: normal !important;");
fwrite($hell, "	display: none;");
fwrite($hell, "	border-radius: ".$_POST['cms_style_hinweis_radius'].";");
fwrite($hell, "	z-index: 50;");
fwrite($hell, "	width: 150px;");
fwrite($hell, "	overflow: visible;");
fwrite($hell, "	left: 0;");
fwrite($hell, "	bottom: 25px;");
fwrite($hell, "	overflow: hidden;");
fwrite($hell, "	text-align: left;");
fwrite($hell, "	z-index: 5");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion p {");
fwrite($hell, "	padding: 5px");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion img {");
fwrite($hell, "	height: 16px;");
fwrite($hell, "	width: 16px");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_id {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_autor {");
fwrite($hell, "	font-size: 90%");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_nachricht {");
fwrite($hell, "	padding-left: 5px;");
fwrite($hell, "	font-size: 110%;");
fwrite($hell, "	white-space: pre-wrap;");
fwrite($hell, "	word-break: break-word");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_zeit {");
fwrite($hell, "	float: right");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen {");
fwrite($hell, "	float: right;");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_chat_eigen']."");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion {");
fwrite($hell, "	text-align: right;");
fwrite($hell, "	left: unset;");
fwrite($hell, "	right: 0");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_sendend {");
fwrite($hell, "	opacity: .8");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_sendend .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=sendend] {");
fwrite($hell, "	display: block");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_sendend .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet {");
fwrite($hell, "	opacity: .8");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet .cms_chat_nachricht_innen {");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter']."");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] [data-mehr=melden] {");
fwrite($hell, "	opacity: .7;");
fwrite($hell, "	cursor: default");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_geloescht {");
fwrite($hell, "	opacity: .7");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_geloescht .cms_chat_nachricht_innen .cms_chat_nachricht_aktion {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_geloescht .cms_chat_nachricht_innen .cms_chat_nachricht_nachricht {");
fwrite($hell, "	font-style: italic;");
fwrite($hell, "	font-size: 90%");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachricht_verfassen {");
fwrite($hell, "	width: 100%");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachricht_verfassen label {");
fwrite($hell, "	cursor: pointer");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachricht_verfassen textarea {");
fwrite($hell, "	width: 90%;");
fwrite($hell, "	width: calc(100% - 26px)");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachricht_verfassen .cms_meldung_fehler {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachricht_verfassen div:not(.cms_meldung_fehler) {");
fwrite($hell, "	display: inline-block;");
fwrite($hell, "	float: right;");
fwrite($hell, "	width: auto");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_nachricht_verfassen div:not(.cms_meldung_fehler) img {");
fwrite($hell, "	padding: 5px;");
fwrite($hell, "	cursor: pointer");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_mehr {");
fwrite($hell, "	color: ".$_POST['cms_style_h_link_schrift'].";");
fwrite($hell, "	cursor: pointer");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_status,");
fwrite($hell, "#cms_chat #cms_chat_laden,");
fwrite($hell, "#cms_chat #cms_chat_leer,");
fwrite($hell, "#cms_chat #cms_chat_mehr {");
fwrite($hell, "	text-align: center;");
fwrite($hell, "	margin-top: 10px;");
fwrite($hell, "	margin-bottom: 20px");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_status h3,");
fwrite($hell, "#cms_chat #cms_chat_laden h3,");
fwrite($hell, "#cms_chat #cms_chat_leer h3,");
fwrite($hell, "#cms_chat #cms_chat_mehr h3 {");
fwrite($hell, "	margin-top: 0");
fwrite($hell, "}");

fwrite($hell, "#cms_chat #cms_chat_status,");
fwrite($hell, "#cms_chat #cms_chat_laden,");
fwrite($hell, "#cms_chat #cms_chat_berechtigung,");
fwrite($hell, "#cms_chat #cms_chat_leer,");
fwrite($hell, "#cms_chat #cms_chat_mehr,");
fwrite($hell, "#cms_chat #cms_chat_stumm {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_leer #cms_chat_leer {");
fwrite($hell, "	display: block");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_mehr #cms_chat_mehr {");
fwrite($hell, "	display: block");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_stumm>#cms_chat_nachricht_verfassen {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_stumm #cms_chat_stumm {");
fwrite($hell, "	display: block");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_status>* {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_status #cms_chat_status {");
fwrite($hell, "	display: block");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_laden #cms_chat_laden {");
fwrite($hell, "	display: block");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_berechtigung>* {");
fwrite($hell, "	display: none");
fwrite($hell, "}");

fwrite($hell, "#cms_chat.cms_chat_berechtigung #cms_chat_berechtigung {");
fwrite($hell, "	display: block");
fwrite($hell, "}");


// DARKMODE
fwrite($dunkel, ".cms_uebersicht .cms_ersteller {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_blogeintrag:hover, .cms_beschlusseintrag:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss_angenommen {color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_beschluss_abgelehnt {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_beschluss_vertagt {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");

fwrite($dunkel, ".cms_aktionen_uebersicht li:last-child > p {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_oe {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}\n");
fwrite($dunkel, ".cms_in {background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}\n");

fwrite($dunkel, ".cms_beschluss {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss_pro {\n");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss_contra {\n");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss_enthaltung {\n");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss_stimmen_pro {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_beschluss_stimmen_contra {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_beschluss_stimmen_enthaltung {background: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");

fwrite($dunkel, ".cms_beschluss_stimmen_pro, .cms_beschluss_stimmen_contra, .cms_beschluss_stimmen_enthaltung, .cms_beschluss_langfristig {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschluss_langfristig {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_beschlussuebersicht_jahr .cms_beschluss {\n");
fwrite($dunkel, "border-right: 10px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen .cms_chat_nachricht_aktion[data-aktion=mehr] .cms_chat_aktion {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund']."");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_eigen .cms_chat_nachricht_innen {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_chat_eigen']."");
fwrite($dunkel, "}");


fwrite($dunkel, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen.cms_chat_nachricht_gemeldet .cms_chat_nachricht_innen {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter']."");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_chat #cms_chat_mehr {");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_link_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_chat #cms_chat_nachrichten .cms_chat_nachricht_aussen .cms_chat_nachricht_innen {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_chat_gegenueber']."");
fwrite($dunkel, "}");

?>
