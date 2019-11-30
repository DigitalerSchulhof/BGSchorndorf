<?php
function cms_geraetezustand_laden($dbs) {
  global $CMS_SCHLUESSEL;
  $code = "";
  $code .= "<h1>Gerätezustand</h1>";
  $code .= "<h2>Räume</h2>";
  $code .= "<div class=\"cms_problemmeldung_box\">";
  $sql = "SELECT * FROM (SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(raeumegeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM raeumegeraete JOIN raeume ON raeumegeraete.standort = raeume.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, rbez ASC, gbez ASC";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      $code .= "<div class=\"cms_problemmeldung cms_problem".$daten['gstat']."\">";
        $code .= "<div class=\"cms_problemmeldunginnen\">";
        $code .= "<span class=\"cms_problemmeldung_standort\">".$daten['rbez']." – ".$daten['gbez']."</span>";
        $code .= "<span class=\"cms_problemmeldung_meldung\">".$daten['gmel']."</span>";
        $code .= "<span class=\"cms_problemmeldung_kommentar\">".$daten['gkom']."</span>";
        $code .= "<span class=\"cms_problemmeldung_zeit\">".date("d.m.Y H:i", $daten['gzei'])."</span>";
        $code .= "</div>";
      $code .= "</div>";
    }
    $anfrage->free();
  }
  $code .= "</div>";

  $code .= "<h2>Leihgeräte</h2>";
  $code .= "<div class=\"cms_problemmeldung_box\">";
  $sql = "SELECT * FROM (SELECT AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(leihengeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM leihengeraete JOIN leihen ON leihengeraete.standort = leihen.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, rbez ASC, gbez ASC";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      $code .= "<div class=\"cms_problemmeldung cms_problem".$daten['gstat']."\">";
        $code .= "<div class=\"cms_problemmeldunginnen\">";
        $code .= "<span class=\"cms_problemmeldung_standort\">".$daten['rbez']." – ".$daten['gbez']."</span>";
        $code .= "<span class=\"cms_problemmeldung_meldung\">".$daten['gmel']."</span>";
        $code .= "<span class=\"cms_problemmeldung_kommentar\">".$daten['gkom']."</span>";
        $code .= "<span class=\"cms_problemmeldung_zeit\">".date("d.m.Y H:i", $daten['gzei'])."</span>";
        $code .= "</div>";
      $code .= "</div>";
    }
    $anfrage->free();
  }
  $code .= "</div>";


  $code .= "<h4>Legende</h4>";
  $code .= "<span class=\"cms_geraetelegende5\">von Extern für gelöst erklärt</span>";
  $code .= "<span class=\"cms_geraetelegende4\">defekt - externe Problemlösung</span>";
  $code .= "<span class=\"cms_geraetelegende3\">defekt - interne Problemlösung</span>";
  $code .= "<span class=\"cms_geraetelegende2\">eingeschränkt nutzbar</span>";
  $code .= "<span class=\"cms_geraetelegende1\">Meldung eingegangen</span>";

  return $code;
}
 ?>
