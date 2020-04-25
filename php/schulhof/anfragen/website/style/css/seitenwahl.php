// HELL;

.cms_seitenwahl {
	display: none;
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	background: @h_haupt_abstufung1;
	border-bottom-right-radius: 5px;
	border-bottom-left-radius: 5px;
	z-index: 2;
	margin-bottom: 20px;
	box-shadow: @h_haupt_hintergrund 0px 0px 7px;
}

.cms_seitenwahlzeile {
	position: relative;
}

.cms_seitenwahl li {
	list-style-type: none;
	margin-left: 15px;
}

.cms_seitenwahl li.cms_notiz {
	margin-left: 32px;
}

.cms_seitenwahl .cms_spalte_i > ul > li {
	margin-left: 0px;
}

.cms_seitenwahl li .cms_aktion_klein {
	position: relative;
	bottom: -3px;
}

.cms_seitenwahl li .cms_aktion_klein .cms_hinweis {
	left: 0px!important;
	right: auto!important;
	text-align: left!important;
}

// DUNKEL;

.cms_seitenwahl {
	background: @d_haupt_abstufung1;
	box-shadow: @d_haupt_hintergrund 0px 0px 7px;
}
