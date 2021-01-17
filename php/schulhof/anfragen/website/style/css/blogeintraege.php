// HELL;

.cms_bloguebersicht_liste {
	padding: 0px;
	margin: 0px;
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.cms_bloguebersicht_liste li {
	padding: 0px;
	margin: 0px;
	border-top: 1px solid @h_haupt_abstufung1;
	list-style-type: none;
	display: block;
}

.cms_bloguebersicht_liste .cms_bloglink {
	width: 100%;
	display: block;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-right: 5px;
	padding-left: 45px;
	position: relative;
	transition: 250ms ease-in-out;
	min-height: 70px;
}

.cms_bloguebersicht_liste .cms_bloglink h3 {
	font-size: 110%;
	font-weight: bold;
	margin-top: 2px;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_bloguebersicht_liste .cms_bloglink p,
.cms_bloguebersicht_artikel .cms_bloglink p,
.cms_bloguebersicht_diashow .cms_bloglink p,
.cms_bloguebersicht_liste .cms_bloglink h3,
.cms_bloguebersicht_artikel .cms_bloglink h3,
.cms_bloguebersicht_diashow .cms_bloglink h3,
.cms_bloguebersicht_liste .cms_bloglink span,
.cms_bloguebersicht_artikel .cms_bloglink span,
.cms_bloguebersicht_diashow .cms_bloglink span {
	color: @h_haupt_schriftfarbepositiv;
	overflow: hidden;
	text-overflow: ellipsis;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_notiz {margin-top: 0px;}
.cms_bloguebersicht_liste .cms_bloglink p:last-child {margin-bottom: 0px;}

.cms_bloguebersicht_liste .cms_bloglink:hover {
	background-color: @h_haupt_abstufung1;
}
.cms_bloguebersicht_liste .cms_bloglink:hover .cms_button {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblaetter {
	position: absolute;
	left: 5px;
	top: 5px;
	width: 32px;
	text-align: center;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt {
	display: inline-block;
	text-align: center;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_i {
	display: inline-block;
	width: 32px;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,
.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez {
	display: block;
	text-align: center;
	width: 100%;
	line-height: 1.2em !important;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_uhrzet {
	display: block;
	text-align: center;
	font-weight: normal;
	font-size: 80%;
	line-height: 1.2em;
	padding: 5px 0px 0px 0px;
}

.cms_bloguebersicht_liste .cms_bloglink .cms_bloglink_vorschaubild {
	float: right;
	margin-left: 5px;
	width: 30%;
}

.cms_bloguebersicht_artikel {
	padding: 0px;
	margin: 0px;
	display: flex;
	flex-wrap: wrap;
}

.cms_optimierung_P .cms_bloguebersicht_artikel li,
.cms_optimierung_T .cms_bloguebersicht_artikel li {
	width: 50%;
}

.cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(odd),
.cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(odd) {
	border-right: 5px solid @h_haupt_hintergrund;
}

.cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(even),
.cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(even) {
	border-left: 5px solid @h_haupt_hintergrund;
}

.cms_optimierung_H .cms_bloguebersicht_artikel li {
	width: 100%;
}

.cms_bloguebersicht_artikel li {
flex-wrap: nowrap;
	padding: 0px;
	margin: 0px;
list-style-type: none;
	display: block;
}

.cms_bloguebersicht_artikel .cms_bloglink {
	width: 100%;
	height: 100%;
	display: block;
	padding: 5px 10px;
	position: relative;
	transition: 250ms ease-in-out;
	border-top: 1px solid @h_haupt_abstufung1;
}

.cms_bloguebersicht_artikel .cms_bloglink h3 {
	font-size: 110%;
	font-weight: bold;
	margin-top: 2px;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_bloguebersicht_artikel .cms_bloglink .cms_notiz {margin-top: 0px;}
.cms_bloguebersicht_artikel .cms_bloglink p:last-child {margin-bottom: 0px;}

.cms_bloguebersicht_artikel .cms_bloglink:hover {
	background-color: @h_haupt_abstufung1;
}
.cms_bloguebersicht_artikel .cms_bloglink:hover .cms_button {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_bloguebersicht_artikel .cms_bloglink .cms_bloglink_vorschaubild {
	width: 100%;
}

.cms_bloguebersicht_diashow {
	padding: 0px;
	margin: 0px;
	border-top: 1px solid @h_haupt_abstufung1;
	border-bottom: 1px solid @h_haupt_abstufung1;
list-style-type: none;
	display: block;
	position: relative;
}

.cms_bloguebersicht_diashow .cms_wechselbilder_m {margin: 0px !important;}

.cms_bloguebersicht_diashow .cms_wechselbilder_wahl {top: 35px !important;}

.cms_bloguebersicht_diashow .cms_bloglink {
	margin: 0px;
	width: 100%;
	height: 100%;
	display: block;
	padding: 5px 10px;
	position: relative;
	transition: 250ms ease-in-out;
	text-align: left !important;
}

.cms_bloguebersicht_diashow .cms_bloglink h3 {
	font-size: 110%;
	font-weight: bold;
	margin-top: 2px;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_bloguebersicht_diashow .cms_bloglink h3+img {
	width: 100%;
}

.cms_bloguebersicht_diashow .cms_bloglink .cms_notiz {margin-top: 0px;}
.cms_bloguebersicht_diashow .cms_bloglink p:last-child {margin-bottom: 0px;}

.cms_bloguebersicht_diashow .cms_bloglink:hover {
	background-color: @h_haupt_abstufung1;
}
.cms_bloguebersicht_diashow .cms_bloglink:hover .cms_button {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_bloguebersicht_diashow .cms_bloglink .cms_bloglink_vorschaubild {
	width: 100%;
}

// DUNKEL;

.cms_bloguebersicht_liste {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_bloguebersicht_liste li {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_bloguebersicht_liste .cms_bloglink p,
.cms_bloguebersicht_artikel .cms_bloglink p,
.cms_bloguebersicht_diashow .cms_bloglink p,
.cms_bloguebersicht_liste .cms_bloglink h3,
.cms_bloguebersicht_artikel .cms_bloglink h3,
.cms_bloguebersicht_diashow .cms_bloglink h3,
.cms_bloguebersicht_liste .cms_bloglink span,
.cms_bloguebersicht_artikel .cms_bloglink span,
.cms_bloguebersicht_diashow .cms_bloglink span {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_bloguebersicht_liste .cms_bloglink:hover {
	background-color: @d_haupt_abstufung1;
}
.cms_bloguebersicht_liste .cms_bloglink:hover .cms_button {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(odd),
.cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(odd) {
	border-right: 5px solid @d_haupt_hintergrund;
}

.cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(even),
.cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(even) {
	border-left: 5px solid @d_haupt_hintergrund;
}

.cms_bloguebersicht_artikel .cms_bloglink {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_bloguebersicht_artikel .cms_bloglink:hover {
	background-color: @d_haupt_abstufung1;
}
.cms_bloguebersicht_artikel .cms_bloglink:hover .cms_button {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_bloguebersicht_diashow {
	border-top: 1px solid @d_haupt_abstufung1;
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_bloguebersicht_diashow .cms_bloglink:hover {
	background-color: @d_haupt_abstufung1;
}

.cms_bloguebersicht_diashow .cms_bloglink:hover .cms_button {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}
