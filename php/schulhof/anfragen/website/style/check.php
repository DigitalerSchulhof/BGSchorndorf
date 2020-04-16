<?php
function cms_stylecheck_alias($text) {
	return preg_match("/^(cms_style[_a-z0-9]*)|-$/", $text);
}

function cms_stylecheck_anzeige($text) {
	return preg_match("/^(inline|block|inline-block|list-item|run-in|inline-table|table|table-caption|table-cell|table-column|table-columns-group|table-footer-group|table-header-group|table-row|table-row-group|flex|none|inherit){1}$/", $text);
}

function cms_stylecheck_positionierung($text) {
	return preg_match("/^(inherit|static|relative|absolute|fixed){1}$/", $text);
}

function cms_stylecheck_dicke($text) {
	return preg_match("/^(inherit|normal|bold|bolder|lighter){1}$/", $text);
}

function cms_stylecheck_linie($text) {
	return preg_match("/^(([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}( (dotted|dashed|solid|double|groove|ridge|inset|outset|inherit){1})?)|none|inherit){1}( !important)?$/", $text);
}

function cms_stylecheck_text($text) {
	return preg_match("/^('[ -_a-zA-Z0-9]+'){1}( !important)?$/", $text);
}

function cms_stylecheck_schattenmass($text) {
	return preg_match("/^(([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}( [0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}){2})|none|inherit|auto){1}$/", $text);
}

function cms_stylecheck_mass($text) {
	return preg_match("/^(([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1})|(auto|inherit|none)){1}( !important)?$/", $text);
}

function cms_stylecheck_abstand($text) {
	return preg_match("/^([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}( [0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}){0,3}){1}( !important)?$/", $text);
}

function cms_stylecheck_alpha($text) {
	return cms_check_ganzzahl($text,0,100);
}

function cms_stylecheck_farbe($text) {
	return preg_match("/^#[a-fA-F0-9]{6}$/", $text);
}
?>
