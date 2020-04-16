<?php
fwrite($hell, ".cms_seitenwahl {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-bottom-right-radius: 5px;\n");
fwrite($hell, "border-bottom-left-radius: 5px;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "margin-bottom: 20px;\n");
fwrite($hell, "box-shadow: ".$_POST['cms_style_h_haupt_hintergrund']." 0px 0px 7px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seitenwahlzeile {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seitenwahl li {\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "margin-left: 15px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seitenwahl li.cms_notiz {\n");
fwrite($hell, "margin-left: 32px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seitenwahl .cms_spalte_i > ul > li {\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seitenwahl li .cms_aktion_klein {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "bottom: -3px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seitenwahl li .cms_aktion_klein .cms_hinweis {\n");
fwrite($hell, "left: 0px!important;\n");
fwrite($hell, "right: auto!important;\n");
fwrite($hell, "text-align: left!important;\n");
fwrite($hell, "}\n");


// DARKMODE
fwrite($dunkel, ".cms_seitenwahl {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "box-shadow: ".$_POST['cms_style_d_haupt_hintergrund']." 0px 0px 7px;\n");
fwrite($dunkel, "}\n");
?>
