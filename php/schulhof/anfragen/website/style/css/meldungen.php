<?php
fwrite($hell, ".cms_meldung {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "background-position: 5px 5px;");
fwrite($hell, "border-left: 4px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "padding: 10px 10px 10px 10px;");
fwrite($hell, "margin: 10px 0px;");
fwrite($hell, "min-height: 42px;");
fwrite($hell, "text-align: left !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung:first-child {");
fwrite($hell, "margin-top: 0px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_laden {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "border: 3px dashed ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "margin: 10px 0px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_info {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_vplan {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "opacity: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_erfolg {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_fehler {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_warnung {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_bauarbeiten {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_meldung_firewall {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_gesichertedaten {");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "margin: 10px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_geschuetzerinhalt {");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "margin: 10px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_geschuetzerinhalt p, .cms_ladebox {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_ladebox {");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "padding: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_systemvoraussetzung {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_systemvoraussetzung li {");
fwrite($hell, "width:100%;");
fwrite($hell, "padding: 10px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "}");




// DARKMODE
fwrite($dunkel, ".cms_meldung {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-left: 4px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_laden {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "border: 3px dashed ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_info {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_vplan {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_erfolg {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_fehler {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_warnung {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_bauarbeiten {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_meldung_firewall {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_gesichertedaten {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_geschuetzerinhalt {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_geschuetzerinhalt p, .cms_ladebox {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_ladebox {");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_systemvoraussetzung {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");
?>
