<?php
function cms_vertretungsplan_stunde_auswaehlen($dbs, $schuljahr, $id) {
  global $CMS_SCHLUESSEL;

  $code = "";
  $stunde = "-";
  $sql = "SELECT * FROM tagebuch_$schuljahr WHERE id = $id";
  $sql = "SELECT kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, tlehrkraft AS lehrer, traum AS raum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, entfall, tbeginn AS beginn, tende AS ende, tstunde AS stunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext FROM ($sql) AS x JOIN kurse ON x.kurs = kurse.id JOIN personen ON tlehrkraft = personen.id JOIN lehrer ON tlehrkraft = lehrer.id JOIN raeume ON traum = raeume.id";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      $stunde = $daten;
    }
    $anfrage->free();
  }

  $klassen = "";
  $klassenbez = "";
  $sql = "SELECT x.klasse AS klassenid, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM (SELECT klasse FROM kursklassen WHERE kurs = ".$stunde['kurs'].") AS x JOIN klassen ON x.klasse = klassen.id JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $klassen .= "|".$daten['klassenid'];
      $klassenbez .= " ".$daten['stufe'].$daten['klassenbez'];
    }
    $anfrage->free();
  }

  if (strlen($klassenbez) > 0) {$klassenbez = substr($klassenbez, 1);}

  // Stunden ausgeben
  if ($stunde == "-") {
    $code = "STUNDE";
  }
  else {
    $code .= "<h3>Stunde aktuell</h3>";
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr>";
        $code .= "<th>Kurs:</th>";
        $code .= "<td>".$stunde['kursbez'];
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_kurs_id\" name=\"cms_vertretungsplan_stunde_gewaehlt_kurs_id\" value=\"".$stunde['kurs']."\">";
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_kurs_bez\" name=\"cms_vertretungsplan_stunde_gewaehlt_kurs_bez\" value=\"".$stunde['kurs']."\">";
        $code .= "</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Betroffene Klassen:</th>";
        $code .= "<td>".$klassenbez;
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_klassen\" name=\"cms_vertretungsplan_stunde_gewaehlt_klassen\" value=\"".$klassen."\">";
        $code .= "</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Lehrkraft:</th>";
        $code .= "<td>".cms_generiere_anzeigename($stunde['vorname'], $stunde['nachname'], $stunde['titel'])." (".$stunde['kuerzel'].")";
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_person_id\" name=\"cms_vertretungsplan_stunde_gewaehlt_person_id\" value=\"".$stunde['lehrer']."\">";
        $code .= "</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Raum:</th>";
        $code .= "<td>".$stunde['raumbez'];
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_raum_id\" name=\"cms_vertretungsplan_stunde_gewaehlt_raum_id\" value=\"".$stunde['raum']."\">";
        $code .= "</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Zeit:</th>";
        $code .= "<td>".date("H:i", $stunde['beginn'])." - ".date("H:i", $stunde['ende']);
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_bs\" name=\"cms_vertretungsplan_stunde_gewaehlt_bs\" value=\"".date('H', $stunde['beginn'])."\">";
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_bm\" name=\"cms_vertretungsplan_stunde_gewaehlt_bm\" value=\"".date('i', $stunde['beginn'])."\">";
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_es\" name=\"cms_vertretungsplan_stunde_gewaehlt_es\" value=\"".date('H', $stunde['ende'])."\">";
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_em\" name=\"cms_vertretungsplan_stunde_gewaehlt_em\" value=\"".date('i', $stunde['ende'])."\">";
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_stunde\" name=\"cms_vertretungsplan_stunde_gewaehlt_stunde\" value=\"".$stunde['stunde']."\">";
        $code .= "</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Status:</th>";
        $code .= "<td>";
          if ($stunde['entfall'] == 1) {$code .= "<i>entfällt</i>";}
          else {$code .= "<i>findet statt</i>";}
          if (strlen($stunde['vtext']) > 0) {$code .= "<br>".$stunde['vtext'];}
          $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_vtext\" name=\"cms_vertretungsplan_stunde_gewaehlt_vtext\" value=\"".$stunde['vtext']."\">";
        $code .= "</td>";
      $code .= "</tr>";
    $code .= "</table>";
    $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt\" name=\"cms_vertretungsplan_stunde_gewaehlt\" value=\"".$id."\">";
    $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_stunde_gewaehlt_schuljahr\" name=\"cms_vertretungsplan_stunde_gewaehlt_schuljahr\" value=\"".$schuljahr."\">";
  }

  return $code;
}
?>
