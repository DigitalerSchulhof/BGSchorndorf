var cms_vplanschiebengeschwindigkeit = 50;
var cms_vplanschiebenwarten = 3000;
var cms_vplanschieberichtung_lh = 1;
var cms_vplanschieberichtung_lg = 1;
var cms_vplanschieberichtung_lm = 1;
var cms_vplanschieberichtung_sh = 1;
var cms_vplanschieberichtung_sm = 1;
var cms_vplanschiebeposition_lh = null;
var cms_vplanschiebeposition_lg = null;
var cms_vplanschiebeposition_lm = null;
var cms_vplanschiebeposition_sh = null;
var cms_vplanschiebeposition_sm = null;
var CMS_INTERN_VPLANL_SCHIEBEN_LH;
var CMS_INTERN_VPLANL_SCHIEBEN_LG;
var CMS_INTERN_VPLANL_SCHIEBEN_LM;
var CMS_INTERN_VPLANL_SCHIEBEN_SH;
var CMS_INTERN_VPLANL_SCHIEBEN_SM;

function cms_vplan_verschieben_lh() {
	var fenster_lh = document.getElementById('cms_lvplan_heute');
	if (fenster_lh) {
		fenster_lh.scrollBy(0,1*cms_vplanschieberichtung_lh);
		if (cms_vplanschiebeposition_lh == fenster_lh.scrollTop) {
			cms_vplanschieberichtung_lh = cms_vplanschieberichtung_lh * -1;
			clearInterval(CMS_INTERN_VPLANL_SCHIEBEN_LH);
			window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_LH = window.setInterval(function () {cms_vplan_verschieben_lh()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);
		}
		cms_vplanschiebeposition_lh = fenster_lh.scrollTop;
	}
}

function cms_vplan_verschieben_lg() {
	var fenster_lg = document.getElementById('cms_lvplan_geraete');
	if (fenster_lg) {
		fenster_lg.scrollBy(0,1*cms_vplanschieberichtung_lg);
		if (cms_vplanschiebeposition_lg == fenster_lg.scrollTop) {
			cms_vplanschieberichtung_lg = cms_vplanschieberichtung_lg * -1;
			clearInterval(CMS_INTERN_VPLANL_SCHIEBEN_LG);
			window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_LG = window.setInterval(function () {cms_vplan_verschieben_lg()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);
		}
		cms_vplanschiebeposition_lg = fenster_lg.scrollTop;
	}
}

function cms_vplan_verschieben_lm() {
	var fenster_lm = document.getElementById('cms_lvplan_morgen');
	if (fenster_lm) {
		fenster_lm.scrollBy(0,1*cms_vplanschieberichtung_lm);
		if (cms_vplanschiebeposition_lm == fenster_lm.scrollTop) {
			cms_vplanschieberichtung_lm = cms_vplanschieberichtung_lm * -1;
			clearInterval(CMS_INTERN_VPLANL_SCHIEBEN_LM);
			window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_LM = window.setInterval(function () {cms_vplan_verschieben_lm()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);
		}
		cms_vplanschiebeposition_lm = fenster_lm.scrollTop;
	}
}

function cms_vplan_verschieben_sh() {
	var fenster_sh = document.getElementById('cms_svplan_heute');
	if (fenster_sh) {
		fenster_sh.scrollBy(0,1*cms_vplanschieberichtung_sh);
		if (cms_vplanschiebeposition_sh == fenster_sh.scrollTop) {
			cms_vplanschieberichtung_sh = cms_vplanschieberichtung_sh * -1;
			clearInterval(CMS_INTERN_VPLANL_SCHIEBEN_SH);
			window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_SH = window.setInterval(function () {cms_vplan_verschieben_sh()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);
		}
		cms_vplanschiebeposition_sh = fenster_sh.scrollTop;
	}
}

function cms_vplan_verschieben_sm() {
	var fenster_sm = document.getElementById('cms_svplan_morgen');
	if (fenster_sm) {
		fenster_sm.scrollBy(0,1*cms_vplanschieberichtung_sm);
		if (cms_vplanschiebeposition_sm == fenster_sm.scrollTop) {
			cms_vplanschieberichtung_sm = cms_vplanschieberichtung_sm * -1;
			clearInterval(CMS_INTERN_VPLANL_SCHIEBEN_SM);
			window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_SM = window.setInterval(function () {cms_vplan_verschieben_sm()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);
		}
		cms_vplanschiebeposition_sm = fenster_sm.scrollTop;
	}
}
