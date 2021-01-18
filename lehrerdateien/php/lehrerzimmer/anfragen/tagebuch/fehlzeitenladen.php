<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['unterricht'])) 	{$unterricht = $_POST['untericht'];} 		        else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

// Stundeninformationen laden
$sql = $dbs->prepare("SELECT tagebuch.id, AES_DECRYPT(tagebuch.inhalt, '$CMS_SCHLUESSEL'), AES_DECRYPT(tagebuch.hausaufgabe, '$CMS_SCHLUESSEL'), tagebuch.freigabe, tagebuch.leistungsmessung, tagebuch.urheber, tbeginn, tende, traum, tkurs, tlehrer, AES_DECRYPT(ur.vorname, '$CMS_SCHLUESSEL') AS urvor, AES_DECRYPT(ur.nachname, '$CMS_SCHLUESSEL') AS urnach, AES_DECRYPT(ur.titel, '$CMS_SCHLUESSEL') AS urtitel, AES_DECRYPT(lehr.vorname, '$CMS_SCHLUESSEL') AS lehrvor, AES_DECRYPT(lehr.nachname, '$CMS_SCHLUESSEL') AS lehrnach, AES_DECRYPT(lehr.titel, '$CMS_SCHLUESSEL') AS lehrtitel, kuerzel, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') FROM tagebuch JOIN unterricht ON tagebuch.id = unterricht.id LEFT JOIN personen AS ur ON ur.id = tagebuch.urheber LEFT JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN personen AS lehr ON lehr.id = tlehrer LEFT JOIN kurse ON tkurs = kurse.id LEFT JOIN raeume ON traum = raeume.id WHERE tagebuch.id = ? AND freigabe != 1");
$sql->bind_param("i", $eintrag);
if ($sql->execute()) {
  $sql->bind_result($uid, $inhalt, $hausi, $frei, $leistung, $urheber, $tbeginn, $tende, $traum, $tkurs, $tlehrer, $urvor, $urnach, $urtitel, $lehrvor, $lehrnach, $lehrtitel, $kuerzel, $raumbez, $kursbez);
  $sql->fetch();
  $zeit = cms_tagnamekomplett(date('N', $tbeginn)).", den ".date("d.m.Y H:i", $tbeginn)." – ".date("H:i", $tende+1);
}
$sql->close();

// Benutzerart laden
$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
  $sql->bind_result($CMS_BENUTZERART);
  $sql->fetch();
}
$sql->close();


if ($angemeldet && $CMS_BENUTZERART == 'l' && $tlehrer == $CMS_BENUTZERID) {
  $code = "";
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');

  // Schüler des Kurses laden
  $schueler = "";
  $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM kursemitglieder JOIN personen ON person = personen.id WHERE gruppe = ? AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname, vorname, titel");
  $sql->bind_param("i", $tkurs);
  if ($sql->execute()) {
    $sql->bind_result($pid, $vorname, $nachname, $titel);
    while ($sql->fetch()) {
      $schueler .= "<option value=\"$pid\">".cms_generiere_anzeigename($vorname, $nachname, $titel)."</option>";
    }
  }
  $sql->close();

  $fehlzeiten = "";
  $fzanzahl = 0;
  $fznr = 0;
  $fzids = [];
  $sql = $dbs->prepare("SELECT fehlzeiten.id, person, von, bis, AES_ENCRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM fehlzeiten WHERE (von < ? AND bis > ?) OR (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) AND person IN (SELECT person FROM kursemitglieder WHERE id = ?)");
  $sql->bind_param("iiiiiii", $tbeginn, $tende, $tbeginn, $tende, $tbeginn, $tende, $tkurs);
  if ($sql->execute()) {
    $sql->bind_result($fid, $fzperson, $fzvon, $fzbis, $fzbem);
    while ($sql->fetch()) {
      $fehlzeiten .= "<table class=\"cms_formular\" id=\"cms_eintrag_fz_$fid\">";
      $fehlzeiten .= "<tr><th>Person:</th><td><select name=\"cms_eintrag_fz_person_$fid\" id=\"cms_eintrag_fz_person_$fid\">".str_replace("value=\"$fzperson\"", "value=\"$fzperson\" selected=\"selected\"", $schueler)."</select></td></tr>";
      $ganztaegig = "<span class=\"cms_button\" onclick=\"cms_eintrag_ganztaegig('$fid')\">Ganztägig</span>";
      $fehlzeiten .= "<tr><th>Zeitraum:</th><td>".cms_uhrzeit_eingabe("cms_eintrag_fz_von_$fid", date("H", $fzvon), date("i", $fzvon))." – ".cms_uhrzeit_eingabe("cms_eintrag_fz_von_$fid", date("H", $fzbis), date("i", $fzbis))." $ganztaegig</td></tr>";
      $fehlzeiten .= "<tr><th>Bemerkung:</th><td><input type=\"text\" name=\"cms_eintrag_fz_bemerkung_$fid\" id=\"cms_eintrag_fz_bemerkung_$fid\" value=\"$fzbem\"></td></tr>";
      $fehlzeiten .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_eintrag_fzweg('$fid');\">– Fehlzeit entfernen</span></td></tr>";
      $fehlzeiten .= "</table>";
      $fzanzahl++;
      $fznr++;
      array_push($fzids, $fid);
    }
  }
  $sql->close();

  echo "$fehlzeiten|||||$fzanzahl|||||".implode("|", $fzids);

  cms_trennen($dbl);
  cms_trennen($dbs);
	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
