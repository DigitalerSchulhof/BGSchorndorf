<?php
fwrite($hell, ".cms_bearbeitenwahl {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bearbeitenwahl th, .cms_bearbeitenwahl td {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bearbeitenwahl th {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_website_bearbeiten_i .cms_notiz {text-align: center;}\n");

fwrite($hell, ".cms_website_neu {\n");
fwrite($hell, "height: 5px;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_bearbeiten {transition: 500ms ease-in-out;}\n");

fwrite($hell, ".cms_website_neu:hover, .cms_website_bearbeiten:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_neu:hover {background-color: ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_website_bearbeiten:hover {background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");

fwrite($hell, ".cms_website_neu_menue, .cms_website_bearbeiten_menue {\n");
fwrite($hell, "padding: 5px 10px 10px 10px;\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_neu_menue {background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";}\n");
fwrite($hell, ".cms_website_bearbeiten_menue {\n");
fwrite($hell, "padding-top: 10px;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_bearbeiten_menue p.cms_elementicons {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 10px;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_element_icon {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "margin-bottom: 5px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_neu_menue_box, .cms_website_bearbeiten_menue_box {\n");
fwrite($hell, "margin: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_neu_element, .cms_website_bearbeiten_element {\n");
fwrite($hell, "margin-top: 10px;\n");
fwrite($hell, "padding-top: 10px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_website_neu_element {border-top: 1px solid ".$_POST['cms_style_h_haupt_meldungerfolgakzent'].";}\n");
fwrite($hell, ".cms_website_bearbeiten_element {border-top: 1px solid ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}\n");

fwrite($hell, ".cms_element_inaktiv {\n");
fwrite($hell, "opacity: .5;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_element_neuedaten {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_element_neuedaten_anzeige {\n");
fwrite($hell, "border-left: 5px solid ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "padding-left: 5px;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_website_neu {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_website_neu:hover {background-color: ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";}\n");
fwrite($dunkel, ".cms_website_bearbeiten:hover {background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";}\n");

fwrite($dunkel, ".cms_website_neu_menue {background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";}\n");
fwrite($dunkel, ".cms_website_bearbeiten_menue {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_website_neu_element {border-top: 1px solid ".$_POST['cms_style_d_haupt_meldungerfolgakzent'].";}\n");
fwrite($dunkel, ".cms_website_bearbeiten_element {border-top: 1px solid ".$_POST['cms_style_d_haupt_meldungwarnungakzent'].";}\n");

fwrite($dunkel, ".cms_element_neuedaten {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_element_neuedaten_anzeige {\n");
fwrite($dunkel, "border-left: 5px solid ".$_POST['cms_style_d_haupt_meldungwarnunghinter'].";\n");
fwrite($dunkel, "}\n");
?>
