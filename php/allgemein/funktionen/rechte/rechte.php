<?php
  include_once(dirname(__FILE__)."/../yaml.php");
  use Async\YAML;

  define("RECHTEPRUEFEN", true);  // Prüfen, on angegebene Rechte existieren und ggf warnen

  $cms_nutzerrechte = array();  // Wichtig: im Array sind nur Rechte, die der Nutzer hat - d.h. Am Ende jedes "Pfads" im Array steht "true"
  $cms_allerechte = array();    // Alle Rechte als Ndarray

  function cms_rechte_laden_sql($sql, &$rueckgabe, $parameterTypen, ...$parameter) { // Rechte aus der Datenbank laden - Personen oder Rollen
    $dbs = cms_verbinden();

    $sql = $dbs->prepare($sql);
    $sql->bind_param($parameterTypen, ...$parameter);
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
    global $CMS_SCHLUESSEL, $cms_nutzerrechte, $cms_allerechte;

    if(!RECHTEPRUEFEN && $cms_nutzerrechte === true)  // Alle Rechte sind vergeben
      return true;

    if($rechteCode === "*")
      if($cms_nutzerrechte === true || count($cms_nutzerrechte))
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
    $rechte = preg_grep("/^(?:[a-zäöüß0-9*_.-]+)$/i", $rechte);
    $ergebnisse = array();

    foreach($rechte as $position => $recht) {
      $rechteArray = $cms_nutzerrechte;

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
?>
