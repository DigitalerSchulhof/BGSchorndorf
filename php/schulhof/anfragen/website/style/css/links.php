// HELL;

a, .cms_link {
	font-size: inherit;
	text-decoration: none;
	color: @h_link_schrift;
	transition: 500ms ease-in-out;
	display: inline-block;
}

a:hover, .cms_link:hover {
	color: @h_link_schrifthover;
	display: inline-block;
	cursor: pointer;
}

#cms_fusszeile_o .cms_notiz a:hover {
	color: @h_fusszeile_linkschrifthover !important;
	display: inline-block;
}

#cms_fusszeile_o .cms_notiz a {
	color: @h_fusszeile_linkschrift !important;
	display: inline-block;
}

.cms_brotkrumen {
	color: @h_haupt_notizschrift;
	font-size: 80%;
}

.cms_aktionsicon {
	font-size: 100%;
	display: inline-block;
	float: right;
	text-align: right;
	position: relative;
	margin-left: 10px;
}

.cms_aktionsicon img:hover {
	cursor: pointer;
}

.cms_optimierung_H .cms_aktionsicon {
	width: 22px;
	height: 22px;
}
.cms_optimierung_H .cms_aktionsicon>img {
	width: 100%;
}

// DUNKEL;

a, .cms_link {
	color: @d_link_schrift;
}

a:hover, .cms_link:hover {
	color: @d_link_schrifthover;
}

#cms_fusszeile_o .cms_notiz a:hover {
	color: @d_fusszeile_linkschrifthover !important;
}

#cms_fusszeile_o .cms_notiz a {
	color: @d_fusszeile_linkschrift !important;
}

.cms_brotkrumen {
	color: @d_haupt_notizschrift;
}

// DRUCKEN;

.cms_brotkrumen {
	color: @h_haupt_notizschrift;
	font-size: 80%;
}

a, .cms_link {
	font-size: inherit;
	text-decoration: none;
	color: @h_link_schrift;
	display: inline-block;
}
