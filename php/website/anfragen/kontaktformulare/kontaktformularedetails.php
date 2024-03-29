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



if(!cms_check_ganzzahl($id) && ($id != '-')) {die("FEHLER");}

$zugriff = false;

if ($id == '-') {$zugriff = cms_r("website.elemente.kontaktformular.anlegen");}
else {$zugriff = cms_r("website.elemente.kontaktformular.bearbeiten");}

if (cms_angemeldet() && $zugriff) {
  $fehler = false;

  $neu = true;
  $betreff = 'Nachricht an Sie: ';
  $kopie = '0';
  $anhang = '1';
  $ids = array();
  $namen = array();
  $ansicht = "m";
  $mails = array();
  $beschreibungen = array();

  if (cms_r("website.freigeben")) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $dbs = cms_verbinden('s');
    $modusk = strtolower($modus);
    $sql = $dbs->prepare("SELECT * FROM kontaktformulare WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $ergebnis = $sql->get_result();
      if ($daten = $ergebnis->fetch_assoc()) {
        if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
          $betreff = $daten['betreff'.$modusk];
          $kopie = $daten['kopie'.$modusk];
          $anhang = $daten['anhang'.$modusk];
          $ansicht = $daten['ansicht'.$modusk];
          $aktiv = $daten['aktiv'];
        }
        else {$fehler = true;}
      } else {
        $fehler = true;
      }
    }
    else {$fehler = true;}
    $sql->close();

    if (!$fehler) {
      $sql = "SELECT id, name, beschreibung, mail FROM kontaktformulareempfaenger WHERE kontaktformular = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("i", $id);
      $sql->bind_result($eid, $ename, $ebes, $email);
      if ($sql->execute()) {
        while ($sql->fetch()) {
          array_push($ids, $eid);
          array_push($namen, $ename);
          array_push($mails, $email);
          array_push($beschreibungen, $ebes);
        }
      }
      $sql->close();
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

      if (cms_r("website.freigeben"))
        $code .= "<tr><th>Aktiv:</th><td>".cms_generiere_schieber('website_element_kontaktformular_aktiv', $aktiv)."</td></tr>";
      else
        $code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"website_element_kontaktformular_aktiv\" name=\"website_element_kontaktformular_aktiv\" value=\"0\"></td></tr>";

      $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_kontaktformular_position', $position, $maxpos, $neu)."</td></tr>";
      $code .= "<tr><th>Betreff:</th><td><input type=\"text\" id=\"cms_website_element_kontaktformular_betreff\" name=\"cms_website_element_kontaktformular_betreff\" value=\"$betreff\"></td></tr>";
      $code .= "<tr><th>Kopie an Absender senden:</th><td>".cms_select_generieren('cms_website_element_kontaktformular_kopie', '', array(1 => "Immer", 2 => "Selbst wählbar", 0 => "Nie"), $kopie, true)."</td></tr>";
      $code .= "<tr><th>Anhänge erlauben:</th><td>".cms_generiere_schieber('website_element_kontaktformular_anhang', $anhang)."</td></tr>";
      $code .= "<tr><th>Ansicht:</th><td>";
        $code .= "<select name=\"cms_website_element_kontaktformular_ansicht\" id=\"cms_website_element_kontaktformular_ansicht\">";
          $ansichtoptionen = "<option value=\"m\">Menü</option><option value=\"v\">Visitenkarten</option>";
          $code .= str_replace("value=\"$ansicht\"", "value=\"$ansicht\" selected=\"selected\"", $ansichtoptionen);
        $code .= "</select>";
      $code .= "</td></tr>";
    $code .= "</table>";

    $code .= "<h3>Zugehörige Emfänger</h3>";
    $code .= "<div id=\"cms_kontaktformular_empfaenger\">";
      for ($i=0; $i < count($namen); $i++) {
        $code .= "<table class=\"cms_formular\">";
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
