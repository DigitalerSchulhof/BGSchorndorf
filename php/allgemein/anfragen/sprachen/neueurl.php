<?php
include_once("../../allgemein/funktionen/sprachen/sprachen.php");
include_once("../../schulhof/funktionen/check.php");

postLesen("sprache");
postLesen("url");

$check = $url;

if(!in_array($sprache, $CMS_SPRACHEN))
  die("FEHLER");

$urls = s("url", array(), null, false);
$pfad = checkUrl($urls);

$neueurl = u($pfad, array(), $sprache);

echo $neueurl ?? "FEHLER";

function checkUrl($url) {
  global $check;
  if(is_array($url))
    foreach($url as $k => $v) {
      if(!is_array($v))
        if($v == $check)
          return $k;
      $u = checkUrl($v);
      if($u !== false)
        return $k.".".$u;
    }
  else
    if($url == $check)
      return $url;
  return false;
}

?>
