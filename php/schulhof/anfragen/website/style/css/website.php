<?php
fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_vorher {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: left;");
fwrite($hell, "width: 20%;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_jahr {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "width: 60%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_nachher {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: right;");
fwrite($hell, "width: 20%;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button,");
fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button {");
fwrite($hell, "font-weight: bold !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2']."!important;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button:hover,");
fwrite($hell, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button:hover {");
fwrite($hell, "font-weight: bold !important;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']."!important;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_allemonate_namen {");
fwrite($hell, "padding-top: 5px;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_monat {");
fwrite($hell, "width: 6.5%;");
fwrite($hell, "margin-right: 2%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "float: left;");
fwrite($hell, "display: block;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_monat.cms_letzte {");
fwrite($hell, "margin-right: 0%;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_allemonate_balken {");
fwrite($hell, "padding: 10px 0px 0px 0px;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_monathoeheF {");
fwrite($hell, "width: 6.5%;");
fwrite($hell, "margin-right: 2%;");
fwrite($hell, "float: left;");
fwrite($hell, "display: block;");
fwrite($hell, "height: 100px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_monathoeheF.cms_letzte {");
fwrite($hell, "margin-right: 0%;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_monathoehe {");
fwrite($hell, "width: 100%;");
fwrite($hell, "float: left;");
fwrite($hell, "display: block;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_zeitdiagramm_balken'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_zeitdiagramm_radiusoben'].";");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_zeitdiagramm_radiusoben'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_zeitdiagramm_radiusunten'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_zeitdiagramm_radiusunten'].";");
fwrite($hell, "bottom: 0px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahrueberischt_monathoehe.cms_jahresuebersicht_aktuell,");
fwrite($hell, ".cms_termine_jahrueberischt_monathoehe:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_zeitdiagramm_balkenhover'].";");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "cursor: pointer");
fwrite($hell, "}");

fwrite($hell, ".cms_element_editor {");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_download_anzeige {");
fwrite($hell, "padding: 5px 5px 5px 42px;");
fwrite($hell, "background-position: 5px 5px;");
fwrite($hell, "min-height: 44px;");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "line-height: 1em !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_download_anzeige p, .cms_download_anzeige h4 {");
fwrite($hell, "line-height: 1em !important;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "overflow:hidden;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "}");

fwrite($hell, ".cms_download_anzeige:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_download_anzeige:hover p, .cms_download_anzeige:hover h4 {color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";}");

fwrite($hell, ".cms_download_inaktiv {opacity: .5;}");
fwrite($hell, ".cms_download_inaktiv:hover {cursor: not-allowed;}");

fwrite($hell, ".cms_website_menuepunkte {");
fwrite($hell, "padding: 5px !important;");
fwrite($hell, "min-height: 0 !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_menuepunkte_ja {");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 5px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_menuepunkte_ja:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_menuepunkte h3:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_zugehoerig {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_zugehoerig_radius'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_zugehoerig_radius'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_zugehoerig_radius'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_zugehoerig_radius'].";");
fwrite($hell, "margin-top: 10px;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_zugehoerig h3, .cms_zugehoerig p {text-align: center !important;}");

fwrite($hell, ".cms_zugehoerig h3 img {");
fwrite($hell, "margin-right: 3px;");
fwrite($hell, "position: relative;");
fwrite($hell, "top: 2px;");
fwrite($hell, "}");

fwrite($hell, ".cms_zugehoerig table {width: 100%; padding: 0px; margin:0px;}");
fwrite($hell, ".cms_zugehoerig table td {font-size: 70%;}");

fwrite($hell, ".cms_zugehoerig td:nth-child(1) {text-align: left;}");
fwrite($hell, ".cms_zugehoerig td:nth-child(2) {text-align: center;}");
fwrite($hell, ".cms_zugehoerig td:nth-child(3) {text-align: right;}");

fwrite($hell, ".cms_zugehoerig h4 {margin-top: 10px; color: ".$_POST['cms_style_h_haupt_abstufung2']."; text-align: left; padding-left: 5px;}");
fwrite($hell, ".cms_zugehoerig ul {padding: 0px; margin: 0px;}");
fwrite($hell, ".cms_zugehoerig li {padding: 0px; margin: 0px; list-style-type: none;}");
fwrite($hell, ".cms_zugehoerig li a {");
fwrite($hell, "display: block;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "padding: 2px 5px;");
fwrite($hell, "margin-bottom: 6px;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "text-align: left;");
fwrite($hell, "}");
fwrite($hell, ".cms_zugehoerig li a:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "background: ".$_POST['cms_style_h_zugehoerig_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_zugehoerig_farbehover'].";");
fwrite($hell, "}");
fwrite($hell, ".cms_zugehoerig li:last-child a {margin-bottom: 0px;}");
fwrite($hell, ".cms_zugehoerig li a span.cms_zugehoerig_datum {");
fwrite($hell, "font-size: 70%;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_u input, .cms_box_n input {width: 100% !important;}");

fwrite($hell, ".cms_boxen_n {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_boxen_u {");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_u, .cms_box_n {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_box_n {");
fwrite($hell, "float: left;");
fwrite($hell, "margin-right: 10px;");
fwrite($hell, "margin-bottom: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_u {");
fwrite($hell, "width: 100%;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "display: flex;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_titel .cms_formular {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_u .cms_box_titel,");
fwrite($hell, ".cms_box_u .cms_box_inhalt {");
fwrite($hell, "float: left;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_n .cms_box_titel,");
fwrite($hell, ".cms_box_n .cms_box_inhalt {");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_box_titel, .cms_box_inhalt {padding: 10px;}");

fwrite($hell, ".cms_box_u .cms_box_titel {");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_box_u .cms_box_inhalt {");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_box_n .cms_box_titel {");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_box_n .cms_box_inhalt {");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_box_1 .cms_box_titel, .cms_box_1 .cms_box_inhalt {background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($hell, ".cms_box_2 .cms_box_titel, .cms_box_3 .cms_box_titel,");
fwrite($hell, ".cms_box_4 .cms_box_titel, .cms_box_5 .cms_box_titel");
fwrite($hell, "{background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";}");

fwrite($hell, ".cms_box_2 .cms_box_inhalt, .cms_box_2 {background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($hell, ".cms_box_3 .cms_box_inhalt, .cms_box_3 {background-color: ".$_POST['cms_style_h_haupt_thema1'].";}");
fwrite($hell, ".cms_box_4 .cms_box_inhalt, .cms_box_4 {background-color: ".$_POST['cms_style_h_haupt_thema2'].";}");
fwrite($hell, ".cms_box_5 .cms_box_inhalt, .cms_box_5 {background-color: ".$_POST['cms_style_h_haupt_thema3'].";}");

fwrite($hell, ".cms_box_3 .cms_box_inhalt *, .cms_box_4 .cms_box_inhalt *,");
fwrite($hell, ".cms_box_5 .cms_box_inhalt *, .cms_box_3 .cms_box_inhalt .cms_button:hover,");
fwrite($hell, ".cms_box_4 .cms_box_inhalt .cms_button:hover, .cms_box_5 .cms_box_inhalt .cms_button:hover");
fwrite($hell, "{color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";}");

fwrite($hell, ".cms_box_3 .cms_box_inhalt .cms_button, .cms_box_4 .cms_box_inhalt .cms_button,");
fwrite($hell, ".cms_box_5 .cms_box_inhalt .cms_button, .cms_box_3 .cms_box_inhalt .note-editor *,");
fwrite($hell, ".cms_box_4 .cms_box_inhalt .note-editor *, .cms_box_5 .cms_box_inhalt .note-editor *");
fwrite($hell, "{color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']."!important;}");

fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_termine,");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_t .cms_eventuebersicht_box_termine,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_t .cms_eventuebersicht_box_termine {width: 100%;}");
fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_blog,");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_b .cms_eventuebersicht_box_blog,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_b .cms_eventuebersicht_box_blog {width: 100%;}");
fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_galerien,");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_g .cms_eventuebersicht_box_galerien,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_g .cms_eventuebersicht_box_galerien {width: 100%;}");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_blog,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_blog {width: 66.66666666%;}");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_termine,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tb .cms_eventuebersicht_box_termine {width: 33.33333333%;}");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_galerien,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_galerien {width: 66.66666666%;}");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_termine,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tg .cms_eventuebersicht_box_termine {width: 33.33333333%;}");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_galerien,");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_termine,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_galerien,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_bg .cms_eventuebersicht_box_termine {width: 50%;}");

fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_blog,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_blog {width: 40%;}");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_galerien,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_galerien {width: 40%;}");
fwrite($hell, ".cms_optimierung_T .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_termine,");
fwrite($hell, ".cms_optimierung_P .cms_eventuebersicht_aussen_tbg .cms_eventuebersicht_box_termine {width: 20%;}");

fwrite($hell, ".cms_optimierung_H .cms_eventuebersicht_box_a, .cms_optimierung_T .cms_eventuebersicht_box_a {margin-bottom: 30px;}");

fwrite($hell, ".cms_eventuebersicht_box_a {float: left;}");
fwrite($hell, ".cms_eventuebersicht_box_i {");
fwrite($hell, "padding-left: 10px;");
fwrite($hell, "padding-right: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_eventuebersicht_box_a:first-child .cms_eventuebersicht_box_i {padding-left: 0px;}");
fwrite($hell, ".cms_eventuebersicht_box_a:last-child .cms_eventuebersicht_box_i {padding-right: 0px;}");

fwrite($hell, "#cms_hauptteil_i img {max-width: 100%;}");
fwrite($hell, ".cms_liste td > img, .cms_formular td > img, .cms_dateisystem_tabelle td > img, .cms_icon_klein_o > img {max-width: none !important;}");

fwrite($hell, ".cms_kopfnavigation .cms_websitesuche {");
fwrite($hell, "margin-right: 10px;");
fwrite($hell, "position: relative;");
fwrite($hell, "width: 250px;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_i .cms_websitesuche {");
fwrite($hell, "margin-right: 10px;");
fwrite($hell, "position: relative;");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_i .cms_websitesuche input {");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung2']."!important;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche input {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "padding: 2px 6px;");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-top-left-radius: 3px;");
fwrite($hell, "border-top-right-radius: 3px;");
fwrite($hell, "border-bottom-left-radius: 0px;");
fwrite($hell, "border-bottom-right-radius: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche input:hover {");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse,");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {");
fwrite($hell, "border-top: 3px solid ".$_POST['cms_style_h_kopfzeile_buttonhintergrundhover'].";");
fwrite($hell, "padding: 7px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-top-left-radius: 0px;");
fwrite($hell, "border-top-right-radius: 0px;");
fwrite($hell, "border-bottom-left-radius: 3px;");
fwrite($hell, "border-bottom-right-radius: 3px;");
fwrite($hell, "max-height: 400px;");
fwrite($hell, "overflow-y: scroll;");
fwrite($hell, "z-index: 5;");
fwrite($hell, "text-align: left;");
fwrite($hell, "display: none;");
fwrite($hell, "min-height: 35px;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse {position: absolute;}");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {");
fwrite($hell, "position: relative;");
fwrite($hell, "border-left: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche_schliessen {");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 7px;");
fwrite($hell, "top: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul,");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li,");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li {");
fwrite($hell, "margin: 0px 0px ".$_POST['cms_style_haupt_absatzschulhof']." 0px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "display: block;");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li:last-child,");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li:last-child {margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a,");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a {");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 5px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "transform: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover,");
fwrite($hell, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_kopfzeile_suchehintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "transform: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche h3 {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "padding: 0px 5px;");
fwrite($hell, "}");
fwrite($hell, ".cms_websitesuche .cms_notiz {");
fwrite($hell, "line-height: 1.2;");
fwrite($hell, "color: inherit !important;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "}");

fwrite($hell, ".cms_websitesuche p:last-child {margin:0px;}");

fwrite($hell, ".cms_optimierung_P .cms_auszeichnung,");
fwrite($hell, ".cms_optimierung_T .cms_auszeichnung {");
fwrite($hell, "text-align: right;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_auszeichnung {");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_auszeichnung li {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: ".$_POST['cms_style_auszeichnung_aussenabstand'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, ".cms_auszeichnung a {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "display: block;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_auszeichnung_radius'].";");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_auszeichnung_radius'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_auszeichnung_radius'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_auszeichnung_radius'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_auszeichnung_hintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_auszeichnung_schrift'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_auszeichnung p, .cms_auszeichnung b {");
fwrite($hell, "line-height: 1.5em !important;");
fwrite($hell, "font-size: 10px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_auszeichnung img {max-height: 150px;}");

fwrite($hell, ".cms_auszeichnung b {");
fwrite($hell, "padding: 0px !important;");
fwrite($hell, "margin: 0px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_auszeichnung a:hover {");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "background: ".$_POST['cms_style_h_auszeichnung_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_auszeichnung_schrifthover'].";");
fwrite($hell, "}");


fwrite($hell, ".cms_datenschutz_einwilligungerteilt {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_datenschutz_einwilligungverweigert {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");

fwrite($hell, ".cms_datenschutz_einwilligungerteilt, .cms_datenschutz_einwilligungverweigert {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "padding: 3px 5px 2px 5px;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_datenschutz_einwilligungerteilt:hover,");
fwrite($hell, ".cms_datenschutz_einwilligungverweigert:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_kontakt_visitenkarten {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_kontakt_visitenkarte {");
fwrite($hell, "width: 25%;");
fwrite($hell, "padding: 10px;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_kontakt_visitenkarte:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_thema2'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_jahr {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button,");
fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2']."!important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_vorher .cms_button:hover,");
fwrite($dunkel, ".cms_termine_jahrueberischt_knoepfe_nachher .cms_button:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']."!important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termine_jahrueberischt_allemonate_namen {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termine_jahrueberischt_monat {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termine_jahrueberischt_monathoehe {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_zeitdiagramm_balken'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termine_jahrueberischt_monathoehe.cms_jahresuebersicht_aktuell,");
fwrite($dunkel, ".cms_termine_jahrueberischt_monathoehe:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_zeitdiagramm_balkenhover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_download_anzeige {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_download_anzeige:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_download_anzeige:hover p, .cms_download_anzeige:hover h4 {color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";}");

fwrite($dunkel, ".cms_website_menuepunkte_ja {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_website_menuepunkte_ja:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_zugehoerig {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_zugehoerig h4 {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}");
fwrite($dunkel, ".cms_zugehoerig li a {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_zugehoerig li a:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_zugehoerig_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_zugehoerig_farbehover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_box_u, .cms_box_n {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_box_u .cms_box_titel {");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_box_n .cms_box_titel {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_box_1 .cms_box_titel, .cms_box_1 .cms_box_inhalt {background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";}");
fwrite($dunkel, ".cms_box_2 .cms_box_titel, .cms_box_3 .cms_box_titel,");
fwrite($dunkel, ".cms_box_4 .cms_box_titel, .cms_box_5 .cms_box_titel");
fwrite($dunkel, "{background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";}");

fwrite($dunkel, ".cms_box_2 .cms_box_inhalt, .cms_box_2 {background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";}");

fwrite($dunkel, ".cms_box_3 .cms_box_inhalt *, .cms_box_4 .cms_box_inhalt *,");
fwrite($dunkel, ".cms_box_5 .cms_box_inhalt *, .cms_box_3 .cms_box_inhalt .cms_button:hover,");
fwrite($dunkel, ".cms_box_4 .cms_box_inhalt .cms_button:hover, .cms_box_5 .cms_box_inhalt .cms_button:hover");
fwrite($dunkel, "{color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";}");

fwrite($dunkel, ".cms_box_3 .cms_box_inhalt .cms_button, .cms_box_4 .cms_box_inhalt .cms_button,");
fwrite($dunkel, ".cms_box_5 .cms_box_inhalt .cms_button, .cms_box_3 .cms_box_inhalt .note-editor *,");
fwrite($dunkel, ".cms_box_4 .cms_box_inhalt .note-editor *, .cms_box_5 .cms_box_inhalt .note-editor *");
fwrite($dunkel, "{color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']."!important;}");

fwrite($dunkel, "#cms_mobilmenue_i .cms_websitesuche input {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung2']."!important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche input {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche input:hover {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse,");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {");
fwrite($dunkel, "border-top: 3px solid ".$_POST['cms_style_d_kopfzeile_buttonhintergrundhover'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse {position: absolute;}");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse {");
fwrite($dunkel, "position: relative;");
fwrite($dunkel, "border-left: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a,");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover,");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kopfzeile_suchehintergrundhover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_pc_ergebnisse ul li a:hover p,");
fwrite($dunkel, ".cms_websitesuche #cms_websitesuche_mobil_ergebnisse ul li a:hover p {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_websitesuche h3 {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_auszeichnung a {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_auszeichnung_hintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_auszeichnung_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_auszeichnung a:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_auszeichnung_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_auszeichnung_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_datenschutz_einwilligungerteilt {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_datenschutz_einwilligungverweigert {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");

fwrite($dunkel, ".cms_datenschutz_einwilligungerteilt, .cms_datenschutz_einwilligungverweigert {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kontakt_visitenkarte {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kontakt_visitenkarte:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_thema2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");
?>
