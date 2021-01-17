// HELL;

.cms_button_bedingt_logisch {
	background-color: @h_haupt_meldunginfohinter;
}

.cms_button_bedingt_bedingung {
	background-color: @h_haupt_meldungwarnunghinter;
}

.cms_bedingt_gui_logisch {
	background-color: @h_haupt_meldunginfohinter;
	background-repeat: no-repeat;
	background-position: 5px 5px;
	display: inline-block;
	border-radius: @haupt_radiusgross;
	padding: 5px;
	width: 100%;
}

.cms_bedingt_gui_logisch_operation {
	margin-top: 5px;
	margin-left: 5px;
}

.cms_bedingt_gui_logisch_feld,
.cms_bedingt_gui_logisch_hinzufuegen {
	display: block;
	border-radius: @haupt_radiusmittel;
	margin-left: 20px;
	margin-top: 20px;
	background-color: @h_haupt_abstufung1;
	padding: 3px;
}

.cms_aktion_klein {
	margin-bottom: 0px;
}

.cms_bedingt_gui_bedingung select,
.cms_bedingt_gui_bedingung input {
	height: 28px;
	transition: none !important;
}

.cms_bedingt_bedingung {
	margin-bottom: 5px;
}

// DUNKEL;

.cms_button_bedingt_logisch {
	background-color: @d_haupt_meldunginfohinter;
}

.cms_button_bedingt_bedingung {
	background-color: @d_haupt_meldungwarnunghinter;
}

.cms_bedingt_gui_logisch {
	background-color: @d_haupt_meldunginfohinter;
}

.cms_bedingt_gui_logisch_feld,
.cms_bedingt_gui_logisch_hinzufuegen {
	background-color: @d_haupt_abstufung1;
}
