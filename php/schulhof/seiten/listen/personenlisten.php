<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$listenart = $CMS_URL[count($CMS_URL)-1];
if ($listenart != 'Eltern') {$genitiv = 'n';}
$code .= "<h1>Liste der $listenart</h1>";

$zugriff = false;
if ($listenart == 'Lehrer') {$zugriff = $CMS_RECHTE['Personen']['Lehrerliste sehen']; $art = 'l';}
if ($listenart == 'Schüler') {$zugriff = $CMS_RECHTE['Personen']['Schülerliste sehen']; $art = 's';}
if ($listenart == 'Eltern') {$zugriff = $CMS_RECHTE['Personen']['Elternliste sehen']; $art = 'e';}
if ($listenart == 'Verwaltung') {$zugriff = $CMS_RECHTE['Personen']['Verwaltungsliste sehen']; $art = 'v';}
if ($listenart == 'Externe') {$zugriff = $CMS_RECHTE['Personen']['Externenliste sehen']; $art = 'x';}
if ($listenart == 'Elternvertreter') {$zugriff = $CMS_RECHTE['Personen']['Elternvertreter sehen']; $art = 'ev';}
if ($listenart == 'Schülervertreter') {$zugriff = $CMS_RECHTE['Personen']['Schülervertreter sehen']; $art = 'sv';}

if ($zugriff) {
  include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
  $schreibenpool = cms_postfach_empfaengerpool_generieren($dbs);

  $code .= "</div><div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
    // Personen laden
    $code .= "<table class=\"cms_liste\" id=\"cms_personenliste\">";
      include_once('php/schulhof/seiten/listen/listenausgeben.php');
      $code .= cms_listen_personenliste_ausgeben($dbs, $schreibenpool, $art, 1);
    $code .= "</table>";


  $code .= "</div></div><div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Optionen</h2>";
    $code .= "<p>";
    $code .= cms_togglebutton_generieren ('cms_personenliste_postfach', 'Postfach', 1, "cms_listen_personenliste_laden('$listenart')")." ";
    $code .= cms_togglebutton_generieren ('cms_personenliste_leer', 'Leeres Feld', 0, "cms_listen_personenliste_laden('$listenart')")." ";
    if ($listenart == 'Lehrer') {
      $code .= cms_togglebutton_generieren ('cms_personenliste_klassenzugehoerigkeit', 'Klassenzugehörigkeit', 0, "cms_listen_personenliste_laden('$listenart')")." ";
    }
    if ($listenart == 'Schüler' || $listenart == 'Schülervertreter') {
      $code .= cms_togglebutton_generieren ('cms_personenliste_eltern', 'Eltern', 0, "cms_listen_personenliste_laden('$listenart')")." ";
      $code .= cms_togglebutton_generieren ('cms_personenliste_klassenzugehoerigkeit', 'Klassenzugehörigkeit', 0, "cms_listen_personenliste_laden('$listenart')")." ";
    }
    if ($listenart == 'Eltern' || $listenart == 'Elternvertreter') {
      $code .= cms_togglebutton_generieren ('cms_personenliste_kinder', 'Kinder', 0, "cms_listen_personenliste_laden('$listenart')")." ";
    }
    if ($CMS_IMLN = false) {
      $code .= cms_togglebutton_generieren ('cms_personenliste_adresse', 'Adresse', 0, "cms_listen_personenliste_laden('$listenart')");
      $code .= cms_togglebutton_generieren ('cms_personenliste_kontaktdaten', 'Kontaktdaten', 0, "cms_listen_personenliste_laden('$listenart')");
      if ($listenart == 'Schüler' || $listenart == 'Schülervertreter') {
        $code .= cms_togglebutton_generieren ('cms_personenliste_geburtsdatum', 'Geburtsdatum', 0, "cms_listen_personenliste_laden('$listenart')");
        $code .= cms_togglebutton_generieren ('cms_personenliste_reliunterricht', 'Religionsunterricht', 0, "cms_listen_personenliste_laden('$listenart')")." ";
        $code .= cms_togglebutton_generieren ('cms_personenliste_konfession', 'Konfession', 0, "cms_listen_personenliste_laden('$listenart')");
      }
    }
    else {
      $code .= "<span class=\"cms_button_eingeschraenkt\">Adresse</span> ";
      $code .= "<span class=\"cms_button_eingeschraenkt\">Kontaktdaten</span> ";
      if ($listenart == 'Schüler' || $listenart == 'Schülervertreter') {
        $code .= "<span class=\"cms_button_eingeschraenkt\">Geburtsdatum</span> ";
        $code .= "<span class=\"cms_button_eingeschraenkt\">Religionsunterricht</span> ";
        $code .= "<span class=\"cms_button_eingeschraenkt\">Konfession</span> ";
      }
    }
    $code .= "</p>";
    if (!$CMS_IMLN) {
      //$code .= cms_meldung_eingeschraenkt();
    }
  $code .= "</div>";
}
else {
  $code .= cms_meldung_berechtigung();
}

$code .= "</div>";

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
