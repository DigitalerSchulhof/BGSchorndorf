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

if(!cms_check_ganzzahl($id) && ($id != '-')) {die("FEHLER");}

$zugriff = false;

if ($id == '-') {$zugriff = $CMS_RECHTE['Website']['Inhalte anlegen'];}
else {$zugriff = $CMS_RECHTE['Website']['Inhalte bearbeiten'];}

if (($zugriff) && ($angemeldet)) {
  $fehler = false;

  $neu = true;
  $inhalt = "";
  $aktiv = 0;
  if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $dbs = cms_verbinden('s');
    $sql = "SELECT * FROM editoren WHERE id = $id";
    if ($anfrage = $dbs->query($sql)) { // Safe weil ID Check
      if ($daten = $anfrage->fetch_assoc()) {
        if ($modus == 'Aktuell') {$inhalt = $daten['aktuell'];}
        else if ($modus == 'Neu') {$inhalt = $daten['neu'];}
        else if ($modus == 'Alt') {$inhalt = $daten['alt'];}
        else {$fehler = true;}

        $aktiv = $daten['aktiv'];
      }
      else {$fehler = true;}
      $anfrage->free();
    }
    else {$fehler = true;}
    cms_trennen($dbs);
  }

  if (!$fehler) {
    $_SESSION['ELEMENTPOSITION'] = $position;
    $_SESSION['ELEMENTSPALTE'] = $spalte;
    $_SESSION['ELEMENTID'] = $id;

    if ($id == '-') {$code = "<h3>Neuer Editor</h3>";}
    else {$code = "<h3>Editor bearbeiten</h3>";}
    $code .= "<table class=\"cms_formular\">";
    if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('website_element_editoren_aktiv', $aktiv)."</td></tr>";}
    else {$code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"cms_website_element_editoren_aktiv\" name=\"cms_website_element_editoren_aktiv\" value=\"0\"></td></tr>";}
    $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_editoren_position', $position, $maxpos, $neu)."</td></tr>";
    $code .= "</table>";

    $code .= "<div id=\"cms_website_element_editor\">".$inhalt."</div>";

    $code .= "<p>";
    if ($id == '-') {$code .= "<span class=\"cms_button\" onclick=\"cms_editoren_neu_speichern('$zusatz')\">Speichern</span> ";}
    else {$code .= "<span class=\"cms_button\" onclick=\"cms_editoren_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";}
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
