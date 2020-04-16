<?php
fwrite($drucken, "* {\n");
fwrite($drucken, "font-family: 'rob', sans-serif;\n");
fwrite($drucken, "font-size: 10pt;\n");
fwrite($drucken, "font-weight: normal;\n");
fwrite($drucken, "padding: 0px;\n");
fwrite($drucken, "margin: 0px;\n");
fwrite($drucken, "list-style-type: none;\n");
fwrite($drucken, "line-height: 1.2em;\n");
fwrite($drucken, "text-decoration: none;\n");
fwrite($drucken, "box-sizing: border-box;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "body {\n");
fwrite($drucken, "background: ".$_POST['cms_style_h_haupt_koerperhintergrund'].";\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_druckseite {\n");
fwrite($drucken, "width: 210mm;\n");
fwrite($drucken, "padding: 2cm 2cm 2cm 2cm;\n");
fwrite($drucken, "background: #ffffff;\n");
fwrite($drucken, "color: #000000;\n");
fwrite($drucken, "margin: 20px auto;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_logo {\n");
fwrite($drucken, "display: inline-block;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_logo_bild {\n");
fwrite($drucken, "float: left;\n");
fwrite($drucken, "width: 2.5cm;\n");
fwrite($drucken, "padding-right: 5mm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_logo_schrift {\n");
fwrite($drucken, "float: left;\n");
fwrite($drucken, "display: block;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_logo_o, .cms_logo_u {\n");
fwrite($drucken, "position: relative;\n");
fwrite($drucken, "color: ".$_POST['cms_style_h_logo_schriftfarbe'].";\n");
fwrite($drucken, "font-size: 25pt;\n");
fwrite($drucken, "padding: 2px 0px 0px 0px;\n");
fwrite($drucken, "display: block;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_logo_o {\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "div.cms_druckkopf {\n");
fwrite($drucken, "display: block;\n");
fwrite($drucken, "margin-bottom: 1cm;\n");
fwrite($drucken, "margin-top: 2cm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "div.cms_druckkopf:first-child {\n");
fwrite($drucken, "margin-top: 0cm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "div#cms_druckfuss {\n");
fwrite($drucken, "text-align: center;\n");
fwrite($drucken, "font-size: 10pt !important;\n");
fwrite($drucken, "color: #aaaaaa;\n");
fwrite($drucken, "margin-top: 1cm;\n");
fwrite($drucken, "display: block;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "div#cms_druckfuss p, div#cms_druckfuss b {\n");
fwrite($drucken, "font-size: 8pt !important;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "b {\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "p {\n");
fwrite($drucken, "line-height: 1.2em;\n");
fwrite($drucken, "margin-bottom: .2cm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "p:last-child {\n");
fwrite($drucken, "margin-bottom: 0px;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "h1 {\n");
fwrite($drucken, "font-size: 14pt;\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "margin-top: 2.5cm;\n");
fwrite($drucken, "margin-bottom: .25cm;\n");
fwrite($drucken, "page-break-before: always;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_druckkopf+h1 {\n");
fwrite($drucken, "margin-top: .5cm !important;\n");
fwrite($drucken, "page-break-before: avoid !important;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "h2 {\n");
fwrite($drucken, "font-size: 12pt;\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "margin-top: .6cm;\n");
fwrite($drucken, "margin-bottom: .2cm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_meldung h4 {\n");
fwrite($drucken, "margin-top: 0px !important;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "h4 {\n");
fwrite($drucken, "margin-top: 30px;\n");
fwrite($drucken, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "font-size: 100%;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "b, strong {\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "font-style: inherit;\n");
fwrite($drucken, "font-size: inherit;\n");
fwrite($drucken, "text-decoration: inherit;\n");
fwrite($drucken, "font-family: inherit;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_notiz {\n");
fwrite($drucken, "margin-top: 10px;\n");
fwrite($drucken, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";\n");
fwrite($drucken, "font-size: 70% !important;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_meldung h4 {\n");
fwrite($drucken, "margin-top: 10px;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_datum {\n");
fwrite($drucken, "text-align: right;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_clear {\n");
fwrite($drucken, "clear: both;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_spalte_2:first-child .cms_spalte_i {padding-right: 2.5mm !important;}\n");
fwrite($drucken, ".cms_spalte_2:last-child .cms_spalte_i {padding-left: 2.5mm !important;}\n");

fwrite($drucken, ".cms_spalte_2 {\n");
fwrite($drucken, "padding: 0mm;\n");
fwrite($drucken, "width: 50%;\n");
fwrite($drucken, "float: left;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "table {\n");
fwrite($drucken, "width: 100%;\n");
fwrite($drucken, "border-collapse:collapse;\n");
fwrite($drucken, "margin-bottom: .5cm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "table th {width: 4cm !important;}\n");

fwrite($drucken, "table th, table td {\n");
fwrite($drucken, "padding: 1mm 0mm;\n");
fwrite($drucken, "border-bottom: 1pt solid #aaaaaa;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "table tr:first-child th, table tr:first-child td {\n");
fwrite($drucken, "border-top: 1pt solid #aaaaaa;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "table th, table td, table i, table b {\n");
fwrite($drucken, "font-size: 10pt;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "table th {\n");
fwrite($drucken, "font-weight: bold;\n");
fwrite($drucken, "text-align: left;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_markierte_liste_0 td {background: ".$_POST['cms_style_h_haupt_hintergrund'].";}\n");
fwrite($drucken, ".cms_markierte_liste_1 td {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}\n");

fwrite($drucken, ".cms_vplanliste_entfall td:first-child {border-left: 1mm solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter']."; padding-left: 2mm;}\n");
fwrite($drucken, ".cms_vplanliste_neu td:first-child {border-left: 1mm solid ".$_POST['cms_style_h_haupt_meldungerfolghinter']."; padding-left: 2mm;}\n");

fwrite($drucken, ".cms_meldung_info {\n");
fwrite($drucken, "border-left: 1mm solid ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($drucken, "margin-top: 2mm;\n");
fwrite($drucken, "margin-bottom: 2mm;\n");
fwrite($drucken, "padding: 2mm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_meldung_vplan {\n");
fwrite($drucken, "border-left: 1mm solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($drucken, "margin-top: 2mm;\n");
fwrite($drucken, "margin-bottom: 2mm;\n");
fwrite($drucken, "padding: 2mm;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_zwischenueberschrift {\n");
fwrite($drucken, "border-right: none !important;\n");
fwrite($drucken, "border-top: 5mm solid #ffffff;\n");
fwrite($drucken, "text-align: center !important;\n");
fwrite($drucken, "background-color: #ededed;\n");
fwrite($drucken, "color: #aaaaaa;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_zwischenueberschrift:first-child {\n");
fwrite($drucken, "border-top: none !important;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "span.cms_unterschrift {\n");
fwrite($drucken, "display: block;\n");
fwrite($drucken, "width: 75%;\n");
fwrite($drucken, "border-bottom: 1pt solid #000000;\n");
fwrite($drucken, "margin-top: 1.5cm;\n");
fwrite($drucken, "margin-bottom: 1mm;\n");
fwrite($drucken, "font-size: 12pt;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_seitenumbruch {\n");
fwrite($drucken, "border-top: .5cm solid ".$_POST['cms_style_h_haupt_koerperhintergrund'].";\n");
fwrite($drucken, "page-break-before: always;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "p.cms_unterschrift {\n");
fwrite($drucken, "font-size: 8pt;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "@media print {\n");
fwrite($drucken, "body {\n");
fwrite($drucken, "background: #ffffff;\n");
fwrite($drucken, "}\n");

fwrite($drucken, ".cms_seitenumbruch {\n");
fwrite($drucken, "border-top: none;\n");
fwrite($drucken, "page-break-before: always;\n");
fwrite($drucken, "}\n");

fwrite($drucken, "@page {margin: 0cm 2cm 2cm 2cm;}\n");

fwrite($drucken, ".cms_druckseite {\n");
fwrite($drucken, "padding: 0cm !important\n");
fwrite($drucken, "width: 100%;\n");
fwrite($drucken, "background: #ffffff;\n");
fwrite($drucken, "color: #000000;\n");
fwrite($drucken, "margin: 0cm !important;\n");
fwrite($drucken, "}\n");
fwrite($drucken, "}\n");
?>
