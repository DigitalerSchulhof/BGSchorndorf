<?php
$aktiv = true;

if ($aktiv) {
	include_once("php/schulhof/funktionen/config.php");
	include_once("php/schulhof/funktionen/dateisystem.php");
	echo $CMS_SCHLUESSEL."<br><br>";
	cms_dateisystem_ordner_verschluesseln('dateien/schulhof/personen');
}
?>


function cms_generiere_kleinste_id ($tabelle, $netz = "s", $benutzer = '-') {
  $fehler = false;
  $id = '-';

  if ($benutzer == '-') {
    if (isset($_SESSION['BENUTZERID'])) {$benutzer = $_SESSION['BENUTZERID'];}
    else {$fehler = true;}
  }

  if (!$fehler) {

    if ($netz == "l") {$db = cms_verbinden('l');}
    else if ($netz == "s") {$db = cms_verbinden('s');}
    else if ($netz == "p") {$db = cms_verbinden('p');}
    $jetzt = time();
    // Neue ID bestimmten und eintragen
    $sql = "SET FOREIGN_KEY_CHECKS = 0";
    $anfrage = $db->query($sql);

    $sql = "INSERT INTO $tabelle (id, idvon, idzeit) SELECT id, idvon, idzeit FROM (SELECT IFNULL(id*0,0)+$benutzer AS idvon, IFNULL(id*0,0)+$jetzt AS idzeit, IFNULL(MIN(id)+1,1) AS id FROM $tabelle WHERE id+1 NOT IN (SELECT id FROM $tabelle)) AS vorherigeid";
    $anfrage = $db->query($sql);

		$sql = "SET FOREIGN_KEY_CHECKS = 1";
    $anfrage = $db->query($sql);

    // ID zurückgewinnen
    $id = "";
    $sql = "SELECT id FROM $tabelle WHERE idvon = ? AND idzeit = ?";
    if ($anfrage = $db->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $id = $daten['id'];
      } else {$fehler = true;}
      $anfrage->free_result();
    } else {$fehler = true;}

    // Persönliche Daten löschen
    if (!$fehler) {
      $sql = "UPDATE $tabelle SET idvon = NULL, idzeit = NULL WHERE id = $id";
      $anfrage = $db->query($sql);
    }
    cms_trennen($db);
  }
  return $id;
}
