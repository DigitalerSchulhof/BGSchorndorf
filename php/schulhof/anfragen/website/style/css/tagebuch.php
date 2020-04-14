<?php
fwrite($hell, ".cms_tagebuch_offen:hover {cursor: pointer;}\n");
fwrite($hell, ".cms_tagebuch_freigabe {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}\n");
fwrite($hell, ".cms_tagebuch_gesperrt {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");

fwrite($hell, ".cms_tagebuch_leistungsmessung {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($hell, ".cms_tagebuch_entschuldigt {color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_tagebuch_unentschuldigt {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($hell, ".cms_tagebuch_positiv {color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_tagebuch_negativ {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($hell, ".cms_tagebuch_neutral {color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}\n");



// DARKMODE
fwrite($dunkel, ".cms_tagebuch_freigabe {background: ".$_POST['cms_style_d_haupt_abstufung1'].";}\n");
fwrite($dunkel, ".cms_tagebuch_gesperrt {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");

fwrite($dunkel, ".cms_tagebuch_leistungsmessung {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_tagebuch_entschuldigt {color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_tagebuch_unentschuldigt {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_tagebuch_positiv {color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_tagebuch_negativ {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, ".cms_tagebuch_neutral {color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}\n");
?>
