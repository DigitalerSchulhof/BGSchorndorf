<?php
include_once('php/website/seiten/schulanmeldung/navigation.php');
$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
$code = "";
$code .= "<div class=\"cms_spalte_i\">";

$code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";
$code .= "<h1>Voranmeldung</h1>";

if (cms_voranmeldung_erlaubt()) {
$code .= cms_voranmeldung_navigation(2);

if (isset($_SESSION['VORANMELDUNG_A1_NACHNAME'])) {$nachname1 = $_SESSION['VORANMELDUNG_A1_NACHNAME'];} else {$nachname1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_VORNAME'])) {$vorname1 = $_SESSION['VORANMELDUNG_A1_VORNAME'];} else {$vorname1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_GESCHLECHT'])) {$geschlecht1 = $_SESSION['VORANMELDUNG_A1_GESCHLECHT'];} else {$geschlecht1 = "w";}
if (isset($_SESSION['VORANMELDUNG_A1_SORGERECHT'])) {$sorgerecht1 = $_SESSION['VORANMELDUNG_A1_SORGERECHT'];} else {$sorgerecht1 = 1;}
if (isset($_SESSION['VORANMELDUNG_A1_BRIEFE'])) {$briefe1 = $_SESSION['VORANMELDUNG_A1_BRIEFE'];} else {$briefe1 = 1;}
if (isset($_SESSION['VORANMELDUNG_A1_HAUPT'])) {$haupt1 = $_SESSION['VORANMELDUNG_A1_HAUPT'];} else {$haupt1 = 1;}
if (isset($_SESSION['VORANMELDUNG_A1_ROLLE'])) {$rolle1 = $_SESSION['VORANMELDUNG_A1_ROLLE'];} else {$rolle1 = "Mu";}
if (isset($_SESSION['VORANMELDUNG_A1_STRASSE'])) {$strasse1 = $_SESSION['VORANMELDUNG_A1_STRASSE'];} else {$strasse1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_HAUSNUMMER'])) {$hausnummer1 = $_SESSION['VORANMELDUNG_A1_HAUSNUMMER'];} else {$hausnummer1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_PLZ'])) {$plz1 = $_SESSION['VORANMELDUNG_A1_PLZ'];} else {$plz1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_ORT'])) {$ort1 = $_SESSION['VORANMELDUNG_A1_ORT'];} else {$ort1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_TEILORT'])) {$teilort1 = $_SESSION['VORANMELDUNG_A1_TEILORT'];} else {$teilort1 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_TELEFON1'])) {$telefon11 = $_SESSION['VORANMELDUNG_A1_TELEFON1'];} else {$telefon11 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_TELEFON2'])) {$telefon21 = $_SESSION['VORANMELDUNG_A1_TELEFON2'];} else {$telefon21 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_HANDY1'])) {$handy11 = $_SESSION['VORANMELDUNG_A1_HANDY1'];} else {$handy11 = "";}
if (isset($_SESSION['VORANMELDUNG_A1_MAIL'])) {$mail1 = $_SESSION['VORANMELDUNG_A1_MAIL'];} else {$mail1 = "";}
if (isset($_SESSION['VORANMELDUNG_A2'])) {$ansprechpartner2 = $_SESSION['VORANMELDUNG_A2'];} else {$ansprechpartner2 = 1;}
if (isset($_SESSION['VORANMELDUNG_A2_NACHNAME'])) {$nachname2 = $_SESSION['VORANMELDUNG_A2_NACHNAME'];} else {$nachname2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_VORNAME'])) {$vorname2 = $_SESSION['VORANMELDUNG_A2_VORNAME'];} else {$vorname2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_GESCHLECHT'])) {$geschlecht2 = $_SESSION['VORANMELDUNG_A2_GESCHLECHT'];} else {$geschlecht2 = "m";}
if (isset($_SESSION['VORANMELDUNG_A2_SORGERECHT'])) {$sorgerecht2 = $_SESSION['VORANMELDUNG_A2_SORGERECHT'];} else {$sorgerecht2 = 1;}
if (isset($_SESSION['VORANMELDUNG_A2_BRIEFE'])) {$briefe2 = $_SESSION['VORANMELDUNG_A2_BRIEFE'];} else {$briefe2 = 1;}
if (isset($_SESSION['VORANMELDUNG_A2_HAUPT'])) {$haupt2 = $_SESSION['VORANMELDUNG_A2_HAUPT'];} else {$haupt2 = 1;}
if (isset($_SESSION['VORANMELDUNG_A2_ROLLE'])) {$rolle2 = $_SESSION['VORANMELDUNG_A2_ROLLE'];} else {$rolle2 = "Va";}
if (isset($_SESSION['VORANMELDUNG_A2_STRASSE'])) {$strasse2 = $_SESSION['VORANMELDUNG_A2_STRASSE'];} else {$strasse2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_HAUSNUMMER'])) {$hausnummer2 = $_SESSION['VORANMELDUNG_A2_HAUSNUMMER'];} else {$hausnummer2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_PLZ'])) {$plz2 = $_SESSION['VORANMELDUNG_A2_PLZ'];} else {$plz2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_ORT'])) {$ort2 = $_SESSION['VORANMELDUNG_A2_ORT'];} else {$ort2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_TEILORT'])) {$teilort2 = $_SESSION['VORANMELDUNG_A2_TEILORT'];} else {$teilort2 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_TELEFON1'])) {$telefon12 = $_SESSION['VORANMELDUNG_A2_TELEFON1'];} else {$telefon12 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_TELEFON2'])) {$telefon22 = $_SESSION['VORANMELDUNG_A2_TELEFON2'];} else {$telefon22 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_HANDY1'])) {$handy12 = $_SESSION['VORANMELDUNG_A2_HANDY1'];} else {$handy12 = "";}
if (isset($_SESSION['VORANMELDUNG_A2_MAIL'])) {$mail2 = $_SESSION['VORANMELDUNG_A2_MAIL'];} else {$mail2 = "";}

if (strlen($strasse1) == 0) {if (isset($_SESSION['VORANMELDUNG_S_STRASSE'])) {$strasse1 = $_SESSION['VORANMELDUNG_S_STRASSE'];}}
if (strlen($strasse2) == 0) {if (isset($_SESSION['VORANMELDUNG_S_STRASSE'])) {$strasse2 = $_SESSION['VORANMELDUNG_S_STRASSE'];}}
if (strlen($hausnummer1) == 0) {if (isset($_SESSION['VORANMELDUNG_S_HAUSNUMMER'])) {$hausnummer1 = $_SESSION['VORANMELDUNG_S_HAUSNUMMER'];}}
if (strlen($hausnummer2) == 0) {if (isset($_SESSION['VORANMELDUNG_S_HAUSNUMMER'])) {$hausnummer2 = $_SESSION['VORANMELDUNG_S_HAUSNUMMER'];}}
if (strlen($plz1) == 0) {if (isset($_SESSION['VORANMELDUNG_S_PLZ'])) {$plz1 = $_SESSION['VORANMELDUNG_S_PLZ'];}}
if (strlen($plz2) == 0) {if (isset($_SESSION['VORANMELDUNG_S_PLZ'])) {$plz2 = $_SESSION['VORANMELDUNG_S_PLZ'];}}
if (strlen($ort1) == 0) {if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$ort1 = $_SESSION['VORANMELDUNG_S_ORT'];}}
if (strlen($ort2) == 0) {if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$ort2 = $_SESSION['VORANMELDUNG_S_ORT'];}}
if (strlen($teilort1) == 0) {if (isset($_SESSION['VORANMELDUNG_S_TEILORT'])) {$teilort1 = $_SESSION['VORANMELDUNG_S_TEILORT'];}}
if (strlen($teilort2) == 0) {if (isset($_SESSION['VORANMELDUNG_S_TEILORT'])) {$teilort2 = $_SESSION['VORANMELDUNG_S_TEILORT'];}}

