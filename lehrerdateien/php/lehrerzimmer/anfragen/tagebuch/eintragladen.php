<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['unterricht'])) 	{$unterricht = $_POST['unterricht'];} 		        else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

// Daten übertragen

$dbs = cms_verbinden('s');
$dbl = cms_verbinden('l');

include_once("../../lehrerzimmer/anfragen/tagebuch/uebertragen.php");

// Stundeninformationen laden
$tlehrer = null;
$sql = $dbs->prepare("SELECT unterricht.id, tbeginn, tende, traum, tkurs, tlehrer, AES_DECRYPT(lehr.vorname, '$CMS_SCHLUESSEL') AS lehrvor, AES_DECRYPT(lehr.nachname, '$CMS_SCHLUESSEL') AS lehrnach, AES_DECRYPT(lehr.titel, '$CMS_SCHLUESSEL') AS lehrtitel, kuerzel, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') FROM unterricht LEFT JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN personen AS lehr ON lehr.id = tlehrer LEFT JOIN kurse ON tkurs = kurse.id LEFT JOIN raeume ON traum = raeume.id WHERE unterricht.id = ?");
$sql->bind_param("i", $unterricht);
if ($sql->execute()) {
  $sql->bind_result($uid, $tbeginn, $tende, $traum, $tkurs, $tlehrer, $lehrvor, $lehrnach, $lehrtitel, $kuerzel, $raumbez, $kursbez);
  $sql->fetch();
  $zeit = cms_tagnamekomplett(date('N', $tbeginn)).", den ".date("d.m.Y H:i", $tbeginn)." – ".date("H:i", $tende+1);
}
$sql->close();

// Benutzerart laden
$CMS_BENUTZERART = "";
$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
  $sql->bind_result($CMS_BENUTZERART);
  $sql->fetch();
}
$sql->close();

// Tagebuchinformationen laden
$tid = null;
$sql = $dbl->prepare("SELECT tagebuch.id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSELL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSELL'), leistungsmessung, freigabe, urheber, eintragsdatum FROM tagebuch WHERE tagebuch.id = ?");
$sql->bind_param("i", $uid);
if ($sql->execute()) {
  $sql->bind_result($tid, $inhalt, $hausi, $leistung, $freigabe, $urheber, $eintragsdatum);
  $sql->fetch();
}
$sql->close();

if ($tid === null) {
  // Fallback ÖServer
  $sql = $dbs->prepare("SELECT tagebuch.id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSEL'), leistungsmessung, freigabe, urheber, eintragsdatum, AES_DECRYPT(vorname ,'$CMS_SCHLUESSEL'), AES_DECRYPT(nachname ,'$CMS_SCHLUESSEL'), AES_DECRYPT(titel ,'$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel ,'$CMS_SCHLUESSEL') FROM tagebuch LEFT JOIN personen ON urheber = personen.id LEFT JOIN lehrer ON urheber = lehrer.id WHERE tagebuch.id = ? AND freigabe != 1");
  $sql->bind_param("i", $uid);
  if ($sql->execute()) {
    $sql->bind_result($tid, $inhalt, $hausi, $leistung, $freigabe, $urheber, $eintragsdatum, $vorname, $nachname, $titel, $kuerzel);
    $sql->fetch();
  }
  $sql->close();
} else {
  $sql = $dbs->prepare("SELECT AES_DECRYPT(vorname ,'$CMS_SCHLUESSEL'), AES_DECRYPT(nachname ,'$CMS_SCHLUESSEL'), AES_DECRYPT(titel ,'$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel ,'$CMS_SCHLUESSEL') FROM personen LEFT JOIN lehrer ON personen.id = lehrer.id WHERE personen.id = ?");
  $sql->bind_param("i", $urheber);
  if ($sql->execute()) {
    $sql->bind_result($vorname, $nachname, $titel, $kuerzel);
    $sql->fetch();
  }
  $sql->close();
}

