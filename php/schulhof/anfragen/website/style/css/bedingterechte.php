<?php
fwrite($hell, ".cms_button_bedingt_logisch {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_button_bedingt_bedingung {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bedingt_gui_logisch {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "background-position: 5px 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "border-radius: ".$_POST['cms_style_haupt_radiusgross'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "width: 100%;");

fwrite($hell, ".cms_bedingt_gui_logisch_operation {");
fwrite($hell, "margin-top: 5px;");
fwrite($hell, "margin-left: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_bedingt_gui_logisch_feld,");
fwrite($hell, ".cms_bedingt_gui_logisch_hinzufuegen {");
fwrite($hell, "display: block;");
fwrite($hell, "border-radius: ".$_POST['cms_style_haupt_radiusmittel'].";");
fwrite($hell, "margin-left: 20px;");
fwrite($hell, "margin-top: 20px;");

fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "padding: 3px;");

fwrite($hell, ".cms_aktion_klein {");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");
fwrite($hell, "&.cms_bedingt_gui_logisch_hinzufuegen {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, ".cms_bedingt_gui_bedingung {");
fwrite($hell, "select,");
fwrite($hell, "input {");
fwrite($hell, "height: 28px;");
fwrite($hell, "transition: none !important;");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, ".cms_bedingt_bedingung {");
fwrite($hell, "margin-bottom: 5px;");
fwrite($hell, "}");




// DARKMODE
fwrite($dunkel, ".cms_button_bedingt_logisch {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_button_bedingt_bedingung {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bedingt_gui_logisch {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");

fwrite($dunkel, ".cms_bedingt_gui_logisch_feld,");
fwrite($dunkel, ".cms_bedingt_gui_logisch_hinzufuegen {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");
fwrite($dunkel, "}");
?>
