<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php
  $dbs = cms_verbinden("s");

  $id = $CMS_URL[2];
  $token = $CMS_URL[3];

  $fehler = false;
  // abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ
  if(!cms_check_ganzzahl($id, 0))
    $fehler = true;

  if(!$fehler) {
    $sql = "DELETE FROM newsletterempfaenger WHERE id = ? AND token = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("is", $id, $token);
    if(!$sql->execute() || !$dbs->affected_rows)
      $fehler = true;
  }
  if($fehler)
    echo cms_meldung_fehler();
  else
    echo "<h1>Erfolgreich vom Newsletter abgemeldet!</h1><br>";
?>
<span class="cms_button" onclick="cms_link('')">Zurück zur Startseite</span>
</div>
<div class="cms_clear"></div>
