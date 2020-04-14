<?php
fwrite($hell, ".cms_terminuebersicht {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminuebersicht li {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding-top: 5px;\n");
fwrite($hell, "padding-bottom: 5px;\n");
fwrite($hell, "padding-right: 5px;\n");
fwrite($hell, "padding-left: 80px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "min-height: 70px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalenderblaetter {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalender_zusatzinfo {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "padding: 2px 0px 2px 20px;\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "background-position: left center;\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "margin-right: 10px;\n");
fwrite($hell, "margin-bottom: 5px;\n");
fwrite($hell, "min-height: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_kalender_zusatzinfo_intern {\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "padding: 5px 5px 5px 25px;\n");
fwrite($hell, "background-position: 5px center;\n");
fwrite($hell, "background-image: url('../res/icons/oegruppen/intern.png');\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink p, .cms_terminlink h3, .cms_terminlink span {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_notiz {margin-top: 0px;}\n");
fwrite($hell, ".cms_terminlink p:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_terminlink:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_terminlink:hover .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblaetter {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: 5px;\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "width: 70px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_i,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_i {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 32px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagbez,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagbez {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "line-height: 1.2em !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kalenderklein_hintergrundmonat'].";\n");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalenderklein_schriftdickemonat'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kalenderklein_farbemonat'].";\n");
fwrite($hell, "font-size: 10px;\n");
fwrite($hell, "border-top: ".$_POST['cms_style_kalenderklein_linienstaerkeobenmonat'].";\n");
fwrite($hell, "border-left: ".$_POST['cms_style_kalenderklein_linienstaerkelinksmonat'].";\n");
fwrite($hell, "border-right: ".$_POST['cms_style_kalenderklein_linienstaerkerechtsmonat'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalenderklein_linienstaerkeuntenmonat'].";\n");
fwrite($hell, "padding: 2px 0px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalenderklein_radiusobenmonat'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalenderklein_radiusobenmonat'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalenderklein_radiusuntenmonat'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalenderklein_radiusuntenmonat'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalenderklein_linienfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kalenderklein_hintergrundtagnr'].";\n");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalenderklein_schriftdicketagnr'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kalenderklein_farbetagnr'].";\n");
fwrite($hell, "font-size: 18px;\n");
fwrite($hell, "border-top: ".$_POST['cms_style_kalenderklein_linienstaerkeobentagnr'].";\n");
fwrite($hell, "border-left: ".$_POST['cms_style_kalenderklein_linienstaerkelinkstagnr'].";\n");
fwrite($hell, "border-right: ".$_POST['cms_style_kalenderklein_linienstaerkerechtstagnr'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalenderklein_linienstaerkeuntentagnr'].";\n");
fwrite($hell, "padding: 2px 0px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalenderklein_radiusobentagnr'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalenderklein_radiusobentagnr'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagnr'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagnr'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalenderklein_linienfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagbez,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagbez {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kalenderklein_hintergrundtagbez'].";\n");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalenderklein_schriftdicketagbez'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kalenderklein_farbetagbez'].";\n");
fwrite($hell, "font-size: 10px;\n");
fwrite($hell, "border-top: ".$_POST['cms_style_kalenderklein_linienstaerkeobentagbez'].";\n");
fwrite($hell, "border-left: ".$_POST['cms_style_kalenderklein_linienstaerkelinkstagbez'].";\n");
fwrite($hell, "border-right: ".$_POST['cms_style_kalenderklein_linienstaerkerechtstagbez'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalenderklein_linienstaerkeuntentagbez'].";\n");
fwrite($hell, "padding: 2px 0px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalenderklein_radiusobentagbez'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalenderklein_radiusobentagbez'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagbez'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagbez'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalenderklein_linienfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_uhrzeit {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "font-weight: normal;\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "line-height: 1.2em;\n");
fwrite($hell, "padding: 5px 0px 0px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblaetter {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt {\n");
fwrite($hell, "width: 40%;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_i {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "line-height: 1.2em !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kalendergross_hintergrundmonat'].";\n");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalendergross_schriftdickemonat'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kalendergross_farbemonat'].";\n");
fwrite($hell, "font-size: 20px;\n");
fwrite($hell, "border-top: ".$_POST['cms_style_kalendergross_linienstaerkeobenmonat'].";\n");
fwrite($hell, "border-left: ".$_POST['cms_style_kalendergross_linienstaerkelinksmonat'].";\n");
fwrite($hell, "border-right: ".$_POST['cms_style_kalendergross_linienstaerkerechtsmonat'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalendergross_linienstaerkeuntenmonat'].";\n");
fwrite($hell, "border-bottom: none;\n");
fwrite($hell, "padding: 4px 0px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalendergross_radiusobenmonat'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalendergross_radiusobenmonat'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalendergross_radiusuntenmonat'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalendergross_radiusuntenmonat'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalendergross_linienfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kalendergross_hintergrundtagnr'].";\n");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalendergross_schriftdicketagnr'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kalendergross_farbetagnr'].";\n");
fwrite($hell, "font-size: 45px;\n");
fwrite($hell, "border-top: ".$_POST['cms_style_kalendergross_linienstaerkeobentagnr'].";\n");
fwrite($hell, "border-left: ".$_POST['cms_style_kalendergross_linienstaerkelinkstagnr'].";\n");
fwrite($hell, "border-right: ".$_POST['cms_style_kalendergross_linienstaerkerechtstagnr'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalendergross_linienstaerkeuntentagnr'].";\n");
fwrite($hell, "padding: 8px 0px 4px 0px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalendergross_radiusobentagnr'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalendergross_radiusobentagnr'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalendergross_radiusuntentagnr'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalendergross_radiusuntentagnr'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalendergross_linienfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_kalendergross_hintergrundtagbez'].";\n");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalendergross_schriftdicketagbez'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_kalendergross_farbetagbez'].";\n");
fwrite($hell, "font-size: 20px;\n");
fwrite($hell, "border-top: ".$_POST['cms_style_kalendergross_linienstaerkeobentagbez'].";\n");
fwrite($hell, "border-left: ".$_POST['cms_style_kalendergross_linienstaerkelinkstagbez'].";\n");
fwrite($hell, "border-right: ".$_POST['cms_style_kalendergross_linienstaerkerechtstagbez'].";\n");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalendergross_linienstaerkeuntentagbez'].";\n");
fwrite($hell, "padding: 4px 0px;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalendergross_radiusobentagbez'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalendergross_radiusobentagbez'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalendergross_radiusuntentagbez'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalendergross_radiusuntentagbez'].";\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalendergross_linienfarbe'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detailinformationen {\n");
fwrite($hell, "margin-top: 15px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termindetails {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 7px 0px 0px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termindetails li {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "margin-right: 0px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termindetails_zusatzinfo {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "padding: 2px 0px 2px 20px;\n");
fwrite($hell, "background-position: left center;\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "margin-right: 0px;\n");
fwrite($hell, "min-height: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termindetails_zusatzinfo:hover {\n");
fwrite($hell, "cursor: pointer !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termin_detailinformationen h3 {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ferienkalender {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border-spacing: 0px;\n");
fwrite($hell, "border-collapse: collapse;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ferienkalender th {\n");
fwrite($hell, "width:8.33333%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "padding: 2px 5px;\n");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ferienkalender td {\n");
fwrite($hell, "padding: 2px 5px;\n");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ferienkalender td:last-child,\n");
fwrite($hell, ".cms_ferienkalender th:last-child {\n");
fwrite($hell, "border-right: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ferienkalender_inhalt {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ferienkalender_we {background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}\n");
fwrite($hell, ".cms_ferienkalender_frei {background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");





// DARKMODE
fwrite($dunkel, ".cms_terminuebersicht {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_terminuebersicht li {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_kalender_zusatzinfo_intern {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_terminlink p, .cms_terminlink h3, .cms_terminlink span {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_terminlink:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, ".cms_terminlink:hover .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_terminlink .cms_kalenderblatt_monat,\n");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,\n");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalenderklein_hintergrundmonat'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalenderklein_farbemonat'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalenderklein_linienfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_terminlink .cms_kalenderblatt_tagnr,\n");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,\n");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalenderklein_hintergrundtagnr'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalenderklein_farbetagnr'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalenderklein_linienfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_terminlink .cms_kalenderblatt_tagbez,\n");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalenderklein_hintergrundtagbez'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalenderklein_farbetagbez'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalenderklein_linienfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalendergross_hintergrundmonat'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalendergross_farbemonat'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalendergross_linienfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalendergross_hintergrundtagnr'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalendergross_farbetagnr'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalendergross_linienfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalendergross_hintergrundtagbez'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalendergross_farbetagbez'].";\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalendergross_linienfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_ferienkalender th {\n");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_ferienkalender td {\n");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_ferienkalender_we {background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}\n");
fwrite($dunkel, ".cms_ferienkalender_frei {background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
