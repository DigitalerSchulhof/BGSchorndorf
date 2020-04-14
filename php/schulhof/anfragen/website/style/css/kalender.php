<?php
fwrite($hell, ".cms_kalenderuebersicht {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_kalenderuebersicht th, .cms_kalenderuebersicht td {");
fwrite($hell, "width: 12.5%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_kalender_kw, .cms_kalender_tag {font-weight: bold; padding: 6px 0px 5px 0px;}");
fwrite($hell, ".cms_kalender_kw {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}");

fwrite($hell, ".cms_kalender_kwzahl, .cms_kalender_tagzahl {font-weight: normal;}");
fwrite($hell, ".cms_kalender_kwzahl {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}");

fwrite($hell, ".cms_kalenderzahl {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "padding: 6px 0px 5px 0px;");
fwrite($hell, "border-radius: 7px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 100%;");
fwrite($hell, "border: 3px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "line-height: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_kalenderzahl:hover {");
fwrite($hell, "border: 3px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_kalenderzahl_heute {border: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($hell, ".cms_kalenderzahl_gewaehlt {background: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;}");
fwrite($hell, ".cms_kalenderzahl_persoenlich {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($hell, ".cms_kalenderzahl_oeffentlich {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");
fwrite($hell, ".cms_kalenderzahl_ferien {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($hell, ".cms_kalenderzahl_sichtbar {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}");



// DARKMODE
fwrite($dunkel, ".cms_kalender_kw {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}");

fwrite($dunkel, ".cms_kalender_kwzahl {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}");

fwrite($dunkel, ".cms_kalenderzahl {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "border: 3px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kalenderzahl:hover {");
fwrite($dunkel, "border: 3px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kalenderzahl_heute {border: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($dunkel, ".cms_kalenderzahl_gewaehlt {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent']." !important;}");
fwrite($dunkel, ".cms_kalenderzahl_persoenlich {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($dunkel, ".cms_kalenderzahl_oeffentlich {background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");
fwrite($dunkel, ".cms_kalenderzahl_ferien {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($dunkel, ".cms_kalenderzahl_sichtbar {background: ".$_POST['cms_style_d_haupt_abstufung1'].";}");
?>
