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





$laender[  0]['wert'] = 'Deutschland';
$laender[  1]['wert'] = 'Ägypten';
$laender[  2]['wert'] = 'Äquatorialguinea';
$laender[  3]['wert'] = 'Äthiopien';
$laender[  4]['wert'] = 'Afghanistan';
$laender[  5]['wert'] = 'Algerien';
$laender[  6]['wert'] = 'Andorra';
$laender[  7]['wert'] = 'Angola';
$laender[  8]['wert'] = 'Anguilla, Antarktis-Territorium, Bermuda';
$laender[  9]['wert'] = 'Antigua und Barbuda';
$laender[ 10]['wert'] = 'Argentinien';
$laender[ 11]['wert'] = 'Armenien';
$laender[ 12]['wert'] = 'Aserbaidschan';
$laender[ 13]['wert'] = 'Australien, einschl. Kokosinseln';
$laender[ 14]['wert'] = 'Bahamas';
$laender[ 15]['wert'] = 'Bahrain (auch: Bahrein)';
$laender[ 16]['wert'] = 'Bangladesch';
$laender[ 17]['wert'] = 'Barbados';
$laender[ 18]['wert'] = 'Belgien';
$laender[ 19]['wert'] = 'Belize';
$laender[ 20]['wert'] = 'Benin (früher: Dahone)';
$laender[ 21]['wert'] = 'Bhutan';
$laender[ 22]['wert'] = 'Bolivien';
$laender[ 23]['wert'] = 'Bosnien-Herzegowina';
$laender[ 24]['wert'] = 'Botsuana';
$laender[ 25]['wert'] = 'Brasilien';
$laender[ 26]['wert'] = 'Brit. Jungferninseln, Canton und Enderbu';
$laender[ 27]['wert'] = 'Brunei Darussalam';
$laender[ 28]['wert'] = 'Bulgarien';
$laender[ 29]['wert'] = 'Burkina Faso';
$laender[ 30]['wert'] = 'Burundi';
$laender[ 31]['wert'] = 'Chile';
$laender[ 32]['wert'] = 'China, VR (einschl. Tibet)';
$laender[ 33]['wert'] = 'Cookinseln';
$laender[ 34]['wert'] = 'Costa Rica';
$laender[ 35]['wert'] = 'Cóte d`Invoire (früher: Elfenbeinküste';       // Côte d'Ivoire
$laender[ 36]['wert'] = 'Dominica';
$laender[ 37]['wert'] = 'Dominikanische Republik';
$laender[ 38]['wert'] = 'Dschibui (auch: Djibouti)';
$laender[ 39]['wert'] = 'Dänemark und Faröer';                          // Färöer
$laender[ 40]['wert'] = 'Ecuador, einschl. Galapagos-Inseln';
$laender[ 41]['wert'] = 'El Salvador';
$laender[ 42]['wert'] = 'Eritrea';
$laender[ 43]['wert'] = 'Estland';
$laender[ 44]['wert'] = 'Falklandinseln, Kaiman-Inseln (brit.abh.';
$laender[ 45]['wert'] = 'Fidschi (auch: Fidji)';
$laender[ 46]['wert'] = 'Finnland';
$laender[ 47]['wert'] = 'Frankreich, einschl Korsika';
$laender[ 48]['wert'] = 'Franz. Guayana, Grönland';
$laender[ 49]['wert'] = 'Franz. Polynesioen, Guam. Pazifische Inse';
$laender[ 50]['wert'] = 'Gabun';
$laender[ 51]['wert'] = 'Gambia';
$laender[ 52]['wert'] = 'Georgien';
$laender[ 53]['wert'] = 'Ghana';
$laender[ 54]['wert'] = 'Gibraltar, Insel Man, Kanalinseln (Guern';
$laender[ 55]['wert'] = 'Grenada';
$laender[ 56]['wert'] = 'Griechenland';
$laender[ 57]['wert'] = 'Franz. Guayana, Grönland';
$laender[ 58]['wert'] = 'Großbrittanien und Nordirland';
$laender[ 59]['wert'] = 'Guadeloupe, Puerto Rico';
$laender[ 60]['wert'] = 'Guatemala';
$laender[ 61]['wert'] = 'Guyana';
$laender[ 62]['wert'] = 'Guinea';
$laender[ 63]['wert'] = 'Guinea-Bissau';
$laender[ 64]['wert'] = 'Haiti';
$laender[ 65]['wert'] = 'Honduras';
$laender[ 66]['wert'] = 'Hongkong';
$laender[ 67]['wert'] = 'Indien, einschl Sikkim und Goa';
$laender[ 68]['wert'] = 'Indien, einschl Irian Jaya';
$laender[ 69]['wert'] = 'Irak';
$laender[ 70]['wert'] = 'Iran, Islamisch Republik';
$laender[ 71]['wert'] = 'Irland';
$laender[ 72]['wert'] = 'Israel';
$laender[ 73]['wert'] = 'Italien';
$laender[ 74]['wert'] = 'Jamaika';
$laender[ 75]['wert'] = 'Japan';
$laender[ 76]['wert'] = 'Jemen';
$laender[ 77]['wert'] = 'Jordanien';
$laender[ 78]['wert'] = 'Kambodscha, Königreich';
$laender[ 79]['wert'] = 'Kamerun';
$laender[ 80]['wert'] = 'Kanada';
$laender[ 81]['wert'] = 'Kap Verde (auch kapverdische Inseln)';
$laender[ 82]['wert'] = 'Karolinen- und Marianeninseln)';
$laender[ 83]['wert'] = 'Kasachstan';
$laender[ 84]['wert'] = 'Katar';
$laender[ 85]['wert'] = 'Kenia';
$laender[ 86]['wert'] = 'Kirgistan';
$laender[ 87]['wert'] = 'Kiribati';
$laender[ 88]['wert'] = 'Kolumbien';
$laender[ 89]['wert'] = 'Komoren';
$laender[ 90]['wert'] = 'Kongo, Demoktratische Republik (früher: Z)';
$laender[ 91]['wert'] = 'Kongo Republik';
$laender[ 92]['wert'] = 'Korea, Dem. VR (auch: Nordkorea)';
$laender[ 93]['wert'] = 'Korea, Republik (auch: Südkorea)';
$laender[ 94]['wert'] = 'Kroatien';
$laender[ 95]['wert'] = 'Kuba';
$laender[ 96]['wert'] = 'Kuwait (auch: Kuweit)';
$laender[ 97]['wert'] = 'Laos';
$laender[ 98]['wert'] = 'Lesotho';
$laender[ 99]['wert'] = 'Lettland';
$laender[100]['wert'] = 'Libanon';
$laender[101]['wert'] = 'Liberia';
$laender[102]['wert'] = 'Liechtenstein';
$laender[103]['wert'] = 'Luxemburg';
$laender[104]['wert'] = 'Lybien';
$laender[105]['wert'] = 'Madagaskar';
$laender[106]['wert'] = 'Malawi';
$laender[107]['wert'] = 'Malaysia';
$laender[108]['wert'] = 'Malediven';
$laender[109]['wert'] = 'Mali';
$laender[110]['wert'] = 'Malta';
$laender[111]['wert'] = 'Marokko';
$laender[112]['wert'] = 'Marshallinseln';
$laender[113]['wert'] = 'Mauretanien';
$laender[114]['wert'] = 'Mauritio';
$laender[115]['wert'] = 'Mazedonien, ehm. jugosl. Republik';
$laender[116]['wert'] = 'Mexico';
$laender[117]['wert'] = 'Moldau, Republik';
$laender[118]['wert'] = 'Monaco';
$laender[119]['wert'] = 'Mongolei';
$laender[120]['wert'] = 'Montenegro';
$laender[121]['wert'] = 'Montserrat, Turks- und Caicos-Inseln(bri';
$laender[122]['wert'] = 'Mosambik';
$laender[123]['wert'] = 'Mayanmar (früher: Birma)';
$laender[124]['wert'] = 'Namibia';
$laender[125]['wert'] = 'Nauru';
$laender[126]['wert'] = 'Nepal';
$laender[127]['wert'] = 'Neukaledonien';
$laender[128]['wert'] = 'Tokeleau-Inseln';
$laender[129]['wert'] = 'Neuseeland';
$laender[130]['wert'] = 'Nicaragua';
$laender[131]['wert'] = 'Niederlande';
$laender[132]['wert'] = 'Niger';
$laender[133]['wert'] = 'Nigeria';
$laender[134]['wert'] = 'Niue-Inseln';
$laender[135]['wert'] = 'Norwegen, Bäreninsel, Spitzbergen';
$laender[136]['wert'] = 'Nördliche Marianen';
$laender[137]['wert'] = 'Österreich';
$laender[138]['wert'] = 'Oman';
$laender[139]['wert'] = 'Pakistan';
$laender[140]['wert'] = 'Palau';
$laender[141]['wert'] = 'Panama';
$laender[142]['wert'] = 'Papua-Neuguinea';
$laender[143]['wert'] = 'Paraguay';
$laender[144]['wert'] = 'Peru';
$laender[145]['wert'] = 'Philippinen';
$laender[146]['wert'] = 'Pitcam-Insel (brit.abh.)';
$laender[147]['wert'] = 'Polen';
$laender[148]['wert'] = 'Portugal';
$laender[149]['wert'] = 'Ruanda';
$laender[150]['wert'] = 'Rumänien';
$laender[151]['wert'] = 'Russland (GUS)';
$laender[152]['wert'] = 'Salomonen';
$laender[153]['wert'] = 'Sambia';
$laender[154]['wert'] = 'Samona (auch: Westsamoa)';
$laender[155]['wert'] = 'San Marino';
$laender[156]['wert'] = 'Sáo Torné und Principe';
$laender[157]['wert'] = 'Saudi-Arabien';
$laender[158]['wert'] = 'Schweden';
$laender[159]['wert'] = 'Schweiz';
$laender[160]['wert'] = 'Senegal';
$laender[161]['wert'] = 'Serbien';
$laender[162]['wert'] = 'Seychellen';
$laender[163]['wert'] = 'Sierra Leone';
$laender[164]['wert'] = 'Simbabwe';
$laender[165]['wert'] = 'Singapur';
$laender[166]['wert'] = 'Slowakei';
$laender[167]['wert'] = 'Slowenien';
$laender[168]['wert'] = 'Somalia';
$laender[169]['wert'] = 'Spnien';
$laender[170]['wert'] = 'Sri Lanka';
$laender[171]['wert'] = 'St. Helena, einsch. Ascension (brit.abh.';
$laender[172]['wert'] = 'St. Kitts und Nevis';
$laender[173]['wert'] = 'St. Lucia';
$laender[174]['wert'] = 'St. Pierre u. Miquelon';
$laender[175]['wert'] = 'St. Vincent u. die Grenadinen';
$laender[176]['wert'] = 'Sudan';
$laender[177]['wert'] = 'Suriname (auch: Surinam)';
$laender[178]['wert'] = 'Swasiland';
$laender[179]['wert'] = 'Südafrika';
$laender[180]['wert'] = 'Syrien';
$laender[181]['wert'] = 'Tadschikistan';
$laender[182]['wert'] = 'Taiwan';
$laender[183]['wert'] = 'Tansania Vereinigte Republik';
$laender[184]['wert'] = 'Thailand';
$laender[185]['wert'] = 'Togo';
$laender[186]['wert'] = 'Tonga';
$laender[187]['wert'] = 'Trinidad und Tobago';
$laender[188]['wert'] = 'Tschad';
$laender[189]['wert'] = 'Tschechische Republik';
$laender[190]['wert'] = 'Tunesien';
$laender[191]['wert'] = 'Turkmenistan';
$laender[192]['wert'] = 'Tuvalu';
$laender[193]['wert'] = 'Türkei';
$laender[194]['wert'] = 'Uganda';
$laender[195]['wert'] = 'Ukraine';
$laender[196]['wert'] = 'Ungarn';
$laender[197]['wert'] = 'Uruguay';
$laender[198]['wert'] = 'Usbekistan';
$laender[199]['wert'] = 'Vánúatú';
$laender[200]['wert'] = 'Vatikanstadt';
$laender[201]['wert'] = 'Venezuela';
$laender[202]['wert'] = 'Vereinigte Arabische Emirate';
$laender[203]['wert'] = 'Vereinigte Staaten von Amerika (USA)';
$laender[204]['wert'] = 'Vietnam';
$laender[205]['wert'] = 'Weihnachtsinseln, einschl. Norfolk-insel';
$laender[206]['wert'] = 'Weißrussland (auch Belarus)';
$laender[207]['wert'] = 'Zentralafrikanische Republik';
$laender[208]['wert'] = 'Zypern';
$laender[209]['wert'] = 'Staatenlos';
$laender[210]['wert'] = 'Ungeklärt';
$laender[211]['wert'] = 'Ohne Angabe';

