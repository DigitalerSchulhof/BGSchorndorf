<?php

function cms_bedingt_bedingung_syntax_baum($bedingung) {

  if(preg_replace("/^\s*$/mi", "", $bedingung) == "")
    return false;

  $bedingung = "($bedingung)";

  $tokens = array();
  $bed = str_split($bedingung);

  $objekt = "";
  $machtObjekt = true;

  //                            +1 um letztes Feld zu vollenden
  for($i = 0; $i < count($bed)+1; $i++) {
    $b  = $bed[$i]   ?? null;
    $p1 = $bed[$i+1] ?? null;
    $p2 = $bed[$i+2] ?? null;
    $p3 = $bed[$i+3] ?? null;
    $p4 = $bed[$i+4] ?? null;
    $p5 = $bed[$i+5] ?? null;
    $p6 = $bed[$i+6] ?? null;

    if(!$machtObjekt) {
      if(strlen($objekt)) {
        $letzte = $tokens[count($tokens)-1];
        $typ = "Feld";
        if(preg_match("/\\d+/", $objekt)) {
          $typ = "Zahl";
        }
        $tokens[count($tokens)-1] = array(
          "typ" => $typ,
          "wert" => $objekt
        );
        $tokens[] = $letzte;
      }
      $objekt = "";
    }

    $machtObjekt = false;

    if($b == " ") {
      continue;
    }

    if($b == "(") {
      $tokens[] = array(
        "typ" => "Klammer",
        "wert" => "("
      );
      continue;
    }

    if($b == ")") {
      $tokens[] = array(
        "typ" => "Klammer",
        "wert" => ")"
      );
      continue;
    }

    if($b == "[") {
      $tokens[] = array(
        "typ" => "Klammer",
        "wert" => "["
      );
      continue;
    }

    if($b == "]") {
      $tokens[] = array(
        "typ" => "Klammer",
        "wert" => "]"
      );
      continue;
    }

    if($b == "!") {
      if($p1 === "=") {
        $tokens[] = array(
          "typ" => "Vergleichsoperator",
          "wert" => "!="
        );
        $i++;
        continue;
      }
      $tokens[] = array(
        "typ" => "LogischerOperator",
        "wert" => "!"
      );
      continue;
    }

    if($b == "=") {
      if($p1 === "=") {
        $tokens[] = array(
          "typ" => "Vergleichsoperator",
          "wert" => "=="
        );
        $i++;
        continue;
      }
      $tokens[] = array(
        "typ" => "Zuweisung",
        "wert" => "="
      );
      continue;
    }

    if($b == "|") {
      if($p1 == "|") {
        $tokens[] = array(
          "typ" => "LogischerOperator",
          "wert" => "||"
        );
        $i++;
        continue;
      }
      $tokens[] = array(
        "typ" => "LogischerOperator",
        "wert" => "|"
      );
    }

    if($b == "&") {
      if($p1 == "&") {
        $tokens[] = array(
          "typ" => "LogischerOperator",
          "wert" => "&&"
        );
        $i++;
        continue;
      }
      $tokens[] = array(
        "typ" => "LogischerOperator",
        "wert" => "&"
      );
    }

    if($b == "<") {
      $tokens[] = array(
        "typ" => "Vergleichsoperator",
        "wert" => "<"
      );
      continue;
    }

    if($b == ">") {
      $tokens[] = array(
        "typ" => "Vergleichsoperator",
        "wert" => ">"
      );
      continue;
    }

    if($b == ",") {
      $tokens[] = array(
        "typ" => "Komma",
        "wert" => ","
      );
      continue;
    }

    if($b == "\"") {
      $string = "";
      $b = $bed[++$i];

      while($b != "\"") {
        $string .= $b;
        $b = $bed[++$i];
      }

      $tokens[] = array(
        "typ" => "String",
        "wert" => $string
      );
      continue;
    }

    $objekt .= $b;
    $machtObjekt = true;
  }

  $c = 0;

  $maxDurchgaenge = 100;

  while(count($tokens) > 1 && ++$c < $maxDurchgaenge+1) {
    $tokens = array_values($tokens);
    for($i = 0; $i < count($tokens); $i++) {
      $t  = $tokens[$i]["typ"];
      $w  = $tokens[$i]["wert"];
      $t1 = $tokens[$i+1]["typ"] ?? null;
      $t2 = $tokens[$i+2]["typ"] ?? null;


      if(in_array($t, array("Feld", "Zahl", "String"))) {
        if($t1 === "Vergleichsoperator") {
          if(in_array($t2, array("Feld", "Zahl", "String"))) {
            $tokens[$i] = array(
              "typ"   => "Vergleich",
              "wert"  => $tokens[$i+1]["wert"],
              "werte" => array(
                $tokens[$i],
                $tokens[$i+2]
              )
            );
            unset($tokens[++$i]);
            unset($tokens[++$i]);
            break;
          }
        }

        if($t1 === "Klammer" && $tokens[$i+1]["wert"] === "[") {
          $argumente = array();
          for($ic = 2; $ic < count($tokens)-$i; $ic++) {
            $ti = $tokens[$i+$ic]["typ"]  ?? null;
            $wi = $tokens[$i+$ic]["wert"] ?? null;

            if($ti === "Klammer" && $wi === "]") {
              $tokens[$i] = array(
                "typ"   => "Funktionsaufruf",
                "wert"  => $w,
                "werte" => $argumente
              );
              for($r = 1; $r < (count($argumente)*2)+2; $r++) {
                unset($tokens[$i+$r]);
              }
              $i+=(count($argumente)*2)+1;
              break 2;
            } else {
              if(in_array($ti, array("Vergleich", "Logisch", "EndFeld", "EndZahl", "EndString", "Funktionsaufruf")) &&
                (
                  ($tokens[$i+$ic-1]["typ"] == "Komma" && $tokens[$i+$ic-1]["wert"] == ",")
                ||
                  ($tokens[$i+$ic-1]["typ"] == "Klammer" && $tokens[$i+$ic-1]["wert"] == "[")
                )
              ) {
                $argumente[] = $tokens[$i+$ic];
              } else {
                if($ti !== "Komma" && $wi !== ",") {
                  break;
                }
              }
            }
          }
        } else {
          $tokens[$i] = array(
            "typ" => "End$t",
            "wert" => $w
          );
        }
      }

      if($t === "LogischerOperator" && $w == "!") {
        if(in_array($t1, array("Vergleich", "Logisch", "EndFeld", "EndZahl", "EndString", "Funktionsaufruf"))) {
          $tokens[$i] = array(
            "typ"   => "Logisch",
            "wert"  => "!",
            "werte" => array(
              $tokens[$i+1],
            )
          );
          unset($tokens[$i+1]);
          break;
        }
      }

      if(in_array($t, array("Vergleich", "Logisch", "EndFeld", "EndZahl", "EndString", "Funktionsaufruf"))) {
        if($t1 === "LogischerOperator") {
          if(in_array($t2, array("Vergleich", "Logisch", "EndFeld", "EndZahl", "EndString", "Funktionsaufruf"))) {
            $tokens[$i] = array(
              "typ"   => "Logisch",
              "wert"  => $tokens[$i+1]["wert"],
              "werte" => array(
                $tokens[$i],
                $tokens[$i+2]
              )
            );
            unset($tokens[++$i]);
            unset($tokens[++$i]);
            break;
          }
        }
      }

      if($t == "Klammer" && $w == "(") {
        if($t2 == "Klammer" && ($tokens[$i+2]??null)["wert"] == ")") {
          $tokens[$i] = $tokens[$i+1];
          unset($tokens[++$i]);
          unset($tokens[++$i]);

          break;
        }
      }
    }
  }
  if(count($tokens) == 1 &&
     in_array($tokens[0]["typ"]??null, array("Vergleich", "Logisch", "EndFeld", "EndZahl", "EndString", "Funktionsaufruf"))) {
    return $tokens;
  }
  return false;
}

function cms_bedingt_bedingung_syntax_pruefen($bedingung) {
  return cms_bedingt_bedingung_syntax_baum($bedingung) !== false;
}

?>
