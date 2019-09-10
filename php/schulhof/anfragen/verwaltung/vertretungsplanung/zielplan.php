<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");

session_start();

if (isset($_POST['tag']))   {$tag = $_POST['tag'];} else {echo "FEHLER";exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER";exit;}
if (isset($_POST['jahr']))  {$jahr = $_POST['jahr'];} else {echo "FEHLER";exit;}
if (isset($_POST['lehrer']))  {$lehrergewaehlt = $_POST['lehrer'];} else {echo "FEHLER";exit;}
if (isset($_POST['raum']))  {$raumgewaehlt = $_POST['raum'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['VERTRETUNGSPLANUNGSTUNDE']))  {$stundegewaehlt = $_SESSION['VERTRETUNGSPLANUNGSTUNDE'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($lehrergewaehlt,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($raumgewaehlt,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($stundegewaehlt,0) || ($stundegewaehlt == 'x')) {echo "FEHLER"; exit;}



// ORT:  j - jetzt z - zukunft -- ART: k - klasse l - lehrer r - raum
function cms_generiere_vplanstunde($std, $ugewaehlt) {
	if ($ugewaehlt == $std['uid']) {$zusatz = ' cms_stundenplan_stunde_gewaehlt';} else {$zusatz = "";}
	$event = " onclick=\"cms_vplanstunde_waehlen(".$std['uid'].")\"";
	if (($std['farbe'] <= 4) || (($std['farbe'] >= 12) && ($std['farbe'] <= 23))) {$style = "color:#ffffff;";} else {$style="";}
	$code = "<span class=\"cms_stundenplanung_stunde cms_farbbeispiel_".$std['farbe']."$zusatz\" style=\"$style\"$event>";
		$code .= $std['kursbez']."<br>".$std['lehrerbez']."<br>".$std['raumbez'];
	$code .= "</span>";
	return $code;
}


// Variablen einlesen, falls übergeben
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$code = "";
	$dbs = cms_verbinden('s');
	$jetzt = mktime(0,0,0,$monat,$tag,$jahr);
	$heuteende = mktime(0,0,0,$monat,$tag+1,$jahr)-1;

	$sql = "SELECT id FROM zeitraeume WHERE ? BETWEEN beginn AND ende";
	$sql = $dbs->prepare($sql);
	$sql -> bind_param("i", $jetzt);
	if ($sql->execute()) {
		$sql->bind_result($ZEITRAUM);
		$sql->fetch();
	}
	$sql->close();

	$sql = "SELECT kurs FROM unterricht WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$sql -> bind_param("i", $stundegewaehlt);
	if ($sql->execute()) {
		$sql->bind_result($kursgewaehlt);
		$sql->fetch();
	}
	$sql->close();

  // Lehrer
  $LEHRER = array();
  $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id) AS x ORDER BY kuerzel, nachname, vorname, titel");
  if ($sql->execute()) {
    $sql->bind_result($eid, $evor, $enach, $etitel, $ekurz);
    while ($sql->fetch()) {
      $einzeln = array();
      $einzeln['id'] = $eid;
      $name = $ekurz;
      if (strlen($name) == 0) {$name = cms_generiere_anzeigename($evor, $enach, $etitel);}
      $einzeln['name'] = $name;
      array_push($LEHRER, $einzeln);
    }
  }
  $sql->close();
  if ((count($LEHRER) > 0) && ($lehrergewaehlt === 'x')) {$lehrergewaehlt = $LEHRER[0]['id'];}
  if (count($LEHRER) == 0) {$lehrergewaehlt = '-';}

  // Räume laden
  $RAEUME = array();
  $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM raeume WHERE verfuegbar = '1') AS x ORDER BY bez");
  if ($sql->execute()) {
    $sql->bind_result($eid, $ebez);
    while ($sql->fetch()) {
      $einzeln = array();
      $einzeln['id'] = $eid;
      $einzeln['bez'] = $ebez;
      array_push($RAEUME, $einzeln);
    }
  }
  $sql->close();
  if ((count($RAEUME) > 0) && ($raumgewaehlt === 'x')) {$raumgewaehlt = $RAEUME[0]['id'];}
  if (count($RAEUME) == 0) {$raumgewaehlt = '-';}

  $SCHULSTUNDEN = array();
  $SCHULSTUNDENIDS = array();
  $SCHULSTUNDENBEGINN = array();
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm");
  $sql->bind_param("i", $ZEITRAUM);
  if ($sql->execute()) {
    $sql->bind_result($sid, $sbez, $sbeginns, $sbeginnm, $sendes, $sendem);
    while ($sql->fetch()) {
      $schulstunde = array();
      $schulstunde['id'] = $sid;
      $schulstunde['bez'] = $sbez;
      $schulstunde['beginn'] = $sbeginns*60+$sbeginnm;
      $schulstunde['beginns'] = $sbeginns;
      $schulstunde['beginnm'] = $sbeginnm;
      $schulstunde['ende'] = $sendes*60+$sendem;
      $schulstunde['endes'] = $sendes;
      $schulstunde['endem'] = $sendem;
      $SCHULSTUNDEN[$sid] = $schulstunde;
      array_push($SCHULSTUNDENIDS, $sid);
      $SCHULSTUNDENBEGINN[cms_fuehrendenull($sbeginns).":".cms_fuehrendenull($sbeginnm)] = $sid;
    }
  }
  $sql->close();

  if (count($SCHULSTUNDEN) > 0) {
    $KLASSENUNTERRICHT = array();
    $LEHRERUNTERRICHT = array();
    $RAUMUNTERRICHT = array();
    foreach ($SCHULSTUNDENIDS as $s) {
      $KLASSENUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
      $LEHRERUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
      $RAUMUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
    }

	  // UNTERRICHT IM RAUM LADEN
	  if ($raumgewaehlt."" != '-') {
	    $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id WHERE traum = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
	    $sql->bind_param('iii', $raumgewaehlt, $jetzt, $heuteende);
	    if ($sql->execute()) {
	      $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
	      while ($sql->fetch()) {
	        $stunde = array();
	        $stunde['uid'] = $uid;
	        $stunde['kursid'] = $kid;
	        if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
	        $stunde['kursbez'] = $kursbez;
	        $stunde['lehrerid'] = $ulehrer;
	        if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
	        $stunde['lehrerbez'] = $lehrerbez;
	        $stunde['raumid'] = $uraum;
	        $stunde['raumbez'] = $uraumbez;
	        $stunde['vplanan'] = $vpan;
	        $stunde['vplanart'] = $vpa;
	        $stunde['vplanbem'] = $vpbem;
	        $stunde['farbe'] = $ufarbe;
	        array_push($RAUMUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
	      }
	    }
	    $sql->close();
	  }
	  // UNTERRICHT DES LEHRERS LADEN
	  if ($lehrergewaehlt."" != '-') {
	    $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id WHERE tlehrer = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");

	    $sql->bind_param('iii', $lehrergewaehlt, $jetzt, $heuteende);
	    if ($sql->execute()) {
	      $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
	      while ($sql->fetch()) {
	        $stunde = array();
	        $stunde['uid'] = $uid;
	        $stunde['kursid'] = $kid;
	        if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
	        $stunde['kursbez'] = $kursbez;
	        $stunde['lehrerid'] = $ulehrer;
	        if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
	        $stunde['lehrerbez'] = $lehrerbez;
	        $stunde['raumid'] = $uraum;
	        $stunde['raumbez'] = $uraumbez;
	        $stunde['vplanan'] = $vpan;
	        $stunde['vplanart'] = $vpa;
	        $stunde['vplanbem'] = $vpbem;
	        $stunde['farbe'] = $ufarbe;
	        array_push($LEHRERUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
	      }
	    }
	    $sql->close();
	  }
	  if (cms_check_ganzzahl($kursgewaehlt,0)) {
	    $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, unterricht.kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = unterricht.kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?) AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
	    $sql->bind_param("iii", $kursgewaehlt, $jetzt, $heuteende);
		  if ($sql->execute()) {
		    $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
		    while ($sql->fetch()) {
		      $stunde = array();
		      $stunde['uid'] = $uid;
		      $stunde['kursid'] = $kid;
		      if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
		      $stunde['kursbez'] = $kursbez;
		      $stunde['lehrerid'] = $ulehrer;
		      if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
		      $stunde['lehrerbez'] = $lehrerbez;
		      $stunde['raumid'] = $uraum;
		      $stunde['raumbez'] = $uraumbez;
		      $stunde['vplanan'] = $vpan;
		      $stunde['vplanart'] = $vpa;
		      $stunde['vplanbem'] = $vpbem;
		      $stunde['farbe'] = $ufarbe;
		      array_push($KLASSENUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
		    }
		  }
		  $sql->close();
		}



    $minpp = 1;
    $yakt = 40;
    $zende = $SCHULSTUNDEN[$SCHULSTUNDENIDS[0]]['beginn'];
    foreach ($SCHULSTUNDENIDS AS $s) {
      $spdauer = $SCHULSTUNDEN[$s]['ende'] - $SCHULSTUNDEN[$s]['beginn'];
      // Abstand zur letzten Stunde berechnen
      $yakt += $SCHULSTUNDEN[$s]['beginn'] - $zende;
      $SCHULSTUNDEN[$s]['beginny'] = $yakt;
      $yakt += floor($spdauer / $minpp);
      $SCHULSTUNDEN[$s]['endey'] = $yakt;
      $zende = $SCHULSTUNDEN[$s]['ende'];
    }
    $sphoehe = $SCHULSTUNDEN[$SCHULSTUNDENIDS[count($SCHULSTUNDENIDS)-1]]['endey'];

    $spaltenbreite = 25;

    $code = "";

    $code .= "<div class=\"cms_stundenplan_box\" style=\"height: $sphoehe"."px\">";
      foreach ($SCHULSTUNDENIDS as $s) {
        $code .= "<span class=\"cms_stundenplan_zeitliniebeginn\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginns']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginnm'])."</span></span>";
          $code .= "<span class=\"cms_stundenplan_zeitliniebez\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;line-height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">".$SCHULSTUNDEN[$s]['bez']."</span>";
        $code .= "<span class=\"cms_stundenplan_zeitlinieende\" style=\"top: ".$SCHULSTUNDEN[$s]['endey']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['endes']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['endem'])."</span></span>";
      }
      $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: $spaltenbreite%;\"><h3>Klasse</h3></span>";
      $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".(2*$spaltenbreite)."%\"><h3>Lehrkraft</h3></span>";
      $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".(3*$spaltenbreite)."%\"><h3>Raum</h3></span>";
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
      $code .= "</div>";
      // Klasse / Stufe
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
      $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
      foreach ($SCHULSTUNDENIDS as $s) {
        $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_k_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
        foreach ($KLASSENUNTERRICHT[$s] AS $std) {
          $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
        }
        $code .= "</span>";
      }
      $code .= "</div>";
      // Lehrer
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
      $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
      foreach ($SCHULSTUNDENIDS as $s) {
        $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_l_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
        foreach ($LEHRERUNTERRICHT[$s] AS $std) {
          $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
        }
        $code .= "</span>";
      }
      $code .= "</div>";
      // Raum
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
      $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
      foreach ($SCHULSTUNDENIDS as $s) {
        $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_r_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
        foreach ($RAUMUNTERRICHT[$s] AS $std) {
          $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
        }
        $code .= "</span>";
      }
      $code .= "</div>";

    $code .= "</div>";
  }
	cms_trennen($dbs);
	echo $code;
}
else {
	echo "BERECHTIGUNG";
}
?>
