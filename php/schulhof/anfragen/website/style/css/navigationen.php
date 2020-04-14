<?php
fwrite($hell, ".cms_kopfnavigation {");
fwrite($hell, "text-align: right;");
fwrite($hell, "}");

fwrite($hell, ".cms_kopfnavigation li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: ".$_POST['cms_style_hauptnavigation_anzeigekategorie'].";");
fwrite($hell, "margin-left: 3px;");
fwrite($hell, "margin-right: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_kopfnavigation li:first-child {");
fwrite($hell, "display: ".$_POST['cms_style_d_suche_anzeige'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_fussnavigation, .cms_optimierung_T .cms_fussnavigation {");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_fussnavigation {");
fwrite($hell, "padding-right: 0px;");
fwrite($hell, "min-height: auto;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_auszeichnung {");
fwrite($hell, "position: static !important;");
fwrite($hell, "top: auto !important;");
fwrite($hell, "right: auto !important;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof']." !important;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H #cms_fusszeile_i .cms_notiz {text-align: center;}");

fwrite($hell, ".cms_fussnavigation li {");
fwrite($hell, "list-style: none;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-right: 3px;");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptnavigation {");
fwrite($hell, "text-align: right;");
fwrite($hell, "position: absolute;");
fwrite($hell, "bottom: ".$_POST['cms_style_hauptnavigation_abstandvonunten'].";");
fwrite($hell, "top: ".$_POST['cms_style_hauptnavigation_abstandvonoben'].";");
fwrite($hell, "left: ".$_POST['cms_style_hauptnavigation_abstandvonlinks'].";");
fwrite($hell, "right: ".$_POST['cms_style_hauptnavigation_abstandvonrechts'].";");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptnavigation > li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin: ".$_POST['cms_style_hauptnavigation_aussenabstandkategorie'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptnavigation .cms_kategorie1,");
fwrite($hell, "#cms_kopfnavigation > li > a, #cms_kopfnavigation > li > span {");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusor'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusol'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusur'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusul'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_hauptnavigation_kategoriehintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_hauptnavigation_kategoriefarbe'].";");
fwrite($hell, "padding: ".$_POST['cms_style_hauptnavigation_kategorieinnenabstand'].";");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "line-height: 20px;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "user-select: none;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_o {");
fwrite($hell, "position: fixed;");
fwrite($hell, "top: ".$_POST['cms_style_d_unternavigation_abstandvonoben'].";");
fwrite($hell, "left: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "z-index: 21;");
fwrite($hell, "max-height: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptnavigation > li:hover .cms_unternavigation_o {");
fwrite($hell, "max-height: 500px;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation a:hover,");
fwrite($hell, "#cms_kopfnavigation span:hover {");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_hauptnavigation > li:hover > span.cms_kategorie1,");
fwrite($hell, "#cms_kopfnavigation li:hover > a,");
fwrite($hell, "#cms_kopfnavigation li:hover > span {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_hauptnavigation_kategoriehintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_hauptnavigation_kategoriefarbehover'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation > li {");
fwrite($hell, "position: relative;");
fwrite($hell, "height: ".$_POST['cms_style_hauptnavigation_kategoriehoehe'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation span {");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation span:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation > li > .cms_naviuntermenue {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "width: 200px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: ".$_POST['cms_style_schulhofnavigation_abstandvonrechts'].";");
fwrite($hell, "left: ".$_POST['cms_style_schulhofnavigation_abstandvonlinks'].";");
fwrite($hell, "top: ".$_POST['cms_style_schulhofnavigation_abstandvonoben'].";");
fwrite($hell, "bottom: ".$_POST['cms_style_schulhofnavigation_abstandvonunten'].";");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "max-height: 0px;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation > li ul {");
fwrite($hell, "border-top: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";");
fwrite($hell, "border-bottom: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation li:hover > .cms_naviuntermenue {");
fwrite($hell, "max-height: 600px;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation > li ul li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: left;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: ".$_POST['cms_style_haupt_absatzschulhof']." 0px ".$_POST['cms_style_haupt_absatzschulhof']." 0px;");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation > li ul li a,");
fwrite($hell, "#cms_kopfnavigation > li ul li span {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 3px 7px;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_kopfnavigation > li ul li a:hover,");
fwrite($hell, "#cms_kopfnavigation > li ul li span:hover {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_m {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-bottom: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";");
fwrite($hell, "border-top: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";");
fwrite($hell, "width: ".$_POST['cms_style_haupt_seitenbreite'].";");
fwrite($hell, "margin: 0 auto;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_i {");
fwrite($hell, "text-align: left;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_i li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation, .cms_navigation ul {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation li {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li {");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li:last-child {");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li a,");
fwrite($hell, ".cms_navigation > li span {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 10px 5px;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li a:hover,");
fwrite($hell, ".cms_navigation > li span:hover {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li > .cms_navigation_aktiveseite {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li > .cms_navigation_aktiveseite:hover {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation > li li .cms_navigation_aktiveseite {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation .cms_naviuntermenue {");
fwrite($hell, "margin-left: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_navigation .cms_naviuntermenue a,");
fwrite($hell, ".cms_navigation .cms_naviuntermenue span {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "font-size: 12px;");
fwrite($hell, "}");

fwrite($hell, "#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {");
fwrite($hell, "width: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "transition: 100ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, "#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {");
fwrite($hell, "height: 5px;");
fwrite($hell, "}");

fwrite($hell, "#cms_aktivitaet_in_profil, .cms_fortschritt_i {");
fwrite($hell, "height: 10px;");
fwrite($hell, "}");

fwrite($hell, "#cms_aktivitaet_out, #cms_maktivitaet_out, #cms_aktivitaet_out_profil, .cms_fortschritt_o {");
fwrite($hell, "width: 100%;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "overflow:hidden;");
fwrite($hell, "}");

fwrite($hell, "#cms_aktivitaet_out, #cms_maktivitaet_out {");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P #cms_mobilnavigation {display: none;}");
fwrite($hell, ".cms_optimierung_T #cms_hauptnavigation, .cms_optimierung_H #cms_hauptnavigation,");
fwrite($hell, ".cms_optimierung_T #cms_kopfnavigation, .cms_optimierung_H #cms_kopfnavigation");
fwrite($hell, "{display: none !important;}");

fwrite($hell, "#cms_mobilnavigation {");
fwrite($hell, "border-top-right-radius: 5px;");
fwrite($hell, "border-top-left-radius: 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrund']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "padding: 4px 10px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "line-height: 20px;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "user-select: none;");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 10px;");
fwrite($hell, "bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilnavigation:hover {");
fwrite($hell, "cursor: pointer !important;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrundhover']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_menuicon {");
fwrite($hell, "width: 20px;");
fwrite($hell, "height: 4px;");
fwrite($hell, "border-radius: 2px;");
fwrite($hell, "background-color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "margin: 4px 0;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_a {");
fwrite($hell, "/*display: none;*/");
fwrite($hell, "position: fixed;");
fwrite($hell, "left: 0px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "z-index: 100000;");
fwrite($hell, "overflow-y: scroll;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_i {padding: 10px 10px 20px 10px;}");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe span, #cms_mobilmenue_i p.cms_mobilmenue_knoepfe a {");
fwrite($hell, "width: 50%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe span,");
fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe a:last-child {");
fwrite($hell, "border-top-left-radius: 0px;");
fwrite($hell, "border-bottom-left-radius: 0px;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe a:first-child {");
fwrite($hell, "border-top-right-radius: 0px;");
fwrite($hell, "border-bottom-right-radius: 0px;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten p.cms_notiz {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten {");
fwrite($hell, "margin-top: 30px;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten > div div {");
fwrite($hell, "padding-left: 10px;");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten ul {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten > div > ul > li {border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";}");
fwrite($hell, "#cms_mobilmenue_seiten > div > ul > li:last-child {border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";}");

fwrite($hell, "#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li .cms_meldezahl {");
fwrite($hell, "position: absolute;");
fwrite($hell, "padding: 3px 5px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border-radius: 10px;");
fwrite($hell, "opacity: 1;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "display: block;");
fwrite($hell, "top: 3px;");
fwrite($hell, "right: 4px;");
fwrite($hell, "height: 18px;");
fwrite($hell, "line-height: 12px;");
fwrite($hell, "width: auto;");
fwrite($hell, "min-width: 30px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li .cms_meldezahl_wichtig {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent']." !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {");
fwrite($hell, "transform: translate(0px) !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {width: 90%;}");
fwrite($hell, "#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen {");
fwrite($hell, "width: 10%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrundhover'].";");
fwrite($hell, "}");
fwrite($hell, "#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_mobilmenue_seiten li .cms_mobilnavi_passiv {");
fwrite($hell, "cursor: default !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "background-color: transparent !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_schliessen {");
fwrite($hell, "position: absolute !important;");
fwrite($hell, "right: 10px;");
fwrite($hell, "top: 5px;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_aktionen_liste li {");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht {");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "margin-right: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li a,");
fwrite($hell, ".cms_uebersicht li span.cms_appmenue_element,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_notifikation,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin,");
fwrite($hell, ".cms_uebersicht li span.cms_blogeintrag,");
fwrite($hell, ".cms_uebersicht li span.cms_beschlusseintrag,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_vertretungsplanung {");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 5px 5px 5px 47px;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "background-position: 5px 5px;");
fwrite($hell, "min-height: 42px;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li a.cms_blogvorschau_ohneicon {");
fwrite($hell, "padding: 5px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li:last-child a,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_appmenue_element,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_notifikation,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_termin,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_blogeintrag,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_beschlusseintrag,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_gremien,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_fachschaften,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_ferien,");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_vertretungsplanung {");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li a p,");
fwrite($hell, ".cms_uebersicht li span.cms_appmenue_element p,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_notifikation p,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin p,");
fwrite($hell, ".cms_uebersicht li span.cms_blogeintrag p,");
fwrite($hell, ".cms_uebersicht li span.cms_beschlusseintrag p,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien p,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften p,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien p,");
fwrite($hell, ".cms_uebersicht .cms_blogliste_details p {");
fwrite($hell, "font-size: 90%;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li a h3,");
fwrite($hell, ".cms_uebersicht li span.cms_appmenue_element h3,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_notifikation h3,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin h3,");
fwrite($hell, ".cms_uebersicht li span.cms_blogeintrag h3,");
fwrite($hell, ".cms_uebersicht li span.cms_beschlusseintrag h3,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien h3,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften h3,");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien h3,");
fwrite($hell, ".cms_uebersicht .cms_blogliste_details h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht_notifikation .cms_notifikation_schliessen {");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 5px !important;");
fwrite($hell, "top: 5px !important;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht_notifikation:hover .cms_notifikation_schliessen {");
fwrite($hell, "opacity: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht li {");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht a, .cms_uebersicht span.cms_appmenue_element {");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht a:hover, .cms_uebersicht span.cms_appmenue_element:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "transform: translateX(0) translateY(0);");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht .cms_blog_keinhover {");
fwrite($hell, "cursor: default !important;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund']." !important;");
fwrite($hell, "}");


fwrite($hell, ".cms_anteilbalken_innen {");
fwrite($hell, "width: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "transition: 100ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_anteilbalken_innen {");
fwrite($hell, "height: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_anteilbalken_aussen {");
fwrite($hell, "width: 100%;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_anteilbalken_notiz {");
fwrite($hell, "text-align: center;");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "font-size: 70%;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");







// DARKMODE
fwrite($dunkel, ".cms_hauptnavigation .cms_kategorie1,");
fwrite($dunkel, "#cms_kopfnavigation > li > a, #cms_kopfnavigation > li > span {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_hauptnavigation_kategoriehintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_hauptnavigation_kategoriefarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_hauptnavigation > li:hover > span.cms_kategorie1,");
fwrite($dunkel, "#cms_kopfnavigation li:hover > a,");
fwrite($dunkel, "#cms_kopfnavigation li:hover > span {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_hauptnavigation_kategoriehintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_hauptnavigation_kategoriefarbehover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_kopfnavigation > li > .cms_naviuntermenue {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_kopfnavigation > li ul {");
fwrite($dunkel, "border-top: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";");
fwrite($dunkel, "border-bottom: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_kopfnavigation > li ul li a,");
fwrite($dunkel, "#cms_kopfnavigation > li ul li span {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_kopfnavigation > li ul li a:hover,");
fwrite($dunkel, "#cms_kopfnavigation > li ul li span:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "cursor: pointer;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_unternavigation_m {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-bottom: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";");
fwrite($dunkel, "border-top: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li:last-child {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li a,");
fwrite($dunkel, ".cms_navigation > li span {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li a:hover,");
fwrite($dunkel, ".cms_navigation > li span:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li > .cms_navigation_aktiveseite {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li > .cms_navigation_aktiveseite:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_navigation > li li .cms_navigation_aktiveseite {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_aktivitaet_out, #cms_maktivitaet_out, #cms_aktivitaet_out_profil, .cms_fortschritt_o {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_aktivitaet_out, #cms_maktivitaet_out {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilnavigation {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrund']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilnavigation:hover {");
fwrite($dunkel, "cursor: pointer !important;");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrundhover']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_menuicon {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_a {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_seiten > div > ul > li {border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";}");
fwrite($dunkel, "#cms_mobilmenue_seiten > div > ul > li:last-child {border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";}");

fwrite($dunkel, "#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_seiten li .cms_meldezahl {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_seiten li .cms_meldezahl_wichtig {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrundhover'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_mobilmenue_seiten li .cms_mobilnavi_passiv {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_uebersicht li a,");
fwrite($dunkel, ".cms_uebersicht li span.cms_appmenue_element,");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_notifikation,");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin,");
fwrite($dunkel, ".cms_uebersicht li span.cms_blogeintrag,");
fwrite($dunkel, ".cms_uebersicht li span.cms_beschlusseintrag,");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien,");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften,");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien,");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_vertretungsplanung {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_uebersicht li:last-child a,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_appmenue_element,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_notifikation,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_termin,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_blogeintrag,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_beschlusseintrag,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_gremien,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_fachschaften,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_ferien,");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_vertretungsplanung {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_uebersicht a:hover, .cms_uebersicht span.cms_appmenue_element:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_uebersicht .cms_blog_keinhover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_anteilbalken_innen {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_anteilbalken_aussen {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_anteilbalken_notiz {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");
?>
