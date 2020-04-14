<?php
fwrite($hell, "#cms_neue_buchung {");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "}");

fwrite($hell, "#cms_buchungsplan {");
fwrite($hell, "margin-top: 50px;");
fwrite($hell, "position: relative;");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsspalte_uhrzeiten, .cms_buchungsspalte {");
fwrite($hell, "position: relative;");
fwrite($hell, "float: left;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsspalte_uhrzeiten {");
fwrite($hell, "width: 9%;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsspalte {");
fwrite($hell, "width: 13%;");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsspalte_ferien {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsspalte .cms_buchungsspaltetitel {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: -40px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "width: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsuhrzeit {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "position: absolute;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "opacity: .5;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchungsuhrzeitlinien {");
fwrite($hell, "width: 100%;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "opacity: .5;");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_blockierung, .cms_buchung_selbst, .cms_buchung_fremd {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 96%;");
fwrite($hell, "overflow-y: scroll;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "left: 2%;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_blockierung {background: ".$_POST['cms_style_h_haupt_abstufung1']."; border: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";}");
fwrite($hell, ".cms_buchung_selbst {background: ".$_POST['cms_style_h_haupt_meldunginfohinter']."; border: 1px solid ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");
fwrite($hell, ".cms_buchung_fremd {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter']."; border: 1px solid ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");

fwrite($hell, ".cms_buchung_zeit, .cms_buchung_von, .cms_buchung_aktion {");
fwrite($hell, "display: block;");
fwrite($hell, "font-size:  80%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_zeit {");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_aktion {");
fwrite($hell, "margin-top: 3px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_aktion .cms_button_nein {");
fwrite($hell, "line-height: 1;");
fwrite($hell, "font-size: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_selbst:hover .cms_buchung_aktion,");
fwrite($hell, ".cms_buchung_fremd:hover .cms_buchung_aktion {");
fwrite($hell, "opacity: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_buchung_grund {");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "font-size:  80%;");
fwrite($hell, "}");


// DARKMODE
fwrite($dunkel, ".cms_buchungsspalte {");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_buchungsspalte_ferien {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_buchungsspalte .cms_buchungsspaltetitel {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_buchungsuhrzeit {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_buchungsuhrzeitlinien {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_buchung_blockierung {background: ".$_POST['cms_style_d_haupt_abstufung1']."; border: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";}");
fwrite($dunkel, ".cms_buchung_selbst {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent']."; border: 1px solid ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
fwrite($dunkel, ".cms_buchung_fremd {background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent']."; border: 1px solid ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");
?>
