<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
// Pürfen, ob der Tagebucheintrag zum Benutzer gehört
if (isset($_SESSION['TAGEBUCHEINTRAG'])) {
  $eintrag = $_SESSION['TAGEBUCHEINTRAG'];

  $tlehrer = null;
  // Stundeninformationen laden
  $sql = $dbs->prepare("SELECT tagebuch.id, AES_DECRYPT(tagebuch.inhalt, '$CMS_SCHLUESSEL'), AES_DECRYPT(tagebuch.hausaufgabe, '$CMS_SCHLUESSEL'), tagebuch.freigabe, tagebuch.leistungsmessung, tagebuch.urheber, tbeginn, tende, traum, tkurs, tlehrer, AES_DECRYPT(ur.vorname, '$CMS_SCHLUESSEL') AS urvor, AES_DECRYPT(ur.nachname, '$CMS_SCHLUESSEL') AS urnach, AES_DECRYPT(ur.titel, '$CMS_SCHLUESSEL') AS urtitel, AES_DECRYPT(lehr.vorname, '$CMS_SCHLUESSEL') AS lehrvor, AES_DECRYPT(lehr.nachname, '$CMS_SCHLUESSEL') AS lehrnach, AES_DECRYPT(lehr.titel, '$CMS_SCHLUESSEL') AS lehrtitel, kuerzel, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') FROM tagebuch JOIN unterricht ON tagebuch.id = unterricht.id LEFT JOIN personen AS ur ON ur.id = tagebuch.urheber LEFT JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN personen AS lehr ON lehr.id = tlehrer LEFT JOIN kurse ON tkurs = kurse.id LEFT JOIN raeume ON traum = raeume.id WHERE tagebuch.id = ? AND freigabe != 1");
  $sql->bind_param("i", $eintrag);
  if ($sql->execute()) {
    $sql->bind_result($uid, $inhalt, $hausi, $frei, $leistung, $urheber, $tbeginn, $tende, $traum, $tkurs, $tlehrer, $urvor, $urnach, $urtitel, $lehrvor, $lehrnach, $lehrtitel, $kuerzel, $raumbez, $kursbez);
    $sql->fetch();
    $zeit = cms_tagnamekomplett(date('N', $tbeginn)).", den ".date("d.m.Y H:i", $tbeginn)." – ".date("H:i", $tende+1);
  }
  $sql->close();


  if ($CMS_BENUTZERART == 'l' && $tlehrer == $CMS_BENUTZERID) {
    $code = "<h1>Eintrag für $kursbez</h1>";

    $code .= "<h2>Stundendetails</h2>";

    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Stunde:</th><td>$zeit in Raum $raumbez</td></tr>";
    if ($urheber != null) {
      $code .= "<tr><th>Letze Änderung:</th><td>".cms_generiere_anzeigename($urvor, $urnach, $urtitel)."</td></tr>";
    }
    $code .= "<tr><th>Stundeninhalt:</th><td><textarea id=\"cms_eintrag_inhalt\" name=\"cms_eintrag_inhalt\">$inhalt</textarea></td></tr>";
    $code .= "<tr><th>Hausaufgaben:</th><td><textarea id=\"cms_eintrag_hausi\" name=\"cms_eintrag_hausi\">$hausi</textarea></td></tr>";

		$code .= "<tr><th>Leistungsmessung:</th><td>".cms_generiere_schieber('eintrag_leistungsmessung', $leistung)."</td></tr>";
		$code .= "<tr><th>Freigeben:</th><td>".cms_generiere_schieber('eintrag_freigabe', '1')."</td></tr>";
		$code .= "</table>";

    // Schüler des Kurses laden
    $schueler = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM kursemitglieder JOIN personen ON person = personen.id WHERE gruppe = ? AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname, vorname, titel");
    $sql->bind_param("i", $tkurs);
    if ($sql->execute()) {
      $sql->bind_result($pid, $vorname, $nachname, $titel);
      while ($sql->fetch()) {
        $schueler .= "<option value=\"$pid\">".cms_generiere_anzeigename($vorname, $nachname, $titel)."</option>";
      }
    }
    $sql->close();
    $code .= "<p style=\"display:none\"><select name=\"cms_eintrag_vorlage\" id=\"cms_eintrag_vorlage\">$schueler</select></p>";


    $code .= "<h2>Fehlzeiten</h2>";
    if (!$CMS_IMLN) {
      $code .= "<div class=\"cms_meldung cms_meldung_warnung\"><h4>Möglicherweise unvollständig</h4><p>Da keine Verbindung zum Lehrernetz besteht, könnten die gemeldeten Fehlzeiten unvollständig sein. Zum Beispiel, könnte für einzelne Schülerinnen und Schüler eine ganztägige Fehlzeit gemeldet worden sein.</p><p>Sollten sich die hier angegebenen Fehlzeiten mit bereits gemeldeten Fehlzeiten überschneiden, so wird diese neue Fehlzeit ignoriert oder die gemeldete Fehlzeit ggf. verlängert. Sollte für diesen Zeitraum bereits eine Fehlzeit bestehen, die hier aber nicht aufgeführt ist, so wird die gemeldete Fehlzeit für diesen Zeitraum unterbrochen, da davon ausgegangen wird, dass in dieser Stunde keine Fehlzeit vorliegt.</p><p><a class=\"cms_button\" href=\"Schulhof/Hilfe/VPN\">Verbindung ins Lehrernetz herstellen</a></p></div>";
    }

    // Fehlzeiten laden
    $fehlzeiten = "";
    $fzanzahl = 0;
    $fznr = 0;
    $fzids = "";
    $sql = $dbs->prepare("SELECT fehlzeiten.id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM fehlzeiten WHERE (von < ? AND bis > ?) OR (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) AND person IN (SELECT person FROM kursemitglieder WHERE id = ?)");
    $sql->bind_param("iiiiiii", $tbeginn, $tende, $tbeginn, $tende, $tbeginn, $tende, $tkurs);
    if ($sql->execute()) {
      $sql->bind_result($fid, $fzperson, $fzvon, $fzbis, $fzbem);
      while ($sql->fetch()) {
        $fehlzeiten .= "<table class=\"cms_formular\" id=\"cms_eintrag_fz_$fid\">";
        $fehlzeiten .= "<tr><th>Person:</th><td><select name=\"cms_eintrag_fz_person_$fid\" id=\"cms_eintrag_fz_person_$fid\">".str_replace("value=\"$fzperson\"", "value=\"$fzperson\" selected=\"selected\"", $schueler)."</select></td></tr>";
        $ganztaegig = "<span class=\"cms_button\" onclick=\"cms_eintrag_ganztaegig('$fid')\">Ganztägig</span>";
        $fehlzeiten .= "<tr><th>Zeitraum:</th><td>".cms_uhrzeit_eingabe("cms_eintrag_fz_von_$fid", date("H", $fzvon), date("i", $fzvon))." – ".cms_uhrzeit_eingabe("cms_eintrag_fz_bis_$fid", date("H", $fzbis), date("i", $fzbis))." $ganztaegig</td></tr>";
        $fehlzeiten .= "<tr><th>Bemerkung:</th><td><input type=\"text\" name=\"cms_eintrag_fz_bemerkung_$fid\" id=\"cms_eintrag_fz_bemerkung_$fid\" value=\"$fzbem\"></td></tr>";
      	$fehlzeiten .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_eintrag_fzweg('$fid');\">– Fehlzeit entfernen</span></td></tr>";
        $fehlzeiten .= "</table>";
        $fzanzahl++;
        $fznr++;
        $fzids .= "|".$fid;
      }
    }
    $sql->close();

    $code .= "<p><input type=\"hidden\" value=\"$fzanzahl\" id=\"cms_eintrag_fzan\" name=\"cms_eintrag_fzan\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$fznr\" id=\"cms_eintrag_fznr\" name=\"cms_eintrag_fznr\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$fzids\" id=\"cms_eintrag_fzids\" name=\"cms_eintrag_fzids\"></p>";
    $code .= "<div id=\"cms_eintrag_fehlzeiten\">";
    if ($CMS_IMLN) {
      $code .= cms_generiere_nachladen("cms_eintrag_fehlzeiten_laden", "");
      $CMS_ONLOAD_EVENTS .= "cms_eintrag_fzladen('$uid');";
    }
    $code .= $fehlzeiten;
    $code .= "</div>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_eintrag_fzdazu('$tbeginn', '$tende')\">+ Fehlzeit hinzufügen</span></p>";


    // Lob und Tadel laden
    $lobundtadel = "";
    $ltanzahl = 0;
    $ltnr = 0;
    $ltids = "";
    $ltpersonen = $schueler."<option value=\"-\">ganzer Kurs</option>";
    $ltartwahl = "<option value=\"m\">Mitarbeits-Tadel</option><option value=\"v\">Verhaltens-Tadel</option><option value=\"l\">Lob</option>";
    $sql = $dbs->prepare("SELECT lobtadel.id, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM lobtadel WHERE eintrag = ?");
    $sql->bind_param("i", $uid);
    if ($sql->execute()) {
      $sql->bind_result($ltid, $ltperson, $ltart, $ltbem);
      while ($sql->fetch()) {
        if ($ltperson == null) {$ltperson = "-";}
        $lobundtadel .= "<table class=\"cms_formular\" id=\"cms_eintrag_lt_$ltid\">";
        $lobundtadel .= "<tr><th>Person:</th><td><select name=\"cms_eintrag_lt_person_$ltid\" id=\"cms_eintrag_lt_person_$ltid\">".str_replace("value=\"$ltperson\"", "value=\"$ltperson\" selected=\"selected\"", $ltpersonen)."</select></td></tr>";
      	$lobundtadel .= "<tr><th>Art:</th><td><select name=\"cms_eintrag_lt_art_$ltid\" id=\"cms_eintrag_lt_art_$ltid\">".str_replace("value=\"$ltart\"", "value=\"$ltart\" selected=\"selected\"", $ltartwahl)."</select></td></tr>";
        $lobundtadel .= "<tr><th>Bemerkung:</th><td><textarea name=\"cms_eintrag_lt_bemerkung_$ltid\" id=\"cms_eintrag_lt_bemerkung_$ltid\">$ltbem</textarea></td></tr>";
      	$lobundtadel .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_eintrag_ltweg('$ltid');\">– Lob / Tadel entfernen</span></td></tr>";
        $lobundtadel .= "</table>";
        $ltanzahl++;
        $ltnr++;
        $ltids .= "|".$ltid;
      }
    }
    $sql->close();

    $code .= "<h2>Lob und Tadel</h2>";
    $code .= "<p><input type=\"hidden\" value=\"$ltanzahl\" id=\"cms_eintrag_ltan\" name=\"cms_eintrag_ltan\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$ltnr\" id=\"cms_eintrag_ltnr\" name=\"cms_eintrag_ltnr\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$ltids\" id=\"cms_eintrag_ltids\" name=\"cms_eintrag_ltids\"></p>";
    $code .= "<div id=\"cms_eintrag_lobundtadel\">$lobundtadel</div>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_eintrag_ltdazu()\">+ Lob oder Tadel hinzufügen</span></p>";



    $code .= "<p><span class=\"cms_button\" onclick=\"cms_tagebuch_eintrag_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Nutzerkonto/Tagebuch\">Abbrechen</a></p>";
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
