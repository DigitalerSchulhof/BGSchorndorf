<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/check.php");

set_time_limit(0);

session_start();
postLesen("version");
if(!cms_check_ganzzahl($version))
  die("FEHLER");
$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet() && $CMS_RECHTE["Administration"]["Schulhof aktualisieren"]) {
  $GitHub_base = "https://api.github.com/repos/oxydon/BGSchorndorf";
  $base_verzeichnis = dirname(__FILE__)."/../../../../..";
  $update_verzeichnis = "$base_verzeichnis/update";
  $backup_verzeichnis = "$base_verzeichnis/backup";

  // Versionen prüfen und Daten laden
  $curl = curl_init();
  $curlConfig = array(
    CURLOPT_URL             => "$GitHub_base/releases/".urlencode($version),
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_HTTPHEADER      => array(
      "Content-Type: application/json",
      "Authorization: token $GITHUB_OAUTH",
      "User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
      "Accept: application/vnd.github.v3+json",
    )
  );
  curl_setopt_array($curl, $curlConfig);
  $antwort = curl_exec($curl);
  curl_close($curl);
  if(!($antwort = json_decode($antwort, true)))
    die("FEHLER");
  if($antwort["prerelease"] || $antwort["draft"])
    die("FEHLER");

  $tarball = $antwort["tarball_url"];

  // Backup machen
  cms_v_loeschen($backup_verzeichnis);
  mkdir($backup_verzeichnis, null, true);
  cms_v_verschieben($base_verzeichnis, $backup_verzeichnis);

  // Update Verzeichnis leeren
  cms_v_loeschen($update_verzeichnis);
  mkdir($update_verzeichnis, null, true);

  // Tarball herunterladen
  $tar_ziel = fopen("$update_verzeichnis/release.tar.gz", "w+");

  $curl = curl_init();
  $curlConfig = array(
    CURLOPT_URL             => $tarball,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_FOLLOWLOCATION  => true,
    CURLOPT_FILE            => $tar_ziel,
    CURLOPT_HTTPHEADER      => array(
      "Content-Type: application/json",
      "Authorization: token $GITHUB_OAUTH",
      "User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
    )
  );
  curl_setopt_array($curl, $curlConfig);
  curl_exec($curl);
  curl_close($curl);
  fclose($tar_ziel);

  $p = new PharData("$update_verzeichnis/release.tar.gz");
  $p->decompress();
  sleep(1);
  unlink("$update_verzeichnis/release.tar.gz");
  sleep(1);
  $p = new PharData("$update_verzeichnis/release.tar");
  $p->extractTo($update_verzeichnis);
  sleep(1);
  unlink("$update_verzeichnis/release.tar");
  sleep(1);
  $d = array_diff(scandir($update_verzeichnis), array(".", ".."));
  rename("$update_verzeichnis/".$d[2], "$update_verzeichnis/release");
  sleep(1);

  // Dev löschen
  cms_v_loeschen("$update_verzeichnis/release/datenbanken");
  cms_v_loeschen("$update_verzeichnis/release/less");
  @unlink("$update_verzeichnis/release/.gitignore");
  @unlink("$update_verzeichnis/release/cms_schulhof.sql");
  @unlink("$update_verzeichnis/release/cms_personen.sql");
  @unlink("$update_verzeichnis/release/encrypt.php");
  @unlink("$update_verzeichnis/release/prepared.php");

  cms_v_verschieben("$update_verzeichnis/release", $base_verzeichnis);
  cms_v_loeschen($update_verzeichnis);
  echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}

function cms_v_loeschen($pfad) {
  if(strpos($pfad, ".git") === 0)
	 return;
  if(!is_dir($pfad))
    return;
  $dateien = array_diff(scandir($pfad), array(".", ".."));
  foreach($dateien as $datei) {
    $datei = "$pfad/$datei";
    if(is_file($datei))
      unlink($datei);
    else if(is_dir($datei))
      cms_v_loeschen($datei);
  }
  @rmdir($pfad);
}

function cms_v_verschieben($von, $nach, $pfad = "") {
  $pfadblacklist = array("/.git/", "/lehrerdateien/", "/backup/", "/update/", "/dateien/", "/css/", "/php/phpmailer/");
  $dateiblacklist = array("/php/schulhof/funktionen/config.php");
  foreach($pfadblacklist as $b)
    if(strpos($pfad, ltrim($b, "/")) === 0)
	   return;

  $dateien = array_diff(scandir("$von$pfad"), array(".", ".."));
  foreach($dateien as $datei) {
    $ddd = "$von$pfad/$datei";
    foreach($dateiblacklist as $b) {
      $b = explode("/", $b);
      $n = array_pop($b);
      $p = join("/", $b);
      if($pfad == $p && $datei == $n)
        continue 2; // Datei überspringen
    }
    if(is_file($ddd)) {
      if(!is_dir("$nach$pfad"))
        @mkdir("$nach$pfad", null, true);
      rename($ddd, "$nach$pfad/$datei");
    } else
      cms_v_verschieben($von, $nach, "$pfad/$datei");
  }
  if(strlen($pfad))
	  @rmdir("$von$pfad");
}
?>
