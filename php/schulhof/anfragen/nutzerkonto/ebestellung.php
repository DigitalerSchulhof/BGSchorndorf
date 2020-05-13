<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bedarf'])) {$bedarf = $_POST['bedarf'];} else {echo "FEHLER1"; exit;}
if (isset($_POST['anrede'])) {$anrede = cms_texttrafo_e_db($_POST['anrede']);} else {echo "FEHLER7"; exit;}
if (isset($_POST['vorname'])) {$vorname = cms_texttrafo_e_db($_POST['vorname']);} else {echo "FEHLER8"; exit;}
if (isset($_POST['nachname'])) {$nachname = cms_texttrafo_e_db($_POST['nachname']);} else {echo "FEHLER9"; exit;}
if (isset($_POST['hausnr'])) {$hausnr = cms_texttrafo_e_db($_POST['hausnr']);} else {echo "FEHLER10"; exit;}
if (isset($_POST['strasse'])) {$strasse = cms_texttrafo_e_db($_POST['strasse']);} else {echo "FEHLER10"; exit;}
if (isset($_POST['plz'])) {$plz = $_POST['plz'];} else {echo "FEHLER11"; exit;}
if (isset($_POST['ort'])) {$ort = cms_texttrafo_e_db($_POST['ort']);} else {echo "FEHLER12"; exit;}
if (isset($_POST['bedingungen'])) {$bedingungen = $_POST['bedingungen'];} else {echo "FEHLER13"; exit;}
if (isset($_POST['telefon1'])) {$telefon1 = cms_texttrafo_e_db($_POST['telefon1']);} else {echo "FEHLER14"; exit;}
if (isset($_POST['telefon2'])) {$telefon2 = cms_texttrafo_e_db($_POST['telefon2']);} else {echo "FEHLER15"; exit;}
if (isset($_POST['mail1'])) {$mail1 = $_POST['mail1'];} else {echo "FEHLER16"; exit;}
if (isset($_POST['mail2'])) {$mail2 = $_POST['mail2'];} else {echo "FEHLER17"; exit;}
if (isset($_POST['geraeteids'])) {$geraeteids = $_POST['geraeteids'];} else {echo "FEHLER17"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER18";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER19"; exit;}
if (($bedarf != '0') && ($bedarf != '1') && ($bedarf != '2')) {echo "FEHLER20"; exit;}
if (($bedarf == '1') || ($bedarf == '2')) {
  if (($anrede != '-') && ($anrede != 'Frau') && ($anrede != 'Herr')) {echo "FEHLER22"; exit;}
  if (strlen($vorname) < 1) {echo "FEHLER23"; exit;}
  if (strlen($nachname) < 1) {echo "FEHLER24"; exit;}
  if (strlen($strasse) < 1) {echo "FEHLER25"; exit;}
  if (strlen($hausnr) < 1) {echo "FEHLER26"; exit;}
  if (!cms_check_ganzzahl($plz, 0, 99999)) {echo "FEHLER27"; exit;}
  if (strlen($ort) < 1) {echo "FEHLER28"; exit;}

	if (strlen($telefon1) < 4) {echo "FEHLER29"; exit;}
	if ($telefon1 != $telefon2) {echo "FEHLER30"; exit;}
	if (!cms_check_mail($mail1)) {echo "FEHLER31"; exit;}
	if ($mail1 != $mail2) {echo "FEHLER"; exit;}
}
if (($bedarf == '1')) {
	if ($bedingungen != 1) {echo "FEHLER36"; exit;}
}

$da = true;
$statusok = true;
$fehler = false;

// Geräteverfügbarkeit prüfen
$dbs = cms_verbinden('s');
$bestellt = array();
$vorrat = array();
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

  $sql = $dbs->prepare("SELECT stk, id FROM egeraete");
  if ($sql->execute()) {
    $sql->bind_result($stk, $gid);
    while($sql->fetch()) {
      if (isset($bestellt[$gid])) {$vorrat[$gid] = $stk - $bestellt[$gid];}
      else {$vorrat[$gid] = $stk;}
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
      if (isset($_POST['geraet'.$gids[$i]])) {
        $anzahl = $_POST['geraet'.$gids[$i]];
        if (!cms_check_ganzzahl($anzahl, 0,5)) {
          $fehler = true;
        }
        else {
          if ($vorrat[$gids[$i]] < $anzahl) {
            $da = false;
          }
        }
      }
      else {
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


if ($da && $statusok && !$fehler) {
  $sql = $dbs->prepare("DELETE FROM ebestellung WHERE id = ?");
  $sql->bind_param("i", $id);
  $sql->execute();
  $sql->close();

	// INSERT
	$jetzt = time();
	if ($bedarf == '1') {
		$sql = $dbs->prepare("INSERT INTO ebestellung (id, bedarf, status, anrede, vorname, nachname, strasse, hausnr, plz, ort, telefon, email, bedingungen, eingegangen) VALUES (?, ?, 0, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?, ?)");
		$sql->bind_param("iisssssssssii", $id, $bedarf, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort, $telefon1, $mail1, $bedingungen, $jetzt);
	}
	else if ($bedarf == '2') {
    $sql = $dbs->prepare("INSERT INTO ebestellung (id, bedarf, status, anrede, vorname, nachname, strasse, hausnr, plz, ort, telefon, email, bedingungen, eingegangen) VALUES (?, ?, 0, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), 1, ?)");
		$sql->bind_param("iisssssssssi", $id, $bedarf, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort, $telefon1, $mail1, $jetzt);
	}
	else {
    $sql = $dbs->prepare("INSERT INTO ebestellung (id, bedarf, status, anrede, vorname, nachname, strasse, hausnr, plz, ort, telefon, email, bedingungen, eingegangen) VALUES (?, ?, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ?)");
		$sql->bind_param("iii", $id, $bedarf, $jetzt);
	}
	$sql->execute();
	$sql->close();

  if ($bedarf == '1') {
    $sql = $dbs->prepare("INSERT INTO eposten (bestellung, geraet, stueck) VALUES (?, ?, ?)");
    for ($i=0; $i<count($gids); $i++) {
      if ($_POST['geraet'.$gids[$i]] > 0) {
        $sql->bind_param("iii", $id, $gids[$i], $_POST['geraet'.$gids[$i]]);
        $sql->execute();
      }
    }
  	$sql->close();
  }

	echo "ERFOLG";
}
else if (!$statusok) {
  echo "STATUS";
}
else if (!$da) {
  echo "VERFUEGBAR";
}
else if ($fehler) {
  echo "FEHLER";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
