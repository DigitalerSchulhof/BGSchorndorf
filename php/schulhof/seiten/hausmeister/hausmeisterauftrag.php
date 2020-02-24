<?php
function cms_neuerhausmeisterauftrag($dbs, $app = "nein") {
  global $CMS_RECHTE, $CMS_SCHLUESEL;
  if ($CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen']) {
    $spalten = 2;
    $auftraege = "";
    $beschreibung = "";
    $titel = "";
    $auftraegezugehoerigkeit = "<input type=\"hidden\" name=\"cms_hausmeisterauftrag_zugehoerig\" id=\"cms_hausmeisterauftrag_zugehoerig\" value=\"\">";
    $auftraege .= "<h2>Hausmeisterauftrag</h2>";
    $auftraege .= "<table class=\"cms_formular\">";
      if (isset($_SESSION['GERAETEPROBLEMDELIGIERENID']) && isset($_SESSION['GERAETEPROBLEMDELIGIERENART'])) {
        $gart = $_SESSION['GERAETEPROBLEMDELIGIERENART'];
        $gid = $_SESSION['GERAETEPROBLEMDELIGIERENID'];
        if (($gart == 'r') || ($gart == 'l')) {
          if ($gart == 'r') {
            $sql = $dbs->prepare("SELECT AES_DECRYPT(raeumegeraete.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(meldung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') FROm raeumegeraete JOIN raeume ON standort = raeume.id WHERE raeumegeraete.id = ?");
          }
          else if ($gart == 'l') {
            $sql = $dbs->prepare("SELECT AES_DECRYPT(leihengeraete.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(meldung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') FROm leihengeraete JOIN leihen ON standort = leihen.id WHERE leihengeraete.id = ?");
          }
          $sql->bind_param("i", $gid);
          if ($sql->execute()) {
            $sql->bind_result($gbez, $sbez, $meldung, $kommentar);
            if ($sql->fetch()) {
              $titel = "Geräteproblem ";
              $auftraege .= "<tr><th>Gerät:</th><td>";
              if ($gart == 'r') {
                $titel .= "in Raum ";
                $auftraege .= "Raum ";
              }
              if ($gart == 'l') {
                $titel .= "mit ";
                $auftraege .= "Leihgeräte ";
              }
              $auftraege .= "$sbez » $gbez</td></tr>";
              $titel .= $sbez." - Gerät ".$gbez;
              $beschreibung = $meldung."\n\n------\n\n".$kommentar;
            }
          }
          $sql->close();
          $auftraegezugehoerigkeit = "<input type=\"hidden\" name=\"cms_hausmeisterauftrag_zugehoerig\" id=\"cms_hausmeisterauftrag_zugehoerig\" value=\"$gart|$gid\">";

        }
        unset($_SESSION['GERAETEPROBLEMDELIGIERENID']);
        unset($_SESSION['GERAETEPROBLEMDELIGIERENART']);
      }
      $auftraege .= "<tr><th>Auftragstitel:</th><td><input type=\"text\" name=\"cms_hausmeisterauftrag_titel\" id=\"cms_hausmeisterauftrag_titel\" value=\"$titel\"></td></tr>";
      $auftraege .= "<tr><th>Auftragsbeschreibung:</th><td><textarea rows=\"10\" cols=\"20\" name=\"cms_hausmeisterauftrag_beschreibung\" id=\"cms_hausmeisterauftrag_beschreibung\">$beschreibung</textarea></td></tr>";
      $zieldatum = mktime(0,0,0,date('m'),date('d')+14,date('Y'));
      $auftraege .= "<tr><th>Zieldatum:</th><td>".cms_datum_eingabe ('cms_hausmeisteraufrag_zieldatum', date('d', $zieldatum), date('m', $zieldatum), date('Y', $zieldatum))." um ".cms_uhrzeit_eingabe('cms_hausmeisteraufrag_zieluhrzeit')."</td></tr>";
      $auftraege .= "<tr><td></td><td><span class=\"cms_button_ja\" onclick=\"cms_hausmeisterauftrag_neu_speichern('app')\">Auftrag einreichen</span> <span class=\"cms_button_nein\" onclick=\"cms_hausmeisterauftrag_abbrechen()\">Auftrag abbrechen</span></td></tr>";
    $auftraege .= "</table>";
    $auftraege .= "<p>".$auftraegezugehoerigkeit."</p>";
    return $auftraege;
  }
  else {
    return cms_meldung_berechtigung();
  }
}
?>
