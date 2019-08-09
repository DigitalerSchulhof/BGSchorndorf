<?php

require_once("php/allgemein/funktionen/sprachen/yaml.php");
require_once("php/allgemein/funktionen/sprachen/functions.php");

$CMS_SPRACHEN = array("de-DE", "en-GB");

$CMS_STRINGS = array();

function s($key, $variablen = array(), $sprache = null) {
  global $CMS_STRINGS;
  $sprache = $sprache ?? cms_aktive_sprache();
  $strings = $CMS_STRINGS[$sprache] ?? $CMS_STRINGS[$sprache] = yaml_load_file("sprachen/$sprache.yml");
  $keys = explode(".", $key);
  $value = $strings;
  while($k = array_shift($keys))
    if(isset($value[$k]))
      $value = $value[$k];
    else
      return $key;

  if(!is_string($value))
    return $value;

  foreach ($variablen as $k => $v)
    $value = str_replace($k, strval($v), $value);

  return $value;
}

function cms_aktive_sprache() {
  global $CMS_SPRACHEN;
  $fallback = "de-DE";
  return in_array(($_COOKIE["sprache"] ?? $fallback), $CMS_SPRACHEN) ? ($_COOKIE["sprache"] ?? $fallback) : $fallback;
}

function cms_js_strings() {
  return json_encode(s("javascript"));
}
?>
