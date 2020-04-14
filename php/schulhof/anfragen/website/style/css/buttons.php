<?php
/* NORMALE BUTTONS */
fwrite($hell, ".cms_button, .cms_button_ja, .cms_button_nein, .cms_button_wichtig,");
fwrite($hell, ".cms_toggle, .cms_toggle_aktiv, .cms_toggle_inaktiv, .cms_iconbutton, .cms_iconbutton_ja,");
fwrite($hell, ".cms_iconbutton_nein, .cms_button_passiv, .cms_button_passivda, .cms_button_gesichert,");
fwrite($hell, ".cms_iconbutton_gesichert, .cms_toggle_aktiv_fest, .cms_datentypwahl,");
fwrite($hell, ".cms_fussnavigation a, .cms_fussnavigation span {");
fwrite($hell, "border: 1px solid transparent;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "padding: 3px 7px;");
fwrite($hell, "margin-bottom: 2px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "position: relative;");
fwrite($hell, "line-height: 1.5em;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_konfliktstunde {font-size: 75%;}");

fwrite($hell, ".cms_button:hover, .cms_button_ja:hover, .cms_button_nein:hover, .cms_button_wichtig:hover,");
fwrite($hell, ".cms_iconbutton, .cms_iconbutton_ja,");
fwrite($hell, ".cms_iconbutton_nein, .cms_aktion_klein:hover, .cms_datentypwahl:hover,");
fwrite($hell, ".cms_fussnavigation a:hover, .cms_fussnavigation span:hover {");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_toggle:hover, .cms_toggle_inaktiv:hover {");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_toggle_aktiv:hover {");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_button, .cms_iconbutton, .cms_aktion_klein, .cms_datentypwahl, .cms_toggle,");
fwrite($hell, ".cms_toggle_aktiv, .cms_toggle_inaktiv {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button:hover, .cms_iconbutton:hover, .cms_aktion_klein:hover, .cms_datentypwahl:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_i .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_i .cms_button:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_button_ja, .cms_aktion_ja, .cms_iconbutton_ja, .cms_ja {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_button_ja:hover, .cms_aktion_ja:hover, .cms_iconbutton_ja:hover, .cms_ja:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_button_nein, .cms_aktion_nein, .cms_iconbutton_nein {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_nein:hover, .cms_aktion_nein:hover, .cms_iconbutton_nein:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_wichtig, .cms_aktion_wichtig {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_wichtig:hover, .cms_aktion_wichtig:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_toggle_aktiv_fest {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_gesichert, .cms_iconbutton_gesichert {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_passiv {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_passivda {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_anonymFragezeichen, .cms_button_anonymFragezeichen:hover{");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "&:hover {");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, ".cms_button_gesichert:hover, .cms_iconbutton_gesichert:hover,");
fwrite($hell, ".cms_button_passiv:hover, .cms_button_passivda {");
fwrite($hell, "cursor: default;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfzeile_i .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_kopfzeile_buttonhintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kopfzeile_buttonschrift'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfzeile_i .cms_button:hover, #cms_kopfzeile_i .cms_button_aktiv {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_kopfzeile_buttonhintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kopfzeile_buttonschrifthover'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_i .cms_button, .cms_fussnavigation a, .cms_fussnavigation span {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_fusszeile_buttonhintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_buttonschrift'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_i li.cms_footer_feedback a {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_i li.cms_footer_feedback a:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_fusszeile_buttonhintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_buttonschrifthover'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_i .cms_button:hover, .cms_fussnavigation a:hover,");
fwrite($hell, ".cms_fussnavigation span:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_fusszeile_buttonhintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_buttonschrifthover'].";");
fwrite($hell, "}");

fwrite($hell, "/* TOGGLES */");
fwrite($hell, ".cms_toggle {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_toggle_aktiv {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_geraeteproblem .cms_toggle_aktiv {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_geraeteproblem .cms_toggle_aktiv:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_toggle:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_toggle_aktiv:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, "/* ICONBUTTONS */");
fwrite($hell, ".cms_iconbutton, .cms_iconbutton_nein, .cms_iconbutton_ja, .cms_iconbutton_gesichert {");
fwrite($hell, "padding-top: 38px;");
fwrite($hell, "background-position: center 3px;");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "/* KLEINE BUTTONS */");
fwrite($hell, ".cms_aktion_klein {");
fwrite($hell, "padding: 2px 2px 3px 3px;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "line-height: 0px;");
fwrite($hell, "position: relative;");
fwrite($hell, "overflow: visible;");
fwrite($hell, "margin-bottom: 2px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "td>& {");
fwrite($hell, "margin-bottom: 0;");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "/* FARBBEISPIELE */");
fwrite($hell, ".cms_farbbeispiel, .cms_farbbeispiel_aktiv {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "border-radius: 11px;");
fwrite($hell, "border: 2px solid transparent;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 18px;");
fwrite($hell, "height: 18px;");
fwrite($hell, "margin: 1px 5px 1px 1px;");
fwrite($hell, "transition: 250ms;");

fwrite($hell, "&:hover:not(.cms_farbbeispiel_aktiv) {");
fwrite($hell, "width: 20px;");
fwrite($hell, "height: 20px;");
fwrite($hell, "margin: 0 4px 0 0;");
fwrite($hell, "border-radius: 8px;");
fwrite($hell, "}");
fwrite($hell, "&.cms_farbbeispiel_aktiv {");
fwrite($hell, "width: 20px;");
fwrite($hell, "height: 20px;");
fwrite($hell, "margin: 0 4px 0 0;");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "/* ICONAUSWAHL */");
fwrite($hell, ".cms_kategorie_icon_aktiv {");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_kategorie_icon {");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_kategorie_icon.cms_icon_verwendet {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_kategorie_icon_aktiv:hover, .cms_kategorie_icon:hover {");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_kategorie_icon_aktiv, .cms_kategorie_icon {");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "padding: 3px 2px 2px 2px;");
fwrite($hell, "margin-right: 5px;");
fwrite($hell, "line-height: 0px;");
fwrite($hell, "transition: 250ms;");
fwrite($hell, "}");

fwrite($hell, ".cms_kategorie_icon_aktiv img, .cms_kategorie_icon img {");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_meldezahl {");
fwrite($hell, "opacity: .5;");
fwrite($hell, "position: relative;");
fwrite($hell, "bottom: 1px;");
fwrite($hell, "padding: 2px 5px;");
fwrite($hell, "border-radius: 10px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "font-size: 70%;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-left: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_meldezahl_wichtig {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button:hover .cms_meldezahl {");
fwrite($hell, "opacity: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_datentypwahl p:first-child {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_button_schliessen {");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: -15px;");
fwrite($hell, "right: 0px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, ".cms_toggleeinblenden {");
fwrite($hell, "margin-top: 30px;");
fwrite($hell, "}");

fwrite($hell, "h1+.cms_toggleeinblenden,");
fwrite($hell, "h2+.cms_toggleeinblenden,");
fwrite($hell, "h3+.cms_toggleeinblenden,");
fwrite($hell, "h4+.cms_toggleeinblenden,");
fwrite($hell, "h5+.cms_toggleeinblenden,");
fwrite($hell, "h6+.cms_toggleeinblenden {");
fwrite($hell, "margin-top: 0px !important;");
fwrite($hell, "}");








// DARKMODE
fwrite($dunkel, ".cms_toggle:hover, .cms_toggle_inaktiv:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_toggle_aktiv:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button, .cms_iconbutton, .cms_aktion_klein, .cms_datentypwahl, .cms_toggle,");
fwrite($dunkel, ".cms_toggle_aktiv, .cms_toggle_inaktiv {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button:hover, .cms_iconbutton:hover, .cms_aktion_klein:hover, .cms_datentypwahl:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_unternavigation_i .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_unternavigation_i .cms_button:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_ja, .cms_aktion_ja, .cms_iconbutton_ja, .cms_ja {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_ja:hover, .cms_aktion_ja:hover, .cms_iconbutton_ja:hover, .cms_ja:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_nein, .cms_aktion_nein, .cms_iconbutton_nein {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_nein:hover, .cms_aktion_nein:hover, .cms_iconbutton_nein:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_wichtig, .cms_aktion_wichtig {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_wichtig:hover, .cms_aktion_wichtig:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_toggle_aktiv_fest {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_gesichert, .cms_iconbutton_gesichert {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_passiv {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_passivda {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_anonymFragezeichen, .cms_button_anonymFragezeichen:hover{");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "&:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_kopfzeile_i .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_kopfzeile_buttonhintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kopfzeile_buttonschrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_kopfzeile_i .cms_button:hover, #cms_kopfzeile_i .cms_button_aktiv {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_kopfzeile_buttonhintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_kopfzeile_buttonschrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_i .cms_button, .cms_fussnavigation a, .cms_fussnavigation span {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_fusszeile_buttonhintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_buttonschrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_i li.cms_footer_feedback a {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_i li.cms_footer_feedback a:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_fusszeile_buttonhintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_buttonschrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_i .cms_button:hover, .cms_fussnavigation a:hover,");
fwrite($dunkel, ".cms_fussnavigation span:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_fusszeile_buttonhintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_buttonschrifthover'].";");
fwrite($dunkel, "}");

/* TOGGLES */
fwrite($dunkel, ".cms_toggle {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_toggle_aktiv {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_geraeteproblem .cms_toggle_aktiv {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_geraeteproblem .cms_toggle_aktiv:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_toggle:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_toggle_aktiv:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

/* FARBBEISPIELE */
fwrite($dunkel, ".cms_farbbeispiel, .cms_farbbeispiel_aktiv {");
fwrite($dunkel, "&.cms_farbbeispiel_aktiv {");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "}");

/* ICONAUSWAHL */
fwrite($dunkel, ".cms_kategorie_icon_aktiv {");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kategorie_icon {");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kategorie_icon.cms_icon_verwendet {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kategorie_icon_aktiv:hover, .cms_kategorie_icon:hover {");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($dunkel, "cursor: pointer;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldezahl {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldezahl_wichtig {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "}");
?>
