<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Conoatest einsehen</h1>";

$zugriff = (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v'));

if (!$zugriff) {
  $code .= cms_meldung_berechtigung();
} else {

  $fehler = false;
  if (!isset($_SESSION['Coronatest']['gruppenid']) || !isset($_SESSION['Coronatest']['gruppenart'])) {$fehler = true;}
  else {
    $gart = $_SESSION['Coronatest']['gruppenart'];
    $gid = $_SESSION['Coronatest']['gruppenid'];

    if (!in_array($gart, $CMS_GRUPPEN)) {$fehler = true;}

  }

  $g = strtolower(str_replace(" ", "", $gart));
  $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, COUNT(*) FROM $g WHERE id = ?");
  $sql->bind_param("i", $gid);
  if ($sql->execute()) {
    $sql->bind_result($gbez, $anz);
    $sql->fetch();
    if ($anz != 1) {$fehler = true;}
  }
  $sql->close();

  if ($fehler) {
    $code .= cms_meldung_bastler();
  } else {
    $code .= "<h2>$gbez</h2>";

    $tcode = "";

    $TESTER = array();
    // TESTER LADEN
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vor, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nach, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS tit FROM personen WHERE art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL') OR art = AES_ENCRYPT('v', '$CMS_SCHLUESSEL')");
    if ($sql->execute()) {
      $sql->bind_result($pid, $vor, $nach, $titel);
      while ($sql->fetch()) {
        $TESTER[$pid] = cms_generiere_anzeigename($vor, $nach, $titel);
      }
    }
    $sql->close();

    // Für jedes Mitglied den letzten Test ausgeben
    $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS pid, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vor, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nach, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS tit FROM personen JOIN $g"."mitglieder ON personen.id = $g"."mitglieder.person WHERE gruppe = ?) AS x ORDER BY nach, vor, tit");
    $MITGLIEDER = array();
    $MITGLIEDERID = array();
    $sql->bind_param("i", $gid);
    if ($sql->execute()) {
      $sql->bind_result($pid, $vor, $nach, $titel);
      while ($sql->fetch()) {
        $m = array();
        $m['id'] = $pid;
        $m['name'] = cms_generiere_anzeigename($vor, $nach, $titel);
        array_push($MITGLIEDER, $m);
        array_push($MITGLIEDERID, $pid);
      }
    }
    $sql->close();

    $sql = $dbs->prepare("SELECT *, COUNT(*) FROM (SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL'), tester, zeit FROM coronatest JOIN coronagetestet ON test = coronatest.id WHERE person = ? ORDER BY zeit DESC LIMIT 0,1) as y");
    foreach ($MITGLIEDER AS $m) {
      $sql->bind_param("i", $m['id']);
      if ($sql->execute()) {
        $sql->bind_result($testart, $durchfuehrer, $zeit, $anzahl);
        if ($sql->fetch()) {
          if (isset($TESTER[$durchfuehrer])) {$getestetvon = $TESTER[$durchfuehrer];}
          else {$getestetvon = "<i>unbekannt</i>";}
          $tcode .= "<tr><td></td><td>".$m['name']."</td>";
          if ($anzahl == 0) {
            $tcode .= "<td><i>nicht stattgefunden</i></td>";
          } else {
            $tcode .= "<td>".date("d.m.Y H:i", $zeit)." ($getestetvon)";
            if ($testart == 't') {$tcode .= " – getestet";}
            else if ($testart == 'b') {$tcode .= " – bescheinigt";}
            $tcode .= "</td>";
          }
          $tcode .= "<td><select id=\"cms_testerfassen_".$m['id']."\">";
          $tcode .= "<option value=\"nt\">nicht getestet</option><option value=\"t\">getestet</option><option value=\"b\">bescheinigt</option></select></td></tr>";
        }
      }

    }
    $sql->close();

    if (strlen($tcode) > 0) {
      $code .= "<table class=\"cms_liste\">";
        $code .= "<tr><th></th><th>Name</th><th>Letzer Test</th><th>Neuer Test</th></tr>";
        $code .= $tcode;
      $code .= "</table>";
      $code .= "<p><input type=\"hidden\" name=\"cms_testpersonen\" id=\"cms_testpersonen\" value=\"".implode(",", $MITGLIEDERID)."\"></p>";
      $code .= "<p><span onclick=\"cms_coronatest_speichern()\" class=\"cms_button_ja\">+ Neuen Test speichern</span> <a class=\"cms_button\" href=\"Schulhof/Verwaltung/Coronatest\">Abbrechen</a></p>";

    }
  }

}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
