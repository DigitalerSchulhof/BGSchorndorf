<?php
  include_once(dirname(__FILE__)."/../yaml.php");
  include_once(dirname(__FILE__)."/../../../schulhof/anfragen/verwaltung/bedingte_rechte/syntax.php");
  use Async\YAML;

  define("RECHTEPRUEFEN", true);  // Prüfen, on angegebene Rechte existieren und ggf warnen

  $cms_nutzerrechte           = array();  // Wichtig: im Array sind nur Rechte, die der Nutzer hat - d.h. Am Ende jedes "Pfads" im Array steht "true"
  $cms_bedingte_nutzerrechte  = array();
  $cms_bedingte_rollenrechte  = array();

  $cms_allerechte = array();

  function cms_rechte_laden_sql($sql, &$rueckgabe, $parameterTypen, ...$parameter) { // Rechte aus der Datenbank laden - Personen oder Rollen
    $dbs = cms_verbinden();

    $sql = $dbs->prepare($sql);
    if($parameterTypen) {
      $sql->bind_param($parameterTypen, ...$parameter);
    }

    $sql->bind_result($recht);
    $sql->execute();

    while($sql->fetch()) {
      $pfad = explode(".", $recht);

      if($rueckgabe === true) // *-Recht vergeben, weitere Checks nicht nötig
        break;

      $aktuellerPfad = &$rueckgabe;

      foreach($pfad as $pfadTiefe => $pfadWert) {
        if(++$pfadTiefe == count($pfad)) {
          $pfadWert == "*" ? ($aktuellerPfad = true) : ($aktuellerPfad[$pfadWert] = true);
          break;
        } else {
          if(@$aktuellerPfad[$pfadWert] === true) // Schon gesetzt, »@« um nicht isset Check
            break;
          $aktuellerPfad[$pfadWert] = $aktuellerPfad[$pfadWert] ?? array();
        }
        $aktuellerPfad = &$aktuellerPfad[$pfadWert];
      }
    }
    $sql->close();
  }

  function cms_rechte_laden_nutzer($person = '-', &$arr = null) { // $arr: Zu befüllendes Array
    global $CMS_SCHLUESSEL, $cms_nutzerrechte;
    $dbs = cms_verbinden("s");

    if(is_null($arr)) // Fallback
      $arr = &$cms_nutzerrechte;

  	if ($person === '-')
      $person = $_SESSION['BENUTZERID'] ?? "-";

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rechtezuordnung WHERE person = ?";
    cms_rechte_laden_sql($sql, $arr, "i", $person);
  }

  function cms_rechte_laden_rollen($person = '-', &$arr = null) { // $arr: Zu befüllendes Array
    global $CMS_SCHLUESSEL, $cms_nutzerrechte;
    $dbs = cms_verbinden("s");

    if(is_null($arr))
      $arr = &$cms_nutzerrechte;

    if ($person === '-')
      $person = $_SESSION['BENUTZERID'] ?? "-";

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte JOIN rollenzuordnung ON rollenrechte.rolle = rollenzuordnung.rolle WHERE rollenzuordnung.person = ?";
    cms_rechte_laden_sql($sql, $arr, "i", $person);
  }

  function cms_allerechte_laden() {
    global $cms_allerechte;

    if(!RECHTEPRUEFEN)  // Nur ausführen, um zu prüfen, ob Recht existiert
      return;

    $rechte = YAML::loader(dirname(__FILE__)."/rechte.yml");

    $rek = function($array, $pfad) use (&$cms_allerechte, &$rek) {
      foreach($array as $k => $v) {
        if(is_array($v)) {
          $rek($v, "$pfad.$k");
        } else {
          $cms_allerechte[] = substcms_r("$pfad.$k"), 1);
        }
      }
    };

    $rek($rechte, "");
  }

  function cms_hat_recht($rechteCode) {
    global $CMS_SCHLUESSEL, $cms_nutzerrechte, $cms_bedingte_nutzerrechte, $cms_bedingte_rollenrechte, $cms_allerechte;

    $rc = str_split($rechteCode);

    $aktuellesFeld = "";
    $machtFeld     = true;
    $tokens = array();
    for($i = 0; $i < count($rc)+1; $i++) {
      $r  = $rc[$i]   ?? null;
      $p1 = $rc[$i+1] ?? null;

      if(preg_match("/^\\s+$/mi", $r)) {
        continue;
      }

      if(!$machtFeld || $i == count($rc)) {
        if(strlen($aktuellesFeld)) {
          $feld = array(
            "typ" => "Feld",
            "wert" => $aktuellesFeld
          );

          $aktuellesFeld = str_replace("%GRUPPEN%", "[|arbeitsgemeinschaften,arbeitskreise,ereignisse,fachschaften,fahrten,gremien,klassen,kurse,sonstigegruppen,stufen,wettbewerbe]", $aktuellesFeld);
          $aktuellesFeld = str_replace("%ELEMENTE%", "[|faq,editor,download,kontaktformular,boxen,eventübersicht,newsletter]", $aktuellesFeld);
          $aktuellesFeld = str_replace("%ARTIKELSTUFEN%", "[|öffentlich,schulhof,lehrer,lehrerundverwaltung]", $aktuellesFeld);

          $aktuellesFeld = mb_strtolower($aktuellesFeld);

          if(!$machtFeld) {
            $letzte = $tokens[count($tokens)-1];
            $tokens[count($tokens)-1] = $feld;
            $tokens[] = $letzte;
          } else {
            $tokens[] = $feld;
          }
        }
        $aktuellesFeld = "";
      }

      $machtFeld = false;

      if($r === "|") {
        if($p1 === "|") {
          $tokens[] = array(
            "typ"   => "LogischerOperator",
            "wert"  => "||"
          );
          $i++;
          continue;
        }
        $tokens[] = array(
          "typ"   => "ListenOperator",
          "wert"  => "|"
        );
        continue;
      }

      if($r === "&") {
        if($p1 === "&") {
          $tokens[] = array(
            "typ"   => "LogischerOperator",
            "wert"  => "&&"
          );
          $i++;
          continue;
        }
        // $tokens[] = array(
        //   "typ"   => "ListenOperator",
        //   "wert"  => "&"
        // );
        continue;
      }

      if($r === ".") {
        $tokens[] = array(
          "typ"   => "Punkt",
          "wert"  => "."
        );
        continue;
      }

      if($r === ",") {
        $tokens[] = array(
          "typ"   => "Punkt",
          "wert"  => ","
        );
        continue;
      }

      if(in_array($r, array("(", ")", "[", "]"))) {
        $tokens[] = array(
          "typ"   => "Klammer",
          "wert"  => $r
        );
        continue;
      }

      $aktuellesFeld .= $r;
      $machtFeld      = true;
    }

    $tokens_durchgehen = function($tokens) {
      for($i = 0; $i < count($tokens)+1; $i++) {
        $t  = $tokens[$i]["typ"]    ?? null;
        $w  = $tokens[$i]["wert"]   ?? null;
        if($t === null) {
          break;
        }
        $t1 = $tokens[$i+1]["typ"]  ?? null;
        $w1 = $tokens[$i+1]["wert"] ?? null;
        $t2 = $tokens[$i+2]["typ"]  ?? null;
        $w2 = $tokens[$i+2]["wert"] ?? null;
        $mt1 = $tokens[$i-1]["typ"] ?? null;
        $mw1 = $tokens[$i-1]["wert"]?? null;

        if($t === "Klammer") {
          if($w === "[") {
            if($t1 === "ListenOperator") {
              $op = $w1;
              $werte = array();
              $li = 2;
              $ende   = false;
              $fehler = false;
              do {
                $p1  = $tokens[$i+$li]              ?? null;
                $pt1 = $tokens[$i+$li]["typ"]       ?? null;
                $pw1 = $tokens[$i+($li++)]["wert"]  ?? null;
                $p2  = $tokens[$i+$li]              ?? null;
                $pt2 = $tokens[$i+$li]["typ"]       ?? null;
                $pw2 = $tokens[$i+($li++)]["wert"]  ?? null;
                $ende = true;

                if(in_array($pt1, array("Feld", "FelderListe"))) {
                  if($pt2 === "Punkt" && $pw2 === ",") {
                    $werte[] = $p1;
                    $ende = false;
                  } else if($pt2 === "Klammer" && $pw2 === "]") {
                    $werte[] = $p1;
                    $ende = true;
                  } else {
                    $fehler = true;
                  }
                } else {
                  $fehler = true;
                }
              } while(!$ende);

              if(!$fehler) {
                $tokens[$i] = array(
                  "typ"   => "FelderListe",
                  "wert"  => $op,
                  "werte" => $werte
                );
                for($u = 1; $u <= count($werte)*2+1; $u++) {
                  unset($tokens[$i+$u]);
                }
                $tokens = array_values($tokens);
                continue;
              }
            }
          }
        }

        if(in_array($t, array("Feld", "FelderListe"))) {
          if($t1 === "Punkt" && $w1 === ".") {
            if(in_array($t2, array("Feld", "FelderListe"))) {
              $tokens[$i] = array(
                "typ"   => "Felder",
                "werte" => array(
                  $tokens[$i],
                  $tokens[$i+2]
                )
              );

              for($u = 1; $u <= 2; $u++) {
                unset($tokens[$i+$u]);
              }
              $tokens = array_values($tokens);
              continue;
            }
          }
        }

        if($t === "Felder") {
          if($t1 === "Punkt" && $w1 === ".") {
            if(in_array($t2, array("Feld", "FelderListe"))) {
              $tokens[$i]["werte"][] = $tokens[$i+2];
              for($u = 1; $u <= 2; $u++) {
                unset($tokens[$i+$u]);
              }
              $tokens = array_values($tokens);
              continue;
            }
            if($t2 === "Felder") {

              $tokens[$i]["werte"] = array_merge($tokens[$i]["werte"], $tokens[$i+2]["werte"]);
              for($u = 1; $u <= 2; $u++) {
                unset($tokens[$i+$u]);
              }
              $tokens = array_values($tokens);
              $t  = $tokens[$i]["typ"]    ?? null;
              $w  = $tokens[$i]["wert"]   ?? null;
              if($t === null) {
                break;
              }
              $t1 = $tokens[$i+1]["typ"]  ?? null;
              $w1 = $tokens[$i+1]["wert"] ?? null;
              $t2 = $tokens[$i+2]["typ"]  ?? null;
              $w2 = $tokens[$i+2]["wert"] ?? null;
            }
          }
        }

        if(in_array($t, array("Felder", "Logisch"))) {
          if($t1 === "LogischerOperator") {
            if(in_array($t2, array("Felder", "Logisch"))) {
              $tokens[$i] = array(
                "typ"   => "Logisch",
                "wert"  => $w1,
                "werte" => array(
                  $tokens[$i],
                  $tokens[$i+2]
                )
              );

              for($u = 1; $u <= 2; $u++) {
                unset($tokens[$i+$u]);
              }
              $tokens = array_values($tokens);
              continue;
            }
          }
        }

        if(in_array($t, array("Feld", "FelderListe"))) {
          if(in_array($mt1, array("LogischerOperator", null)) && in_array($t1, array("LogischerOperator", null))) {
            $tokens[$i] = array(
              "typ"   => "Felder",
              "werte" => array(
                $tokens[$i]
              )
            );
          }
        }
      }

      $tokens = array_values($tokens);
      return $tokens;
    };

    $c = 0;
    while(count($tokens) > 1 && ++$c<100) {
      $tokens = $tokens_durchgehen($tokens);
    }

    $eval = function($tokens, ...$argumente) use (&$eval, $cms_nutzerrechte){
      $tokens = $tokens[0]      ?? $tokens;
      $typ = $tokens["typ"]     ?? null;
      $wert = $tokens["wert"]   ?? null;
      $werte = $tokens["werte"] ?? null;
      if($typ === "Logisch") {
        $w0 = $werte[0];
        $w1 = $werte[1];
        if($wert === "||") {
          return $eval($w0) || $eval($w1);
        }
        if($wert === "&&") {
          return $eval($w0) && $eval($w1);
        }
        return null;
      }

      if($typ === "Felder") {
        $pfad = array($cms_nutzerrechte);
        foreach($werte as $wert) {
          $e = $eval($wert, ...$pfad);
          if($e === false || $e === true) {
            return $e;
          } else {
            $pfad = $e;
          }
        }
      }

      if($typ === "Feld") {
        if(!count($argumente)) {
          return false;
        }
        $r = array();
        foreach($argumente as $arg) {
          if($arg === true) {
            return true;
          }
          if($wert === "*") {
            if(count($arg)) {
              return true;
            }
          }
          if(isset($arg[$wert])) {
            if($arg[$wert] === true) {
              return true;
            }
            $r[] = $arg[$wert];
          }
        }
        return $r;
      }

      if($typ === "FelderListe") {
        $r = array();
        foreach($werte as $w) {
          $e = $eval($w, ...$argumente);
          // if($wert === "&") {
          //   if($e === false) {
          //     return false;
          //   }
          // }
          if($wert === "|") {
            if($e === true) {
              return true;
            }
          }
          if(is_array($e)) {
            $r[] = $e[0]??array();
          }
        }
        if(!count($r)) {
          return $wert !== "&";
        }
        return $r;
      }
    };

    return $eval($tokens);
  }

  function r() {  // Alias :)
    return call_user_func("cms_hat_recht", ...func_get_args()) ?? false;
  }

  function cms_rechte_laden_bedingte_rechte() {
    global $CMS_SCHLUESSEL, $cms_bedingte_nutzerrechte;
    $zeit             = time();
    $nutzer_id        = $_SESSION["BENUTZERID"];
    $nutzer_vorname   = $_SESSION["BENUTZERVORNAME"];
    $nutzer_nachname  = $_SESSION["BENUTZERNACHNAME"];
    $nutzer_titel     = $_SESSION["BENUTZERTITEL"];
    $nutzer_art       = $_SESSION["BENUTZERART"];
    $nutzer_imln      = $_SESSION["IMLN"] ?? "0";

    $werte = array(
      "zeit"      => $zeit,
      "nutzer.id" => $nutzer_id,
      "nutzer.vorname" => $nutzer_vorname,
      "nutzer.nachname" => $nutzer_nachname,
      "nutzer.titel" => $nutzer_titel,
      "nutzer.art" => $nutzer_art,
      "nutzer.imln" => $nutzer_imln
    );

    $bedingungen = array();

    $dbs = cms_verbinden("s");

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL'), AES_DECRYPT(bedingung, '$CMS_SCHLUESSEL') FROM bedingterechte";
    $sql = $dbs->prepare($sql);
    $sql->bind_result($recht, $bed);
    $sql->execute();
    while($sql->fetch()) {
      $b = $bedingungen[$recht] ?? "";
      $b = "$b || ($bed)";
      $bedingungen[$recht] = $b;
    }

    foreach($bedingungen as $r => $b) {
      $bedingungen[$r] = substr($b, 4);
    }

    $evaluieren = function($bedingung) use(&$evaluieren, $werte, $nutzer_id, $CMS_SCHLUESSEL) {
      $bedingung = $bedingung[0] ?? $bedingung;

      $typ  = $bedingung["typ"];
      $wert = $bedingung["wert"];
      if($typ === "Logisch") {
        $w0 = $bedingung["werte"][0] ?? null;
        $w1 = $bedingung["werte"][1] ?? null;

        if($wert == "&&") {
          return $evaluieren($w0) && $evaluieren($w1);
        }
        if($wert == "||") {
          return $evaluieren($w0) || $evaluieren($w1);
        }
        if($wert == "!") {
          return !($evaluieren($w0));
        }
      }

      if($typ === "Vergleich") {
        $w0 = $bedingung["werte"][0] ?? null;
        $w1 = $bedingung["werte"][1] ?? null;

        if($wert == "==") {
          return $evaluieren($w0) == $evaluieren($w1);
        }
        if($wert == "!=") {
          return $evaluieren($w0) != $evaluieren($w1);
        }
        if($wert == "<") {
          return $evaluieren($w0) < $evaluieren($w1);
        }
        if($wert == ">") {
          return $evaluieren($w0) > $evaluieren($w1);
        }
      }

      if($typ === "EndZahl" || $typ === "Zahl") {
        return (int) $wert;
      }

      if($typ === "EndString" || $typ === "String") {
        return "$wert";
      }

      if($typ === "EndFeld" || $typ === "Feld") {
        return $werte[$wert] ?? null;
      }

      if($typ == "Funktionsaufruf") {
        if($wert == "nutzer.hatRolle") {
          $rolle = $evaluieren($bedingung["werte"][0]);

          $dbs = cms_verbinden("s");

          $sql = "SELECT person FROM rollenzuordnung JOIN rollen ON rollenzuordnung.rolle = rollen.id WHERE AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') = ? AND person = ?;";
          $sql = $dbs->prepare($sql);
          $sql->bind_param("si", $rolle, $nutzer_id);
          $sql->bind_result($pers);

          if($sql->execute() && $sql->fetch() && $pers == $nutzer_id) {
            return true;
          } else {
            return false;
          }
        }
        if($wert == "nutzer.hatRecht") {
          $recht = $evaluieren($bedingung["werte"][0]);

          return cms_hat_recht($recht);
        }
      }

      return null;
    };

    foreach($bedingungen as $recht => $bedingung) {
      $bedingung_baum = cms_bedingt_bedingung_syntax_baum($bedingung);

      if($bedingung_baum === false) {
        throw new Exception("Bei den Bedingungen »{$bedingung}« für das Recht »{$recht}« ist ein Fehler aufgetreten!");
        return;
      }

      if($evaluieren($bedingung_baum)) {
        // Nicht von oben geklaut 😈

        $pfad = explode(".", $recht);

        if($cms_bedingte_nutzerrechte === true) // *-Recht vergeben, weitere Checks nicht nötig
          break;

        $aktuellerPfad = &$cms_bedingte_nutzerrechte;

        foreach($pfad as $pfadTiefe => $pfadWert) {
          if(++$pfadTiefe == count($pfad)) {
            $pfadWert == "*" ? ($aktuellerPfad = true) : ($aktuellerPfad[$pfadWert] = true);
            break;
          } else {
            if(@$aktuellerPfad[$pfadWert] === true) // Schon gesetzt, »@« um nicht isset Check
              break;
            $aktuellerPfad[$pfadWert] = $aktuellerPfad[$pfadWert] ?? array();
          }
          $aktuellerPfad = &$aktuellerPfad[$pfadWert];
        }
      }
    }
  }

  function cms_rechte_laden_bedingte_rollen() {
    global $CMS_SCHLUESSEL, $cms_bedingte_rollenrechte;
    $zeit             = time();
    $nutzer_id        = $_SESSION["BENUTZERID"];
    $nutzer_vorname   = $_SESSION["BENUTZERVORNAME"];
    $nutzer_nachname  = $_SESSION["BENUTZERNACHNAME"];
    $nutzer_titel     = $_SESSION["BENUTZERTITEL"];
    $nutzer_art       = $_SESSION["BENUTZERART"];
    $nutzer_imln      = $_SESSION["IMLN"] ?? "0";

    $werte = array(
      "zeit"      => $zeit,
      "nutzer.id" => $nutzer_id,
      "nutzer.vorname" => $nutzer_vorname,
      "nutzer.nachname" => $nutzer_nachname,
      "nutzer.titel" => $nutzer_titel,
      "nutzer.art" => $nutzer_art,
      "nutzer.imln" => $nutzer_imln
    );

    $bedingungen = array();

    $dbs = cms_verbinden("s");

    $sql = "SELECT rolle, AES_DECRYPT(bedingung, '$CMS_SCHLUESSEL') FROM bedingterollen";
    $sql = $dbs->prepare($sql);
    $sql->bind_result($rolle, $bed);
    $sql->execute();
    while($sql->fetch()) {
      $b = $bedingungen[$rolle] ?? "";
      $b = "$b || ($bed)";
      $bedingungen[$rolle] = $b;
    }

    foreach($bedingungen as $r => $b) {
      $bedingungen[$r] = substr($b, 4);
    }

    $evaluieren = function($bedingung) use(&$evaluieren, $werte, $nutzer_id, $CMS_SCHLUESSEL) {
      $bedingung = $bedingung[0] ?? $bedingung;

      $typ  = $bedingung["typ"];
      $wert = $bedingung["wert"];
      if($typ === "Logisch") {
        $w0 = $bedingung["werte"][0] ?? null;
        $w1 = $bedingung["werte"][1] ?? null;

        if($wert == "&&") {
          return $evaluieren($w0) && $evaluieren($w1);
        }
        if($wert == "||") {
          return $evaluieren($w0) || $evaluieren($w1);
        }
        if($wert == "!") {
          return !($evaluieren($w0));
        }
      }

      if($typ === "Vergleich") {
        $w0 = $bedingung["werte"][0] ?? null;
        $w1 = $bedingung["werte"][1] ?? null;

        if($wert == "==") {
          return $evaluieren($w0) == $evaluieren($w1);
        }
        if($wert == "!=") {
          return $evaluieren($w0) != $evaluieren($w1);
        }
        if($wert == "<") {
          return $evaluieren($w0) < $evaluieren($w1);
        }
        if($wert == ">") {
          return $evaluieren($w0) > $evaluieren($w1);
        }
      }

      if($typ === "EndZahl" || $typ === "Zahl") {
        return (int) $wert;
      }

      if($typ === "EndString" || $typ === "String") {
        return "$wert";
      }

      if($typ === "EndFeld" || $typ === "Feld") {
        return $werte[$wert] ?? null;
      }

      if($typ == "Funktionsaufruf") {
        if($wert == "nutzer.hatRolle") {
          $rolle = $evaluieren($bedingung["werte"][0]);

          $dbs = cms_verbinden("s");

          $sql = "SELECT person FROM rollenzuordnung JOIN rollen ON rollenzuordnung.rolle = rollen.id WHERE AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') = ? AND person = ?;";
          $sql = $dbs->prepare($sql);
          $sql->bind_param("si", $rolle, $nutzer_id);
          $sql->bind_result($pers);

          if($sql->execute() && $sql->fetch() && $pers == $nutzer_id) {
            return true;
          } else {
            return false;
          }
        }
        if($wert == "nutzer.hatRecht") {
          $recht = $evaluieren($bedingung["werte"][0]);

          return cms_hat_recht($recht);
        }
      }

      return null;
    };

    $extrarollen = array();

    foreach($bedingungen as $rolle => $bedingung) {
      $bedingung_baum = cms_bedingt_bedingung_syntax_baum($bedingung);

      if($bedingung_baum === false) {
        throw new Exception("Bei den Bedingungen »{$bedingung}« für die Rolle »{$rolle}« ist ein Fehler aufgetreten!");
        return;
      }

      if($evaluieren($bedingung_baum)) {
        if(!in_array($rolle, $extrarollen)) {
          $extrarollen[] = $rolle;
        }
      }
    }

    if(count($extrarollen)) {
      $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle IN (".join(",", $extrarollen).")";
      cms_rechte_laden_sql($sql, $cms_bedingte_rollenrechte, "");
    }
  }
?>
