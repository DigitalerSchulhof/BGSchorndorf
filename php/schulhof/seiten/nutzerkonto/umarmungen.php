<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Umarmungen</h1>

<?php

  $sql = "SELECT von, anonym, wann FROM umarmungen WHERE an = $CMS_BENUTZERID ORDER BY wann asc";
  $dbs = cms_verbinden("s");
  $sql = $dbs->query($sql); // Safe weil keine Eingabe
  $umarmungen = array();
  if($sql)
    while($sqld = $sql->fetch_assoc())
      array_push($umarmungen, array("von" => $sqld["von"], "anonym" => $sqld["anonym"], "wann" => $sqld["wann"]));

  if(count($umarmungen) == 0)
    echo "<p class=\"cms_notiz\">Schade, keine Umarmungen</p>";

?>

<?php
$n = array();
foreach($umarmungen as $u) {
  $v = $u["von"];
  $a = $u["anonym"];
  $w = $u["wann"];
  echo "<p>";
  if($a)
    echo "Anonym";
  else {
    if(!isset($n[$v])) {
      $sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id = $v";
      if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
        if ($daten = $anfrage->fetch_assoc()) {
          $vorname = $daten['vorname'];
          $nachname = $daten['nachname'];
          $titel = $daten['titel'];
          $von = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
          echo $n[$v] = $von;
        }
        $anfrage->free();
      }
    } else {
      echo $n[$v];
    }
  }
  echo " hat ".($CMS_BENUTZERART=="s"?"dich":"Sie")." am ".date("d.m.Y", $w)." um ".date("H:m", $w)." Uhr umarmt! ( ＾◡＾)っ ♡</p>";
}
?>


<p><a class="cms_button" href="Schulhof/Nutzerkonto/Mein_Profil">Zurück</a></p>

</div>

<div class="cms_clear"></div>
