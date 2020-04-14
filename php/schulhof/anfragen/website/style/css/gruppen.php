<?php
fwrite($hell, ".cms_uebersicht .cms_ersteller {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht .cms_blogeintrag:hover .cms_ersteller,");
fwrite($hell, ".cms_uebersicht tr:hover .cms_ersteller  {");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_blogeintrag, .cms_beschlusseintrag {");
fwrite($hell, "padding-left: 5px !important;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschlusseintrag {");
fwrite($hell, "padding-right: 26px !important;");
fwrite($hell, "min-height: 45px;");
fwrite($hell, "}");

fwrite($hell, ".cms_blogeintrag:hover, .cms_beschlusseintrag:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_blogliste_details:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_blogeintrag p.cms_inhaltvorschau, .cms_beschlusseintrag p.cms_inhaltvorschau {");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_angenommen {color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_beschluss_abgelehnt {color: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($hell, ".cms_beschluss_vertagt {color: ".$_POST['cms_style_h_haupt_abstufung2'].";}");

fwrite($hell, ".cms_beschlusseintrag p.cms_beschlussicons {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 5px;");
fwrite($hell, "right: 5px;");
fwrite($hell, "width: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_icon {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-bottom: 5px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_icon:last-child {");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_aktionen_uebersicht li > p {");
fwrite($hell, "padding: 4px 5px 4px 5px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_aktionen_uebersicht li .cms_beschlusseintrag {");
fwrite($hell, "border-bottom: none !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_aktionen_uebersicht li:last-child > p {");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_gruppen_oeffentlich_art {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "width: 16px;");
fwrite($hell, "height: 16px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 5px;");
fwrite($hell, "top: 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, ".cms_oe, .cms_in {");
fwrite($hell, "display: block;");
fwrite($hell, "width: 16px;");
fwrite($hell, "height: 16px;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-radius: 8px;");
fwrite($hell, "}");

fwrite($hell, ".cms_oe {background: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";}");
fwrite($hell, ".cms_in {background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");

fwrite($hell, ".cms_beschluss {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "display: block;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss h4, .cms_beschluss p {");
fwrite($hell, "display: block;");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss:hover {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_pro {");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_contra {");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_enthaltung {");
fwrite($hell, "border-left: 3px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_stimmen {");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_stimmen_pro {background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_beschluss_stimmen_contra {background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($hell, ".cms_beschluss_stimmen_enthaltung {background: ".$_POST['cms_style_h_haupt_abstufung2'].";}");

fwrite($hell, ".cms_beschluss_stimmen_pro, .cms_beschluss_stimmen_contra, .cms_beschluss_stimmen_enthaltung, .cms_beschluss_langfristig {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "padding: 2px 7px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "text-align: center;");
fwrite($hell, "min-width: 25px;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_stimmen_pro {");
fwrite($hell, "border-top-left-radius: 7px;");
fwrite($hell, "border-bottom-left-radius: 7px;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_stimmen_contra {");
fwrite($hell, "border-top-right-radius: 7px;");
fwrite($hell, "border-bottom-right-radius: 7px;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschluss_langfristig {");
fwrite($hell, "margin-left: 10px;");
fwrite($hell, "border-radius: 7px;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_beschlussuebersicht_jahr {");
fwrite($hell, "display: flex;");
fwrite($hell, "}");

fwrite($hell, ".cms_beschlussuebersicht_jahr .cms_beschluss {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "width: 25%;");
fwrite($hell, "border-right: 10px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_chat {");
fwrite($hell, "#cms_chat_nachrichten {");
fwrite($hell, ".cms_chat_datum {");
fwrite($hell, "text-align: center;");
fwrite($hell, "margin-bottom: 10px;");
fwrite($hell, "}");
fwrite($hell, "width: 100%;");
fwrite($hell, "padding: 5px 20px;");
fwrite($hell, "max-height: 500px;");
fwrite($hell, "overflow-y: auto;");
fwrite($hell, ".cms_chat_nachricht_aussen {");
fwrite($hell, "width: 100%;");
fwrite($hell, "margin-bottom: 15px;");
fwrite($hell, "float: left;");
fwrite($hell, ".cms_chat_nachricht_innen {");
fwrite($hell, "position: relative;");
fwrite($hell, "min-width: 40%;");
fwrite($hell, "max-width: 60%;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_chat_gegenueber'].";");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "float: left;");
fwrite($hell, "padding: 5px;");
fwrite($hell, ".cms_chat_nachricht_aktion {");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 0;");
fwrite($hell, "right: 0;");
fwrite($hell, "padding: inherit;");
fwrite($hell, "&[data-aktion=sendend] {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, "&[data-aktion=mehr] {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, ".cms_chat_aktion {");
fwrite($hell, "// .cms_hinweis");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";");
fwrite($hell, "padding: 0px 5px 0px 5px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "font-family: 'robl';");
fwrite($hell, "font-weight: normal !important;");
fwrite($hell, "display: none;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "z-index: 50;");
fwrite($hell, "width: 150px;");
fwrite($hell, "overflow: visible;");
fwrite($hell, "left: 0px;");
fwrite($hell, "bottom: 25px;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-align: left;");

fwrite($hell, "z-index: 5;");
fwrite($hell, "p {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "img {");
fwrite($hell, "height: 16px;");
fwrite($hell, "width: 16px;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, ".cms_chat_nachricht_id {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, ".cms_chat_nachricht_autor {");
fwrite($hell, "font-size: 90%;");
fwrite($hell, "}");
fwrite($hell, ".cms_chat_nachricht_nachricht {");
fwrite($hell, "padding-left: 5px;");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "white-space: pre-wrap;");
fwrite($hell, "word-break: break-word");
fwrite($hell, "}");
fwrite($hell, ".cms_chat_nachricht_zeit {");
fwrite($hell, "float: right;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_nachricht_eigen {");
fwrite($hell, ".cms_chat_nachricht_innen {");
fwrite($hell, "float: right;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_chat_eigen'].";");
fwrite($hell, ".cms_chat_nachricht_aktion {");
fwrite($hell, "&[data-aktion=mehr] {");
fwrite($hell, "display: none;");
fwrite($hell, ".cms_chat_aktion {");
fwrite($hell, "text-align: right;");
fwrite($hell, "left: unset;");
fwrite($hell, "right: 0;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_nachricht_sendend {");
fwrite($hell, "opacity: 0.8;");
fwrite($hell, ".cms_chat_nachricht_innen {");
fwrite($hell, ".cms_chat_nachricht_aktion {");
fwrite($hell, "&[data-aktion=sendend] {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "&[data-aktion=mehr] {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_nachricht_gemeldet {");
fwrite($hell, "opacity: 0.8;");
fwrite($hell, ".cms_chat_nachricht_innen {");
fwrite($hell, "background-color: mix(".$_POST['cms_style_h_haupt_meldungfehlerhinter'].", white);");
fwrite($hell, ".cms_chat_nachricht_aktion {");
fwrite($hell, "&[data-aktion=mehr] {");
fwrite($hell, "[data-mehr=melden] {");
fwrite($hell, "opacity: 0.7;");
fwrite($hell, "cursor: default;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_nachricht_geloescht {");
fwrite($hell, "opacity: 0.7;");
fwrite($hell, ".cms_chat_nachricht_innen {");
fwrite($hell, ".cms_chat_nachricht_aktion {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, ".cms_chat_nachricht_nachricht {");
fwrite($hell, "font-style: italic;");
fwrite($hell, "font-size: 90%;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "#cms_chat_nachricht_verfassen {");
fwrite($hell, "width: 100%;");
fwrite($hell, "label {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");
fwrite($hell, "textarea {");
fwrite($hell, "width: 90%; // Fallback");
fwrite($hell, "width: ~'calc(100% - 26px)';");
fwrite($hell, "}");
fwrite($hell, ".cms_meldung_fehler {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, "div:not(.cms_meldung_fehler) {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "float: right;");
fwrite($hell, "width: auto;");
fwrite($hell, "img {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "#cms_chat_mehr {");
fwrite($hell, "color: ".$_POST['cms_style_h_link_schrift'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, "#cms_chat_status,");
fwrite($hell, "#cms_chat_laden,");
fwrite($hell, "#cms_chat_leer,");
fwrite($hell, "#cms_chat_mehr {");
fwrite($hell, "text-align: center;");
fwrite($hell, "margin-top: 10px;");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "h3 {");
fwrite($hell, "margin-top: 0;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "#cms_chat_status,");
fwrite($hell, "#cms_chat_laden,");
fwrite($hell, "#cms_chat_berechtigung,");
fwrite($hell, "#cms_chat_leer,");
fwrite($hell, "#cms_chat_mehr,");
fwrite($hell, "#cms_chat_stumm {");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, "// Nach Priorität:");
fwrite($hell, "&.cms_chat_leer { // 0");
fwrite($hell, "#cms_chat_leer {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_mehr { // 0");
fwrite($hell, "#cms_chat_mehr {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_stumm { // 0.1");
fwrite($hell, ">#cms_chat_nachricht_verfassen {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, "#cms_chat_stumm {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_status { // 1");
fwrite($hell, ">* {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, "#cms_chat_status {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_laden {  // 1.1");
fwrite($hell, "#cms_chat_laden {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "&.cms_chat_berechtigung { // 2");
fwrite($hell, ">* {");
fwrite($hell, "display: none;");
fwrite($hell, "}");
fwrite($hell, "#cms_chat_berechtigung {");
fwrite($hell, "display: block;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");





// DARKMODE
fwrite($dunkel, ".cms_uebersicht .cms_ersteller {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_blogeintrag:hover, .cms_beschlusseintrag:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss_angenommen {color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_beschluss_abgelehnt {color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($dunkel, ".cms_beschluss_vertagt {color: ".$_POST['cms_style_d_haupt_abstufung2'].";}");

fwrite($dunkel, ".cms_aktionen_uebersicht li:last-child > p {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_oe {background: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";}");
fwrite($dunkel, ".cms_in {background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");

fwrite($dunkel, ".cms_beschluss {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss_pro {");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss_contra {");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss_enthaltung {");
fwrite($dunkel, "border-left: 3px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss_stimmen_pro {background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_beschluss_stimmen_contra {background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($dunkel, ".cms_beschluss_stimmen_enthaltung {background: ".$_POST['cms_style_d_haupt_abstufung2'].";}");

fwrite($dunkel, ".cms_beschluss_stimmen_pro, .cms_beschluss_stimmen_contra, .cms_beschluss_stimmen_enthaltung, .cms_beschluss_langfristig {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschluss_langfristig {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_beschlussuebersicht_jahr .cms_beschluss {");
fwrite($dunkel, "border-right: 10px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_chat {");
fwrite($dunkel, "#cms_chat_nachrichten {");
fwrite($dunkel, ".cms_chat_nachricht_aussen {");
fwrite($dunkel, ".cms_chat_nachricht_innen {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_chat_gegenueber'].";");
fwrite($dunkel, ".cms_chat_nachricht_aktion {");
fwrite($dunkel, "&[data-aktion=mehr] {");
fwrite($dunkel, ".cms_chat_aktion {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
fwrite($dunkel, "&.cms_chat_nachricht_eigen {");
fwrite($dunkel, ".cms_chat_nachricht_innen {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_chat_eigen'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
fwrite($dunkel, "&.cms_chat_nachricht_gemeldet {");
fwrite($dunkel, "opacity: 0.8;");
fwrite($dunkel, ".cms_chat_nachricht_innen {");
fwrite($dunkel, "background-color: mix(".$_POST['cms_style_h_haupt_meldungfehlerakzent'].", white);");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_chat_mehr {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_link_schrift'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
?>
