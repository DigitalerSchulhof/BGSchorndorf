<?php

set_time_limit(0);

include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$DATEIMODE = 0755;
$dbs = cms_verbinden("s");

if ($angemeldet && cms_r("technik.server.update")) {
  $GitHub_base = "https://api.github.com/repos/oxydon/BGSchorndorf";
  $GitHub_base_at = "https://$GITHUB_OAUTH:@api.github.com/repos/oxydon/BGSchorndorf";

  $base_verzeichnis = realpath(dirname(__FILE__)."/../../../../..");
  $update_verzeichnis = "$base_verzeichnis/update";
  $backup_verzeichnis = "$base_verzeichnis/backup";
  $version = trim(file_get_contents("$base_verzeichnis/version/version"));

  if($version == "") {
    cms_anfrage_beenden(); exit;
  }

  // Backup machen
  cms_v_loeschen($backup_verzeichnis);
  mkdir($backup_verzeichnis, $DATEIMODE, true);

  cms_v_verschieben($base_verzeichnis, $backup_verzeichnis);

  // Versionen prüfen und Daten laden
  $curl = curl_init();
  $curlConfig = array(
    CURLOPT_URL             => "$GitHub_base/releases/latest",
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
  if(!($antwort = @json_decode($antwort, true))) {
    cms_backup_fehler(error_get_last());
	}

  $assets = $antwort["assets"];
  $tarball = $antwort["tarball_url"];

  // Update Verzeichnis leeren
  cms_v_loeschen($update_verzeichnis);
  if(!@mkdir($update_verzeichnis, $DATEIMODE, true)) {
    cms_backup_fehler(error_get_last());
  }
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
  try {
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
    cms_v_verschieben("$update_verzeichnis/".$d[2], "$update_verzeichnis/release", "", false);
    sleep(1);

    cms_v_verschieben("$update_verzeichnis/release/lehrerdateien", $base_verzeichnis);

    $dbs = cms_verbinden("s");
    $dbl = cms_verbinden("l");

    ob_start();
    include("$base_verzeichnis/version/updatedb.php");
    $ob = ob_get_contents();
    ob_end_clean();
    $ob = str_replace("{cms_schluessel}", "'$CMS_SCHLUESSEL'", $ob);

    $sql = "";
    $verreicht = false;

    foreach(explode("\n", $ob) as $zeile) {
      if($verreicht) {
        if(preg_match("/^\\s*--/", $zeile) === 1) {
          // Kommentar
          continue;
        }
        $sql .= $zeile;
      } else {
        if(preg_match("/^\\s*--\\s*((?:[0-9]+)(?:\\.[0-9]+)*)\\s*$/", $zeile, $matches) === 1) {
          if(version_compare($matches[1], $version) === 0) {
            $verreicht = true;
          }
          continue;
        }
      }
    }

    $dbl = cms_verbinden("s");
    $dbl->multi_query($sql);
    $dbl->close();

    unlink("$base_verzeichnis/version/updatedb.php");

    cms_v_loeschen($update_verzeichnis);
  } catch(Exception $e) {
    cms_backup_fehler($e->getMessage());
  }

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
  rmdir($pfad);
}

function cms_v_verschieben($von, $nach, $pfad = "", $blacklist = true) {
  // $von, $nach sind die Basen
  global $DATEIMODE;
  $pfadblacklist = array();
  $dateiblacklist = array();
  if($blacklist) {
    $pfadblacklist = array("/.git/", "/backup/", "/update/", "/dateien/");
    $dateiblacklist = array("/php/lehrerzimmer/funktionen/config.php");
  }

  foreach($pfadblacklist as $pfadb) {
    if(strpos($pfad, rtrim($pfadb, "/")) === 0) {
      // Blacklist
      return;
    }
  }
  if(is_dir("$von$pfad")) {
    $dir = opendir("$von$pfad");
    if($dir === false) {
      cms_backup_fehler(array("von" => $von, "nach" => $nach, "pfad" => $pfad, "blacklist" => $blacklist));
    }
    while(($datei = readdir($dir)) !== false) {
      if (!in_array($datei, array(".", ".."))) {
        foreach($dateiblacklist as $dateib) {
          $dateib = explode("/", $dateib);
          $name = array_pop($dateib);
          $dateibpfad = join("/", $dateib);
          if($pfad == $dateibpfad && $datei == $name) {
            continue 2; // Überspringen
          }
        }
        cms_v_verschieben($von, $nach, "$pfad/$datei", $blacklist);
      }
    }
    closedir($dir);
    if(strlen($pfad)) //  / nicht löschen
      @rmdir("$von$pfad");
  } else {
    // Datei verschieben
    if(!is_dir(dirname("$nach$pfad"))) {
      if(!mkdir(dirname("$nach$pfad"), $DATEIMODE, true)) {
        cms_backup_fehler(array("von" => $von, "nach" => $nach, "pfad" => $pfad, "blacklist" => $blacklist));
      }
    }
    if( !copy("$von$pfad", "$nach$pfad") ||
        !unlink("$von$pfad")) {
          cms_backup_fehler(array("von" => $von, "nach" => $nach, "pfad" => $pfad, "blacklist" => $blacklist));
    }
  }
}

$fehler = false;
function cms_backup_fehler(...$args) {
  global $fehler, $base_verzeichnis, $backup_verzeichnis, $update_verzeichnis;
  if($fehler == true) {
    // Schon mal Fehler -> Rekursion
    error_log(json_encode($args));
    cms_anfrage_beenden(); exit;
  }
  error_log(json_encode($args));
  $fehler = true;
  cms_v_verschieben($backup_verzeichnis, $base_verzeichnis, "", false);
  cms_anfrage_beenden("SICHER"); exit
}
?>
