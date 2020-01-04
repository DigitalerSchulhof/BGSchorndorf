<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (r("schulhof.planung.schuljahre.fabrik")) {

  $code .= "<h1>Schuljahre aus bestehenden Schuljahren erzeugen</h1>";


  $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p>');

  $code .= "<h2>Stammschuljahr auswählen</h2>";
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY beginn DESC");
  $buttons = "";
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$SCHULJAHR = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];}
  else {$SCHULJAHR = null;}
  if ($sql->execute()) {
    $sql->bind_result($id, $sjbez);
    while ($sql->fetch()) {
      if (($SCHULJAHR == $id) && ($SCHULJAHR != 'null')) {$buttons .= "<span class=\"cms_button_ja\"";}
      else {$buttons .= "<span class=\"cms_button\"";}
      $buttons .= " onclick=\"cms_schuljahrfabrik_vorbereiten($id);\">".$sjbez."</span> ";
    }
  }
  $sql->close();
  if (strlen($buttons) > 0) {$code .= "<p>$buttons</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}

  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginn, ende FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez, $sjbeginn, $sjende);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  $code .= "<h2>Teilschritte</h2>";
  $code .= "<p>";
  $code .= "<a class=\"cms_iconbutton cms_button_ja cms_schuljahrfabrik_grundlagen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Grundlagen\">Grundlagen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_profile\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile\">Profile</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_kurspersonen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\">Personen in Kursen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_lehrauftraege\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\">Lehraufträge</a> ";
  $code .= "</p>";
  echo $code;

  if (is_null($SCHULJAHR) || $SCHULJAHR == 'null') {$sjfehler = true;}

  if (!$sjfehler) {

    $code = "<h2>Grundlagen erfassen</h2>";
    $code = "<h3>Schuljahrdetails</h4>";
    $code .= "<table class=\"cms_formular\">";
      $sjneubeginn = mktime(0,0,0,date('m', $sjbeginn), date('d', $sjbeginn), date('Y', $sjbeginn)+1);
      $sjneuende = mktime(23,59,59,date('m', $sjende), date('d', $sjende), date('Y', $sjende)+1);
      $code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_sjfabrik_sjbez\" id=\"cms_sjfabrik_sjbez\" value=\"".date('Y', $sjneubeginn)."-".date('Y', $sjneuende)."\"></td></tr>";
      $code .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe('cms_sjfabrik_jsbeginn', date('d', $sjneubeginn), date('m', $sjneubeginn), date('Y', $sjneubeginn))."</td></tr>";
      $code .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe('cms_sjfabrik_jsende', date('d', $sjneuende), date('m', $sjneuende), date('Y', $sjneuende))."</td></tr>";
    $code .= "</table>";

    $schluesselpositionen[0]  = ['bez' => 'Schulleitung',                  'art' => 'l',  'pers' => ''];
    $schluesselpositionen[1]  = ['bez' => 'Stellvertretende Schulleitung', 'art' => 'l',  'pers' => ''];
    $schluesselpositionen[2]  = ['bez' => 'Abteilungsleitung',             'art' => 'l',  'pers' => ''];
    $schluesselpositionen[3]  = ['bez' => 'Vertretungsplanung',            'art' => 'l',  'pers' => ''];
    $schluesselpositionen[4]  = ['bez' => 'Datenschutzbeauftragter',       'art' => 'l',  'pers' => ''];
    $schluesselpositionen[5]  = ['bez' => 'Sekretariat',                   'art' => 'vx', 'pers' => ''];
    $schluesselpositionen[6]  = ['bez' => 'Hausmeister',                   'art' => 'vx', 'pers' => ''];
    $schluesselpositionen[7]  = ['bez' => 'Sozialarbeit',                  'art' => 'vx', 'pers' => ''];
    $schluesselpositionen[8]  = ['bez' => 'Oberstufenberatung',            'art' => 'l',  'pers' => ''];
    $schluesselpositionen[9]  = ['bez' => 'Beratungslehrkräfte',           'art' => 'l',  'pers' => ''];
    $schluesselpositionen[10] = ['bez' => 'Verbindungslehrkräfte',         'art' => 'l',  'pers' => ''];
    $schluesselpositionen[11] = ['bez' => 'Schülersprecher',               'art' => 's',  'pers' => ''];
    $schluesselpositionen[12] = ['bez' => 'Elternbeiratsvorsitzende',      'art' => 'e',  'pers' => ''];


    $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')) AS personen");

    for ($i = 0; $i<count($schluesselpositionen); $i++) {
      $sql->bind_param("is", $SCHULJAHR, $schluesselpositionen[$i]['bez']);
      if ($sql->execute()) {
        $sql->bind_result($persid);
        while ($sql->fetch()) {
          $schluesselpositionen[$i]['pers'] .= "|".$persid;
        }
      }
    }
    $sql->close();

    include_once('php/schulhof/seiten/personensuche/personensuche.php');

    $code .= "<h3>Schlüsselpositionen in diesem Schuljahr</h3>";
    $code .= "<table class=\"cms_liste\">";
    for ($i = 0; $i<count($schluesselpositionen); $i++) {
      $code .= "<tr><th>".$schluesselpositionen[$i]['bez'].":</th><td>";
  				$code .= cms_personensuche_personhinzu_generieren($dbs, 'cms_sjfabrik_sj'.cms_textzudb($schluesselpositionen[$i]['bez']), $schluesselpositionen[$i]['art'], $schluesselpositionen[$i]['pers']);
  		$code.= "</td></tr>";
    }
    $code .= "</table>";
    echo cms_toggleeinblenden_generieren ('cms_sjfabrik_sjfelder', 'Neues Schuljahr einblenden', 'Neues Schuljahr ausblenden', $code, 1);

    $code = "<h3>Fächer</h3>";
    $code .= "<p>Die folgenden Fächer werden im neuen Schuljahr angelegt:</p>";
    $faechercode = "";
    $faecherids = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM faecher WHERE schuljahr = ?) AS f ORDER BY bez");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($fid, $fbez, $fkurz);
      while ($sql->fetch()) {
        $faechercode .= cms_togglebutton_generieren ("cms_sjfabrik_faecher_".$fid, $fbez." (".$fkurz.")", 1)." ";
        $faecherids .= "|".$fid;
      }
    }
    $sql->close();
    $code .= "<p><input type=\"hidden\" name=\"cms_sjfabrik_faecher\" id=\"cms_sjfabrik_faecher\" value=\"$faecherids\">";
    if (strlen($faechercode) > 0) {$code .= $faechercode;}
    else {$code .= "<span class=\"cms_notiz\">keine Fächer verfügbar</span>";}
    $code .= "</p>";
    echo cms_toggleeinblenden_generieren ('cms_sjfabrik_ffelder', 'Neue Fächer einblenden', 'Neue Fächer ausblenden', $code, 1);



    $SJF_GRUPPEN[0] = "Gremien";
    $SJF_GRUPPEN[1] = "Fachschaften";
    $SJF_GRUPPEN[2] = "Stufen";
    $SJF_GRUPPEN[3] = "Klassen";
    $SJF_GRUPPEN[4] = "Arbeitsgemeinschaften";
    $SJF_GRUPPEN[5] = "Arbeitskreise";
    $SJF_GRUPPEN[6] = "Fahrten";
    $SJF_GRUPPEN[7] = "Wettbewerbe";
    $SJF_GRUPPEN[8] = "Ereignisse";
    $SJF_GRUPPEN[9] = "Sonstige Gruppen";

    foreach ($SJF_GRUPPEN as $SJFG) {
      $kSJFG = cms_textzudb($SJFG);
      $code = "<h3>$SJFG</h3>";
      $code .= "<p>Die folgenden $SJFG werden im neuen Schuljahr angelegt:</p>";
      $teilcode = "";
      $teilids = "";
      if ($SJFG == "Stufen") {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $kSJFG WHERE schuljahr = ? ORDER BY reihenfolge) AS s");
      }
      else if ($SJFG == "Klassen") {
        $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $kSJFG JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ? ORDER BY reihenfolge) AS s");
      }
      else {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $kSJFG WHERE schuljahr = ?) AS s ORDER BY bezeichnung");
      }

      $sql->bind_param("i", $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($tid, $tbez);
        $tanzahl = 0;
        while ($sql->fetch()) {
          $teilcode .= cms_togglebutton_generieren ("cms_sjfabrik_".$kSJFG."_".$tid, $tbez, 1)." ";
          $teilids .= "|".$tid;
          $tanzahl++;
        }
      }

      $sql->close();
      $code .= "<p><input type=\"hidden\" name=\"cms_sjfabrik_$kSJFG\" id=\"cms_sjfabrik_$kSJFG\" value=\"$teilids\">";
      if (strlen($teilcode) > 0) {$code .= $teilcode;}
      else {$code .= "<span class=\"cms_notiz\">keine $SJFG verfügbar</span>";}
      $code .= "</p>";
      if ($SJFG == "Stufen") {
        $code .= cms_meldung('info', '<h4>Änderung der Reihenfolge bei Abwahl von Stufen</h4><p>Werden Stufen abgewählt, so rutschen nachfolgende Stufen in der Reihenfolge auf.</p>');
      }
      if ($SJFG == "Klassen") {
        $code .= cms_meldung('info', '<h4>Ignorieren bei Abwahl von Stufen</h4><p>Werden Stufen abgewählt, so werden keine Klassen dieser Stufe angelegt, auch wenn sie hier ausgewählt sind.</p>');
      }
      $einblenden = 0;
      if ($tanzahl > 0) {$einblenden = 1;}
      echo cms_toggleeinblenden_generieren ('cms_sjfabrik_'.$kSJFG.'_felder', 'Neue '.$SJFG.' einblenden', 'Neue '.$SJFG.' ausblenden', $code, $einblenden);
    }


    $code = "<h2>Erfassung der Grundlagen abschließen</h2>";
    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p>');
    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_grundlagen();\">+ Schuljahrfabrik – Gundlagen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
