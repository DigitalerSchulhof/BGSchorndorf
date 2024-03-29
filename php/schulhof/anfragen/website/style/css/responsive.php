// HELL;

@media (max-width: 999px) {
	.cms_optimierung_P #cms_kopfzeile_m,
	.cms_optimierung_P #cms_hauptteil_m,
	.cms_optimierung_P #cms_fusszeile_m,
	.cms_optimierung_P #cms_website_bearbeiten_m {
		width: 100% !important;
		margin: 0px !important;
	}

	.cms_optimierung_P .cms_unternavigation_m {
		width: 100% !important;
		margin: 0px !important;
	}

	#cms_aktionsschicht_m {width: 100%;}
}

@media (max-width: 799px) {
	.cms_kopfnavigation {display: none !important;}
	#cms_kopfnavigation {display: none !important;}
	#cms_hauptnavigation {display: none !important;}
	#cms_mobilnavigation {display: block !important;}

	.cms_bloguebersicht_artikel {
		display: block !important;
	}

	.cms_bloguebersicht_artikel li {
		width: 100% !important;
		border-left: none !important;
		border-right: none !important;
	}

	.cms_blockwahl {
		width: 50%;
	}

	@media (max-width: 599px) {
		.cms_eventuebersicht_box_a {
			float: none !important;
			width: 100% !important;
			margin-bottom: 30px !important;
		}

		ul.cms_uebersicht a p img {
			height: 100px !important;
		}

		.cms_neuigkeit {
			width: 50% !important;
		}

		.cms_voranmeldung_navigation li {
			width: 50%;
		}

		.cms_eventuebersicht_box_blog, .cms_eventuebersicht_box_termine,
		.cms_eventuebersicht_box_galerie {width: 100% !important;}

		.cms_eventuebersicht_box_i {padding: 0px;}

		.cms_optimierung_P .cms_spalte_2, .cms_optimierung_P .cms_spalte_3,
		.cms_optimierung_P .cms_spalte_60, .cms_optimierung_P .cms_spalte_40,
		.cms_optimierung_P .cms_spalte_4, .cms_optimierung_P .cms_spalte_34,
		.cms_optimierung_P .cms_spalte_6, .cms_optimierung_P .cms_spalte_15,
		.cms_optimierung_P .cms_spalte_25, .cms_optimierung_P .cms_spalte_45,
		.cms_optimierung_T .cms_spalte_2, .cms_optimierung_T .cms_spalte_3,
		.cms_optimierung_T .cms_spalte_60, .cms_optimierung_T .cms_spalte_40,
		.cms_optimierung_T .cms_spalte_4, .cms_optimierung_T .cms_spalte_34,
		.cms_optimierung_T .cms_spalte_6 .cms_optimierung_T .cms_spalte_15,
		.cms_optimierung_T .cms_spalte_25, .cms_optimierung_T .cms_spalte_45 {
			float: none !important;
			width: 100%;
			padding-bottom: 40px !important;
		}

		.cms_pinnwand_anschlag_aussen {
			width: 100% !important;
		}

		.cms_termindetailansicht_kalenderblaetter_o,
		.cms_termindetailansicht_details_o {
			float: none !important;
			width: 100% !important;
			margin-bottom: 20px !important;
		}

		.cms_termindetailansicht_kalenderblaetter_i,
		.cms_termindetailansicht_details_i {
			padding: 0px !important;
		}

		.cms_fussnavigation {
			padding-right: 0px !important;
			min-height: auto !important;
			text-align: center;
		}

		.cms_auszeichnung {
			position: static !important;
			top: auto !important;
			right: auto !important;
			margin-bottom: @haupt_absatzschulhof !important;
			text-align: center  !important;
		}

		#cms_fusszeile_i p {text-align: center;}

		#cms_blende_m {width: 100%;}

		.cms_sidebar_inhalt {display: none !important;}
		.cms_hauptteil_inhalt {width: 100% !important;}
		.cms_kopfnavigation {display: none !important;}
		#cms_kopfnavigation {display: none !important;}
		#cms_hauptnavigation {display: none !important;}
		#cms_mobilnavigation {display: block !important;}

		.cms_optimierung_P ul.cms_uebersicht a p img,
		.cms_optimierung_T ul.cms_uebersicht a p img,
		.cms_optimierung_H ul.cms_uebersicht a p img {
			width: 100% !important;
			height: auto !important;
			margin-left: 0px !important;
			float: none !important;
		}

		.cms_blockwahl {
			width: 100%;
		}
	}
}
