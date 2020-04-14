<?php
fwrite($hell, ".cms_terminuebersicht {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_terminuebersicht li {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink {");
fwrite($hell, "width: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "padding-top: 5px;");
fwrite($hell, "padding-bottom: 5px;");
fwrite($hell, "padding-right: 5px;");
fwrite($hell, "padding-left: 80px;");
fwrite($hell, "position: relative;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "min-height: 70px;");
fwrite($hell, "}");

fwrite($hell, ".cms_kalenderblaetter {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_kalender_zusatzinfo {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "padding: 2px 0px 2px 20px;");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "background-position: left center;");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "margin-right: 10px;");
fwrite($hell, "margin-bottom: 5px;");
fwrite($hell, "min-height: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_kalender_zusatzinfo_intern {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "padding: 5px 5px 5px 25px;");
fwrite($hell, "background-position: 5px center;");
fwrite($hell, "background-image: url('../res/icons/oegruppen/intern.png');");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink p, .cms_terminlink h3, .cms_terminlink span {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_notiz {margin-top: 0px;}");
fwrite($hell, ".cms_terminlink p:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_terminlink:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");
fwrite($hell, ".cms_terminlink:hover .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblaetter {");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: 5px;");
fwrite($hell, "top: 5px;");
fwrite($hell, "width: 70px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_i,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_i {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 32px;");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagbez,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagbez {");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "width: 100%;");
fwrite($hell, "line-height: 1.2em !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat {");
fwrite($hell, "background: ".$_POST['cms_style_h_kalenderklein_hintergrundmonat'].";");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalenderklein_schriftdickemonat'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kalenderklein_farbemonat'].";");
fwrite($hell, "font-size: 10px;");
fwrite($hell, "border-top: ".$_POST['cms_style_kalenderklein_linienstaerkeobenmonat'].";");
fwrite($hell, "border-left: ".$_POST['cms_style_kalenderklein_linienstaerkelinksmonat'].";");
fwrite($hell, "border-right: ".$_POST['cms_style_kalenderklein_linienstaerkerechtsmonat'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalenderklein_linienstaerkeuntenmonat'].";");
fwrite($hell, "padding: 2px 0px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalenderklein_radiusobenmonat'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalenderklein_radiusobenmonat'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalenderklein_radiusuntenmonat'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalenderklein_radiusuntenmonat'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalenderklein_linienfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr {");
fwrite($hell, "background: ".$_POST['cms_style_h_kalenderklein_hintergrundtagnr'].";");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalenderklein_schriftdicketagnr'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kalenderklein_farbetagnr'].";");
fwrite($hell, "font-size: 18px;");
fwrite($hell, "border-top: ".$_POST['cms_style_kalenderklein_linienstaerkeobentagnr'].";");
fwrite($hell, "border-left: ".$_POST['cms_style_kalenderklein_linienstaerkelinkstagnr'].";");
fwrite($hell, "border-right: ".$_POST['cms_style_kalenderklein_linienstaerkerechtstagnr'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalenderklein_linienstaerkeuntentagnr'].";");
fwrite($hell, "padding: 2px 0px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalenderklein_radiusobentagnr'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalenderklein_radiusobentagnr'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagnr'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagnr'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalenderklein_linienfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_tagbez,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagbez {");
fwrite($hell, "background: ".$_POST['cms_style_h_kalenderklein_hintergrundtagbez'].";");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalenderklein_schriftdicketagbez'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kalenderklein_farbetagbez'].";");
fwrite($hell, "font-size: 10px;");
fwrite($hell, "border-top: ".$_POST['cms_style_kalenderklein_linienstaerkeobentagbez'].";");
fwrite($hell, "border-left: ".$_POST['cms_style_kalenderklein_linienstaerkelinkstagbez'].";");
fwrite($hell, "border-right: ".$_POST['cms_style_kalenderklein_linienstaerkerechtstagbez'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalenderklein_linienstaerkeuntentagbez'].";");
fwrite($hell, "padding: 2px 0px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalenderklein_radiusobentagbez'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalenderklein_radiusobentagbez'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagbez'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalenderklein_radiusuntentagbez'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalenderklein_linienfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_terminlink .cms_kalenderblatt_uhrzeit {");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "font-weight: normal;");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "line-height: 1.2em;");
fwrite($hell, "padding: 5px 0px 0px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblaetter {");
fwrite($hell, "display: block;");
fwrite($hell, "width: 100%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt {");
fwrite($hell, "width: 40%;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_i {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "width: 100%;");
fwrite($hell, "line-height: 1.2em !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {");
fwrite($hell, "background: ".$_POST['cms_style_h_kalendergross_hintergrundmonat'].";");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalendergross_schriftdickemonat'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kalendergross_farbemonat'].";");
fwrite($hell, "font-size: 20px;");
fwrite($hell, "border-top: ".$_POST['cms_style_kalendergross_linienstaerkeobenmonat'].";");
fwrite($hell, "border-left: ".$_POST['cms_style_kalendergross_linienstaerkelinksmonat'].";");
fwrite($hell, "border-right: ".$_POST['cms_style_kalendergross_linienstaerkerechtsmonat'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalendergross_linienstaerkeuntenmonat'].";");
fwrite($hell, "border-bottom: none;");
fwrite($hell, "padding: 4px 0px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalendergross_radiusobenmonat'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalendergross_radiusobenmonat'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalendergross_radiusuntenmonat'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalendergross_radiusuntenmonat'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalendergross_linienfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {");
fwrite($hell, "background: ".$_POST['cms_style_h_kalendergross_hintergrundtagnr'].";");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalendergross_schriftdicketagnr'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kalendergross_farbetagnr'].";");
fwrite($hell, "font-size: 45px;");
fwrite($hell, "border-top: ".$_POST['cms_style_kalendergross_linienstaerkeobentagnr'].";");
fwrite($hell, "border-left: ".$_POST['cms_style_kalendergross_linienstaerkelinkstagnr'].";");
fwrite($hell, "border-right: ".$_POST['cms_style_kalendergross_linienstaerkerechtstagnr'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalendergross_linienstaerkeuntentagnr'].";");
fwrite($hell, "padding: 8px 0px 4px 0px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalendergross_radiusobentagnr'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalendergross_radiusobentagnr'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalendergross_radiusuntentagnr'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalendergross_radiusuntentagnr'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalendergross_linienfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {");
fwrite($hell, "background: ".$_POST['cms_style_h_kalendergross_hintergrundtagbez'].";");
fwrite($hell, "font-weight: ".$_POST['cms_style_kalendergross_schriftdicketagbez'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_kalendergross_farbetagbez'].";");
fwrite($hell, "font-size: 20px;");
fwrite($hell, "border-top: ".$_POST['cms_style_kalendergross_linienstaerkeobentagbez'].";");
fwrite($hell, "border-left: ".$_POST['cms_style_kalendergross_linienstaerkelinkstagbez'].";");
fwrite($hell, "border-right: ".$_POST['cms_style_kalendergross_linienstaerkerechtstagbez'].";");
fwrite($hell, "border-bottom: ".$_POST['cms_style_kalendergross_linienstaerkeuntentagbez'].";");
fwrite($hell, "padding: 4px 0px;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_kalendergross_radiusobentagbez'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_kalendergross_radiusobentagbez'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_kalendergross_radiusuntentagbez'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_kalendergross_radiusuntentagbez'].";");
fwrite($hell, "border-color: ".$_POST['cms_style_h_kalendergross_linienfarbe'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detailinformationen {");
fwrite($hell, "margin-top: 15px;");
fwrite($hell, "}");

fwrite($hell, ".cms_termindetails {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 7px 0px 0px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_termindetails li {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "margin-right: 0px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_termindetails_zusatzinfo {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "padding: 2px 0px 2px 20px;");
fwrite($hell, "background-position: left center;");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "margin-right: 0px;");
fwrite($hell, "min-height: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_termindetails_zusatzinfo:hover {");
fwrite($hell, "cursor: pointer !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_termin_detailinformationen h3 {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_ferienkalender {");
fwrite($hell, "width: 100%;");
fwrite($hell, "border-spacing: 0px;");
fwrite($hell, "border-collapse: collapse;");
fwrite($hell, "}");

fwrite($hell, ".cms_ferienkalender th {");
fwrite($hell, "width:8.33333%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "padding: 2px 5px;");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_ferienkalender td {");
fwrite($hell, "padding: 2px 5px;");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_ferienkalender td:last-child,");
fwrite($hell, ".cms_ferienkalender th:last-child {");
fwrite($hell, "border-right: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_ferienkalender_inhalt {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 50%;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_ferienkalender_we {background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
fwrite($hell, ".cms_ferienkalender_frei {background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");





// DARKMODE
fwrite($dunkel, ".cms_terminuebersicht {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_terminuebersicht li {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_kalender_zusatzinfo_intern {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_terminlink p, .cms_terminlink h3, .cms_terminlink span {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_terminlink:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");
fwrite($dunkel, ".cms_terminlink:hover .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_terminlink .cms_kalenderblatt_monat,");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalenderklein_hintergrundmonat'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalenderklein_farbemonat'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalenderklein_linienfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_terminlink .cms_kalenderblatt_tagnr,");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalenderklein_hintergrundtagnr'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalenderklein_farbetagnr'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalenderklein_linienfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_terminlink .cms_kalenderblatt_tagbez,");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalenderklein_hintergrundtagbez'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalenderklein_farbetagbez'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalenderklein_linienfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalendergross_hintergrundmonat'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalendergross_farbemonat'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalendergross_linienfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalendergross_hintergrundtagnr'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalendergross_farbetagnr'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalendergross_linienfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_kalendergross_hintergrundtagbez'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_kalendergross_farbetagbez'].";");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_kalendergross_linienfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_ferienkalender th {");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_ferienkalender td {");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_ferienkalender_we {background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");
fwrite($dunkel, ".cms_ferienkalender_frei {background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