$laender[  0]['bezeichnung'] = 'Deutschland';
$laender[  1]['bezeichnung'] = 'Ägypten';
$laender[  2]['bezeichnung'] = 'Äquatorialguinea';
$laender[  3]['bezeichnung'] = 'Äthiopien';
$laender[  4]['bezeichnung'] = 'Afghanistan';
$laender[  5]['bezeichnung'] = 'Algerien';
$laender[  6]['bezeichnung'] = 'Andorra';
$laender[  7]['bezeichnung'] = 'Angola';
$laender[  8]['bezeichnung'] = 'Anguilla, Antarktis-Territorium, Bermuda';
$laender[  9]['bezeichnung'] = 'Antigua und Barbuda';
$laender[ 10]['bezeichnung'] = 'Argentinien';
$laender[ 11]['bezeichnung'] = 'Armenien';
$laender[ 12]['bezeichnung'] = 'Aserbaidschan';
$laender[ 13]['bezeichnung'] = 'Australien, einschl. Kokosinseln';
$laender[ 14]['bezeichnung'] = 'Bahamas';
$laender[ 15]['bezeichnung'] = 'Bahrain / Bahrein';
$laender[ 16]['bezeichnung'] = 'Bangladesch';
$laender[ 17]['bezeichnung'] = 'Barbados';
$laender[ 18]['bezeichnung'] = 'Belgien';
$laender[ 19]['bezeichnung'] = 'Belize';
$laender[ 20]['bezeichnung'] = 'Benin';
$laender[ 21]['bezeichnung'] = 'Bhutan';
$laender[ 22]['bezeichnung'] = 'Bolivien';
$laender[ 23]['bezeichnung'] = 'Bosnien-Herzegowina';
$laender[ 24]['bezeichnung'] = 'Botsuana';
$laender[ 25]['bezeichnung'] = 'Brasilien';
$laender[ 26]['bezeichnung'] = 'Brit. Jungferninseln, Canton und Enderbu';
$laender[ 27]['bezeichnung'] = 'Brunei Darussalam';
$laender[ 28]['bezeichnung'] = 'Bulgarien';
$laender[ 29]['bezeichnung'] = 'Burkina Faso';
$laender[ 30]['bezeichnung'] = 'Burundi';
$laender[ 31]['bezeichnung'] = 'Chile';
$laender[ 32]['bezeichnung'] = 'China, VR einschließlich Tibet';
$laender[ 33]['bezeichnung'] = 'Cookinseln';
$laender[ 34]['bezeichnung'] = 'Costa Rica';
$laender[ 35]['bezeichnung'] = 'Côte d\'Ivoire';
$laender[ 36]['bezeichnung'] = 'Dominica';
$laender[ 37]['bezeichnung'] = 'Dominikanische Republik';
$laender[ 38]['bezeichnung'] = 'Dschibui / Djibouti';
$laender[ 39]['bezeichnung'] = 'Dänemark und Färöer';
$laender[ 40]['bezeichnung'] = 'Ecuador, einschließlich Galapagos-Inseln';
$laender[ 41]['bezeichnung'] = 'El Salvador';
$laender[ 42]['bezeichnung'] = 'Eritrea';
$laender[ 43]['bezeichnung'] = 'Estland';
$laender[ 44]['bezeichnung'] = 'Falklandinseln, Kaiman-Inseln';
$laender[ 45]['bezeichnung'] = 'Fidschi / Fidji';
$laender[ 46]['bezeichnung'] = 'Finnland';
$laender[ 47]['bezeichnung'] = 'Frankreich, einschl Korsika';
$laender[ 48]['bezeichnung'] = 'Französisch Guayana';
$laender[ 49]['bezeichnung'] = 'Französisch Polynesioen, Guam Pazifische Insel';
$laender[ 50]['bezeichnung'] = 'Gabun';
$laender[ 51]['bezeichnung'] = 'Gambia';
$laender[ 52]['bezeichnung'] = 'Georgien';
$laender[ 53]['bezeichnung'] = 'Ghana';
$laender[ 54]['bezeichnung'] = 'Gibraltar, Insel Man, Kanalinseln (Guernsey, Jersey)';
$laender[ 55]['bezeichnung'] = 'Grenada';
$laender[ 56]['bezeichnung'] = 'Griechenland';
$laender[ 57]['bezeichnung'] = 'Grönland';
$laender[ 58]['bezeichnung'] = 'Großbrittanien und Nordirland';
$laender[ 59]['bezeichnung'] = 'Guadeloupe, Puerto Rico';
$laender[ 60]['bezeichnung'] = 'Guatemala';
$laender[ 61]['bezeichnung'] = 'Guyana';
$laender[ 62]['bezeichnung'] = 'Guinea';
$laender[ 63]['bezeichnung'] = 'Guinea-Bissau';
$laender[ 64]['bezeichnung'] = 'Haiti';
$laender[ 65]['bezeichnung'] = 'Honduras';
$laender[ 66]['bezeichnung'] = 'Hongkong';
$laender[ 67]['bezeichnung'] = 'Indien, einschl Sikkim und Goa';
$laender[ 68]['bezeichnung'] = 'Indien, einschl Irian Jaya';
$laender[ 69]['bezeichnung'] = 'Irak';
$laender[ 70]['bezeichnung'] = 'Iran, Islamisch Republik';
$laender[ 71]['bezeichnung'] = 'Irland';
$laender[ 72]['bezeichnung'] = 'Israel';
$laender[ 73]['bezeichnung'] = 'Italien';
$laender[ 74]['bezeichnung'] = 'Jamaika';
$laender[ 75]['bezeichnung'] = 'Japan';
$laender[ 76]['bezeichnung'] = 'Jemen';
$laender[ 77]['bezeichnung'] = 'Jordanien';
$laender[ 78]['bezeichnung'] = 'Kambodscha, Königreich';
$laender[ 79]['bezeichnung'] = 'Kamerun';
$laender[ 80]['bezeichnung'] = 'Kanada';
$laender[ 81]['bezeichnung'] = 'Kap Verde einschließlich kapverdische Inseln)';
$laender[ 82]['bezeichnung'] = 'Karolinen- und Marianeninseln)';
$laender[ 83]['bezeichnung'] = 'Kasachstan';
$laender[ 84]['bezeichnung'] = 'Katar';
$laender[ 85]['bezeichnung'] = 'Kenia';
$laender[ 86]['bezeichnung'] = 'Kirgistan';
$laender[ 87]['bezeichnung'] = 'Kiribati';
$laender[ 88]['bezeichnung'] = 'Kolumbien';
$laender[ 89]['bezeichnung'] = 'Komoren';
$laender[ 90]['bezeichnung'] = 'Kongo, Demoktratische Republik';
$laender[ 91]['bezeichnung'] = 'Kongo Republik';
$laender[ 92]['bezeichnung'] = 'Korea, Dem. VR / Nordkorea';
$laender[ 93]['bezeichnung'] = 'Korea, Republik / Südkorea';
$laender[ 94]['bezeichnung'] = 'Kroatien';
$laender[ 95]['bezeichnung'] = 'Kuba';
$laender[ 96]['bezeichnung'] = 'Kuwait / Kuweit';
$laender[ 97]['bezeichnung'] = 'Laos';
$laender[ 98]['bezeichnung'] = 'Lesotho';
$laender[ 99]['bezeichnung'] = 'Lettland';
$laender[100]['bezeichnung'] = 'Libanon';
$laender[101]['bezeichnung'] = 'Liberia';
$laender[102]['bezeichnung'] = 'Liechtenstein';
$laender[103]['bezeichnung'] = 'Luxemburg';
$laender[104]['bezeichnung'] = 'Lybien';
$laender[105]['bezeichnung'] = 'Madagaskar';
$laender[106]['bezeichnung'] = 'Malawi';
$laender[107]['bezeichnung'] = 'Malaysia';
$laender[108]['bezeichnung'] = 'Malediven';
$laender[109]['bezeichnung'] = 'Mali';
$laender[110]['bezeichnung'] = 'Malta';
$laender[111]['bezeichnung'] = 'Marokko';
$laender[112]['bezeichnung'] = 'Marshallinseln';
$laender[113]['bezeichnung'] = 'Mauretanien';
$laender[114]['bezeichnung'] = 'Mauritio';
$laender[115]['bezeichnung'] = 'Mazedonien';
$laender[116]['bezeichnung'] = 'Mexico';
$laender[117]['bezeichnung'] = 'Moldau, Republik';
$laender[118]['bezeichnung'] = 'Monaco';
$laender[119]['bezeichnung'] = 'Mongolei';
$laender[120]['bezeichnung'] = 'Montenegro';
$laender[121]['bezeichnung'] = 'Montserrat, Turks- und Caicos-Inseln';
$laender[122]['bezeichnung'] = 'Mosambik';
$laender[123]['bezeichnung'] = 'Mayanmar';
$laender[124]['bezeichnung'] = 'Namibia';
$laender[125]['bezeichnung'] = 'Nauru';
$laender[126]['bezeichnung'] = 'Nepal';
$laender[127]['bezeichnung'] = 'Neukaledonien';
$laender[128]['bezeichnung'] = 'Tokeleau-Inseln';
$laender[129]['bezeichnung'] = 'Neuseeland';
$laender[130]['bezeichnung'] = 'Nicaragua';
$laender[131]['bezeichnung'] = 'Niederlande';
$laender[132]['bezeichnung'] = 'Niger';
$laender[133]['bezeichnung'] = 'Nigeria';
$laender[134]['bezeichnung'] = 'Niue-Inseln';
$laender[135]['bezeichnung'] = 'Norwegen, Bäreninsel, Spitzbergen';
$laender[136]['bezeichnung'] = 'Nördliche Marianen';
$laender[137]['bezeichnung'] = 'Österreich';
$laender[138]['bezeichnung'] = 'Oman';
$laender[139]['bezeichnung'] = 'Pakistan';
$laender[140]['bezeichnung'] = 'Palau';
$laender[141]['bezeichnung'] = 'Panama';
$laender[142]['bezeichnung'] = 'Papua-Neuguinea';
$laender[143]['bezeichnung'] = 'Paraguay';
$laender[144]['bezeichnung'] = 'Peru';
$laender[145]['bezeichnung'] = 'Philippinen';
$laender[146]['bezeichnung'] = 'Pitcam-Insel';
$laender[147]['bezeichnung'] = 'Polen';
$laender[148]['bezeichnung'] = 'Portugal';
$laender[149]['bezeichnung'] = 'Ruanda';
$laender[150]['bezeichnung'] = 'Rumänien';
$laender[151]['bezeichnung'] = 'Russland';
$laender[152]['bezeichnung'] = 'Salomonen';
$laender[153]['bezeichnung'] = 'Sambia';
$laender[154]['bezeichnung'] = 'Samona / Westsamoa';
$laender[155]['bezeichnung'] = 'San Marino';
$laender[156]['bezeichnung'] = 'Sáo Torné und Principe';
$laender[157]['bezeichnung'] = 'Saudi-Arabien';
$laender[158]['bezeichnung'] = 'Schweden';
$laender[159]['bezeichnung'] = 'Schweiz';
$laender[160]['bezeichnung'] = 'Senegal';
$laender[161]['bezeichnung'] = 'Serbien';
$laender[162]['bezeichnung'] = 'Seychellen';
$laender[163]['bezeichnung'] = 'Sierra Leone';
$laender[164]['bezeichnung'] = 'Simbabwe';
$laender[165]['bezeichnung'] = 'Singapur';
$laender[166]['bezeichnung'] = 'Slowakei';
$laender[167]['bezeichnung'] = 'Slowenien';
$laender[168]['bezeichnung'] = 'Somalia';
$laender[169]['bezeichnung'] = 'Spanien';
$laender[170]['bezeichnung'] = 'Sri Lanka';
$laender[171]['bezeichnung'] = 'St. Helena einschließlich Ascension';
$laender[172]['bezeichnung'] = 'St. Kitts und Nevis';
$laender[173]['bezeichnung'] = 'St. Lucia';
$laender[174]['bezeichnung'] = 'St. Pierre und Miquelon';
$laender[175]['bezeichnung'] = 'St. Vincent und die Grenadinen';
$laender[176]['bezeichnung'] = 'Sudan';
$laender[177]['bezeichnung'] = 'Suriname / Surinam';
$laender[178]['bezeichnung'] = 'Swasiland';
$laender[179]['bezeichnung'] = 'Südafrika';
$laender[180]['bezeichnung'] = 'Syrien';
$laender[181]['bezeichnung'] = 'Tadschikistan';
$laender[182]['bezeichnung'] = 'Taiwan';
$laender[183]['bezeichnung'] = 'Tansania Vereinigte Republik';
$laender[184]['bezeichnung'] = 'Thailand';
$laender[185]['bezeichnung'] = 'Togo';
$laender[186]['bezeichnung'] = 'Tonga';
$laender[187]['bezeichnung'] = 'Trinidad und Tobago';
$laender[188]['bezeichnung'] = 'Tschad';
$laender[189]['bezeichnung'] = 'Tschechische Republik';
$laender[190]['bezeichnung'] = 'Tunesien';
$laender[191]['bezeichnung'] = 'Turkmenistan';
$laender[192]['bezeichnung'] = 'Tuvalu';
$laender[193]['bezeichnung'] = 'Türkei';
$laender[194]['bezeichnung'] = 'Uganda';
$laender[195]['bezeichnung'] = 'Ukraine';
$laender[196]['bezeichnung'] = 'Ungarn';
$laender[197]['bezeichnung'] = 'Uruguay';
$laender[198]['bezeichnung'] = 'Usbekistan';
$laender[199]['bezeichnung'] = 'Vánúatú';
$laender[200]['bezeichnung'] = 'Vatikanstadt';
$laender[201]['bezeichnung'] = 'Venezuela';
$laender[202]['bezeichnung'] = 'Vereinigte Arabische Emirate';
$laender[203]['bezeichnung'] = 'Vereinigte Staaten von Amerika (USA)';
$laender[204]['bezeichnung'] = 'Vietnam';
$laender[205]['bezeichnung'] = 'Weihnachtsinseln einschließlich Norfolk-insel';
$laender[206]['bezeichnung'] = 'Weißrussland / Belarus';
$laender[207]['bezeichnung'] = 'Zentralafrikanische Republik';
$laender[208]['bezeichnung'] = 'Zypern';
$laender[209]['bezeichnung'] = 'Staatenlos';
$laender[210]['bezeichnung'] = 'Ungeklärt';
$laender[211]['bezeichnung'] = 'Ohne Angabe';