if ($angemeldet && $CMS_BENUTZERART == 'l' && $tlehrer == $CMS_BENUTZERID) {
  $code = "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Stundendetails</h2>";

    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Stunde:</th><td>$zeit in Raum $raumbez</td></tr>";
    if ($urheber !== null) {
      $code .= "<tr><th>Letze Änderung:</th><td>".cms_generiere_anzeigename($vorname, $nachname, $titel);
      if ($kuerzel !== null) {$code .= " ($kuerzel) ";}
      $code .= " – ".date("d.m.Y H:i", $eintragsdatum)."</td></tr>";
    }
    $code .= "<tr><th>Stundeninhalt:</th><td><textarea id=\"cms_eintrag_inhalt\" name=\"cms_eintrag_inhalt\">$inhalt</textarea></td></tr>";
    $code .= "<tr><th>Hausaufgaben:</th><td><textarea id=\"cms_eintrag_hausi\" name=\"cms_eintrag_hausi\">$hausi</textarea></td></tr>";

    $code .= "<tr><th>Leistungsmessung:</th><td>".cms_generiere_schieber('eintrag_leistungsmessung', $leistung)."</td></tr>";
    $code .= "<tr><th>Freigeben:</th><td>".cms_generiere_schieber('eintrag_freigabe', '1')."</td></tr>";
    $code .= "</table>";

    // Schüler des Kurses laden
    $schueler = "";
    $schuelerids = [];
    $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM kursemitglieder JOIN personen ON person = personen.id WHERE gruppe = ? AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname, vorname, titel");
    $sql->bind_param("i", $tkurs);
    if ($sql->execute()) {
      $sql->bind_result($pid, $vorname, $nachname, $titel);
      while ($sql->fetch()) {
        $schueler .= "<option value=\"$pid\">".cms_generiere_anzeigename($vorname, $nachname, $titel)."</option>";
        array_push($schuelerids, $pid);
      }
    }
    $sql->close();
    $code .= "<p style=\"display:none\"><select name=\"cms_eintrag_vorlage\" id=\"cms_eintrag_vorlage\"><option value=\"-\">-- Bitte wählen --</option>$schueler</select></p>";

    $t = date("d", $tbeginn);
    $m = date("m", $tbeginn);
    $j = date("Y", $tbeginn);
    $a = mktime(0,0,0,$m,$t,$j);
    $x = mktime(0,0,0,$m,$t+1,$j)-1;
    $fehlzeiten = "";
    $fzanzahl = 0;
    $fznr = 0;
    $fzids = "";
    $code .= "<h2>Fehlzeiten</h2>";

    // Fehlzeiten laden
    if (count($schuelerids) > 0) {
      $schuelerids = "(".implode(",", $schuelerids).")";
      $sql = $dbl->prepare("SELECT fehlzeiten.id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN $schuelerids");
      $sql->bind_param("iiii", $a, $x, $a, $x);
      if ($sql->execute()) {
        $sql->bind_result($fid, $fzperson, $fzvon, $fzbis, $fzbem);
        while ($sql->fetch()) {
          $fzbis++;
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
    }

    $code .= "<div id=\"cms_eintrag_fehlzeiten\">";
    $code .= $fehlzeiten;
    $code .= "</div>";
    $code .= "<p><input type=\"hidden\" value=\"$fzanzahl\" id=\"cms_eintrag_fzan\" name=\"cms_eintrag_fzan\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$fznr\" id=\"cms_eintrag_fznr\" name=\"cms_eintrag_fznr\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$fzids\" id=\"cms_eintrag_fzids\" name=\"cms_eintrag_fzids\"></p>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_eintrag_fzdazu('$tbeginn', '$tende', 'ln')\">+ Fehlzeit hinzufügen</span></p>";

    // Lob und Tadel laden
    $lobundtadel = "";
    $ltanzahl = 0;
    $ltnr = 0;
    $ltids = "";
    $ltpersonen = $schueler."<option value=\"a\">ganzer Kurs</option>";
    $ltartwahl = "<option value=\"m\">Mitarbeits-Tadel</option><option value=\"v\">Verhaltens-Tadel</option><option value=\"l\">Lob</option>";
    $sql = $dbl->prepare("SELECT lobtadel.id, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM lobtadel WHERE eintrag = ?");
    $sql->bind_param("i", $uid);
    if ($sql->execute()) {
      $sql->bind_result($ltid, $ltperson, $ltart, $ltbem);
      while ($sql->fetch()) {
        if ($ltperson == null) {$ltperson = "a";}
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
    $code .= "<div id=\"cms_eintrag_lobundtadel\">$lobundtadel</div>";
    $code .= "<p><input type=\"hidden\" value=\"$ltanzahl\" id=\"cms_eintrag_ltan\" name=\"cms_eintrag_ltan\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$ltnr\" id=\"cms_eintrag_ltnr\" name=\"cms_eintrag_ltnr\"></p>";
    $code .= "<p><input type=\"hidden\" value=\"$ltids\" id=\"cms_eintrag_ltids\" name=\"cms_eintrag_ltids\"></p>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_eintrag_ltdazu()\">+ Lob oder Tadel hinzufügen</span></p>";

    $code .= "<p><span class=\"cms_button\" onclick=\"cms_tagebuch_eintrag_speichern('ln', '$unterricht');\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Nutzerkonto/Tagebuch\">Abbrechen</a></p>";

  $code .= "</div></div>";
  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";

    $code .= "<h2>Tagesansicht</h2>";
      // Klassen laden, denen dieser Kurs zugeordnet ist
      $klassen = "";
      $klassenids = [];
      $gewaehlt = false;
      $kgewaehlt = null;
      $sql = $dbs->prepare("SELECT * FROM (SELECT klasse, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM kurseklassen JOIN klassen ON klasse = klassen.id WHERE kurs = ?) AS x ORDER BY bez");
      $sql->bind_param("i", $tkurs);
      if ($sql->execute()) {
        $sql->bind_result($kid, $kbez);
        while ($sql->fetch()) {
          $cssklasse = "cms_button";
          if (!$gewaehlt) {
            $cssklasse = "cms_button_ja";
            $gewaehlt = true;
            $kgewaehlt = $kid;
          }
          $klassen .= "<span id=\"cms_klassen_$kid\" class=\"$cssklasse\" onclick=\"cms_tagebuch_klassewechseln('$kid', '$uid')\">$kbez</span> ";
          array_push($klassenids, $kid);
        }
      }
      $sql->close();

      include_once("../../lehrerzimmer/anfragen/tagebuch/tagebuchladen.php");

      if (count($klassenids) > 0) {
        $code .= "<p><input type=\"hidden\" id=\"cms_tagebuch_alleklassen\" name=\"cms_tagebuch_alleklassen\" value=\"".implode("|", $klassenids)."\">";
        $code .= "<input type=\"hidden\" id=\"cms_tagebuch_klasseg\" name=\"cms_tagebuch_klasseg\" value=\"$kgewaehlt\">";
        if (count($klassenids) != 1) {$code .= $klassen;}
        $code .= "</p>";
        $code .= "<table class=\"cms_zeitwahl\"><tbody><tr>";
        $code .= "<td><span class=\"cms_button\" onclick=\"cms_tagebuch_tagesansicht('-', '$uid')\">«</span></td>";
        $code .= "<td>".cms_datum_eingabe("cms_tagebuch_tagesansicht_tag", $t, $m, $j, "cms_tagebuch_tagesansicht('j', '$uid')")."</td>";
        $code .= "<td><span class=\"cms_button\" onclick=\"cms_tagebuch_tagesansicht('+', '$uid')\">»</span></td></tr></tbody></table>";
        $code .= "<div id=\"cms_tagebuch_tagesansicht\">";
        $code .= cms_tagebucheintrag_tag($dbs, $dbl, $kgewaehlt, $t, $m, $j);
      } else {
        $code .= "<div id=\"cms_tagebuch_tagesansicht\">";
        $code .= cms_tagebucheintrag_kurs($dbs, $dbl, $tkurs, $t, $m, $j, "5");
      }

    $code .= "</div>";

  $code .= "</div></div><div class=\"cms_clear\"></div>";
	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}

cms_trennen($dbl);
cms_trennen($dbs);
?>
