<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Conoatest</h1>";


$zugriff = (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')); // Mitgliedschaft oder nach außen sichtbar

if ($zugriff) {
  $code .= "<h2>Meine Gruppen</h2>";
  foreach ($CMS_GRUPPEN as $g) {
    $kg = strtolower(str_replace(" ", "", $g));
    $gcode = "";

    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM $kg WHERE id IN (SELECT DISTINCT gruppe FROM $kg"."mitglieder WHERE person = ?)) AS x ORDER BY bez ASC");
    $sql->bind_param("i", $CMS_BENUTZERID);
	  if ($sql->execute()) {
	    $sql->bind_result($gid, $gbez);
	    while ($sql->fetch()) {
        $gcode .= "<span class=\"cms_button\" onclick=\"cms_coronatest_vorbereiten('$g', '$gid')\">$gbez</span> ";
      }
	  }
	  $sql->close();

    if (strlen($gcode) > 0) {
      $code .= "<h3>$g</h3><p>".$gcode."</p>";
    }
  }

  $code .= "<h2>Sonstige Gruppen</h2>";

  foreach ($CMS_GRUPPEN as $g) {
    $kg = strtolower(str_replace(" ", "", $g));
    $gcode = "";

    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM $kg WHERE id NOT IN (SELECT DISTINCT gruppe FROM $kg"."mitglieder WHERE person = ?)) AS x ORDER BY bez ASC");
    $sql->bind_param("i", $CMS_BENUTZERID);
	  if ($sql->execute()) {
	    $sql->bind_result($gid, $gbez);
	    while ($sql->fetch()) {
        $gcode .= "<span class=\"cms_button\" onclick=\"cms_coronatest_vorbereiten('$g', '$gid')\">$gbez</span> ";
      }
	  }
	  $sql->close();

    if (strlen($gcode) > 0) {
      $code .= "<h3>$g</h3><p>".$gcode."</p>";
    }
  }
}
else {
  $code .= cms_meldung_berechtigung();
}


$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
