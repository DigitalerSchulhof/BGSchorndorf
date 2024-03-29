// HELL;

.cms_pinnwand_anschlaege {
	display: flex;
	flex-wrap: wrap;
}

.cms_pinnwand_anschlag_aussen {
	width: 50%;
}

.cms_pinnwand_anschlag_innen {
	margin: 10px;
	box-shadow: 0px 0px 10px @h_haupt_abstufung1;
}

.cms_pinnwand_datum,
.cms_pinnwand_ersteller,
.cms_pinnwand_titel,
.cms_pinnwand_inhalt {
	padding: 0px 10px 0px 10px;
	margin: 0px;
}

.cms_pinnwand_titel {
	padding-top: 10px;
	padding-bottom: 5px;
	color: @h_haupt_schriftfarbepositiv;
}

.cms_pinnwand_ersteller {
	padding-top: 5px;
	padding-bottom: 10px;
}

.cms_pinnwand_titel, .cms_pinnwand_datum, .cms_pinnwand_ersteller {
	background: @h_haupt_abstufung1;
	padding-bottom: 5px;
}

.cms_pinnwand_inhalt {
	padding-top: 10px;
	padding-bottom: 10px;
}

.cms_pinnwand_datum, .cms_pinnwand_ersteller {
	font-size: 75%;
	color: @h_haupt_schriftfarbepositiv;
}

.cms_pinnwand_ersteller .cms_link {font-size: inherit;}

// DUNKEL;

.cms_pinnwand_anschlag_innen {
	box-shadow: 0px 0px 10px @d_haupt_abstufung1;
}

.cms_pinnwand_titel {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_pinnwand_titel, .cms_pinnwand_datum, .cms_pinnwand_ersteller {
	background: @d_haupt_abstufung1;
}

.cms_pinnwand_datum, .cms_pinnwand_ersteller {
	color: @d_haupt_schriftfarbepositiv;
}
