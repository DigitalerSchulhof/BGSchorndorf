<?php
fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_vorher {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "width: 20%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_jahr {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "width: 60%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_nachher {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "width: 20%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button,\n");
fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button {\n");
fwrite($hell, "font-weight: bold !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2']."!important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button:hover,\n");
fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button:hover {\n");
fwrite($hell, "font-weight: bold !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']."!important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_allemonate_namen {\n");
fwrite($hell, "padding-top: 5px;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_monat {\n");
fwrite($hell, "width: 6.5%;\n");
fwrite($hell, "margin-right: 2%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_monat.cms_letzte {\n");
fwrite($hell, "margin-right: 0%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_allemonate_balken {\n");
fwrite($hell, "padding: 10px 0px 0px 0px;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_monathoeheF {\n");
fwrite($hell, "width: 6.5%;\n");
fwrite($hell, "margin-right: 2%;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "height: 100px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_monathoeheF.cms_letzte {\n");
fwrite($hell, "margin-right: 0%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_monathoehe {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_zeitdiagramm_balken'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_zeitdiagramm_radiusoben'].";\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_zeitdiagramm_radiusoben'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_zeitdiagramm_radiusunten'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_zeitdiagramm_radiusunten'].";\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahrueberischt_monathoehe.cms_jahresuebersicht_aktuell,\n");
fwrite($hell, ".cms_termine_jahrueberischt_monathoehe:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_zeitdiagramm_balkenhover'].";\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "cursor: pointer\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_element_editor {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_download_anzeige {\n");
fwrite($hell, "padding: 5px 5px 5px 42px;\n");
fwrite($hell, "background-position: 5px 5px;\n");
fwrite($hell, "min-height: 44px;\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "line-height: 1em !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_download_anzeige p, .cms_download_anzeige h4 {\n");
fwrite($hell, "line-height: 1em !important;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "overflow:hidden;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_download_anzeige:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_download_anzeige:hover p, .cms_download_anzeige:hover h4 {color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";}\n");

fwrite($hell, ".cms_download_inaktiv {opacity: .5;}\n");
fwrite($hell, ".cms_download_inaktiv:hover {cursor: not-allowed;}\n");

fwrite($hell, ".cms_website_menuepunkte {\n");
fwrite($hell, "padding: 5px !important;\n");
fwrite($hell, "min-height: 0 !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_menuepunkte_ja {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_menuepunkte_ja:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_menuepunkte h3:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_zugehoerig {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_zugehoerig_radius'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_zugehoerig_radius'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_zugehoerig_radius'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_zugehoerig_radius'].";\n");
fwrite($hell, "margin-top: 10px;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_zugehoerig h3, .cms_zugehoerig p {text-align: center !important;}\n");

fwrite($hell, ".cms_zugehoerig h3 img {\n");
fwrite($hell, "margin-right: 3px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "top: 2px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_zugehoerig table {width: 100%; padding: 0px; margin:0px;}\n");
fwrite($hell, ".cms_zugehoerig table td {font-size: 70%;}\n");

fwrite($hell, ".cms_zugehoerig td:nth-child(1) {text-align: left;}\n");
fwrite($hell, ".cms_zugehoerig td:nth-child(2) {text-align: center;}\n");
fwrite($hell, ".cms_zugehoerig td:nth-child(3) {text-align: right;}\n");

fwrite($hell, ".cms_zugehoerig h4 {margin-top: 10px; color: ".$_POST['cms_style_h_haupt_abstufung2']."; text-align: left; padding-left: 5px;}\n");
fwrite($hell, ".cms_zugehoerig ul {padding: 0px; margin: 0px;}\n");
fwrite($hell, ".cms_zugehoerig li {padding: 0px; margin: 0px; list-style-type: none;}\n");
fwrite($hell, ".cms_zugehoerig li a {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "padding: 2px 5px;\n");
fwrite($hell, "margin-bottom: 6px;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_zugehoerig li a:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_zugehoerig_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_zugehoerig_farbehover'].";\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_zugehoerig li:last-child a {margin-bottom: 0px;}\n");
fwrite($hell, ".cms_zugehoerig li a span.cms_zugehoerig_datum {\n");
fwrite($hell, "font-size: 70%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_u input, .cms_box_n input {width: 100% !important;}\n");

fwrite($hell, ".cms_boxen_n {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_boxen_u {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_u, .cms_box_n {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_n {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "margin-right: 10px;\n");
fwrite($hell, "margin-bottom: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_u {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_titel .cms_formular {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_u .cms_box_titel,\n");
fwrite($hell, ".cms_box_u .cms_box_inhalt {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_n .cms_box_titel,\n");
fwrite($hell, ".cms_box_n .cms_box_inhalt {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_titel, .cms_box_inhalt {padding: 10px;}\n");

fwrite($hell, ".cms_box_u .cms_box_titel {\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_u .cms_box_inhalt {\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_n .cms_box_titel {\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_n .cms_box_inhalt {\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_box_1 .cms_box_titel, .cms_box_1 .cms_box_inhalt {background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($hell, ".cms_box_2 .cms_box_titel, .cms_box_3 .cms_box_titel,\n");
fwrite($hell, ".cms_box_4 .cms_box_titel, .cms_box_5 .cms_box_titel\n");
fwrite($hell, "{background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";}\n");

fwrite($hell, ".cms_box_2 .cms_box_inhalt, .cms_box_2 {background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($hell, ".cms_box_3 .cms_box_inhalt, .cms_box_3 {background-color: ".$_POST['cms_style_h_haupt_thema1'].";}\n");
fwrite($hell, ".cms_box_4 .cms_box_inhalt, .cms_box_4 {background-color: ".$_POST['cms_style_h_haupt_thema2'].";}\n");
fwrite($hell, ".cms_box_5 .cms_box_inhalt, .cms_box_5 {background-color: ".$_POST['cms_style_h_haupt_thema3'].";}\n");

fwrite($hell, ".cms_box_3 .cms_box_inhalt *, .cms_box_4 .cms_box_inhalt *,\n");
fwrite($hell, ".cms_box_5 .cms_box_inhalt *, .cms_box_3 .cms_box_inhalt .cms_button:hover,\n");
fwrite($hell, ".cms_box_4 .cms_box_inhalt .cms_button:hover, .cms_box_5 .cms_box_inhalt .cms_button:hover\n");
fwrite($hell, "{color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";}\n");

fwrite($hell, ".cms_box_3 .cms_box_inhalt .cms_button, .cms_box_4 .cms_box_inhalt .cms_button,\n");
fwrite($hell, ".cms_box_5 .cms_box_inhalt .cms_button, .cms_box_3 .cms_box_inhalt .note-editor *,\n");
fwrite($hell, ".cms_box_4 .cms_box_inhalt .note-editor *, .cms_box_5 .cms_box_inhalt .note-editor *\n");
fwrite($hell, "{color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']."!important;}\n");

fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_t .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_t .cms_eventuebersicht_box_termine {width: 100%;}\n");
fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_blog,\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_b .cms_eventuebersicht_box_blog,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_b .cms_eventuebersicht_box_blog {width: 100%;}\n");
fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_galerien,\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_g .cms_eventuebersicht_box_galerien,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_g .cms_eventuebersicht_box_galerien {width: 100%;}\n");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_blog,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_blog {width: 66.66666666%;}\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_termine {width: 33.33333333%;}\n");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_galerien,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_galerien {width: 66.66666666%;}\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_termine {width: 33.33333333%;}\n");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_galerien,\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_galerien,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_termine {width: 50%;}\n");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_blog,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_blog {width: 40%;}\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_galerien,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_galerien {width: 40%;}\n");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_termine {width: 20%;}\n");

fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_a, .cms_optimierung_T .cms_eventuebersicht_box_a {margin-bottom: 30px;}\n");

fwrite($hell, ".cms_eventuebersicht_box_a {float: left;}\n");
fwrite($hell, ".cms_eventuebersicht_box_i {\n");
fwrite($hell, "padding-left: 10px;\n");
fwrite($hell, "padding-right: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_eventuebersicht_box_a:first-child .cms_eventuebersicht_box_i {padding-left: 0px;}\n");
fwrite($hell, ".cms_eventuebersicht_box_a:last-child .cms_eventuebersicht_box_i {padding-right: 0px;}\n");

fwrite($hell, "#cms_hauptteil_i img {max-width: 100%;}\n");
fwrite($hell, ".cms_liste td > img, .cms_formular td > img, .cms_dateisystem_tabelle td > img, .cms_icon_klein_o > img {max-width: none !important;}\n");

fwrite($hell, ".cms_kopfnavigation .cms_websitesuche {\n");
fwrite($hell, "margin-right: 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "width: 250px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_i .cms_websitesuche {\n");
fwrite($hell, "margin-right: 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_i .cms_websitesuche input {\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung2']."!important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche input {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "padding: 2px 6px;\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-top-left-radius: 3px;\n");
fwrite($hell, "border-top-right-radius: 3px;\n");
fwrite($hell, "border-bottom-left-radius: 0px;\n");
fwrite($hell, "border-bottom-right-radius: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche input:hover {\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse,\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {\n");
fwrite($hell, "border-top: 3px solid ".$_POST['cms_style_h_kopfzeile_buttonhintergrundhover'].";\n");
fwrite($hell, "padding: 7px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-top-left-radius: 0px;\n");
fwrite($hell, "border-top-right-radius: 0px;\n");
fwrite($hell, "border-bottom-left-radius: 3px;\n");
fwrite($hell, "border-bottom-right-radius: 3px;\n");
fwrite($hell, "max-height: 400px;\n");
fwrite($hell, "overflow-y: scroll;\n");
fwrite($hell, "z-index: 5;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "min-height: 35px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse {position: absolute;}\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "border-left: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche_schliessen {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 7px;\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul,\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li,\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li {\n");
fwrite($hell, "margin: 0px 0px ".$_POST['cms_style_haupt_absatzschulhof']." 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li:last-child,\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li:last-child {margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a,\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover,\n");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kopfzeile_suchehintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "transition: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche h3 {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "padding: 0px 5px;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_websitesuche .cms_notiz {\n");
fwrite($hell, "line-height: 1.2;\n");
fwrite($hell, "color: inherit !important;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_websitesuche p:last-child {margin:0px;}\n");

fwrite($hell, ".cms_optimierung_P .cms_auszeichnung,\n");
fwrite($hell, ".cms_optimierung_T .cms_auszeichnung {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_auszeichnung {\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auszeichnung li {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: ".$_POST['cms_style_auszeichnung_aussenabstand'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auszeichnung a {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_auszeichnung_radius'].";\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_auszeichnung_radius'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_auszeichnung_radius'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_auszeichnung_radius'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_auszeichnung_hintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_auszeichnung_schrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auszeichnung p, .cms_auszeichnung b {\n");
fwrite($hell, "line-height: 1.5em !important;\n");
fwrite($hell, "font-size: 10px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auszeichnung img {max-height: 150px;}\n");

fwrite($hell, ".cms_auszeichnung b {\n");
fwrite($hell, "padding: 0px !important;\n");
fwrite($hell, "margin: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auszeichnung a:hover {\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_auszeichnung_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_auszeichnung_schrifthover'].";\n");
fwrite($hell, "}\n");


fwrite($hell, ".cms_datenschutz_einwilligungerteilt {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_datenschutz_einwilligungverweigert {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");

fwrite($hell, ".cms_datenschutz_einwilligungerteilt, .cms_datenschutz_einwilligungverweigert {\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "padding: 3px 5px 2px 5px;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_datenschutz_einwilligungerteilt:hover,\n");
fwrite($hell, ".cms_datenschutz_einwilligungverweigert:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kontakt_visitenkarten {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kontakt_visitenkarte {\n");
fwrite($hell, "width: 25%;\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kontakt_visitenkarte:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_thema2'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_jahr {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button,\n");
fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2']."!important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button:hover,\n");
fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']."!important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termine_jahrueberischt_allemonate_namen {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termine_jahrueberischt_monat {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termine_jahrueberischt_monathoehe {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_zeitdiagramm_balken'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termine_jahrueberischt_monathoehe.cms_jahresuebersicht_aktuell,\n");
fwrite($dunkel, ".cms_termine_jahrueberischt_monathoehe:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_zeitdiagramm_balkenhover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_download_anzeige {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_download_anzeige:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_download_anzeige:hover p, .cms_download_anzeige:hover h4 {color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";}\n");

fwrite($dunkel, ".cms_website_menuepunkte_ja {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_website_menuepunkte_ja:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_zugehoerig {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_zugehoerig h4 {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");
fwrite($dunkel, ".cms_zugehoerig li a {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_zugehoerig li a:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_zugehoerig_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_zugehoerig_farbehover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_box_u, .cms_box_n {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_box_u .cms_box_titel {\n");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_box_n .cms_box_titel {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_box_1 .cms_box_titel, .cms_box_1 .cms_box_inhalt {background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");
fwrite($dunkel, ".cms_box_2 .cms_box_titel, .cms_box_3 .cms_box_titel,\n");
fwrite($dunkel, ".cms_box_4 .cms_box_titel, .cms_box_5 .cms_box_titel\n");
fwrite($dunkel, "{background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";}\n");

fwrite($dunkel, ".cms_box_2 .cms_box_inhalt, .cms_box_2 {background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";}\n");

fwrite($dunkel, ".cms_box_3 .cms_box_inhalt *, .cms_box_4 .cms_box_inhalt *,\n");
fwrite($dunkel, ".cms_box_5 .cms_box_inhalt *, .cms_box_3 .cms_box_inhalt .cms_button:hover,\n");
fwrite($dunkel, ".cms_box_4 .cms_box_inhalt .cms_button:hover, .cms_box_5 .cms_box_inhalt .cms_button:hover\n");
fwrite($dunkel, "{color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";}\n");

fwrite($dunkel, ".cms_box_3 .cms_box_inhalt .cms_button, .cms_box_4 .cms_box_inhalt .cms_button,\n");
fwrite($dunkel, ".cms_box_5 .cms_box_inhalt .cms_button, .cms_box_3 .cms_box_inhalt .note-editor *,\n");
fwrite($dunkel, ".cms_box_4 .cms_box_inhalt .note-editor *, .cms_box_5 .cms_box_inhalt .note-editor *\n");
fwrite($dunkel, "{color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']."!important;}\n");

fwrite($dunkel, "#cms_mobilmenue_i .cms_websitesuche input {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung2']."!important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche input {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche input:hover {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse,\n");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {\n");
fwrite($dunkel, "border-top: 3px solid ".$_POST['cms_style_d_kopfzeile_buttonhintergrundhover'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {\n");
fwrite($dunkel, "border-left: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a,\n");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover,\n");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kopfzeile_suchehintergrundhover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover p,\n");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover p {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_websitesuche h3 {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_auszeichnung a {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_auszeichnung_hintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_auszeichnung_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_auszeichnung a:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_auszeichnung_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_auszeichnung_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_datenschutz_einwilligungerteilt {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_datenschutz_einwilligungverweigert {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");

fwrite($dunkel, ".cms_datenschutz_einwilligungerteilt, .cms_datenschutz_einwilligungverweigert {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kontakt_visitenkarte {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kontakt_visitenkarte:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_thema2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");
?>
