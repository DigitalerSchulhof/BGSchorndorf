<?php
fwrite($hell, "a, .cms_link {\n");
fwrite($hell, "font-size: inherit;\n");
fwrite($hell, "text-decoration: none;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_link_schrift'].";\n");
fwrite($hell, "transition: 500ms ease-in-out;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, "a:hover, .cms_link:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_link_schrifthover'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_o .cms_notiz a:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_linkschrifthover']." !important;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_o .cms_notiz a {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_fusszeile_linkschrift']." !important;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_brotkrumen {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_favorisieren {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "margin-left: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_favorisieren img:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neue_weiterleitung {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neue_weiterleitung img:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");




// DARKMODE
fwrite($dunkel, "a, .cms_link {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_link_schrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "a:hover, .cms_link:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_link_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_o .cms_notiz a:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_linkschrifthover']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_fusszeile_o .cms_notiz a {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_fusszeile_linkschrift']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_brotkrumen {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_notizschrift'].";\n");
fwrite($dunkel, "}\n");
?>
