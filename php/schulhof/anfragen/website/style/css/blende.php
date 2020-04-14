<?php
fwrite($hell, "#cms_blende_o, #cms_aktionsschicht_o {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "z-index: 6000;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_blende_m {\n");
fwrite($hell, "margin: 20px auto;\n");
fwrite($hell, "width: 500px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktionsschicht_m {\n");
fwrite($hell, "margin: 20px auto;\n");
fwrite($hell, "width: 1000px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_blende_i, .cms_aktionsschicht_i {\n");
fwrite($hell, "border-radius: 20px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "box-shadow: 0px 0px 20px ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_blende_i .cms_spalte_i {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_laden {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "}\n");




// DARKMODE
fwrite($dunkel, "#cms_blende_o, #cms_aktionsschicht_o {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_blende_i, .cms_aktionsschicht_i {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "box-shadow: 0px 0px 20px ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");
?>
