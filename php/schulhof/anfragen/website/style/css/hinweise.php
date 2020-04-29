// HELL;

.cms_hinweis_aussen {
	display: inline-block;
	position: relative;
	padding-right: 20px;
	background-image: url('../res/icons/klein/fragezeichen.png');
	background-repeat: no-repeat;
	background-position: right, center;
	min-height: 16px;
}

.cms_hinweis {
	color: @h_haupt_schriftfarbenegativ !important;
	background: @h_hinweis_hintergrund;
	padding: 0px 5px 0px 5px;
	position: absolute;
	font-family: 'robl';
	font-weight: normal !important;
	display: block;
	border-top-left-radius: @hinweis_radius;
	border-top-right-radius: @hinweis_radius;
	border-bottom-left-radius: @hinweis_radius;
	border-bottom-right-radius: @hinweis_radius;
	z-index: 50;
	width: 150px;
	overflow: visible;
	left: 0px;
	bottom: 25px;
	transition-delay: 1s;
	transition: 250ms ease-in-out 500ms;
	max-height: 0px;
	overflow: hidden;
	text-align: left;
}

.cms_hinweis_unten {
	bottom: unset;
	top: 25px;
}

.cms_layout_zeile_plus .cms_hinweis {
	bottom: 15px;
}

.cms_layout_spalte_plus .cms_hinweis,
.cms_layout_spalte .cms_hinweis {
	bottom: 105px;
}

.cms_button:hover > .cms_hinweis,
.cms_aktion_klein:hover > .cms_hinweis,
.cms_postfach_papierkorb_aussen:hover > .cms_hinweis,
.cms_button_nein:hover > .cms_hinweis,
.cms_button_ja:hover > .cms_hinweis,
.cms_button_passiv:hover > .cms_hinweis,
.cms_icon_klein_o:hover > .cms_hinweis,
.cms_beschluss_icon:hover > .cms_hinweis,
.cms_hinweis_aussen:hover > .cms_hinweis,
.cms_element_icon:hover > .cms_hinweis,
.cms_aktionsicon:hover > .cms_hinweis,
img:hover + .cms_hinweis{
	max-height: 100px;
	padding: 5px;
	z-index: 5;
}

td:last-child .cms_hinweis, .cms_notifikation_schliessen .cms_hinweis,
.cms_beschluss_icon .cms_hinweis, .cms_element_icon .cms_hinweis,
.cms_layout_spalte_plus:last-child .cms_hinweis, .cms_vollbild_schliessen .cms_hinweis,
.cms_neuigkeit_oeffnen .cms_hinweis, .cms_gruppen_oeffentlich_art .cms_hinweis {
	right: 0px !important;
	left: auto !important;
	text-align: right !important;
}

.cms_vollbild_schliessen .cms_hinweis {
	top: 25px;
	bottom: 0;
}

.cms_dateisystem_hochladen_dateiliste .cms_button_nein .cms_hinweis {
	left: 25px !important;
	top: auto !important;
	bottom: 0px !important;
	text-align: left !important;
}

.cms_icon_klein .cms_hinweis {
	left: 0px;
	text-align: left;
}

// DUNKEL;

.cms_hinweis {
	color: @d_haupt_schriftfarbenegativ !important;
	background: @d_hinweis_hintergrund;
}
