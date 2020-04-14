<?php
fwrite($hell, "#cms_blende_o, #cms_aktionsschicht_o {");
fwrite($hell, "display: none;");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";");
fwrite($hell, "position: fixed;");
fwrite($hell, "top: 0px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "z-index: 6000;");
fwrite($hell, "}");

fwrite($hell, "#cms_blende_m {");
fwrite($hell, "margin: 20px auto;");
fwrite($hell, "width: 500px;");
fwrite($hell, "}");

fwrite($hell, "#cms_aktionsschicht_m {");
fwrite($hell, "margin: 20px auto;");
fwrite($hell, "width: 1000px;");
fwrite($hell, "}");

fwrite($hell, "#cms_blende_i, .cms_aktionsschicht_i {");
fwrite($hell, "border-radius: 20px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "padding: 10px;");
fwrite($hell, "box-shadow: 0px 0px 20px ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_blende_i .cms_spalte_i {");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, "#cms_laden {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "}");




// DARKMODE
fwrite($dunkel, "#cms_blende_o, #cms_aktionsschicht_o {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_blende_i, .cms_aktionsschicht_i {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "box-shadow: 0px 0px 20px ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");
?>
