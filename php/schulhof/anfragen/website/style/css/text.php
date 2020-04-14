<?php
fwrite($hell, ".cms_notiz, .cms_hochladen_fortschritt_anzeige {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";\n");
fwrite($hell, "font-size: 70% !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_notiz a, .cms_brotkrumen a {\n");
fwrite($hell, "font-size: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "p, ul, ol, table, li {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "h1 {\n");
fwrite($hell, "margin-top: 40px;\n");
fwrite($hell, "margin-bottom: 15px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-size: 170%;\n");
fwrite($hell, "overflow: show;\n");
fwrite($hell, "}\n");

fwrite($hell, "h2 {\n");
fwrite($hell, "margin-top: 30px;\n");
fwrite($hell, "margin-bottom: 10px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-size: 140%;\n");
fwrite($hell, "}\n");

fwrite($hell, "h3 {\n");
fwrite($hell, "margin-top: 30px;\n");
fwrite($hell, "margin-bottom: 10px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-size: 120%;\n");
fwrite($hell, "}\n");

fwrite($hell, "h4 {\n");
fwrite($hell, "margin-top: 30px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-size: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "h2+h3 {\n");
fwrite($hell, "margin-top: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, "h3+h4 {\n");
fwrite($hell, "margin-top: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, "p.cms_notiz+h1, p.cms_brotkrumen + h1 {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzbrotkrumen'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termine_jahresuebersicht + p.cms_notiz {\n");
fwrite($hell, "margin-top: 40px;\n");
fwrite($hell, "}\n");

fwrite($hell, "p:first-child, h1:first-child, h2:first-child, h3:first-child,\n");
fwrite($hell, "h4:first-child, h5:first-child, h6:first-child, ul:first-child,\n");
fwrite($hell, "ol:first-child, table:first-child {\n");
fwrite($hell, "margin-top: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "p:last-child, h1:last-child, h2:last-child, h3:last-child,\n");
fwrite($hell, "h4:last-child, h5:last-child, h6:last-child, ul:last-child,\n");
fwrite($hell, "ol:last-child, table:last-child {\n");
fwrite($hell, "margin-bottom: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "ul li {\n");
fwrite($hell, "list-style-type: square;\n");
fwrite($hell, "margin-left: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, "ul ul li {\n");
fwrite($hell, "list-style-type: circle;\n");
fwrite($hell, "margin-left: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, "ol li {\n");
fwrite($hell, "list-style-type: decimal;\n");
fwrite($hell, "margin-left: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, "b, strong {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "font-style: inherit;\n");
fwrite($hell, "font-size: inherit;\n");
fwrite($hell, "text-decoration: inherit;\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "color: inherit;\n");
fwrite($hell, "}\n");
fwrite($hell, "i, em {\n");
fwrite($hell, "font-style: italic;\n");
fwrite($hell, "font-weight: inherit;\n");
fwrite($hell, "text-decoration: inherit;\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "font-size: inherit;\n");
fwrite($hell, "color: inherit;\n");
fwrite($hell, "}\n");
fwrite($hell, "u {\n");
fwrite($hell, "font-style: inherit;\n");
fwrite($hell, "font-weight: inherit;\n");
fwrite($hell, "text-decoration: underline;\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "font-size: inherit;\n");
fwrite($hell, "color: inherit;\n");
fwrite($hell, "}\n");

fwrite($hell, "p span, p b, p i, p u,\n");
fwrite($hell, "li span, li b, li i, li u,\n");
fwrite($hell, "td span, td b, td i, td u,\n");
fwrite($hell, "th span, th b, th i, th u {\n");
fwrite($hell, "font-size: inherit;\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "color: inherit;\n");
fwrite($hell, "}\n");

fwrite($hell, "blockquote {\n");
fwrite($hell, "background-image: url('../res/sonstiges/zitat.png');\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "background-position: 10px 10px;\n");
fwrite($hell, "padding: 30px 10px 10px 80px;\n");
fwrite($hell, "min-height: 70px;\n");
fwrite($hell, "font-family: inherit;\n");
fwrite($hell, "color: inherit;\n");
fwrite($hell, "}\n");

fwrite($hell, "pre, .cms_konsole, .cms_code {\n");
fwrite($hell, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "font-family: monospace;\n");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_zentriert {\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_rechtsbuendig {\n");
fwrite($hell, "text-align: right !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptteil_inhalt * {\n");
fwrite($hell, "line-height: 1.5em !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptteil_inhalt .cms_uebersicht_kalender *,\n");
fwrite($hell, ".cms_hauptteil_inhalt .cms_uebersicht_kalender_beginn *,\n");
fwrite($hell, ".cms_hauptteil_inhalt .cms_uebersicht_kalender_ende * {\n");
fwrite($hell, "line-height: 1.2em !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptteil_inhalt p, .cms_hauptteil_inhalt ul, .cms_hauptteil_inhalt ol,\n");
fwrite($hell, ".cms_hauptteil_inhalt table, .cms_download_anzeige, .cms_boxen_u, .cms_boxen_n {\n");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzwebsite'].";\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzwebsite'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptteil_inhalt h4 {\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzwebsite'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_download_anzeige * {\n");
fwrite($hell, "line-height: 1.2em;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_uebersicht * {\n");
fwrite($hell, "line-height: 1.2em;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_notiz, .cms_hochladen_fortschritt_anzeige {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "pre, .cms_konsole, .cms_code {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");
?>
