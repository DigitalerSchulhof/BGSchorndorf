<?php
  function cms_auffaellig_liste() {
    global $CMS_SCHLUESSEL, $CMS_RECHTE;

    $ausgabe = "<h2>Auffälliges Verhalten</h2><table class=\"cms_liste\">";
      $ausgabe .= "<thead>";
        $ausgabe .= "<tr><th></th><th>Benutzer</th><th>Typ</th><th>Aktion</th><th>Datum</th><th>Aktionen</th></tr>";
      $ausgabe .= "</thead>";
      $ausgabe .= "<tbody>";

      $dbs = cms_verbinden('s');
      $sql = "SELECT id, ursacher, typ, AES_DECRYPT(aktion, '$CMS_SCHLUESSEL') AS aktion, zeitstempel FROM auffaelliges WHERE status = 0 ORDER BY zeitstempel DESC";
      $liste = "";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $liste .= '<tr>';
          $liste .= '<td><img src="res/icons/klein/auffaellig.png"></td>';
          $nameSql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') as vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') as nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') as titel FROM personen WHERE id=".intval($daten["ursacher"].";");
          $name = "Gelöschte Person";
          if ($a = $dbs->query($nameSql))
          	if($d = $a->fetch_assoc())
              $name = cms_generiere_anzeigename($d["vorname"], $d["nachname"], $d["titel"]);
          $liste .= "<td alt=\"$name\">$name</td>";
          $liste .= "<td alt=\"".cms_auffaellig_typzutext($daten["typ"])."\">".cms_auffaellig_typzutext($daten["typ"])."</td>";
          $liste .= "<td>".cms_auffaellig_dateizuaktion($daten["aktion"])."</td>";
          date_default_timezone_set("Europe/Berlin");
          $liste .= "<td>".date("d.m.Y", $daten["zeitstempel"])."</td>";

          $liste .= '<td>';
            if ($CMS_RECHTE['Website']['Auffälliges verwalten']) {
              $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_auffaelliges_loeschen('".$daten['id']."');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/auffaelliges_loeschen.png\"></span> ";
            }
            $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_auffaelliges_details('".$daten['id']."');\"><span class=\"cms_hinweis\">Details anzeigen</span><img src=\"res/icons/klein/auffaelliges_information.png\"></span> ";
          $liste .= '</td>';
          $liste .= '</tr>';
        }

        $anfrage->free();
        if (strlen($liste) == 0) {
          $liste .= "<tr><td colspan=\"6\" class=\"cms_notiz\">-- kein auffälliges Verhalten aufgenommen --</td></tr>";
        }
        $ausgabe .= $liste;
      }

    $ausgabe .= "</tbody>";
    $ausgabe .= "</table>";
    return $ausgabe;
  }

  function cms_auffaellig_typzutext($typ) {
    switch($typ) {
      case 0:
        return "Unbefugter Aufruf";
      case 1:
        return "Wiederholter Aufruf";
      case 2:
        return "Programmcode";
    }
    return "Unbekannter Typ";
  }

  function cms_auffaellig_dateizuaktion($file) {
    $aktion = $file;
    if(endsWith($file, "php/schulhof/anfragen/gruppen/blogbearbeitenspeichern.php"))
      $aktion = "Interner Blogeintrag bearbeitet";
    else if(endsWith($file, "php/schulhof/anfragen/gruppen/blogneuspeichern.php"))
      $aktion = "Interner Blogeintrag erstellt";
    else if(endsWith($file, "php/schulhof/anfragen/gruppen/terminbearbeitenspeichern.php"))
      $aktion = "Interner Termin erstellt";
    else if(endsWith($file, "php/schulhof/anfragen/gruppen/terminneuspeichern.php"))
      $aktion = "Interner Termin bearbeitet";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/dauerbrenner/bearbeitenspeichern.php"))
      $aktion = "Dauerbrenner bearbeitet";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/dauerbrenner/neuspeichern.php"))
      $aktion = "Dauerbrenner erstellt";
    else if(endsWith($file, "php/schulhof/anfragen/hausmeister/neuspeichern.php"))
      $aktion = "Hausmeisterauftrag eingerichtet";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/blogeintraege/bearbeitenspeichern.php"))
      $aktion = "Blogeintrag bearbeitet";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/blogeintraege/neuspeichern.php"))
      $aktion = "Blogeintrag erstellt";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/termine/bearbeitenspeichern.php"))
      $aktion = "Termin bearbeitet";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/termine/neuspeichern.php"))
      $aktion = "Termin erstellt";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/galerien/bearbeitenspeichern.php"))
      $aktion = "Galerie bearbeitet";
    else if(endsWith($file, "php/schulhof/anfragen/verwaltung/galerien/neuspeichern.php"))
      $aktion = "Galerie erstellt";

    return $aktion;
  }

  function cms_auffaelliges_details($id) {
    global $CMS_SCHLUESSEL, $CMS_RECHTE;
    $dbs = cms_verbinden("s");
    $sql = $dbs->prepare("SELECT id, ursacher, typ, AES_DECRYPT(aktion, '$CMS_SCHLUESSEL') AS aktion, AES_DECRYPT(eingaben, '$CMS_SCHLUESSEL') AS eingaben, AES_DECRYPT(details, '$CMS_SCHLUESSEL') AS details, AES_DECRYPT(notizen, '$CMS_SCHLUESSEL') AS notizen, zeitstempel FROM auffaelliges WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      if(is_null($sqld = $sql->get_result()->fetch_assoc()))
        return cms_meldung_bastler();
    } else {return cms_meldung_bastler();}
    $sql->close();

    if($sqld["ursacher"] == -1)
      $sqld["ursacher"] = "Nicht angemeldet";
    else {
      $nameSql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') as vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') as nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') as titel FROM personen WHERE id=".intval($sqld["ursacher"].";");
      if ($a = $dbs->query($nameSql))
        if($d = $a->fetch_assoc())
          $sqld["ursacher"] = cms_generiere_anzeigename($d["vorname"], $d["nachname"], $d["titel"]);
      }
    if($sqld["eingaben"] == "")
      $sqld["eingaben"] = "Keine Eingaben aufgezeichnet";
    if($sqld["details"] == "")
      $sqld["details"] = "Keine Details aufgezeichnet";

    $code = "";
    $code .= "<div class=\"cms_spalte_40\">";
      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<h4>Allgemeine Details</h4>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
              $code .= "<th>Ursacher:</th>";
              $code .= "<td><input disabled value=\"".$sqld["ursacher"]."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Typ:</th>";
              $code .= "<td><input title=\"".$sqld["typ"]."\" disabled value=\"".cms_auffaellig_typzutext($sqld["typ"])."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Aktion:</th>";
              $code .= "<td><input title=\"".$sqld["aktion"]."\" disabled value=\"".cms_auffaellig_dateizuaktion($sqld["aktion"])."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Zeitpunkt:</th>";
              date_default_timezone_set("Europe/Berlin");
              $code .= "<td><input title=\"".$sqld["zeitstempel"]."\" disabled value=\"".date("d.m.Y H:i:s", $sqld["zeitstempel"])."\"></td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
      $code .= "</div>";
      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<h4>Notizen</h4>";
        $code .= "<div class=\"cms_formular\" style=\"padding: 5px;\">";
          $code .= "<textarea id=\"cms_auffaelliges_notizen\" rows=\"".textarearows($sqld["notizen"], 25)."\" style=\"resize: vertical; transition: none\">".$sqld["notizen"]."</textarea><br><br>";
          $code .= "<span class=\"cms_button_ja\" onclick=\"cms_auffaelliges_notizen_speichern(".$sqld["id"].")\">Änderungen Speichern</span>";
        $code .= "</div>";
      $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_60\">";
      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<h4>Details</h4>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
              $code .= "<th>Eingaben:</th>";
              $code .= "<td><textarea rows=\"".textarearows($sqld["eingaben"], 25)."\" style=\"resize: none\" disabled>".$sqld["eingaben"]."</textarea></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Details:</th>";
              $code .= "<td><textarea rows=\"".textarearows($sqld["details"], 25)."\" style=\"resize: none\" disabled>".$sqld["details"]."</textarea></td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
      $code .= "</div>";
    $code .= "<div class=\"cms_spalte_i\">";

    return $code;
  }

  function cms_auffaelliges_speichern($typ) {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden('s');

    $id = cms_generiere_kleinste_id('auffaelliges');

    $ursacher = is_null($_SESSION["BENUTZERID"])?-1:$_SESSION["BENUTZERID"];

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $file = str_replace("\\", "/", $trace[1]["file"]);


    $aktion = $file;
    $eingaben = cms_array_leserlich($_POST, "\n");
    $details = "";   // TODO
    $zeitstempel = time();
    $status = 0;

    $sql = $dbs->prepare("UPDATE auffaelliges SET ursacher = ?, typ = ?, aktion = ?, eingaben = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), details = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), zeitstempel = ?, status = ? WHERE id = ?");
    $sql->bind_param("iisssiii", $ursacher, $typ, $aktion, $eingaben, $details, $zeitstempel, $status, $id);
    $sql->execute();
    $sql->close();
  }

  function endsWith($haystack, $needle) {
    if(strlen($needle)<1) return false;
    return substr($haystack, -strlen($needle))===$needle;
  }

  function textarearows($t, $i) {
    $r = 1;
    foreach(explode("\n", $t) as $s)
      $r += ($i != 0?(strlen($s) / $i):0) + 1;
    return floor($r);
  }
?>