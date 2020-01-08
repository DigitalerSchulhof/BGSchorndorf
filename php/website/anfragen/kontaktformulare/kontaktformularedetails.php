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

cms_rechte_laden();

if(!cms_check_ganzzahl($id) && $id != "-")
  die("FEHLER");

$zugriff = false;

if ($id == '-') {$zugriff = cms_r("website.elemente.kontaktformular.anlegen"));}
else {$zugriff = cms_r("website.elemente.kontaktformular.bearbeiten"));}

if (cms_angemeldet() && $zugriff) {
  $fehler = false;

  $neu = true;
  $betreff = 'Nachricht an Sie: ';
  $kopie = '0';
  $anhang = '1';
  $ids = array();
  $namen = array();
  $mails = array();
  $beschreibungen = array();

  if (cms_r("website.freigeben"))) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $dbs = cms_verbinden('s');
    $modusk = strtolower($modus);
    $sql = "SELECT * FROM kontaktformulare WHERE id = $id";
    if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
      if ($anfrage = $dbs->query($sql)) { // Safe weil ID Check
        if ($daten = $anfrage->fetch_assoc()) {
          $betreff = $daten['betreff'.$modusk];
          $kopie = $daten['kopie'.$modusk];
          $anhang = $daten['anhang'.$modusk];
          $aktiv = $daten['aktiv'];
        }
        else {$fehler = true;}
        $anfrage->free();
      } else $fehler = true;
      $sql = "SELECT id, name$modusk as name, beschreibung$modus as beschreibung, mail$modusk as mail FROM kontaktformulareempfaenger WHERE kontaktformular = $id";
      if($sql = $dbs->query($sql))  // TODO: Irgendwie safe machen
        while($sqld = $sql->fetch_assoc()) {
          array_push($ids, $sqld["id"]);
          array_push($namen, $sqld["name"]);
          array_push($mails, $sqld["mail"]);
          array_push($beschreibungen, $sqld["beschreibung"]);
        }
    }
    else {$fehler = true;}
    cms_trennen($dbs);
  }

  if (!$fehler) {
    $_SESSION['ELEMENTPOSITION'] = $position;
    $_SESSION['ELEMENTSPALTE'] = $spalte;
    $_SESSION['ELEMENTID'] = $id;

    if ($id == '-')
      $code = "<h3>Neues Kontaktformular</h3>";
    else
      $code = "<h3>Kontaktformular bearbeiten</h3>";

    $code .= "<table class=\"cms_formular\">";

      if (cms_r("website.freigeben")))
        $code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('website_element_kontaktformular_aktiv', $aktiv)."</td></tr>";
      else
        $code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"website_element_kontaktformular_aktiv\" name=\"website_element_kontaktformular_aktiv\" value=\"0\"></td></tr>";

      $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_kontaktformular_position', $position, $maxpos, $neu)."</td></tr>";
      $code .= "<tr><th>Betreff:</th><td><input type=\"text\" id=\"cms_website_element_kontaktformular_betreff\" name=\"cms_website_element_kontaktformular_betreff\" value=\"$betreff\"></td></tr>";
      $code .= "<tr><th>Kopie an Absender senden:</th><td>".cms_select_generieren('cms_website_element_kontaktformular_kopie', '', array(1 => "Immer", 2 => "Selbst wählbar", 0 => "Nie"), $kopie, true)."</td></tr>";
      $code .= "<tr><th>Anhänge erlauben:</th><td>".cms_schieber_generieren('website_element_kontaktformular_anhang', $anhang)."</td></tr>";
    $code .= "</table>";

    $code .= "<h3>Zugehörige Emfänger</h3>";
    $code .= "<div id=\"cms_kontaktformular_empfaenger\">";
      for ($i=0; $i < count($namen); $i++) {
        $code .= "<table class=\"cms_formular\">";
          $code .= "<tr style=\"display:none\"><th><input type=\"hidden\" class=\"cms_kontaktformular_empfaenger_id\" value=\"".$ids[$i]."\"></th></tr>";
          $code .= "<tr><th>Name: </th><td><input type=\"text\" class=\"cms_kontaktformular_empfaenger_name\" value=\"".$namen[$i]."\"></td></tr>";
          $code .= "<tr><th>eMailadresse: </th><td><input type=\"text\" class=\"cms_kontaktformular_empfaenger_mail\" value=\"".$mails[$i]."\"></td></tr>";
          $code .= "<tr><th>Beschreibung: </th><td><textarea class=\"cms_kontaktformular_empfaenger_beschreibung\">".$beschreibungen[$i]."</textarea></td></tr>";
          $code .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_kontaktformular_empfaenger_loeschen(this);\">- Empfänger löschen</span></td></tr>";
        $code .= "</table>";
      }
    $code .= "</div><br>";
    $code .= "<span class=\"cms_button\" onclick=\"cms_kontaktformular_empfaenger_hinzufuegen();\">+ Empfänger hinzufügen</span>";
    $code .= "<p>";
      if ($id == '-')
        $code .= "<span class=\"cms_button\" onclick=\"cms_kontaktformulare_neu_speichern('$zusatz')\">Speichern</span> ";
      else
      $code .= "<span class=\"cms_button\" onclick=\"cms_kontaktformulare_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";
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
