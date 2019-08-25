<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "gruppenid", "anzahl"));
$g = $gruppe;
$gid = $gruppenid;
$anz = $anzahl;
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($g)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($anz, 0)) {echo "FEHLER"; exit;}
if (isset($_SESSION["ERSTENACHRICHT_$g"]["$gid"])) {$erste = $_SESSION["ERSTENACHRICHT_$g"]["$gid"];} else {echo "FEHLER"; exit;}
$dbs = cms_verbinden('s');
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);

$zugriff = $CMS_GRUPPENRECHTE['mitglied'];

$namecache = array();

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!$fehler) {
		$gk = cms_textzudb($g);

    $sql = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL'), chat.meldestatus, AES_DECRYPT(person.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(person.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(person.titel, '$CMS_SCHLUESSEL') FROM $gk"."chat as chat JOIN personen as person ON person.id = chat.person WHERE chat.gruppe = ? AND chat.id < ? ORDER BY chat.id DESC LIMIT ".($anz+1);
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $gid, $erste);
    $sql->bind_result($id, $p, $d, $i, $m, $vorname, $nachname, $titel);
    $sql->execute();
    $nachrichten = array();
		$num = 0;
    while($sql->fetch()) {
			if(++$num > $anz)
				break;
			$name = cms_generiere_anzeigename($vorname, $nachname, $titel);
      array_push($nachrichten, array($id, $name, $d, $i, $m, $p == $person));
      $_SESSION["ERSTENACHRICHT_$g"]["$gid"] = $id;
    }
		$del = chr(29);

		if($num > $anz)	// Mehr nachzuladen
			echo "1";
		else
			echo "0";
		echo $del;
    /*
      Die Antwort wird als ","-getrennter String zurück gegeben.
    */
    foreach($nachrichten as $i => $v)
      echo $v[0].$del.$v[1].$del.$v[2].$del.$v[3].$del.$v[4].$del.$v[5].$del;
	}
	else {echo "FEHLER";}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
