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

if ($id == '-') {$zugriff = cms_r("website.elemente.newsletter.anlegen");}
else {$zugriff = cms_r("website.elemente.newsletter.bearbeiten");}

if (cms_angemeldet() && $zugriff) {
  $fehler = false;

  $benutzertyp = $_SESSION["BENUTZERART"];

  $neu = true;
  $bezeichnung = 'Anmeldung zum Newsletter';
  $beschreibung = 'Beschreibung des Anmeldeformulars';
  $typ = null;

  if (cms_r("website.freigeben")) {$aktiv = 1;}

  if ($id != '-') {
    $neu = false;
    $dbs = cms_verbinden('s');
    $modusk = strtolower($modus);
    $sql = $dbs->prepare("SELECT * FROM wnewsletter WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $ergebnis = $sql->get_result();
      if ($daten = $ergebnis->fetch_assoc()) {
        if (($modus == 'Aktuell') || ($modus == 'Alt') || ($modus == 'Neu')) {
          $bezeichnung = $daten['bezeichnung'.$modusk];
          $beschreibung = $daten['beschreibung'.$modusk];
          $typ = $daten['typ'.$modusk];
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
      $code = "<h3>Neues Newsletteranmeldeformular</h3>";
    else
      $code = "<h3>Newsletteranmeldeformular bearbeiten</h3>";

    $code .= "<table class=\"cms_formular\">";

    $typen = array();

    // Typen laden
    $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM newslettertypen";
    $sql = $dbs->prepare($sql);
    $sql->bind_result($t_id, $t_b);
    $sql->execute();
    while($sql->fetch()) {
      $typen[$t_id] = cms_texttrafo_e_db($t_b);
    }

      if (cms_r("website.freigeben"))
        $code .= "<tr><th>Aktiv:</th><td>".cms_generiere_schieber('website_element_newsletter_aktiv', $aktiv)."</td></tr>";
      else
        $code .= "<tr><th>Aktiv:</th><td>".cms_meldung('info', '<h4>Freigabe erforderlich</h4><p>Die neuen Inhalte werden gespeichert, aber öffentlich nicht angezeigt, bis sie die Freigabe erhalten haben.</p>')."<input type=\"hidden\" id=\"website_element_newsletter_aktiv\" name=\"website_element_newsletter_aktiv\" value=\"0\"></td></tr>";

      $code .= "<tr><th>Position:</th><td>".cms_positionswahl_generieren('cms_website_element_newsletter_position', $position, $maxpos, $neu)."</td></tr>";
      if(count($typen) < 1)
        if(cms_r("schulhof.information.newsletter.anlegen")) {
          if($benutzertyp == "s")
            $code .= "<tr>".cms_meldung("fehler", "<p>Es sind keine Newsletter vorhanden!<br>Leg im <a href=\"Schulhof/Website/Newsletter\">Schulhof</a> einen Neuen an.</p>")."</tr>";
          else
            $code .= "<tr>".cms_meldung("fehler", "<p>Es sind keine Newsletter vorhanden!<br>Legen Sie im <a href=\"Schulhof/Website/Newsletter\">Schulhof</a> einen Neuen an.</p>")."</tr>";
        } else {
          if($benutzertyp == "s")
            $code .= "<tr>".cms_meldung("fehler", "<p>Es sind keine Newsletter vorhanden!<br>Bitte einen Adrministrator, einen Neuen anzulegen.</p>")."</tr>";
          else
            $code .= "<tr>".cms_meldung("fehler", "<p>Es sind keine Newsletter vorhanden!<br>Bitten Sie einen Adrministrator, einen Neuen anzulegen.</p>")."</tr>";
        }
      else
        $code .= "<tr><th>Newsletter:</th><td>".cms_select_generieren('cms_website_element_newsletter_typ', '', $typen, $typ, true)."</td></tr>";
      $code .= "<tr><th>Überschrift:</th><td><input id=\"cms_website_element_newsletter_bezeichnung\" value=\"$bezeichnung\"></td>";
      $code .= "<tr><th>Beschreibung:</th><td><textarea id=\"cms_website_element_newsletter_beschreibung\">$beschreibung</textarea></td>";
    $code .= "</table>";

    $code .= "</div><br>";
    $code .= "<p>";
      if ($id == '-') {
        if(count($typen))
          $code .= "<span class=\"cms_button\" onclick=\"cms_wnewsletter_neu_speichern('$zusatz')\">Speichern</span> ";
      } else
        $code .= "<span class=\"cms_button\" onclick=\"cms_wnewsletter_bearbeiten_speichern('$zusatz')\">Änderungen speichern</span> ";
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
