<?php
fwrite($hell, ".cms_notiz, .cms_hochladen_fortschritt_anzeige {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";");
fwrite($hell, "font-size: 70% !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_notiz a, .cms_brotkrumen a {");
fwrite($hell, "font-size: 100%;");
fwrite($hell, "}");

fwrite($hell, "p, ul, ol, table, li {");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, "h1 {");
fwrite($hell, "margin-top: 40px;");
fwrite($hell, "margin-bottom: 15px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-size: 170%;");
fwrite($hell, "overflow: show;");
fwrite($hell, "}");

fwrite($hell, "h2 {");
fwrite($hell, "margin-top: 30px;");
fwrite($hell, "margin-bottom: 10px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-size: 140%;");
fwrite($hell, "}");

fwrite($hell, "h3 {");
fwrite($hell, "margin-top: 30px;");
fwrite($hell, "margin-bottom: 10px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-size: 120%;");
fwrite($hell, "}");

fwrite($hell, "h4 {");
fwrite($hell, "margin-top: 30px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-size: 100%;");
fwrite($hell, "}");

fwrite($hell, "h2+h3 {");
fwrite($hell, "margin-top: 20px;");
fwrite($hell, "}");

fwrite($hell, "h3+h4 {");
fwrite($hell, "margin-top: 10px;");
fwrite($hell, "}");

fwrite($hell, "p.cms_notiz+h1, p.cms_brotkrumen + h1 {");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzbrotkrumen'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_termine_jahresuebersicht + p.cms_notiz {");
fwrite($hell, "margin-top: 40px;");
fwrite($hell, "}");

fwrite($hell, "p:first-child, h1:first-child, h2:first-child, h3:first-child,");
fwrite($hell, "h4:first-child, h5:first-child, h6:first-child, ul:first-child,");
fwrite($hell, "ol:first-child, table:first-child {");
fwrite($hell, "margin-top: 0px !important;");
fwrite($hell, "}");

fwrite($hell, "p:last-child, h1:last-child, h2:last-child, h3:last-child,");
fwrite($hell, "h4:last-child, h5:last-child, h6:last-child, ul:last-child,");
fwrite($hell, "ol:last-child, table:last-child {");
fwrite($hell, "margin-bottom: 0px !important;");
fwrite($hell, "}");

fwrite($hell, "ul li {");
fwrite($hell, "list-style-type: square;");
fwrite($hell, "margin-left: 20px;");
fwrite($hell, "}");

fwrite($hell, "ul ul li {");
fwrite($hell, "list-style-type: circle;");
fwrite($hell, "margin-left: 20px;");
fwrite($hell, "}");

fwrite($hell, "ol li {");
fwrite($hell, "list-style-type: decimal;");
fwrite($hell, "margin-left: 20px;");
fwrite($hell, "}");

fwrite($hell, "b, strong {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "font-style: inherit;");
fwrite($hell, "font-size: inherit;");
fwrite($hell, "text-decoration: inherit;");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "color: inherit;");
fwrite($hell, "}");
fwrite($hell, "i, em {");
fwrite($hell, "font-style: italic;");
fwrite($hell, "font-weight: inherit;");
fwrite($hell, "text-decoration: inherit;");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "font-size: inherit;");
fwrite($hell, "color: inherit;");
fwrite($hell, "}");
fwrite($hell, "u {");
fwrite($hell, "font-style: inherit;");
fwrite($hell, "font-weight: inherit;");
fwrite($hell, "text-decoration: underline;");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "font-size: inherit;");
fwrite($hell, "color: inherit;");
fwrite($hell, "}");

fwrite($hell, "p span, p b, p i, p u,");
fwrite($hell, "li span, li b, li i, li u,");
fwrite($hell, "td span, td b, td i, td u,");
fwrite($hell, "th span, th b, th i, th u {");
fwrite($hell, "font-size: inherit;");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "color: inherit;");
fwrite($hell, "}");

fwrite($hell, "blockquote {");
fwrite($hell, "background-image: url('../res/sonstiges/zitat.png');");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "background-position: 10px 10px;");
fwrite($hell, "padding: 30px 10px 10px 80px;");
fwrite($hell, "min-height: 70px;");
fwrite($hell, "font-family: inherit;");
fwrite($hell, "color: inherit;");
fwrite($hell, "}");

fwrite($hell, "pre, .cms_konsole, .cms_code {");
fwrite($hell, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "padding: 10px;");
fwrite($hell, "font-family: monospace;");
fwrite($hell, "border-radius: ".$_POST['cms_style_button_rundeecken'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_zentriert {");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtsbuendig {");
fwrite($hell, "text-align: right !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptteil_inhalt * {");
fwrite($hell, "line-height: 1.5em !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptteil_inhalt .cms_uebersicht_kalender *,");
fwrite($hell, ".cms_hauptteil_inhalt .cms_uebersicht_kalender_beginn *,");
fwrite($hell, ".cms_hauptteil_inhalt .cms_uebersicht_kalender_ende * {");
fwrite($hell, "line-height: 1.2em !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptteil_inhalt p, .cms_hauptteil_inhalt ul, .cms_hauptteil_inhalt ol,");
fwrite($hell, ".cms_hauptteil_inhalt table, .cms_download_anzeige, .cms_boxen_u, .cms_boxen_n {");
fwrite($hell, "margin-top: ".$_POST['cms_style_haupt_absatzwebsite'].";");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzwebsite'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptteil_inhalt h4 {");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzwebsite'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_download_anzeige * {");
fwrite($hell, "line-height: 1.2em;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_uebersicht * {");
fwrite($hell, "line-height: 1.2em;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_notiz, .cms_hochladen_fortschritt_anzeige {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "pre, .cms_konsole, .cms_code {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");
?>
