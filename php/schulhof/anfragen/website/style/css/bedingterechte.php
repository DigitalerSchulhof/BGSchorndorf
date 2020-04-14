<?php
fwrite($hell, ".cms_button_bedingt_logisch {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button_bedingt_bedingung {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bedingt_gui_logisch {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "background-position: 5px 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_haupt_radiusgross'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bedingt_gui_logisch_operation {\n");
fwrite($hell, "margin-top: 5px;\n");
fwrite($hell, "margin-left: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bedingt_gui_logisch_feld,\n");
fwrite($hell, ".cms_bedingt_gui_logisch_hinzufuegen {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_haupt_radiusmittel'].";\n");
fwrite($hell, "margin-left: 20px;\n");
fwrite($hell, "margin-top: 20px;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "padding: 3px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_aktion_klein {\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bedingt_gui_bedingung select,\n");
fwrite($hell, ".cms_bedingt_gui_bedingung input {\n");
fwrite($hell, "height: 28px;\n");
fwrite($hell, "transition: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bedingt_bedingung {\n");
fwrite($hell, "margin-bottom: 5px;\n");
fwrite($hell, "}\n");




// DARKMODE
fwrite($dunkel, ".cms_button_bedingt_logisch {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_button_bedingt_bedingung {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bedingt_gui_logisch {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bedingt_gui_logisch_feld,\n");
fwrite($dunkel, ".cms_bedingt_gui_logisch_hinzufuegen {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");
?>
