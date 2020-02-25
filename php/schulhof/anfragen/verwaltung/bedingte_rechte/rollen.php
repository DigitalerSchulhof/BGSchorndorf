<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

include_once("syntax.php");

session_start();

// Variablen einlesen, falls übergeben
postLesen("bedingungen");



if (cms_angemeldet() && cms_r("schulhof.verwaltung.rechte.rollen.bedingt")) {
  if(($bedingungen = json_decode($bedingungen, true)) === null)
    die("FEHLER");

  // Eingabe überprüfen

  foreach($bedingungen as $rolle => $bed) {
    if(!cms_check_ganzzahl($rolle))
      die("FEHLER");
    foreach($bed as $bedingung) {
      if(!cms_bedingt_bedingung_syntax_pruefen($bedingung)) {
        die("SYNTAX");
      }
    }
  }

  $dbs = cms_verbinden("s");


  $sql = "DELETE FROM bedingterollen";
  $sql = $dbs->prepare($sql);
  $sql->execute();

  $sql = "INSERT INTO bedingterollen (rolle, bedingung) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'));";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("is", $rolle, $bedingung);

  foreach($bedingungen as $rolle => $bed)
    foreach($bed as $bedingung)
      if($bedingung != "")
        $sql->execute();

  echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
