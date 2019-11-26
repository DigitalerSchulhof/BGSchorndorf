<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");

session_start();

if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['spalte'])) {$spalte = $_POST['spalte'];} else {echo "FEHLER"; exit;}
if (isset($_POST['position'])) {$position = $_POST['position'];} else {echo "FEHLER"; exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER"; exit;}
if (isset($_POST['zusatz'])) {$zusatz = $_POST['zusatz'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTMAXPOS'])) {$maxpos = $_SESSION['ELEMENTMAXPOS'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$angemeldet = cms_angemeldet();

$zugriff = false;

if ($id == '-') {$zugriff = $CMS_RECHTE['Website']['Inhalte anlegen'];}
else {$zugriff = $CMS_RECHTE['Website']['Inhalte bearbeiten'];}

if(!cms_check_ganzzahl($id))
  die("FEHLER");

if (($zugriff) && ($angemeldet)) {
  $fehler = false;

  $neu = true;
  $ausrichtung = "n";
  $breite = 200;
  $aktiv = 0;
  $boxen = array();
  if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $modusk = strtolower($modus);
    $dbs = cms_verbinden('s');
    $sql = "SELECT * FROM boxenaussen WHERE id = $id";
    if ($anfrage = $dbs->query($sql)) { // Safe weil ID Check
      if ($daten = $anfrage->fetch_assoc()) {
        if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
          $ausrichtung = $daten['ausrichtung'.$modusk];
          $breite = $daten['breite'.$modusk];
        }
        else {$fehler = true;}
        $aktiv = $daten['aktiv'];
      }
      else {$fehler = true;}
      $anfrage->free();
    }
    else {$fehler = true;}

    $sql = "SELECT * FROM boxen WHERE boxaussen = ".$id." ORDER BY position";
    if ($anfrage = $dbs->query($sql)) { // Safe weil ID Check
      while ($daten = $anfrage->fetch_assoc()) {
        if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
          $boxen[$daten['position']-1]['titel'] = $daten['titel'.$modusk];
          $boxen[$daten['position']-1]['inhalt'] = $daten['inhalt'.$modusk];
          $boxen[$daten['position']-1]['style'] = $daten['style'.$modusk];
        }
        $boxen[$daten['position']-1]['id'] = $daten['id'];
        $boxen[$daten['position']-1]['aktiv'] = $daten['aktiv'];
      }
      $anfrage->free();
    }
    else {$fehler = true;}
    cms_trennen($dbs);
  }



  if (!$fehler) {
    $_SESSION['ELEMENTPOSITION'] = $position;
    $_SESSION['ELEMENTSPALTE'] = $spalte;
    $_SESSION['ELEMENTID'] = $id;

    if ($id == '-') {$code = "<h3>Neue Boxen</h3>";}
    else {$code = "<h3>Boxen bearbeiten</h3>";}
    $code .= "<table class=\"cms_formular\">";
    if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('website_element_boxen_aktiv', $aktiv)."</td></tr>";}
    else {$code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"cms_website_element_boxen_aktiv\" name=\"cms_website_element_boxen_aktiv\" value=\"0\"></td></tr>";}
    $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_boxen_position', $position, $maxpos, $neu)."</td></tr>";
    $code .= "<tr><th>Ausrichtung:</th><td><select id=\"cms_website_element_boxen_ausrichtung\" onchange=\"cms_boxen_ausrichtung_aendern();\">";
    if ($ausrichtung == 'u') {$szusatz = " selected=\"selected\"";} else {$szusatz = "";}
    $code .= "<option value=\"u\"$szusatz>untereinander</option>";
    if ($ausrichtung == 'n') {$szusatz = " selected=\"selected\"";} else {$szusatz = "";}
    $code .= "<option value=\"n\"$szusatz>nebeneinander</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr><th>Breite:</th><td><input class=\"cms_klein\" type=\"number\" id=\"cms_website_element_boxen_breite\" name=\"cms_website_element_boxen_breite\" value=\"$breite\" onkeyup=\"cms_boxen_breite_aendern();\"> Pixel</td></tr>";
    $code .= "</table>";

    if (count($boxen) == 0) {
      $boxen[0]['titel'] = '';
      $boxen[0]['inhalt'] = '';
      $boxen[0]['aktiv'] = 0;
      $boxen[0]['style'] = 1;
      if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$boxen[0]['aktiv'] = 1;}
      $boxen[0]['id'] = 'temp1';
    }


    $code .= "<div id=\"cms_boxen_boxen\" class=\"cms_boxen_$ausrichtung\">";
    $anzahl = 0;
    $ids = "";
    for ($i=0; $i<count($boxen); $i++) {
      $bid = $boxen[$i]['id'];
      if ($ausrichtung == 'u') {$style = "";} else {$style = " style=\"width: $breite"."px;\"";}

      $code .= "<div class=\"cms_box_$ausrichtung cms_box_".$boxen[$i]['style']."\" id=\"cms_boxen_box_$bid\"$style>";

        if ($ausrichtung == 'n') {$style = "";} else {$style = " style=\"width: $breite"."px;\"";}
        $code .= "<div class=\"cms_box_titel\" id=\"cms_box_titel_$bid\" $style>";
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tr><th>Aktiv:</th><td>";
          if ($CMS_RECHTE['Website']['Inhalte freigeben']) {
            $code .= cms_schieber_generieren('cms_boxen_box_aktiv_'.$bid, $boxen[$i]['aktiv']);
          }
          else {
            $code .= cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Bis dieser Eintrag freigegeben wird, bleibt er inaktiv.</p>');
            $code .= '<input type="hidden" name="cms_boxen_box_aktiv_'.$bid.'" id="cms_boxen_box_aktiv_'.$bid.'" value="'.$boxen[$i]['aktiv'].'">';
          }
          $code .= "</td></tr>";
          $code .= "<tr><td colspan=\"2\">";
          for ($j=1; $j<=5; $j++) {
            if ($boxen[$i]['style'] == $j) {$zklasse = "_aktiv";} else {$zklasse = "";}
            $code .= "<span class=\"cms_farbbeispiel$zklasse cms_box_style_".$j."\" id=\"cms_box_style_".$bid."_".$j."\" onclick=\"cms_box_style('$bid', '$j')\"></span> ";
          }
          $code .= "<input type=\"hidden\" name=\"cms_boxen_box_style_$bid\" id=\"cms_boxen_box_style_$bid\" value=\"".$boxen[$i]['style']."\">";
          $code .= "</td></tr>";
          $code .= "<tr><td colspan=\"2\"><span class=\"cms_button_nein\" onclick=\"cms_boxen_box_entfernen('".$boxen[$i]['id']."');\">Box löschen</span></td></tr>";
        $code .= "</table>";
        $code .= "<input type=\"text\" id=\"cms_boxen_box_titel_$bid\" name=\"cms_boxen_box_titel_$bid\" value=\"".$boxen[$i]['titel']."\">";

        $code .= "</div>";
        $code .= "<div class=\"cms_box_inhalt\" id=\"cms_boxen_box_inhalt_$bid\">";
          $code .= "<div id=\"cms_boxen_box_editor_$bid\">".$boxen[$i]['inhalt']."</div>";
          $code .= "<p><span id=\"cms_boxen_box_inhalt_bearbeiten_$bid\" class=\"cms_button\" onclick=\"cms_boxinhalt_bearbeiten('$bid');\">Bearbeiten</span> ";
          $code .= "<span id=\"cms_boxen_box_inhalt_fertig_$bid\" class=\"cms_button cms_button_ja\" onclick=\"cms_boxinhalt_bearbeiten_abschliessen('$bid');\" style=\"display: none;\">Fertig</span></p>";
        $code .= "</div>";
        $code .= "<div class=\"cms_clear\"></div>";
      $code .= "</div>";
      $anzahl++;
      $ids .= "|".$bid;
    }

    $code .= "<div class=\"cms_clear\"></div>";
    $code .= "</div>";
    $freigabe = 0;
    if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$freigabe = 1;}
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_boxen_neue_box('$freigabe');\">+ Neue Box</span>";
      $code .= "<input type=\"hidden\" id=\"cms_boxen_boxen_bearbeitung\" name=\"cms_boxen_boxen_bearbeitung\" value=\"\">";
      $code .= "<input type=\"hidden\" id=\"cms_boxen_boxen_anzahl\" name=\"cms_boxen_boxen_anzahl\" value=\"$anzahl\">";
      $code .= "<input type=\"hidden\" id=\"cms_boxen_boxen_nr\" name=\"cms_boxen_boxen_nr\" value=\"$anzahl\">";
      $code .= "<input type=\"hidden\" id=\"cms_boxen_boxen_ids\" name=\"cms_boxen_boxen_ids\" value=\"$ids\">";
    $code.= "</p>";


    $code .= "<p>";
    if ($id == '-') {$code .= "<span class=\"cms_button\" onclick=\"cms_boxen_neu_speichern('$zusatz')\">Speichern</span> ";}
    else {$code .= "<span class=\"cms_button\" onclick=\"cms_boxen_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";}
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
