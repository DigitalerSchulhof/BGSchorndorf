<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['verfuegbar'])) {$verfuegbar = $_POST['verfuegbar'];} else {echo "FEHLER"; exit;}
if (isset($_POST['buchbar'])) {$buchbar = $_POST['buchbar'];} else {echo "FEHLER"; exit;}
if (isset($_POST['extern'])) {$extern = $_POST['extern'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stundenplan'])) {$stundenplan = $_POST['stundenplan'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geraeteanzahl'])) {$geraeteanzahl = $_POST['geraeteanzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geraeteids'])) {$geraeteids = $_POST['geraeteids'];} else {echo "FEHLER"; exit;}
if (isset($_POST['blockierunganzahl'])) {$blockierunganzahl = $_POST['blockierunganzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['blockierungids'])) {$blockierungids = $_POST['blockierungids'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["RAUMBEARBEITEN"])) {$id = $_SESSION["RAUMBEARBEITEN"];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);
$stundenplan = cms_texttrafo_e_db($stundenplan);


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Räume bearbeiten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}
	if (!cms_check_toggle($verfuegbar)) {$fehler = true;}
	if (!cms_check_toggle($buchbar)) {$fehler = true;}
	if (!cms_check_toggle($extern)) {$fehler = true;}

	if (!cms_check_ganzzahl($blockierunganzahl,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($geraeteanzahl,0)) {$fehler = true;}

	$gbezeichnung = array();

	if (!$fehler) {
		$gbezeichnung = array();

		if ($geraeteanzahl > 0) {
			$gids = explode('|', $geraeteids);
			for ($i=1; $i<count($gids); $i++) {
				if (isset($_POST["gbezeichnung_".$gids[$i]])) {
					if (!in_array($_POST["gbezeichnung_".$gids[$i]], $gbezeichnung)) {
						$gbezeichnung[$i] = cms_texttrafo_e_db($_POST["gbezeichnung_".$gids[$i]]);
					}
					else {
						$gbezeichnung[$i] = "";
						$fehler = true;
					}
				}
				else {$gbezeichnung[$i]= ""; $fehler = true;}
				if (strlen($gbezeichnung[$i]) < 1) {$fehler = true;}
			}
		}

		if ($blockierunganzahl > 0) {
			$bids = explode('|', $blockierungids);
			for ($i=1; $i<count($bids); $i++) {
				if (isset($_POST["bwochentag_".$bids[$i]])) {
					$bwochentag[$i] = $_POST["bwochentag_".$bids[$i]];
				}
				else {$bwochentag[$i]= ""; $fehler = true;}
				if (!cms_check_ganzzahl($bwochentag[$i],1,7)) {$fehler = true;}

				if (isset($_POST["bgrund_".$bids[$i]])) {
					$bgrund[$i] = cms_texttrafo_e_db($_POST["bgrund_".$bids[$i]]);
				}
				else {$bgrund[$i] = ""; $fehler = true;}
				if (strlen($bgrund[$i]) == 0) {$fehler = true;}

				if (isset($_POST["bbeginns_".$bids[$i]])) {
					$bbeginns[$i] = $_POST["bbeginns_".$bids[$i]];
				}
				else {$bbeginns[$i] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($bbeginns[$i],0,23)) {$fehler = true;}

				if (isset($_POST["bbeginnm_".$bids[$i]])) {
					$bbeginnm[$i] = $_POST["bbeginnm_".$bids[$i]];
				}
				else {$bbeginnm[$i] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($bbeginnm[$i],0,59)) {$fehler = true;}

				if (isset($_POST["bendes_".$bids[$i]])) {
					$bendes[$i] = $_POST["bendes_".$bids[$i]];
				}
				else {$bendes[$i] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($bendes[$i],0,23)) {$fehler = true;}

				if (isset($_POST["bendem_".$bids[$i]])) {
					$bendem[$i] = $_POST["bendem_".$bids[$i]];
				}
				else {$bendem[$i] = ""; $fehler = true;}
				if (!cms_check_ganzzahl($bendem[$i],0,59)) {$fehler = true;}

				if (isset($_POST["bferien_".$bids[$i]])) {
					$bferien[$i] = $_POST["bferien_".$bids[$i]];
				}
				else {$bferien[$i]= ""; $fehler = true;}
				if (!cms_check_toggle($bferien[$i])) {$fehler = true;}
			}
		}
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		// Prüfen, ob es ein Raum mit dieser Bezeichnung vorliegt
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM raeume WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
	  $sql->bind_param("si", $bezeichnung, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
		cms_trennen($dbs);

		// Prüfen, ob sich die Blockierungen überschneiden
		if ($blockierunganzahl > 0) {
			$blockierungen = array();
			for ($i=1; $i<=7; $i++) {$blockierungen[$i] = array();}
			$blockfehler = false;
			for ($i=1; $i<=count($bbeginns); $i++) {
				$zeit['beginn'] = $bbeginns[$i]*60 + $bbeginnm[$i];
				$zeit['ende'] = $bendes[$i]*60 + $bendem[$i];

				$vergeben = $blockierungen[$bwochentag[$i]];
				foreach ($vergeben as $v) {
					if (($zeit['beginn'] <= $v['beginn']) && ($zeit['ende'] >= $v['beginn'])) {$blockfehler = true;}
					if (($zeit['ende'] >= $v['ende']) && ($zeit['beginn'] <= $v['ende'])) {$blockfehler = true;}
					if (($zeit['beginn'] >= $v['beginn']) && ($zeit['ende'] <= $v['ende'])) {$blockfehler = true;}
				}
				array_push($blockierungen[$bwochentag[$i]], $zeit);
			}

			if ($blockfehler) {
				echo "BLOCK";
				$fehler = true;
			}
		}
	}

	if (!$fehler) {

		// RAUM EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE raeume SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stundenplan = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), verfuegbar = ?, buchbar = ?, externverwaltbar = ? WHERE id = ?");
	  $sql->bind_param("ssiiii", $bezeichnung, $stundenplan, $verfuegbar, $buchbar, $extern, $id);
	  $sql->execute();
	  $sql->close();

		// Geräte
		// Alle vorhandenen Geräte laden
		$gidsvorhanden = "";
		$sql = "SELECT id FROM raeumegeraete WHERE standort = $id";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil Ganzzahl Check
			while ($daten = $anfrage->fetch_assoc()) {
				$gidsvorhanden .= '|'.$daten['id'];
			}
			$anfrage->free();
		}
		$gidsvorhanden = $gidsvorhanden."|";

		$gids = explode('|', $geraeteids);
		$sqlneu = $dbs->prepare("UPDATE raeumegeraete SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), standort = ? WHERE id = ?");
		$sqlalt = $dbs->prepare("UPDATE raeumegeraete SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		for ($i=1; $i<count($gids); $i++) {
			// Nicht neu hinzugefügt
			$bezeichnung = $_POST["gbezeichnung_".$gids[$i]];
			if (substr($gids[$i], 0,4) != 'temp') {
				$sqlalt->bind_param("si", $bezeichnung, $gids[$i]);
				$sqlalt->execute();
			}
			else {
				$gid = cms_generiere_kleinste_id('raeumegeraete');
				$sqlneu->bind_param("sii", $bezeichnung, $id, $gid);
				$sqlneu->execute();
			}
			$gidsvorhanden = str_replace('|'.$gids[$i].'|', '|', $gidsvorhanden);
		}
		$sqlneu->close();
		$sqlalt->close();
		// Übrige GIDSVORHANDEN löschen
		if (strlen($gidsvorhanden) > 1) {
			$gidsvorhanden = substr($gidsvorhanden, 0, -1);
			$gidsloeschen = explode('|', $gidsvorhanden);
			$sql = $dbs->prepare("DELETE FROM raeumegeraete WHERE id = ? AND standort = ?");
			for ($i=1; $i<count($gidsloeschen); $i++) {
				$sql->bind_param("ii", $gidsloeschen[$i], $id);
			  $sql->execute();
			}
		  $sql->close();
		}

		// BLOCKIERUNGEN ENTFERNEN
		$sql = $dbs->prepare("DELETE FROM raeumeblockieren WHERE standort = ?");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		// BLOCKIERUNGEN EINTRAGEN
		if ($blockierunganzahl > 0) {
			$sql = $dbs->prepare("UPDATE raeumeblockieren SET standort = ?, grund = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), wochentag = ?, beginns = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginnm = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), endes = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), endem = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ferien = ? WHERE id = ?");
			for ($i=1; $i<=count($bwochentag); $i++) {
				$bid = cms_generiere_kleinste_id('raeumeblockieren');
				$sql->bind_param("isissssii", $id, $bgrund[$i], $bwochentag[$i], $bbeginns[$i], $bbeginnm[$i], $bendes[$i], $bendem[$i], $bferien[$i], $bid);
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
?>
