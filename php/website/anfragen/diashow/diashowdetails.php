<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

postLesen(array("id", "spalte", "position", "modus", "zusatz"));

if (isset($_SESSION['ELEMENTMAXPOS'])) {$maxpos = $_SESSION['ELEMENTMAXPOS'];} else {echo "FEHLER"; exit;}
$dbs = cms_verbinden("s");


if(!cms_check_ganzzahl($id) && ($id != '-')) {die("FEHLER");}

$zugriff = false;

if ($id == '-') {$zugriff = cms_r("website.elemente.diashow.anlegen");}
else {$zugriff = cms_r("website.elemente.diashow.bearbeiten");}

if (cms_angemeldet() && $zugriff) {
  $fehler = false;

  $benutzertyp = $_SESSION["BENUTZERART"];

  $neu = true;
  $titel = "Neue Diashow";

  if (cms_r("website.freigeben")) {$aktiv = 1;}
  $dbs = cms_verbinden('s');

  if ($id != '-') {
    $neu = false;
    $modusk = strtolower($modus);
    $sql = $dbs->prepare("SELECT * FROM diashows WHERE id = ?");
    $sql->bind_param("i", $id);
    if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
      if ($sql->execute()) {
        $ergebnis = $sql->get_result();
        if ($daten = $ergebnis->fetch_assoc()) {
          $titel = $daten['titel'.$modusk];
          $aktiv = $daten['aktiv'];
        }
        else {$fehler = true;}
      } else $fehler = true;
    }
    else {$fehler = true;}
    $sql->close();
    cms_trennen($dbs);
  }

  if (!$fehler) {
    $_SESSION['ELEMENTPOSITION'] = $position;
    $_SESSION['ELEMENTSPALTE'] = $spalte;
    $_SESSION['ELEMENTID'] = $id;

    if ($id == '-')
      $code = "<h3>Neue Diashow</h3>";
    else
      $code = "<h3>Diashow bearbeiten</h3>";

    $code .= "<table class=\"cms_formular\">";

    $typen = array();

    if (cms_r("website.freigeben"))
        $code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('website_element_diashow_aktiv', $aktiv)."</td></tr>";
      else
        $code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"website_element_diashow_aktiv\" name=\"website_element_diashow_aktiv\" value=\"0\"></td></tr>";

      $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_diashow_position', $position, $maxpos, $neu)."</td></tr>";
      $code .= "<tr><th>Titel:</th><td><input id=\"cms_website_element_diashow_titel\" value=\"$titel\"></td>";
    $code .= "</table>";

    // Bilder laden
    $bilder = array();
    if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
      $modusk = strtolower($modus);
      $sql = "SELECT id, pfad$modusk, beschreibung$modusk FROM diashowbilder WHERE diashow = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_result($bid, $pfad, $beschreibung);
      $sql->bind_param("i", $id);
      $sql->execute();
      while($sql->fetch()) {
        $bilder[] = array("pfad" => $pfad, "beschreibung" => $beschreibung, "id" => $bid);
      }
    }
    $code .= "<h3>Bilder</h3>";

    $code .= cms_dateiwaehler_generieren('website', 'website', 'cms_galerien_dateien', 's', 'website', '-', 'bilder', true);

    $code .= "<div id=\"cms_bilder\">";
      // Entferne Bilder rausnehmen
      $bb = $bilder;
      $bilder = array();
      foreach($bb as $b) {
        if($b["pfad"] != "") {
          $bilder[] = $b;
        }
      }
      foreach($bilder as $i => $bild) {
        $i++;
        $pfad = $bild["pfad"];
        $beschreibung = $bild["beschreibung"];
        $id = $bild["id"];
        $code .= "<table class=\"cms_formular\" id=\"cms_bildtemp$i\">";
          $code .= "<tr>";
            $code .= "<th>Datei:</th><td><input id=\"cms_bild_datei_temp$i\" name=\"cms_bild_datei_temp$i\" type=\"hidden\" value=\"$pfad\"><input id=\"cms_bild_id_temp$i\" name=\"cms_bild_id_temp$i\" type=\"hidden\" value=\"$id\">";
            $code .= "<p class=\"cms_notiz cms_vorschau\" id=\"cms_bild_datei_temp{$i}_vorschau\"><img src=\"$pfad\"></p>";
            $code .= "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'website', '-', 'website', 'cms_bild_datei_temp$i', 'vorschaubild', '-', '-')\">Bild auswählen</span></p>";
            $code .= "<p id=\"cms_bild_datei_temp{$i}_verzeichnis\"></p>";
          $code .= "</tr>";
          $code .= "<tr>";
            $code .= "<th>Beschreibung: </th>";
            $code .= "<td><textarea name=\"cms_bild_beschreibung_temp$i\" id=\"cms_bild_beschreibung_temp$i\">$beschreibung</textarea></td>";
          $code .= "</tr>";
          $code .= "<tr>";
            $code .= "<th></th>";
            $code .= "<td><span class=\"cms_button_nein\" onclick=\"cms_bild_entfernen('temp$i')\">- Bild entfernen</span></td>";
          $code .= "</tr>";
        $code .= "</table>";
      }
    $code .= "</div>";

    $bilderIDs = "";
    foreach($bilder as $i => $bild) {
      $bilderIDs .= "|temp".($i+1);
    }

    $code .= "<input type=\"hidden\" id=\"cms_bilder_anzahl\" name=\"cms_bilder_anzahl\" value=\"".count($bilder)."\">";
    $code .= "<input type=\"hidden\" id=\"cms_bilder_nr\" name=\"cms_bilder_nr\" value=\"".count($bilder)."\">";
    $code .= "<input type=\"hidden\" id=\"cms_bilder_ids\" name=\"cms_bilder_ids\" value=\"$bilderIDs\">";

    $code .= "</div><br>";
    $code .= "<p>";
      if ($id == '-') {
          $code .= "<span class=\"cms_button\" onclick=\"cms_diashows_neu_speichern('$zusatz')\">Speichern</span> ";
      } else {
        $code .= "<span class=\"cms_button\" onclick=\"cms_diashows_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";
      }
      $code .= "<span class=\"cms_button cms_button_nein\" onclick=\"cms_menuebearbeiten_ausblenden('$spalte')\">Abbrechen</span> ";
    $code .= "</p>";
    echo $code;
  }
  else {
    echo "FEHLER";
  }
}
else {echo "BERECHTIGUNG";}
?>
