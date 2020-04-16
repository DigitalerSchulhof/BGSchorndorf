<?php
fwrite($hell, "input, textarea, select, .cms_wahl {\n");
fwrite($hell, "font-weight: normal;\n");
fwrite($hell, "padding: 5px 7px;\n");
fwrite($hell, "border-top-right-radius: 3px;\n");
fwrite($hell, "border-top-left-radius: 3px;\n");
fwrite($hell, "border-bottom-right-radius: 3px;\n");
fwrite($hell, "border-bottom-left-radius: 3px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhintergrund'].";\n");
fwrite($hell, "border: none;\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_formular_feldfocushintergrund'].";\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, "textarea.cms_textarea {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 250px;\n");
fwrite($hell, "}\n");

fwrite($hell, "input:hover, textarea:hover, select:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhoverhintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "input:focus, textarea:focus, select:focus {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldfocushintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "input.cms_klein, select.cms_klein {\n");
fwrite($hell, "width: 35%;\n");
fwrite($hell, "}\n");

fwrite($hell, "input.cms_gross, select.cms_gross {\n");
fwrite($hell, "width: 60%;\n");
fwrite($hell, "}\n");

fwrite($hell, "span.cms_input_Tbez {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 30px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "input.cms_input_h, input.cms_input_m, input.cms_input_T, input.cms_input_M, input.cms_input_klein {\n");
fwrite($hell, "width: 30px;\n");
fwrite($hell, "}\n");

fwrite($hell, "input.cms_input_J {\n");
fwrite($hell, "width: 60px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_nutzerkonto_postfach_nachricht {\n");
fwrite($hell, "padding: 0px 7px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_nutzerkonto_postfach_nachricht textarea {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 300px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personensuche_feld,\n");
fwrite($hell, ".cms_gruppensuche_feld {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-bottom-right-radius: 5px;\n");
fwrite($hell, "border-bottom-left-radius: 5px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "box-shadow: ".$_POST['cms_style_h_haupt_hintergrund']." 0px 0px 7px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personensuche_feld input {\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personensuche_feld_aussen,\n");
fwrite($hell, ".cms_gruppensuche_feld_aussen {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personenauswahl {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-right: 25px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "font-family: 'robl', sans-serif;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personenauswahl:hover .cms_personenauswahl_schliessen {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personenauswahl:hover {\n");
fwrite($hell, "cursor: default;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_fenster_schliessen {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 0px;\n");
fwrite($hell, "top: -20px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_personenauswahl_schliessen {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: -8px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "z-index: 5;\n");
fwrite($hell, "}\n");



fwrite($hell, ".cms_schieber_o_aktiv, .cms_schieber_o_inaktiv {\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "border-radius: 11px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 40px;\n");
fwrite($hell, "line-height: 0px !important;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schieber_o_aktiv {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schieber_o_inaktiv {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schieber_o_aktiv .cms_schieber_i {\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schieber_o_inaktiv .cms_schieber_i {\n");
fwrite($hell, "margin-left: 18px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schieber_i {\n");
fwrite($hell, "width: 20px;\n");
fwrite($hell, "height: 20px;\n");
fwrite($hell, "border-radius: 10px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schieber_o_aktiv:hover, .cms_schieber_o_inaktiv:hover {\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_eingabe_icon {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_eingabe_icon img {\n");
fwrite($hell, "bottom: -5px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vorschau img, .cms_vorschau video {\n");
fwrite($hell, "max-width: 100%;\n");
fwrite($hell, "max-height: 300px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateiwahl_tabelle td:last-child {\n");
fwrite($hell, "text-align: left !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateiwahl_tabelle td:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_notizzettel {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "height: 200px;\n");
fwrite($hell, "resize: vertical;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_notizzettelleer {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_notizzettel:hover, .cms_notizzettel:focus {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_farbwahl_rgb {\n");
fwrite($hell, "height: 30px;\n");
fwrite($hell, "width: 70%;\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_farbwahl_alpha {width: 20%;}\n");






// DARKMODE
fwrite($dunkel, "input, textarea, select, .cms_wahl {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhintergrund'].";\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_formular_feldfocushintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "input:hover, textarea:hover, select:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhoverhintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "input:focus, textarea:focus, select:focus {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldfocushintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_personensuche_feld,\n");
fwrite($dunkel, ".cms_gruppensuche_feld {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "box-shadow: ".$_POST['cms_style_d_haupt_hintergrund']." 0px 0px 7px;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_schieber_o_aktiv, .cms_schieber_o_inaktiv {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_schieber_o_aktiv {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_schieber_o_inaktiv {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_schieber_i {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_schieber_o_aktiv:hover, .cms_schieber_o_inaktiv:hover {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_notizzettel {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldunginfohinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_notizzettelleer {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_notizzettel:hover, .cms_notizzettel:focus {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");
?>
