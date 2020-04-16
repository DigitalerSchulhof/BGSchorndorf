<?php
fwrite($hell, "* {\n");
fwrite($hell, "font-family: ".$_POST['cms_style_haupt_schriftart'].", sans-serif;\n");
fwrite($hell, "font-size: ".$_POST['cms_style_haupt_schriftgroesse'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "font-weight: normal;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "image-rendering: pixelated;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "line-height: 1.2em;\n");
fwrite($hell, "text-decoration: none;\n");
fwrite($hell, "box-sizing: border-box;\n");
fwrite($hell, "}\n");

fwrite($hell, "body {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_koerperhintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seite_normal #cms_kopfzeile_o {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_kopfzeile_aussenabstand'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kopfzeile_hintergrund'].";\n");
fwrite($hell, "position: ".$_POST['cms_style_kopfzeile_positionierung'].";\n");
fwrite($hell, "top: ".$_POST['cms_style_kopfzeile_abstandvonoben'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kopfzeile_linienstaerkeunten'].";\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "z-index: 20;\n");
fwrite($hell, "box-shadow: ".$_POST['cms_style_kopfzeile_schattenausmasse']." ".$_POST['cms_style_h_kopfzeile_schattenfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_platzhalter_bild {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_kopfzeile_platzhalter'].";\n");
fwrite($hell, "height: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_website_bearbeiten_o {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_hauptbild_o {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_koerperhintergrund'].";\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_hauptteil_o {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_o {\n");
fwrite($hell, "border-top: ".$_POST['cms_style_fusszeile_linienstaerkeoben'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_fusszeile_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seite_normal #cms_kopfzeile_i {\n");
fwrite($hell, "padding: 10px 10px 0px 10px;\n");
fwrite($hell, "height: ".$_POST['cms_style_kopfzeile_hoehe'].";\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_website_bearbeiten_i {\n");
fwrite($hell, "padding: 0px 0px 0px 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_hauptteil_i {\n");
fwrite($hell, "padding: 10px 0px 10px 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_i {\n");
fwrite($hell, "padding: 10px 10px 30px 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seite_normal #cms_logo {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: 10px;\n");
fwrite($hell, "top: 10px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_bild {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "padding-right: 10px;\n");
fwrite($hell, "width: ".$_POST['cms_style_logo_breite'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_schrift {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "display: ".$_POST['cms_style_logo_anzeige'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_o, #cms_logo_u {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_logo_schriftfarbe'].";\n");
fwrite($hell, "font-size: 170%;\n");
fwrite($hell, "padding: 2px 0px 0px 0px;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_o {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_netzcheckstatus {\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "font-size: 10px;\n");
fwrite($hell, "font-family: 'robl', sans-serif;\n");
fwrite($hell, "padding: 2px 5px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "z-index: 5000;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_netzcheckstatus_lehrer {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_spalte_i {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_spalte_icon {\n");
fwrite($hell, "padding-right: 52px;\n");
fwrite($hell, "min-height: 32px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_clear {\n");
fwrite($hell, "clear: both;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bild {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bild img {\n");
fwrite($hell, "max-width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vollbild {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vollbild .cms_rechtsbuendig {\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "top: 10px;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vollbild .cms_vollbild_innen > .cms_spalte_i:first-child {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_vollbild_schliessen, .cms_button_vollbild_oeffnen {\n");
fwrite($hell, "position: absolute !important;\n");
fwrite($hell, "top: 10px;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_vollbild_schliessen {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

/* COMPUTER & TABLET */
fwrite($hell, ".cms_spalte_2, .cms_spalte_23, .cms_spalte_25, .cms_spalte_15, .cms_spalte_3, .cms_spalte_6,\n");
fwrite($hell, ".cms_spalte_60, .cms_spalte_40, .cms_spalte_20, .cms_spalte_4, .cms_spalte_34 {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_2,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_2 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_23,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_23 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 66.66%;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_25,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_25 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 40%;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_15,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_15 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 20%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_3,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_3 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 33.33%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_6,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_6 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 66.66%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_60,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_60 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 60%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_40,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_40 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 40%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_20,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_20 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 20%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_4,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_4 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 25%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_34,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_34 {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "width: 75%;\n");
fwrite($hell, "}\n");

/* COMPUTER */
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_kopfzeile_m,\n");
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_hauptteil_m,\n");
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_website_bearbeiten_m,\n");
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_fusszeile_m {\n");
fwrite($hell, "width: ".$_POST['cms_style_haupt_seitenbreite'].";\n");
fwrite($hell, "margin: 0px auto;\n");
fwrite($hell, "}\n");

/* TABLET & HANDY */
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_kopfzeile_m,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_hauptteil_m,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_website_bearbeiten_m,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_fusszeile_m,\n");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_kopfzeile_m,\n");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_hauptteil_m,\n");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_website_bearbeiten_m,\n");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_fusszeile_m {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "max-width: ".$_POST['cms_style_haupt_seitenbreite']." !important;\n");
fwrite($hell, "margin: 0px auto;\n");
fwrite($hell, "}\n");

/* HANDY */
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_2,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_3,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_60,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_40,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_4,\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_34\n");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_6 {\n");
fwrite($hell, "float: none;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_groesseaendern {\n");
fwrite($hell, "right: 0px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "height: 100% !important;\n");
fwrite($hell, "width: 4px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "z-index: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_groesseaendern:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_groesseaendern:hover {cursor: ew-resize;}\n");


// DARKMODE
fwrite($dunkel, "* {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "body {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_fusszeile_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_seite_normal #cms_kopfzeile_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kopfzeile_hintergrund'].";\n");
fwrite($dunkel, "box-shadow: ".$_POST['cms_style_kopfzeile_schattenausmasse']." ".$_POST['cms_style_d_kopfzeile_schattenfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_website_bearbeiten_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_hauptbild_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_koerperhintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_hauptteil_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_fusszeile_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_seite_normal #cms_logo #cms_logo_schrift #cms_logo_o,\n");
fwrite($dunkel, ".cms_seite_normal #cms_logo #cms_logo_schrift #cms_logo_u {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_logo_schriftfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_netzcheckstatus {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_netzcheckstatus_lehrer {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_vollbild {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_groesseaendern:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

?>
