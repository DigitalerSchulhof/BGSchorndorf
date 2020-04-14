<?php
fwrite($hell, ".cms_kopfnavigation {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kopfnavigation li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: ".$_POST['cms_style_hauptnavigation_anzeigekategorie'].";\n");
fwrite($hell, "margin-left: 3px;\n");
fwrite($hell, "margin-right: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kopfnavigation li:first-child {\n");
fwrite($hell, "display: ".$_POST['cms_style_d_suche_anzeige'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_fussnavigation, .cms_optimierung_T .cms_fussnavigation {\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_fussnavigation {\n");
fwrite($hell, "padding-right: 0px;\n");
fwrite($hell, "min-height: auto;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_auszeichnung {\n");
fwrite($hell, "position: static !important;\n");
fwrite($hell, "top: auto !important;\n");
fwrite($hell, "right: auto !important;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof']." !important;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H #cms_fusszeile_i .cms_notiz {text-align: center;}\n");

fwrite($hell, ".cms_fussnavigation li {\n");
fwrite($hell, "list-style: none;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-right: 3px;\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptnavigation {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "bottom: ".$_POST['cms_style_hauptnavigation_abstandvonunten'].";\n");
fwrite($hell, "top: ".$_POST['cms_style_hauptnavigation_abstandvonoben'].";\n");
fwrite($hell, "left: ".$_POST['cms_style_hauptnavigation_abstandvonlinks'].";\n");
fwrite($hell, "right: ".$_POST['cms_style_hauptnavigation_abstandvonrechts'].";\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptnavigation > li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin: ".$_POST['cms_style_hauptnavigation_aussenabstandkategorie'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptnavigation .cms_kategorie1,\n");
fwrite($hell, "#cms_kopfnavigation > li > a, #cms_kopfnavigation > li > span {\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusor'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusol'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusur'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_hauptnavigation_kategorieradiusul'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_hauptnavigation_kategoriehintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_hauptnavigation_kategoriefarbe'].";\n");
fwrite($hell, "padding: ".$_POST['cms_style_hauptnavigation_kategorieinnenabstand'].";\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "line-height: 20px;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "user-select: none;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_o {\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "top: ".$_POST['cms_style_d_unternavigation_abstandvonoben'].";\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "z-index: 21;\n");
fwrite($hell, "max-height: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptnavigation > li:hover .cms_unternavigation_o {\n");
fwrite($hell, "max-height: 500px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation a:hover,\n");
fwrite($hell, "#cms_kopfnavigation span:hover {\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_hauptnavigation > li:hover > span.cms_kategorie1,\n");
fwrite($hell, "#cms_kopfnavigation li:hover > a,\n");
fwrite($hell, "#cms_kopfnavigation li:hover > span {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_hauptnavigation_kategoriehintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_hauptnavigation_kategoriefarbehover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation > li {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "height: ".$_POST['cms_style_hauptnavigation_kategoriehoehe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation span {\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation span:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation > li > .cms_naviuntermenue {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "width: 200px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: ".$_POST['cms_style_schulhofnavigation_abstandvonrechts'].";\n");
fwrite($hell, "left: ".$_POST['cms_style_schulhofnavigation_abstandvonlinks'].";\n");
fwrite($hell, "top: ".$_POST['cms_style_schulhofnavigation_abstandvonoben'].";\n");
fwrite($hell, "bottom: ".$_POST['cms_style_schulhofnavigation_abstandvonunten'].";\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "max-height: 0px;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation > li ul {\n");
fwrite($hell, "border-top: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";\n");
fwrite($hell, "border-bottom: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation li:hover > .cms_naviuntermenue {\n");
fwrite($hell, "max-height: 600px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation > li ul li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: ".$_POST['cms_style_haupt_absatzschulhof']." 0px ".$_POST['cms_style_haupt_absatzschulhof']." 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation > li ul li a,\n");
fwrite($hell, "#cms_kopfnavigation > li ul li span {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 3px 7px;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kopfnavigation > li ul li a:hover,\n");
fwrite($hell, "#cms_kopfnavigation > li ul li span:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_m {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-bottom: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";\n");
fwrite($hell, "border-top: 3px solid ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";\n");
fwrite($hell, "width: ".$_POST['cms_style_haupt_seitenbreite'].";\n");
fwrite($hell, "margin: 0 auto;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_i {\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_i li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation, .cms_navigation ul {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation li {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li {\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li:last-child {\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li a,\n");
fwrite($hell, ".cms_navigation > li span {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 10px 5px;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li a:hover,\n");
fwrite($hell, ".cms_navigation > li span:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li > .cms_navigation_aktiveseite {\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li > .cms_navigation_aktiveseite:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hauptnavigation_akzentfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation > li li .cms_navigation_aktiveseite {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation .cms_naviuntermenue {\n");
fwrite($hell, "margin-left: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_navigation .cms_naviuntermenue a,\n");
fwrite($hell, ".cms_navigation .cms_naviuntermenue span {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "font-size: 12px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "transition: 100ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {\n");
fwrite($hell, "height: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktivitaet_in_profil, .cms_fortschritt_i {\n");
fwrite($hell, "height: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktivitaet_out, #cms_maktivitaet_out, #cms_aktivitaet_out_profil, .cms_fortschritt_o {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "overflow:hidden;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktivitaet_out, #cms_maktivitaet_out {\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P #cms_mobilnavigation {display: none;}\n");
fwrite($hell, ".cms_optimierung_T #cms_hauptnavigation, .cms_optimierung_H #cms_hauptnavigation,\n");
fwrite($hell, ".cms_optimierung_T #cms_kopfnavigation, .cms_optimierung_H #cms_kopfnavigation\n");
fwrite($hell, "{display: none !important;}\n");

fwrite($hell, "#cms_mobilnavigation {\n");
fwrite($hell, "border-top-right-radius: 5px;\n");
fwrite($hell, "border-top-left-radius: 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrund']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "padding: 4px 10px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "line-height: 20px;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "user-select: none;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilnavigation:hover {\n");
fwrite($hell, "cursor: pointer !important;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrundhover']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_menuicon {\n");
fwrite($hell, "width: 20px;\n");
fwrite($hell, "height: 4px;\n");
fwrite($hell, "border-radius: 2px;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "margin: 4px 0;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_a {\n");
fwrite($hell, "/*display: none;*/\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "z-index: 100000;\n");
fwrite($hell, "overflow-y: scroll;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_i {padding: 10px 10px 20px 10px;}\n");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe span, #cms_mobilmenue_i p.cms_mobilmenue_knoepfe a {\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe span,\n");
fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe a:last-child {\n");
fwrite($hell, "border-top-left-radius: 0px;\n");
fwrite($hell, "border-bottom-left-radius: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_i p.cms_mobilmenue_knoepfe a:first-child {\n");
fwrite($hell, "border-top-right-radius: 0px;\n");
fwrite($hell, "border-bottom-right-radius: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten p.cms_notiz {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten {\n");
fwrite($hell, "margin-top: 30px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten > div div {\n");
fwrite($hell, "padding-left: 10px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten ul {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten > div > ul > li {border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");
fwrite($hell, "#cms_mobilmenue_seiten > div > ul > li:last-child {border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");

fwrite($hell, "#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li .cms_meldezahl {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "padding: 3px 5px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border-radius: 10px;\n");
fwrite($hell, "opacity: 1;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "top: 3px;\n");
fwrite($hell, "right: 4px;\n");
fwrite($hell, "height: 18px;\n");
fwrite($hell, "line-height: 12px;\n");
fwrite($hell, "width: auto;\n");
fwrite($hell, "min-width: 30px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li .cms_meldezahl_wichtig {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {\n");
fwrite($hell, "transform: translate(0px) !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {width: 90%;}\n");
fwrite($hell, "#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen {\n");
fwrite($hell, "width: 10%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrundhover'].";\n");
fwrite($hell, "}\n");
fwrite($hell, "#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_mobilmenue_seiten li .cms_mobilnavi_passiv {\n");
fwrite($hell, "cursor: default !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "background-color: transparent !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_schliessen {\n");
fwrite($hell, "position: absolute !important;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_aktionen_liste li {\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht {\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "margin-right: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li a,\n");
fwrite($hell, ".cms_uebersicht li span.cms_appmenue_element,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_notifikation,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin,\n");
fwrite($hell, ".cms_uebersicht li span.cms_blogeintrag,\n");
fwrite($hell, ".cms_uebersicht li span.cms_beschlusseintrag,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_vertretungsplanung {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 5px 5px 5px 47px;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "background-position: 5px 5px;\n");
fwrite($hell, "min-height: 42px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li a.cms_blogvorschau_ohneicon {\n");
fwrite($hell, "padding: 5px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li:last-child a,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_appmenue_element,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_notifikation,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_termin,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_blogeintrag,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_beschlusseintrag,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_gremien,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_fachschaften,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_ferien,\n");
fwrite($hell, ".cms_uebersicht li:last-child span.cms_uebersicht_vertretungsplanung {\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li a p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_appmenue_element p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_notifikation p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_blogeintrag p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_beschlusseintrag p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften p,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien p,\n");
fwrite($hell, ".cms_uebersicht .cms_blogliste_details p {\n");
fwrite($hell, "font-size: 90%;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li a h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_appmenue_element h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_notifikation h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_blogeintrag h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_beschlusseintrag h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften h3,\n");
fwrite($hell, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien h3,\n");
fwrite($hell, ".cms_uebersicht .cms_blogliste_details h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht_notifikation .cms_notifikation_schliessen {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 5px !important;\n");
fwrite($hell, "top: 5px !important;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht_notifikation:hover .cms_notifikation_schliessen {\n");
fwrite($hell, "opacity: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht li {\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht a, .cms_uebersicht span.cms_appmenue_element {\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht a:hover, .cms_uebersicht span.cms_appmenue_element:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "transform: translateX(0) translateY(0);\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht .cms_blog_keinhover {\n");
fwrite($hell, "cursor: default !important;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund']." !important;\n");
fwrite($hell, "}\n");


fwrite($hell, ".cms_anteilbalken_innen {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "transition: 100ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_anteilbalken_innen {\n");
fwrite($hell, "height: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_anteilbalken_aussen {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_anteilbalken_notiz {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "font-size: 70%;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");







// DARKMODE
fwrite($dunkel, ".cms_hauptnavigation .cms_kategorie1,\n");
fwrite($dunkel, "#cms_kopfnavigation > li > a, #cms_kopfnavigation > li > span {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_hauptnavigation_kategoriehintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_hauptnavigation_kategoriefarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_hauptnavigation > li:hover > span.cms_kategorie1,\n");
fwrite($dunkel, "#cms_kopfnavigation li:hover > a,\n");
fwrite($dunkel, "#cms_kopfnavigation li:hover > span {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_hauptnavigation_kategoriehintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_hauptnavigation_kategoriefarbehover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_kopfnavigation > li > .cms_naviuntermenue {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_kopfnavigation > li ul {\n");
fwrite($dunkel, "border-top: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";\n");
fwrite($dunkel, "border-bottom: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_kopfnavigation > li ul li a,\n");
fwrite($dunkel, "#cms_kopfnavigation > li ul li span {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_kopfnavigation > li ul li a:hover,\n");
fwrite($dunkel, "#cms_kopfnavigation > li ul li span:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "cursor: pointer;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_unternavigation_m {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-bottom: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";\n");
fwrite($dunkel, "border-top: 3px solid ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li:last-child {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li a,\n");
fwrite($dunkel, ".cms_navigation > li span {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li a:hover,\n");
fwrite($dunkel, ".cms_navigation > li span:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li > .cms_navigation_aktiveseite {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li > .cms_navigation_aktiveseite:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hauptnavigation_akzentfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_navigation > li li .cms_navigation_aktiveseite {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_aktivitaet_in, #cms_maktivitaet_in, #cms_aktivitaet_in_profil, .cms_fortschritt_i {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_aktivitaet_out, #cms_maktivitaet_out, #cms_aktivitaet_out_profil, .cms_fortschritt_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_aktivitaet_out, #cms_maktivitaet_out {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilnavigation {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrund']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilnavigation:hover {\n");
fwrite($dunkel, "cursor: pointer !important;\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrundhover']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_menuicon {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_a {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten > div > ul > li {border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");
fwrite($dunkel, "#cms_mobilmenue_seiten > div > ul > li:last-child {border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten li a, #cms_mobilmenue_seiten li span {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten li .cms_meldezahl {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten li .cms_meldezahl_wichtig {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten li a:hover, #cms_mobilmenue_seiten li span:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrundhover'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, "#cms_mobilmenue_seiten li span.cms_mobilmenue_aufklappen:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_mobilmenue_seiten li .cms_mobilnavi_passiv {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_uebersicht li a,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_appmenue_element,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_notifikation,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_termin,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_blogeintrag,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_beschlusseintrag,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_gremien,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_fachschaften,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_verwaltung_ferien,\n");
fwrite($dunkel, ".cms_uebersicht li span.cms_uebersicht_vertretungsplanung {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_uebersicht li:last-child a,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_appmenue_element,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_notifikation,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_termin,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_blogeintrag,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_beschlusseintrag,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_gremien,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_fachschaften,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_verwaltung_ferien,\n");
fwrite($dunkel, ".cms_uebersicht li:last-child span.cms_uebersicht_vertretungsplanung {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_uebersicht a:hover, .cms_uebersicht span.cms_appmenue_element:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_uebersicht .cms_blog_keinhover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_anteilbalken_innen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_anteilbalken_aussen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_anteilbalken_notiz {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");
?>
