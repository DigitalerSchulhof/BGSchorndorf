// HELL;

.cms_neuigkeiten {
	padding: 0px;
	margin-bottom: @haupt_absatzschulhof;
	display: flex;
	flex-wrap: wrap;
}

.cms_neuigkeit {
	width: 25%;
	padding: 10px;
	list-style-type: none;
	margin: 0px;
	background: @h_haupt_abstufung1;
	display: inline-block;
	transition: 250ms ease-in-out;
	position: relative;
  border-radius: 2px;
  box-shadow: 0 0 1em rgba(0,0,0,.08)!important
}

.cms_neuigkeit a {color: @h_neuigkeit_schrift;}
.cms_neuigkeit a:hover {color: @h_neuigkeit_schrifthover;}

.cms_neuigkeit_notfall {
	text-align: left !important;
	width: 100%;
	padding: 10px;
	margin: 0px;
	background: @h_haupt_meldungwarnunghinter;
	transition: 250ms ease-in-out;
	display: block;
	position: relative;
	animation-name: notfallanimationhell;
	animation-delay: 1s;
	animation-duration: 4s;
	animation-iteration-count: infinite;
}

@keyframes notfallanimationhell {
	0%   {background-color:@h_haupt_meldungwarnunghinter;}
	37.5%  {background-color:@h_haupt_meldungfehlerhinter;}
	75%  {background-color:@h_haupt_meldungwarnunghinter;}
	100%  {background-color:@h_haupt_meldungwarnunghinter;}
}

.cms_neuigkeit_ln {
	background: @h_haupt_hintergrund !important;
	padding: 8px !important;
	border: 2px dashed @h_haupt_abstufung1;
}

.cms_neuigkeit_ln h4, .cms_neuigkeit_ln p {
	color: @h_haupt_abstufung2;
}

.cms_neuigkeit_ganz:hover {
	cursor: pointer;
}

.cms_optimierung_H .cms_neuigkeit {
	width: 50% !important;
}

.cms_neuigkeit:hover {
	background: @h_haupt_abstufung2;
}

.cms_neuigkeit_icon {
	position: absolute;
	top: 10px;
	left: 10px;
}

.cms_neuigkeit_inhalt {
	display: block;
	margin-left: 42px;
}

.cms_neuigkeit_inhalt p {
	overflow: hidden;
	text-overflow: ellipsis;
}

.cms_neuigkeit_inhalt h4 {
	margin-top: 0px;
}

.cms_neuigkeit_inhalt .cms_neuigkeit_vorschau {
	font-size: 70%;
	margin-bottom: 0px;
}

.cms_neuigkeit_schliessen, .cms_neuigkeit_oeffnen {
	position: absolute;
	bottom: 8px;
	display: none;
	line-height: 1;
}

.cms_neuigkeit_schliessen {
	left: 10px;
}

.cms_neuigkeit_oeffnen {
	right: 10px;
}

.cms_neuigkeit:hover .cms_neuigkeit_schliessen,
.cms_neuigkeit:hover .cms_neuigkeit_oeffnen {
	display: block;
}

// DUNKEL;

.cms_neuigkeit {
	background: @d_haupt_abstufung1;
}

.cms_neuigkeit a {color: @d_neuigkeit_schrift;}
.cms_neuigkeit a:hover {color: @d_neuigkeit_schrifthover;}

.cms_neuigkeit_notfall {
	background: @d_haupt_meldungwarnunghinter;
	animation-name: notfallanimationdunkel;
}

@keyframes notfallanimationdunkel {
	0%   {background-color:@d_haupt_meldungwarnunghinter;}
	37.5%  {background-color:@d_haupt_meldungfehlerhinter;}
	75%  {background-color:@d_haupt_meldungwarnunghinter;}
	100%  {background-color:@d_haupt_meldungwarnunghinter;}
}

.cms_neuigkeit_ln {
	background: @d_haupt_hintergrund !important;
	border: 2px dashed @d_haupt_abstufung1;
}

.cms_neuigkeit_ln h4, .cms_neuigkeit_ln p {
	color: @d_haupt_abstufung2;
}

.cms_neuigkeit:hover {
	background: @d_haupt_abstufung2;
}
