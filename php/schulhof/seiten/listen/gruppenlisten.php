<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$gruppenart = $CMS_URL[count($CMS_URL)-3];
$g = cms_linkzutext($gruppenart);
$gk = cms_textzudb($g);
$schuljahr = $CMS_URL[count($CMS_URL)-2];
$sj = cms_linkzutext($schuljahr);
$gruppenname = $CMS_URL[count($CMS_URL)-1];
$gn = cms_linkzutext($gruppenname);

if ($sj == 'Schuljahrübergreifend') {
  $code .= "<h1>Listen von $gn</h1>";
}
else {
  $code .= "<h1>Listen von $gn im Schuljahr $sj</h1>";
}

if (cms_valide_gruppe($g)) {
  $zugriff = $CMS_RECHTE['Gruppen'][$g." Listen sehen"];

  // Prüfen, ob Mitglied in dieser Gruppe
  if (!$zugriff) {
    if ($schuljahr == 'Schuljahrübergreifend') {
      $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk JOIN $gk"."mitglieder ON $gk.id = $gk"."mitglieder.gruppe WHERE $gk"."mitglieder.person = $CMS_BENUTZERID AND $gk.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
      $sql->bind_param("s", $gn);
    }
    else {
      $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk JOIN $gk"."mitglieder ON $gk.id = $gk"."mitglieder.gruppe JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk"."mitglieder.person = $CMS_BENUTZERID AND $gk.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahre.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
      $sql->bind_param("ss", $gn, $sj);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$zugriff = true;}}
    }
    $sql->close();
  }

  // Gruppeninformation abholen
  if ($schuljahr == 'Schuljahrübergreifend') {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, $gk.id FROM $gk WHERE $gk.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
    $sql->bind_param("s", $gn);
  }
  else {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, $gk.id FROM $gk JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahre.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
    $sql->bind_param("ss", $gn, $sj);
  }
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $gruppenid);
    if ($sql->fetch()) {if ($anzahl != 1) {$zugriff = false;}}
  }
  $sql->close();

  $gruppenliste = "";
  if ($zugriff) {
    include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
    $schreibenpool = cms_postfach_empfaengerpool_generieren($dbs);

    $code .= "</div><div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
      // Personen laden
      $code .= "<div id=\"cms_gruppenliste\">";
        include_once('php/schulhof/seiten/listen/listenausgeben.php');
        $rueckgabe = cms_listen_gruppenliste_ausgeben($dbs, $gk, $gruppenid, 'slevx', 'mva', $schreibenpool, 1);
        $code .= $rueckgabe['tabelle'];
      $code .= "</div>";


    $code .= "</div></div><div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
      $code .= "<h2>Personengruppen</h2>";
      $code .= "<p>";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_ps', 'Schüler', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_pl', 'Lehrer', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_pe', 'Eltern', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_pv', 'Verwaltung', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_px', 'Extern', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= "</p>";

      $code .= "<h2>Gruppenrollen</h2>";
      $code .= "<p>";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_rm', 'Mitglieder', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_rv', 'Vorsitzende', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_ra', 'Aufsichten', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= "</p>";

      $code .= "<h2>Optionen</h2>";
      $code .= "<p>";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_postfach', 'Postfach', 1, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_leer', 'Leeres Feld', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_klassenzugehoerigkeit', 'Klassenzugehörigkeit', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_eltern', 'Eltern', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      $code .= cms_togglebutton_generieren ('cms_gruppenliste_kinder', 'Kinder', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
      if ($CMS_IMLN = false) {
        $code .= cms_togglebutton_generieren ('cms_gruppenliste_adresse', 'Adresse', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')");
        $code .= cms_togglebutton_generieren ('cms_gruppenliste_kontaktdaten', 'Kontaktdaten', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')");
        $code .= cms_togglebutton_generieren ('cms_gruppenliste_geburtsdatum', 'Geburtsdatum', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')");
        $code .= cms_togglebutton_generieren ('cms_gruppenliste_reliunterricht', 'Religionsunterricht', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
        $code .= cms_togglebutton_generieren ('cms_gruppenliste_profil', 'Profile', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')")." ";
        $code .= cms_togglebutton_generieren ('cms_gruppenliste_konfession', 'Konfession', 0, "cms_listen_gruppenliste_laden('$g', '$gruppenid')");
      }
      else {
        $code .= "<span class=\"cms_button_gesichert\">Adresse</span> ";
        $code .= "<span class=\"cms_button_gesichert\">Kontaktdaten</span> ";
        $code .= "<span class=\"cms_button_gesichert\">Geburtsdatum</span> ";
        $code .= "<span class=\"cms_button_gesichert\">Religionsunterricht</span> ";
        $code .= "<span class=\"cms_button_gesichert\">Profile</span> ";
        $code .= "<span class=\"cms_button_gesichert\">Konfession</span> ";
      }
      $code .= "</p>";
      if (!$CMS_IMLN) {
        //$code .= cms_meldung_eingeschraenkt();
      }

      if (strlen($rueckgabe['knoepfe']) > 0) {
        $code .= "<h2>Aktionen</h2>";
        $code .= "<p>".$rueckgabe['knoepfe']."</p>";
      }
    $code .= "</div>";
  }
  else {$code .= cms_meldung_berechtigung();}

}
else {$code .= cms_meldung_berechtigung();}

$code .= "</div>";

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
