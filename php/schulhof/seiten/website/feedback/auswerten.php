<?php
  function cms_feedback_liste() {
    global $CMS_SCHLUESSEL, $CMS_RECHTE;
    $ausgabe = "<h2>Feedback</h2><table class=\"cms_liste\">";
      $ausgabe .= "<thead>";
        $ausgabe .= "<tr><th></th><th>Name</th><th>Feedback</th><th>Datum</th><th>Aktionen</th></tr>";
      $ausgabe .= "</thead>";
      $ausgabe .= "<tbody>";

      $dbs = cms_verbinden('s');
      $sql = "SELECT id, AES_DECRYPT(name, '$CMS_SCHLUESSEL') AS name, AES_DECRYPT(feedback, '$CMS_SCHLUESSEL') AS feedback, zeitstempel FROM feedback ORDER BY zeitstempel DESC";
      $liste = "";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $liste .= '<tr>';
          $liste .= '<td><img src="res/icons/klein/feedback.png"></td>';
          $liste .= "<td style=\"overflow: hidden; text-overflow: ellipsis; white-space: nowrap;\" alt=\"".$daten["name"]."\">".(substr($daten["name"], 0, strpos(wordwrap($daten["name"], 80), "\n")==0?80:strpos(wordwrap($daten["name"], 80), "\n"))."...")."</td>";
          $liste .= "<td style=\"overflow: hidden; text-overflow: ellipsis; white-space: nowrap;\" alt=\"".$daten["feedback"]."\">".(substr($daten["feedback"], 0, strpos(wordwrap($daten["feedback"], 80), "\n")==0?80:strpos(wordwrap($daten["feedback"], 80), "\n"))."...")."</td>";
          date_default_timezone_set("Europe/Berlin");
          $liste .= "<td>".date("d.m.Y", $daten["zeitstempel"])."</td>";

          $liste .= '<td>';
            if ($CMS_RECHTE['Website']['Feedback verwalten']) {
              $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_feedback_loeschen('".$daten['id']."');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/feedback_loeschen.png\"></span> ";
            }
            $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_feedback_details('".$daten['id']."');\"><span class=\"cms_hinweis\">Details anzeigen</span><img src=\"res/icons/klein/feedback_information.png\"></span> ";
          $liste .= '</td>';
          $liste .= '</tr>';
        }

        $anfrage->free();
        if (strlen($liste) == 0) {
          $liste .= "<tr><td colspan=\"5\" class=\"cms_notiz\">-- kein Feedback vorhanden --</td></tr>";
        }
        $ausgabe .= $liste;
      }

    $ausgabe .= "</tbody>";
    $ausgabe .= "</table>";
    return $ausgabe;
  }

  function cms_feedback_details($id) {
    global $CMS_SCHLUESSEL, $CMS_RECHTE;
    $dbs = cms_verbinden("s");
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(name, '$CMS_SCHLUESSEL') AS name, AES_DECRYPT(feedback, '$CMS_SCHLUESSEL') AS feedback, zeitstempel FROM feedback WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      if(is_null($sqld = $sql->get_result()->fetch_assoc()))
        return cms_meldung_bastler();
    } else {return cms_meldung_bastler();}
    $sql->close();

    if($sqld["feedback"] == "")
      $sqld["feedback"] = "Kein Feedback vorhanden";
    if($sqld["name"] == "")
      $sqld["name"] = "Unbekannt";
    $code = "";
        $code .= "<h4>Allgemeine Details</h4>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
              $code .= "<th>Name:</th>";
              $code .= "<td><input disabled value=\"".$sqld["name"]."\"></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Feedback:</th>";
              $code .= "<td><textarea rows=\"".(intval(strlen($sqld["feedback"])/25)+1)."\" style=\"resize: none\" disabled>".$sqld["feedback"]."</textarea></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Zeitpunkt:</th>";
              date_default_timezone_set("Europe/Berlin");
              $code .= "<td><input disabled value=\"".date("d.m.Y H:i:s", $sqld["zeitstempel"])."\"></td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
    return $code;
  }
?>