$code .= "<h2>Ansprechpartner</h2>";
  $code .= "</div>";
  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Erster Ansprechpartner</h3>";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Vorname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_vorname\" id=\"cms_voranmeldung_ansprechpartner1_vorname\" value=\"$vorname1\"></td></tr>";
  $code .= "<tr><th>Nachname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_nachname\" id=\"cms_voranmeldung_ansprechpartner1_nachname\" value=\"$nachname1\"></td></tr>";
  $code .= "<tr><th>Geschlecht:</th><td colspan=\"2\"><select name=\"cms_voranmeldung_ansprechpartner1_geschlecht\" id=\"cms_voranmeldung_ansprechpartner1_geschlecht\">";
  foreach ($geschlechter as $g) {
    if ($g['wert'] == $geschlecht1) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $code .= "<option value=\"".$g['wert']."\"$zusatz>".$g['bezeichnung']."</option>";
  }
  $code .= "</select></td></tr>";
  $code .= "<tr><th>Rolle:</th><td colspan=\"2\"><select name=\"cms_voranmeldung_ansprechpartner1_rolle\" id=\"cms_voranmeldung_ansprechpartner1_rolle\">";
  foreach ($rollen as $r) {
    if ($r['wert'] == $rolle1) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $code .= "<option value=\"".$r['wert']."\"$zusatz>".$r['bezeichnung']."</option>";
  }
  $code .= "</select></td></tr>";
  $code .= "<tr><th>Sorgeberechtigt:</th><td colspan=\"2\">".cms_generiere_schieber('voranmeldung_ansprechpartner1_sorgerecht', $sorgerecht1)."</td></tr>";
  $code .= "<tr><th>In Briefe integrieren:</th><td colspan=\"2\">".cms_generiere_schieber('voranmeldung_ansprechpartner1_briefe', $briefe1)."</td></tr>";
  $code .= "<tr><th>Hauptansprechpartner:</th><td colspan=\"2\">".cms_generiere_schieber('voranmeldung_ansprechpartner1_haupt', $haupt1)."</td></tr>";
  $code .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_strasse\" id=\"cms_voranmeldung_ansprechpartner1_strasse\" class=\"cms_gross\" value=\"$strasse1\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_hausnummer\" id=\"cms_voranmeldung_ansprechpartner1_hausnummer\" class=\"cms_klein\" value=\"$hausnummer1\"></td></tr>";
  $code .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_postleitzahl\" id=\"cms_voranmeldung_ansprechpartner1_postleitzahl\" class=\"cms_klein\" value=\"$plz1\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_ort\" id=\"cms_voranmeldung_ansprechpartner1_ort\" class=\"cms_gross\" value=\"$ort1\"></td></tr>";
  $code .= "<tr><th>Teilort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_teilort\" id=\"cms_voranmeldung_ansprechpartner1_teilort\" value=\"$teilort1\"></td></tr>";
  $code .= "<tr><th>Telefonnummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_telefon1\" id=\"cms_voranmeldung_ansprechpartner1_telefon1\" value=\"$telefon11\"></td></tr>";
  $code .= "<tr><th>Telefonnummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_telefon2\" id=\"cms_voranmeldung_ansprechpartner1_telefon2\" value=\"$telefon21\"></td></tr>";
  $code .= "<tr><th>Handynummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_handy1\" id=\"cms_voranmeldung_ansprechpartner1_handy1\" value=\"$handy11\"></td></tr>";
  $code .= "<tr><th>Mailadresse:</th><td><input name=\"cms_schulhof_voranmeldung_ansprechpartner1_mail\" id=\"cms_schulhof_voranmeldung_ansprechpartner1_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('cms_schulhof_voranmeldung_ansprechpartner1_mail');\" value=\"$mail1\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_voranmeldung_ansprechpartner1_mail_icon\"></span></td></td></tr>";
  $CMS_ONLOAD_EXTERN_EVENTS .= "cms_check_mail_wechsel('cms_schulhof_voranmeldung_ansprechpartner1_mail');";
  $code .= "</table>";
  $code .= "</div>";
  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Zweiter Ansprechpartner</h3>";

  $inhalt = "<table class=\"cms_formular\">";
  $inhalt .= "<tr><th>Vorname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_vorname\" id=\"cms_voranmeldung_ansprechpartner2_vorname\" value=\"$vorname2\"></td></tr>";
  $inhalt .= "<tr><th>Nachname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_nachname\" id=\"cms_voranmeldung_ansprechpartner2_nachname\" value=\"$nachname2\"></td></tr>";
  $inhalt .= "<tr><th>Geschlecht:</th><td colspan=\"2\"><select name=\"cms_voranmeldung_ansprechpartner2_geschlecht\" id=\"cms_voranmeldung_ansprechpartner2_geschlecht\">";
  foreach ($geschlechter as $g) {
    if ($g['wert'] == $geschlecht2) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $inhalt .= "<option value=\"".$g['wert']."\"$zusatz>".$g['bezeichnung']."</option>";
  }
  $inhalt .= "</select></td></tr>";
  $inhalt .= "<tr><th>Rolle:</th><td colspan=\"2\"><select name=\"cms_voranmeldung_ansprechpartner2_rolle\" id=\"cms_voranmeldung_ansprechpartner2_rolle\">";
  foreach ($rollen as $r) {
    if ($r['wert'] == $rolle2) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $inhalt .= "<option value=\"".$r['wert']."\"$zusatz>".$r['bezeichnung']."</option>";
  }
  $inhalt .= "</select></td></tr>";
  $inhalt .= "<tr><th>Sorgeberechtigt:</th><td colspan=\"2\">".cms_generiere_schieber('voranmeldung_ansprechpartner2_sorgerecht',$sorgerecht2)."</td></tr>";
  $inhalt .= "<tr><th>In Briefe integrieren:</th><td colspan=\"2\">".cms_generiere_schieber('voranmeldung_ansprechpartner2_briefe',$briefe2)."</td></tr>";
  $inhalt .= "<tr><th>Hauptansprechpartner:</th><td colspan=\"2\">".cms_generiere_schieber('voranmeldung_ansprechpartner2_haupt', $haupt2)."</td></tr>";
  $inhalt .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_strasse\" id=\"cms_voranmeldung_ansprechpartner2_strasse\" class=\"cms_gross\" value=\"$strasse2\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_hausnummer\" id=\"cms_voranmeldung_ansprechpartner2_hausnummer\" class=\"cms_klein\" value=\"$hausnummer2\"></td></tr>";
  $inhalt .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_postleitzahl\" id=\"cms_voranmeldung_ansprechpartner2_postleitzahl\" class=\"cms_klein\" value=\"$plz2\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_ort\" id=\"cms_voranmeldung_ansprechpartner2_ort\" class=\"cms_gross\" value=\"$ort2\"></td></tr>";
  $inhalt .= "<tr><th>Teilort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_teilort\" id=\"cms_voranmeldung_ansprechpartner2_teilort\" value=\"$teilort2\"></td></tr>";
  $inhalt .= "<tr><th>Telefonnummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_telefon1\" id=\"cms_voranmeldung_ansprechpartner2_telefon1\" value=\"$telefon12\"></td></tr>";
  $inhalt .= "<tr><th>Telefonnummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_telefon2\" id=\"cms_voranmeldung_ansprechpartner2_telefon2\" value=\"$telefon22\"></td></tr>";
  $inhalt .= "<tr><th>Handynummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_handy1\" id=\"cms_voranmeldung_ansprechpartner2_handy1\" value=\"$handy12\"></td></tr>";
  $inhalt .= "<tr><th>Mailadresse:</th><td><input name=\"cms_schulhof_voranmeldung_ansprechpartner2_mail\" id=\"cms_schulhof_voranmeldung_ansprechpartner2_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('cms_schulhof_voranmeldung_ansprechpartner2_mail');\" value=\"$mail2\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_voranmeldung_ansprechpartner2_mail_icon\"></span></td></td></tr>";
  $CMS_ONLOAD_EXTERN_EVENTS .= "cms_check_mail_wechsel('cms_schulhof_voranmeldung_ansprechpartner2_mail');";
  $inhalt .= "</table>";

  $code .= cms_toggleeinblenden_generieren ('cms_ansprechpartner2', 'Zweiten Ansprechpartner erstellen', 'Zweiten Ansprechpartner entfernen', $inhalt, $ansprechpartner2);

  $code .= "</div>";
  $code .= "</div>";
  $code .= "<div class=\"cms_clear\"></div>";

  $code .= "<div class=\"cms_spalte_i\">";
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_ansprechpartnerdaten_speichern()\">Eingaben speichern und zum nächsten Schritt</span></p>";
}
else {
  $code .= cms_meldung('warnung', '<h4>Informationen lesen und Voraussetzungen bestätigen</h4><p>Die auf der Seite »Informationen« angegebenen Voraussetzungen wurden noch nicht (vollständig) bestätigt.</p><p><a class="cms_button" href="Website/Voranmeldung">Voraussetzungen bestätigen</a></p>');
}

echo $code;
?>
