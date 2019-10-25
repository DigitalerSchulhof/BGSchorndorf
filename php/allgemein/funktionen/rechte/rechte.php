<?php
  include_once(dirname(__FILE__)."/../yaml.php");

  function cms_hat_recht($recht) {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden("s");
    $person = $_SESSION['BENUTZERID'];
    if(!isset($person))
      return false;

    $rechte = explode(" ", $recht);
    $rechte = preg_grep("/([a-zäöüß]{1,}\.[a-zäöüß]{1,})|[a-zäöüß]{1,}/i", $rechte);
    $ergebnisse = array();

    foreach($rechte as $p => $r) {
      $pfad = explode(".", $r);
      $query = array();
      $prev = "";
      foreach($pfad as $pf) {
        $query[] = $prev."*";
        $prev .= "$pf.";
      }
      $prev = substr($prev, 0, -1);
      $query[] = $prev;
      $sql = "SELECT COUNT(*) FROM rechtezuordnung WHERE person = ? AND (";
      $sql .= implode(" OR ", array_fill(0, count($query), "recht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')"));
      $sql .= ")";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("i".str_repeat("s", count($query)), $person, ...$query);
      $sql->bind_result($has);
      $sql->execute();
      $sql->fetch();
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
