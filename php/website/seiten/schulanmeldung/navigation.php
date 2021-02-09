<?php
function cms_voranmeldung_navigation($ort) {
  $code = "";
  $code .= "<ul class=\"cms_voranmeldung_navigation\">";
  $code .= "<li class=\"cms_voranmeldung_abgeschlossen\" onclick=\"cms_link('Website/Voranmeldung/Information')\">Information</li>";

  $jetzt = time();
  $klasse = "";
  $event = "onclick=\"cms_link('Website/Voranmeldung/Schülerdaten');\"";
  if ($ort == 1) {$klasse = "cms_voranmeldung_aktuell"; $event = "";}
  if (max($_SESSION['VORANMELDUNG_FORTSCHRITT'], $ort) > 1) {
    $fehler = false;
    // Prüfen, ob die Eingaben zu Schritt 1 korrekt sind
    if (isset($_SESSION['VORANMELDUNG_S_VORNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_S_VORNAME'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_NACHNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_S_NACHNAME'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_RUFNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_S_RUFNAME'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_GEBURTSDATUM'])) {if ($_SESSION['VORANMELDUNG_S_GEBURTSDATUM'] >= $jetzt) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_GEBURTSORT'])) {if (strlen($_SESSION['VORANMELDUNG_S_GEBURTSORT']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_GEBURTSLAND'])) {if (strlen($_SESSION['VORANMELDUNG_S_GEBURTSLAND']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'])) {if (strlen($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'])) {if (strlen($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_GESCHLECHT'])) {
      if (($_SESSION['VORANMELDUNG_S_GESCHLECHT'] != 'm') && ($_SESSION['VORANMELDUNG_S_GESCHLECHT'] != 'w') && ($_SESSION['VORANMELDUNG_S_GESCHLECHT'] != 'd')) {$fehler = true;}}
    else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_RELIGION'])) {if (strlen($_SESSION['VORANMELDUNG_S_RELIGION']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'])) {if (strlen($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_LAND1'])) {if (strlen($_SESSION['VORANMELDUNG_S_LAND1']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_STRASSE'])) {if (strlen($_SESSION['VORANMELDUNG_S_STRASSE']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_HAUSNUMMER'])) {if (strlen($_SESSION['VORANMELDUNG_S_HAUSNUMMER']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_PLZ'])) {if (strlen($_SESSION['VORANMELDUNG_S_PLZ']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {if (strlen($_SESSION['VORANMELDUNG_S_ORT']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_TELEFON1']) && isset($_SESSION['VORANMELDUNG_S_TELEFON2']) && isset($_SESSION['VORANMELDUNG_S_HANDY1']) && isset($_SESSION['VORANMELDUNG_S_HANDY2'])) {
      if ((strlen($_SESSION['VORANMELDUNG_S_TELEFON1']) <= 0) && (strlen($_SESSION['VORANMELDUNG_S_TELEFON2']) <= 0) && (strlen($_SESSION['VORANMELDUNG_S_HANDY1']) <= 0) && (strlen($_SESSION['VORANMELDUNG_S_HANDY2']) <= 0)) {$fehler = true;}}
    else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_MAIL'])) {if (strlen($_SESSION['VORANMELDUNG_S_MAIL'])) {if (!cms_check_mail($_SESSION['VORANMELDUNG_S_MAIL'])) {$fehler = true;}}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_EINSCHULUNG'])) {if ($_SESSION['VORANMELDUNG_S_EINSCHULUNG'] >= $jetzt) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_VORIGESCHULE'])) {if (strlen($_SESSION['VORANMELDUNG_S_VORIGESCHULE']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_KLASSE'])) {if (strlen($_SESSION['VORANMELDUNG_S_KLASSE']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_S_PROFIL'])) {if (strlen($_SESSION['VORANMELDUNG_S_PROFIL']) <= 0) {$fehler = true;}} else {$fehler = true;}

    if ($fehler) {$klasse .= "cms_voranmeldung_fehler";}
    else {$klasse .= "cms_voranmeldung_abgeschlossen";}
  }
  $code .= "<li class=\"$klasse\" $event>Schülerdaten</li>";

  $klasse = "";
  $event = "onclick=\"cms_link('Website/Voranmeldung/Ansprechpartner');\"";
  if ($ort == 2) {$klasse = "cms_voranmeldung_aktuell"; $event = "";}
  if (max($_SESSION['VORANMELDUNG_FORTSCHRITT'], $ort) > 2) {
    $fehler = false;
    // Prüfen, ob die Eingaben zu Schritt 3 korrekt sind
    if (isset($_SESSION['VORANMELDUNG_A1_VORNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_A1_VORNAME'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_NACHNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_A1_NACHNAME'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_GESCHLECHT'])) {if (($_SESSION['VORANMELDUNG_A1_GESCHLECHT'] != 'm') && ($_SESSION['VORANMELDUNG_A1_GESCHLECHT'] != 'w') && ($_SESSION['VORANMELDUNG_A1_GESCHLECHT'] != 'd')) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_SORGERECHT'])) {if (!cms_check_toggle($_SESSION['VORANMELDUNG_A1_SORGERECHT'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_BRIEFE'])) {if (!cms_check_toggle($_SESSION['VORANMELDUNG_A1_BRIEFE'])) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_STRASSE'])) {if (strlen($_SESSION['VORANMELDUNG_A1_STRASSE']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_HAUSNUMMER'])) {if (strlen($_SESSION['VORANMELDUNG_A1_HAUSNUMMER']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_PLZ'])) {if (strlen($_SESSION['VORANMELDUNG_A1_PLZ']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_ORT'])) {if (strlen($_SESSION['VORANMELDUNG_A1_ORT']) <= 0) {$fehler = true;}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_TELEFON1']) && isset($_SESSION['VORANMELDUNG_A1_TELEFON2']) && isset($_SESSION['VORANMELDUNG_A1_HANDY1'])) {
      if ((strlen($_SESSION['VORANMELDUNG_A1_TELEFON1']) <= 0) && (strlen($_SESSION['VORANMELDUNG_A1_TELEFON2']) <= 0) && (strlen($_SESSION['VORANMELDUNG_A1_HANDY1']) <= 0)) {$fehler = true;}}
    else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A1_MAIL'])) {if (strlen($_SESSION['VORANMELDUNG_A1_MAIL'])) {if (!cms_check_mail($_SESSION['VORANMELDUNG_A1_MAIL'])) {$fehler = true;}}} else {$fehler = true;}
    if (isset($_SESSION['VORANMELDUNG_A2'])) {
      if (!cms_check_toggle($_SESSION['VORANMELDUNG_A2'])) {$fehler = true;}
      if ($_SESSION['VORANMELDUNG_A2'] == '1') {
        if (isset($_SESSION['VORANMELDUNG_A2_VORNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_A2_VORNAME'])) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_NACHNAME'])) {if (!cms_check_name($_SESSION['VORANMELDUNG_A2_NACHNAME'])) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_GESCHLECHT'])) {if (($_SESSION['VORANMELDUNG_A2_GESCHLECHT'] != 'm') && ($_SESSION['VORANMELDUNG_A2_GESCHLECHT'] != 'w') && ($_SESSION['VORANMELDUNG_A2_GESCHLECHT'] != 'd')) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_SORGERECHT'])) {if (!cms_check_toggle($_SESSION['VORANMELDUNG_A2_SORGERECHT'])) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_BRIEFE'])) {if (!cms_check_toggle($_SESSION['VORANMELDUNG_A2_BRIEFE'])) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_STRASSE'])) {if (strlen($_SESSION['VORANMELDUNG_A2_STRASSE']) <= 0) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_HAUSNUMMER'])) {if (strlen($_SESSION['VORANMELDUNG_A2_HAUSNUMMER']) <= 0) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_PLZ'])) {if (strlen($_SESSION['VORANMELDUNG_A2_PLZ']) <= 0) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_ORT'])) {if (strlen($_SESSION['VORANMELDUNG_A2_ORT']) <= 0) {$fehler = true;}} else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_TELEFON1']) && isset($_SESSION['VORANMELDUNG_A2_TELEFON2']) && isset($_SESSION['VORANMELDUNG_A2_HANDY1'])) {
          if ((strlen($_SESSION['VORANMELDUNG_A2_TELEFON1']) <= 0) && (strlen($_SESSION['VORANMELDUNG_A2_TELEFON2']) <= 0) && (strlen($_SESSION['VORANMELDUNG_A2_HANDY1']) <= 0)) {$fehler = true;}}
        else {$fehler = true;}
        if (isset($_SESSION['VORANMELDUNG_A2_MAIL'])) {if (strlen($_SESSION['VORANMELDUNG_A2_MAIL'])) {if (!cms_check_mail($_SESSION['VORANMELDUNG_A2_MAIL'])) {$fehler = true;}}} else {$fehler = true;}
      }
    } else {$fehler = true;}

    if ($fehler) {$klasse .= "cms_voranmeldung_fehler";}
    else {$klasse .= "cms_voranmeldung_abgeschlossen";}
  }
  $code .= "<li class=\"$klasse\" $event>Ansprechpartner</li>";

  $klasse = "";
  $event = "onclick=\"cms_link('Website/Voranmeldung/Zusammenfassung');\"";
  if ($ort == 3) {$klasse = "cms_voranmeldung_aktuell"; $event = "";}
  if (max($_SESSION['VORANMELDUNG_FORTSCHRITT'], $ort) > 3) {
    if ($_SESSION['VORANMELDUNG_FORTSCHRITT'] != 3) {$klasse .= "cms_voranmeldung_fehler";}
    else {$klasse .= "cms_voranmeldung_abgeschlossen";}
  }
  $code .= "<li class=\"$klasse\" $event>Zusammenfassung</li>";
  $code .= "</ul>";
  return $code;
}

