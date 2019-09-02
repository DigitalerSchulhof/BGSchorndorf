<?php
function cms_lehrerzimmer_gruppen_links_anzeigen ($gruppe) {
  $gruppek = strtolower($gruppe);
  global $CMS_IMLN, $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID;
  $ausgabe = "";

  if (!$CMS_IMLN && ($CMS_RECHTE['Gruppen'][$gruppe.' anlegen'] || $CMS_RECHTE['Gruppen'][$gruppe.' löschen'])) {
    $ausgabe .= cms_meldung_eingeschraenkt();
  }

  $dbs = cms_verbinden('s');
  // Sichtbare Gruppen laden und Gruppen, in denen sich der Benutzer befindet
  $sqlsichtbar = "SELECT ".$gruppek.".id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM ".$gruppek." WHERE sichtbar = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";
  $sqlmitglied = "SELECT ".$gruppek.".id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM ".$gruppek." JOIN ".$gruppek."mitgliedschaften ON ".$gruppek.".id = ".$gruppek."mitgliedschaften.gruppe WHERE person = $CMS_BENUTZERID";
  $sqlaufsicht = "SELECT ".$gruppek.".id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM ".$gruppek." JOIN aufsichten ON ".$gruppek.".id = aufsichten.gruppenid WHERE person = $CMS_BENUTZERID AND gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL')";

  $sql = "SELECT DISTINCT * FROM (($sqlsichtbar) UNION ($sqlmitglied) UNION ($sqlaufsicht)) AS gruppen ORDER BY bezeichnung ASC;";

  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $ausgabe .= "<li><span class=\"cms_button\" onclick=\"cms_lehrerzimmer_gruppe_anzeigen('$gruppe', '".$daten['id']."', '".$daten['bezeichnung']."');\">".$daten['bezeichnung']."</span></li> ";
    }
  }
  cms_trennen($dbs);
  if ($CMS_RECHTE['Gruppen'][$gruppe.' anlegen']) {
    if ($CMS_IMLN) {
      $ausgabe .= "<li><a class=\"cms_button_ja\" href=\"Lehrerzimmer/Verwaltung/$gruppe/Neue_Gruppe_anlegen\">+ Neue"." ".$gruppe." anlegen</a></li>";
    }
    else {
      $ausgabe .= "<li><span class=\"cms_button_eingeschraenkt\">+ Neue ".$gruppe." anlegen</span></li>";
    }
  }

  if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
  else {$ausgabe = '<p class="cms_notiz">Keine '.$gruppe.' angelegt</p>';}

  return $ausgabe;
}
?>
