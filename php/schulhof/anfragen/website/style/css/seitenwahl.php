<?php
fwrite($hell, ".cms_seitenwahl {");
fwrite($hell, "display: none;");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: 0px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-bottom-right-radius: 5px;");
fwrite($hell, "border-bottom-left-radius: 5px;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "margin-bottom: 20px;");
fwrite($hell, "box-shadow: ".$_POST['cms_style_h_haupt_hintergrund']." 0px 0px 7px;");
fwrite($hell, "}");

fwrite($hell, ".cms_seitenwahlzeile {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_seitenwahl li {");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "margin-left: 15px;");
fwrite($hell, "}");

fwrite($hell, ".cms_seitenwahl li.cms_notiz {");
fwrite($hell, "margin-left: 32px;");
fwrite($hell, "}");

fwrite($hell, ".cms_seitenwahl .cms_spalte_i > ul > li {");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_seitenwahl li .cms_aktion_klein {");
fwrite($hell, "position: relative;");
fwrite($hell, "bottom: -3px;");
fwrite($hell, "}");

fwrite($hell, ".cms_seitenwahl li .cms_aktion_klein .cms_hinweis {");
fwrite($hell, "left: 0px!important;");
fwrite($hell, "right: auto!important;");
fwrite($hell, "text-align: left!important;");
fwrite($hell, "}");


// DARKMODE
fwrite($dunkel, ".cms_seitenwahl {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "box-shadow: ".$_POST['cms_style_d_haupt_hintergrund']." 0px 0px 7px;");
fwrite($dunkel, "}");
?>
