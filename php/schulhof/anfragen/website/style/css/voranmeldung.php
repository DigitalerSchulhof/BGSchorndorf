<?php
fwrite($hell, ".cms_voranmeldung_navigation {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: nowrap;");
fwrite($hell, "margin: ".$_POST['cms_style_haupt_absatzschulhof']." 0px ".$_POST['cms_style_haupt_absatzschulhof']." 0px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_voranmeldung_navigation li,");
fwrite($hell, ".cms_optimierung_T .cms_voranmeldung_navigation li {");
fwrite($hell, "width: 25%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_voranmeldung_navigation li {");
fwrite($hell, "width: 50%;");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "background: ".$_POST['cms_style_h_button_hintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "display: block;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover']." !important;");
fwrite($hell, "cursor: default !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");




// DARKMODE
fwrite($dunkel, ".cms_voranmeldung_navigation li {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_button_hintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");
?>
