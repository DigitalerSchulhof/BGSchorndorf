<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();
session_write_close();
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
$gk = cms_textzudb($g);

$namecache = array();


$sqlnachrq = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL'), chat.meldestatus, AES_DECRYPT(person.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(person.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(person.titel, '$CMS_SCHLUESSEL') FROM $gk"."chat as chat JOIN personen as person ON person.id = chat.person WHERE gruppe = ? AND chat.id > ? AND chat.fertig = 1 ORDER BY chat.id ASC;";
$sqlbannq = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ? AND chatbannbis < ?";

if (cms_angemeldet() && $zugriff) {
	while (true) {
		$nachrichten = array();
		$jetzt = time();
		$ausgegeben = false;
		$e = "";
		$stati = array();

		$sql = $dbs->prepare($sqlnachrq);
		$sql->bind_param("ii", $gid, $letzte);
		$sql->bind_result($id, $p, $d, $i, $m, $vorname, $nachname, $titel);
		$sql->execute();
	  while($sql->fetch()) {
        if($p != $person) {
					$name = cms_generiere_anzeigename($vorname, $nachname, $titel);
          array_push($nachrichten, array($id, $name, $d, $i, $m));
        }
				session_start();
        $letzte = $_SESSION["LETZTENACHRICHT_$g"]["$gid"] = $id??-1;
				session_write_close();
    }
		$sql->close();

		$gebannt = 1;
		// Stummschaltung prüfen
		$sql = $dbs->prepare($sqlbannq);
		$sql->bind_param("iii", $person, $gid, $jetzt);
		$sql->bind_result($gebannt);
		$sql->execute();
		$sql->fetch();
		$sql->close();
		$gebannt = !$gebannt;		// Umkehrung, weil bei abgelaufener Banndauer (bannbis == 0) 1 gegeben wird.

		// Stati setzen
		if($gebannt)
			array_push($stati, "s");

		// Stati laden und mit den Letzten vergleichen
		$lstati = $_SESSION["LETZTESTATI_$g"]["$gid"]??array();

		if($lstati != $stati)
			foreach($stati as $i => $s) {
				$e .= $s.",";
				$ausgegeben = true;
				session_start();
				$_SESSION["LETZTESTATI_$g"]["$gid"] = $stati;
				session_write_close();
			}

		$lstati = $stati;


    $del = chr(29);
    /*
      Die Antwort wird als $del-getrennter String zurück gegeben.
    */
    foreach($nachrichten as $i => $v) {
      $e .= $v[0].$del.$v[1].$del.$v[2].$del.$v[3].$del;
			$ausgegeben = true;
		}

		if($ausgegeben)
			die($del . $e);

		time_nanosleep(0, 300 * 1000);	// 300 ms
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
