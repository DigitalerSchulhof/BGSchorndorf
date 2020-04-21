// HELL;

.cms_notiz, .cms_hochladen_fortschritt_anzeige {
	color: @h_haupt_notizschrift;
	font-size: 70% !important;
}

.cms_notiz a, .cms_brotkrumen a {
	font-size: 100%;
}

p, ul, ol, table, li {
	margin-top: @haupt_absatzschulhof;
	margin-bottom: @haupt_absatzschulhof;
}

h1 {
	margin-top: 40px;
	margin-bottom: 15px;
	font-weight: bold;
	font-size: 170%;
	overflow: visible;
}

h2 {
	margin-top: 30px;
	margin-bottom: 10px;
	font-weight: bold;
	font-size: 140%;
}

h3 {
	margin-top: 30px;
	margin-bottom: 10px;
	font-weight: bold;
	font-size: 120%;
}

h4 {
	margin-top: 30px;
	margin-bottom: @haupt_absatzschulhof;
	font-weight: bold;
	font-size: 100%;
}

h2+h3 {
	margin-top: 20px;
}

h3+h4 {
	margin-top: 10px;
}

p.cms_notiz+h1, p.cms_brotkrumen + h1 {
	margin-top: @haupt_absatzbrotkrumen;
}

.cms_termine_jahresuebersicht + p.cms_notiz {
	margin-top: 40px;
}

p:first-child, h1:first-child, h2:first-child, h3:first-child,
h4:first-child, h5:first-child, h6:first-child, ul:first-child,
ol:first-child, table:first-child {
	margin-top: 0px !important;
}

p:last-child, h1:last-child, h2:last-child, h3:last-child,
h4:last-child, h5:last-child, h6:last-child, ul:last-child,
ol:last-child, table:last-child {
	margin-bottom: 0px !important;
}

ul li {
	list-style-type: square;
	margin-left: 20px;
}

ul ul li {
	list-style-type: circle;
	margin-left: 20px;
}

ol li {
	list-style-type: decimal;
	margin-left: 20px;
}

b, strong {
	font-weight: bold;
	font-style: inherit;
	font-size: inherit;
	text-decoration: inherit;
	font-family: inherit;
	color: inherit;
}
i, em {
	font-style: italic;
	font-weight: inherit;
	text-decoration: inherit;
	font-family: inherit;
	font-size: inherit;
	color: inherit;
}
u {
	font-style: inherit;
	font-weight: inherit;
	text-decoration: underline;
	font-family: inherit;
	font-size: inherit;
	color: inherit;
}

p span, p b, p i, p u,
li span, li b, li i, li u,
td span, td b, td i, td u,
th span, th b, th i, th u {
	font-size: inherit;
	font-family: inherit;
	color: inherit;
}

blockquote {
	background-image: url('../res/sonstiges/zitat.png');
	background-repeat: no-repeat;
	background-position: 10px 10px;
	padding: 30px 10px 10px 80px;
	min-height: 70px;
	font-family: inherit;
	color: inherit;
}

pre, .cms_konsole, .cms_code {
	background: @h_haupt_abstufung2;
	color: @h_haupt_schriftfarbenegativ;
	padding: 10px;
	font-family: monospace;
	border-radius: @button_rundeecken;
}

.cms_zentriert {
	text-align: center !important;
}

.cms_rechtsbuendig {
	text-align: right !important;
}

.cms_hauptteil_inhalt * {
	line-height: 1.5em !important;
}

.cms_hauptteil_inhalt .cms_uebersicht_kalender *,
.cms_hauptteil_inhalt .cms_uebersicht_kalender_beginn *,
.cms_hauptteil_inhalt .cms_uebersicht_kalender_ende * {
	line-height: 1.2em !important;
}

.cms_hauptteil_inhalt p, .cms_hauptteil_inhalt ul, .cms_hauptteil_inhalt ol,
.cms_hauptteil_inhalt table, .cms_download_anzeige, .cms_boxen_u, .cms_boxen_n {
	margin-top: @haupt_absatzwebsite;
	margin-bottom: @haupt_absatzwebsite;
}

.cms_hauptteil_inhalt h4 {
	margin-bottom: @haupt_absatzwebsite;
}

.cms_download_anzeige * {
	line-height: 1.2em;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_uebersicht * {
	line-height: 1.2em;
}

// DUNKEL;

.cms_notiz, .cms_hochladen_fortschritt_anzeige {
	color: @d_haupt_notizschrift;
}

pre, .cms_konsole, .cms_code {
	background: @d_haupt_abstufung2;
	color: @d_haupt_schriftfarbenegativ;
}
