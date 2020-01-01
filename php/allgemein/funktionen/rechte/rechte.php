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

  	if ($person == '-')
      $person = $_SESSION['BENUTZERID'] ?? "-";

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rechtezuordnung WHERE person = ?";
    cms_rechte_laden_sql($sql, $arr, "i", $person);
  }

  function cms_rechte_laden_rollen($person = '-', &$arr = null) { // $arr: Zu befüllendes Array
    global $CMS_SCHLUESSEL, $cms_nutzerrechte;
    $dbs = cms_verbinden("s");

    if(is_null($arr))
      $arr = &$cms_nutzerrechte;

    if ($person == '-')
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
          $cms_allerechte[] = substr("$pfad.$k", 1);
        }
      }
    };

    $rek($rechte, "");
  }

  function cms_hat_recht($rechteCode) {
    global $CMS_SCHLUESSEL, $cms_nutzerrechte, $cms_bedingte_nutzerrechte, $cms_bedingte_rollenrechte, $cms_allerechte;

    if($cms_nutzerrechte === true || $cms_bedingte_nutzerrechte === true || $cms_bedingte_rollenrechte === true) {
      $cms_rechte = true;
    } else {
      $cms_rechte = array_merge($cms_nutzerrechte, $cms_bedingte_nutzerrechte, $cms_bedingte_rollenrechte);
    }

    if(!RECHTEPRUEFEN && $cms_rechte === true)  // Alle Rechte sind vergeben
      return true;

    if($rechteCode === "*")
      if($cms_rechte === true || count($cms_rechte))
        return true;
      else
        return false;

    $dbs = cms_verbinden("s");

    $person = $_SESSION['BENUTZERID'];
    if(!isset($person))
      return false;

    $rechteCode = str_replace("(",   " ( ",  $rechteCode);
    $rechteCode = str_replace(")",   " ) ",  $rechteCode);
    $rechteCode = str_replace("&&",  " && ", $rechteCode);
    $rechteCode = str_replace("||",  " || ", $rechteCode);

    $rechte = explode(" ", $rechteCode);
    $rechte = preg_grep("/^(?:[a-zäöüß*.]+)$/i", $rechte);
    $ergebnisse = array();

    foreach($rechte as $position => $recht) {
      $rechteArray = $cms_rechte;

      if(RECHTEPRUEFEN && !(function($cms_allerechte, $recht) {
        foreach($cms_allerechte as $a)
          if(substr($a, 0, strlen(rtrim($recht, ".*"))) === rtrim($recht, ".*"))
            return true;
        return false;
      })($cms_allerechte, $recht))
        throw new Exception("Unbekanntes Recht: $recht");

      foreach(explode(".", $recht) as $recht) {
        if($recht == "*") {
          if(count($rechteArray) > 0 || $rechteArray === true) {
            $hat = true;
            break;
          }
        }
        if(isset($rechteArray[$recht])) {
          if($rechteArray[$recht] === true) {
            $hat = true;
            break;
          }
          $rechteArray = $rechteArray[$recht];
        } else {
          $hat = false;
          break;
        }
      }
      $ergebnisse[$position] = $hat ? "1" : "0";
    }

    if(RECHTEPRUEFEN && $cms_nutzerrechte === true)  // Alle Rechte
      return true;

    $rechteEval = explode(" ", $rechteCode);
    foreach($ergebnisse as $i => $e)
      $rechteEval[$i] = $e;
    $rechteEval = implode(" ", $rechteEval);

    return eval("return ($rechteEval);");
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
