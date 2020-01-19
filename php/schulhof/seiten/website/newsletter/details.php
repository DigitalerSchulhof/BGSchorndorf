<?php
function cms_newsletter_details_laden($id, $ziel) {
  global $CMS_SCHLUESSEL, $CMS_RECHTE, $CMS_EINSTELLUNGEN, $CMS_BENUTZERART, $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID, $CMS_GRUPPEN, $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL, $newsletteransehen;
  $code = "";

  $anlegen = $CMS_RECHTE['Website']['Newsletter anlegen'];
  $bearbeiten = $CMS_RECHTE['Website']['Newsletter bearbeiten'];
  $sehen = $CMS_RECHTE["Website"]["Newsletter Empfängerliste sehen"];

  $neu = $id == "-";

  $bezeichnung = 'Neuer Newsletter';
  foreach ($CMS_GRUPPEN as $g) {
    $zugeordnet[$g] = array();
    $zgruppenids[$g] = "";
  }

  $ansehen = isset($newsletteransehen) && $newsletteransehen;

  $dbs = cms_verbinden('s');
  if ($id != "-") {
	  $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM newslettertypen WHERE id = ?");
    $sql->bind_param("i", $id);
		if ($sql->execute()) {
      $sql->bind_result($bezeichnung);
			$sql->fetch();
		}
    $sql->close();
  }
  foreach ($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    $sql = $dbs->prepare("SELECT * FROM (SELECT gruppe AS id FROM ".$gk."newsletter JOIN $gk ON ".$gk."newsletter.gruppe = $gk.id WHERE newsletter = ?) AS x");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($zid);
      while ($sql->fetch()) {
        $zgruppenids[$g] .= "|".$zid;
      }
    }
    $sql->close();
  }

  if(($neu && $anlegen) || (!$neu && $bearbeiten)) {
    $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<h3>Newsletter</h3>";
      $code .= "<table class=\"cms_formular\">";
        $code .= "<tr><th>Bezeichnung</th><td><input id=\"cms_newsletter_bezeichnung\"".($ansehen?" disabled":"")." value=\"$bezeichnung\"></td></th>";
        if(!$ansehen) {
          $code .= "<tr><th>Zuordnung:</th><td class=\"cms_gruppensuche_feld_aussen\">";
            $code .= cms_zuordnungsauswahl_generieren($zgruppenids, 'gremien', $CMS_BENUTZERSCHULJAHR);
          $code .= "</td></tr>";
        }
      $code .= "</table>";

    if($ansehen) {
      $code .= "<p>";
      if($bearbeiten)
        $code .= "<span class=\"cms_button\" onclick=\"cms_link('Schulhof/Website/Newsletter/Newsletter_bearbeiten');\">Bearbeiten</span> ";
      $code .= "<a class=\"cms_button_nein\" href=\"$ziel\">Zurück</a></p>";
    } else {
      if ($neu && $anlegen) {
        $code .= "<p><span class=\"cms_button\" onclick=\"cms_newsletter_neu_speichern('$ziel');\">Speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
      }
      if (!$neu && $bearbeiten) {
        $code .= "<p><span class=\"cms_button\" onclick=\"cms_newsletter_bearbeiten_speichern('$ziel');\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
      }
    }

    $code .= "</div>";
    $code .= "<div class=\"cms_clear\"></div>";
  }

  if($sehen && !$neu) {
    $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<h3>Empfänger</h3>";
      $code .= "<table class=\"cms_liste\" id=\"cms_newsletter_empfaenger_liste\">";
        $code .= '<thead>';
          $code .= '<tr><th></th><th>Name</th><th>eMailadresse</th><th>Aktionen</th></tr>';
        $code .= "</thead>";
        $code .= '<tbody id="cms_verwaltung_newsletter_empfaenger">';
          $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(name, '$CMS_SCHLUESSEL') AS name, AES_DECRYPT(email, '$CMS_SCHLUESSEL') FROM newsletterempfaenger WHERE newsletter = ?) AS x ORDER BY name";
          $sql = $dbs->prepare($sql);
          $sql->bind_param("i", $id);
          $sql->bind_result($eid, $name, $mail);
          $sql->execute();
          while($sql->fetch())
            $code .= "<tr id=\"cms_newsletter_empfaenger_$eid\"><td><img src=\"res/icons/klein/newsletterempfaenger.png\"></td><td class=\"cms_newsletter_empfaenger_name\">$name</td><td class=\"cms_newsletter_empfaenger_mail\">$mail</td><td class=\"cms_newsletter_empfaenger_aktionen\">".(function() use ($CMS_RECHTE, $eid) {
              $aktionen = "";
              if($CMS_RECHTE["Website"]["Newsletter Empfänger bearbeiten"])
                $aktionen .= "<span class=\"cms_aktion_klein\" onclick=\"cms_newsletter_empfaenger_bearbeiten('".($eid)."')\"><span class=\"cms_hinweis\">Empfänger bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
              if($CMS_RECHTE["Website"]["Newsletter Empfänger löschen"])
                $aktionen .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_newsletter_empfaenger_loeschen_vorbereiten('".($eid)."')\"><span class=\"cms_hinweis\">Empfänger entfernen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
              return $aktionen;
            })()."</td></tr>";

          if(!$sql->num_rows)
            $code .= "<tr class=\"cms_leer\"><td colspan=\"4\" class=\"cms_notiz\">-- keine Empfänger vorhanden --</td></tr>";
        $code .= "</tbody>";
      $code .= "</table>";
      $codea = "";
      if($CMS_RECHTE["Website"]["Newsletter Empfänger anlegen"])
        $codea .= "<span class=\"cms_button_ja\" onclick=\"cms_newsletter_empfaenger_anlegen($id)\">+ Empfänger hinzufügen</span> ";
      if($CMS_RECHTE["Website"]["Newsletter Empfänger löschen"])
        $codea .= "<span class=\"cms_button_nein\" onclick=\"cms_newsletter_empfaenger_loeschen_alle_vorbereiten($id)\">Alle Empfänger entfernen</span> ";

      if(strlen($codea)) {
        $code .= "<p id=\"cms_empfaenger_aktionen\">".substr($codea, 0, -1)."</p>";
      }
    $code .= "</div>";
  }

  if($sehen && !$neu) {
    $num = 0;
    $sql = "SELECT COUNT(*) FROM newsletterempfaenger WHERE newsletter = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $id);
    $sql->bind_result($num);
    $sql->execute();
    $sql->fetch();

    $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<h3>Schreiben</h3>";
      $code .= "<textarea id=\"cms_newsletter_text\"></textarea>";
      $code .= "<span class=\"cms_button_ja\" onclick=\"cms_newsletter_senden($id)\">An alle Empfänger ($num) senden</span>";
    $code .= "</div>";
  }

  cms_trennen($dbs);

  if(strlen($code) < 1 || !cms_angemeldet()) {
    $code = cms_meldung_berechtigung();
  }
  return $code."</div>";
}

?>
