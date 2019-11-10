<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Schienen anlegen'] || $CMS_RECHTE['Planung']['Schienen bearbeiten'] || $CMS_RECHTE['Planung']['Schienen löschen'];

$code = "";
if ($zugriff) {
  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHIENESCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['SCHIENESCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Schienen</h1>";

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_schienen_vorbereiten($id)\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    $zeilen = "";
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th>Bezeichnung</th><th>Zeitraum</th><th>Kurse</th><th>Aktionen</th></tr>";
      $SCHIENEN = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT schienen.id, AES_DECRYPT(schienen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT(zeitraeume.bezeichnung, '$CMS_SCHLUESSEL') AS zbez, zeitraeume.beginn AS zeitraum FROM schienen JOIN zeitraeume ON schienen.zeitraum = zeitraeume.id WHERE zeitraeume.schuljahr = ?) AS x ORDER BY zeitraum, sbez ASC");
      $sql->bind_param('i', $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($schienenid, $schienenbez, $zeitraumbez, $zbeginn);
        while ($sql->fetch()) {
          $S = array();
          $S['id'] = $schienenid;
          $S['bez'] = $schienenbez;
          $S['zbez'] = $zeitraumbez;
          $S['kurse'] = array();
          array_push($SCHIENEN, $S);
        }
      }
      $sql->close();

      $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez FROM kurse JOIN schienenkurse ON kurse.id = schienenkurse.kurs WHERE schiene = ?) AS x ORDER BY kbez ASC");
      for ($i=0; $i<count($SCHIENEN); $i++) {
        $sql->bind_param('i', $SCHIENEN[$i]['id']);
        if ($sql->execute()) {
          $sql->bind_result($kursbez);
          while ($sql->fetch()) {
            array_push($SCHIENEN[$i]['kurse'], $kursbez);
          }
        }
      }
      $sql->close();

      foreach ($SCHIENEN AS $S) {
        $code .= "<tr>";
          $code .= "<td><img src=\"res/icons/klein/schienen.png\"></td>";
          $code .= "<td>".$S['bez']."</td>";
          $code .= "<td>".$S['zbez']."</td>";
          $code .= "<td>".implode(", ", $S['kurse'])."</td>";
          $code .= "<td>";
          if ($CMS_RECHTE['Planung']['Schienen bearbeiten']) {
            $code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schienen_bearbeiten_vorbereiten(".$S['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
          }
          if ($CMS_RECHTE['Planung']['Schienen löschen']) {
            $code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schienen_loeschen_anzeigen('".$S['bez']."', ".$S['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
          }
          $code .= "</td>";
        $code .= "</tr>";
      }

      if (count($SCHIENEN) == 0) {
        $code .= "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
      }

    $code .= "</table>";

    if ($CMS_RECHTE['Planung']['Schienen anlegen']) {
      $code .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Schienen/Neue_Schiene_anlegen\">+ Neue Schiene anlegen</a></p>";
    }


  }
  else {$code .= "<h1>Schienen</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Schienen</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
