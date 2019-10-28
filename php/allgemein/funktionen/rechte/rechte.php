<?php
  include_once(dirname(__FILE__)."/../yaml.php");
  use Async\YAML;

  define("RECHTEPRUEFEN", true);

  $cms_nutzerrechte = array();  // Wichtig: im Array sind nur Rechte, die der Nutzer hat - d.h. Am Ende jedes "Pfads" im Array steht "true"
  $cms_allerechte = array();  // Alle Rechte als Ndarray
  function cms_rechte_laden_sql($sql, $pt, ...$params) {
    global $cms_nutzerrechte;
    $dbs = cms_verbinden();

    $sql = $dbs->prepare($sql);
    $sql->bind_param($pt, ...$params);
    $sql->bind_result($recht);
    $sql->execute();

    while($sql->fetch()) {
      $pfad = explode(".", $recht);
      $a = &$cms_nutzerrechte;
      foreach($pfad as $i => $p) {
        if(++$i == count($pfad)) {
          $p == "*" ? ($a = true) : ($a[$p] = true);
          break;
        } else {
          if(@$a[$p] === true)
            break;
          $a[$p] = array();
        }
        $a = &$a[$p];
      }
    }
    $sql->close();
  }
  function cms_rechte_laden_n() {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden("s");

    $person = $_SESSION['BENUTZERID'];
    if(!isset($person))
      return false;

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rechtezuordnung WHERE person = ?";
    cms_rechte_laden_sql($sql, "i", $person);
  }
  function cms_rechte_laden_r() {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden("s");

    $person = $_SESSION['BENUTZERID'];
    if(!isset($person))
      return false;

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte JOIN rollenzuordnung ON rollenrechte.rolle = rollenzuordnung.rolle WHERE rollenzuordnung.person = ?";
    cms_rechte_laden_sql($sql, "i", $person);
  }
  function cms_allerechte_laden() {
    global $cms_allerechte;

    if(!RECHTEPRUEFEN)
      return;

    $rechte = YAML::loader(dirname(__FILE__)."/rechte.yml");

    $rek = function($array, $pfad, $rek) use (&$cms_allerechte){
      foreach($array as $k => $v) {
        if(is_array($v)) {
          $rek($v, "$pfad.$k", $rek);
        } else {
          $cms_allerechte[] = substr("$pfad.$k", 1);
        }
      }
    };
    $rek($rechte, "", $rek);
  }

  function cms_hat_recht($recht) {
    global $CMS_SCHLUESSEL, $cms_nutzerrechte, $cms_allerechte;
    if(!RECHTEPRUEFEN && $cms_nutzerrechte === true)  // Alle Rechte
      return true;
    $dbs = cms_verbinden("s");

    $person = $_SESSION['BENUTZERID'];
    if(!isset($person))
      return false;

    $recht = str_replace("(",   " ( ",  $recht);
    $recht = str_replace(")",   " ) ",  $recht);
    $recht = str_replace("&&",  " && ", $recht);
    $recht = str_replace("||",  " || ", $recht);

    $rechte = explode(" ", $recht);
    $rechte = preg_grep("/([a-zäöüß]{1,}\.[a-zäöüß]{1,})|[a-zäöüß]{1,}/i", $rechte);
    $ergebnisse = array();

    foreach($rechte as $p => $r) {
      $n = $cms_nutzerrechte;

      if(RECHTEPRUEFEN && !(function($cms_allerechte, $r) {
        foreach($cms_allerechte as $a)
          if(substr($a, 0, strlen(rtrim($r, ".*"))) === rtrim($r, ".*"))
            return true;
        return false;
      })($cms_allerechte, $r))
        throw new Exception("Unbekanntes Recht: $r");

      foreach(explode(".", $r) as $r) {
        if($r == "*") {
          if(count($n) > 0 || $n === true) {
            $hat = true;
            break;
          }
        }
        if(isset($n[$r])) {
          if($n[$r] === true) {
            $hat = true;
            break;
          }
          $n = $n[$r];
        } else {
          $hat = false;
          break;
        }
      }
      $ergebnisse[$p] = $hat ? "1" : "0";
    }
    if(RECHTEPRUEFEN && $cms_nutzerrechte === true)  // Alle Rechte
    return true;
    $rechte = explode(" ", $recht);
    foreach($ergebnisse as $i => $e)
      $rechte[$i] = $e;
    $rechte = implode(" ", $rechte);
    eval('$r'." = $rechte;");
    return $r;
  }

  function r() {  // Alias
    return call_user_func("cms_hat_recht", ...func_get_args()) ?? false;
  }
?>
