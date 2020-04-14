<?php
fwrite($hell, ".cms_bearbeitenwahl {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_bearbeitenwahl th, .cms_bearbeitenwahl td {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_bearbeitenwahl th {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, "#cms_website_bearbeiten_i .cms_notiz {text-align: center;}");

fwrite($hell, ".cms_website_neu {");
fwrite($hell, "height: 5px;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_website_bearbeiten {transition: 500ms ease-in-out;}");

fwrite($hell, ".cms_website_neu:hover, .cms_website_bearbeiten:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_neu:hover {background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_website_bearbeiten:hover {background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");

fwrite($hell, ".cms_website_neu_menue, .cms_website_bearbeiten_menue {");
fwrite($hell, "padding: 5px 10px 10px 10px;");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_neu_menue {background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($hell, ".cms_website_bearbeiten_menue {");
fwrite($hell, "padding-top: 10px;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_website_bearbeiten_menue p.cms_elementicons {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 10px;");
fwrite($hell, "right: 10px;");
fwrite($hell, "width: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_element_icon {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "margin-bottom: 5px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_neu_menue_box, .cms_website_bearbeiten_menue_box {");
fwrite($hell, "margin: 0px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_neu_element, .cms_website_bearbeiten_element {");
fwrite($hell, "margin-top: 10px;");
fwrite($hell, "padding-top: 10px;");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_website_neu_element {border-top: 1px solid ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($hell, ".cms_website_bearbeiten_element {border-top: 1px solid ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");

fwrite($hell, ".cms_element_inaktiv {");
fwrite($hell, "opacity: .5;");
fwrite($hell, "}");

fwrite($hell, ".cms_element_neuedaten {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_element_neuedaten_anzeige {");
fwrite($hell, "border-left: 5px solid ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "padding-left: 5px;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_website_neu {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_website_neu:hover {background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_website_bearbeiten:hover {background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");

fwrite($dunkel, ".cms_website_neu_menue {background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}");
fwrite($dunkel, ".cms_website_bearbeiten_menue {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_website_neu_element {border-top: 1px solid ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}");
fwrite($dunkel, ".cms_website_bearbeiten_element {border-top: 1px solid ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");

fwrite($dunkel, ".cms_element_neuedaten {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_element_neuedaten_anzeige {");
fwrite($dunkel, "border-left: 5px solid ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "}");
?>
