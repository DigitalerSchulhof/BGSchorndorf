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

fwrite($hell, "#cms_chat {\n");
fwrite($hell, "#cms_chat_nachrichten {\n");
fwrite($hell, ".cms_chat_datum {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "margin-bottom: 10px;\n");
fwrite($hell, "}\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "padding: 5px 20px;\n");
fwrite($hell, "max-height: 500px;\n");
fwrite($hell, "overflow-y: auto;\n");
fwrite($hell, ".cms_chat_nachricht_aussen {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "margin-bottom: 15px;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, ".cms_chat_nachricht_innen {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "min-width: 40%;\n");
fwrite($hell, "max-width: 60%;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_chat_gegenueber'].";\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, ".cms_chat_nachricht_aktion {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 0;\n");
fwrite($hell, "right: 0;\n");
fwrite($hell, "padding: inherit;\n");
fwrite($hell, "&[data-aktion=sendend] {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, "&[data-aktion=mehr] {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, ".cms_chat_aktion {\n");
fwrite($hell, "// .cms_hinweis\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";\n");
fwrite($hell, "padding: 0px 5px 0px 5px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "font-family: 'robl';\n");
fwrite($hell, "font-weight: normal !important;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "z-index: 50;\n");
fwrite($hell, "width: 150px;\n");
fwrite($hell, "overflow: visible;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "bottom: 25px;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-align: left;\n");

fwrite($hell, "z-index: 5;\n");
fwrite($hell, "p {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "img {\n");
fwrite($hell, "height: 16px;\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_chat_nachricht_id {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_chat_nachricht_autor {\n");
fwrite($hell, "font-size: 90%;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_chat_nachricht_nachricht {\n");
fwrite($hell, "padding-left: 5px;\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "white-space: pre-wrap;\n");
fwrite($hell, "word-break: break-word\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_chat_nachricht_zeit {\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_nachricht_eigen {\n");
fwrite($hell, ".cms_chat_nachricht_innen {\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_chat_eigen'].";\n");
fwrite($hell, ".cms_chat_nachricht_aktion {\n");
fwrite($hell, "&[data-aktion=mehr] {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, ".cms_chat_aktion {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "left: unset;\n");
fwrite($hell, "right: 0;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_nachricht_sendend {\n");
fwrite($hell, "opacity: 0.8;\n");
fwrite($hell, ".cms_chat_nachricht_innen {\n");
fwrite($hell, ".cms_chat_nachricht_aktion {\n");
fwrite($hell, "&[data-aktion=sendend] {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "&[data-aktion=mehr] {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_nachricht_gemeldet {\n");
fwrite($hell, "opacity: 0.8;\n");
fwrite($hell, ".cms_chat_nachricht_innen {\n");
fwrite($hell, "background-color: mix(".$_POST['cms_style_h_haupt_meldungfehlerhinter'].", white);\n");
fwrite($hell, ".cms_chat_nachricht_aktion {\n");
fwrite($hell, "&[data-aktion=mehr] {\n");
fwrite($hell, "[data-mehr=melden] {\n");
fwrite($hell, "opacity: 0.7;\n");
fwrite($hell, "cursor: default;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_nachricht_geloescht {\n");
fwrite($hell, "opacity: 0.7;\n");
fwrite($hell, ".cms_chat_nachricht_innen {\n");
fwrite($hell, ".cms_chat_nachricht_aktion {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_chat_nachricht_nachricht {\n");
fwrite($hell, "font-style: italic;\n");
fwrite($hell, "font-size: 90%;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "#cms_chat_nachricht_verfassen {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "label {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");
fwrite($hell, "textarea {\n");
fwrite($hell, "width: 90%; // Fallback\n");
fwrite($hell, "width: ~'calc(100% - 26px)';\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_meldung_fehler {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, "div:not(.cms_meldung_fehler) {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "width: auto;\n");
fwrite($hell, "img {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_chat_mehr {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_link_schrift'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_chat_status,\n");
fwrite($hell, "#cms_chat_laden,\n");
fwrite($hell, "#cms_chat_leer,\n");
fwrite($hell, "#cms_chat_mehr {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "margin-top: 10px;\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "h3 {\n");
fwrite($hell, "margin-top: 0;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "#cms_chat_status,\n");
fwrite($hell, "#cms_chat_laden,\n");
fwrite($hell, "#cms_chat_berechtigung,\n");
fwrite($hell, "#cms_chat_leer,\n");
fwrite($hell, "#cms_chat_mehr,\n");
fwrite($hell, "#cms_chat_stumm {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, "// Nach Priorität:\n");
fwrite($hell, "&.cms_chat_leer { // 0\n");
fwrite($hell, "#cms_chat_leer {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_mehr { // 0\n");
fwrite($hell, "#cms_chat_mehr {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_stumm { // 0.1\n");
fwrite($hell, ">#cms_chat_nachricht_verfassen {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, "#cms_chat_stumm {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_status { // 1\n");
fwrite($hell, ">* {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, "#cms_chat_status {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_laden {  // 1.1\n");
fwrite($hell, "#cms_chat_laden {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "&.cms_chat_berechtigung { // 2\n");
fwrite($hell, ">* {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");
fwrite($hell, "#cms_chat_berechtigung {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");





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

fwrite($dunkel, "#cms_chat {\n");
fwrite($dunkel, "#cms_chat_nachrichten {\n");
fwrite($dunkel, ".cms_chat_nachricht_aussen {\n");
fwrite($dunkel, ".cms_chat_nachricht_innen {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_chat_gegenueber'].";\n");
fwrite($dunkel, ".cms_chat_nachricht_aktion {\n");
fwrite($dunkel, "&[data-aktion=mehr] {\n");
fwrite($dunkel, ".cms_chat_aktion {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "&.cms_chat_nachricht_eigen {\n");
fwrite($dunkel, ".cms_chat_nachricht_innen {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_chat_eigen'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "&.cms_chat_nachricht_gemeldet {\n");
fwrite($dunkel, "opacity: 0.8;\n");
fwrite($dunkel, ".cms_chat_nachricht_innen {\n");
fwrite($dunkel, "background-color: mix(".$_POST['cms_style_h_haupt_meldungfehlerakzent'].", white);\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_chat_mehr {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_link_schrift'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "}\n");
?>
