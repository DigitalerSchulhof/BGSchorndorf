<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bedarf'])) {$bedarf = $_POST['bedarf'];} else {echo "FEHLER1"; exit;}
if (isset($_POST['geraeteids'])) {$geraeteids = $_POST['geraeteids'];} else {echo "FEHLER17"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER18";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER19"; exit;}
if (($bedarf != '0') && ($bedarf != '1') && ($bedarf != '2')) {echo "FEHLER20"; exit;}

$statusok = true;
$fehler = false;

// Geräteverfügbarkeit prüfen
$dbs = cms_verbinden('s');
$bestellt = array();
$vorrat = array();
$geraete = array();
if ($bedarf == '1') {
  $sql = $dbs->prepare("SELECT SUM(stueck), geraet FROM eposten WHERE bestellung != ? GROUP BY geraet");
  $sql->bind_param("i", $CMS_BENUTZERID);
  if ($sql->execute()) {
    $sql->bind_result($b, $gid);
    while($sql->fetch()) {
      $bestellt[$gid] = $b;
    }
  }
  $sql->close();

  $sql = $dbs->prepare("SELECT stk, id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), preis FROM egeraete");
  if ($sql->execute()) {
    $sql->bind_result($stk, $gid, $titel, $preis);
    while($sql->fetch()) {
      if (isset($bestellt[$gid])) {$vorrat[$gid] = $stk - $bestellt[$gid];}
      else {$vorrat[$gid] = $stk;}
      $geraete[$gid]['titel'] = $titel;
      $geraete[$gid]['preis'] = $preis;
    }
  }
  $sql->close();

  if (!cms_check_idfeld($geraeteids)) {
    $fehler = true;
  }
  else {
    $geraeteids = substr($geraeteids, 1);
    $gids = explode("|", $geraeteids);
    for ($i=0; $i<count($gids); $i++) {
      if (!isset($_POST['geraet'.$gids[$i]])) {
        $fehler = true;
      }
    }
  }
}

$sql = $dbs->prepare("SELECT COUNT(*) FROM nutzerkonten WHERE id = ?");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($anzahl);
	if ($sql->fetch()) {
		if ($anzahl != 1) {$fehler = true;}
	}
	else {$fehler = true;}
}
$sql->close();

$sql = $dbs->prepare("SELECT COUNT(*) FROM ebestellung WHERE id = ? AND status >= 2");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($anzahl);
	if ($sql->fetch()) {
		if ($anzahl > 0) {$statusok = false;}
	}
	else {$fehler = true;}
}
$sql->close();


if ($statusok && !$fehler) {
  $code = "";
  if ($bedarf == '0') {
    $code .= "<tr><td colspan=\"4\" class=\"cms_zentriert\"><i>Kein Bedarf</i></td></tr>";
  }
  else if ($bedarf == '2') {
    $code .= "<tr><td>Leihgerät von der Schule</td><td style=\"text-align: right\">1</td><td style=\"text-align: right\">0,00 €</td><td style=\"text-align: right\">0,00 €</td></tr>";
    $code .= "<tr><th colspan=\"3\">Gesamt</th><th style=\"text-align: right\">0,00 €</th></tr>";
  }
  else {
    $summe = 0;
    for ($i=0; $i<count($gids); $i++) {
      $anzahl = $_POST['geraet'.$gids[$i]];
      $zwischensumme = $geraete[$gids[$i]]['preis'] * $anzahl;
      if ($anzahl > 0) {
        $code .= "<tr><td>".$geraete[$gids[$i]]['titel']."</td><td style=\"text-align: right\">";
        if ($vorrat[$gids[$i]] < $anzahl) {
          $code .= "<i>nicht verfügbar</i>";
        }
        else {
          $code .= $anzahl;
        }
        $code .= "</td><td style=\"text-align: right\">".cms_format_preis($geraete[$gids[$i]]['preis']/100)." €</td><td style=\"text-align: right\">".cms_format_preis($zwischensumme/100)." €</td></tr>";

        $summe += $zwischensumme;
      }
    }
    if ($summe == 0) {
      $code .= "<tr><td colspan=\"4\" class=\"cms_zentriert\"><i>Kein Gerät gewählt</i></td></tr>";
    }
    $code .= "<tr><th colspan=\"3\">Gesamt</th><th style=\"text-align: right\">".cms_format_preis($summe/100)." €</th></tr>";
  }

	echo $code;
}
else {
	echo "FEHLER";
}
cms_trennen($dbs);
?>
