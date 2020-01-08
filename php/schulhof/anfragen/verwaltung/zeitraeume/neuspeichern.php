<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['beginnT'])) {$beginnT = $_POST['beginnT'];} else {echo "FEHLER"; exit;}
if (isset($_POST['beginnM'])) {$beginnM = $_POST['beginnM'];} else {echo "FEHLER"; exit;}
if (isset($_POST['beginnJ'])) {$beginnJ = $_POST['beginnJ'];} else {echo "FEHLER"; exit;}
if (isset($_POST['endeT'])) {$endeT = $_POST['endeT'];} else {echo "FEHLER"; exit;}
if (isset($_POST['endeM'])) {$endeM = $_POST['endeM'];} else {echo "FEHLER"; exit;}
if (isset($_POST['endeJ'])) {$endeJ = $_POST['endeJ'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mo'])) {$mo = $_POST['mo'];} else {echo "FEHLER"; exit;}
if (isset($_POST['di'])) {$di = $_POST['di'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mi'])) {$mi = $_POST['mi'];} else {echo "FEHLER"; exit;}
if (isset($_POST['do'])) {$do = $_POST['do'];} else {echo "FEHLER"; exit;}
if (isset($_POST['fr'])) {$fr = $_POST['fr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sa'])) {$sa = $_POST['sa'];} else {echo "FEHLER"; exit;}
if (isset($_POST['so'])) {$so = $_POST['so'];} else {echo "FEHLER"; exit;}
if (isset($_POST['aktiv'])) {$aktiv = $_POST['aktiv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['rythmen'])) {$rythmen = $_POST['rythmen'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schulstundenanzahl'])) {$schulstundenanzahl = $_POST['schulstundenanzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schulstundenids'])) {$schulstundenids = $_POST['schulstundenids'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {$SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];} else {echo "FEHLER";exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.anlegen"))) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($mo)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($di)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($mi)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($do)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($fr)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($sa)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($so)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($aktiv)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($beginnT,1,31)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($beginnM,1,12)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($beginnJ,0)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($endeT,1,31)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($endeM,1,12)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($endeJ,0)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($rythmen,1,26)) {echo "FEHLER"; exit;}

	$beginn = mktime(0,0,0,$beginnM,$beginnT,$beginnJ);
	$ende = mktime(23,59,59,$endeM,$endeT,$endeJ);

	if ($beginn >= $ende) {echo "FEHLER"; exit;}

	if (!cms_check_ganzzahl($schulstundenanzahl,0)) {echo "FEHLER"; exit;}

	$dbs = cms_verbinden('s');
	// Existiert das Schuljahr
  $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, beginn, ende FROM schuljahre WHERE id = ?");
  $sql->bind_param('i', $SCHULJAHR);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $sjbeginn, $sjende);
    if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
			if (($beginn < $sjbeginn) || ($ende > $sjende)) {echo "ZEIT"; $fehler = true;}
		} else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

	if (!$fehler) {
		// Prüfen, ob sich die Zeiträume überschneiden
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn < ? AND ende > ?)");
		$sql->bind_param("iiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende);
		if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {
				if ($anzahl != 0) {$fehler = true; echo "DOPPELT";}
			} else {$fehler = true;}
	  } else {$fehler = true;}
	  $sql->close();
	}

	if (!$fehler) {

		$schulstunden = array();
		if ($schulstundenanzahl > 0) {
			$sids = explode('|', $schulstundenids);
			for ($i=1; $i<count($sids); $i++) {
				if (isset($_POST["sbezeichnung_".$sids[$i]])) {
					$schulstunden[$i]['bez'] = cms_texttrafo_e_db($_POST["sbezeichnung_".$sids[$i]]);
				}
				else {$schulstunden[$i]['bez'] = ""; $fehler = true;}
				if (!cms_check_titel($schulstunden[$i]['bez'])) {$fehler = true; echo 1;}

				if (isset($_POST["sbeginns_".$sids[$i]])) {
					$schulstunden[$i]['beginns'] = $_POST["sbeginns_".$sids[$i]];
				}
				else {$schulstunden[$i]['beginns'] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($schulstunden[$i]['beginns'], 0, 23)) {$fehler = true;}
				if (isset($_POST["sbeginnm_".$sids[$i]])) {
					$schulstunden[$i]['beginnm'] = $_POST["sbeginnm_".$sids[$i]];
				}
				else {$schulstunden[$i]['beginnm'] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($schulstunden[$i]['beginnm'], 0, 59)) {$fehler = true;}

				if (isset($_POST["sendes_".$sids[$i]])) {
					$schulstunden[$i]['endes'] = $_POST["sendes_".$sids[$i]];
				}
				else {$schulstunden[$i]['endes'] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($schulstunden[$i]['endes'], 0, 23)) {$fehler = true;}
				if (isset($_POST["sendem_".$sids[$i]])) {
					$schulstunden[$i]['endem'] = $_POST["sendem_".$sids[$i]];
				}
				else {$schulstunden[$i]['endem'] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($schulstunden[$i]['endem'], 0, 59)) {$fehler = true;}

				if (!$fehler) {
					$schulstunden[$i]['beginn'] = $schulstunden[$i]['beginns']*60 + $schulstunden[$i]['beginnm'];
					$schulstunden[$i]['ende'] = $schulstunden[$i]['endes']*60 + $schulstunden[$i]['endem']-1;
				}
			}
		}
	}

	if (!$fehler) {

		// Prüfen, ob sich die schulstundenen überschneiden
		$vergeben = array();
		if ($schulstundenanzahl > 0) {
			$schulstundenfehler = false;
			for ($i=1; $i<=count($schulstunden); $i++) {
				foreach ($vergeben as $v) {
					if (($schulstunden[$i]['beginn'] <= $v['beginn']) && ($schulstunden[$i]['ende'] >= $v['beginn'])) {$schulstundenfehler = true;}
					if (($schulstunden[$i]['ende'] >= $v['ende']) && ($schulstunden[$i]['beginn'] <= $v['ende'])) {$schulstundenfehler = true;}
					if (($schulstunden[$i]['beginn'] >= $v['beginn']) && ($schulstunden[$i]['ende'] <= $v['ende'])) {$schulstundenfehler = true;}
				}
				array_push($vergeben, $schulstunden[$i]);
			}

			if ($schulstundenfehler) {
				echo "STUNDEN";
				$fehler = true;
			}
		}
	}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('zeitraeume');
		// ZEITRAUM EINTRAGEN
		$sql = $dbs->prepare("UPDATE zeitraeume SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ?, mo = ?, di = ?, mi = ?, do = ?, fr = ?, sa = ?, so = ?, rythmen = ?, aktiv = ? WHERE id = ?");
	  $sql->bind_param("isiiiiiiiiiiii", $SCHULJAHR, $bezeichnung, $beginn, $ende, $mo, $di, $mi, $do, $fr, $sa, $so, $rythmen, $aktiv, $id);
	  $sql->execute();
	  $sql->close();

		if ($schulstundenanzahl > 0) {
			$sql = $dbs->prepare("UPDATE schulstunden SET zeitraum = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginns = ?, beginnm = ?, endes = ?, endem = ? WHERE id = ?");
			for ($i=1; $i<=count($schulstunden); $i++) {
				$sid = cms_generiere_kleinste_id('schulstunden');
				$sql->bind_param("isiiiii", $id, $schulstunden[$i]['bez'], $schulstunden[$i]['beginns'], $schulstunden[$i]['beginnm'], $schulstunden[$i]['endes'], $schulstunden[$i]['endem'], $sid);
			  $sql->execute();
			}
		  $sql->close();
		}

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
