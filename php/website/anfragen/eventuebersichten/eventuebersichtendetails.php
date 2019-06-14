<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/dateisystem.php");

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

if (($zugriff) && ($angemeldet)) {
  $fehler = false;

  $neu = true;
  $termine = '1';
  $blog = '1';
  $galerie = '0';
  $termineanzahl = 10;
  $bloganzahl = 5;
  $galerieanzahl = 5;
  if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $dbs = cms_verbinden('s');
    $modusk = strtolower($modus);
    $sql = "SELECT * FROM eventuebersichten WHERE id = $id";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
          $termine = $daten['termine'.$modusk];
          $blog = $daten['blog'.$modusk];
          $galerie = $daten['galerie'.$modusk];
          $termineanzahl = $daten['termineanzahl'.$modusk];
          $bloganzahl = $daten['bloganzahl'.$modusk];
          $galerieanzahl = $daten['galerieanzahl'.$modusk];
        }
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

    if ($id == '-') {$code = "<h3>Neue Eventuebersicht</h3>";}
    else {$code = "<h3>Eventuebersicht bearbeiten</h3>";}
    $code .= "<table class=\"cms_formular\">";
    if ($CMS_RECHTE['Website']['Inhalte freigeben']) {$code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('website_element_eventuebersicht_aktiv', $aktiv)."</td></tr>";}
    else {$code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"website_element_eventuebersicht_aktiv\" name=\"website_element_eventuebersicht_aktiv\" value=\"0\"></td></tr>";}
    $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_eventuebersicht_position', $position, $maxpos, $neu)."</td></tr>";
    $code .= "<tr><th>Termine:</th><td>".cms_schieber_generieren('website_element_eventuebersicht_termine', $termine, 'cms_eventuebersichten_aendern(\'termine\');')."</td></tr>";
    if ($termine != '1') {$style = "display: none;";} else {$style = "display: table-row;";}
    $code .= "<tr style=\"$style\" id=\"cms_website_element_eventuebersicht_termine_zeile\"><th><span class=\"cms_hinweis_aussen\">Terminanzahl:<span class=\"cms_hinweis\">Wie viele anstehenden Termine sollen angezeigt werden?</span></span></th><td><input type=\"number\" class=\"cms_klein\" id=\"cms_website_element_eventuebersicht_termineanzahl\" name=\"cms_website_element_eventuebersicht_galerienanzahl\" value=\"$termineanzahl\"></td></tr>";
    $code .= "<tr><th>Blog:</th><td>".cms_schieber_generieren('website_element_eventuebersicht_blog', $blog, 'cms_eventuebersichten_aendern(\'blog\');')."</td></tr>";
    if ($blog != '1') {$style = "display: none;";} else {$style = "display: table-row;";}
    $code .= "<tr style=\"$style\" id=\"cms_website_element_eventuebersicht_blog_zeile\"><th><span class=\"cms_hinweis_aussen\">Bloganzahl:<span class=\"cms_hinweis\">Wie viele der letzten Blogeinträge sollen angezeigt werden?</span></span></th><td><input type=\"number\" class=\"cms_klein\" id=\"cms_website_element_eventuebersicht_bloganzahl\" name=\"cms_website_element_eventuebersicht_galerienanzahl\" value=\"$bloganzahl\"></td></tr>";
    $code .= "<tr><th>Galerien:</th><td>".cms_schieber_generieren('website_element_eventuebersicht_galerien', $galerie, 'cms_eventuebersichten_aendern(\'galerien\');')."</td></tr>";
    if ($galerie != '1') {$style = "display: none;";} else {$style = "display: table-row;";}
    $code .= "<tr style=\"$style\" id=\"cms_website_element_eventuebersicht_galerien_zeile\"><th><span class=\"cms_hinweis_aussen\">Galerienanzahl:<span class=\"cms_hinweis\">Wie viele der letzten Galerien sollen angezeigt werden?</span></span></th><td><input type=\"number\" class=\"cms_klein\" id=\"cms_website_element_eventuebersicht_galerienanzahl\" name=\"cms_website_element_eventuebersicht_galerienanzahl\" value=\"$galerieanzahl\"></td></tr>";
    $code .= "</table>";

    $code .= "<p>";
    if ($id == '-') {$code .= "<span class=\"cms_button\" onclick=\"cms_eventuebersichten_neu_speichern('$zusatz')\">Speichern</span> ";}
    else {$code .= "<span class=\"cms_button\" onclick=\"cms_eventuebersichten_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";}
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