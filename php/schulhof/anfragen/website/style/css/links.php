<?php
fwrite($hell, "a, .cms_link {");
fwrite($hell, "font-size: inherit;");
fwrite($hell, "text-decoration: none;");
fwrite($hell, "color: ".$_POST['cms_style_h_link_schrift'].";");
fwrite($hell, "transition: 500ms ease-in-out;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, "a:hover, .cms_link:hover {");
fwrite($hell, "color: ".$_POST['cms_style_h_link_schrifthover'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_o .cms_notiz a:hover {");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_linkschrifthover']." !important;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, "#cms_fusszeile_o .cms_notiz a {");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_linkschrift']." !important;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, ".cms_brotkrumen {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "}");

fwrite($hell, ".cms_favorisieren {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "float: right;");
fwrite($hell, "text-align: right;");
fwrite($hell, "position: relative;");
fwrite($hell, "margin-left: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_favorisieren img:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_neue_weiterleitung {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "float: right;");
fwrite($hell, "text-align: right;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_neue_weiterleitung img:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");




// DARKMODE
fwrite($dunkel, "a, .cms_link {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_link_schrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "a:hover, .cms_link:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_link_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_o .cms_notiz a:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_linkschrifthover']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_fusszeile_o .cms_notiz a {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_linkschrift']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_brotkrumen {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_notizschrift'].";");
fwrite($dunkel, "}");
?>
