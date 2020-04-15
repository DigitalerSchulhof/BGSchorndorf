<?php
/* NORMALE BUTTONS */
fwrite($hell, ".cms_button, .cms_button_ja, .cms_button_nein, .cms_button_wichtig,\n");
fwrite($hell, ".cms_toggle, .cms_toggle_aktiv, .cms_toggle_inaktiv, .cms_iconbutton, .cms_iconbutton_ja,\n");
fwrite($hell, ".cms_iconbutton_nein, .cms_button_passiv, .cms_button_passivda, .cms_button_gesichert,\n");
fwrite($hell, ".cms_iconbutton_gesichert, .cms_toggle_aktiv_fest, .cms_datentypwahl,\n");
fwrite($hell, ".cms_fussnavigation a, .cms_fussnavigation span {\n");
fwrite($hell, "border: 1px solid transparent;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "padding: 3px 7px;\n");
fwrite($hell, "margin-bottom: 2px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "line-height: 1.5em;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_konfliktstunde {font-size: 75%;}\n");

fwrite($hell, ".cms_button:hover, .cms_button_ja:hover, .cms_button_nein:hover, .cms_button_wichtig:hover,\n");
fwrite($hell, ".cms_iconbutton, .cms_iconbutton_ja,\n");
fwrite($hell, ".cms_iconbutton_nein, .cms_aktion_klein:hover, .cms_datentypwahl:hover,\n");
fwrite($hell, ".cms_fussnavigation a:hover, .cms_fussnavigation span:hover {\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggle:hover, .cms_toggle_inaktiv:hover {\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggle_aktiv:hover {\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button, .cms_iconbutton, .cms_aktion_klein, .cms_datentypwahl, .cms_toggle,\n");
fwrite($hell, ".cms_toggle_aktiv, .cms_toggle_inaktiv {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button:hover, .cms_iconbutton:hover, .cms_aktion_klein:hover, .cms_datentypwahl:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_i .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_i .cms_button:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_ja, .cms_aktion_ja, .cms_iconbutton_ja, .cms_ja {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_ja:hover, .cms_aktion_ja:hover, .cms_iconbutton_ja:hover, .cms_ja:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_nein, .cms_aktion_nein, .cms_iconbutton_nein {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_nein:hover, .cms_aktion_nein:hover, .cms_iconbutton_nein:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_wichtig, .cms_aktion_wichtig {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_wichtig:hover, .cms_aktion_wichtig:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggle_aktiv_fest {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_gesichert, .cms_iconbutton_gesichert {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_passiv {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_passivda {\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_anonymFragezeichen, .cms_button_anonymFragezeichen:hover{\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_button_anonymFragezeichen:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_gesichert:hover, .cms_iconbutton_gesichert:hover,\n");
fwrite($hell, ".cms_button_passiv:hover, .cms_button_passivda {\n");
fwrite($hell, "cursor: default;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfzeile_i .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_kopfzeile_buttonhintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kopfzeile_buttonschrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfzeile_i .cms_button:hover, #cms_kopfzeile_i .cms_button_aktiv {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_kopfzeile_buttonhintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kopfzeile_buttonschrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_i .cms_button, .cms_fussnavigation a, .cms_fussnavigation span {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_fusszeile_buttonhintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_buttonschrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_i li.cms_footer_feedback a {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_i li.cms_footer_feedback a:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_fusszeile_buttonhintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_buttonschrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_i .cms_button:hover, .cms_fussnavigation a:hover,\n");
fwrite($hell, ".cms_fussnavigation span:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_fusszeile_buttonhintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_buttonschrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "/* TOGGLES */\n");
fwrite($hell, ".cms_toggle {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggle_aktiv {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_geraeteproblem .cms_toggle_aktiv {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_geraeteproblem .cms_toggle_aktiv:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggle:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggle_aktiv:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "/* ICONBUTTONS */\n");
fwrite($hell, ".cms_iconbutton, .cms_iconbutton_nein, .cms_iconbutton_ja, .cms_iconbutton_gesichert {\n");
fwrite($hell, "padding-top: 38px;\n");
fwrite($hell, "background-position: center 3px;\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "/* KLEINE BUTTONS */\n");
fwrite($hell, ".cms_aktion_klein {\n");
fwrite($hell, "padding: 2px 2px 3px 3px;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "line-height: 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "overflow: visible;\n");
fwrite($hell, "margin-bottom: 2px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, "td>.cms_aktion_klein {\n");
fwrite($hell, "margin-bottom: 0;\n");
fwrite($hell, "}\n");

fwrite($hell, "/* FARBBEISPIELE */\n");
fwrite($hell, ".cms_farbbeispiel, .cms_farbbeispiel_aktiv {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "border-radius: 11px;\n");
fwrite($hell, "border: 2px solid transparent;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 18px;\n");
fwrite($hell, "height: 18px;\n");
fwrite($hell, "margin: 1px 5px 1px 1px;\n");
fwrite($hell, "transition: 250ms;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_farbbeispiel:hover {\n");
fwrite($hell, "width: 20px;\n");
fwrite($hell, "height: 20px;\n");
fwrite($hell, "margin: 0 4px 0 0;\n");
fwrite($hell, "border-radius: 8px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_farbbeispiel_aktiv {\n");
fwrite($hell, "width: 20px;\n");
fwrite($hell, "height: 20px;\n");
fwrite($hell, "margin: 0 4px 0 0;\n");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "/* ICONAUSWAHL */\n");
fwrite($hell, ".cms_kategorie_icon_aktiv {\n");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kategorie_icon {\n");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kategorie_icon.cms_icon_verwendet {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kategorie_icon_aktiv:hover, .cms_kategorie_icon:hover {\n");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kategorie_icon_aktiv, .cms_kategorie_icon {\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "padding: 3px 2px 2px 2px;\n");
fwrite($hell, "margin-right: 5px;\n");
fwrite($hell, "line-height: 0px;\n");
fwrite($hell, "transition: 250ms;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kategorie_icon_aktiv img, .cms_kategorie_icon img {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldezahl {\n");
fwrite($hell, "opacity: .5;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "bottom: 1px;\n");
fwrite($hell, "padding: 2px 5px;\n");
fwrite($hell, "border-radius: 10px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "font-size: 70%;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-left: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldezahl_wichtig {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button:hover .cms_meldezahl {\n");
fwrite($hell, "opacity: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_datentypwahl p:first-child {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_schliessen {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: -15px;\n");
fwrite($hell, "right: 0px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_toggleeinblenden {\n");
fwrite($hell, "margin-top: 30px;\n");
fwrite($hell, "}\n");

fwrite($hell, "h1+.cms_toggleeinblenden,\n");
fwrite($hell, "h2+.cms_toggleeinblenden,\n");
fwrite($hell, "h3+.cms_toggleeinblenden,\n");
fwrite($hell, "h4+.cms_toggleeinblenden,\n");
fwrite($hell, "h5+.cms_toggleeinblenden,\n");
fwrite($hell, "h6+.cms_toggleeinblenden {\n");
fwrite($hell, "margin-top: 0px !important;\n");
fwrite($hell, "}\n");








// DARKMODE
fwrite($dunkel, ".cms_toggle:hover, .cms_toggle_inaktiv:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_toggle_aktiv:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerakzent']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button, .cms_iconbutton, .cms_aktion_klein, .cms_datentypwahl, .cms_toggle,\n");
fwrite($dunkel, ".cms_toggle_aktiv, .cms_toggle_inaktiv {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button:hover, .cms_iconbutton:hover, .cms_aktion_klein:hover, .cms_datentypwahl:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_unternavigation_i .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_unternavigation_i .cms_button:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_ja, .cms_aktion_ja, .cms_iconbutton_ja, .cms_ja {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_ja:hover, .cms_aktion_ja:hover, .cms_iconbutton_ja:hover, .cms_ja:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_nein, .cms_aktion_nein, .cms_iconbutton_nein {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_nein:hover, .cms_aktion_nein:hover, .cms_iconbutton_nein:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_wichtig, .cms_aktion_wichtig {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_wichtig:hover, .cms_aktion_wichtig:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnungakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_toggle_aktiv_fest {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_gesichert, .cms_iconbutton_gesichert {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "border: 1px dashed ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_passiv {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_passivda {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_anonymFragezeichen, .cms_button_anonymFragezeichen:hover{\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "&:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_kopfzeile_i .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_kopfzeile_buttonhintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kopfzeile_buttonschrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_kopfzeile_i .cms_button:hover, #cms_kopfzeile_i .cms_button_aktiv {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_kopfzeile_buttonhintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kopfzeile_buttonschrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_i .cms_button, .cms_fussnavigation a, .cms_fussnavigation span {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_fusszeile_buttonhintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_buttonschrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_i li.cms_footer_feedback a {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_i li.cms_footer_feedback a:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_fusszeile_buttonhintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_buttonschrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_i .cms_button:hover, .cms_fussnavigation a:hover,\n");
fwrite($dunkel, ".cms_fussnavigation span:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_fusszeile_buttonhintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_buttonschrifthover'].";\n");
fwrite($dunkel, "}\n");

/* TOGGLES */
fwrite($dunkel, ".cms_toggle {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_toggle_aktiv {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_geraeteproblem .cms_toggle_aktiv {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_geraeteproblem .cms_toggle_aktiv:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_toggle:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_toggle_aktiv:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

/* FARBBEISPIELE */
fwrite($dunkel, ".cms_farbbeispiel, .cms_farbbeispiel_aktiv {\n");
fwrite($dunkel, "&.cms_farbbeispiel_aktiv {\n");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");

/* ICONAUSWAHL */
fwrite($dunkel, ".cms_kategorie_icon_aktiv {\n");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kategorie_icon {\n");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kategorie_icon.cms_icon_verwendet {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kategorie_icon_aktiv:hover, .cms_kategorie_icon:hover {\n");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "cursor: pointer;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldezahl {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldezahl_wichtig {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");
?>
