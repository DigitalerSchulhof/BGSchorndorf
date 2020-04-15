<?php
fwrite($hell, ".cms_kalenderuebersicht {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalenderuebersicht th, .cms_kalenderuebersicht td {\n");
fwrite($hell, "width: 12.5%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalender_kw, .cms_kalender_tag {font-weight: bold; padding: 6px 0px 5px 0px;}\n");
fwrite($hell, ".cms_kalender_kw {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");

fwrite($hell, ".cms_kalender_kwzahl, .cms_kalender_tagzahl {font-weight: normal;}\n");
fwrite($hell, ".cms_kalender_kwzahl {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");

fwrite($hell, ".cms_kalenderzahl {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "padding: 6px 0px 5px 0px;\n");
fwrite($hell, "border-radius: 7px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border: 3px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "line-height: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalenderzahl:hover {\n");
fwrite($hell, "border: 3px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalenderzahl_heute {border: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($hell, ".cms_kalenderzahl_gewaehlt {background: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;}\n");
fwrite($hell, ".cms_kalenderzahl_persoenlich {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($hell, ".cms_kalenderzahl_oeffentlich {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");
fwrite($hell, ".cms_kalenderzahl_ferien {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($hell, ".cms_kalenderzahl_sichtbar {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}\n");



// DARKMODE
fwrite($dunkel, ".cms_kalender_kw {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");

fwrite($dunkel, ".cms_kalender_kwzahl {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");

fwrite($dunkel, ".cms_kalenderzahl {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "border: 3px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kalenderzahl:hover {\n");
fwrite($dunkel, "border: 3px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kalenderzahl_heute {border: 3px solid ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";}\n");
fwrite($dunkel, ".cms_kalenderzahl_gewaehlt {background: ".$_POST['cms_style_d_haupt_meldunginfohinter']." !important;}\n");
fwrite($dunkel, ".cms_kalenderzahl_persoenlich {background: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_kalenderzahl_oeffentlich {background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";}\n");
fwrite($dunkel, ".cms_kalenderzahl_ferien {background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_kalenderzahl_sichtbar {background: ".$_POST['cms_style_d_haupt_abstufung1'].";}\n");
?>
