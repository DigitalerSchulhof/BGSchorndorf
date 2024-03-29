// DRUCKEN;

* {
	-webkit-print-color-adjust: exact;
	font-family: 'rob', sans-serif;
	font-size: 10pt;
	font-weight: normal;
	padding: 0px;
	margin: 0px;
	list-style-type: none;
	line-height: 1.2em;
	text-decoration: none;
	box-sizing: border-box;
  visibility: hidden;
  max-height: 0;
  height: 0;
}

.cms_druckseite {
	width: 210mm;
	background: #ffffff;
	color: #000000;
}

.cms_druckseite, .cms_druckseite * {
  visibility: initial;
  max-height: initial;
  height: initial;
}

.cms_logo {
	display: inline-block;
}

.cms_logo_bild {
	float: left;
	width: 2.5cm;
	padding-right: 5mm;
}

.cms_logo_schrift {
	float: left;
	display: block;
}

.cms_logo_o, .cms_logo_u {
	position: relative;
	color: @h_logo_schriftfarbe;
	font-size: 25pt;
	padding: 2px 0px 0px 0px;
	display: block;
}

.cms_logo_o {
	font-weight: bold;
}

div.cms_druckkopf {
	display: block;
	margin-bottom: 1cm;
	margin-top: 2cm;
}

div.cms_druckkopf:first-child {
	margin-top: 0cm;
}

div#cms_druckfuss {
	text-align: center;
	font-size: 10pt !important;
	color: #aaaaaa;
	margin-top: 1cm;
	display: block;
}

div#cms_druckfuss p, div#cms_druckfuss b {
	font-size: 8pt !important;
}

b {
	font-weight: bold;
}

p {
	line-height: 1.2em;
	margin-bottom: .2cm;
}

p:last-child {
	margin-bottom: 0px;
}

h1 {
	font-size: 14pt;
	font-weight: bold;
	margin-bottom: .25cm;
}

.cms_druckkopf+h1 {
	margin-top: .5cm !important;
	page-break-before: avoid !important;
}

h2 {
	font-size: 12pt;
	font-weight: bold;
	margin-top: .6cm;
	margin-bottom: .2cm;
}

.cms_meldung h4 {
	margin-top: 0px !important;
}

h4 {
	margin-top: 30px;
	margin-bottom: @haupt_absatzschulhof;
	font-weight: bold;
	font-size: 100%;
}

b, strong {
	font-weight: bold;
	font-style: inherit;
	font-size: inherit;
	text-decoration: inherit;
	font-family: inherit;
}

.cms_notiz {
	margin-top: 10px;
	color: @h_haupt_notizschrift;
	font-size: 70% !important;
}

.cms_meldung h4 {
	margin-top: 10px;
}

.cms_datum {
	text-align: right;
}

.cms_clear {
	clear: both;
}

.cms_spalte_2:first-child .cms_spalte_i {padding-right: 2.5mm !important;}
.cms_spalte_2:last-child .cms_spalte_i {padding-left: 2.5mm !important;}

table {
	width: 100%;
	border-collapse:collapse;
	margin-bottom: .5cm;
}

table th {width: 4cm !important;}

table th, table td {
	padding: 1mm 0mm;
	border-bottom: 1pt solid #aaaaaa;
}

table tr:first-child th, table tr:first-child td {
	border-top: 1pt solid #aaaaaa;
}

table th, table td, table i, table b {
	font-size: 10pt;
}

table th {
	font-weight: bold;
	text-align: left;
}

.cms_markierte_liste_0 td {background: @h_haupt_hintergrund;}
.cms_markierte_liste_1 td {background: @h_haupt_abstufung1;}

.cms_vplanliste_entfall td:first-child {border-left: 1mm solid @h_haupt_meldungfehlerhinter; padding-left: 2mm;}
.cms_vplanliste_neu td:first-child {border-left: 1mm solid @h_haupt_meldungerfolghinter; padding-left: 2mm;}

.cms_meldung_info {
	border-left: 1mm solid @h_haupt_meldunginfoakzent;
	margin-top: 2mm;
	margin-bottom: 2mm;
	padding: 2mm;
}

.cms_meldung_vplan {
	border-left: 1mm solid @h_haupt_abstufung2;
	margin-top: 2mm;
	margin-bottom: 2mm;
	padding: 2mm;
}

.cms_zwischenueberschrift {
	border-right: none !important;
	border-top: 5mm solid #ffffff;
	text-align: center !important;
	background-color: #ededed;
	color: #aaaaaa;
}

.cms_zwischenueberschrift:first-child {
	border-top: none !important;
}

span.cms_unterschrift {
	display: block;
	width: 75%;
	border-bottom: 1pt solid #000000;
	margin-top: 1.5cm;
	margin-bottom: 1mm;
	font-size: 12pt;
}

p.cms_unterschrift {
	font-size: 8pt;
}

.cms_seitenumbruch {
	border-top: none;
	page-break-before: always;
}

@page {margin: 2cm;}
