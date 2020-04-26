<?php
  include_once(dirname(__FILE__)."/../../../schulhof/funktionen/generieren.php");

  function cms_auffaellig_liste() {
    global $CMS_SCHLUESSEL;

    $ausgabe = "<h2>Auffälliges Verhalten</h2>".
               "<span class=\"cms_button cms_button_nein\" onclick=\"cms_auffaelliges_alle_loeschen_vorbereiten()\">Alle Meldungen löschen</span>";
    $ausgabe .= "<table class=\"cms_liste\">";
      $ausgabe .= "<thead>";
        $ausgabe .= "<tr><th></th><th>Benutzer</th><th>Typ</th><th>Aktion</th><th>Datum</th><th>Aktionen</th></tr>";
      $ausgabe .= "</thead>";
      $ausgabe .= "<tbody>";

      $dbs = cms_verbinden('s');
      $sql = $dbs->prepare("SELECT auffaelliges.id, typ, AES_DECRYPT(aktion, '$CMS_SCHLUESSEL'), zeitstempel, nutzerkonten.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), erstellt FROM auffaelliges LEFT JOIN personen ON ursacher = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE status = 0 ORDER BY zeitstempel DESC");
      $liste = "";
      if ($sql->execute()) {
        $sql->bind_result($aufid, $auftyp, $aufaktion, $aufzeit, $aufnutzer, $aufvor, $aufnach, $auftitel, $auferstellt);
        while ($sql->fetch()) {
          $liste .= '<tr>';
          $liste .= '<td><img src="res/icons/klein/auffaellig.png"></td>';
          if (($aufzeit >= $auferstellt) && ($aufnutzer !== null)) {
            $name = cms_generiere_anzeigename($aufvor, $aufnach, $auftitel);
          }
          else {
            $name = "Gelöschte Person";
          }
          $liste .= "<td>$name</td>";
          $liste .= "<td>".cms_auffaellig_typzutext($auftyp)."</td>";
          $liste .= "<td>".cms_auffaellig_dateizuaktion($aufaktion)."</td>";
          date_default_timezone_set("Europe/Berlin");
          $liste .= "<td>".date("d.m.Y H:i", $aufzeit)."</td>";

          $liste .= '<td>';
            $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_auffaelliges_details('$aufid');\"><span class=\"cms_hinweis\">Details anzeigen</span><img src=\"res/icons/klein/auffaelliges_information.png\"></span> ";
            if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
              $liste .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_auffaelliges_loeschen('$aufid');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
            }
          $liste .= '</td>';
          $liste .= '</tr>';
        }
        if (strlen($liste) == 0) {
          $liste .= "<tr><td colspan=\"6\" class=\"cms_notiz\">-- kein auffälliges Verhalten aufgenommen --</td></tr>";
        }
        $ausgabe .= $liste;
      }
      $sql->close();
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
    $aktionen = array(
      "php/schulhof/anfragen/gruppen/blogbearbeitenspeichern.php"               => "Internen Blogeintrag bearbeitet",
      "php/schulhof/anfragen/gruppen/blogneuspeichern.php"                      => "Internen Blogeintrag erstellt",
      "php/schulhof/anfragen/verwaltung/blogeintraege/bearbeitenspeichern.php"  => "Öffentlichen Blogeintrag bearbeitet",
      "php/schulhof/anfragen/verwaltung/blogeintraege/neuspeichern.php"         => "Öffentlichen Blogeintrag erstellt",
      "php/schulhof/anfragen/gruppen/terminbearbeitenspeichern.php"             => "Internen Termin bearbeitet",
      "php/schulhof/anfragen/gruppen/terminneuspeichern.php"                    => "Internen Termin erstellt",
      "php/schulhof/anfragen/verwaltung/termine/bearbeitenspeichern.php"        => "Öffentlichen Termin bearbeitet",
      "php/schulhof/anfragen/verwaltung/termine/neuspeichern.php"               => "Öffentlichen Termin erstellt",
      "php/schulhof/anfragen/verwaltung/galerien/bearbeitenspeichern.php"       => "Galerie bearbeitet",
      "php/schulhof/anfragen/verwaltung/galerien/neuspeichern.php"              => "Galerie erstellt",
      "php/schulhof/anfragen/verwaltung/dauerbrenner/bearbeitenspeichern.php"   => "Dauerbrenner bearbeitet",
      "php/schulhof/anfragen/verwaltung/dauerbrenner/neuspeichern.php"          => "Dauerbrenner erstellt",
      "php/schulhof/anfragen/hausmeister/neuspeichern.php"                      => "Hausmeisterauftrag erteilt",
      "php/schulhof/anfragen/nutzerkonto/postfach/versenden.php"                => "Nachricht versenden",
      "php/allgemein/anfragen/seitensuche.php"                                  => "Seitensuche"
    );

    foreach($aktionen as $d => $n) {
      if(endsWith($file, $d))
        return $n;
    }
    return $file;
  }

  function cms_auffaelliges_details($id) {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden("s");
    $sql = $dbs->prepare("SELECT auffaelliges.id, typ, AES_DECRYPT(aktion, '$CMS_SCHLUESSEL'), AES_DECRYPT(eingaben, '$CMS_SCHLUESSEL') AS eingaben, AES_DECRYPT(details, '$CMS_SCHLUESSEL') AS details, AES_DECRYPT(auffaelliges.notizen, '$CMS_SCHLUESSEL') AS notizen, zeitstempel, ursacher, nutzerkonten.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), erstellt FROM auffaelliges LEFT JOIN personen ON ursacher = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE auffaelliges.id = ?");

    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($aufid, $auftyp, $aufaktion, $aufeingaben, $aufdetails, $aufnotizen, $aufzeit, $aufursacher, $aufnutzer, $aufvorname, $aufnachname, $auftitel, $auferstellt);
      if($sql->fetch()) {
        if($aufursacher === null)
          $aufursacher = "Nicht angemeldet";
        else {
          $aufursacher = cms_generiere_anzeigename($aufvorname, $aufnachname, $auftitel);
        }
        if($aufeingaben == "") {$aufeingaben = "Keine Eingaben aufgezeichnet";}
        if($aufdetails == "") {$aufdetails = "Keine Details aufgezeichnet";}

        $code = "";
        $code .= "<div class=\"cms_spalte_40\">";
          $code .= "<div class=\"cms_spalte_i\">";
            $code .= "<h4>Allgemeine Details</h4>";
            $code .= "<table class=\"cms_formular\">";
              $code .= "<tbody>";
                $code .= "<tr>";
                  $code .= "<th>Ursacher:</th>";
                  $code .= "<td><input disabled value=\"$aufursacher\"></td>";
                $code .= "</tr>";
                $code .= "<tr>";
                  $code .= "<th>Typ:</th>";
                  $code .= "<td><input type=\"text\" disabled=\"disabled=\" value=\"".cms_auffaellig_typzutext($auftyp)."\"></td>";
                $code .= "</tr>";
                $code .= "<tr>";
                  $code .= "<th>Aktion:</th>";
                  $code .= "<td><input type=\"text\" disabled=\"disabled=\" value=\"".cms_auffaellig_dateizuaktion($aufaktion)."\"></td>";
                $code .= "</tr>";
                $code .= "<tr>";
                  $code .= "<th>Zeitpunkt:</th>";
                  date_default_timezone_set("Europe/Berlin");
                  $code .= "<td><input type=\"text\" disabled=\"disabled=\" value=\"".date("d.m.Y H:i:s", $aufzeit)."\"></td>";
                $code .= "</tr>";
              $code .= "</tbody>";
            $code .= "</table>";
          $code .= "</div>";
          $code .= "<div class=\"cms_spalte_i\">";
            $code .= "<h4>Notizen</h4>";
            $code .= "<div class=\"cms_formular\" style=\"padding: 5px;\">";
              $code .= "<textarea id=\"cms_auffaelliges_notizen\" rows=\"".textarearows($aufnotizen, 25)."\" style=\"resize: vertical; transition: none\">$aufnotizen</textarea><br><br>";
              $code .= "<span class=\"cms_button_ja\" onclick=\"cms_auffaelliges_notizen_speichern($aufid)\">Änderungen Speichern</span>";
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
                  $code .= "<td><textarea rows=\"".textarearows($aufeingaben, 25)."\" style=\"resize: none\" disabled=\"disabled=\">$aufeingaben</textarea></td>";
                $code .= "</tr>";
    /*            $code .= "<tr>";
                  $code .= "<th>Details:</th>";
                  $code .= "<td><textarea rows=\"".textarearows($aufdetails, 25)."\" style=\"resize: none\" disabled=\"disabled=\">$aufdetails</textarea></td>";
                $code .= "</tr>";*/
              $code .= "</tbody>";
            $code .= "</table>";
          $code .= "</div>";
        $code .= "<div class=\"cms_spalte_i\">";
      }
      else {return cms_meldung_bastler();}
    } else {return cms_meldung_bastler();}
    $sql->close();

    return $code;
  }

  function cms_auffaelliges_speichern($typ, $infos = array()) {
    global $CMS_SCHLUESSEL;
    $dbs = cms_verbinden('s');

    $id = cms_generiere_kleinste_id('auffaelliges');

    $CMS_BENUTZERID = $_SESSION["BENUTZERID"] ?? null;
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    for($i = 1; $i < count($trace); $i++) {
      if(!endsWith(($file = str_replace("\\", "/", $trace[$i]["file"])), "php/schulhof/funktionen/texttrafo.php") && !endsWith($file, "php/schulhof/funktionen/meldungen.php"))
        break;
    }
    $aktion = $file;
    if($typ === 0) {
      $aktion = $infos["pfad"] ?? "Fehler";
    }
    $eingaben = cms_array_leserlich($_POST, "\n");
    $details = "";
    $zeitstempel = time();
    $status = 0;

    $sql = $dbs->prepare("UPDATE auffaelliges SET ursacher = ?, typ = ?, aktion = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), eingaben = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), details = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), zeitstempel = ?, status = ? WHERE id = ?");
    $sql->bind_param("iisssiii", $CMS_BENUTZERID, $typ, $aktion, $eingaben, $details, $zeitstempel, $status, $id);
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
