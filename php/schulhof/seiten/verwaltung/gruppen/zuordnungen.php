<?php
function cms_zuordnungsauswahl_generieren($zgruppenids, $gruppe, $schuljahr, $schuljahrabhaengig = "") {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_GRUPPEN;

  $dbs = cms_verbinden('s');
  $schuljahre[0]['id'] = '-';
  $schuljahre[0]['bezeichnung'] = 'Schuljahrübergreifend';
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY beginn DESC");
  if ($sql->execute()) {
    $sql->bind_result($sjid, $sjbezeichnung);
    while ($sql->fetch()) {
      $SJ = array();
      $SJ['id'] = $sjid;
      $SJ['bezeichnung'] = $sjbezeichnung;
      array_push($schuljahre, $SJ);
    }
  }
  $sql->close();

  $zgruppenknoepfe = "";
  foreach ($CMS_GRUPPEN as $g) {
    if (strlen($zgruppenids[$g]) > 0) {
      $gk = cms_textzudb($g);
      $gids = "(".str_replace('|',',', substr($zgruppenids[$g],1)).")";
      if (cms_check_idliste($gids)) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $gk WHERE id IN $gids) AS x ORDER BY bezeichnung ASC");
        if ($sql->execute()) {
          $sql->bind_result($zgid, $zgbez);
          while ($sql->fetch()) {
            $zgruppenknoepfe .= "<span class=\"cms_toggle cms_toggle_aktiv\" id=\"cms_zugeordnet_".$gk."_".$zgid."\" onclick=\"cms_gruppe_abordnen('$gk', '$zgid')\">$g » $zgbez</span> ";
          }
        }
        $sql->close();
      }
    }
  }
  cms_trennen($dbs);

  // Zugeordnete Gruppen angeben
  $code = "<span id=\"cms_zuordnungsauswahl_zugeordnet\">$zgruppenknoepfe</span>";
  // Gruppenauswahl erzeugen
  $code .= "<span class=\"cms_button_ja\" onclick=\"cms_einblenden('cms_zuordnungsauswahl_feld')\"><span class=\"cms_hinweis\">Zuordnung hinzufügen</span>+</span>";
  $code .= "<div class=\"cms_gruppensuche_feld\" id=\"cms_zuordnungsauswahl_feld\" style=\"display: none;\">";
    $code .= "<span class=\"cms_fenster_schliessen cms_button_nein\" onclick=\"cms_ausblenden('cms_zuordnungsauswahl_feld')\"><span class=\"cms_hinweis\">Fenster schließen</span>×</span>";
    $code .= "<div class=\"cms_spalte_i\">";
      foreach ($CMS_GRUPPEN as $g) {
        $namek = cms_textzudb($g);
        $code .= "<input type=\"hidden\" id=\"cms_zuorndung_zugeordnetegruppen_$namek\" name=\"cms_zuorndung_zugeordnetegruppen_$namek\" value=\"".$zgruppenids[$g]."\">";
      }

      $code .= "<p><select id=\"cms_zuordnungsauswahl_schuljahr\" name=\"cms_zuordnungsauswahl_schuljahr\" onchange=\"cms_zuordnung_aktualisieren()\">";
      foreach ($schuljahre as $sj) {
        $code .= "<option value=\"".$sj['id']."\">".$sj['bezeichnung']."</option>";
      }
      $code .= "</select></p>";
      $code .= "<p><select id=\"cms_zuordnungsauswahl_gruppe\" name=\"cms_zuordnungsauswahl_gruppe\" onchange=\"cms_zuordnung_aktualisieren()\">";
      foreach ($CMS_GRUPPEN as $g) {
        $code .= "<option value=\"$g\">".$g."</option>";
      }
      $code .= "</select></p>";
      $code .= "<p id=\"cms_zuordnungsauswahl_ergebnisse\">";

      $code .= cms_zuordnungselemente($zgruppenids[$CMS_GRUPPEN[0]], $CMS_GRUPPEN[0], $schuljahre[0]['id']);

      $code .= "</p>";
    $code .= "</div>";
  $code .= "</div>";

  return $code;
}

function cms_zuordnungselemente($zgruppenids, $gruppe, $schuljahr) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_GRUPPEN;
  $code = "";

  $gruppek = cms_textzudb($gruppe);

  $dbs = cms_verbinden('s');
  $ergebnisse = array();
  $zugeordnet =  "(".str_replace('|', ',', $zgruppenids).")";
  if (cms_check_idliste($zugeordnet)) {$zugeordnet = "AND id NOT IN $zugeordnet";}
  else {$zugeordnet = "";}

  if ($schuljahr == '-') {
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $gruppek WHERE schuljahr IS NULL $zugeordnet) AS x ORDER BY bezeichnung ASC");
  }
  else {
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $gruppek WHERE schuljahr = ? $zugeordnet) AS x ORDER BY bezeichnung ASC");
    $sql->bind_param("i", $schuljahr);
  }

  $gruppeninfo = array();

  if ($sql->execute()) {
    $sql->bind_result($zgid, $zgbez);
    while ($sql->fetch()) {
      $ZG = array();
      $ZG['id'] = $zgid;
      $ZG['bezeichnung'] = $zgbez;
      array_push($gruppeninfo, $ZG);
    }
  }
  $sql->close();

  cms_trennen($dbs);

  foreach ($gruppeninfo as $g) {
    $code .= "<span id=\"cms_zuordnen_".$gruppek."_".$g['id']."\" class=\"cms_toggle\" onclick=\"cms_gruppe_zuordnen('$gruppek', '".$g['id']."')\">".$g['bezeichnung']."</span> ";
  }

  if (strlen($code) == 0) {
    $code .= "<span class=\"cms_notiz\">Keine Datensätze gefunden.</span> ";
  }

  return $code;
}
?>
