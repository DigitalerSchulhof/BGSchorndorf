<?
fwrite($hell, "* {");
fwrite($hell, "font-family: ".$_POST['cms_style_haupt_schriftart'].", sans-serif;");
fwrite($hell, "font-size: ".$_POST['cms_style_haupt_schriftgroesse'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "font-weight: normal;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "line-height: 1.2em;");
fwrite($hell, "text-decoration: none;");
fwrite($hell, "box-sizing: border-box;");
fwrite($hell, "}");

fwrite($hell, "body {");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_koerperhintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_seite_normal #cms_kopfzeile_o {");
  fwrite($hell, "margin-top: ".$_POST['cms_style_kopfzeile_aussenabstand'].";");
  fwrite($hell, "background: ".$_POST['cms_style_h_kopfzeile_hintergrund'].";");
  fwrite($hell, "position: ".$_POST['cms_style_kopfzeile_positionierung'].";");
  fwrite($hell, "top: ".$_POST['cms_style_kopfzeile_abstandvonoben'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kopfzeile_linienstaerkeunten'].";");
  fwrite($hell, "left: 0px;");
  fwrite($hell, "width: 100%;");
  fwrite($hell, "z-index: 20;");
  fwrite($hell, "box-shadow: ".$_POST['cms_style_kopfzeile_schattenausmasse']." ".$_POST['cms_style_h_kopfzeile_schattenfarbe'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_platzhalter_bild {");
  fwrite($hell, "margin-top: ".$_POST['cms_style_kopfzeile_platzhalter'].";");
  fwrite($hell, "height: 0px;");
  fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, "#cms_website_bearbeiten_o {");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_hauptbild_o {");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_koerperhintergrund'].";");
  fwrite($hell, "margin: 0px;");
  fwrite($hell, "padding: 0px;");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, "#cms_hauptteil_o {");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_o {");
fwrite($hell, "border-top: ".$_POST['cms_style_fusszeile_linienstaerkeoben'].";");
  fwrite($hell, "background: ".$_POST['cms_style_h_fusszeile_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_seite_normal #cms_kopfzeile_i {");
  fwrite($hell, "padding: 10px 10px 0px 10px;");
  fwrite($hell, "height: ".$_POST['cms_style_kopfzeile_hoehe'].";");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, "#cms_website_bearbeiten_i {");
  fwrite($hell, "padding: 0px 0px 0px 0px;");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, "#cms_hauptteil_i {");
  fwrite($hell, "padding: 10px 0px 10px 0px;");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_i {");
  fwrite($hell, "padding: 10px 10px 30px 10px;");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_seite_normal #cms_logo {");
  fwrite($hell, "position: absolute;");
  fwrite($hell, "left: 10px;");
  fwrite($hell, "top: 10px;");
  fwrite($hell, "display: inline-block;");

fwrite($hell, "#cms_logo_bild {");
  fwrite($hell, "float: left;");
  fwrite($hell, "padding-right: 10px;");
fwrite($hell, "width: ".$_POST['cms_style_logo_breite'].";");
fwrite($hell, "}");
fwrite($hell, "");
fwrite($hell, "#cms_logo_schrift {");
  fwrite($hell, "float: left;");
  fwrite($hell, "display: ".$_POST['cms_style_logo_anzeige'].";");

fwrite($hell, "#cms_logo_o, #cms_logo_u {");
  fwrite($hell, "position: relative;");
  fwrite($hell, "color: ".$_POST['cms_style_h_logo_schriftfarbe'].";");
  fwrite($hell, "font-size: 170%;");
  fwrite($hell, "padding: 2px 0px 0px 0px;");
  fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, "#cms_logo_o {");
  fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "#cms_netzcheckstatus {");
  fwrite($hell, "position: fixed;");
  fwrite($hell, "left: 0px;");
  fwrite($hell, "bottom: 0px;");
  fwrite($hell, "width: 100%;");
  fwrite($hell, "font-size: 10px;");
  fwrite($hell, "font-family: 'robl', sans-serif;");
  fwrite($hell, "padding: 2px 5px;");
  fwrite($hell, "text-align: center;");
  fwrite($hell, "z-index: 5000;");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_netzcheckstatus_lehrer {");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_spalte_i {");
  fwrite($hell, "padding: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_spalte_icon {");
  fwrite($hell, "padding-right: 52px;");
  fwrite($hell, "min-height: 32px;");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_clear {");
  fwrite($hell, "clear: both;");
fwrite($hell, "}");

fwrite($hell, ".cms_bild {");
  fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_bild img {");
  fwrite($hell, "max-width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_vollbild {");
  fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
  fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_vollbild .cms_rechtsbuendig {");
fwrite($hell, "position: fixed;");
fwrite($hell, "right: 10px;");
fwrite($hell, "top: 10px;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "}");

fwrite($hell, ".cms_vollbild .cms_vollbild_innen > .cms_spalte_i:first-child {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_button_vollbild_schliessen, .cms_button_vollbild_oeffnen {");
  fwrite($hell, "position: absolute !important;");
  fwrite($hell, "top: 10px;");
  fwrite($hell, "right: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_button_vollbild_schliessen {");
  fwrite($hell, "display: none;");
fwrite($hell, "}");

/* COMPUTER & TABLET */
fwrite($hell, ".cms_spalte_2, .cms_spalte_23, .cms_spalte_25, .cms_spalte_15, .cms_spalte_3, .cms_spalte_6,");
fwrite($hell, ".cms_spalte_60, .cms_spalte_40, .cms_spalte_20, .cms_spalte_4, .cms_spalte_34 {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_spalte_2,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_2 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 50%;");
fwrite($hell, "}");
fwrite($hell, ".cms_optimierung_P .cms_spalte_23,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_23 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 66.66%;");
fwrite($hell, "}");
fwrite($hell, ".cms_optimierung_P .cms_spalte_25,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_25 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 40%;");
fwrite($hell, "}");
fwrite($hell, ".cms_optimierung_P .cms_spalte_15,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_15 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 20%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_spalte_3,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_3 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 33.33%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_spalte_6,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_6 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 66.66%;");
fwrite($hell, "}");
fwrite($hell, "");
fwrite($hell, ".cms_optimierung_P .cms_spalte_60,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_60 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 60%;");
fwrite($hell, "}");
fwrite($hell, "");
fwrite($hell, ".cms_optimierung_P .cms_spalte_40,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_40 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 40%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_spalte_20,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_20 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 20%;");

fwrite($hell, ".cms_optimierung_P .cms_spalte_4,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_4 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 25%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_spalte_34,");
fwrite($hell, ".cms_optimierung_T .cms_spalte_34 {");
  fwrite($hell, "float: left;");
  fwrite($hell, "width: 75%;");
fwrite($hell, "}");

/* COMPUTER */
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_kopfzeile_m,");
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_hauptteil_m,");
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_website_bearbeiten_m,");
fwrite($hell, ".cms_optimierung_P.cms_seite_normal #cms_fusszeile_m {");
  fwrite($hell, "width: ".$_POST['cms_style_haupt_seitenbreite'].";");
  fwrite($hell, "margin: 0px auto;");
fwrite($hell, "}");

/* TABLET & HANDY */
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_kopfzeile_m,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_hauptteil_m,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_website_bearbeiten_m,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal #cms_fusszeile_m,");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_kopfzeile_m,");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_hauptteil_m,");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_website_bearbeiten_m,");
fwrite($hell, ".cms_optimierung_T.cms_seite_normal #cms_fusszeile_m {");
  fwrite($hell, "width: 100%;");
  fwrite($hell, "max-width: ".$_POST['cms_style_haupt_seitenbreite']." !important;");
  fwrite($hell, "margin: 0px auto;");
fwrite($hell, "}");

/* HANDY */
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_2,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_3,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_60,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_40,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_4,");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_34");
fwrite($hell, ".cms_optimierung_H.cms_seite_normal .cms_spalte_6 {");
  fwrite($hell, "float: none;");
  fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_groesseaendern {");
fwrite($hell, "right: 0px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "height: 100% !important;");
fwrite($hell, "width: 4px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "z-index: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_groesseaendern:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_groesseaendern:hover {cursor: ew-resize;}");


// DARKMODE
fwrite($dunkel, "* {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "body {");
  fwrite($dunkel, "background: ".$_POST['cms_style_d_fusszeile_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_seite_normal #cms_kopfzeile_o {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kopfzeile_hintergrund'].";");
  fwrite($dunkel, "box-shadow: ".$_POST['cms_style_kopfzeile_schattenausmasse']." ".$_POST['cms_style_d_kopfzeile_schattenfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_website_bearbeiten_o {");
  fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_hauptbild_o {");
  fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_koerperhintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_hauptteil_o {");
  fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_o {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_fusszeile_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_seite_normal #cms_logo #cms_logo_schrift #cms_logo_o,");
fwrite($dunkel, ".cms_seite_normal #cms_logo #cms_logo_schrift #cms_logo_u {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_logo_schriftfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_netzcheckstatus {");
  fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_netzcheckstatus_lehrer {");
  fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_vollbild {");
  fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_groesseaendern:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

?>
