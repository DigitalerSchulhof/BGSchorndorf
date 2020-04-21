// HELL;

.cms_terminuebersicht {
	padding: 0px;
	margin: 0px;
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.cms_terminuebersicht li {
	padding: 0px;
	margin: 0px;
	border-top: 1px solid @h_haupt_abstufung1;
	list-style-type: none;
	display: block;
	text-overflow: ellipsis;
}

.cms_terminlink {
	width: 100%;
	display: block;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-right: 5px;
	padding-left: 80px;
	position: relative;
	transition: 250ms ease-in-out;
	min-height: 70px;
}

.cms_kalenderblaetter {
	text-align: center;
}

.cms_kalender_zusatzinfo {
	display: inline-block;
	padding: 2px 0px 2px 20px;
	font-size: 80%;
	background-position: left center;
	background-repeat: no-repeat;
	margin-right: 10px;
	margin-bottom: 5px;
	min-height: 16px;
}

.cms_kalender_zusatzinfo_intern {
	color: @h_haupt_schriftfarbenegativ !important;
	background-color: @h_haupt_abstufung2;
	border-radius: @button_rundeecken;
	padding: 5px 5px 5px 25px;
	background-position: 5px center;
	background-image: url('../res/icons/oegruppen/intern.png');
}

.cms_terminlink h3 {
	font-size: 110%;
	font-weight: bold;
	margin-top: 2px;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_terminlink p, .cms_terminlink h3, .cms_terminlink span {
	color: @h_haupt_schriftfarbepositiv;
	overflow: hidden;
	text-overflow: ellipsis;
}

.cms_terminlink .cms_notiz {margin-top: 0px;}
.cms_terminlink p:last-child {margin-bottom: 0px;}

.cms_terminlink:hover {
	background-color: @h_haupt_abstufung1;
}
.cms_terminlink:hover .cms_button {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_terminlink .cms_kalenderblaetter {
	position: absolute;
	left: 5px;
	top: 5px;
	width: 70px;
	text-align: center;
}

.cms_terminlink .cms_kalenderblatt,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt {
	display: inline-block;
	text-align: center;
}

.cms_terminlink .cms_kalenderblatt_i,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_i {
	display: inline-block;
	width: 32px;
}

.cms_terminlink .cms_kalenderblatt_monat,
.cms_terminlink .cms_kalenderblatt_tagnr,
.cms_terminlink .cms_kalenderblatt_tagbez,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagbez {
	display: block;
	text-align: center;
	width: 100%;
	line-height: 1.2em !important;
}

.cms_terminlink .cms_kalenderblatt_monat,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat {
	background: @h_kalenderklein_hintergrundmonat;
	font-weight: @kalenderklein_schriftdickemonat;
	color: @h_kalenderklein_farbemonat;
	font-size: 10px;
	border-top: @kalenderklein_linienstaerkeobenmonat;
	border-left: @kalenderklein_linienstaerkelinksmonat;
	border-right: @kalenderklein_linienstaerkerechtsmonat;
	border-bottom: @kalenderklein_linienstaerkeuntenmonat;
	padding: 2px 0px;
	border-top-right-radius: @kalenderklein_radiusobenmonat;
	border-top-left-radius: @kalenderklein_radiusobenmonat;
	border-bottom-right-radius: @kalenderklein_radiusuntenmonat;
	border-bottom-left-radius: @kalenderklein_radiusuntenmonat;
	border-color: @h_kalenderklein_linienfarbe;
}

.cms_terminlink .cms_kalenderblatt_tagnr,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr {
	background: @h_kalenderklein_hintergrundtagnr;
	font-weight: @kalenderklein_schriftdicketagnr;
	color: @h_kalenderklein_farbetagnr;
	font-size: 18px;
	border-top: @kalenderklein_linienstaerkeobentagnr;
	border-left: @kalenderklein_linienstaerkelinkstagnr;
	border-right: @kalenderklein_linienstaerkerechtstagnr;
	border-bottom: @kalenderklein_linienstaerkeuntentagnr;
	padding: 2px 0px;
	border-top-right-radius: @kalenderklein_radiusobentagnr;
	border-top-left-radius: @kalenderklein_radiusobentagnr;
	border-bottom-right-radius: @kalenderklein_radiusuntentagnr;
	border-bottom-left-radius: @kalenderklein_radiusuntentagnr;
	border-color: @h_kalenderklein_linienfarbe;
}

.cms_terminlink .cms_kalenderblatt_tagbez,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagbez {
	background: @h_kalenderklein_hintergrundtagbez;
	font-weight: @kalenderklein_schriftdicketagbez;
	color: @h_kalenderklein_farbetagbez;
	font-size: 10px;
	border-top: @kalenderklein_linienstaerkeobentagbez;
	border-left: @kalenderklein_linienstaerkelinkstagbez;
	border-right: @kalenderklein_linienstaerkerechtstagbez;
	border-bottom: @kalenderklein_linienstaerkeuntentagbez;
	padding: 2px 0px;
	border-top-right-radius: @kalenderklein_radiusobentagbez;
	border-top-left-radius: @kalenderklein_radiusobentagbez;
	border-bottom-right-radius: @kalenderklein_radiusuntentagbez;
	border-bottom-left-radius: @kalenderklein_radiusuntentagbez;
	border-color: @h_kalenderklein_linienfarbe;
}

.cms_terminlink .cms_kalenderblatt_uhrzeit {
	display: block;
	text-align: center;
	font-weight: normal;
	font-size: 80%;
	line-height: 1.2em;
	padding: 5px 0px 0px 0px;
}

.cms_termin_detialkalenderblatt .cms_kalenderblaetter {
	display: block;
	width: 100%;
	text-align: center;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt {
	width: 40%;
	display: inline-block;
	text-align: center;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_i {
	display: inline-block;
	width: 100%;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_monat,
.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr,
.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {
	display: block;
	text-align: center;
	width: 100%;
	line-height: 1.2em !important;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {
	background: @h_kalendergross_hintergrundmonat;
	font-weight: @kalendergross_schriftdickemonat;
	color: @h_kalendergross_farbemonat;
	font-size: 20px;
	border-top: @kalendergross_linienstaerkeobenmonat;
	border-left: @kalendergross_linienstaerkelinksmonat;
	border-right: @kalendergross_linienstaerkerechtsmonat;
	border-bottom: @kalendergross_linienstaerkeuntenmonat;
	border-bottom: none;
	padding: 4px 0px;
	border-top-right-radius: @kalendergross_radiusobenmonat;
	border-top-left-radius: @kalendergross_radiusobenmonat;
	border-bottom-right-radius: @kalendergross_radiusuntenmonat;
	border-bottom-left-radius: @kalendergross_radiusuntenmonat;
	border-color: @h_kalendergross_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {
	background: @h_kalendergross_hintergrundtagnr;
	font-weight: @kalendergross_schriftdicketagnr;
	color: @h_kalendergross_farbetagnr;
	font-size: 45px;
	border-top: @kalendergross_linienstaerkeobentagnr;
	border-left: @kalendergross_linienstaerkelinkstagnr;
	border-right: @kalendergross_linienstaerkerechtstagnr;
	border-bottom: @kalendergross_linienstaerkeuntentagnr;
	padding: 8px 0px 4px 0px;
	border-top-right-radius: @kalendergross_radiusobentagnr;
	border-top-left-radius: @kalendergross_radiusobentagnr;
	border-bottom-right-radius: @kalendergross_radiusuntentagnr;
	border-bottom-left-radius: @kalendergross_radiusuntentagnr;
	border-color: @h_kalendergross_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {
	background: @h_kalendergross_hintergrundtagbez;
	font-weight: @kalendergross_schriftdicketagbez;
	color: @h_kalendergross_farbetagbez;
	font-size: 20px;
	border-top: @kalendergross_linienstaerkeobentagbez;
	border-left: @kalendergross_linienstaerkelinkstagbez;
	border-right: @kalendergross_linienstaerkerechtstagbez;
	border-bottom: @kalendergross_linienstaerkeuntentagbez;
	padding: 4px 0px;
	border-top-right-radius: @kalendergross_radiusobentagbez;
	border-top-left-radius: @kalendergross_radiusobentagbez;
	border-bottom-right-radius: @kalendergross_radiusuntentagbez;
	border-bottom-left-radius: @kalendergross_radiusuntentagbez;
	border-color: @h_kalendergross_linienfarbe;
}

.cms_termin_detailinformationen {
	margin-top: 15px;
}

.cms_termindetails {
	padding: 0px;
	margin: 7px 0px 0px 0px;
}

.cms_termindetails li {
	padding: 0px;
	margin-left: 0px;
	margin-right: 0px;
	text-align: center;
	list-style-type: none;
}

.cms_termindetails_zusatzinfo {
	display: inline-block;
	padding: 2px 0px 2px 20px;
	background-position: left center;
	background-repeat: no-repeat;
	margin-right: 0px;
	min-height: 16px;
}

.cms_termindetails_zusatzinfo:hover {
	cursor: pointer !important;
}

.cms_termin_detailinformationen h3 {
	text-align: center;
}

.cms_ferienkalender {
	width: 100%;
	border-spacing: 0px;
	border-collapse: collapse;
}

.cms_ferienkalender th {
	width:8.33333%;
	font-weight: bold;
	padding: 2px 5px;
	border-right: 1px solid @h_haupt_abstufung1;
}

.cms_ferienkalender td {
	padding: 2px 5px;
	border-right: 1px solid @h_haupt_abstufung1;
	border-top: 1px solid @h_haupt_abstufung1;
}

.cms_ferienkalender td:last-child,
.cms_ferienkalender th:last-child {
	border-right: none;
}

.cms_ferienkalender_inhalt {
	display: inline-block;
	width: 50%;
	text-align: center;
}

.cms_ferienkalender_we {background-color: @h_haupt_meldunginfohinter;}
.cms_ferienkalender_frei {background-color: @h_haupt_meldungerfolghinter;}

// DUNKEL;

.cms_terminuebersicht {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_terminuebersicht li {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_kalender_zusatzinfo_intern {
	color: @d_haupt_schriftfarbenegativ !important;
	background-color: @d_haupt_abstufung2;
}

.cms_terminlink p, .cms_terminlink h3, .cms_terminlink span {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_terminlink:hover {
	background-color: @d_haupt_abstufung1;
}
.cms_terminlink:hover .cms_button {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_terminlink .cms_kalenderblatt_monat,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_monat {
	background: @d_kalenderklein_hintergrundmonat;
	color: @d_kalenderklein_farbemonat;
	border-color: @d_kalenderklein_linienfarbe;
}

.cms_terminlink .cms_kalenderblatt_tagnr,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,
.cms_galerieuebersicht_liste .cms_galerielink .cms_kalenderblatt_tagnr {
	background: @d_kalenderklein_hintergrundtagnr;
	color: @d_kalenderklein_farbetagnr;
	border-color: @d_kalenderklein_linienfarbe;
}

.cms_terminlink .cms_kalenderblatt_tagbez,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez {
	background: @d_kalenderklein_hintergrundtagbez;
	color: @d_kalenderklein_farbetagbez;
	border-color: @d_kalenderklein_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {
	background: @d_kalendergross_hintergrundmonat;
	color: @d_kalendergross_farbemonat;
	border-color: @d_kalendergross_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {
	background: @d_kalendergross_hintergrundtagnr;
	color: @d_kalendergross_farbetagnr;
	border-color: @d_kalendergross_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {
	background: @d_kalendergross_hintergrundtagbez;
	color: @d_kalendergross_farbetagbez;
	border-color: @d_kalendergross_linienfarbe;
}

.cms_ferienkalender th {
	border-right: 1px solid @d_haupt_abstufung1;
}

.cms_ferienkalender td {
	border-right: 1px solid @d_haupt_abstufung1;
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_ferienkalender_we {background-color: @d_haupt_meldunginfohinter;}
.cms_ferienkalender_frei {background-color: @d_haupt_meldungerfolghinter;}

// DRUCKEN;

.cms_termin_detialkalenderblatt .cms_kalenderblatt_monat,
.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr,
.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {
	display: block;
	text-align: center;
	width: 100%;
	line-height: 1.2em !important;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_monat {
	background: @h_kalendergross_hintergrundmonat;
	font-weight: @kalendergross_schriftdickemonat;
	color: @h_kalendergross_farbemonat;
	font-size: 20px;
	border-top: @kalendergross_linienstaerkeobenmonat;
	border-left: @kalendergross_linienstaerkelinksmonat;
	border-right: @kalendergross_linienstaerkerechtsmonat;
	border-bottom: @kalendergross_linienstaerkeuntenmonat;
	border-bottom: none;
	padding: 4px 0px;
	border-top-right-radius: @kalendergross_radiusobenmonat;
	border-top-left-radius: @kalendergross_radiusobenmonat;
	border-bottom-right-radius: @kalendergross_radiusuntenmonat;
	border-bottom-left-radius: @kalendergross_radiusuntenmonat;
	border-color: @h_kalendergross_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagnr {
	background: @h_kalendergross_hintergrundtagnr;
	font-weight: @kalendergross_schriftdicketagnr;
	color: @h_kalendergross_farbetagnr;
	font-size: 45px;
	border-top: @kalendergross_linienstaerkeobentagnr;
	border-left: @kalendergross_linienstaerkelinkstagnr;
	border-right: @kalendergross_linienstaerkerechtstagnr;
	border-bottom: @kalendergross_linienstaerkeuntentagnr;
	padding: 8px 0px 4px 0px;
	border-top-right-radius: @kalendergross_radiusobentagnr;
	border-top-left-radius: @kalendergross_radiusobentagnr;
	border-bottom-right-radius: @kalendergross_radiusuntentagnr;
	border-bottom-left-radius: @kalendergross_radiusuntentagnr;
	border-color: @h_kalendergross_linienfarbe;
}

.cms_termin_detialkalenderblatt .cms_kalenderblatt_tagbez {
	background: @h_kalendergross_hintergrundtagbez;
	font-weight: @kalendergross_schriftdicketagbez;
	color: @h_kalendergross_farbetagbez;
	font-size: 20px;
	border-top: @kalendergross_linienstaerkeobentagbez;
	border-left: @kalendergross_linienstaerkelinkstagbez;
	border-right: @kalendergross_linienstaerkerechtstagbez;
	border-bottom: @kalendergross_linienstaerkeuntentagbez;
	padding: 4px 0px;
	border-top-right-radius: @kalendergross_radiusobentagbez;
	border-top-left-radius: @kalendergross_radiusobentagbez;
	border-bottom-right-radius: @kalendergross_radiusuntentagbez;
	border-bottom-left-radius: @kalendergross_radiusuntentagbez;
	border-color: @h_kalendergross_linienfarbe;
}
