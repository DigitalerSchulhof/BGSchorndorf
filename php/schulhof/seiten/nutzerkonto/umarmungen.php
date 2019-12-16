<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Umarmungen</h1>

<?php
  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare("SELECT von, anonym, wann, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM umarmungen LEFT JOIN personen ON von = personen.id WHERE an = ? ORDER BY wann ASC");
  $sql->bind_param("i", $CMS_BENUTZERID);
  $umarmungen = array();
  if ($sql->execute()) {
    $sql->bind_result($uvon, $uano, $uwann, $uvor, $unach, $utit);
    while($sql->fetch()) {
      $U = array();
      $U['von'] = $uvon;
      $U['anonym'] = $uano;
      $U['wann'] = $uwann;
      $U['vorname'] = $uvor;
      $U['nachname'] = $unach;
      $U['titel'] = $utit;
      array_push($umarmungen, $U);
    }
  }
  $sql->close();

  if(count($umarmungen) == 0) {
    echo "<p class=\"cms_notiz\">Schade, keine Umarmungen</p>";
  }
?>

<?php
foreach($umarmungen as $u) {
  $v = $u["von"];
  $a = $u["anonym"];
  $w = $u["wann"];
  echo "<p>";
  if($a) {echo "Anonym";}
  else {
    echo cms_generiere_anzeigename($u['vorname'], $u['nachname'], $u['titel']);
  }
  echo " hat ".($CMS_BENUTZERART=="s"?"dich":"Sie")." am ".date("d.m.Y", $w)." um ".date("H:m", $w)." Uhr umarmt! (＾◡＾)っ ♡</p>";
}
?>


<p><a class="cms_button" href="Schulhof/Nutzerkonto/Mein_Profil">Zurück</a></p>

</div>

<div class="cms_clear"></div>