$religionen[ 0]['wert'] = 'evangelisch';
$religionen[ 1]['wert'] = 'römisch-katholisch';
$religionen[ 2]['wert'] = 'islamisch';
$religionen[ 3]['wert'] = 'baptistisch';
$religionen[ 3]['wert'] = 'methodistisch';
$religionen[ 4]['wert'] = 'jüdisch';
$religionen[ 5]['wert'] = 'syrisch-orthodox';
$religionen[ 6]['wert'] = 'neuapostolisch';
$religionen[ 7]['wert'] = 'Zeugen Jehowas';
$religionen[ 8]['wert'] = 'freikirschlich';
$religionen[ 9]['wert'] = 'alevitisch';
$religionen[10]['wert'] = 'sonstige';
$religionen[11]['wert'] = 'keine';

$religionen[ 0]['bezeichnung'] = 'evangelisch';
$religionen[ 1]['bezeichnung'] = 'römisch-katholisch';
$religionen[ 2]['bezeichnung'] = 'islamisch';
$religionen[ 3]['bezeichnung'] = 'baptistisch';
$religionen[ 3]['bezeichnung'] = 'methodistisch';
$religionen[ 4]['bezeichnung'] = 'jüdisch';
$religionen[ 5]['bezeichnung'] = 'syrisch-orthodox';
$religionen[ 6]['bezeichnung'] = 'neuapostolisch';
$religionen[ 7]['bezeichnung'] = 'Zeugen Jehowas';
$religionen[ 8]['bezeichnung'] = 'freikirschlich';
$religionen[ 9]['bezeichnung'] = 'alevitisch';
$religionen[10]['bezeichnung'] = 'sonstige';
$religionen[11]['bezeichnung'] = 'keine';


