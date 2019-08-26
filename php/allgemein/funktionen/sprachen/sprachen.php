<?php

require_once(dirname(__FILE__)."/yaml.php");
require_once(dirname(__FILE__)."/functions.php");

$CMS_SPRACHEN = array("de-DE", "en-GB");

$CMS_STRINGS = array();

function cms_sprache_string($key, $variablen = array(), $sprache = null, $phpprefix = true) {
  global $CMS_STRINGS;
  $sprache = $sprache ?? cms_aktive_sprache();
  $strings = $CMS_STRINGS[$sprache] ?? $CMS_STRINGS[$sprache] = yaml_load_file(dirname(__FILE__)."/../../../../sprachen/$sprache.yml");
  if($phpprefix)
    $key = "php.".$key;
  $keys = explode(".", $key);
  $value = $strings;
  while($k = array_shift($keys))
    if(isset($value[$k]))
      $value = $value[$k];
    else {
      $r = $key;
      foreach($variablen as $k => $v)
        $r .= " [$k => ".htmlentities($v)."]";
      return $r;
    }

  if(!is_string($value))
    return $value;

  foreach ($variablen as $k => $v)
    $value = str_replace($k, strval($v), $value);

  return $value;
}

function s($key, $variablen = array(), $sprache = null, $phpprefix = true) {
  return cms_sprache_string($key, $variablen, $sprache, $phpprefix);
}

function cms_js_strings() {
  return json_encode(cms_sprache_string("javascript", array(), null, false));
}

function cms_sprache_url($url, $variablen = array(), $sprache = null, $urlprefix = true) {
  return cms_sprache_string(($urlprefix?"url.":"").$url, $variablen, $sprache, false);
}

function u($url, $variablen = array(), $sprache = null, $urlprefix = true) {
  return cms_sprache_url($url, $variablen, $sprache, $urlprefix);
}

function cms_aktive_sprache() {
  global $CMS_SPRACHEN;
  $fallback = "de-DE";
  return in_array(($_COOKIE["sprache"] ?? $fallback), $CMS_SPRACHEN) ? ($_COOKIE["sprache"] ?? $fallback) : $fallback;
}

function cms_zeitarray($zeit) {
  $r = array();
  $r["%datum%"]               = date("d.m.Y",   $zeit);

  $r["%tag%"]                 =                         date("d",       $zeit);
  $r["%tagname%"]             = cms_tagname(            date("w",       $zeit));
  $r["%tagnamekomplett%"]     = cms_tagnamekomplett(    date("w",       $zeit));

  $r["%monat%"]               =                         date("m",       $zeit);
  $r["%monatsname%"]          = cms_monatsname(         date("m",       $zeit));
  $r["%monatsnamekomplett%"]  = cms_monatsnamekomplett( date("m",       $zeit));

  $r["%jahr%"]                = date("Y",       $zeit);
  $r["%zeit%"]                = date("H:m",     $zeit);
  return $r;
}
?>
