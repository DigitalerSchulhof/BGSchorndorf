<?php
fwrite($hell, "input, textarea, select, .cms_wahl {");
fwrite($hell, "font-weight: normal;");
fwrite($hell, "padding: 5px 7px;");
fwrite($hell, "border-top-right-radius: 3px;");
fwrite($hell, "border-top-left-radius: 3px;");
fwrite($hell, "border-bottom-right-radius: 3px;");
fwrite($hell, "border-bottom-left-radius: 3px;");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhintergrund'].";");
fwrite($hell, "border: none;");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_formular_feldfocushintergrund'].";");
fwrite($hell, "width: 100%;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, "textarea.cms_textarea {");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 250px;");
fwrite($hell, "}");

fwrite($hell, "input:hover, textarea:hover, select:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhoverhintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "input:focus, textarea:focus, select:focus {");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldfocushintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "input.cms_klein, select.cms_klein {");
fwrite($hell, "width: 35%;");
fwrite($hell, "}");

fwrite($hell, "input.cms_gross, select.cms_gross {");
fwrite($hell, "width: 60%;");
fwrite($hell, "}");

fwrite($hell, "span.cms_input_Tbez {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 30px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "input.cms_input_h, input.cms_input_m, input.cms_input_T, input.cms_input_M, input.cms_input_klein {");
fwrite($hell, "width: 30px;");
fwrite($hell, "}");

fwrite($hell, "input.cms_input_J {");
fwrite($hell, "width: 60px;");
fwrite($hell, "}");

fwrite($hell, ".cms_nutzerkonto_postfach_nachricht {");
fwrite($hell, "padding: 0px 7px;");
fwrite($hell, "}");

fwrite($hell, ".cms_nutzerkonto_postfach_nachricht textarea {");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 300px;");
fwrite($hell, "}");

fwrite($hell, ".cms_personensuche_feld,");
fwrite($hell, ".cms_gruppensuche_feld {");
fwrite($hell, "width: 100%;");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: 0px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-bottom-right-radius: 5px;");
fwrite($hell, "border-bottom-left-radius: 5px;");
fwrite($hell, "display: none;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "box-shadow: ".$_POST['cms_style_h_haupt_hintergrund']." 0px 0px 7px;");
fwrite($hell, "}");

fwrite($hell, ".cms_personensuche_feld input {");
fwrite($hell, "width: 100% !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_personensuche_feld_aussen,");
fwrite($hell, ".cms_gruppensuche_feld_aussen {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_personenauswahl {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-right: 25px;");
fwrite($hell, "position: relative;");
fwrite($hell, "font-family: 'robl', sans-serif;");
fwrite($hell, "}");

fwrite($hell, ".cms_personenauswahl:hover .cms_personenauswahl_schliessen {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, ".cms_personenauswahl:hover {");
fwrite($hell, "cursor: default;");
fwrite($hell, "}");

fwrite($hell, ".cms_fenster_schliessen {");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 0px;");
fwrite($hell, "top: -20px;");
fwrite($hell, "}");

fwrite($hell, ".cms_personenauswahl_schliessen {");
fwrite($hell, "display: none;");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: -8px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "z-index: 5;");
fwrite($hell, "}");



fwrite($hell, ".cms_schieber_o_aktiv, .cms_schieber_o_inaktiv {");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "border-radius: 11px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 40px;");
fwrite($hell, "line-height: 0px !important;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "text-align: left;");
fwrite($hell, "}");

fwrite($hell, ".cms_schieber_o_aktiv {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_schieber_o_inaktiv {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_schieber_o_aktiv .cms_schieber_i {");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_schieber_o_inaktiv .cms_schieber_i {");
fwrite($hell, "margin-left: 18px;");
fwrite($hell, "}");

fwrite($hell, ".cms_schieber_i {");
fwrite($hell, "width: 20px;");
fwrite($hell, "height: 20px;");
fwrite($hell, "border-radius: 10px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_schieber_o_aktiv:hover, .cms_schieber_o_inaktiv:hover {");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_eingabe_icon {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "position: relative;");
fwrite($hell, "width: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_eingabe_icon img {");
fwrite($hell, "bottom: -5px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "}");

fwrite($hell, ".cms_vorschau img, .cms_vorschau video {");
fwrite($hell, "max-width: 100%;");
fwrite($hell, "max-height: 300px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateiwahl_tabelle td:last-child {");
fwrite($hell, "text-align: left !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateiwahl_tabelle td:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_notizzettel {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "height: 200px;");
fwrite($hell, "resize: vertical;");
fwrite($hell, "}");

fwrite($hell, ".cms_notizzettelleer {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_notizzettel:hover, .cms_notizzettel:focus {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_farbwahl_rgb {");
fwrite($hell, "height: 30px;");
fwrite($hell, "width: 70%;");
fwrite($hell, "}");
fwrite($hell, ".cms_farbwahl_alpha {width: 20%;}");






// DARKMODE
fwrite($dunkel, "input, textarea, select, .cms_wahl {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhintergrund'].";");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_formular_feldfocushintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "input:hover, textarea:hover, select:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhoverhintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "input:focus, textarea:focus, select:focus {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldfocushintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_personensuche_feld,");
fwrite($dunkel, ".cms_gruppensuche_feld {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "box-shadow: ".$_POST['cms_style_d_haupt_hintergrund']." 0px 0px 7px;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_schieber_o_aktiv, .cms_schieber_o_inaktiv {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_schieber_o_aktiv {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_schieber_o_inaktiv {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_schieber_i {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_schieber_o_aktiv:hover, .cms_schieber_o_inaktiv:hover {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_notizzettel {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_notizzettelleer {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_notizzettel:hover, .cms_notizzettel:focus {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");
?>
