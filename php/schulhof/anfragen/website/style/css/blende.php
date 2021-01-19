// HELL;

#cms_blende_o, #cms_aktionsschicht_o {
	display: none;
	background: @h_hinweis_hintergrund;
	position: fixed;
	top: 0px;
	left: 0px;
	width: 100%;
	height: 100%;
	z-index: 6000;
}

#cms_blende_m {
margin: 20px auto;
	width: 500px;
}

#cms_aktionsschicht_m {
	margin: 20px auto;
	width: 1000px;
}

#cms_blende_i, .cms_aktionsschicht_i {
	border-radius: 20px;
	background: @h_haupt_hintergrund;
	padding: 10px;
	box-shadow: 0px 0px 20px @h_haupt_hintergrund;
}

#cms_blende_i .cms_spalte_i {
	text-align: center;
}

#cms_laden {
	padding: 10px;
}

// DUNKEL;

#cms_blende_o, #cms_aktionsschicht_o {
	background: @d_hinweis_hintergrund;
}

#cms_blende_i, .cms_aktionsschicht_i {
	background: @d_haupt_hintergrund;
	box-shadow: 0px 0px 20px @d_haupt_hintergrund;
}