$reliunterrichtangebot[0]['bezeichnung'] = 'Evangelische Religion';
$reliunterrichtangebot[0]['wert'] = 'ev';
$reliunterrichtangebot[1]['bezeichnung'] = 'Katholische Religion';
$reliunterrichtangebot[1]['wert'] = 'rk';
$reliunterrichtangebot[2]['bezeichnung'] = 'Kein Religionsunterricht';
$reliunterrichtangebot[2]['wert'] = 'nein';
//$religionsunterricht[3]['fach'] = 'Ethik';
//$religionsunterricht[3]['wert'] = 'Ethik';

$geschlechter[0]['bezeichnung'] = 'weiblich';
$geschlechter[0]['wert'] = 'w';
$geschlechter[1]['bezeichnung'] = 'männlich';
$geschlechter[1]['wert'] = 'm';
//$geschlechter[2]['bezeichnung'] = 'divers';
//$geschlechter[2]['wert'] = 'd';

$klassenbezeichnungen = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

$sprachen[ 0]['wert'] = 'deutsch';
$sprachen[ 1]['wert'] = 'albanisch';
$sprachen[ 2]['wert'] = 'bosnisch';
$sprachen[ 3]['wert'] = 'bulgarisch';
$sprachen[ 4]['wert'] = 'chinesisch';
$sprachen[ 5]['wert'] = 'englisch';
$sprachen[ 6]['wert'] = 'französisch';
$sprachen[ 7]['wert'] = 'griechisch';
$sprachen[ 8]['wert'] = 'italienisch';
$sprachen[ 9]['wert'] = 'japanisch';
$sprachen[10]['wert'] = 'kasachisch';
$sprachen[11]['wert'] = 'kroatisch';
$sprachen[12]['wert'] = 'kurdisch';
$sprachen[13]['wert'] = 'mazedonisch';
$sprachen[14]['wert'] = 'russisch';
$sprachen[15]['wert'] = 'spanisch';
$sprachen[16]['wert'] = 'türkisch';

