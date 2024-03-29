// HELL;

.cms_galerie_liste {
	padding: 0px;
	margin: 0px;
	border-bottom: 1px solid @h_haupt_abstufung1;
}

.cms_galerieuebersicht_liste li {
	padding: 0px;
	margin: 0px;
	border-top: 1px solid @h_haupt_abstufung1;
	list-style-type: none;
	display: block;
}

.cms_galerieuebersicht_liste .cms_galerielink {
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

.cms_galerieuebersicht_liste .cms_galerielink h3 {
	font-size: 110%;
	font-weight: bold;
	margin-top: 2px;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_galerieuebersicht_liste .cms_galerielink p,
.cms_galerieuebersicht_liste .cms_galerielink h3,
.cms_galerieuebersicht_liste .cms_galerielink span,
.cms_galerieuebersicht_artikel .cms_galerielink p,
.cms_galerieuebersicht_artikel .cms_galerielink h3,
.cms_galerieuebersicht_artikel .cms_galerielink span {
	color: @h_haupt_schriftfarbepositiv;
	overflow: hidden;
	text-overflow: ellipsis;
}

.cms_galerieuebersicht_liste .cms_galerielink .cms_notiz {margin-top: 0px;}
.cms_galerieuebersicht_liste .cms_galerielink p:last-child {margin-bottom: 0px;}

.cms_galerieuebersicht_liste .cms_galerielink:hover {
	background-color: @h_haupt_abstufung1;
}

.cms_galerieuebersicht_liste .cms_galerielink:hover .cms_button {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_galerieuebersicht_liste .cms_galerielink .cms_galerielink_vorschaubild {
	float: right;
	margin-left: 5px;
	width: 30%;
}

.cms_galerieuebersicht_artikel {
	padding: 0px;
	margin: 0px;
	display: flex;
	flex-wrap: wrap;
}

.cms_optimierung_P .cms_galerieuebersicht_artikel li,
.cms_optimierung_T .cms_galerieuebersicht_artikel li {
	width: 50%;
}

.cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(odd),
.cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(odd) {
	border-right: 5px solid @h_haupt_hintergrund;
}

.cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(even),
.cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(even) {
	border-left: 5px solid @h_haupt_hintergrund;
}

.cms_optimierung_H .cms_galerieuebersicht_artikel li {
	width: 100%;
}

.cms_galerieuebersicht_artikel li {
	display: flex;
	flex-wrap: nowrap;
	padding: 0px;
	margin: 0px;
	list-style-type: none;
	display: block;
}

.cms_galerieuebersicht_artikel .cms_galerielink {
	width: 100%;
	height: 100%;
	display: block;
	padding: 5px 10px;
	position: relative;
	transition: 250ms ease-in-out;
	border-top: 1px solid @h_haupt_abstufung1;
}

.cms_galerieuebersicht_artikel .cms_galerielink h3 {
	font-size: 110%;
	font-weight: bold;
	margin-top: 2px;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_galerieuebersicht_artikel .cms_galerielink .cms_notiz {margin-top: 0px;}
.cms_galerieuebersicht_artikel .cms_galerielink p:last-child {margin-bottom: 0px;}

.cms_galerieuebersicht_artikel .cms_galerielink:hover {
	background-color: @h_haupt_abstufung1;
}

.cms_galerieuebersicht_artikel .cms_galerielink:hover .cms_button {
	background-color: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
}

.cms_galerieuebersicht_artikel .cms_galerielink .cms_galerielink_vorschaubild {
	width: 100%;
}

// DUNKEL;

.cms_galerie_liste {
	border-bottom: 1px solid @d_haupt_abstufung1;
}

.cms_galerieuebersicht_liste li {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_galerieuebersicht_liste .cms_galerielink p,
.cms_galerieuebersicht_liste .cms_galerielink h3,
.cms_galerieuebersicht_liste .cms_galerielink span,
.cms_galerieuebersicht_artikel .cms_galerielink p,
.cms_galerieuebersicht_artikel .cms_galerielink h3,
.cms_galerieuebersicht_artikel .cms_galerielink span {
	color: @d_haupt_schriftfarbepositiv;
}

.cms_galerieuebersicht_liste .cms_galerielink:hover {
	background-color: @d_haupt_abstufung1;
}

.cms_galerieuebersicht_liste .cms_galerielink:hover .cms_button {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(odd),
.cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(odd) {
	border-right: 5px solid @d_haupt_hintergrund;
}

.cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(even),
.cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(even) {
	border-left: 5px solid @d_haupt_hintergrund;
}

.cms_galerieuebersicht_artikel .cms_galerielink {
	border-top: 1px solid @d_haupt_abstufung1;
}

.cms_galerieuebersicht_artikel .cms_galerielink:hover {
	background-color: @d_haupt_abstufung1;
}

.cms_galerieuebersicht_artikel .cms_galerielink:hover .cms_button {
	background-color: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}
