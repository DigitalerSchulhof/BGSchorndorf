<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
// Pürfen, ob der Tagebucheintrag zum Benutzer gehört
if (isset($_SESSION['TAGEBUCHEINSEHENID']) && isset($_SESSION['TAGEBUCHSEHENART'])) {
  $gruppenid = $_SESSION['TAGEBUCHEINSEHENID'];
  $gruppenart = $_SESSION['TAGEBUCHSEHENART'];

  // Prüfen, ob in dieser Klasse unterrichtet wird
  $zugriff = false;
  if ($gruppenart == "klasse") {
    $sql = $dbs->prepare("SELECT COUNT(tlehrer) FROM unterricht WHERE tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND tlehrer = ?");
    $sql->bind_param("ii", $gruppenid, $CMS_BENUTZERID);
  } else {
    $sql = $dbs->prepare("SELECT COUNT(tlehrer) FROM unterricht WHERE tkurs = ? AND tlehrer = ?");
    $sql->bind_param("ii", $gruppenid, $CMS_BENUTZERID);
  }
  $sql->bind_result($anzahl);
  if ($sql->execute()) {
    $sql->fetch();
    if ($anzahl > 0) {$zugriff = true;}
  }
  $sql->close();


  if ($zugriff) {
    // Titel erzeugen
    $titel = "";
    if ($gruppenart == "klasse") {
      $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM klassen WHERE id = ?");
    } else {
      $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM kurse WHERE id = ?");
    }
    $sql->bind_param("i", $gruppenid);
    if ($sql->execute()) {
      $sql->bind_result($titel);
      $sql->fetch();
    }
    $sql->close();
    // Wochenwahl ausgeben
    if ($gruppenart == "klasse") {
      $code .= "<h1>Klassentagebuch »".$titel."«</h1>";

      $jetzt = time();
      $t = date("d", $jetzt);
      $m = date("m", $jetzt);
      $j = date("Y", $jetzt);
      $w = date("w", $jetzt)-1;
      $anfang = mktime(0,0,0,$m,$t-$w,$j);
      $ende = mktime(0,0,0,$m,$t-$w+7,$j)-1;

      // Schulwochenanzeige
      $code .= "<p><span id=\"cms_tagebuchansicht_w\" class=\"cms_button_ja\" onclick=\"cms_tagebuchansicht_aendern('w')\">Wochenweise</span> <span id=\"cms_tagebuchansicht_v\" class=\"cms_button\" onclick=\"cms_tagebuchansicht_aendern('v')\">Vollständig</span> <input type=\"hidden\" id=\"cms_tagebuchansicht\" name=\"cms_tagebuchansicht\" value=\"w\"><input type=\"hidden\" id=\"cms_tagebuch_wochenansicht_datum\" name=\"cms_tagebuch_wochenansicht_datum\" value=\"$anfang\"></p>";

      $code .= "<table class=\"cms_zeitwahl\"><tbody><tr>";
      $code .= "<td><span class=\"cms_button\" onclick=\"cms_tagebuch_wochenansicht('-')\">«</span></td>";
      $code .= "<td id=\"cms_tagebuch_wochenansicht_datum_text\">MO ".date("d.m.Y", $anfang)." – SO ".date("d.m.Y", $ende)."</td>";
      $code .= "<td><span class=\"cms_button\" onclick=\"cms_tagebuch_wochenansicht('+')\">»</span></td></tr></tbody></table>";

    } else {
      $code .= "<h1>Kurstagebuch »".$titel."«</h1>";
    }

    $code .= "<p><input type=\"hidden\" id=\"cms_tagebuchgruppenart\" name=\"cms_tagebuchgruppenart\" value=\"$gruppenart\"><input type=\"hidden\" id=\"cms_tagebuchgruppenid\" name=\"cms_tagebuchgruppenid\" value=\"$gruppenid\"></p>";
    $code .= "<div id=\"cms_tagebuch_tagesansicht\">";
    $code .= cms_generiere_nachladen("cms_tagebuchuebersicht_lehrernetz", "");
    $CMS_ONLOAD_EVENTS .= "cms_tagebuch_wochenansicht('j');";
    $code .= "</div>";
    echo $code;
  }
  else {
    echo cms_meldung_berechtigung();
  }

}
else {
  echo cms_meldung_bastler();
}
?>
</div>
