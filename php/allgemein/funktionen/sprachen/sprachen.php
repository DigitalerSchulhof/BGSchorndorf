<?php

require_once("php/allgemein/funktionen/sprachen/yaml.php");
require_once("php/allgemein/funktionen/sprachen/functions.php");

$CMS_STRINGS = yaml_load_file("sprachen/de_DE.yml");

function s($key, $variablen = array()) {
  global $CMS_STRINGS;
  $keys = explode(".", $key);
  $value = $CMS_STRINGS;
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
?>
