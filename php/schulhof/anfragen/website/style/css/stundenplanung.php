<?php
fwrite($hell, ".cms_stundenplan_box {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_ueberschrift {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "padding: 0px 1px 0px 1px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_spalte {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "border-left: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_spalte .cms_stundenplan_spaltentitel {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "line-height: 20px;\n");
fwrite($hell, "height: 20px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "padding: 0px 1px 0px 1px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_zeitliniebez {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-size: 150%;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "width: 35px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "line-height: 100%;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_zeitliniebeginn, .cms_stundenplan_zeitlinieende {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_zeitlinietext {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-size: 70%;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "padding: 3px;\n");
fwrite($hell, "left: 35px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_zeitliniebeginn .cms_stundenplan_zeitlinietext {\n");
fwrite($hell, "border-bottom-right-radius: 3px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_zeitlinieende .cms_stundenplan_zeitlinietext {\n");
fwrite($hell, "border-top-right-radius: 3px;\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vollbild {\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "min-height: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "z-index: 5001;\n");
fwrite($hell, "padding: 0px 0px 20px 0px;\n");
fwrite($hell, "overflow-y: scroll;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplanung_stundenfeld, .cms_stundenplan_stundenfeld {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 30px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenfeld_blockiert {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplanung_markiert {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vplan_stunde_markiert {\n");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplanung_stundenfeld:hover {\n");
fwrite($hell, "cursor: crosshair;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplanung_stunde {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "opacity: .75;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "font-size: 75%;\n");
fwrite($hell, "padding: 2px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_stunde, .cms_stundenplan_stunde_entfall,\n");
fwrite($hell, ".cms_stundenplan_stunde_entfallgeaendert, .cms_stundenplan_stunde_ausfall,\n");
fwrite($hell, ".cms_stundenplan_stunde_geloest, .cms_stundenplan_stunde_konflikt,\n");
fwrite($hell, ".cms_stundenplan_stunde_ueberschneidung, .cms_stundenplan_stunde_geaendert {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "font-size: 75%;\n");
fwrite($hell, "padding: 2px 0px;\n");
fwrite($hell, "overflow-y: hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_stunde[onclick], .cms_stundenplan_stunde_entfall[onclick],\n");
fwrite($hell, ".cms_stundenplan_stunde_entfallgeaendert[onclick], .cms_stundenplan_stunde_ausfall[onclick],\n");
fwrite($hell, ".cms_stundenplan_stunde_geloest[onclick], .cms_stundenplan_stunde_konflikt[onclick],\n");
fwrite($hell, ".cms_stundenplan_stunde_ueberschneidung[onclick], .cms_stundenplan_stunde_geaendert[onclick] {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_stunde {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_entfall {border: 2px dashed ".$_POST['cms_style_h_haupt_meldunginfohinter']."; background: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_entfallgeaendert {border: 2px dashed ".$_POST['cms_style_h_haupt_meldungerfolghinter']."; background: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_ausfall {color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_geloest {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_geaendert {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_konflikt {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($hell, ".cms_stundenplan_stunde_ueberschneidung {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");

fwrite($hell, ".cms_stundenplan_stunde_rythmus {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "right: 3px;\n");
fwrite($hell, "opacity: .4;\n");
fwrite($hell, "color: #ffffff;\n");
fwrite($hell, "font-size: 250%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplanung_stundeinfo {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";\n");
fwrite($hell, "padding: 0px 5px 0px 5px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "font-family: 'robl';\n");
fwrite($hell, "font-weight: normal !important;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "z-index: 50;\n");
fwrite($hell, "width: 100px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "bottom: 10px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "height: 0px;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "font-size: 90%;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplanung_stunde:hover .cms_stundenplanung_stundeinfo {height: auto; opacity: 1;}\n");

fwrite($hell, ".cms_stundenplanung_stunde:hover {opacity: 1;}\n");

fwrite($hell, ".cms_wochentag_rythmus, .cms_wochentag_rythmus_schulfrei,\n");
fwrite($hell, ".cms_wochentag_rythmus_ferien, .cms_wochentag_rythmus_leer {\n");
fwrite($hell, "padding: 2px 5px;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 40px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_stunde_gewaehlt {\n");
fwrite($hell, "border: 2px dotted ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wochentag_rythmus_leer {background: ".$_POST['cms_style_h_haupt_abstufung1']."; border-color: ".$_POST['cms_style_h_haupt_abstufung1'].";}\n");
fwrite($hell, ".cms_wochentag_rythmus {background: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($hell, ".cms_wochentag_rythmus_schulfrei {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}\n");
fwrite($hell, ".cms_wochentag_rythmus_ferien {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");

fwrite($hell, ".cms_stundenplan_spaltentitel .cms_notiz {color: inherit !important;}\n");

fwrite($hell, ".cms_vplan_geloest {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_spalte_wochenplaene, #cms_spalte_konflikte, #cms_spalte_lehrer,\n");
fwrite($hell, "#cms_spalte_raeume, #cms_spalte_klassen {\n");
fwrite($hell, "overflow: hidden !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_vplan_konflikte_liste {\n");
fwrite($hell, "max-height: 650px;\n");
fwrite($hell, "overflow: scroll;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_stundenplan_spalte_trenner {\n");
fwrite($hell, "border-left: 1px solid ".$_POST['cms_style_h_haupt_abstufung2']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vplan_konfliktplan_wahl {width: 100%;}\n");
fwrite($hell, ".cms_vplan_konfliktplan_wahl td {width: 50%;}\n");

fwrite($hell, ".cms_vplan_entfall {\n");
fwrite($hell, "opacity: .5;\n");
fwrite($hell, "}\n");

fwrite($hell, "tr.cms_vplan_ausgewaehlt td {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "tr.cms_vplan_ausgewaehlt:hover td {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vplan_konfliktgrund {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vplan_konfliktwarnung {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_lvplan_heute {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 40%;\n");
fwrite($hell, "left: 0%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_lvplan_heute p, #cms_lvplan_heute td, #cms_lvplan_heute h4,\n");
fwrite($hell, "#cms_lvplan_morgen p, #cms_lvplan_morgen td, #cms_lvplan_morgen h4,\n");
fwrite($hell, "#cms_svplan_heute p, #cms_svplan_heute td, #cms_svplan_heute h4,\n");
fwrite($hell, "#cms_svplan_morgen p, #cms_svplan_morgen td, #cms_svplan_morgen h4,\n");
fwrite($hell, "#cms_lvplan_geraete p, #cms_lvplan_geraete td, #cms_lvplan_geraete h4 {font-size: 110% !important;}\n");
fwrite($hell, "#cms_lvplan_geraete p.cms_notiz {font-size: 80% !important;}\n");
fwrite($hell, "#cms_lvplan_heute h2, #cms_lvplan_morgen h2, #cms_lvplan_geraete h2,\n");
fwrite($hell, "#cms_svplan_heute h2, #cms_svplan_morgen h2, #cms_svplan_geraete h2 {font-size: 150% !important;}\n");

fwrite($hell, "#cms_lvplan_geraete {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 20%;\n");
fwrite($hell, "left: 40%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_lvplan_morgen {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 40%;\n");
fwrite($hell, "left: 60%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_svplan_heute {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "left: 0%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_svplan_morgen {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "left: 50%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_ausplanung_ausgeplant_l, #cms_ausplanung_ausgeplant_r,\n");
fwrite($hell, "#cms_ausplanung_ausgeplant_k, #cms_ausplanung_ausgeplant_s {\n");
fwrite($hell, "margin-top: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_markierte_liste_0 td {background: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($hell, ".cms_markierte_liste_1 td {background: ".$_POST['cms_style_h_haupt_abstufung1']."; border-bottom: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");

fwrite($hell, ".cms_vplanliste_entfall td:first-child {border-left: 4px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($hell, ".cms_vplanliste_neu td:first-child {border-left: 4px solid ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");

fwrite($hell, ".cms_auswaehlen {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_konflikte_liste_menue {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "left: 30px;\n");
fwrite($hell, "padding: 3px 3px 0px 3px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "transition-delay: 1s;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_stundenplan_spalte {\n");
fwrite($dunkel, "border-left: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_spalte .cms_stundenplan_spaltentitel {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_zeitliniebez {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_zeitliniebeginn, .cms_stundenplan_zeitlinieende {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_zeitlinietext {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_vollbild {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenfeld_blockiert {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplanung_markiert {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_vplan_stunde_markiert {\n");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplanung_stunde {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_stunde {background: ".$_POST['cms_style_d_haupt_abstufung1'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_entfall {border: 2px dashed ".$_POST['cms_style_d_haupt_meldunginfohinter']."; background: ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_entfallgeaendert {border: 2px dashed ".$_POST['cms_style_d_haupt_meldungerfolghinter']."; background: ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_ausfall {color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";background: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_geloest {background: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_geaendert {background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_konflikt {background: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_stundenplan_stunde_ueberschneidung {background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";}\n");

fwrite($dunkel, ".cms_stundenplanung_stundeinfo {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_wochentag_rythmus, .cms_wochentag_rythmus_schulfrei,\n");
fwrite($dunkel, ".cms_wochentag_rythmus_ferien, .cms_wochentag_rythmus_leer {\n");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_stunde_gewaehlt {\n");
fwrite($dunkel, "border: 2px dotted ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_wochentag_rythmus_leer {background: ".$_POST['cms_style_d_haupt_abstufung1']."; border-color: ".$_POST['cms_style_d_haupt_abstufung1'].";}\n");
fwrite($dunkel, ".cms_wochentag_rythmus {background: ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");
fwrite($dunkel, ".cms_wochentag_rythmus_schulfrei {background: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";}\n");
fwrite($dunkel, ".cms_wochentag_rythmus_ferien {background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";}\n");

fwrite($dunkel, ".cms_vplan_geloest {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_meldunginfoakzent']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_stundenplan_spalte_trenner {\n");
fwrite($dunkel, "border-left: 1px solid ".$_POST['cms_style_d_haupt_abstufung2']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "tr.cms_vplan_ausgewaehlt td {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "tr.cms_vplan_ausgewaehlt:hover td {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_vplan_konfliktgrund {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_vplan_konfliktwarnung {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_markierte_liste_0 td {background: ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");
fwrite($dunkel, ".cms_markierte_liste_1 td {background: ".$_POST['cms_style_d_haupt_abstufung1']."; border-bottom: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");

fwrite($dunkel, ".cms_vplanliste_entfall td:first-child {border-left: 4px solid ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_vplanliste_neu td:first-child {border-left: 4px solid ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";}\n");

fwrite($dunkel, ".cms_konflikte_liste_menue {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";\n");
fwrite($dunkel, "}\n");
?>