function cms_voranmeldung_erlaubt() {
  if (isset($_SESSION['VORANMELDUNG_COOKIES']) && isset($_SESSION['VORANMELDUNG_DATENSCHUTZ']) &&
      isset($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG']) && isset($_SESSION['VORANMELDUNG_VERBINDLICHKEIT']) &&
      isset($_SESSION['VORANMELDUNG_FORTSCHRITT'])) {
    if (($_SESSION['VORANMELDUNG_COOKIES'] == 1) && ($_SESSION['VORANMELDUNG_DATENSCHUTZ'] == 1) &&
        ($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'] == 1) && ($_SESSION['VORANMELDUNG_VERBINDLICHKEIT'] == 1)) {
      return true;
    }
  }
  return false;
}

function cms_voranmeldung_zeit() {
  global $CMS_VORANMELDUNG;
  $code = "";
  $zulaessig = false;
  $jetzt = time();
  if ($CMS_VORANMELDUNG['Anmeldung aktiv'] == 1) {
    if (($jetzt > $CMS_VORANMELDUNG['Anmeldung von']) && ($jetzt < $CMS_VORANMELDUNG['Anmeldung bis'])) {
      $zulaessig = true;
    }
    else {
      $text = "<p>Die Online-Voranmeldung ist noch nicht freigegeben.</p><p>Sie können sich von ".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung von'])).", den ".date('d.m.Y H:i', $CMS_VORANMELDUNG['Anmeldung von'])." bis ".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung bis'])).", den ".date('d.m.Y H:i', $CMS_VORANMELDUNG['Anmeldung bis'])." vorab anmelden.</p>";
      $code .= cms_meldung("info", "<h4>Sie sind zu früh!</h4>".$text);
      $code .= "<p><a class=\"cms_button\" href=\"Website\">zur Website</a></p>";
    }
  }
  else {
    $code .= cms_meldung("warnung", "<h4>Inaktiv</h4><p>Die Online-Voranmeldung ist derzeit inaktiv.</p>");
    $code .= "<p><a class=\"cms_button\" href=\"Website\">zur Website</a></p>";
  }

  $rueckgabe['code'] = $code;
  $rueckgabe['zulaessig'] = $zulaessig;
  return $rueckgabe;
}

