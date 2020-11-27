<?php
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

if(!cms_r("technik.server.update")) {
  echo "BERECHTIGUNG";
  die();
}

$CMS_EINSTELLUNGEN = cms_einstellungen_laden("allgemeineeinstellungen");

if ($CMS_EINSTELLUNGEN["Netze Offizielle Version"]) {
  $Updater_base = "https://pool.digitaler-schulhof.de";
} else {
  $Updater_base = "https://api.github.com/repos/{$CMS_EINSTELLUNGEN['Netze GitHub Benutzer']}/{$CMS_EINSTELLUNGEN['Netze GitHub Repository']}";
}
$basis_verzeichnis = dirname(__FILE__) . "/../../../..";

if (!file_exists("$basis_verzeichnis/version/version")) {
  echo "FEHLER";
  die();
} else {
  $version = trim(file_get_contents("$basis_verzeichnis/version/version"));

  // Versionsverlauf von GitHub holen
  $curl = curl_init();
  $curlConfig = array(
    CURLOPT_URL             => "$Updater_base/releases/latest",
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_HTTPHEADER      => array(
      "Content-Type: application/json",
      "Authorization: token " . $CMS_EINSTELLUNGEN['Netze GitHub OAuth'],
      "User-Agent: " . $_SERVER["HTTP_USER_AGENT"],
      "Accept: application/vnd.github.v3+json",
    )
  );
  curl_setopt_array($curl, $curlConfig);
  $neuste = curl_exec($curl);
  curl_close($curl);

  if (($neuste = json_decode($neuste, true)) === null || !count($neuste) || @$neuste["documentation_url"]/* Fehler mit API */) {
    echo "FEHLER";
    die();
  } else {
    $neusteversion = $neuste["name"];

    if (version_compare($neusteversion, $version, "gt")) {
      echo $neusteversion;
      die();
    }
  }
}
echo "NEIN";
?>