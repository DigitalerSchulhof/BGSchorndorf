// HELL;

.cms_ladeicon {
	display: inline-block;
	position: relative;
	width: 64px;
	height: 11px;
}

.cms_ladeicon div {
	position: absolute;
	width: 11px;
	height: 11px;
	border-radius: 50%;
	background: @h_haupt_abstufung2;
	animation-timing-function: cubic-bezier(0, 1, 1, 0);
}

.cms_ladeicon div:nth-child(1) {
	left: 0px;
	animation: cms_ladeicon1 0.6s infinite;
}

.cms_ladeicon div:nth-child(2) {
	left: 0px;
	animation: cms_ladeicon2 0.6s infinite;
}

.cms_ladeicon div:nth-child(3) {
	left: 20px;
	animation: cms_ladeicon2 0.6s infinite;
}

.cms_ladeicon div:nth-child(4) {
	left: 39px;
	animation: cms_ladeicon3 0.6s infinite;
}

@keyframes cms_ladeicon1 {
	0% {transform: scale(0);}
	100% {transform: scale(1);}
}

@keyframes cms_ladeicon3 {
	0% {transform: scale(1);}
	100% {transform: scale(0);}
}

@keyframes cms_ladeicon2 {
	0% {transform: translate(0, 0);}
	100% {transform: translate(19px, 0);}
}

// DUNKEL;

.cms_ladeicon div {
	background: @d_haupt_abstufung2;
}