$laender[ 0]['wert']        = 'D';
$laender[ 0]['bezeichnung'] = 'Deutschland';
$laender[ 1]['wert'] = 'AFG';
$laender[ 1]['bezeichnung'] = 'Afghanistan';
$laender[ 2]['wert'] = 'ET';
$laender[ 2]['bezeichnung'] = 'Ägypten';
$laender[ 3]['wert'] = 'AL';
$laender[ 3]['bezeichnung'] = 'Albanien';
$laender[ 4]['wert'] = 'DZ';
$laender[ 4]['bezeichnung'] = 'Algerien';
$laender[ 5]['wert'] = 'AND';
$laender[ 5]['bezeichnung'] = 'Andorra';
$laender[ 6]['wert'] = 'ANG';
$laender[ 6]['bezeichnung'] = 'Angola';
$laender[ 7]['wert'] = 'AG';
$laender[ 7]['bezeichnung'] = 'Antigua und Barbuda';
$laender[ 8]['wert'] = 'GQ';
$laender[ 8]['bezeichnung'] = 'Äquatorialguinea';
$laender[ 9]['wert'] = 'RA';
$laender[ 9]['bezeichnung'] = 'Argentinien';
$laender[ 10]['wert'] = 'AM';
$laender[ 10]['bezeichnung'] = 'Armenien';
$laender[ 11]['wert'] = 'AZ';
$laender[ 11]['bezeichnung'] = 'Aserbaidschan';
$laender[ 12]['wert'] = 'ETH';
$laender[ 12]['bezeichnung'] = 'Äthiopien';
$laender[ 13]['wert'] = 'AUS';
$laender[ 13]['bezeichnung'] = 'Australien';
$laender[ 14]['wert'] = 'BS';
$laender[ 14]['bezeichnung'] = 'Bahamas';
$laender[ 15]['wert'] = 'BRN';
$laender[ 15]['bezeichnung'] = 'Bahrain';
$laender[ 16]['wert'] = 'BD';
$laender[ 16]['bezeichnung'] = 'Bangladesch';
$laender[ 17]['wert'] = 'BDS';
$laender[ 17]['bezeichnung'] = 'Barbados';
$laender[ 18]['wert'] = 'B';
$laender[ 18]['bezeichnung'] = 'Belgien';
$laender[ 19]['wert'] = 'BH';
$laender[ 19]['bezeichnung'] = 'Belize';
$laender[ 20]['wert'] = 'DY';
$laender[ 20]['bezeichnung'] = 'Benin';
$laender[ 21]['wert'] = 'BHT';
$laender[ 21]['bezeichnung'] = 'Bhutan';
$laender[ 22]['wert'] = 'BOL';
$laender[ 22]['bezeichnung'] = 'Bolivien';
$laender[ 23]['wert'] = 'BIH';
$laender[ 23]['bezeichnung'] = 'Bosnien-Herzegowina';
$laender[ 24]['wert'] = 'BW';
$laender[ 24]['bezeichnung'] = 'Botsuana';
$laender[ 25]['wert'] = 'BR';
$laender[ 25]['bezeichnung'] = 'Brasilien';
$laender[ 26]['wert'] = 'GB3';
$laender[ 26]['bezeichnung'] = 'Brit. Geb. in Amerika';
$laender[ 27]['wert'] = 'GB1';
$laender[ 27]['bezeichnung'] = 'Brit. Geb. in Europa';
$laender[ 28]['wert'] = 'GB5';
$laender[ 28]['bezeichnung'] = 'Brit. Geb. in Ozeanien';
$laender[ 29]['wert'] = 'BRU';
$laender[ 29]['bezeichnung'] = 'Brunei Darussalam';
$laender[ 30]['wert'] = 'BG';
$laender[ 30]['bezeichnung'] = 'Bulgarien';
$laender[ 31]['wert'] = 'BF';
$laender[ 31]['bezeichnung'] = 'Burkina Faso';
$laender[ 32]['wert'] = 'RU';
$laender[ 32]['bezeichnung'] = 'Burundi';
$laender[ 33]['wert'] = 'RCH';
$laender[ 33]['bezeichnung'] = 'Chile';
$laender[ 34]['wert'] = 'RC';
$laender[ 34]['bezeichnung'] = 'China VR';
$laender[ 35]['wert'] = 'CR';
$laender[ 35]['bezeichnung'] = 'Costa Rica';
$laender[ 36]['wert'] = 'CI';
$laender[ 36]['bezeichnung'] = 'Côte d\'Ivore';
$laender[ 37]['wert'] = 'DK';
$laender[ 37]['bezeichnung'] = 'Dänemark';
$laender[ 38]['wert'] = 'WD';
$laender[ 38]['bezeichnung'] = 'Dominika';
$laender[ 39]['wert'] = 'DOM';
$laender[ 39]['bezeichnung'] = 'Dominikanische Republik';
$laender[ 40]['wert'] = 'DJI';
$laender[ 40]['bezeichnung'] = 'Dschibuti';
$laender[ 41]['wert'] = 'EC';
$laender[ 41]['bezeichnung'] = 'Ecuador';
$laender[ 42]['wert'] = 'ES';
$laender[ 42]['bezeichnung'] = 'El Salvador';
$laender[ 43]['wert'] = 'ER';
$laender[ 43]['bezeichnung'] = 'Eritrea';
$laender[ 44]['wert'] = 'EST';
$laender[ 44]['bezeichnung'] = 'Estland';
$laender[ 45]['wert'] = 'FJI';
$laender[ 45]['bezeichnung'] = 'Fidschi';
$laender[ 46]['wert'] = 'FIN';
$laender[ 46]['bezeichnung'] = 'Finnland';
$laender[ 47]['wert'] = 'F';
$laender[ 47]['bezeichnung'] = 'Frankreich';
$laender[ 48]['wert'] = 'G';
$laender[ 48]['bezeichnung'] = 'Gabun';
$laender[ 49]['wert'] = 'WAG';
$laender[ 49]['bezeichnung'] = 'Gambia';
$laender[ 50]['wert'] = 'GE';
$laender[ 50]['bezeichnung'] = 'Georgien';
$laender[ 51]['wert'] = 'GH';
$laender[ 51]['bezeichnung'] = 'Ghana';
$laender[ 52]['wert'] = 'WG';
$laender[ 52]['bezeichnung'] = 'Grenada';
$laender[ 53]['wert'] = 'GR';
$laender[ 53]['bezeichnung'] = 'Griechenland';
$laender[ 54]['wert'] = 'GB';
$laender[ 54]['bezeichnung'] = 'Großbritannien';
$laender[ 55]['wert'] = 'GCA';
$laender[ 55]['bezeichnung'] = 'Guatemala';
$laender[ 56]['wert'] = 'RG';
$laender[ 56]['bezeichnung'] = 'Guinea';
$laender[ 57]['wert'] = 'GUB';
$laender[ 57]['bezeichnung'] = 'Guinea-Bissau';
$laender[ 58]['wert'] = 'GUY';
$laender[ 58]['bezeichnung'] = 'Guyana';
$laender[ 59]['wert'] = 'RH';
$laender[ 59]['bezeichnung'] = 'Haiti';
$laender[ 60]['wert'] = 'HN';
$laender[ 60]['bezeichnung'] = 'Honduras';
$laender[ 61]['wert'] = 'HOK';
$laender[ 61]['bezeichnung'] = 'Hongkong (VR China)';
$laender[ 62]['wert'] = 'IND';
$laender[ 62]['bezeichnung'] = 'Indien';
$laender[ 63]['wert'] = 'RI';
$laender[ 63]['bezeichnung'] = 'Indonesien';
$laender[ 64]['wert'] = 'IRQ';
$laender[ 64]['bezeichnung'] = 'Irak';
$laender[ 65]['wert'] = 'IR';
$laender[ 65]['bezeichnung'] = 'Iran Islam. Rep.';
$laender[ 66]['wert'] = 'IRL';
$laender[ 66]['bezeichnung'] = 'Irland';
$laender[ 67]['wert'] = 'IS';
$laender[ 67]['bezeichnung'] = 'Island';
$laender[ 68]['wert'] = 'IL';
$laender[ 68]['bezeichnung'] = 'Israel';
$laender[ 69]['wert'] = 'I';
$laender[ 69]['bezeichnung'] = 'Italien';
$laender[ 70]['wert'] = 'JA';
$laender[ 70]['bezeichnung'] = 'Jamaika';
$laender[ 71]['wert'] = 'J';
$laender[ 71]['bezeichnung'] = 'Japan';
$laender[ 72]['wert'] = 'YAR';
$laender[ 72]['bezeichnung'] = 'Jemen';
$laender[ 73]['wert'] = 'HKJ';
$laender[ 73]['bezeichnung'] = 'Jordanien';
$laender[ 74]['wert'] = 'K';
$laender[ 74]['bezeichnung'] = 'Kambodscha Königr.';
$laender[ 75]['wert'] = 'CAM';
$laender[ 75]['bezeichnung'] = 'Kamerun';
$laender[ 76]['wert'] = 'CDN';
$laender[ 76]['bezeichnung'] = 'Kanada';
$laender[ 77]['wert'] = 'CV';
$laender[ 77]['bezeichnung'] = 'Kap Verde';
$laender[ 78]['wert'] = 'KZ';
$laender[ 78]['bezeichnung'] = 'Kasachstan';
$laender[ 79]['wert'] = 'Q';
$laender[ 79]['bezeichnung'] = 'Katar';
$laender[ 80]['wert'] = 'EAK';
$laender[ 80]['bezeichnung'] = 'Kenia';
$laender[ 81]['wert'] = 'KS';
$laender[ 81]['bezeichnung'] = 'Kirgisistan';
$laender[ 82]['wert'] = 'KIR';
$laender[ 82]['bezeichnung'] = 'Kiribati';
$laender[ 83]['wert'] = 'CO';
$laender[ 83]['bezeichnung'] = 'Kolumbien';
$laender[ 84]['wert'] = 'COM';
$laender[ 84]['bezeichnung'] = 'Komoren';
$laender[ 85]['wert'] = 'COD';
$laender[ 85]['bezeichnung'] = 'Kongo Dem. Rep.';
$laender[ 86]['wert'] = 'RCB';
$laender[ 86]['bezeichnung'] = 'Kongo Republik';
$laender[ 87]['wert'] = 'PRK';
$laender[ 87]['bezeichnung'] = 'Korea Dem. VR';
$laender[ 88]['wert'] = 'ROK';
$laender[ 88]['bezeichnung'] = 'Korea Republik';
$laender[ 89]['wert'] = 'XK';
$laender[ 89]['bezeichnung'] = 'Kosovo';
$laender[ 90]['wert'] = 'HR';
$laender[ 90]['bezeichnung'] = 'Kroatien';
$laender[ 91]['wert'] = 'CU';
$laender[ 91]['bezeichnung'] = 'Kuba';
$laender[ 92]['wert'] = 'KWT';
$laender[ 92]['bezeichnung'] = 'Kuwait';
$laender[ 93]['wert'] = 'LAO';
$laender[ 93]['bezeichnung'] = 'Laos';
$laender[ 94]['wert'] = 'LS';
$laender[ 94]['bezeichnung'] = 'Lesotho';
$laender[ 95]['wert'] = 'LV';
$laender[ 95]['bezeichnung'] = 'Lettland';
$laender[ 96]['wert'] = 'RL';
$laender[ 96]['bezeichnung'] = 'Libanon';
$laender[ 97]['wert'] = 'LB';
$laender[ 97]['bezeichnung'] = 'Liberia';
$laender[ 98]['wert'] = 'LAR';
$laender[ 98]['bezeichnung'] = 'Libyen';
$laender[ 99]['wert'] = 'FL';
$laender[ 99]['bezeichnung'] = 'Liechtenstein';
$laender[ 100]['wert'] = 'LT';
$laender[ 100]['bezeichnung'] = 'Litauen';
$laender[ 101]['wert'] = 'L';
$laender[ 101]['bezeichnung'] = 'Luxemburg';
$laender[ 102]['wert'] = 'MAC';
$laender[ 102]['bezeichnung'] = 'Macau (VR China)';
$laender[ 103]['wert'] = 'RM';
$laender[ 103]['bezeichnung'] = 'Madagaskar';
$laender[ 104]['wert'] = 'MW';
$laender[ 104]['bezeichnung'] = 'Malawi';
$laender[ 105]['wert'] = 'MAL';
$laender[ 105]['bezeichnung'] = 'Malaysia';
$laender[ 106]['wert'] = 'MV';
$laender[ 106]['bezeichnung'] = 'Malediven';
$laender[ 107]['wert'] = 'RMM';
$laender[ 107]['bezeichnung'] = 'Mali';
$laender[ 108]['wert'] = 'M';
$laender[ 108]['bezeichnung'] = 'Malta';
$laender[ 109]['wert'] = 'MA';
$laender[ 109]['bezeichnung'] = 'Marokko';
$laender[ 110]['wert'] = 'MH';
$laender[ 110]['bezeichnung'] = 'Marshallinseln';
$laender[ 111]['wert'] = 'RIM';
$laender[ 111]['bezeichnung'] = 'Mauretanien';
$laender[ 112]['wert'] = 'MS';
$laender[ 112]['bezeichnung'] = 'Mauritius';
$laender[ 113]['wert'] = 'MK';
$laender[ 113]['bezeichnung'] = 'Mazedonien (ehm.jug.Rep.)';
$laender[ 114]['wert'] = 'MEX';
$laender[ 114]['bezeichnung'] = 'Mexiko';
$laender[ 115]['wert'] = 'FSM';
$laender[ 115]['bezeichnung'] = 'Mikronesien Föd. St.';
$laender[ 116]['wert'] = 'MD';
$laender[ 116]['bezeichnung'] = 'Moldau Republik';
$laender[ 117]['wert'] = 'MC';
$laender[ 117]['bezeichnung'] = 'Monaco';
$laender[ 118]['wert'] = 'MGL';
$laender[ 118]['bezeichnung'] = 'Mongolei';
$laender[ 119]['wert'] = 'MNE';
$laender[ 119]['bezeichnung'] = 'Montenegro';
$laender[ 120]['wert'] = 'MOC';
$laender[ 120]['bezeichnung'] = 'Mosambik';
$laender[ 121]['wert'] = 'BUR';
$laender[ 121]['bezeichnung'] = 'Myanmar';
$laender[ 122]['wert'] = 'NAM';
$laender[ 122]['bezeichnung'] = 'Namibia';
$laender[ 123]['wert'] = 'NAU';
$laender[ 123]['bezeichnung'] = 'Nauru';
$laender[ 124]['wert'] = 'NEP';
$laender[ 124]['bezeichnung'] = 'Nepal';
$laender[ 125]['wert'] = 'NZ';
$laender[ 125]['bezeichnung'] = 'Neuseeland';
$laender[ 126]['wert'] = 'NIC';
$laender[ 126]['bezeichnung'] = 'Nicaragua';
$laender[ 127]['wert'] = 'NL';
$laender[ 127]['bezeichnung'] = 'Niederlande';
$laender[ 128]['wert'] = 'RN';
$laender[ 128]['bezeichnung'] = 'Niger';
$laender[ 129]['wert'] = 'WAN';
$laender[ 129]['bezeichnung'] = 'Nigeria';
$laender[ 130]['wert'] = 'MP';
$laender[ 130]['bezeichnung'] = 'Nördliche Marianen';
$laender[ 131]['wert'] = 'N';
$laender[ 131]['bezeichnung'] = 'Norwegen';
$laender[ 132]['wert'] = 'OM';
$laender[ 132]['bezeichnung'] = 'Oman';
$laender[ 133]['wert'] = 'A';
$laender[ 133]['bezeichnung'] = 'Österreich';
$laender[ 134]['wert'] = 'PK';
$laender[ 134]['bezeichnung'] = 'Pakistan';
$laender[ 135]['wert'] = 'AUT';
$laender[ 135]['bezeichnung'] = 'Palästina';
$laender[ 136]['wert'] = 'PAL';
$laender[ 136]['bezeichnung'] = 'Palau';
$laender[ 137]['wert'] = 'PA';
$laender[ 137]['bezeichnung'] = 'Panama';
$laender[ 138]['wert'] = 'PNG';
$laender[ 138]['bezeichnung'] = 'Papua-Neuguinea';
$laender[ 139]['wert'] = 'PY';
$laender[ 139]['bezeichnung'] = 'Paraguay';
$laender[ 140]['wert'] = 'PE';
$laender[ 140]['bezeichnung'] = 'Peru';
$laender[ 141]['wert'] = 'RP';
$laender[ 141]['bezeichnung'] = 'Philippinen';
$laender[ 142]['wert'] = 'PL';
$laender[ 142]['bezeichnung'] = 'Polen';
$laender[ 143]['wert'] = 'P';
$laender[ 143]['bezeichnung'] = 'Portugal';
$laender[ 144]['wert'] = 'RWA';
$laender[ 144]['bezeichnung'] = 'Ruanda';
$laender[ 145]['wert'] = 'R';
$laender[ 145]['bezeichnung'] = 'Rumänien';
$laender[ 146]['wert'] = 'RUS';
$laender[ 146]['bezeichnung'] = 'Russische Föderation';
$laender[ 147]['wert'] = 'SOL';
$laender[ 147]['bezeichnung'] = 'Salomonen';
$laender[ 148]['wert'] = 'RNR';
$laender[ 148]['bezeichnung'] = 'Sambia';
$laender[ 149]['wert'] = 'WS';
$laender[ 149]['bezeichnung'] = 'Samoa';
$laender[ 150]['wert'] = 'RSM';
$laender[ 150]['bezeichnung'] = 'San Marino';
$laender[ 151]['wert'] = 'STP';
$laender[ 151]['bezeichnung'] = 'São Tomé und Principe';
$laender[ 152]['wert'] = 'SA';
$laender[ 152]['bezeichnung'] = 'Saudi-Arabien';
$laender[ 153]['wert'] = 'S';
$laender[ 153]['bezeichnung'] = 'Schweden';
$laender[ 154]['wert'] = 'CH';
$laender[ 154]['bezeichnung'] = 'Schweiz';
$laender[ 155]['wert'] = 'SN';
$laender[ 155]['bezeichnung'] = 'Senegal';
$laender[ 156]['wert'] = 'SRB';
$laender[ 156]['bezeichnung'] = 'Serbien';
$laender[ 157]['wert'] = 'SY';
$laender[ 157]['bezeichnung'] = 'Seychellen';
$laender[ 158]['wert'] = 'WAL';
$laender[ 158]['bezeichnung'] = 'Sierra Leone';
$laender[ 159]['wert'] = 'ZW';
$laender[ 159]['bezeichnung'] = 'Simbabwe';
$laender[ 160]['wert'] = 'SGP';
$laender[ 160]['bezeichnung'] = 'Singapur';
$laender[ 161]['wert'] = 'SK';
$laender[ 161]['bezeichnung'] = 'Slowakei';
$laender[ 162]['wert'] = 'SLO';
$laender[ 162]['bezeichnung'] = 'Slowenien';
$laender[ 163]['wert'] = 'SO';
$laender[ 163]['bezeichnung'] = 'Somalia';
$laender[ 164]['wert'] = 'SOW';
$laender[ 164]['bezeichnung'] = 'Sowjetunion';
$laender[ 165]['wert'] = 'E';
$laender[ 165]['bezeichnung'] = 'Spanien';
$laender[ 166]['wert'] = 'CL';
$laender[ 166]['bezeichnung'] = 'Sri Lanka';
$laender[ 167]['wert'] = 'KAN';
$laender[ 167]['bezeichnung'] = 'St. Kitts und Nevis';
$laender[ 168]['wert'] = 'WL';
$laender[ 168]['bezeichnung'] = 'St. Lucia';
$laender[ 169]['wert'] = 'WV';
$laender[ 169]['bezeichnung'] = 'St. Vincent u. Grenad.';
$laender[ 170]['wert'] = 'ZA';
$laender[ 170]['bezeichnung'] = 'Südafrika';
$laender[ 171]['wert'] = 'SDN';
$laender[ 171]['bezeichnung'] = 'Republik Sudan';
$laender[ 172]['wert'] = 'SSDN';
$laender[ 172]['bezeichnung'] = 'Südsudan';
$laender[ 173]['wert'] = 'SME';
$laender[ 173]['bezeichnung'] = 'Suriname';
$laender[ 174]['wert'] = 'SD';
$laender[ 174]['bezeichnung'] = 'Swasiland';
$laender[ 175]['wert'] = 'SYR';
$laender[ 175]['bezeichnung'] = 'Syrien';
$laender[ 176]['wert'] = 'TJ';
$laender[ 176]['bezeichnung'] = 'Tadschikistan';
$laender[ 177]['wert'] = 'TW';
$laender[ 177]['bezeichnung'] = 'Taiwan';
$laender[ 178]['wert'] = 'EAT';
$laender[ 178]['bezeichnung'] = 'Tansania Ver. Rep.';
$laender[ 179]['wert'] = 'T';
$laender[ 179]['bezeichnung'] = 'Thailand';
$laender[ 180]['wert'] = 'TL';
$laender[ 180]['bezeichnung'] = 'Timor-Leste';
$laender[ 181]['wert'] = 'TG';
$laender[ 181]['bezeichnung'] = 'Togo';
$laender[ 182]['wert'] = 'TON';
$laender[ 182]['bezeichnung'] = 'Tonga';
$laender[ 183]['wert'] = 'TT';
$laender[ 183]['bezeichnung'] = 'Trinidad und Tobago';
$laender[ 184]['wert'] = 'TD';
$laender[ 184]['bezeichnung'] = 'Tschad';
$laender[ 185]['wert'] = 'CZ';
$laender[ 185]['bezeichnung'] = 'Tschechische Republik';
$laender[ 186]['wert'] = 'TN';
$laender[ 186]['bezeichnung'] = 'Tunesien';
$laender[ 187]['wert'] = 'TR';
$laender[ 187]['bezeichnung'] = 'Türkei';
$laender[ 188]['wert'] = 'TM';
$laender[ 188]['bezeichnung'] = 'Turkmenistan';
$laender[ 189]['wert'] = 'TUV';
$laender[ 189]['bezeichnung'] = 'Tuvalu';
$laender[ 190]['wert'] = 'UG';
$laender[ 190]['bezeichnung'] = 'Uganda';
$laender[ 191]['wert'] = 'UA';
$laender[ 191]['bezeichnung'] = 'Ukraine';
$laender[ 192]['wert'] = 'H';
$laender[ 192]['bezeichnung'] = 'Ungarn';
$laender[ 193]['wert'] = 'ROU';
$laender[ 193]['bezeichnung'] = 'Uruguay';
$laender[ 194]['wert'] = 'USA';
$laender[ 194]['bezeichnung'] = 'USA';
$laender[ 195]['wert'] = 'UZ';
$laender[ 195]['bezeichnung'] = 'Usbekistan';
$laender[ 196]['wert'] = 'VAN';
$laender[ 196]['bezeichnung'] = 'Vánúatú';
$laender[ 197]['wert'] = 'V';
$laender[ 197]['bezeichnung'] = 'Vatikanstadt';
$laender[ 198]['wert'] = 'YV';
$laender[ 198]['bezeichnung'] = 'Venezuela';
$laender[ 199]['wert'] = 'UAE';
$laender[ 199]['bezeichnung'] = 'Ver. Arab. Emirate';
$laender[ 200]['wert'] = 'VN';
$laender[ 200]['bezeichnung'] = 'Vietnam';
$laender[ 201]['wert'] = 'BY';
$laender[ 201]['bezeichnung'] = 'Weißrussland (auch: Belarus)';
$laender[ 202]['wert'] = 'RCA';
$laender[ 202]['bezeichnung'] = 'Zentralafrikan. Republik';
$laender[ 203]['wert'] = 'CY';
$laender[ 203]['bezeichnung'] = 'Zypern';
$laender[ 204]['wert'] = 'UAF';
$laender[ 204]['bezeichnung'] = 'Unselbst.Geb.i.Afrika';
$laender[ 205]['wert'] = 'UNGEKLÄRT';
$laender[ 205]['bezeichnung'] = 'Ungeklärt';
$laender[ 206]['wert'] = 'OHNE';
$laender[ 206]['bezeichnung'] = 'Staatenlos';


