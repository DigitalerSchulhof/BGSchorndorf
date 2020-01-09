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

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.rechte.bedingt")) {
  if(($bedingungen = json_decode($bedingungen, true)) === null)
    die("FEHLER");

  // Eingabe überprüfen

  foreach($bedingungen as $recht => $bed) {
    if(!preg_match("/^(?:[a-zäöüß*.]+)$/i", $recht))
      die("FEHLER");
    foreach($bed as $bedingung) {
      if(!cms_bedingt_bedingung_syntax_pruefen($bedingung)) {
        die("SYNTAX");
      }
    }
  }

  $dbs = cms_verbinden("s");


  $sql = "DELETE FROM bedingterechte";
  $sql = $dbs->prepare($sql);
  $sql->execute();

  $sql = "INSERT INTO bedingterechte (recht, bedingung) VALUES (AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'));";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ss", $recht, $bedingung);

  foreach($bedingungen as $recht => $bed)
    foreach($bed as $bedingung)
      if($bedingung != "")
        $sql->execute();

  echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
