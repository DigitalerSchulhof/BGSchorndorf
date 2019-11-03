<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Stunden und Tagebücher erzeugen'];

$code = "";
if ($zugriff) {

  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['STUNDENERZEUGENSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['STUNDENERZEUGENSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (!$sjfehler) {
    $code .= "<h1>Stunden und Tagebücher erzeugen</h1>";
    $zeitraumfehler = false;

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_stundenerzeugen_vorbereiten($id)\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    $zeitraumids = "";
    $jetzt = time();
    $ZEITRAEUME = array();
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, beginn, ende FROM zeitraeume WHERE aktiv = 1 AND schuljahr = ? AND ende > ? ORDER BY beginn");
    $sql->bind_param("ii", $SCHULJAHR, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($zid, $zbez, $zbeginn, $zende);
      while ($sql->fetch()) {
        $z = array();
        $z['id'] = $zid;
        $z['bez'] = $zbez;
        $z['beginn'] = $zbeginn;
        $z['ende'] = $zende;
        array_push($ZEITRAEUME, $z);
        $zeitraumids .= "|".$zid;
      }
    }
    $sql->close();

    // Kurse aller Zeiträume suchen
    $kurseids = "";
    $sql = $dbs->prepare("SELECT id FROM kurse WHERE schuljahr = ?");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($kid);
      while ($sql->fetch()) {
        $kurseids .= "|".$kid;
      }
    }
    $sql->close();

    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Zeitraum</th><th>von</th><th>bis</th><th>Erzeugen?</th></tr>";
    $tabellencode = "";
    // Zeiträume laden
    foreach($ZEITRAEUME AS $z) {
      $tabellencode .= "<tr><td>".$z['bez']."</td><td>".cms_tagname(date('N', $z['beginn']))." ".date('d.m.Y', $z['beginn'])."</td><td>";
      $tabellencode .= cms_tagname(date('N', $z['ende']))." ".date('d.m.Y', $z['ende'])."</td><td>".cms_schieber_generieren('zeitraum_erzeugen_'.$zid, 0)."</td></tr>";
    }
    if (strlen($tabellencode) > 0) {$code .= $tabellencode;}
    else {$code .= "<tr><td class=\"cms_notiz\" colspan=\"4\">Keine aktiven zukünftigen Zeiträume gefunden.</td></tr>";}
    $code .= "</table>";

    $code .= "<p><input type=\"hidden\" id=\"cms_zeitraeume\" name=\"cms_zeitraeume\" value=\"$zeitraumids\"><input type=\"hidden\" id=\"cms_kurse\" name=\"cms_kurse\" value=\"$kurseids\"><input type=\"hidden\" id=\"cms_schuljahr\" name=\"cms_schuljahr\" value=\"$SCHULJAHR\"></p>";

    $code .= cms_meldung("warnung", '<h4>Alte Stunden werden gelöscht</h4><p>Alle noch ausstehnden Stunden bereits erzeugter Zeiträume, inklusive aller zur Vertretung vorgemerkten Stunden werden gelöscht und neu angelegt! <b>Vertretungen müssen also neu eingegeben werden!</b></p>');

    $code .= "<p>";
    if (strlen($zeitraumids) > 0) {$code .= "<span class=\"cms_button\" onclick=\"cms_stundenerzeugen_speichern();\">Stunden und Tagebücher erzeugen</span> ";}
    $code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Stundenplanung</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Stundenplanung</h1>".cms_meldung_berechtigung();
}

function cms_generiere_unterrichtsstunde($stunde, $modus) {
  $event = "";
  if ($modus == 'L') {$event = " onclick=\"cms_stundeloeschen(".$stunde['id'].")\"";}
  $code = "<span class=\"cms_stundenplanung_stunde cms_farbbeispiel_".$stunde['farbe']."\"$event><span class=\"cms_stundenplanung_stundeinfo\">".$stunde['kursbez']."<br>".$stunde['lehrerbez']."<br>".$stunde['raumbez']."";
  if ($stunde['rythmus'].'' != '0') {
    $code .= "<br>".chr(64+$stunde['rythmus'])." Woche";
  }
  $code .= "</span></span>";
  return $code;
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
