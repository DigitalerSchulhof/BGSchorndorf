<?php
fwrite($hell, "#cms_neuerungenverlauf p + h4 {");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_sichtbar {");
fwrite($hell, "max-height: 2500px !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_dsgvo_datenschutz {");
fwrite($hell, "position:fixed;");
fwrite($hell, "bottom:0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "left: 0px;");
fwrite($hell, "padding:10px;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "}");

fwrite($hell, "#cms_dsgvo_datenschutz .cms_meldung {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_netzcheckstatus span {font-size: inherit;}");

fwrite($hell, ".cms_filter_ein {");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_absender {text-align: right;}");
fwrite($hell, ".cms_empfaenger, .cms_anhangtitel {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}");

fwrite($hell, ".cms_postfach_papierkorb_aussen {");
fwrite($hell, "width: 6px;");
fwrite($hell, "height: 23px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "line-height: 0px;");
fwrite($hell, "position: relative;");
fwrite($hell, "bottom: -6px;");
fwrite($hell, "margin-bottom: 2px;");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_papierkorb_innen {");
fwrite($hell, "width: 6px;");
fwrite($hell, "height: 23px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "display: block;");
fwrite($hell, "position: absolute;");
fwrite($hell, "bottom: 0px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_button .cms_postfach_anhang {");
fwrite($hell, "margin-right: 0px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_anhang {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-right: 10px;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_anhang img {");
fwrite($hell, "position: relative;");
fwrite($hell, "top: 3px;");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_anhang .cms_button_nein {");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: -3px;");
fwrite($hell, "z-index: 1;");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_anhang:hover .cms_button_nein {");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_signatur, .cms_originalnachricht_meta {");
fwrite($hell, "margin-top: 15px;");
fwrite($hell, "padding-top:5px;");
fwrite($hell, "border-top: 1px dotted ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "}");

fwrite($hell, ".cms_originalnachricht {");
fwrite($hell, "padding-left: 5px;");
fwrite($hell, "border-left: 2px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_versteckt {");
fwrite($hell, "display: none;");
fwrite($hell, "}");


fwrite($hell, "#cms_debug {");
fwrite($hell, "bottom: 0px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "z-index: 1000;");
fwrite($hell, "padding: 10px 10px 30px 10px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_geraeteproblem_meldung {");
fwrite($hell, "margin-top: 20px;");
fwrite($hell, "}");

fwrite($hell, ".cms_geraeteproblem_meldung textarea {");
fwrite($hell, "height: 50px !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_kurse_kursklassen .cms_notiz {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P ul.cms_bloguebersicht a p img {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H ul.cms_bloguebersicht a p img {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, "#cms_gruppe_icon_auswahl {");
fwrite($hell, "display: block;");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 0px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "width: 100%;");
fwrite($hell, "max-width: 800px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "padding: 10px;");
fwrite($hell, "margin-bottom: 15px;");
fwrite($hell, "border-radius: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_nicht_genehmigt {");
fwrite($hell, "opacity: .35;");
fwrite($hell, "}");

fwrite($hell, ".cms_genehmigungausstehend {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "padding: 5px !important;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_auftragausstehend {");
fwrite($hell, "padding: 5px !important;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "}");

fwrite($hell, ".cms_auftragerledigt {");
fwrite($hell, "padding: 5px !important;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "}");

fwrite($hell, ".cms_spamschutz {");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_vorlaeufig {");
fwrite($hell, "opacity: .5;");
fwrite($hell, "}");

fwrite($hell, ".cms_vollbild {");
fwrite($hell, "position: fixed;");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "top: 0px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "z-index: 10000000;");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 20px;");
fwrite($hell, "font-size: 120%;");
fwrite($hell, "}");

fwrite($hell, ".cms_gesichert {");
fwrite($hell, "margin-top: 20px;");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "}");

fwrite($hell, ".cms_reitermenue_i .cms_gesichert, .cms_reitermenue_i .cms_meldung_laden {");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_fortschritt_box {");
fwrite($hell, "margin-top:15px;");
fwrite($hell, "}");

fwrite($hell, ".cms_legende {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_legende span {");
fwrite($hell, "width: 33.3333333%;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, "#cms_speicherplatz_frei {");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 30px;");
fwrite($hell, "margin-bottom: 3px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "line-height: 1;");
fwrite($hell, "overflow:hidden;");
fwrite($hell, "border-radius: ".$_POST['cms_style_haupt_radiussehrgross'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_speicherplatz_system_balken, #cms_speicherplatz_website_balken,");
fwrite($hell, "#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_gruppen_balken,");
fwrite($hell, "#cms_speicherplatz_personen_balken {");
fwrite($hell, "height: 100%;");
fwrite($hell, "width: 0%;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "line-height: 1;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, "#cms_speicherplatz_system_icon, #cms_speicherplatz_website_icon,");
fwrite($hell, "#cms_speicherplatz_schulhof_icon, #cms_speicherplatz_gruppen_icon,");
fwrite($hell, "#cms_speicherplatz_personen_icon {");
fwrite($hell, "height: 15px;");
fwrite($hell, "width: 15px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "border-radius: 10px;");
fwrite($hell, "}");

fwrite($hell, "#cms_speicherplatz_system_balken, #cms_speicherplatz_system_icon {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($hell, "#cms_speicherplatz_website_balken, #cms_speicherplatz_website_icon {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");
fwrite($hell, "#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_schulhof_icon {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
fwrite($hell, "#cms_speicherplatz_gruppen_balken, #cms_speicherplatz_gruppen_icon {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($hell, "#cms_speicherplatz_personen_balken, #cms_speicherplatz_personen_icon {background: ".$_POST['cms_style_h_haupt_abstufung2'].";}");






// DARKMODE
fwrite($dunkel, "#cms_dsgvo_datenschutz .cms_meldung {");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_empfaenger, .cms_anhangtitel {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}");

fwrite($dunkel, ".cms_postfach_papierkorb_aussen {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_postfach_papierkorb_innen {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_signatur, .cms_originalnachricht_meta {");
fwrite($dunkel, "border-top: 1px dotted ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_originalnachricht {");
fwrite($dunkel, "border-left: 2px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_debug {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_gruppe_icon_auswahl {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_genehmigungausstehend {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_auftragausstehend {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_auftragerledigt {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_spamschutz {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_vollbild {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");



fwrite($dunkel, "#cms_speicherplatz_frei {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_speicherplatz_system_balken, #cms_speicherplatz_system_icon {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($dunkel, "#cms_speicherplatz_website_balken, #cms_speicherplatz_website_icon {background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");
fwrite($dunkel, "#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_schulhof_icon {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");
fwrite($dunkel, "#cms_speicherplatz_gruppen_balken, #cms_speicherplatz_gruppen_icon {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($dunkel, "#cms_speicherplatz_personen_balken, #cms_speicherplatz_personen_icon {background: ".$_POST['cms_style_d_haupt_abstufung2'].";}");
?>
