<?php
  /*
    Status -1 => Löschen
    Status 0 => Offen
    Status 1 => Wird bearbeitet
    Status 2 => Behoben
   */
  function cms_fehlermeldungen_liste() {
    global $CMS_SCHLUESSEL, $CMS_RECHTE;
    $ausgabe = "<h2>Fehlermeldungen</h2><table class=\"cms_liste\">";
      $ausgabe .= "<thead>";
        $ausgabe .= "<tr><th></th><th>Bezeichnung</th><th>Beschreibung</th><th>Datum</th><th>Status</th><th>Aktionen</th></tr>";
      $ausgabe .= "</thead>";
      $ausgabe .= "<tbody>";

      $dbs = cms_verbinden('s');
      $sql = "SELECT id, ersteller, AES_DECRYPT(url, '$CMS_SCHLUESSEL') AS url, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, zeitstempel, status FROM fehlermeldungen WHERE sichtbar = '1' ORDER BY status ASC, zeitstempel DESC";
      $liste = "";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $liste .= '<tr class=\"cms_fehlermeldung_'.$daten["status"].'\">';
          if($daten["status"] == "2")
            $liste .= '<td><img src="res/icons/klein/fehlermeldung_behoben.png"></td>';
          else
            $liste .= '<td><img src="res/icons/klein/fehlermeldung.png"></td>';
          $liste .= "<td title=\"".$daten["titel"]."\" style=\"word-break: break-word;\">".$daten["titel"]."</td>";
          $liste .= "<td title=\"".$daten["beschreibung"]."\" style=\"word-break: break-word;\">".$daten["beschreibung"]."</td>";
          date_default_timezone_set("Europe/Berlin");
          $liste .= "<td title=\"".date("d.m.Y H:i:s", $daten["zeitstempel"])."\">".date("d.m.Y", $daten["zeitstempel"])."</td>";
          $statusT = "Fehler";
          $statusI = "fehlermeldung";
          switch ($daten["status"]) {
            case 0:
              $statusT = "Offen"; $statusI = "rot";
              break;
            case 1:
              $statusT = "Wird bearbeitet"; $statusI = "gelb";
              break;
            case 2:
              $statusT = "Behoben"; $statusI = "gruen";
              break;
          }
          $liste .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$statusT</span><img src=\"res/icons/klein/".$statusI.".png\"></span> ";

          $liste .= '<td>';
            if ($CMS_RECHTE['Website']['Fehlermeldungen verwalten']) {
              $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_fehlermeldung_status_setzen('".$daten['id']."', '2');\"><span class=\"cms_hinweis\">Als behoben markieren</span><img src=\"res/icons/klein/fehlermeldung_beheben.png\"></span> ";
              $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_fehlermeldung_status_setzen('".$daten['id']."', '1');\"><span class=\"cms_hinweis\">Als in Bearbeitung markieren</span><img src=\"res/icons/klein/fehlermeldung_bearbeitung.png\"></span> ";
              $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_fehlermeldung_status_setzen('".$daten['id']."', '0');\"><span class=\"cms_hinweis\">Als offen markieren</span><img src=\"res/icons/klein/fehlermeldung_oeffnen.png\"></span> ";
              // -1 => Löschen
              $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_fehlermeldung_status_setzen('".$daten['id']."', '-1');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/fehlermeldung_loeschen.png\"></span> ";
            }
            $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_fehlermeldung_details('".$daten['id']."');\"><span class=\"cms_hinweis\">Details anzeigen</span><img src=\"res/icons/klein/fehlermeldung_information.png\"></span> ";
          $liste .= '</td>';
          $liste .= '</tr>';
        }

        $anfrage->free();
        if (strlen($liste) == 0) {
//          $liste .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Fehler vorhanden --</td></tr>";
//                                                                                Doch, es gibt immer noch viele!
          $liste .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Fehler gemeldet --</td></tr>";
        }
        $ausgabe .= $liste;
      }

    $ausgabe .= "</tbody>";
    $ausgabe .= "</table>";
    return $ausgabe;
  }

  function cms_fehlermeldung_details($id) {
    global $CMS_SCHLUESSEL, $CMS_RECHTE;
    $dbs = cms_verbinden("s");
    $weilreference1 = 1;
    $sql = $dbs->prepare("SELECT id, ersteller, AES_DECRYPT(url, '$CMS_SCHLUESSEL') AS url, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(header, '$CMS_SCHLUESSEL') AS header, AES_DECRYPT(session, '$CMS_SCHLUESSEL') AS session, AES_DECRYPT(notizen, '$CMS_SCHLUESSEL') AS notizen, zeitstempel, status FROM fehlermeldungen WHERE id = ? AND sichtbar = ?");
    $sql->bind_param("ii", $id, $weilreference1);
    if ($sql->execute()) {
      if(is_null($sqld = $sql->get_result()->fetch_assoc()))
        return cms_meldung_bastler();
    } else {return cms_meldung_bastler();}
    $sql->close();

    if($sqld["beschreibung"] == "")
      $sqld["beschreibung"] = "Keine Beschreibung vorhanden";
    if($sqld["ersteller"] == "")
      $sqld["ersteller"] = "Unbekannt";
    if($sqld["url"] == "")
      $sqld["url"] = "Keine URL vorhanden";
    $code = "";
    $code .= cms_meldung('warnung', '<h4>Nutzerdaten</h4><p>Alle hier gelisteten Daten sind von einem Besucher der Website eingegeben worden und können mitunter frei erfunden sein.</p>');

    $code .= "<div class=\"cms_spalte_40\">";
      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<h4>Allgemeine Details</h4>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
              $code .= "<th>Bezeichnung:</th>";
              $code .= "<td><input disabled value=\"".$sqld["titel"]."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Beschreibung:</th>";
              $code .= "<td><textarea rows=\"".textarearows($sqld["beschreibung"], 25)."\" style=\"resize: none\" disabled>".$sqld["beschreibung"]."</textarea></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Zeitpunkt der Meldung:</th>";
              date_default_timezone_set("Europe/Berlin");
              $code .= "<td><input disabled value=\"".date("d.m.Y H:i:s", $sqld["zeitstempel"])."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Ersteller der Meldung:</th>";
              $ersteller = $sqld["ersteller"];
              if($id != "") {
                $sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id = $ersteller";
                if ($anfrage = $dbs->query($sql)) {
                  if ($daten = $anfrage->fetch_assoc()) {
                    $vorname = $daten['vorname'];
                    $nachname = $daten['nachname'];
                    $titel = $daten['titel'];
                    $ersteller = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
                    $fehler = false;
                  }
                  $anfrage->free();
                }
              }
              $code .= "<td><input disabled value=\"".$ersteller."\"></td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
      $code .= "</div>";
      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<h4>Notizen</h4>";
        $code .= "<div class=\"cms_formular\" style=\"padding: 5px;\">";
          $code .= "<textarea id=\"cms_fehlermeldung_notizen\" rows=\"".textarearows($sqld["notizen"], 25)."\" style=\"resize: vertical; transition: none\">".$sqld["notizen"]."</textarea><br><br>";
          $code .= "<span class=\"cms_button_ja\" onclick=\"cms_fehlermeldung_notizen_speichern(".$sqld["id"].")\">Änderungen Speichern</span>";
        $code .= "</div>";
      $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_60\">";
      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<h4>Technische Details</h4>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
              $code .= "<th>ID:</th>";
              $code .= "<td><input disabled value=\"".$sqld["id"]."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>URL des Aufrufs:</th>";
              $code .= "<td><span class=\"cms_feld_hinweis\"><input disabled value=\"".$sqld["url"]."\"><span class=\"cms_hinweis\" style=\"max-width: unset;\">".$sqld["url"]."</span></span></td>";
              $code .= "<td><span class=\"cms_aktion_klein\" onclick=\"window.location.href = ('".$sqld["url"]."')\"><span class=\"cms_hinweis\">Zur Seite</span><img src=\"res/icons/klein/springen.png\"></span>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Zeitstempel:</th>";
              $code .= "<td><input disabled value=\"".$sqld["zeitstempel"]."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>HTTP Header: <span class=\"cms_hinweis_aussen\"><span class=\"cms_hinweis\">Ein HTTP Header ist eine Information, die automatisch vom Benutzer gesendet wird und Informationen über den Browser etc. enthält.</span></span></span></th>";
              $code .= "<td><textarea rows=\"".textarearows($sqld["header"], 60)."\" style=\"resize: none\" disabled>".$sqld["header"]."</textarea></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Session Cookie: <span class=\"cms_hinweis_aussen\"><span class=\"cms_hinweis\">Der Session Cookie enthält Informationen üben den Benutzer, sofern dieser angemeldet ist.</span></span></span></th>";
              $code .= "<td><textarea rows=\"".textarearows($sqld["session"], 200)."\" style=\"resize: none\" disabled>".$sqld["session"]."</textarea></td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
      $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_i\">";
    if($CMS_RECHTE['Website']['Fehlermeldungen verwalten'])
      $code .= "<a class=\"cms_button_ja\" onclick=\"cms_fehlermeldung_status_setzen($id, 2)\">Als behoben markieren</a> ";
    return $code;
  }

  function textarearows($t, $i) {
    $r = 1;
    foreach(explode("\n", $t) as $s)
      $r += ($i != 0?(strlen($s) / $i):0) + 1;
    return floor($r);
  }
?>
