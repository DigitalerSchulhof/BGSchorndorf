<?php
fwrite($hell, ".cms_meldung {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "background-position: 5px 5px;\n");
fwrite($hell, "border-left: 4px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "padding: 10px 10px 10px 10px;\n");
fwrite($hell, "margin: 10px 0px;\n");
fwrite($hell, "min-height: 42px;\n");
fwrite($hell, "text-align: left !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung:first-child {\n");
fwrite($hell, "margin-top: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_laden {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "border: 3px dashed ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "margin: 10px 0px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_info {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_vplan {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "opacity: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_erfolg {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_fehler {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_warnung {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_bauarbeiten {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_meldung_firewall {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_gesichertedaten {\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "margin: 10px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_geschuetzerinhalt {\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "margin: 10px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_geschuetzerinhalt p, .cms_ladebox {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ladebox {\n");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_systemvoraussetzung {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_systemvoraussetzung li {\n");
fwrite($hell, "width:100%;\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "}\n");




// DARKMODE
fwrite($dunkel, ".cms_meldung {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-left: 4px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_laden {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "border: 3px dashed ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_info {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_meldunginfoakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_vplan {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_erfolg {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_fehler {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_warnung {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_meldungwarnungakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_bauarbeiten {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_meldungwarnungakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_meldung_firewall {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_meldunginfoakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_gesichertedaten {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_geschuetzerinhalt {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_geschuetzerinhalt p, .cms_ladebox {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_ladebox {\n");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_systemvoraussetzung {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");
?>
