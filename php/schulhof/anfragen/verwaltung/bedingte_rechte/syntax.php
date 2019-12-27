<?php

function cms_bedingt_bedingung_syntax_pruefen($bedingung) {

  if(preg_replace("/^\s*$/mi", "", $bedingung) == "")
    return false;

  $bedingung = "($bedingung)";

  $bedingung = str_replace("==", " == ", $bedingung);
  $bedingung = str_replace("!=", " != ", $bedingung);
  $bedingung = str_replace("||", " || ", $bedingung);
  $bedingung = str_replace("&&", " && ", $bedingung);
  $bedingung = str_replace(")",  " ) ",  $bedingung);
  $bedingung = str_replace("(",  " ( ",  $bedingung);
  $bedingung = str_replace(">",  " > ",  $bedingung);
  $bedingung = str_replace("<",  " < ",  $bedingung);

  $bedingung = explode(" ", $bedingung);
  $b         = array_diff($bedingung, array(""));
  $bedingung = array();
  foreach($b as $v)   // Lücken im Index auffüllen
    $bedingung[] = $v;

  // Gültigkeit prüfen
  $matches   = preg_grep('/^(?:\(|\)|zeit|(?:nutzer\.(?:id|vorname|nachname|titel|art|imln|hatRolle))|!=|==|&&|\|\||\d+|(?:"[\-0-9a-zäöüßáàâéèêíìîïóòôúùûçøæœå.* ]+")|<|>)$/mi', $bedingung);
  if(count($matches) != count($bedingung))
    return false;

  // Syntax prüfen
  $auf = 0;
  for($i = 0; $i < count($bedingung); $i++) {
    $b = $bedingung[$i];
    if($b == "(") {
      $auf++;
      continue;
    }
    if($b == ")") {
      $auf--;
      continue;
    }
    if($b == "||" || $b == "&&") {
      $m1 = $bedingung[$i-1] ?? null;
      $p1 = $bedingung[$i+1] ?? null;
      if($m1 === null || $p1 === null)
        return false;
      if(in_array($m1, array("&&", "||", "(")))
        return false;
      if(in_array($p1, array("&&", "||", ")")))
        return false;
      continue;
    }
    if($auf < 0)
      return false;

    if(preg_match('/^zeit|(?:nutzer\.(?:id|vorname|nachname|titel|art))$/mi', $b)) {
      $p1 = $bedingung[$i+1] ?? null;
      $p2 = $bedingung[$i+2] ?? null;
      if($p2 === null) {
        return false;  // Nicht genug Argumente
      }

      if(preg_match('/^zeit|(?:nutzer\.(?:id))$/mi', $b)) {
        if(!in_array($p1, array(">", "<", "==", "!="))) {
          return false;  // Falscher bzw. kein Operator
        }
        if(!preg_match('/^\d+$/mi', $p2)) {
          return false;  // Zu Vergleichend nicht numerisch
        }

        $i += 2;
      }

      if(preg_match('/^(?:nutzer\.(?:vorname|nachname|titel))$/mi', $b)) {
        if(!in_array($p1, array("==", "!="))) {
          return false;  // Falscher bzw. kein Operator
        }
        if(!preg_match('/^(?:"[\-a-zäöüßáàâéèêíìîïóòôúùûçøæœå ]+")$/mi', $p2)) {
          return false;  // Zu Vergleichend ungültig
        }

        $i += 2;
      }

      if(preg_match('/^(?:nutzer\.(?:art))$/mi', $b)) {
        if(!in_array($p1, array("==", "!="))) {
          return false;  // Falscher bzw. kein Operator
        }
        if(!in_array($p2, array('"s"', '"l"', '"v"', '"e"', '"x"'))) {
          return false;  // Zu Vergleichend ungültig
        }

        $i += 2;
      }
      continue;
    }

    if(preg_match('/^(?:nutzer\.(?:hatRolle))$/mi', $b)) {
      $p1 = $bedingung[$i+1] ?? null;
      $p2 = $bedingung[$i+2] ?? null;
      $p3 = $bedingung[$i+3] ?? null;
      if($p3 === null) {
        return false;
      }

      if($p1 != "(" || $p3 != ")") {
        return false;
      }

      if($b == "nutzer.hatRolle") {
        if(!preg_match('/(?:(?:"[\-0-9a-zäöüßáàâéèêíìîïóòôúùûçøæœå.* ]+")|\d+)$/mi', $p2)) {
          return false;  // Zu Vergleichend ungültig
        }

        $i += 3;
      }
      continue;
    }

    if(preg_match('/^(?:nutzer\.(?:imln))$/mi', $b)) {
      $m1 = $bedingung[$i-1] ?? null;
      if($m1 === null) {
        return false;
      }

      if(!in_array($m1, array("(", ")", "||", "&&"))) {
        return false;
      }
      continue;
    }

    // Wenn nicht behandelt und nicht übersprungen
    return false;
  }

  if($auf != 0)
    return false;
  return true;
}

?>
