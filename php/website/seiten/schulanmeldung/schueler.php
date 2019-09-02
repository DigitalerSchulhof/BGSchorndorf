<?php
include_once('php/website/seiten/schulanmeldung/navigation.php');
$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
$code = "";
$code .= "<div class=\"cms_spalte_i\">";

$code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";
$code .= "<h1>Voranmeldung</h1>";

if (cms_voranmeldung_erlaubt()) {

$code .= cms_voranmeldung_navigation(1);

$code .= "<h2>Schülerdaten</h2>";
$code .= "<p>Bitte füllen Sie die Formularfelder sorgfältig aus. Die Schreibweise des Namens muss der auf der Geburtsurkunde entsprechen.</p>";

  // Werte laden
  if (isset($_SESSION['VORANMELDUNG_S_NACHNAME'])) {$nachname = $_SESSION['VORANMELDUNG_S_NACHNAME'];} else {$nachname = "";}
  if (isset($_SESSION['VORANMELDUNG_S_VORNAME'])) {$vorname = $_SESSION['VORANMELDUNG_S_VORNAME'];} else {$vorname = "";}
  if (isset($_SESSION['VORANMELDUNG_S_RUFNAME'])) {$rufname = $_SESSION['VORANMELDUNG_S_RUFNAME'];} else {$rufname = "";}
  if (isset($_SESSION['VORANMELDUNG_S_GEBURTSDATUM'])) {$geburtsdatum = $_SESSION['VORANMELDUNG_S_GEBURTSDATUM']; $geburtsdatumgeladen = true;}
  else {$geburtsdatum = time(); $geburtsdatumgeladen = false;}
  if (isset($_SESSION['VORANMELDUNG_S_GEBURTSORT'])) {$geburtsort = $_SESSION['VORANMELDUNG_S_GEBURTSORT'];} else {$geburtsort = "";}
  if (isset($_SESSION['VORANMELDUNG_S_GEBURTSLAND'])) {$geburtsland = $_SESSION['VORANMELDUNG_S_GEBURTSLAND'];} else {$geburtsland = "";}
  if (isset($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'])) {$muttersprache = $_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'];} else {$muttersprache = "";}
  if (isset($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'])) {$verkehrssprache = $_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'];} else {$verkehrssprache = "";}
  if (isset($_SESSION['VORANMELDUNG_S_GESCHLECHT'])) {$geschlecht = $_SESSION['VORANMELDUNG_S_GESCHLECHT'];} else {$geschlecht = "";}
  if (isset($_SESSION['VORANMELDUNG_S_RELIGION'])) {$religion = $_SESSION['VORANMELDUNG_S_RELIGION'];} else {$religion = "";}
  if (isset($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'])) {$religionsunterricht = $_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'];} else {$religionsunterricht = "";}
  if (isset($_SESSION['VORANMELDUNG_S_LAND1'])) {$land1 = $_SESSION['VORANMELDUNG_S_LAND1'];} else {$land1 = "";}
  if (isset($_SESSION['VORANMELDUNG_S_LAND2'])) {$land2 = $_SESSION['VORANMELDUNG_S_LAND2'];} else {$land2 = "";}
  if (isset($_SESSION['VORANMELDUNG_S_STRASSE'])) {$strasse = $_SESSION['VORANMELDUNG_S_STRASSE'];} else {$strasse = "";}
  if (isset($_SESSION['VORANMELDUNG_S_HAUSNUMMER'])) {$hausnummer = $_SESSION['VORANMELDUNG_S_HAUSNUMMER'];} else {$hausnummer = "";}
  if (isset($_SESSION['VORANMELDUNG_S_PLZ'])) {$plz = $_SESSION['VORANMELDUNG_S_PLZ'];} else {$plz = "";}
  if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$ort = $_SESSION['VORANMELDUNG_S_ORT'];} else {$ort = "";}
  if (isset($_SESSION['VORANMELDUNG_S_TEILORT'])) {$teilort = $_SESSION['VORANMELDUNG_S_TEILORT'];} else {$teilort = "";}
  if (isset($_SESSION['VORANMELDUNG_S_TELEFON1'])) {$telefon1 = $_SESSION['VORANMELDUNG_S_TELEFON1'];} else {$telefon1 = "";}
  if (isset($_SESSION['VORANMELDUNG_S_TELEFON2'])) {$telefon2 = $_SESSION['VORANMELDUNG_S_TELEFON2'];} else {$telefon2 = "";}
  if (isset($_SESSION['VORANMELDUNG_S_HANDY1'])) {$handy1 = $_SESSION['VORANMELDUNG_S_HANDY1'];} else {$handy1 = "";}
  if (isset($_SESSION['VORANMELDUNG_S_HANDY2'])) {$handy2 = $_SESSION['VORANMELDUNG_S_HANDY2'];} else {$handy2 = "";}
  if (isset($_SESSION['VORANMELDUNG_S_MAIL'])) {$mail = $_SESSION['VORANMELDUNG_S_MAIL'];} else {$mail = "";}
  if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$ort = $_SESSION['VORANMELDUNG_S_ORT'];} else {$ort = "";}
  if (isset($_SESSION['VORANMELDUNG_S_EINSCHULUNG'])) {$einschulung = $_SESSION['VORANMELDUNG_S_EINSCHULUNG']; $einschulunggeladen = false;}
  else {$einschulung = $geburtsdatum; $einschulunggeladen = true;}
  if (isset($_SESSION['VORANMELDUNG_S_VORIGESCHULE'])) {$vorigeschule = $_SESSION['VORANMELDUNG_S_VORIGESCHULE'];} else {$vorigeschule = "";}
  if (isset($_SESSION['VORANMELDUNG_S_KLASSE'])) {$klasse = $_SESSION['VORANMELDUNG_S_KLASSE'];} else {$klasse = "";}
  if (isset($_SESSION['VORANMELDUNG_S_PROFIL'])) {$profil = $_SESSION['VORANMELDUNG_S_PROFIL'];} else {$profil = "";}

  $geburtsdatumT = date('d', $geburtsdatum);
  $geburtsdatumM = date('m', $geburtsdatum);
  if ($geburtsdatumgeladen) {$geburtsdatumJ = date('Y', $geburtsdatum);}
  else {$geburtsdatumJ = date('Y', $geburtsdatum) - $CMS_VORANMELDUNG['Anmeldung Eintrittsalter'];}
  $einschulungT = date('d', $einschulung);
  $einschulungM = date('m', $einschulung);
  if ($einschulunggeladen) {$einschulungJ = date('Y', $einschulung);}
  else {$einschulungJ = date('Y', $einschulung)-2*$CMS_VORANMELDUNG['Anmeldung Eintrittsalter'] + $CMS_VORANMELDUNG['Anmeldung Einschulungsalter'];}



  $code .= "</div>";
  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Persönliche Daten</h3>";
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Vorname:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_vorname\" id=\"cms_voranmeldung_schueler_vorname\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_vorname', 'cms_voranmeldung_schueler_rufname')\" value=\"$vorname\"></td></tr>";
    $code .= "<tr><th>Rufname:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_rufname\" id=\"cms_voranmeldung_schueler_rufname\" value=\"$rufname\"></td></tr>";
    $code .= "<tr><th>Nachname:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_nachname\" id=\"cms_voranmeldung_schueler_nachname\" value=\"$nachname\"></td></tr>";
    $code .= "<tr><th>Geburtstag:</th><td>".cms_datum_eingabe('cms_vornameldung_schueler_geburtsdatum', $geburtsdatumT, $geburtsdatumM, $geburtsdatumJ)."</td></tr>";
    $code .= "<tr><th>Geburtsort:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_geburtsort\" id=\"cms_voranmeldung_schueler_geburtsort\" value=\"$geburtsort\"></td></tr>";
    $code .= "<tr><th>Geburtsland:</th><td><select name=\"cms_voranmeldung_schueler_geburtsland\" id=\"cms_voranmeldung_schueler_geburtsland\">";
    foreach ($laender as $l) {
      if ($l['wert'] == $geburtsland) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$l['wert']."\"$zusatz>".$l['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
    $code .= "<tr><th>Muttersprache:</th><td><select name=\"cms_voranmeldung_schueler_muttersprache\" id=\"cms_voranmeldung_schueler_muttersprache\">";
    foreach ($sprachen as $s) {
      if ($s['wert'] == $muttersprache) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$s['wert']."\"$zusatz>".$s['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
    $code .= "<tr><th>Verkehrssprache:</th><td><select name=\"cms_voranmeldung_schueler_verkehrssprache\" id=\"cms_voranmeldung_schueler_verkehrssprache\">";
    foreach ($sprachen as $s) {
      if ($s['wert'] == $verkehrssprache) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$s['wert']."\"$zusatz>".$s['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
    $code .= "<tr><th>Geschlecht:</th><td><select name=\"cms_voranmeldung_schueler_geschlecht\" id=\"cms_voranmeldung_schueler_geschlecht\">";
    foreach ($geschlechter as $g) {
      if ($g['wert'] == $geschlecht) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$g['wert']."\"$zusatz>".$g['bezeichnung']."</option>";
    }
    $code .= "</select></td></tr>";
    $code .= "<tr><th>Religion:</th><td><select name=\"cms_voranmeldung_schueler_religion\" id=\"cms_voranmeldung_schueler_religion\">";
    foreach ($religionen as $r) {
      if ($r['wert'] == $religion) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$r['wert']."\"$zusatz>".$r['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
    $code .= "<tr><th>Religionsunterricht:</th><td><select name=\"cms_voranmeldung_schueler_religionsunterricht\" id=\"cms_voranmeldung_schueler_religionsunterricht\">";
    foreach ($reliunterrichtangebot as $r) {
      if ($r['wert'] == $religionsunterricht) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$r['wert']."\"$zusatz>".$r['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
    $code .= "<tr><th>Staatsangehörigkeit:</th><td><select name=\"cms_voranmeldung_schueler_land1\" id=\"cms_voranmeldung_schueler_land1\">";
    foreach ($laender as $l) {
      if ($l['wert'] == $land1) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$l['wert']."\"$zusatz>".$l['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
    $code .= "<tr><th>Zweite Staatsangehörigkeit:</th><td><select name=\"cms_voranmeldung_schueler_land2\" id=\"cms_voranmeldung_schueler_land2\">";
    $code .= "<option value=\"\">Keine</option>";
    foreach ($laender as $l) {
      if ($l['wert'] == $land2) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
      $code .= "<option value=\"".$l['wert']."\"$zusatz>".$l['bezeichnung']."</option>";
    }
    $code .= "</td></tr>";
  $code .= "</table>";
  $code .= "</div>";
  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Kontaktdaten</h3>";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_strasse\" id=\"cms_voranmeldung_schueler_strasse\" class=\"cms_gross\" value=\"$strasse\"> <input type=\"text\" name=\"cms_voranmeldung_schueler_hausnummer\" id=\"cms_voranmeldung_schueler_hausnummer\" class=\"cms_klein\" value=\"$hausnummer\"></td></tr>";
  $code .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_postleitzahl\" id=\"cms_voranmeldung_schueler_postleitzahl\" class=\"cms_klein\" value=\"$plz\"> <input type=\"text\" name=\"cms_voranmeldung_schueler_ort\" id=\"cms_voranmeldung_schueler_ort\" class=\"cms_gross\" value=\"$ort\"></td></tr>";
  $code .= "<tr><th>Teilort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_teilort\" id=\"cms_voranmeldung_schueler_teilort\" value=\"$teilort\"></td></tr>";
  $code .= "<tr><th>Telefonnummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_telefon1\" id=\"cms_voranmeldung_schueler_telefon1\" value=\"$telefon1\"></td></tr>";
  $code .= "<tr><th>Telefonnummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_telefon2\" id=\"cms_voranmeldung_schueler_telefon2\" value=\"$telefon2\"></td></tr>";
  $code .= "<tr><th>Handynummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_handy1\" id=\"cms_voranmeldung_schueler_handy1\" value=\"$handy1\"></td></tr>";
  $code .= "<tr><th>Handynummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_handy2\" id=\"cms_voranmeldung_schueler_handy2\" value=\"$handy2\"></td></tr>";
  $code .= "<tr><th>Mailadresse:</th><td><input name=\"cms_schulhof_voranmeldung_schueler_mail\" id=\"cms_schulhof_voranmeldung_schueler_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('voranmeldung_schueler_mail');\" value=\"$mail\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_voranmeldung_schueler_mail_icon\"></span></td></td></tr>";
  $CMS_ONLOAD_EXTERN_EVENTS .= "cms_check_mail_wechsel('voranmeldung_schueler_mail');";
  $code .= "</table>";

  $code .= "<h3>Schullaufbahn</h3>";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Einschulung in Klasse 1:</th><td>".cms_datum_eingabe('cms_vornameldung_schueler_einschulung', $einschulungT, $einschulungM, $einschulungJ)."</td></tr>";
  $code .= "<tr><th>Vorige Schule:</th><td><input type=\"text\" name=\"cms_voranmeldung_vorigeschule\" id=\"cms_voranmeldung_vorigeschule\", value=\"$vorigeschule\"></td></tr>";
  $code .= "<tr><th>Klasse an der letzten Schule:</th><td><select name=\"cms_voranmeldung_klasse\" id=\"cms_voranmeldung_klasse\">";
  foreach($klassenbezeichnungen as $b) {
    if ($klasse == $CMS_VORANMELDUNG['Anmeldung Klassenstufe'].$b) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $code .= "<option value=\"".$CMS_VORANMELDUNG['Anmeldung Klassenstufe'].$b."\"$zusatz>".$CMS_VORANMELDUNG['Anmeldung Klassenstufe'].$b."</option>";
  }
  $code .= "</select></td></tr>";
  $code .= "<tr><th>Künftiges Profil:</th><td><select name=\"cms_voranmeldung_profil\" id=\"cms_voranmeldung_profil\">";
  foreach ($profile as $p) {
    if ($p['wert'] == $profil) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $code .= "<option value=\"".$p['wert']."\"$zusatz>".$p['bezeichnung']."</option>";
  }
  $code .= "</select></td></tr>";
  $code .= "</table>";
  $code .= "</div>";
  $code .= "</div>";
  $code .= "<div class=\"cms_clear\"></div>";

  $code .= "<div class=\"cms_spalte_i\">";
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_schuelerdaten_speichern()\">Eingaben speichern und zum nächsten Schritt</span></p>";
}
else {
  $code .= cms_meldung('warnung', '<h4>Informationen lesen und Voraussetzungen bestätigen</h4><p>Die auf der Seite »Informationen« angegebenen Voraussetzungen wurden noch nicht (vollständig) bestätigt.</p><p><a class="cms_button" href="Website/Voranmeldung">Voraussetzungen bestätigen</a></p>');
}

$code .= "</div>";
echo $code;
?>
