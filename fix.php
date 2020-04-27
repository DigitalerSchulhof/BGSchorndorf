<?php
include_once('php/allgemein/funktionen/sql.php');
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/generieren.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");

if (isset($_GET['token'])) {
  $t = $_GET['token'];
}
else {$t = "";}

if ($t == 'YA13KSzUl8HNWaRFEdpxuAO0MJwrTWBUQJ9ZRmVbB4gCLH2TDSXDvECX7VZ5Pk6cPoGFGeKQnILyNMO') {
  $dbs = cms_verbinden('s');
  $anzahl = 0;
  $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, bedarf, AES_DECRYPT(betrag, '$CMS_SCHLUESSEL') AS preis, AES_DECRYPT(telefon, '$CMS_SCHLUESSEL') AS telefon, AES_DECRYPT(emailadresse, '$CMS_SCHLUESSEL') AS emailadresse, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, eingegangen FROM ebedarf JOIN personen ON ebedarf.id = personen.id LEFT JOIN (SELECT klassen.id AS id, bezeichnung, person FROM klassen JOIN klassenmitglieder ON klassen.id = klassenmitglieder.gruppe WHERE schuljahr = 2) AS klassen ON klassen.person = personen.id WHERE personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND bedarf > 0) AS x ORDER BY bedarf DESC, kbez ASC, nachname ASC, vorname ASC");
  if ($sql->execute()) {
    $sql->bind_result($vor, $nach, $bedarf, $preis, $telefon, $email, $kbez, $eingegangen);
    while ($sql->fetch()) {
      echo $bedarf.";".$kbez.";".$nach.";".$vor.";".$preis.";Tel: ".$telefon.";".$email."<br>";
      $anzahl++;
    }
  }
  $sql->close();

  $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, bedarf, AES_DECRYPT(betrag, '$CMS_SCHLUESSEL') AS preis, AES_DECRYPT(telefon, '$CMS_SCHLUESSEL') AS telefon, AES_DECRYPT(emailadresse, '$CMS_SCHLUESSEL') AS emailadresse, 'X' as kbez, eingegangen FROM ebedarf JOIN personen ON ebedarf.id = personen.id WHERE personen.art != AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND bedarf > 0) AS x ORDER BY bedarf DESC, kbez ASC, nachname ASC, vorname ASC");
  if ($sql->execute()) {
    $sql->bind_result($vor, $nach, $bedarf, $preis, $telefon, $email, $kbez, $eingegangen);
    while ($sql->fetch()) {
      echo $bedarf.";".$kbez.";".$nach.";".$vor.";".$preis.";Tel: ".$telefon.";".$email."<br>";
      $anzahl++;
    }
  }
  $sql->close();

  $interesse = 0;
  $sql = $dbs->prepare("SELECT COUNT(*) FROM ebedarf WHERE bedarf != 0");
  if ($sql->execute()) {
    $sql->bind_result($interesse);
    $sql->fetch();
  }
  $sql->close();

  $nutzerkonten = 1;
  $sql = $dbs->prepare("SELECT COUNT(*) FROM nutzerkonten");
  if ($sql->execute()) {
    $sql->bind_result($nutzerkonten);
    $sql->fetch();
  }
  $sql->close();

  $sql = $dbs->prepare("SELECT COUNT(*) FROM ebedarf");
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {
      echo "<br>".$interesse." Interessenten - ".$anzahl." Rückmeldungen - ".$nutzerkonten." Nutzerkonten - ".round($anzahl/$nutzerkonten*100,2)."%";
    }
  }
  $sql->close();

  cms_trennen($dbs);
}
else {
  echo "Zugriff verweigert!";
}


?>
