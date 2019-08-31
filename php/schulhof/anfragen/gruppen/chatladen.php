<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "gruppenid"));
$g = $gruppe;
$gid = $gruppenid;
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($g)) {echo "FEHLER"; exit;}
if (isset($_SESSION["LETZTENACHRICHT_$g"]["$gid"])) {$letzte = $_SESSION["LETZTENACHRICHT_$g"]["$gid"];} else {echo "FEHLER"; exit;}
$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);

$zugriff = $CMS_GRUPPENRECHTE['mitglied'];

$namecache = array();
$stati = array();

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!$fehler) {
		$gk = cms_textzudb($g);
    $sql = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL'), chat.meldestatus, AES_DECRYPT(person.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(person.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(person.titel, '$CMS_SCHLUESSEL') FROM $gk"."chat as chat JOIN personen as person ON person.id = chat.person WHERE gruppe = ? AND chat.id > ? AND chat.fertig = 1 ORDER BY chat.id ASC;";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $gid, $letzte);
    $sql->bind_result($id, $p, $d, $i, $m, $vorname, $nachname, $titel);
    $sql->execute();
    $nachrichten = array();
    while($sql->fetch()) {
        if($p != $person) {
					$name = cms_generiere_anzeigename($vorname, $nachname, $titel);
          array_push($nachrichten, array($id, $name, $d, $i, $m));
        }
        $_SESSION["LETZTENACHRICHT_$g"]["$gid"] = $id??-1;
    }

		$gebannt = 1;
		// Stummschaltung prüfen
		$sql = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ? AND chatbannbis < ".time();
		$sql = $dbs->prepare($sql);
		$sql->bind_param("ii", $person, $gid);
		$sql->bind_result($gebannt);
		$sql->execute();
		$sql->fetch();
		$gebannt = !$gebannt;		// Umkehrung, weil bei abgelaufener Banndauer (bannbis == 0) 1 gegeben wird.
		if($gebannt)
			array_push($stati, "s");
		foreach($stati as $i => $s)
			echo $s.",";

    $del = chr(29);
		echo $del;
    /*
      Die Antwort wird als $del-getrennter String zurück gegeben.
    */
    foreach($nachrichten as $i => $v)
      echo $v[0].$del.$v[1].$del.$v[2].$del.$v[3].$del;
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
