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


$angemeldet = cms_angemeldet();

$zugriff = false;

if ($id == '-') {$zugriff = cms_r("website.elemente.download.anlegen");}
else {$zugriff = cms_r("website.elemente.download.bearbeiten");}

if(!cms_check_ganzzahl($id) && ($id != '-')) {die("FEHLER");}

if (cms_angemeldet() && $zugriff) {
  $fehler = false;

  $neu = true;
  $pfad = "";
  $titel = "";
  $beschreibung = "";
  $name = 1;
  $groesse = 1;
  if (cms_r("website.freigeben")) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $dbs = cms_verbinden('s');
    $sql = $dbs->prepare("SELECT * FROM downloads WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $ergebnis = $sql->get_result();
      if ($daten = $ergebnis->fetch_assoc()) {
        if ($modus == 'Aktuell') {
          $pfad = $daten['pfadaktuell'];
          $titel = $daten['titelaktuell'];
          $beschreibung = $daten['beschreibungaktuell'];
          $name = $daten['dateinameaktuell'];
          $groesse = $daten['dateigroesseaktuell'];
        }
        else if ($modus == 'Neu') {
          $pfad = $daten['pfadneu'];
          $titel = $daten['titelneu'];
          $beschreibung = $daten['beschreibungneu'];
          $name = $daten['dateinameneu'];
          $groesse = $daten['dateigroesseneu'];
        }
        else if ($modus == 'Alt') {
          $pfad = $daten['pfadalt'];
          $titel = $daten['titelalt'];
          $beschreibung = $daten['beschreibungalt'];
          $name = $daten['dateinamealt'];
          $groesse = $daten['dateigroessealt'];
        }
        else {$fehler = true;}

        $aktiv = $daten['aktiv'];
      }
      else {$fehler = true;}
      $anfrage->free();
    }
    else {$fehler = true;}
    $sql->close();
    cms_trennen($dbs);
  }

  if (!$fehler) {
    $_SESSION['ELEMENTPOSITION'] = $position;
    $_SESSION['ELEMENTSPALTE'] = $spalte;
    $_SESSION['ELEMENTID'] = $id;

    if ($id == '-') {$code = "<h3>Neuer Download</h3>";}
    else {$code = "<h3>Download bearbeiten</h3>";}
    $code .= "<table class=\"cms_formular\">";
    if (cms_r("website.freigeben")) {$code .= "<tr><th>Aktiv:</th><td>".cms_generiere_schieber('website_element_downloads_aktiv', $aktiv)."</td></tr>";}
    else {$code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"cms_website_element_downloads_aktiv\" name=\"cms_website_element_downloads_aktiv\" value=\"0\"></td></tr>";}
    $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_downloads_position', $position, $maxpos, $neu)."</td></tr>";
    $code .= "<tr><th>Datei:</th><td>".cms_dateiwahl_knopf ('website', 'cms_website_element_downloads_datei', 's', 'website', '-', 'download', $pfad)."</td></tr>";
    $code .= "<tr><th>Titel:</th><td><input type=\"text\" name=\"website_element_downloads_titel\" id=\"website_element_downloads_titel\" value=\"$titel\"></td></tr>";
    $code .= "<tr><th>Beschreibung:</th><td><textarea name=\"website_element_downloads_beschreibung\" id=\"website_element_downloads_beschreibung\">$beschreibung</textarea></td></tr>";
    $code .= "<tr><th>Dateiname anzeigen:</th><td>".cms_generiere_schieber('website_element_downloads_dateiname', $name)."</td></tr>";
    $code .= "<tr><th>Dateigröße anzeigen:</th><td>".cms_generiere_schieber('website_element_downloads_dateigroesse', $groesse)."</td></tr>";
    $code .= "</table>";

    $code .= "<p>";
    if ($id == '-') {$code .= "<span class=\"cms_button\" onclick=\"cms_downloads_neu_speichern('$zusatz')\">Speichern</span> ";}
    else {$code .= "<span class=\"cms_button\" onclick=\"cms_downloads_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";}
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
