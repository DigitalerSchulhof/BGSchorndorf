<?php
fwrite($drucken, "* {");
fwrite($drucken, "font-family: 'rob', sans-serif;");
fwrite($drucken, "font-size: 10pt;");
fwrite($drucken, "font-weight: normal;");
fwrite($drucken, "padding: 0px;");
fwrite($drucken, "margin: 0px;");
fwrite($drucken, "list-style-type: none;");
fwrite($drucken, "line-height: 1.2em;");
fwrite($drucken, "text-decoration: none;");
fwrite($drucken, "box-sizing: border-box;");
fwrite($drucken, "}");

fwrite($drucken, "body {");
fwrite($drucken, "background: ".$_POST['cms_style_h_haupt_koerperhintergrund'].";");
fwrite($drucken, "}");

fwrite($drucken, ".cms_druckseite {");
fwrite($drucken, "width: 210mm;");
fwrite($drucken, "padding: 2cm 2cm 2cm 2cm;");
fwrite($drucken, "background: #ffffff;");
fwrite($drucken, "color: #000000;");
fwrite($drucken, "margin: 20px auto;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_logo {");
fwrite($drucken, "display: inline-block;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_logo_bild {");
fwrite($drucken, "float: left;");
fwrite($drucken, "width: 2.5cm;");
fwrite($drucken, "padding-right: 5mm;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_logo_schrift {");
fwrite($drucken, "float: left;");
fwrite($drucken, "display: block;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_logo_o, .cms_logo_u {");
fwrite($drucken, "position: relative;");
fwrite($drucken, "color: ".$_POST['cms_style_h_logo_schriftfarbe'].";");
fwrite($drucken, "font-size: 25pt;");
fwrite($drucken, "padding: 2px 0px 0px 0px;");
fwrite($drucken, "display: block;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_logo_o {");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "}");

fwrite($drucken, "div.cms_druckkopf {");
fwrite($drucken, "display: block;");
fwrite($drucken, "margin-bottom: 1cm;");
fwrite($drucken, "margin-top: 2cm;");
fwrite($drucken, "}");

fwrite($drucken, "div.cms_druckkopf:first-child {");
fwrite($drucken, "margin-top: 0cm;");
fwrite($drucken, "}");

fwrite($drucken, "div#cms_druckfuss {");
fwrite($drucken, "text-align: center;");
fwrite($drucken, "font-size: 10pt !important;");
fwrite($drucken, "color: #aaaaaa;");
fwrite($drucken, "margin-top: 1cm;");
fwrite($drucken, "display: block;");
fwrite($drucken, "}");

fwrite($drucken, "div#cms_druckfuss p, div#cms_druckfuss b {");
fwrite($drucken, "font-size: 8pt !important;");
fwrite($drucken, "}");

fwrite($drucken, "b {");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "}");

fwrite($drucken, "p {");
fwrite($drucken, "line-height: 1.2em;");
fwrite($drucken, "margin-bottom: .2cm;");
fwrite($drucken, "}");

fwrite($drucken, "p:last-child {");
fwrite($drucken, "margin-bottom: 0px;");
fwrite($drucken, "}");

fwrite($drucken, "h1 {");
fwrite($drucken, "font-size: 14pt;");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "margin-top: 2.5cm;");
fwrite($drucken, "margin-bottom: .25cm;");
fwrite($drucken, "page-break-before: always;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_druckkopf+h1 {");
fwrite($drucken, "margin-top: .5cm !important;");
fwrite($drucken, "page-break-before: avoid !important;");
fwrite($drucken, "}");

fwrite($drucken, "h2 {");
fwrite($drucken, "font-size: 12pt;");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "margin-top: .6cm;");
fwrite($drucken, "margin-bottom: .2cm;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_meldung h4 {");
fwrite($drucken, "margin-top: 0px !important;");
fwrite($drucken, "}");

fwrite($drucken, "h4 {");
fwrite($drucken, "margin-top: 30px;");
fwrite($drucken, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "font-size: 100%;");
fwrite($drucken, "}");

fwrite($drucken, "b, strong {");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "font-style: inherit;");
fwrite($drucken, "font-size: inherit;");
fwrite($drucken, "text-decoration: inherit;");
fwrite($drucken, "font-family: inherit;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_notiz {");
fwrite($drucken, "margin-top: 10px;");
fwrite($drucken, "color: ".$_POST['cms_style_h_haupt_notizschrift'].";");
fwrite($drucken, "font-size: 70% !important;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_meldung h4 {");
fwrite($drucken, "margin-top: 10px;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_datum {");
fwrite($drucken, "text-align: right;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_clear {");
fwrite($drucken, "clear: both;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_spalte_2:first-child .cms_spalte_i {padding-right: 2.5mm !important;}");
fwrite($drucken, ".cms_spalte_2:last-child .cms_spalte_i {padding-left: 2.5mm !important;}");

