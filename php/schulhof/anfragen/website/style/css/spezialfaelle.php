<?php
fwrite($hell, "#cms_neuerungenverlauf p + h4 {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_sichtbar {\n");
fwrite($hell, "max-height: 2500px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_dsgvo_datenschutz {\n");
fwrite($hell, "position:fixed;\n");
fwrite($hell, "bottom:0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "padding:10px;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_dsgvo_datenschutz .cms_meldung {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_netzcheckstatus span {font-size: inherit;}\n");

fwrite($hell, ".cms_filter_ein {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_absender {text-align: right;}\n");
fwrite($hell, ".cms_empfaenger, .cms_anhangtitel {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");

fwrite($hell, ".cms_postfach_papierkorb_aussen {\n");
fwrite($hell, "width: 6px;\n");
fwrite($hell, "height: 23px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "line-height: 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "bottom: -6px;\n");
fwrite($hell, "margin-bottom: 2px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_papierkorb_innen {\n");
fwrite($hell, "width: 6px;\n");
fwrite($hell, "height: 23px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button .cms_postfach_anhang {\n");
fwrite($hell, "margin-right: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_anhang {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-right: 10px;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_anhang img {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "top: 3px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_anhang .cms_button_nein {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: -3px;\n");
fwrite($hell, "z-index: 1;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_anhang:hover .cms_button_nein {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_signatur, .cms_originalnachricht_meta {\n");
fwrite($hell, "margin-top: 15px;\n");
fwrite($hell, "padding-top:5px;\n");
fwrite($hell, "border-top: 1px dotted ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_originalnachricht {\n");
fwrite($hell, "padding-left: 5px;\n");
fwrite($hell, "border-left: 2px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_versteckt {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");


fwrite($hell, "#cms_debug {\n");
fwrite($hell, "bottom: 0px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "z-index: 1000;\n");
fwrite($hell, "padding: 10px 10px 30px 10px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_geraeteproblem_meldung {\n");
fwrite($hell, "margin-top: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_geraeteproblem_meldung textarea {\n");
fwrite($hell, "height: 50px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_kurse_kursklassen .cms_notiz {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P ul.cms_bloguebersicht a p img {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H ul.cms_bloguebersicht a p img {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_gruppe_icon_auswahl {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "max-width: 800px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "margin-bottom: 15px;\n");
fwrite($hell, "border-radius: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_nicht_genehmigt {\n");
fwrite($hell, "opacity: .35;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_genehmigungausstehend {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "padding: 5px !important;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auftragausstehend {\n");
fwrite($hell, "padding: 5px !important;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auftragerledigt {\n");
fwrite($hell, "padding: 5px !important;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ'].";\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_spamschutz {\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vorlaeufig {\n");
fwrite($hell, "opacity: .5;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vollbild {\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "z-index: 10000000;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 20px;\n");
fwrite($hell, "font-size: 120%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_gesichert {\n");
fwrite($hell, "margin-top: 20px;\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reitermenue_i .cms_gesichert, .cms_reitermenue_i .cms_meldung_laden {\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_fortschritt_box {\n");
fwrite($hell, "margin-top:15px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_legende {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_legende span {\n");
fwrite($hell, "width: 33.3333333%;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_speicherplatz_frei {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 30px;\n");
fwrite($hell, "margin-bottom: 3px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "line-height: 1;\n");
fwrite($hell, "overflow:hidden;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_haupt_radiussehrgross'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_speicherplatz_system_balken, #cms_speicherplatz_website_balken,\n");
fwrite($hell, "#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_gruppen_balken,\n");
fwrite($hell, "#cms_speicherplatz_personen_balken {\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "width: 0%;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "line-height: 1;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_speicherplatz_system_icon, #cms_speicherplatz_website_icon,\n");
fwrite($hell, "#cms_speicherplatz_schulhof_icon, #cms_speicherplatz_gruppen_icon,\n");
fwrite($hell, "#cms_speicherplatz_personen_icon {\n");
fwrite($hell, "height: 15px;\n");
fwrite($hell, "width: 15px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "border-radius: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_speicherplatz_system_balken, #cms_speicherplatz_system_icon {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($hell, "#cms_speicherplatz_website_balken, #cms_speicherplatz_website_icon {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");
fwrite($hell, "#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_schulhof_icon {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}\n");
fwrite($hell, "#cms_speicherplatz_gruppen_balken, #cms_speicherplatz_gruppen_icon {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($hell, "#cms_speicherplatz_personen_balken, #cms_speicherplatz_personen_icon {background: ".$_POST['cms_style_h_haupt_abstufung2'].";}\n");






// DARKMODE
fwrite($dunkel, "#cms_dsgvo_datenschutz .cms_meldung {\n");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_empfaenger, .cms_anhangtitel {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");

fwrite($dunkel, ".cms_postfach_papierkorb_aussen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_postfach_papierkorb_innen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_signatur, .cms_originalnachricht_meta {\n");
fwrite($dunkel, "border-top: 1px dotted ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_originalnachricht {\n");
fwrite($dunkel, "border-left: 2px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_debug {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_gruppe_icon_auswahl {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_genehmigungausstehend {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_auftragausstehend {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_auftragerledigt {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ'].";\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_spamschutz {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_vollbild {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");



fwrite($dunkel, "#cms_speicherplatz_frei {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_speicherplatz_system_balken, #cms_speicherplatz_system_icon {background: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";}\n");
fwrite($dunkel, "#cms_speicherplatz_website_balken, #cms_speicherplatz_website_icon {background: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";}\n");
fwrite($dunkel, "#cms_speicherplatz_schulhof_balken, #cms_speicherplatz_schulhof_icon {background: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";}\n");
fwrite($dunkel, "#cms_speicherplatz_gruppen_balken, #cms_speicherplatz_gruppen_icon {background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, "#cms_speicherplatz_personen_balken, #cms_speicherplatz_personen_icon {background: ".$_POST['cms_style_d_haupt_abstufung2'].";}\n");
?>