$religionen[ 0]['wert'] = 'SON-KEIN';
$religionen[ 1]['wert'] = 'ALE';
$religionen[ 2]['wert'] = 'AK';
$religionen[ 3]['wert'] = 'EV';
$religionen[ 3]['wert'] = 'SYR';
$religionen[ 4]['wert'] = 'ISL';
$religionen[ 5]['wert'] = 'RK';
$religionen[ 6]['wert'] = 'JÜD';
$religionen[ 7]['wert'] = 'OTX';
$religionen[ 8]['wert'] = 'K_A';

$religionen[ 0]['bezeichnung'] = 'sonstige/keine Religionszugehörigkeit';
$religionen[ 1]['bezeichnung'] = 'alevitisch';
$religionen[ 2]['bezeichnung'] = 'alt-katholisch';
$religionen[ 3]['bezeichnung'] = 'evangelisch';
$religionen[ 3]['bezeichnung'] = 'syrisch-orthodox';
$religionen[ 4]['bezeichnung'] = 'islamisch-sunnitisch';
$religionen[ 5]['bezeichnung'] = 'römisch-katholisch';
$religionen[ 6]['bezeichnung'] = 'jüdisch';
$religionen[ 7]['bezeichnung'] = 'orthodox (außer syrisch-orthodox)';
$religionen[ 8]['bezeichnung'] = 'keine Angabe';


