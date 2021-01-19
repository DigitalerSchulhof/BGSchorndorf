<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once('../../schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];}  else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];}  else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERSCHULJAHR'])) {$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];}  else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];}  else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung")) {
  $dbs = cms_verbinden('s');
  $datum = mktime(0,0,0,$monat, $tag, $jahr);
  $code = "";
  $schreibenpool = cms_postfach_empfaengerpool_generieren($dbs);

  $neu = 0;
  $gesamt = 0;

  // Vertretungstext laden
  $sql = $dbs->prepare("SELECT * FROM (SELECT vplanwuensche.id, AES_DECRYPT(wunsch, '$CMS_SCHLUESSEL'), status, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), vplanwuensche.ersteller, vplanwuensche.erstellzeit, datum, nutzerkonten.id AS nutzerkonto FROM vplanwuensche LEFT JOIN nutzerkonten ON vplanwuensche.ersteller = nutzerkonten.id LEFT JOIN personen ON vplanwuensche.ersteller = personen.id) AS x ORDER BY status ASC, datum DESC, nachname ASC, vorname ASC");
  if ($sql->execute()) {
    $sql->bind_result($wid, $wunsch, $status, $vorname, $nachname, $titel, $ersteller, $erstellzeit, $datum, $nutzerkonto);
    while ($sql->fetch()) {
      $gesamt ++;
      $datum = date("d.m.Y", $datum);
      $erstellung = date("d.m.Y", $erstellzeit)." um ".date("H:i", $erstellzeit)." Uhr";
      if ($status == 1) {$style = " class=\"cms_erledigt\"";} else {$style = ""; $neu ++;}
      $code .= "<tr><td><p$style>".cms_textaustextfeld_anzeigen($wunsch)."</p>";
      if ($nutzerkonto !== null) {
        $anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
        $code .= "<p class=\"cms_notiz\">$datum – $anzeigename (erstellt am $erstellung)</p></td><td>";
      }
      else {
        $code .= "<p class=\"cms_notiz\">$datum – (erstellt am $erstellung)</p></td><td>";
      }
      if (in_array($ersteller, $schreibenpool)) {
        $code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $ersteller)\"><span class=\"cms_hinweis\">Absender schreiben</span><img src=\"res/icons/klein/postnachricht.png\"></span> ";
      }
      else if ($nutzerkonto == null) {
        $code .= "<span class=\"cms_aktion_klein cms_button_passivda\" onclick=\"cms_meldung_keinkonto()\"><img src=\"res/icons/klein/postnachricht.png\"></span> ";
      }
      else {
        $code .= "<span class=\"cms_aktion_klein cms_button_passiv\" onclick=\"cms_meldung_nichtschreiben()\"><img src=\"res/icons/klein/postnachricht.png\"></span> ";
      }
      if ($status == 0) {
        $code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_vplanwunsch_status($wid, 1)\"><span class=\"cms_hinweis\">Als erledigt markieren</span><img src=\"res/icons/klein/erledigt.png\"></span>";
      }
      else {
        $code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_vplanwunsch_status($wid, 0)\"><span class=\"cms_hinweis\">Als ausstehend markieren</span><img src=\"res/icons/klein/ausstehend.png\"></span>";
      }
      $code .= " <span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_vplanwunsch_loeschen_anzeigen($wid)\"><span class=\"cms_hinweis\">Wunsch löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
      $code .= "</td></tr>";
    }
  }
  $sql->close();
  cms_trennen($dbs);

  if (strlen($code) > 0) {
    $code  = "<table class=\"cms_liste\"><tr><th>Wunsch</th><th>Aktionen</th></tr>".$code."</table>";
  }
  else {
    $code = "<p class=\"cms_notiz\">Keine Wünsche vorhanden!</p>";
  }
  echo $neu."|".$gesamt."|".$code;
}
else {
	echo cms_meldung_berechtigung();
}
cms_trennen($dbs);
?>
