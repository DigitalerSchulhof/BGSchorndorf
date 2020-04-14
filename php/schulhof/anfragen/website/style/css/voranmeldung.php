<?php
fwrite($hell, ".cms_voranmeldung_navigation {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: nowrap;\n");
fwrite($hell, "margin: ".$_POST['cms_style_haupt_absatzschulhof']." 0px ".$_POST['cms_style_haupt_absatzschulhof']." 0px;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_voranmeldung_navigation li,\n");
fwrite($hell, ".cms_optimierung_T .cms_voranmeldung_navigation li {\n");
fwrite($hell, "width: 25%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_voranmeldung_navigation li {\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_button_hintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover']." !important;\n");
fwrite($hell, "cursor: default !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");




// DARKMODE
fwrite($dunkel, ".cms_voranmeldung_navigation li {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_button_hintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_aktuell:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_voranmeldung_navigation li.cms_voranmeldung_fehler:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");
?>
