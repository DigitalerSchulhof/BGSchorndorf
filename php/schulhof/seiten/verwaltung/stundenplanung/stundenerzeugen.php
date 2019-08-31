<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Stunden und Tagebücher erzeugen'];

$code = "";
if ($zugriff) {

  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['STUNDENERZEUGENSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['STUNDENERZEUGENSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (!$sjfehler) {
    $code .= "<h1>Stunden und Tagebücher erzeugen</h1>";
    $zeitraumfehler = false;

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_stundenerzeugen_vorbereiten($id)\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    
  }
  else {$code .= "<h1>Stundenplanung</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Stundenplanung</h1>".cms_meldung_berechtigung();
}

function cms_generiere_unterrichtsstunde($stunde, $modus) {
  $event = "";
  if ($modus == 'L') {$event = " onclick=\"cms_stundeloeschen(".$stunde['id'].")\"";}
  $code = "<span class=\"cms_stundenplanung_stunde cms_farbbeispiel_".$stunde['farbe']."\"$event><span class=\"cms_stundenplanung_stundeinfo\">".$stunde['kursbez']."<br>".$stunde['lehrerbez']."<br>".$stunde['raumbez']."";
  if ($stunde['rythmus'].'' != '0') {
    $code .= "<br>".chr(64+$stunde['rythmus'])." Woche";
  }
  $code .= "</span></span>";
  return $code;
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
