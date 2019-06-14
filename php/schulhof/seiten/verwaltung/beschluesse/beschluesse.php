<?php
function cms_beschlusselemente($dbs, $id, $gruppe, $gruppenid) {
  global $CMS_SCHLUESSEL;
  $code = "";

  $gk = cms_textzudb($gruppe);

  // Vorhandene Downloads laden
  $sql = "";
  $beschluesse = array();
  if ($id != '-') {
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(langfristig, '$CMS_SCHLUESSEL') AS langfristig, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, pro, contra, enthaltung FROM $gruppe"."blogeintragbeschluesse WHERE blogeintrag = $id) AS x ORDER BY langfristig, titel";
    if (strlen($sql) > 0) {
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          array_push($beschluesse, $daten);
        }
        $anfrage->free();
      }
    }
  }


  $code .= "<div id=\"cms_beschluesse\">";
  $anzahl = 0;
  $ids = "";
  foreach ($beschluesse as $b) {
    $bid = $b['id'];
    $code .= "<table class=\"cms_formular\" id=\"cms_beschluss_$bid\">";
      $code .= "<tr><th>Titel:</th><td colspan=\"6\"><input type=\"text\" name=\"cms_beschluss_titel_".$bid."\" id=\"cms_beschluss_titel_".$bid."\" value=\"".$b['titel']."\"></td></tr>";
      $code .= "<tr><th>Beschreibung:</th><td colspan=\"6\"><textarea name=\"cms_beschluss_beschreibung_".$bid."\" id=\"cms_beschluss_beschreibung_".$bid."\">".$b['beschreibung']."</textarea></td></tr>";
      $code .= "<tr><th>Langfristig:</th><td colspan=\"6\">".cms_schieber_generieren('cms_beschluss_langfristig_'.$bid, $b['langfristig'])."</td></tr>";
      $code .= "<tr><th>Stimmen:</th><td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">Dafür</span><img src=\"res/icons/klein/pro.png\"></span></td>";
      $code .= "<td><input type=\"number\" min=\"0\" step=\"1\" value=\"".$b['pro']."\" name=\"cms_beschluss_pro_".$bid."\" id=\"cms_beschluss_pro_".$bid."\"></td>";
      $code .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">Enthaltung</span><img src=\"res/icons/klein/egal.png\"></span></td>";
      $code .= "<td><input type=\"number\" min=\"0\" step=\"1\" value=\"".$b['enthaltung']."\" name=\"cms_beschluss_enthaltung_".$bid."\" id=\"cms_beschluss_enthaltung_".$bid."\"></td>";
      $code .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">Dagegen</span><img src=\"res/icons/klein/contra.png\"></span></td>";
      $code .= "<td><input class=\"\" type=\"number\" min=\"0\" step=\"1\" value=\"".$b['contra']."\" name=\"cms_beschluss_contra_".$bid."\" id=\"cms_beschluss_contra_".$bid."\"></td>";
      $code .= "</tr>";

      $code .= "<tr><th></th><td colspan=\"6\"><span class=\"cms_button_nein\" onclick=\"cms_beschluss_entfernen('$bid');\">Beschluss löschen</span></td></tr>";
    $code .= "</table>";
    $anzahl++;
    $ids .= "|".$bid;
  }
  $code .= "</div>";

  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_beschluss();\">+ Neuer Beschluss</span>";
    $code .= "<input type=\"hidden\" id=\"cms_beschluesse_anzahl\" name=\"cms_beschluesse_anzahl\" value=\"$anzahl\">";
    $code .= "<input type=\"hidden\" id=\"cms_beschluesse_nr\"     name=\"cms_beschluesse_nr\" value=\"$anzahl\">";
    $code .= "<input type=\"hidden\" id=\"cms_beschluesse_ids\"    name=\"cms_beschluesse_ids\" value=\"$ids\">";
  $code.= "</p>";


  return $code;
}

function cms_beschluss_ausgeben($b, $link = false, $url = "") {
  if ($link) {
    $jahr = date('Y', $b['datum']);
    $monat = cms_monatsnamekomplett(date('m', $b['datum']));
    $tag = date('d', $b['datum']);
    $url = $url."/Blog/".$jahr."/".$monat."/".$tag."/".cms_textzulink($b['bezeichnung']);
  }
  $code = "";
  if (($b['pro'] > $b['contra']) && ($b['pro'] > $b['enthaltung'])) {$zusatz = "pro";}
  else if (($b['contra'] > $b['pro']) && ($b['contra'] > $b['enthaltung'])) {$zusatz = "contra";}
  else {$zusatz = "enthaltung";}
  if ($link) {$code .= "<a class=\"cms_beschluss cms_beschluss_$zusatz\" href=\"$url\">";}
  else {$code .= "<div class=\"cms_beschluss cms_beschluss_$zusatz\">";}
  $code .= "<h4>".$b['titel']."</h4>";
  $code .= "<p>".cms_textaustextfeld_anzeigen($b['beschreibung'])."</p>";
  $code .= "<p class=\"cms_beschluss_stimmen\"><span class=\"cms_beschluss_stimmen_pro\">".$b['pro']."</span><span class=\"cms_beschluss_stimmen_enthaltung\">".$b['enthaltung']."</span><span class=\"cms_beschluss_stimmen_contra\">".$b['contra']."</span> ";
  if ($b['langfristig'] == 1) {$code .= "<span class=\"cms_beschluss_langfristig\">langfristig</span>";}
  $code .= "</p>";
  if ($link) {$code .= "</a>";}
  else {$code .= "</div>";}
  return $code;
}
?>
