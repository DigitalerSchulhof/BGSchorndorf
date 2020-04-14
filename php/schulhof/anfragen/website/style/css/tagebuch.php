<?php
fwrite($hell, ".cms_tagebuch_offen:hover {cursor: pointer;}");
fwrite($hell, ".cms_tagebuch_freigabe {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}");
fwrite($hell, ".cms_tagebuch_gesperrt {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");

fwrite($hell, ".cms_tagebuch_leistungsmessung {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($hell, ".cms_tagebuch_entschuldigt {color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_tagebuch_unentschuldigt {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($hell, ".cms_tagebuch_positiv {color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_tagebuch_negativ {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($hell, ".cms_tagebuch_neutral {color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");



// DARKMODE
fwrite($dunkel, ".cms_tagebuch_freigabe {background: ".$_POST['cms_style_d_haupt_abstufung1'].";}");
fwrite($dunkel, ".cms_tagebuch_gesperrt {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");

fwrite($dunkel, ".cms_tagebuch_leistungsmessung {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($dunkel, ".cms_tagebuch_entschuldigt {color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_tagebuch_unentschuldigt {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($dunkel, ".cms_tagebuch_positiv {color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_tagebuch_negativ {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($dunkel, ".cms_tagebuch_neutral {color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
?>
