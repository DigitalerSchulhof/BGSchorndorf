<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Nutzerprofile</h1>";

if (isset($_SESSION['PERSONENPROFIL'])) {
  if (r("schulhof.verwaltung.personen.sehen")) {
    $person = $_SESSION['PERSONENPROFIL'];
    $fehler = true;
    $sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(art, '$CMS_SCHLUESSEL'), AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $person);
    if ($sql->execute()) {
      $sql->bind_result($vorname, $nachname, $titel, $art, $geschlecht);
      if ($sql->fetch()) {
        $anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
        $fehler = false;
      }
      $sql->close();
    }

    if (!$fehler) {
      $code .= "<h2>$anzeigename</h2>";
      $code .=  "<p>";
      if ($art == 'l') {$code .= "Lehrer"; if ($geschlecht == 'w') {$code .= "in";}}
      if ($art == 'e') {$code .= "Elternteil";}
      if ($art == 's') {$code .= "Schüler"; if ($geschlecht == 'w') {$code .= "in";}}
      if ($art == 'v') {$code .= "Angestellte"; if (($geschlecht == 'm') || ($geschlecht == 'u')) {$code .= "r";} $code .= " der Verwaltung";}
      $code .= "</p>";

      $aktionscode = "";

      if ($person != $CMS_BENUTZERID) {
        $schreiben = cms_schreibeberechtigung($dbs, $person);
        if ($schreiben) {
          $aktionscode .= "<li><a class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('vorgabe', '', '', '$person')\">Nachricht schreiben</a></li><br>";
        }
        $umarmen = true;
        if ($umarmen) {
          $aktionscode .= "<li><a class=\"cms_button\" id=\"cms_umarmen\" onclick=\"cms_umarmen('$person')\">Umarmen ( ＾◡＾)っ ♡</a></li> ";
        }
      }

      if (strlen($aktionscode) > 0) {
        $code .= "<h3>Aktionen</h3><ul class=\"cms_aktionen_liste\">".$aktionscode."</ul>";
      }
    }
  } else {
    $code .= cms_meldung_berechtigung();
  }
}
else {$code .= cms_meldung_bastler();}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