$rollen[ 0]['wert'] = "Mu";
$rollen[ 1]['wert'] = "Va";
$rollen[ 2]['wert'] = "Pf";
$rollen[ 0]['bezeichnung'] = "Mutter";
$rollen[ 1]['bezeichnung'] = "Vater";
$rollen[ 2]['bezeichnung'] = "Pflegeeltern";

$reliunterrichtangebot[0]['bezeichnung'] = 'Evangelischer Religionsunterricht';
$reliunterrichtangebot[0]['wert'] = 'EV';
$reliunterrichtangebot[1]['bezeichnung'] = 'Römisch-katholischer Religionsunterricht';
$reliunterrichtangebot[1]['wert'] = 'RK';
$reliunterrichtangebot[2]['bezeichnung'] = 'Ethikunterricht';
$reliunterrichtangebot[2]['wert'] = 'ETH';

$geschlechter[0]['bezeichnung'] = 'weiblich';
$geschlechter[0]['wert'] = 'w';
$geschlechter[1]['bezeichnung'] = 'männlich';
$geschlechter[1]['wert'] = 'm';
//$geschlechter[2]['bezeichnung'] = 'divers';
//$geschlechter[2]['wert'] = 'd';

$klassenbezeichnungen = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

$sprachen[0]['wert'] = 'D';
$sprachen[0]['bezeichnung'] = 'deutsch';
$sprachen[1]['wert'] = 'ALB';
$sprachen[1]['bezeichnung'] = 'albanisch';
$sprachen[2]['wert'] = 'ARA';
$sprachen[2]['bezeichnung'] = 'arabisch';
$sprachen[3]['wert'] = 'BOS';
$sprachen[3]['bezeichnung'] = 'bosnisch';
$sprachen[4]['wert'] = 'BUL';
$sprachen[4]['bezeichnung'] = 'bulgarisch';
$sprachen[5]['wert'] = 'CHIN';
$sprachen[5]['bezeichnung'] = 'chinesisch';
$sprachen[6]['wert'] = 'E';
$sprachen[6]['bezeichnung'] = 'englisch';
$sprachen[7]['wert'] = 'F';
$sprachen[7]['bezeichnung'] = 'französisch';
$sprachen[8]['wert'] = 'GR-N';
$sprachen[8]['bezeichnung'] = 'griechisch';
$sprachen[9]['wert'] = 'I';
$sprachen[9]['bezeichnung'] = 'italienisch';
$sprachen[10]['wert'] = 'JAP';
$sprachen[10]['bezeichnung'] = 'japanisch';
$sprachen[11]['wert'] = 'KAS';
$sprachen[11]['bezeichnung'] = 'kasachisch';
$sprachen[12]['wert'] = 'KRO';
$sprachen[12]['bezeichnung'] = 'kroatisch';
$sprachen[13]['wert'] = 'KUR';
$sprachen[13]['bezeichnung'] = 'kurdisch';
$sprachen[14]['wert'] = 'MAZ';
$sprachen[14]['bezeichnung'] = 'mazedonisch';
$sprachen[15]['wert'] = 'POL';
$sprachen[15]['bezeichnung'] = 'polnisch';
$sprachen[16]['wert'] = 'POR';
$sprachen[16]['bezeichnung'] = 'portugiesisch';
$sprachen[17]['wert'] = 'RUM';
$sprachen[17]['bezeichnung'] = 'rumänisch';
$sprachen[18]['wert'] = 'RU';
$sprachen[18]['bezeichnung'] = 'russisch';
$sprachen[19]['wert'] = 'SER';
$sprachen[19]['bezeichnung'] = 'serbisch';
$sprachen[20]['wert'] = 'SP';
$sprachen[20]['bezeichnung'] = 'spanisch';
$sprachen[21]['wert'] = 'TÜ';
$sprachen[21]['bezeichnung'] = 'türkisch';
$sprachen[22]['wert'] = 'UKR';
$sprachen[22]['bezeichnung'] = 'ukrainisch';
$sprachen[23]['wert'] = 'SOF';
$sprachen[23]['bezeichnung'] = 'sonstige Sprache';
$sprachen[24]['wert'] = 'K_A';
$sprachen[24]['bezeichnung'] = 'keine Angabe';


$profile[ 0]['wert'] = 'keines';
$profile[ 1]['wert'] = 'bilingual';

$profile[ 0]['bezeichnung'] = 'keines';
$profile[ 1]['bezeichnung'] = 'bilingual';

$empfehlungen[ 0]['wert'] = '-';
$empfehlungen[ 1]['wert'] = 'G';
$empfehlungen[ 2]['wert'] = 'R';
$empfehlungen[ 3]['wert'] = 'W';
$empfehlungen[ 4]['wert'] = 'S';

$empfehlungen[ 0]['bezeichnung'] = '– Bitte wählen –';
$empfehlungen[ 1]['bezeichnung'] = 'Gymnasium';
$empfehlungen[ 2]['bezeichnung'] = 'Realschule';
$empfehlungen[ 3]['bezeichnung'] = 'Werkrealschule/Gemeinschaftsschule';
$empfehlungen[ 4]['bezeichnung'] = 'Sonstiges';


function cms_bezeichnung_finden($wert, $auswahl) {
  global $$auswahl;
  foreach ($$auswahl as $a) {
    if ($a['wert'] == $wert) {
      return $a['bezeichnung'];
    }
  }
  return "";
}
?>
