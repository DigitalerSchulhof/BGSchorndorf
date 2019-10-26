<?php
  include_once(dirname(__FILE__)."/../yaml.php");

  $cms_nutzerrechte = array();
  function cms_rollen_sql($sql, $pt, ...$params) {
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
        $letztes = ++$i == count($pfad);
        if($letztes) {
          if($p != "*") {
            $a[$p] = null;
            $a = &$a[$p];
          }
          $a = true;
          break;
        } else {
          if(@$a[$p] === true) // Nichts überschreiben
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
    cms_rollen_sql($sql, "i", $person);
  }

  function cms_rechte_laden_r() {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden("s");

    $person = $_SESSION['BENUTZERID'];
    if(!isset($person))
      return false;

    $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte JOIN rollenzuordnung ON rollenrechte.rolle = rollenzuordnung.rolle WHERE rollenzuordnung.person = ?";
    cms_rollen_sql($sql, "i", $person);
  }

  function cms_hat_recht($recht) {
    global $CMS_SCHLUESSEL, $cms_nutzerrechte;
    if($cms_nutzerrechte === true)  // Alle Rechte
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
      foreach(explode(".", $r) as $r) {
        if(isset($n[$r])) {
          if($n[$r] === true) {
            $has = true;
            break;
          }
          $n = $n[$r];
        } else {
          $has = false;
          break;
        }
      }
      $ergebnisse[$p] = $has ? "1" : "0";
    }
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
