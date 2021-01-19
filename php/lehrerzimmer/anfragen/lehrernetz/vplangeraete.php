<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/generieren.php");
// Variablen einlesen, falls übergeben

$fehler = false;
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kennung'])) {$kennung = $_POST['kennung'];} else {echo "FEHLER"; exit;}
if ($art != 'l') {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
if (!$fehler) {
  $inhalt = "VPlan".strtoupper($art);
  $gefunden = false;
  $sql = $dbs->prepare("SELECT COUNT(*) FROM internedienste WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
  $sql->bind_param("ss", $inhalt, $kennung);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {
      if ($anzahl > 0) {$gefunden = true;}
    }
  }
  $sql->close();

  $code = "";
  if ($gefunden)  {
    $code .= "<h2>Gerätezustände</h2>";

    $raeume = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(raeumegeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM raeumegeraete JOIN raeume ON raeumegeraete.standort = raeume.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, rbez ASC, gbez ASC");
    if ($sql->execute()) {
      $sql->bind_result($rbez, $gbez, $gstat, $gmel, $gkom, $gzei);
      while ($sql->fetch()) {
        $icon = "blau";
        if ($gstat == 1) {$icon = "blau";}
        if ($gstat == 2) {$icon = "gelb";}
        if ($gstat == 3) {$icon = "rot";}
        if ($gstat == 4) {$icon = "schwarz";}
        if ($gstat == 5) {$icon = "weiss";}

        $raeume .= "<tr><td><b>$rbez – $gbez</b>";
        //"<p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td>";
        $raeume .= "</td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
        //$raeume .= "<tr><td><p><b>$rbez – $gbez</b></p><p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
      }
    }
    $sql->close();


    $leihen = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT(leihengeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM leihengeraete JOIN leihen ON leihengeraete.standort = leihen.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, sbez ASC, gbez ASC");
    if ($sql->execute()) {
      $sql->bind_result($sbez, $gbez, $gstat, $gmel, $gkom, $gzei);
      while ($sql->fetch()) {
        $icon = "blau";
        if ($gstat == 1) {$icon = "blau";}
        if ($gstat == 2) {$icon = "gelb";}
        if ($gstat == 3) {$icon = "rot";}
        if ($gstat == 4) {$icon = "schwarz";}
        if ($gstat == 5) {$icon = "weiss";}
        $leihen .= "<tr><td><b>$sbez – $gbez</b>";
        //"<p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td>";
        $leihen .= "</td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
      }
    }
    $sql->close();

    if (strlen($raeume) > 0) {
      $code .= "<h3>Räume</h3>";
      $code .= "<table class=\"cms_liste\">";
      $code .= $raeume;
      $code .= "</table>";
    }

    if (strlen($leihen) > 0) {
      $code .= "<h3>Leihgeräte</h3>";
      $code .= "<table class=\"cms_liste\">";
      $code .= $leihen;
      $code .= "</table>";
    }

    if ((strlen($leihen) == 0) && (strlen($raeume) == 0)) {
      $code .= "<p class=\"cms_notiz\">Alle Geräte sind in einwandfreiem Zustand.</p>";
    }
    echo $code;
  }
  else {
    echo "BERECHTIGUNG";
  }
}
else {
  echo "FEHLER";
}
cms_trennen($dbs);
?>
