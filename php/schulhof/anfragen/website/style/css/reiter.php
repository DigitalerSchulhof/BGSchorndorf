// HELL;

.cms_reitermenue, .cms_reitermenue li {
	margin-bottom: 0px !important;
	display: inline-block;
	list-style-type: none;
	margin-left: 0px;
	margin-top: 0px;
}

.cms_reiter, .cms_reiter_aktiv {
	display: inline-block;
	padding: 7px 10px 4px 10px;
	transition: 250ms ease-in-out;
	font-weight: bold;
}

.cms_reiter {
	color: @h_reiter_farbe;
	background: @h_reiter_hintergrund;
	border-top-right-radius: @reiter_radiusoben;
	border-top-left-radius: @reiter_radiusoben;
	border-bottom-right-radius: @reiter_radiusunten;
	border-bottom-left-radius: @reiter_radiusunten;
}

.cms_reiter:hover, .cms_reiter_aktiv:hover {
	color: @h_reiter_farbehover;
	background: @h_reiter_hintergrundhover;
	cursor: pointer;
	border-top-right-radius: @reiter_radiusoben;
	border-top-left-radius: @reiter_radiusoben;
	border-bottom-right-radius: @reiter_radiusunten;
	border-bottom-left-radius: @reiter_radiusunten;
}

.cms_reiter_aktiv {
	color: @h_reiter_farbeaktiv;
	background: @h_reiter_hintergrundaktiv;
	cursor: pointer;
	border-top-right-radius: @reiter_radiusoben;
	border-top-left-radius: @reiter_radiusoben;
	border-bottom-right-radius: @reiter_radiusunten;
	border-bottom-left-radius: @reiter_radiusunten;
}

.cms_reitermenue_o {
	border-color: @h_haupt_abstufung2;
	border-style: solid;
	border-width: 0px;
	border-top-width: 2px;
	border-bottom-width: 2px;
	display: none;
}

.cms_reitermenue_i {
	padding: 10px 0px;
}

.cms_unternavigation_o .cms_reitermenue_i {
	background: @h_haupt_abstufung1;
}

.cms_hauptteil_o .cms_reitermenue_i {
	background: @h_haupt_hintergrund;
}

// DUNKEL;

.cms_reiter {
	color: @d_reiter_farbe;
	background: @d_reiter_hintergrund;
}

.cms_reiter:hover, .cms_reiter_aktiv:hover {
	color: @d_reiter_farbehover;
	background: @d_reiter_hintergrundhover;
}

.cms_reiter_aktiv {
	color: @d_reiter_farbeaktiv;
	background: @d_reiter_hintergrundaktiv;
}

.cms_reitermenue_o {
	border-color: @d_haupt_abstufung2;
}

.cms_unternavigation_o .cms_reitermenue_i {
	background: @d_haupt_abstufung1;
}

.cms_hauptteil_o .cms_reitermenue_i {
	background: @d_haupt_hintergrund;
}
