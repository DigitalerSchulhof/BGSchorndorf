<?php
function cms_texttrafo_e_event($string) {
	$string = str_replace("\"", "”", $string);
	$string = str_replace("'", "’", $string);
	$string = str_replace("\\", "\\\\", $string);
	return $string;
}

function cms_texttrafo_e_db($string) {
	$string = str_replace("<br></p>", "</p>", $string);
	if(cms_boesartig($string) && !cms_r("website.programmcode")) {
		include_once "../../../php/schulhof/seiten/auffaelliges/auswerten.php";
		cms_auffaelliges_speichern(2);
		die("BÖSE");
	}
	return $string;
}

function cms_texttrafo_e_db_ohnetag($string) {
	$string = cms_texttrafo_e_db($string);
	$string = strip_tags($string);
	return $string;
}

function cms_dateischreiben_vorbereiten ($string) {
	$string = str_replace("\\", "\\\\", $string);
	$string = str_replace("\r", "\\r", $string);
	$string = str_replace("\n", "\\n", $string);
	$string = str_replace('"', '\\"', $string);
	$string = str_replace("'", "’", $string);
	return $string;
}

function cms_textaustextfeld_anzeigen($string) {
	$string = str_replace("\r\n", "<br>", $string);
	$string = str_replace("\n\r", "<br>", $string);
	$string = str_replace("\n", "<br>", $string);
	return $string;
}

function cms_array_leserlich($arr, $sep = "<br>", $l = 0) {
  $r = "";
  foreach($arr as $k => $v)
    if(is_array($v)) {
      $r .= str_repeat("  ", $l)."[$k] =>".$sep;
      $r .= cms_array_leserlich($v, $sep, $l+1);
    } else
      $r .= str_repeat("  ", $l)."[$k] => $v".$sep;
  return $r;
}

function cms_boesartig($string) {
	// onevent
	if (preg_match("/ [oO][nN][a-zA-Z]* *=[^\\\\]*/", $string)) {return true;}
	// <script>
	if (preg_match("/<[sS][cC][rR][iI][pP][tT].*>/", $string)) {return true;}
	// data:
	if (preg_match("/=['\"]?data:(application\\/(javascript|octet-stream|zip|x-shockwave-flash)|image\\/(svg\+xml)|text\\/(javascript|x-scriptlet|html)|data\\/(javascript))[;,]/", $string)) {
		return true;
	}
	preg_match_all("/(.[^ ])*[jJ](&.*;)*[aA](&.*;)*[vV](&.*;)*[aA](&.*;)*[sS](&.*;)*[cC](&.*;)*[rR](&.*;)*[iI](&.*;)*[pP](&.*;)*[tT](&.*;)*(:|;[cC][oO][lL][oO][nN])/", $string, $matchjs);
	preg_match_all("/javascript:cms_download\('([-a-zA-Z0-9]+\/)*[\-\_a-zA-Z0-9]{1,244}\.((tar\.gz)|([a-zA-Z0-9]{2,10}))'\)/", $string, $matchdown);

	if (count($matchjs[0]) != count($matchdown[0])) {
		return true;
	}
	return false;
}
?>