fwrite($drucken, ".cms_spalte_2 {");
fwrite($drucken, "padding: 0mm;");
fwrite($drucken, "width: 50%;");
fwrite($drucken, "float: left;");
fwrite($drucken, "}");

fwrite($drucken, "table {");
fwrite($drucken, "width: 100%;");
fwrite($drucken, "border-collapse:collapse;");
fwrite($drucken, "margin-bottom: .5cm;");
fwrite($drucken, "}");

fwrite($drucken, "table th {width: 4cm !important;}");

fwrite($drucken, "table th, table td {");
fwrite($drucken, "padding: 1mm 0mm;");
fwrite($drucken, "border-bottom: 1pt solid #aaaaaa;");
fwrite($drucken, "}");

fwrite($drucken, "table tr:first-child th, table tr:first-child td {");
fwrite($drucken, "border-top: 1pt solid #aaaaaa;");
fwrite($drucken, "}");

fwrite($drucken, "table th, table td, table i, table b {");
fwrite($drucken, "font-size: 10pt;");
fwrite($drucken, "}");

fwrite($drucken, "table th {");
fwrite($drucken, "font-weight: bold;");
fwrite($drucken, "text-align: left;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_markierte_liste_0 td {background: ".$_POST['cms_style_h_haupt_hintergrund'].";}");
fwrite($drucken, ".cms_markierte_liste_1 td {background: ".$_POST['cms_style_h_haupt_abstufung1'].";}");

fwrite($drucken, ".cms_vplanliste_entfall td:first-child {border-left: 1mm solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter']."; padding-left: 2mm;}");
fwrite($drucken, ".cms_vplanliste_neu td:first-child {border-left: 1mm solid ".$_POST['cms_style_h_haupt_meldungerfolghinter']."; padding-left: 2mm;}");

fwrite($drucken, ".cms_meldung_info {");
fwrite($drucken, "border-left: 1mm solid ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($drucken, "margin-top: 2mm;");
fwrite($drucken, "margin-bottom: 2mm;");
fwrite($drucken, "padding: 2mm;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_meldung_vplan {");
fwrite($drucken, "border-left: 1mm solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($drucken, "margin-top: 2mm;");
fwrite($drucken, "margin-bottom: 2mm;");
fwrite($drucken, "padding: 2mm;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_zwischenueberschrift {");
fwrite($drucken, "border-right: none !important;");
fwrite($drucken, "border-top: 5mm solid #ffffff;");
fwrite($drucken, "text-align: center !important;");
fwrite($drucken, "background-color: #ededed;");
fwrite($drucken, "color: #aaaaaa;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_zwischenueberschrift:first-child {");
fwrite($drucken, "border-top: none !important;");
fwrite($drucken, "}");

fwrite($drucken, "span.cms_unterschrift {");
fwrite($drucken, "display: block;");
fwrite($drucken, "width: 75%;");
fwrite($drucken, "border-bottom: 1pt solid #000000;");
fwrite($drucken, "margin-top: 1.5cm;");
fwrite($drucken, "margin-bottom: 1mm;");
fwrite($drucken, "font-size: 12pt;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_seitenumbruch {");
fwrite($drucken, "border-top: .5cm solid ".$_POST['cms_style_h_haupt_koerperhintergrund'].";");
fwrite($drucken, "page-break-before: always;");
fwrite($drucken, "}");

fwrite($drucken, "p.cms_unterschrift {");
fwrite($drucken, "font-size: 8pt;");
fwrite($drucken, "}");

fwrite($drucken, "@media print {");
fwrite($drucken, "body {");
fwrite($drucken, "background: #ffffff;");
fwrite($drucken, "}");

fwrite($drucken, ".cms_seitenumbruch {");
fwrite($drucken, "border-top: none;");
fwrite($drucken, "page-break-before: always;");
fwrite($drucken, "}");

fwrite($drucken, "@page {margin: 0cm 2cm 2cm 2cm;}");

fwrite($drucken, ".cms_druckseite {");
fwrite($drucken, "padding: 0cm !important");
fwrite($drucken, "width: 100%;");
fwrite($drucken, "background: #ffffff;");
fwrite($drucken, "color: #000000;");
fwrite($drucken, "margin: 0cm !important;");
fwrite($drucken, "}");
fwrite($drucken, "}");
?>
