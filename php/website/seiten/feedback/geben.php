<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
</div>
<?php
  $fehleraktiv = $CMS_EINSTELLUNGEN['Fehlermeldung aktiv'] == "1";
  $feedbackaktiv = $CMS_EINSTELLUNGEN['Feedback aktiv'] == "1";
  $fehleranmeldung = $CMS_EINSTELLUNGEN["Fehlermeldung Anmeldung notwendig"] == "0" || ($CMS_EINSTELLUNGEN["Fehlermeldung Anmeldung notwendig"] == "1" && $CMS_ANGEMELDET);
  $feedbackanmeldung = $CMS_EINSTELLUNGEN["Feedback Anmeldung notwendig"] == "0" || ($CMS_EINSTELLUNGEN["Feedback Anmeldung notwendig"] == "1" && $CMS_ANGEMELDET);
  $fehlerzugriff = $fehleraktiv && $fehleranmeldung;
  $feedbackzugriff = $feedbackaktiv && $feedbackanmeldung;
  if($fehlerzugriff) {
    $url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"";
    $header = urlencode(json_encode(getallheaders(), true));
    $code = "";

    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
      $code .= "<h1>Fehler melden</h1>";

      $code .= cms_meldung('info', '<p>Vielen Dank, dass Sie das System verbessern möchten.</p><p>Bitte geben Sie anschließend einen <b>knappen</b>, <b>schlagwortartigen</b> Fehlertitel und eine <b>ausführliche</b> Fehlerbeschreibung an! Je mehr Informationen zum aufgetretenen Fehler bekannt sind, desto einfacher lässt er sich beheben. Bitte gehen Sie dabei auch auf die Vorgeschichte des Fehlers ein, denn manche Funktionen des Systems erfordern Vorbereitungen, die nicht zwingend unmittelbar vor dem Fehler getroffen werden.</p><p>Zur Behebung des Fehlers kann der Sytemzustand sehr aufschlussreich sein. Diese Daten auszulesen zu können wäre daher hilfreich. Sie werden an keine Dritten weitergeben.</p>');

        $code .= "<input type=\"hidden\" id=\"cms_fehlermeldung_url\" value=\"$url\">";
        $code .= "<input type=\"hidden\" id=\"cms_fehlermeldung_header\" value=\"$header\">";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
            $code .= "<th>Titel:</th>";
              $code .= "<td><input id=\"cms_fehlermeldung_titel\" type=\"text\"></input></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Beschreibung:</th>";
              $code .= "<td><textarea style=\"resize: vertical; transition: none;\" id=\"cms_fehlermeldung_beschreibung\"></textarea></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Systemzustand übermitteln:</th>";
              $code .= "<td>".cms_schieber_generieren('fehlermeldung_okay', 0)."</td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
      $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_fehlermeldung_einhanden()\">Fehler melden</span> <span class=\"cms_button_nein\" onclick=\"cms_link('')\">Abbrechen</span></p>";
    $code .= "</div></div>";
    echo $code;
  }
  if($feedbackzugriff) {
    $code = "";

    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= "<h1>Feedback geben</h1>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tbody>";
            $code .= "<tr>";
              $code .= "<th>Name:</th>";
              $code .= "<td><input id=\"cms_feedback_name\" type=\"text\"></input></td>";
            $code .= "</tr>";
            $code .= "<tr>";
              $code .= "<th>Feedback:</th>";
              $code .= "<td><textarea style=\"resize: vertical; transition: none;\" id=\"cms_feedback_beschreibung\"></textarea></td>";
            $code .= "</tr>";
          $code .= "</tbody>";
        $code .= "</table>";
      $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_feedback_einhanden()\">Feedback geben</span> <span class=\"cms_button_nein\" onclick=\"cms_link('')\">Abbrechen</span></p>";
    $code .= "</div></div>";
    echo $code;
  }

  if(!$fehleraktiv && !$feedbackaktiv) {
    echo cms_meldung("fehler", "<h4>Zugriff verweigert</h4><p>Sie sind nicht berechtigt, diese Seite zu sehen!</p>");
  } elseif(!$fehleranmeldung && !$feedbackanmeldung){
    echo cms_meldung("fehler", "<h4>Zugriff verweigert</h4><p>Um auf diese Seite zuzugreifen, müssen Sie angemeldet sein!</p>");
  }
?>
<div class="cms_clear"></div>
