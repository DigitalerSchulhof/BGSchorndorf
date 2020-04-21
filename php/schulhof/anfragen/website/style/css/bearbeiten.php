// HELL;

.cms_bearbeitenwahl {
	width: 100%;
}

.cms_bearbeitenwahl th, .cms_bearbeitenwahl td {
	text-align: center;
}

.cms_bearbeitenwahl th {
	font-weight: bold;
}

#cms_website_bearbeiten_i .cms_notiz {text-align: center;}

.cms_website_neu {
	height: 5px;
	transition: 500ms ease-in-out;
	background-color: @h_haupt_meldungerfolghinter;
}

.cms_website_bearbeiten {transition: 500ms ease-in-out;}

.cms_website_neu:hover, .cms_website_bearbeiten:hover {
	cursor: pointer;
}

.cms_website_neu:hover {background-color: @h_haupt_meldungerfolgakzent;}
.cms_website_bearbeiten:hover {background-color: @h_haupt_meldungwarnunghinter;}

.cms_website_neu_menue, .cms_website_bearbeiten_menue {
	padding: 5px 10px 10px 10px;
	transition: 500ms ease-in-out;
	margin: 0px;
	position: relative;
}

.cms_website_neu_menue {background-color: @h_haupt_meldungerfolghinter;}
.cms_website_bearbeiten_menue {
	padding-top: 10px;
	background-color: @h_haupt_meldungwarnunghinter;
}

.cms_website_bearbeiten_menue p.cms_elementicons {
	margin: 0px;
	position: absolute;
	top: 10px;
	right: 10px;
	width: 16px;
}

.cms_element_icon {
	display: inline-block;
	margin-bottom: 5px;
	position: relative;
}

.cms_website_neu_menue_box, .cms_website_bearbeiten_menue_box {
	margin: 0px !important;
}

.cms_website_neu_element, .cms_website_bearbeiten_element {
	margin-top: 10px;
	padding-top: 10px;
	display: none;
}

.cms_website_neu_element {border-top: 1px solid @h_haupt_meldungerfolgakzent;}
.cms_website_bearbeiten_element {border-top: 1px solid @h_haupt_meldungwarnungakzent;}

.cms_element_inaktiv {
	opacity: .5;
}

.cms_element_neuedaten {
	background-color: @h_haupt_meldungwarnunghinter;
}

.cms_element_neuedaten_anzeige {
	border-left: 5px solid @h_haupt_meldungwarnunghinter;
	padding-left: 5px;
}


// DUNKEL;

.cms_website_neu {
background-color: @d_haupt_meldungerfolghinter;
}

.cms_website_neu:hover {background-color: @d_haupt_meldungerfolgakzent;}
.cms_website_bearbeiten:hover {background-color: @d_haupt_meldungwarnunghinter;}

.cms_website_neu_menue {background-color: @d_haupt_meldungerfolghinter;}
.cms_website_bearbeiten_menue {
background-color: @d_haupt_meldungwarnunghinter;
}

.cms_website_neu_element {border-top: 1px solid @d_haupt_meldungerfolgakzent;}
.cms_website_bearbeiten_element {border-top: 1px solid @d_haupt_meldungwarnungakzent;}

.cms_element_neuedaten {
background-color: @d_haupt_meldungwarnunghinter;
}

.cms_element_neuedaten_anzeige {
border-left: 5px solid @d_haupt_meldungwarnunghinter;
}
