<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Stundenplanung</h1>

<?php
include_once('php/schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php');

if (cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {
  $zeitraum = $_SESSION["STUNDENPLANZEITRAUM"];

  $fehler = false;
  $meldefehler = false;
  $lehrkraft = "-";
  $raum = "-";
  $klasse = "-";
  $kurs = "-";

  // Schuljahr und Zeitraum laden
  $dbs = cms_verbinden('s');
  $sql = $dbs->prepare("SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, schuljahr, zeitraeume.beginn AS beginn, zeitraeume.ende AS ende FROM zeitraeume JOIN schuljahre ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?");
  $sql->bind_param("i", $zeitraum);
  if ($sql->execute() {
    $sql->bind_result($schuljahrbez, $schuljahr, $beginn, $ende);
    if ($sql->fetch()) {
      $zeitraumbez = cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('n', $beginn))." ".date('Y', $beginn)." bis ";
      $zeitraumbez .= cms_tagnamekomplett(date('N', $ende)).", den ".date('d', $ende).". ".cms_monatsnamekomplett(date('n', $ende))." ".date('Y', $ende);
    } else {$fehler = true;}
    $sql->close();
  } else {$fehler = true;}

  // Lehrer suchen
  $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id)");
  $sql .= " AS x ORDER BY nachname ASC, vorname ASC, kuerzel ASC";
  $lehreroptionen = "";
  if ($sql->execute()) {
    $sql->bind_result($pid, $pvor, $pnach, $ptit, $pkurz);
    while ($sql->fetch()) {
      if ($lehrkraft == '-') {$lehrkraft = $pid;}
      $lehreroptionen .= "<option id=\"cms_stundenplanung_lehrkraft_$pid\" value=\"$pid\">";
        $lehreroptionen .= cms_generiere_anzeigename($pvor, $pnach, $ptit)." ($pkurz)";
      $lehreroptionen .= "</option>";
    }
  } else {$fehler = true;}
  $sql->close();

  // Räume suchen
  $sql = $dbs->prepare("SELECT * FROM (SELECT id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume) AS x ORDER BY bezeichnung ASC");
  $raumoptionen = "";
  if ($sql->execute()) {
    $sql->bind_result($rid, $rbez);
    while ($sql->fetch()) {
      if ($raum == '-') {$raum = $rid;}
      $raumoptionen .= "<option id=\"cms_stundenplanung_raum_$rid\" value=\"$rid\">";
        $raumoptionen .= $rbez;
      $raumoptionen .= "</option>";
    }
  } else {$fehler = true;}
  $sql->close();

  if (!$fehler) {
    // Klassen suchen
    $sql = $dbs->prepare("SELECT * FROM (SELECT reihenfolge, klassen.id AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM klassen JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id WHERE schuljahr = ?) AS x ORDER BY reihenfolge ASC, stufe ASC, bezeichnung ASC");
    $klassenoptionen = "";
    $sql->bind_param("i", $schuljahr);
    if ($sql->execute()) {
      $sql->bind_result($rf, $kid, $kbez, $kstufe);
      while ($sql->fetch()) {
        if ($klasse == '-') {$klasse = $kid;}
        $klassenoptionen .= "<option id=\"cms_stundenplanung_klasse_$kid\" value=\"$kid\">";
          $klassenoptionen .= $kstufe." ".$kbez;
        $klassenoptionen .= "</option>";
      }
    } else {$fehler = true;}
    $sql->close();
  }

  if ($klasse == '-') {echo cms_meldung('info', '<h4>Keine Klassen</h4><p>In diesem Schuljahr wurden noch keine Klassen angelegt.</p>'); $meldefehler = true;}

  if (!$fehler && !$meldefehler) {
    // Kurse suchen
    $sql = $dbs->prepare("SELECT kurse.id AS id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(faecher.bezeichnung, '$CMS_SCHLUESSEL') AS fach, AES_DECRYPT(faecher.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM (SELECT kurs FROM kursklassen WHERE klasse = ?) AS x JOIN kurse ON x.kurs = kurse.id JOIN faecher ON kurse.fach = faecher.id ORDER BY fach ASC, bezeichnung ASC");
    $sql->bind_param("i", $klasse);
    $kursoptionen = "";
    if ($sql->execute()) {
      $sql->bind_result($kid, $kbez, $fbez, $fkurz);
      while ($sql->fetch()) {
        if ($kurs == '-') {$kurs = $kid;}
        $kursoptionen .= "<option id=\"cms_stundenplanung_kurs_$kid\" value=\"$kid\">";
          $kursoptionen .= $kbez." - $fbez ($fkurz)";
        $kursoptionen .= "</option>";
      }
    } else {$fehler = true;}
    $sql->close();
  }

  if ($kurs == '-') {echo cms_meldung('info', '<h4>Keine Kurse</h4><p>Für diese Klasse wurden noch keine Kurse angelegt.</p>'); $meldefehler = true;}

  // Suche alle Klassen, in denen dieser Kurs vorkommt
  if (!$fehler && !$meldefehler) {
    // Kurse suchen
    $sql = $dbs->prepare("SELECT klasse FROM (SELECT klasse, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM kursklassen JOIN klassen ON kursklassen.klasse = klassen.id WHERE kurs = ? AND klasse != ?) AS x ORDER BY bezeichnung");
    $sql->bind_param("ii", $kurs, $klasse);
    if ($sql->execute()) {
      if (strlen($klasse) > 0) {$klasse = "|".$klasse;}
      $sql->bind_result($kid);
      while ($sql->fetch()) {
        $klassen .= "|".$kid;
      }
    } else {$fehler = true;}
    $sql->close();
  }

  if (!$fehler && !$meldefehler) {
    // Stundenpläne erzeugen
    $lehrerstundenplan = cms_stundenplan_erzeugen($dbs, $zeitraum, 'l', $lehrkraft);
    $raumstundenplan = cms_stundenplan_erzeugen($dbs, $zeitraum, 'r', $raum);
    $klassenfehler = false;
    if ($klasse != '-') {
      $klassen = explode('|', $klasse);
      $breite = 100 / count($klassen)-1;
      if ($breite > 37.5) {$breite = 37.5;}
      $klassenstundenplan = "";
      for ($k=1; $k<count($klassen); $k++) {
        $stundenplan = cms_stundenplan_erzeugen($dbs, $zeitraum, 'k', $klassen[$k]);
        if (!$stundenplan) {$klassenfehler = true;}
        $klassenstundenplan .= "<div class=\"cms_klassenstundenplan\" style=\"width: $breite%;\"><div class=\"cms_spalte_i\">".$stundenplan."</div></div>";
      }
    }

    if ((!$lehrerstundenplan) || (!$raumstundenplan) || ($klassenfehler)) {$fehler = true;}
  }
  cms_trennen($dbs);

  if ($fehler) {echo cms_meldung_fehler();}
  else if (!$meldefehler) {
    $code = "</div>";
    $code .= "<div class=\"cms_vollbild\" id=\"cms_stundenplanfenster\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<h2>Stundenpläne Schuljahr $schuljahrbez<br> $zeitraumbez</h2>";
    $code .= "<span id=\"cms_stundenplanfenster_oeffnen\" class=\"cms_iconbutton cms_button_vollbild_oeffnen\" onclick=\"cms_vollbild_oeffnen('cms_stundenplanfenster')\">Vollbild</span>";
    $code .= "<span id=\"cms_stundenplanfenster_schliessen\" class=\"cms_iconbutton cms_button_vollbild_schliessen\" onclick=\"cms_vollbild_schliessen('cms_stundenplanfenster')\" style=\"display: none;\">In der Seite</span>";
    $code .= "</div>";

    // Erste Zeile, Auswahl, Lehrer, Raum
    $code .= "<div class=\"cms_spalte_4\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<h3>Details</h3>";
    $code .= "<table class=\"cms_formular\">";
      $code .= "<tr>";
        $code .= "<th>Lehrkraft:</th>";
        $code .= "<td><select id=\"cms_stundenplanung_lehrkraft\" onchange=\"cms_stundenplan_neuerstundenplan('l');\">";
          $code .= $lehreroptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Raum:</th>";
        $code .= "<td><select id=\"cms_stundenplanung_raum\" onchange=\"cms_stundenplan_neuerstundenplan('r');\">";
          $code .= $raumoptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Klasse:</th>";
        $code .= "<td><select id=\"cms_stundenplanung_klasse\" onchange=\"cms_stundenplan_kurse_laden();\">";
          $code .= $klassenoptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Kurs:</th>";
        $code .= "<td><select id=\"cms_stundenplanung_kurs\" onchange=\"cms_stundenplan_neuerstundenplan('k');\">";
          $code .= $kursoptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
    $code .= "</table>";

    $code .= "<h3>Gewählte Stunde</h3>";
    $code .= "<div id=\"cms_stundenplanung_gewaehlte_stunde\"><p class=\"cms_notiz\">Keine Schulstunde ausgewählt.</p></div>";
    $code .= "<p><span class=\"cms_button\" onclick=\"cms_stundenplan_stunde_auswahlentfernen();\">Auswahl entfernen</span></p>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_34\">";
    $code .= "<div class=\"cms_spalte_2\">";
    $code .= "<div class=\"cms_spalte_i\" id=\"cms_stundenplanung_lehrerslot\">";
    $code .= $lehrerstundenplan;
    $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_2\">";
    $code .= "<div class=\"cms_spalte_i\" id=\"cms_stundenplanung_raumslot\">";
    $code .= $raumstundenplan;
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_clear\"></div>";

    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "</div>";
    $code .= "<div class=\"cms_klassenstundenplaene\" id=\"cms_stundenplanung_klassenslot\">";
    $code .= $klassenstundenplan;
    $code .= "<div class=\"cms_clear\"></div>";
    $code .= "</div>";

    $code .= "</div>";
    echo $code;
  }
} else {echo cms_meldung_bastler();echo "</div>";}
?>


<div class="cms_clear"></div>