$sprachen[ 0]['bezeichnung'] = 'deutsch';
$sprachen[ 1]['bezeichnung'] = 'albanisch';
$sprachen[ 2]['bezeichnung'] = 'bosnisch';
$sprachen[ 3]['bezeichnung'] = 'bulgarisch';
$sprachen[ 4]['bezeichnung'] = 'chinesisch';
$sprachen[ 5]['bezeichnung'] = 'englisch';
$sprachen[ 6]['bezeichnung'] = 'französisch';
$sprachen[ 7]['bezeichnung'] = 'griechisch';
$sprachen[ 8]['bezeichnung'] = 'italienisch';
$sprachen[ 9]['bezeichnung'] = 'japanisch';
$sprachen[10]['bezeichnung'] = 'kasachisch';
$sprachen[11]['bezeichnung'] = 'kroatisch';
$sprachen[12]['bezeichnung'] = 'kurdisch';
$sprachen[13]['bezeichnung'] = 'mazedonisch';
$sprachen[14]['bezeichnung'] = 'russisch';
$sprachen[15]['bezeichnung'] = 'spanisch';
$sprachen[16]['bezeichnung'] = 'türkisch';


$profile[ 0]['wert'] = 'keines';
$profile[ 1]['wert'] = 'bilingual';

$profile[ 0]['bezeichnung'] = 'keines';
$profile[ 1]['bezeichnung'] = 'bilingual';


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
