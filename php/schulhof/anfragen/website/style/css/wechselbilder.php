// HELL;

.cms_wechselbilder_o {
	position: relative;
}

.cms_wechselbilder_m {
	padding: 0px;
	margin: 0px;
}

.cms_wechselbilder_bild {
	padding: 0px;
	margin: 0px;
	line-height: 0;
	width: 100%;
	float: left;
	margin-right: -100%;
	position: relative;
	opacity: 0;
	display: block;
	z-index: 1;
	transition: 1s ease-in-out;
	text-align: center;
}

.cms_wechselbilder_bild > img {
	width: 100%;
}

.cms_wechselbild_voriges, .cms_wechselbild_naechstes {
	position: absolute;
	top: 0px;
	height: 100%;
	background-color: @h_galerie_button;
	display: block;
	color: @h_haupt_schriftfarbepositiv;
	top: 0px;
	z-index: 2;
	width: auto;
	line-height: 100%;
	width: 20px;
	opacity: 0;
	transition: 250ms ease-in-out;
}

.cms_wechselbild_voriges {left: 0px;}
.cms_wechselbild_naechstes {right: 0px;}

.cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {
	background-color: @h_galerie_buttonhover;
}

.cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {
	opacity: 1 !important;
	cursor: pointer;
}

.cms_wechselbilder_wahl {
	position: absolute;
	text-align: center;
	top: 15px;
	left: 0px;
	width: 100%;
	z-index: 2;
	padding: 0px 25px 0px 25px;
	transition: 250ms ease-in-out;
}

.cms_wechselbilder_wahl span {
	background-color: @h_galerie_button;
	width: @galerie_buttonbreite;
	height: @galerie_buttonhoehe;
	border-radius: 10px;
	display: inline-block;
	transition: 250ms ease-in-out;
	opacity: 0;
}

span.cms_wechselbilder_knopf_aktiv {
	background-color: @h_galerie_buttonaktiv;
}

.cms_wechselbilder_o:hover .cms_wechselbild_voriges,
.cms_wechselbilder_o:hover .cms_wechselbild_naechstes,
.cms_wechselbilder_o:hover .cms_wechselbilder_knopf {opacity: .5;}

.cms_wechselbilder_o:hover .cms_wechselbilder_knopf_aktiv {opacity: 1 !important;}

.cms_wechselbilder_wahl span:hover {
	background-color: @h_galerie_buttonhover;
	opacity: 1 !important;
	cursor: pointer;
}

.cms_wechselbilder_galerie_unterschrift {
	width: 100%;
	padding: 5px;
}

// DUNKEL;

.cms_wechselbild_voriges, .cms_wechselbild_naechstes {
	background-color: @d_galerie_button;
	color: @d_haupt_schriftfarbepositiv;
}

.cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {
	background-color: @d_galerie_buttonhover;
}

.cms_wechselbilder_wahl span {
	background-color: @d_galerie_button;
}

span.cms_wechselbilder_knopf_aktiv {
	background-color: @d_galerie_buttonaktiv;
}

.cms_wechselbilder_wahl span:hover {
	background-color: @d_galerie_buttonhover;
}
