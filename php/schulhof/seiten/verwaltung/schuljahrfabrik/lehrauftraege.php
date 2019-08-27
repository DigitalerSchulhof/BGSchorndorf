<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Schuljahrfabrik'];

$code = "";
if ($zugriff) {

  $code .= "<h1>Schuljahre aus bestehenden Schuljahren erzeugen</h1>";

  $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bestehenden Lehrauftrage in Klassen und Klassenkursen im Zielschuljahr, werden mit dem Abschluss dieses Schrittes aus diesen Kursen und Klassen entfernt.</b></p>');

  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY beginn DESC");
  $buttonsa = "";
  $buttonsn = "";
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$SCHULJAHR = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];}
  else {$SCHULJAHR = null;}
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$SCHULJAHRNEU = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];}
  else {$SCHULJAHRNEU = null;}
  if ($sql->execute()) {
    $sql->bind_result($id, $sjbez);
    while ($sql->fetch()) {
      if (($SCHULJAHR == $id) && ($SCHULJAHR != 'null')) {$buttonsa .= "<span class=\"cms_button_ja\"";}
      else {$buttonsa .= "<span class=\"cms_button\"";}
      $buttonsa .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$id', 'Lehraufträge', '$SCHULJAHRNEU');\">".$sjbez."</span> ";
      if (($SCHULJAHRNEU == $id) && ($SCHULJAHRNEU != 'null')) {$buttonsn .= "<span class=\"cms_button_ja\"";}
      else {$buttonsn .= "<span class=\"cms_button\"";}
      $buttonsn .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$SCHULJAHR', 'Lehraufträge', '$id');\">".$sjbez."</span> ";
    }
  }
  $sql->close();
  $code .= "<h2>Zielschuljahr auswählen</h2>";
  if (strlen($buttonsa) > 0) {$code .= "<p>$buttonsn</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}

  // Prüfen, ob Stammschuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR']) || isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre WHERE id = ?");
    if (!is_null($SCHULJAHRNEU)) {
      $sql->bind_param('i', $SCHULJAHRNEU);
      if ($sql->execute()) {
        $sql->bind_result($anzahl, $SCHULJAHRBEZ);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
    }
    if (!is_null($SCHULJAHR)) {
      $sql->bind_param('i', $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($anzahl, $SCHULJAHRBEZ);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
      $sql->close();
    }
  }

  $code .= "<h2>Teilschritte</h2>";
  $code .= "<p>";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_grundlagen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Grundlagen\">Grundlagen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_profile\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile\">Profile</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_kurspersonen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\">Personen in Kursen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_lehrauftraege cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\">Lehraufträge</a> ";
  $code .= "</p>";
  echo $code;

  if (is_null($SCHULJAHR) || is_null($SCHULJAHRNEU) ||$SCHULJAHR == 'null' || $SCHULJAHRNEU == 'null') {
    echo cms_meldung('info', '<h4>Informationen fehlen</h4><p>Bitte wählen Sie ein Stammschuljahr und ein Zielschuljahr aus.</p>');
    $sjfehler = true;
  }

  if (!$sjfehler) {
    if ($SCHULJAHR == $SCHULJAHRNEU) {
      echo cms_meldung('info', '<h4>Schuljahrauswahl ungültig</h4><p>Das Stammschuljahr und das Zielschuljahr sind identisch.</p>');
      $sjfehler = true;
    }
  }
  $code = "";

  if (!$sjfehler) {

    echo "<h2>Lehraufträge verteilen</h2>";

    include_once('php/schulhof/seiten/personensuche/personensuche.php');

    $KLASSEN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, kbez");
    $sql->bind_param("i", $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($kid, $kursbez, $stufenbez, $reihenfolge);
      while ($sql->fetch()) {
        $klasse = array();
        $klasse['id'] = $kid;
        $klasse['bez'] = $kursbez;
        $klasse['stufe'] = $stufenbez;
        $klasse['lehrer'] = "";
        array_push($KLASSEN, $klasse);
      }
    }

    $sql = $dbs->prepare("SELECT person FROM klassenvorsitz WHERE gruppe = ?");
    for ($k=0; $k<count($KLASSEN); $k++) {
      $sql->bind_param("i", $KLASSEN[$k]['id']);
      if ($sql->execute()) {
        $sql->bind_result($p);
        while ($sql->fetch()) {
          $KLASSEN[$k]['lehrer'] .= "|".$p;
        }
      }
    }
    $sql->close();

    //echo "<textarea cols=\"500\" rows=\"20\">";print_r($KURSE);echo "</textarea>";
    $klassenids = "";
    $code .= "<h3>Klassenlehrer</h3>";
    $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th>Klassen</th><th>Lehrer</th></tr>";
    $altestufe = null;
    $teilcode = "";
    foreach ($KLASSEN as $k) {
      if ($k['stufe'] != $altestufe) {
        $teilcode .= "<tr><th class=\"cms_zwischenueberschrift\" colspan=\"3\">".$k['stufe']."</th></tr>";
        $altestufe = $k['stufe'];
      }
      $teilcode .= "<tr><td>".$k['bez']."</td>";
      $teilcode .= "<td>".cms_personensuche_personhinzu_generieren($dbs, "cms_sjfabrik_lehrer_klasse_".$k['id'], 'l', $k['lehrer'])."</td></tr>";
      $klassenids .= "|".$k['id'];
    }
    if (strlen($teilcode) > 0) {$code .= $teilcode;}
    else {$code .= "<tr><td class=\"cms_notiz\" colspan=\"2\">Keine Kurse in Stufen angelegt.</td></tr>";}
    $code .= "</table><p><input type=\"hidden\" name=\"cms_sjfabrik_kurse\" id=\"cms_sjfabrik_klassen\" value=\"$klassenids\"></p>";
    echo $code;




    $KURSE = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge FROM kurse JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ? AND kurse.id IN (SELECT kurs FROM kurseklassen JOIN klassen ON kurseklassen.klasse = klassen.id WHERE klassen.schuljahr = ?)) AS x ORDER BY reihenfolge, kbez");
    $sql->bind_param("ii", $SCHULJAHRNEU, $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($kid, $kursbez, $stufenbez, $reihenfolge);
      while ($sql->fetch()) {
        $kurs = array();
        $kurs['id'] = $kid;
        $kurs['bez'] = $kursbez;
        $kurs['stufe'] = $stufenbez;
        $kurs['lehrer'] = "";
        array_push($KURSE, $kurs);
      }
    }

    $sql = $dbs->prepare("SELECT person FROM kursevorsitz WHERE gruppe = ?");
    for ($k=0; $k<count($KURSE); $k++) {
      $sql->bind_param("i", $KURSE[$k]['id']);
      if ($sql->execute()) {
        $sql->bind_result($p);
        while ($sql->fetch()) {
          $KURSE[$k]['lehrer'] .= "|".$p;
        }
      }
    }
    $sql->close();

    //echo "<textarea cols=\"500\" rows=\"20\">";print_r($KURSE);echo "</textarea>";
    $kurseids = "";
    $code = "<h3>Kurslehrer</h3>";
    $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th>Kurse</th><th>Lehrer</th></tr>";
    $altestufe = null;
    $teilcode = "";
    foreach ($KURSE as $k) {
      if ($k['stufe'] != $altestufe) {
        $teilcode .= "<tr><th class=\"cms_zwischenueberschrift\" colspan=\"3\">".$k['stufe']."</th></tr>";
        $altestufe = $k['stufe'];
      }
      $teilcode .= "<tr><td>".$k['bez']."</td>";
      $teilcode .= "<td>".cms_personensuche_personhinzu_generieren($dbs, "cms_sjfabrik_lehrer_kurs_".$k['id'], 'l', $k['lehrer'])."</td></tr>";
      $kurseids .= "|".$k['id'];
    }
    if (strlen($teilcode) > 0) {$code .= $teilcode;}
    else {$code .= "<tr><td class=\"cms_notiz\" colspan=\"2\">Keine Kurse in Stufen angelegt.</td></tr>";}
    $code .= "</table><p><input type=\"hidden\" name=\"cms_sjfabrik_kurse\" id=\"cms_sjfabrik_kurse\" value=\"$kurseids\"></p>";
    echo $code;



    $code = "<h2>Lehraufträge verteilen abschließen</h2>";
    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bestehenden Lehrauftrage in Klassen und Klassenkursen im Zielschuljahr, werden mit dem Abschluss dieses Schrittes aus diesen Kursen und Klassen entfernt.</b></p>');
    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_lehrauftraege();\">+ Schuljahrfabrik – Lehraufträge</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
  }
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
