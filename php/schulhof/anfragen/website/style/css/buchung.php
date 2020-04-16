<?php
fwrite($hell, "#cms_neue_buchung {\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_buchungsplan {\n");
fwrite($hell, "margin-top: 50px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsspalte_uhrzeiten, .cms_buchungsspalte {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsspalte_uhrzeiten {\n");
fwrite($hell, "width: 9%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsspalte {\n");
fwrite($hell, "width: 13%;\n");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsspalte_ferien {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsspalte .cms_buchungsspaltetitel {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: -40px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsuhrzeit {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "opacity: .5;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchungsuhrzeitlinien {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "opacity: .5;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_blockierung, .cms_buchung_selbst, .cms_buchung_fremd {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 96%;\n");
fwrite($hell, "overflow-y: scroll;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "left: 2%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_blockierung {background: ".$_POST['cms_style_h_haupt_abstufung1']."; border: 1px solid ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");
fwrite($hell, ".cms_buchung_selbst {background: ".$_POST['cms_style_h_haupt_meldunginfohinter']."; border: 1px solid ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}\n");
fwrite($hell, ".cms_buchung_fremd {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter']."; border: 1px solid ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}\n");

fwrite($hell, ".cms_buchung_zeit, .cms_buchung_von, .cms_buchung_aktion {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "font-size:  80%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_zeit {\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_aktion {\n");
fwrite($hell, "margin-top: 3px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_aktion .cms_button_nein {\n");
fwrite($hell, "line-height: 1;\n");
fwrite($hell, "font-size: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_selbst:hover .cms_buchung_aktion,\n");
fwrite($hell, ".cms_buchung_fremd:hover .cms_buchung_aktion {\n");
fwrite($hell, "opacity: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_buchung_grund {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "font-size:  80%;\n");
fwrite($hell, "}\n");


// DARKMODE
fwrite($dunkel, ".cms_buchungsspalte {\n");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_buchungsspalte_ferien {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_buchungsspalte .cms_buchungsspaltetitel {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_buchungsuhrzeit {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_buchungsuhrzeitlinien {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_buchung_blockierung {background: ".$_POST['cms_style_d_haupt_abstufung1']."; border: 1px solid ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");
fwrite($dunkel, ".cms_buchung_selbst {background: ".$_POST['cms_style_d_haupt_meldunginfohinter']."; border: 1px solid ".$_POST['cms_style_d_haupt_meldunginfoakzent'].";}\n");
fwrite($dunkel, ".cms_buchung_fremd {background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter']."; border: 1px solid ".$_POST['cms_style_d_haupt_meldungwarnungakzent'].";}\n");
?>
