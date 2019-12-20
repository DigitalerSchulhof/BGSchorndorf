<?php
  function cms_chatmeldungen_liste() {
    global $CMS_SCHLUESSEL, $CMS_RECHTE, $CMS_GRUPPEN;

    $ausgabe = "<h2>Chatmeldungen</h2><table class=\"cms_liste\">";
      $ausgabe .= "<thead>";
        $ausgabe .= "<tr><th></th><th>Gruppe</th><th>Melder</th><th>Meldezeitpunkt</th><th>Sender</th><th>Nachricht</th><th>Aktionen</th></tr>";
      $ausgabe .= "</thead>";
      $ausgabe .= "<tbody>";

      $dbs = cms_verbinden('s');

      $sql = "";
      foreach($CMS_GRUPPEN as $i => $g) {
        $gk = cms_textzudb($g);
        $sql .= "(SELECT '$g' as gruppe, chat.id as id, meldezeitpunkt, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL') as nachricht, AES_DECRYPT(melder.titel, '$CMS_SCHLUESSEL') as meldertitel, AES_DECRYPT(melder.vorname, '$CMS_SCHLUESSEL') as meldervorname, AES_DECRYPT(melder.nachname, '$CMS_SCHLUESSEL') as meldernachname, AES_DECRYPT(sender.titel, '$CMS_SCHLUESSEL') as sendertitel, AES_DECRYPT(sender.vorname, '$CMS_SCHLUESSEL') as sendervorname, AES_DECRYPT(sender.nachname, '$CMS_SCHLUESSEL') as sendernachname, AES_DECRYPT(gruppe.bezeichnung, '$CMS_SCHLUESSEL') as bezeichnung FROM $gk"."chatmeldungen as meldung JOIN $gk"."chat as chat ON chat.id = meldung.nachricht JOIN personen as melder ON melder.id = meldung.melder JOIN personen as sender ON sender.id = chat.person JOIN $gk as gruppe ON gruppe.id = chat.gruppe ORDER BY meldezeitpunkt DESC) UNION";
      }
      $sql = $dbs->prepare(substr($sql, 0, -5));
      $liste = "";
      if ($sql->execute()) {
        $sql->bind_result($cgruppe, $cid, $cmeldezeitpunkt, $cnachricht, $cmtit, $cmvor, $cmnach, $cstit, $csvor, $csnach, $cbezeichnung);
        while ($sql->fetch()) {
          $liste .= '<tr>';
          $liste .= '<td><img src="res/icons/klein/chatmeldungen.png"></td>';
          $liste .= "<td>$cgruppe » $cbezeichnung</td>";
          $liste .= "<td>".cms_generiere_anzeigename($cmvor, $cmnach, $cmtit)."</td>";
          $liste .= "<td>".date("d.m.Y G:i", $cmeldezeitpunkt)."</td>";
          $liste .= "<td>".cms_generiere_anzeigename($csvor, $csnach, $cstit)."</td>";
          $liste .= "<td>".$cnachricht."</td>";
          $liste .= '<td>';
          if ($CMS_RECHTE['Gruppen']['Chatmeldungen verwalten'])
            $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_chatmeldung_loeschen('".$cgruppe."', '".$cid."');\"><span class=\"cms_hinweis\">Meldungen dieser Nachricht löschen</span><img src=\"res/icons/klein/auffaelliges_loeschen.png\"></span> ";
          if ($CMS_RECHTE['Gruppen']['Chatmeldungen verwalten'])
            $liste .= "<span class=\"cms_aktion_klein\" onclick=\"cms_chatmeldung_nachricht_loeschen('".$cgruppe."', '".$cid."');\"><span class=\"cms_hinweis\">Nachricht in der Gruppe löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
          $liste .= '</td>';
          $liste .= '</tr>';
        }
        if (strlen($liste) == 0) {
          $liste .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- kein Nachrichten gemeldet --</td></tr>";
        }
        $ausgabe .= $liste;
      }
      $sql->close();

    $ausgabe .= "</tbody>";
    $ausgabe .= "</table>";
    return $ausgabe;
  }
?>
