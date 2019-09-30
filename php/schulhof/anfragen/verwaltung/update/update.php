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
  unlink("$update_verzeichnis/release.tar.gz");

  $p = new PharData("$update_verzeichnis/release.tar");
  $p->extractTo($update_verzeichnis);
  unlink("$update_verzeichnis/release.tar");

  sleep(1);
  $d = array_diff(scandir($update_verzeichnis), array(".", ".."));
  rename("$update_verzeichnis/".$d[2], "$update_verzeichnis/release");

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
  $d = array_diff(scandir($pfad), array(".", ".."));
  foreach($d as $dd) {
    $dd = "$pfad/$dd";
    if(is_file($dd))
      unlink($dd);
    else if(is_dir($dd))
      cms_v_loeschen($dd);
  }
  rmdir($pfad);
}

function cms_v_verschieben($von, $nach, $pfad = "") {
  $bl = array("/.git", "/backup", "/update", "/dateien", "/css");
  foreach($bl as $b)
    if(strpos($pfad, $b) === 0)
	  return;
  $d = array_diff(scandir("$von$pfad"), array(".", ".."));
  foreach($d as $dd) {
    $ddd = "$von$pfad/$dd";
	  if($pfad == "/php/schulhof/funktionen" && $dd == "config.php")
	   continue;
    if(is_file($ddd)) {
      if(!is_dir("$nach$pfad"))
        @mkdir("$nach$pfad", null, true);
      rename($ddd, "$nach$pfad/$dd");
    } else
      cms_v_verschieben($von, $nach, "$pfad/$dd");
  }
  if(strlen($pfad))
	  @rmdir("$von$pfad");
}
?>
