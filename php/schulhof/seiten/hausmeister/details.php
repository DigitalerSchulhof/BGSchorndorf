<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php

if (cms_r("schulhof.technik.hausmeisteraufträge.sehen"))) {
  if (isset($_SESSION['HAUSMEISTERAUFTRAGID'])) {
    $spalten = 2;
    $fehler = false;
    $sqlfelder = "COUNT(*) AS anzahl, hausmeisterauftraege.id AS id, status, AES_DECRYPT(hausmeisterauftraege.titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, start, ziel, erledigt, hausmeisterauftraege.idvon AS ersteller, AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL') AS pvorname, AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL') AS pnachname, AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS ptitel, nutzerkonten.erstellt AS perstellt, AES_DECRYPT(personen2.vorname, '$CMS_SCHLUESSEL') AS evorname, AES_DECRYPT(personen2.nachname, '$CMS_SCHLUESSEL') AS enachname, AES_DECRYPT(personen2.titel, '$CMS_SCHLUESSEL') AS etitel, nutzerkonten2.erstellt AS eerstellt";

    $sql = $dbs->prepare("SELECT $sqlfelder FROM hausmeisterauftraege LEFT JOIN personen ON personen.id = hausmeisterauftraege.idvon LEFT JOIN nutzerkonten ON nutzerkonten.id = hausmeisterauftraege.idvon LEFT JOIN personen AS personen2 ON personen2.id = hausmeisterauftraege.erledigtvon LEFT JOIN nutzerkonten AS nutzerkonten2 ON nutzerkonten2.id = hausmeisterauftraege.erledigtvon WHERE hausmeisterauftraege.id = ?");

  	$sql->bind_param("i", $_SESSION['HAUSMEISTERAUFTRAGID']);
  	if ($sql->execute()) {
  	  $sql->bind_result($anzahl, $id, $status, $titel, $beschreibung, $start, $ziel, $erledigt, $ersteller, $pvor, $pnach, $ptitel, $perstellt, $evor, $enach, $etitel, $eerstellt);
  	  if ($sql->fetch()) {
  			if ($anzahl != 1) {
  				$fehler = true;
  			}
  		} else {$fehler = true;}
  	} else {$fehler = true;}
  	$sql->close();

    if (!$fehler) {
      if (cms_r("schulhof.technik.hausmeisteraufträge.[|markieren,löschen]"))) {
        $spalten ++;
      }

      include_once('php/schulhof/seiten/termine/termineausgeben.php');

      $code = "";
      $code .= "</div>";
      // Datum und Zieldatum
      $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
      $zeiten = [
          "tagb"        => date('d', $start),
          "monatb"      => date('m', $start),
          "jahrb"       => date('Y', $start),
          "wochentagb"  => date('N', $start),
          "tage"        => date('d', $ziel),
          "monate"      => date('m', $ziel),
          "jahre"       => date('Y', $ziel),
          "wochentage"  => date('N', $ziel)
      ];
      $leer = [
          "uhrzeitbt"        => "0",
          "uhrzeitet"        => "0",
          "beginn"           => $start,
          "ende"             => $ziel,
          "mehrtaegigt"      => "1"
      ];

      $code .= "<h3 class=\"cms_zentriert\">Eingereicht und Zieltermin</h3>";
      $code .= "<div class=\"cms_termin_detialkalenderblatt\">".cms_termin_kalenderblatterzeugen($leer, $zeiten);

      include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
      $CMS_EMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);


      $code .= "<p class=\"cms_zentriert\"><b>Zielzeit:</b> ".date("H:i", $ziel)." Uhr</p>";
      if ($status == 'e') {
        $code .= "<p class=\"cms_zentriert\"><span class=\"cms_auftragerledigt\">Erledigt am ".date('d.m.Y', $erledigt)." um ".date('H:i', $erledigt)." Uhr<br>";
        $code .= "von ";
        if (!is_null($evor) && ($eerstellt < $erledigt)) {
          $erlediger = cms_generiere_anzeigename($evor, $enach, $etitel);
        }
        else {$erlediger .= "<i>existiert nicht mehr</i>";}
        $code .= $erlediger."</span></e>";
      }
      else {
        $code .= "<p class=\"cms_zentriert\"><span class=\"cms_auftragausstehend\">Auftrag noch nicht bearbeitet</span><p>";
      }

      $code .= "<h3 class=\"cms_zentriert\">Ersteller</h3>";
      if (!is_null($pvor) && ($perstellt < $start)) {
        $anzeigename = cms_generiere_anzeigename($pvor, $pnach, $ptitel);
        if (in_array($ersteller, $CMS_EMPFAENGERPOOL)) {
          $code .= "<p class=\"cms_zentriert\"><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$ersteller.")\">$anzeigename</span></td>";
        }
        else {
          $code .= "<p class=\"cms_zentriert\"><span class=\"cms_button_passiv\">$anzeigename</span></p>";
        }
      }
      else {$code .= "<p class=\"cms_zentriert\"><i>existiert nicht mehr</i></p>";}

      $code .= "</div>";
      $code .= "</div></div>";


      if ($spalten > 2) {$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";}
      else {$code .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";}
      $code .= "<h1>$titel</h1>";
      $code .= "<p>".cms_textaustextfeld_anzeigen($beschreibung)."</p>";
      $code .= "</div>";

      if ($spalten > 2) {
        $code .= "</div>";
        $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
        $code .= "<h2>Aktionen</h2><p>";
        if (cms_r("schulhof.technik.hausmeisteraufträge.markieren"))) {
          if ($status == 'e') {
            $code .= "<span class=\"cms_button_wichtig\" onclick=\"cms_hausmeisterauftrag_markieren('n', '$id')\">Auftrag als ausstehend markieren</span></span> ";
          }
          else {
            $code .= "<span class=\"cms_button_ja\" onclick=\"cms_hausmeisterauftrag_markieren('e', '$id')\">Auftrag als erledigt markieren</span> ";
          }
        }
        if (cms_r("schulhof.technik.hausmeisteraufträge.löschen"))) {
          $code .= "<span class=\"cms_button_nein\" onclick=\"cms_hausmeisterauftrag_loeschen_anzeigen('$id')\">Auftrag löschen</span> ";
        }
        $code .= "</p>";
        $code .= "</div>";
      }
      echo $code;
    }
    else {
      echo "<h1>Hausmeisteraufträge</h1>";
      echo cms_meldung_bastler();
    }
  }
  else {
    echo "<h1>Hausmeisteraufträge</h1>";
    echo cms_meldung_bastler();
  }
}
else {
  echo "<h1>Hausmeisteraufträge</h1>";
  echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
