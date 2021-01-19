<?php
include_once('php/website/seiten/schulanmeldung/navigation.php');
$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
$code = "";
$code .= "<div class=\"cms_spalte_i\">";

$code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";
$code .= "<h1>Voranmeldung</h1>";

if (cms_voranmeldung_erlaubt()) {
$code .= cms_voranmeldung_navigation(3);

$fehler = false;
if (isset($_SESSION['VORANMELDUNG_S_NACHNAME'])) {$snachname = $_SESSION['VORANMELDUNG_S_NACHNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_VORNAME'])) {$svorname = $_SESSION['VORANMELDUNG_S_VORNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_RUFNAME'])) {$srufname = $_SESSION['VORANMELDUNG_S_RUFNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_GEBURTSDATUM'])) {$sgeburtsdatum = $_SESSION['VORANMELDUNG_S_GEBURTSDATUM'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_GEBURTSORT'])) {$sgeburtsort = $_SESSION['VORANMELDUNG_S_GEBURTSORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_GEBURTSLAND'])) {$sgeburtsland = $_SESSION['VORANMELDUNG_S_GEBURTSLAND'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'])) {$smuttersprache = $_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'])) {$sverkehrssprache = $_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_GESCHLECHT'])) {$sgeschlecht = $_SESSION['VORANMELDUNG_S_GESCHLECHT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_RELIGION'])) {$sreligion = $_SESSION['VORANMELDUNG_S_RELIGION'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'])) {$sreligionsunterricht = $_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_LAND1'])) {$sland1 = $_SESSION['VORANMELDUNG_S_LAND1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_LAND2'])) {$sland2 = $_SESSION['VORANMELDUNG_S_LAND2'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_IMPFUNG'])) {$simpfung = $_SESSION['VORANMELDUNG_S_IMPFUNG'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_STRASSE'])) {$sstrasse = $_SESSION['VORANMELDUNG_S_STRASSE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_HAUSNUMMER'])) {$shausnummer = $_SESSION['VORANMELDUNG_S_HAUSNUMMER'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_PLZ'])) {$splz = $_SESSION['VORANMELDUNG_S_PLZ'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$sort = $_SESSION['VORANMELDUNG_S_ORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_STAAT'])) {$sstaat = $_SESSION['VORANMELDUNG_S_STAAT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_TEILORT'])) {$steilort = $_SESSION['VORANMELDUNG_S_TEILORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_TELEFON1'])) {$stelefon1 = $_SESSION['VORANMELDUNG_S_TELEFON1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_TELEFON2'])) {$stelefon2 = $_SESSION['VORANMELDUNG_S_TELEFON2'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_HANDY1'])) {$shandy1 = $_SESSION['VORANMELDUNG_S_HANDY1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_HANDY2'])) {$shandy2 = $_SESSION['VORANMELDUNG_S_HANDY2'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_MAIL'])) {$smail = $_SESSION['VORANMELDUNG_S_MAIL'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$sort = $_SESSION['VORANMELDUNG_S_ORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_EINSCHULUNG'])) {$seinschulung = $_SESSION['VORANMELDUNG_S_EINSCHULUNG'];$einschulunggeladen = false;}
else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_VORIGESCHULE'])) {$svorigeschule = $_SESSION['VORANMELDUNG_S_VORIGESCHULE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_KLASSE'])) {$sklasse = $_SESSION['VORANMELDUNG_S_KLASSE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_S_PROFIL'])) {$sprofil = $_SESSION['VORANMELDUNG_S_PROFIL'];} else {$fehler = true;}

if (isset($_SESSION['VORANMELDUNG_A1_NACHNAME'])) {$anachname1 = $_SESSION['VORANMELDUNG_A1_NACHNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_VORNAME'])) {$avorname1 = $_SESSION['VORANMELDUNG_A1_VORNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_GESCHLECHT'])) {$ageschlecht1 = $_SESSION['VORANMELDUNG_A1_GESCHLECHT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_SORGERECHT'])) {$asorgerecht1 = $_SESSION['VORANMELDUNG_A1_SORGERECHT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_BRIEFE'])) {$abriefe1 = $_SESSION['VORANMELDUNG_A1_BRIEFE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_HAUPT'])) {$ahaupt1 = $_SESSION['VORANMELDUNG_A1_HAUPT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_ROLLE'])) {$arolle1 = $_SESSION['VORANMELDUNG_A1_ROLLE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_STRASSE'])) {$astrasse1 = $_SESSION['VORANMELDUNG_A1_STRASSE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_HAUSNUMMER'])) {$ahausnummer1 = $_SESSION['VORANMELDUNG_A1_HAUSNUMMER'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_PLZ'])) {$aplz1 = $_SESSION['VORANMELDUNG_A1_PLZ'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_ORT'])) {$aort1 = $_SESSION['VORANMELDUNG_A1_ORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_TEILORT'])) {$ateilort1 = $_SESSION['VORANMELDUNG_A1_TEILORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_TELEFON1'])) {$atelefon11 = $_SESSION['VORANMELDUNG_A1_TELEFON1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_TELEFON2'])) {$atelefon21 = $_SESSION['VORANMELDUNG_A1_TELEFON2'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_HANDY1'])) {$ahandy11 = $_SESSION['VORANMELDUNG_A1_HANDY1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A1_MAIL'])) {$amail1 = $_SESSION['VORANMELDUNG_A1_MAIL'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2'])) {$aansprechpartner2 = $_SESSION['VORANMELDUNG_A2'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_NACHNAME'])) {$anachname2 = $_SESSION['VORANMELDUNG_A2_NACHNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_VORNAME'])) {$avorname2 = $_SESSION['VORANMELDUNG_A2_VORNAME'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_GESCHLECHT'])) {$ageschlecht2 = $_SESSION['VORANMELDUNG_A2_GESCHLECHT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_SORGERECHT'])) {$asorgerecht2 = $_SESSION['VORANMELDUNG_A2_SORGERECHT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_BRIEFE'])) {$abriefe2 = $_SESSION['VORANMELDUNG_A2_BRIEFE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_HAUPT'])) {$ahaupt2 = $_SESSION['VORANMELDUNG_A2_HAUPT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_ROLLE'])) {$arolle2 = $_SESSION['VORANMELDUNG_A2_ROLLE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_STRASSE'])) {$astrasse2 = $_SESSION['VORANMELDUNG_A2_STRASSE'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_HAUSNUMMER'])) {$ahausnummer2 = $_SESSION['VORANMELDUNG_A2_HAUSNUMMER'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_PLZ'])) {$aplz2 = $_SESSION['VORANMELDUNG_A2_PLZ'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_ORT'])) {$aort2 = $_SESSION['VORANMELDUNG_A2_ORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_TEILORT'])) {$ateilort2 = $_SESSION['VORANMELDUNG_A2_TEILORT'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_TELEFON1'])) {$atelefon12 = $_SESSION['VORANMELDUNG_A2_TELEFON1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_TELEFON2'])) {$atelefon22 = $_SESSION['VORANMELDUNG_A2_TELEFON2'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_HANDY1'])) {$ahandy12 = $_SESSION['VORANMELDUNG_A2_HANDY1'];} else {$fehler = true;}
if (isset($_SESSION['VORANMELDUNG_A2_MAIL'])) {$amail2 = $_SESSION['VORANMELDUNG_A2_MAIL'];} else {$fehler = true;}

if (!$fehler) {
  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Schülerdaten</h2>";
  $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
    $code .= "<tr><th>Name</th><td>$svorname $snachname<br><i>$srufname</i></td></tr>";
    $code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($sgeschlecht, 'geschlechter')."</td></tr>";
    $code .= "<tr><th>Geburtsdaten</th><td>Geboren am ".date('d.m.Y', $sgeburtsdatum)."<br>in $sgeburtsort (".cms_bezeichnung_finden($sgeburtsland, 'laender').")</td></tr>";
    $code .= "<tr><th>Muttersprache</th><td>".cms_bezeichnung_finden($smuttersprache, 'sprachen')."</td></tr>";
    $code .= "<tr><th>Verkehrssprache</th><td>".cms_bezeichnung_finden($sverkehrssprache, 'sprachen')."</td></tr>";
    $code .= "<tr><th>Staatsangehörigkeit</th><td>".cms_bezeichnung_finden($sland1, 'laender');
    if (strlen($sland2) > 0) {$code .= "<br>".cms_bezeichnung_finden($sland2, 'laender');}
    $code .= "</td></tr>";
    $code .= "<tr><th>Religion</th><td>".cms_bezeichnung_finden($sreligion, 'religionen')."</td></tr>";
    $code .= "<tr><th>Adresse</th><td>$sstrasse $shausnummer<br>$splz $sort";
    if (strlen($steilort) > 0) {$code .= " - $steilort";}
    $code .= "<br>".cms_bezeichnung_finden($sstaat, 'laender');
    $code .= "</td></tr>";
    $code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
    $kontakt = "";
    if (strlen($stelefon1) > 0) {$kontakt .= "<br>Telefon: $stelefon1";}
    if (strlen($stelefon2) > 0) {$kontakt .= "<br>Telefon: $stelefon2";}
    if (strlen($shandy1) > 0) {$kontakt .= "<br>Handy: $shandy1";}
    if (strlen($shandy2) > 0) {$kontakt .= "<br>Handy: $shandy2";}
    if (strlen($smail) > 0) {$kontakt .= "<br>eMail: $smail";}
    $code .= substr($kontakt, 4)."</td></tr>";
    $code .= "<tr><th>Vollständige Masernimpfung</th><td>";
    if ($simpfung == 1) {$code .= "ja";}
    else {$code .= "nein";}
    $code .= "</td></tr>";
    $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Alte Schule</th></tr>";
    $code .= "<tr><th>Name</th><td>$svorigeschule</td></tr>";
    $code .= "<tr><th>Klasse</th><td>$sklasse</td></tr>";
    $code .= "<tr><th>Einschulung</th><td>am ".date('d.m.Y', $seinschulung)."</td></tr>";
    $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Unterricht an der neuen Schule</th></tr>";
    $code .= "<tr><th>Religion</th><td>".cms_bezeichnung_finden($sreligionsunterricht, 'reliunterrichtangebot')."</td></tr>";
    $code .= "<tr><th>Profil</th><td>".cms_bezeichnung_finden($sprofil, 'profile')."</td></tr>";
  $code .= "</table>";
  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Erster Ansprechpartner</h2>";
  $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
    $code .= "<tr><th>Name</th><td>$avorname1 $anachname1</td></tr>";
    $code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($ageschlecht1, 'geschlechter')."</td></tr>";
    $code .= "<tr><th>Adresse</th><td>$astrasse1 $ahausnummer1<br>$aplz1 $aort1";
    if (strlen($ateilort1) > 0) {$code .= " - $ateilort1";}
    $code .= "</td></tr>";
    $code .= "<tr><th>Rolle</th><td>".cms_bezeichnung_finden($arolle1, 'rollen')."</td></tr>";
    $code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
    $kontakt = "";
    if (strlen($atelefon11) > 0) {$kontakt .= "<br>Telefon: $atelefon11";}
    if (strlen($atelefon21) > 0) {$kontakt .= "<br>Telefon: $atelefon21";}
    if (strlen($ahandy11) > 0) {$kontakt .= "<br>Handy: $ahandy11";}
    if (strlen($amail1) > 0) {$kontakt .= "<br>eMail: $amail1";}
    $code .= substr($kontakt, 4)."</td></tr>";
    $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Berechtigungen</th></tr>";
    $code .= "<tr><th>Sorgerecht</th><td>";
      if ($asorgerecht1 == 1) {$code .= "ja";}
      else {$code .= "<b><i>nein</i></b>";}
    $code .= "</td></tr>";
    $code .= "<tr><th>In Briefen einbezogen</th><td>";
      if ($abriefe1 == 1) {$code .= "ja";}
      else {$code .= "<b><i>nein</i></b>";}
    $code .= "</td></tr>";
    $code .= "<tr><th>Hauptansprechpartner</th><td>";
      if ($ahaupt1 == 1) {$code .= "ja";}
      else {$code .= "<b><i>nein</i></b>";}
    $code .= "</td></tr>";
  $code .= "</table>";

  $code .= "<h2>Zweiter Ansprechpartner</h2>";
  if ($aansprechpartner2 == '1') {
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
      $code .= "<tr><th>Name</th><td>$avorname2 $anachname2</td></tr>";
      $code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($ageschlecht2, 'geschlechter')."</td></tr>";
      $code .= "<tr><th>Adresse</th><td>$astrasse2 $ahausnummer2<br>$aplz2 $aort2";
      if (strlen($ateilort2) > 0) {$code .= " - $ateilort2";}
      $code .= "</td></tr>";
      $code .= "<tr><th>Rolle</th><td>".cms_bezeichnung_finden($arolle2, 'rollen')."</td></tr>";
      $code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
      $kontakt = "";
      if (strlen($atelefon12) > 0) {$kontakt .= "<br>Telefon: $atelefon12";}
      if (strlen($atelefon22) > 0) {$kontakt .= "<br>Telefon: $atelefon22";}
      if (strlen($ahandy12) > 0) {$kontakt .= "<br>Handy: $ahandy12";}
      if (strlen($amail2) > 0) {$kontakt .= "<br>eMail: $amail2";}
      $code .= substr($kontakt, 4)."</td></tr>";
      $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Berechtigungen</th></tr>";
      $code .= "<tr><th>Sorgerecht</th><td>";
        if ($asorgerecht2 == 1) {$code .= "ja";}
        else {$code .= "<b><i>nein</i></b>";}
      $code .= "</td></tr>";
      $code .= "<tr><th>In Briefen einbezogen</th><td>";
        if ($abriefe2 == 1) {$code .= "ja";}
        else {$code .= "<b><i>nein</i></b>";}
      $code .= "</td></tr>";
      $code .= "<tr><th>Hauptansprechpartner</th><td>";
        if ($ahaupt2 == 1) {$code .= "ja";}
        else {$code .= "<b><i>nein</i></b>";}
      $code .= "</td></tr>";
    $code .= "</table>";
  }
  else {$code .= "<p>Es wurde kein zweiter Ansprechpartner erfasst.</p>";}
  $code .= "</div></div>";

  $code .= "<div class=\"cms_clear\"></div>";

  $code .= "<div class=\"cms_spalte_i\">";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Korrekt und Vollständig</th><td>".cms_generiere_schieber('korrekt', 0)."</td><td>Ich vesichere, dass die gemachten Angaben nach bestem Wissen und Gewissen korrekt und vollständig sind.</td></tr>";
  $code .= "<tr><th>Sicherheitsabfrage zur Spamverhinderung:</th><td colspan=\"2\">".cms_captcha_generieren()." Bitte übertragen Sie die Buchstaben und Zahlen aus dem Bild in der korrekten Reihenfolge in das nachstehende Feld.</td></tr>";
  $code .= "<tr><th></th><td colspan=\"2\"></td></tr>";
  $code .= "<tr><th></th><td colspan=\"2\"><input type=\"text\" name=\"cms_spamverhinderung\" id=\"cms_spamverhinderung\"></td></tr>";
  $code .= "<tr><th></th><td colspan=\"2\"><span class=\"cms_button_ja\" onclick=\"cms_voranmeldung_speichern()\">Anmeldung abschicken</span> <span class=\"cms_button_nein\" onclick=\"cms_voranmeldung_abbrechen_anzeigen()\">Anmeldung abbrechen und eingegebene Daten löschen</span></td></tr>";
  $code .= "</table>";
}
else {
  $code .= cms_meldung('info', '<h4>Anmeldung unvollständig</h4><p>Die Anmeldedaten sind unvollständig. Bitte ergänzen Sie fehlende Daten. An rot markierten Reitern erkennen Sie, wo noch Daten fehlen.</p>');
}
}
else {
  $code .= cms_meldung('warnung', '<h4>Informationen lesen und Voraussetzungen bestätigen</h4><p>Die auf der Seite »Informationen« angegebenen Voraussetzungen wurden noch nicht (vollständig) bestätigt.</p><p><a class="cms_button" href="Website/Voranmeldung">Voraussetzungen bestätigen</a></p>');
}
$code .= "</div>";
echo $code;
?>
