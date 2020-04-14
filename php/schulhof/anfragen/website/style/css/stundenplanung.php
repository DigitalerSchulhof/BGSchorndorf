<?php
fwrite($hell, ".cms_stundenplan_box {");
fwrite($hell, "display: block;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_ueberschrift {");
fwrite($hell, "position: absolute;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "padding: 0px 1px 0px 1px;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_spalte {");
fwrite($hell, "position: relative;");
fwrite($hell, "display: block;");
fwrite($hell, "height: 100%;");
fwrite($hell, "float: left;");
fwrite($hell, "border-left: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_spalte .cms_stundenplan_spaltentitel {");
fwrite($hell, "position: absolute;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "line-height: 20px;");
fwrite($hell, "height: 20px;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "padding: 0px 1px 0px 1px;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_zeitliniebez {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-size: 150%;");
fwrite($hell, "position: absolute;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "width: 35px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "line-height: 100%;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_zeitliniebeginn, .cms_stundenplan_zeitlinieende {");
fwrite($hell, "width: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "position: absolute;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_zeitlinietext {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-size: 70%;");
fwrite($hell, "position: absolute;");
fwrite($hell, "padding: 3px;");
fwrite($hell, "left: 35px;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "text-align: left;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_zeitliniebeginn .cms_stundenplan_zeitlinietext {");
fwrite($hell, "border-bottom-right-radius: 3px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_zeitlinieende .cms_stundenplan_zeitlinietext {");
fwrite($hell, "border-top-right-radius: 3px;");
fwrite($hell, "bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_vollbild {");
fwrite($hell, "position: fixed;");
fwrite($hell, "left: 0px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "min-height: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "z-index: 5001;");
fwrite($hell, "padding: 0px 0px 20px 0px;");
fwrite($hell, "overflow-y: scroll;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplanung_stundenfeld, .cms_stundenplan_stundenfeld {");
fwrite($hell, "display: flex;");
fwrite($hell, "height: 100%;");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 30px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenfeld_blockiert {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplanung_markiert {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_vplan_stunde_markiert {");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplanung_stundenfeld:hover {");
fwrite($hell, "cursor: crosshair;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplanung_stunde {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "height: 100%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "position: relative;");
fwrite($hell, "opacity: .75;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "font-size: 75%;");
fwrite($hell, "padding: 2px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_stunde, .cms_stundenplan_stunde_entfall,");
fwrite($hell, ".cms_stundenplan_stunde_entfallgeaendert, .cms_stundenplan_stunde_ausfall,");
fwrite($hell, ".cms_stundenplan_stunde_geloest, .cms_stundenplan_stunde_konflikt,");
fwrite($hell, ".cms_stundenplan_stunde_ueberschneidung, .cms_stundenplan_stunde_geaendert {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "height: 100%;");
fwrite($hell, "width: 100%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "position: relative;");
fwrite($hell, "font-size: 75%;");
fwrite($hell, "padding: 2px 0px;");
fwrite($hell, "overflow-y: hidden;");
fwrite($hell, "&[onclick] {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_stunde {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}");
fwrite($hell, ".cms_stundenplan_stunde_entfall {border: 2px dashed ".$_POST['cms_style_h_haupt_meldunginfohinter']."; background: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($hell, ".cms_stundenplan_stunde_entfallgeaendert {border: 2px dashed ".$_POST['cms_style_h_haupt_meldungerfolghinter']."; background: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($hell, ".cms_stundenplan_stunde_ausfall {color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($hell, ".cms_stundenplan_stunde_geloest {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
fwrite($hell, ".cms_stundenplan_stunde_geaendert {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($hell, ".cms_stundenplan_stunde_konflikt {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($hell, ".cms_stundenplan_stunde_ueberschneidung {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");

fwrite($hell, ".cms_stundenplan_stunde_rythmus {");
fwrite($hell, "position: absolute;");
fwrite($hell, "bottom: 0px;");
fwrite($hell, "right: 3px;");
fwrite($hell, "opacity: .4;");
fwrite($hell, "color: #ffffff;");
fwrite($hell, "font-size: 250%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplanung_stundeinfo {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";");
fwrite($hell, "padding: 0px 5px 0px 5px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "font-family: 'robl';");
fwrite($hell, "font-weight: normal !important;");
fwrite($hell, "display: block;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "z-index: 50;");
fwrite($hell, "width: 100px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "bottom: 10px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "height: 0px;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-align: center;");
fwrite($hell, "font-size: 90%;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplanung_stunde:hover .cms_stundenplanung_stundeinfo {height: auto; opacity: 1;}");

fwrite($hell, ".cms_stundenplanung_stunde:hover {opacity: 1;}");

fwrite($hell, ".cms_wochentag_rythmus, .cms_wochentag_rythmus_schulfrei,");
fwrite($hell, ".cms_wochentag_rythmus_ferien, .cms_wochentag_rythmus_leer {");
fwrite($hell, "padding: 2px 5px;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 40px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "border: 2px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_stunde_gewaehlt {");
fwrite($hell, "border: 2px dotted ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_wochentag_rythmus_leer {background: ".$_POST['cms_style_h_haupt_abstufung1']."; border-color: ".$_POST['cms_style_h_haupt_abstufung1'].";}");
fwrite($hell, ".cms_wochentag_rythmus {background: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($hell, ".cms_wochentag_rythmus_schulfrei {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
fwrite($hell, ".cms_wochentag_rythmus_ferien {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");

fwrite($hell, ".cms_stundenplan_spaltentitel .cms_notiz {color: inherit !important;}");

fwrite($hell, ".cms_vplan_geloest {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_spalte_wochenplaene, #cms_spalte_konflikte, #cms_spalte_lehrer,");
fwrite($hell, "#cms_spalte_raeume, #cms_spalte_klassen {");
fwrite($hell, "overflow: hidden !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_vplan_konflikte_liste {");
fwrite($hell, "max-height: 650px;");
fwrite($hell, "overflow: scroll;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_stundenplan_spalte_trenner {");
fwrite($hell, "border-left: 1px solid ".$_POST['cms_style_h_haupt_abstufung2']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_vplan_konfliktplan_wahl {width: 100%;}");
fwrite($hell, ".cms_vplan_konfliktplan_wahl td {width: 50%;}");

fwrite($hell, ".cms_vplan_entfall {");
fwrite($hell, "opacity: .5;");
fwrite($hell, "}");

fwrite($hell, "tr.cms_vplan_ausgewaehlt td {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, "tr.cms_vplan_ausgewaehlt:hover td {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_vplan_konfliktgrund {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_vplan_konfliktwarnung {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter']." !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_lvplan_heute {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 40%;");
fwrite($hell, "left: 0%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "}");

fwrite($hell, "#cms_lvplan_heute p, #cms_lvplan_heute td, #cms_lvplan_heute h4,");
fwrite($hell, "#cms_lvplan_morgen p, #cms_lvplan_morgen td, #cms_lvplan_morgen h4,");
fwrite($hell, "#cms_svplan_heute p, #cms_svplan_heute td, #cms_svplan_heute h4,");
fwrite($hell, "#cms_svplan_morgen p, #cms_svplan_morgen td, #cms_svplan_morgen h4,");
fwrite($hell, "#cms_lvplan_geraete p, #cms_lvplan_geraete td, #cms_lvplan_geraete h4 {font-size: 110% !important;}");
fwrite($hell, "#cms_lvplan_geraete p.cms_notiz {font-size: 80% !important;}");
fwrite($hell, "#cms_lvplan_heute h2, #cms_lvplan_morgen h2, #cms_lvplan_geraete h2,");
fwrite($hell, "#cms_svplan_heute h2, #cms_svplan_morgen h2, #cms_svplan_geraete h2 {font-size: 150% !important;}");

fwrite($hell, "#cms_lvplan_geraete {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 20%;");
fwrite($hell, "left: 40%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "}");

fwrite($hell, "#cms_lvplan_morgen {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 40%;");
fwrite($hell, "left: 60%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "}");

fwrite($hell, "#cms_svplan_heute {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 50%;");
fwrite($hell, "left: 0%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "}");

fwrite($hell, "#cms_svplan_morgen {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 50%;");
fwrite($hell, "left: 50%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "}");

fwrite($hell, "#cms_ausplanung_ausgeplant_l, #cms_ausplanung_ausgeplant_r,");
fwrite($hell, "#cms_ausplanung_ausgeplant_k, #cms_ausplanung_ausgeplant_s {");
fwrite($hell, "margin-top: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_markierte_liste_0 td {background: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($hell, ".cms_markierte_liste_1 td {background: ".$_POST['cms_style_h_haupt_abstufung1']."; border-bottom: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";}");

fwrite($hell, ".cms_vplanliste_entfall td:first-child {border-left: 4px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($hell, ".cms_vplanliste_neu td:first-child {border-left: 4px solid ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");

fwrite($hell, ".cms_auswaehlen {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_konflikte_liste_menue {");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";");
fwrite($hell, "position: absolute;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "top: 0px;");
fwrite($hell, "left: 30px;");
fwrite($hell, "padding: 3px 3px 0px 3px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "transition-delay: 1s;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_stundenplan_spalte {");
fwrite($dunkel, "border-left: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_spalte .cms_stundenplan_spaltentitel {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_zeitliniebez {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_zeitliniebeginn, .cms_stundenplan_zeitlinieende {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_zeitlinietext {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_vollbild {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenfeld_blockiert {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplanung_markiert {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_vplan_stunde_markiert {");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplanung_stunde {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_stunde {background: ".$_POST['cms_style_d_haupt_abstufung1'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_entfall {border: 2px dashed ".$_POST['cms_style_h_haupt_meldunginfoakzent']."; background: ".$_POST['cms_style_d_haupt_hintergrund'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_entfallgeaendert {border: 2px dashed ".$_POST['cms_style_h_haupt_meldungerfolgakzent']."; background: ".$_POST['cms_style_d_haupt_hintergrund'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_ausfall {color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_geloest {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_geaendert {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_konflikt {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($dunkel, ".cms_stundenplan_stunde_ueberschneidung {background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");

fwrite($dunkel, ".cms_stundenplanung_stundeinfo {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_wochentag_rythmus, .cms_wochentag_rythmus_schulfrei,");
fwrite($dunkel, ".cms_wochentag_rythmus_ferien, .cms_wochentag_rythmus_leer {");
fwrite($dunkel, "border: 2px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_stunde_gewaehlt {");
fwrite($dunkel, "border: 2px dotted ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_wochentag_rythmus_leer {background: ".$_POST['cms_style_d_haupt_abstufung1']."; border-color: ".$_POST['cms_style_d_haupt_abstufung1'].";}");
fwrite($dunkel, ".cms_wochentag_rythmus {background: ".$_POST['cms_style_d_haupt_hintergrund'].";}");
fwrite($dunkel, ".cms_wochentag_rythmus_schulfrei {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");
fwrite($dunkel, ".cms_wochentag_rythmus_ferien {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");

fwrite($dunkel, ".cms_vplan_geloest {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_stundenplan_spalte_trenner {");
fwrite($dunkel, "border-left: 1px solid ".$_POST['cms_style_d_haupt_abstufung2']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "tr.cms_vplan_ausgewaehlt td {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "tr.cms_vplan_ausgewaehlt:hover td {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_vplan_konfliktgrund {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_vplan_konfliktwarnung {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_markierte_liste_0 td {background: ".$_POST['cms_style_d_haupt_hintergrund'].";}");
fwrite($dunkel, ".cms_markierte_liste_1 td {background: ".$_POST['cms_style_d_haupt_abstufung1']."; border-bottom: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";}");

fwrite($dunkel, ".cms_vplanliste_entfall td:first-child {border-left: 4px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($dunkel, ".cms_vplanliste_neu td:first-child {border-left: 4px solid ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");

fwrite($dunkel, ".cms_konflikte_liste_menue {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";");
fwrite($dunkel, "}");
?>
