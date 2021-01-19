<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

postLesen("sub");


if (cms_angemeldet()) {
    $sub = json_decode($sub, true);
    if($sub === null) {
        die("FEHLER");
    }
    if(!isset($sub["endpoint"]) || !isset($sub["keys"]) || !isset($sub["keys"]["p256dh"]) || !isset($sub["keys"]["auth"])) {
        die("FEHLER");
    }
    $endpoint = $sub["endpoint"];
    $p256dh = $sub["keys"]["p256dh"];
    $auth = $sub["keys"]["auth"];
    $dbs = cms_verbinden("s");

    $id = cms_generiere_kleinste_id("pushendpoints");
    $sql = $dbs->prepare("UPDATE pushendpoints SET nutzer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), endpoint = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), p256dh = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), auth = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
    $sql->bind_param("isssi", $_SESSION['BENUTZERID'], $endpoint, $p256dh, $auth, $id);
    $sql->execute();
    echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}