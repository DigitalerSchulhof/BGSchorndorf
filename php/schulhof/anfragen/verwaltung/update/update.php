<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/check.php");

session_start();
postLesen("version");
if(!cms_check_ganzzahl($version))
  die("FEHLER");
$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet() && $CMS_RECHTE["Administration"]["Schulhof aktualisieren"]) {
  $GitHub_base = "https://api.github.com/repos/oxydon/BGSchorndorf";
  $basis_verzeichnis = dirname(__FILE__)."/../../../";

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
}
else {
	echo "BERECHTIGUNG";
}
?>
