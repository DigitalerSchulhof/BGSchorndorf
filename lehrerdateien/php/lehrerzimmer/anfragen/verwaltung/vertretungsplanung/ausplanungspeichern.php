<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['art'])) 		    {$art = $_POST['art'];} 			                  else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['ziel'])) 		    {$ziel = $_POST['ziel'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['grund'])) 		  {$grund = $_POST['grund'];} 			              else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['zusatz'])) 		  {$zusatz = cms_texttrafo_e_db($_POST['zusatz']);}else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['vonS'])) 		    {$vonS = $_POST['vonS'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['vonT'])) 		    {$vonT = $_POST['vonT'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['vonM'])) 		    {$vonM = $_POST['vonM'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['vonJ'])) 		    {$vonJ = $_POST['vonJ'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['bisS'])) 		    {$bisS = $_POST['bisS'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['bisT'])) 		    {$bisT = $_POST['bisT'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['bisM'])) 		    {$bisM = $_POST['bisM'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['bisJ'])) 		    {$bisJ = $_POST['bisJ'];} 			                else {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (isset($_POST['folge'])) 		  {$folge = $_POST['folge'];} 			              else {echo "FEHLER"; cms_anfrage_beenden();exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");

if (($folge != 'k') && ($folge != 'e') && ($folge != 'u')) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (($art != 'l') && ($art != 'r') && ($art != 'k') && ($art != 's')) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($ziel,0)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($vonS,0)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($vonT,1,31)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($vonM,1,12)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($vonJ,0)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($bisS,0)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($bisT,1,31)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($bisM,1,12)) {echo "FEHLER"; cms_anfrage_beenden();exit;}
if (!cms_check_ganzzahl($bisJ,0)) {echo "FEHLER"; cms_anfrage_beenden();exit;}


if (($art == 'l') && ($grund != 'dv') && ($grund != 'k') && ($grund != 'kk') && ($grund != 'p') &&
    ($grund != 'b') && ($grund != 'ex') && ($grund != 's')) {cms_anfrage_beenden();exit;}
if (($art == 'r') && ($grund != 'b') && ($grund != 'p') && ($grund != 'k') && ($grund != 's')) {cms_anfrage_beenden();exit;}
if ((($art == 'k') || ($art == 's')) && ($grund != 'ex') && ($grund != 'sh') && ($grund != 'p') && ($grund != 'bv') &&
    ($grund != 'k') && ($grund != 's')) {cms_anfrage_beenden();exit;}

$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = cms_r("lehrerzimmer.vertretungsplan.*");

if ($angemeldet && $zugriff) {
  $code = "";
  $fehler = false;
  $zeitfehler = false;
  $doppeltfehler = false;

  $dbl = cms_verbinden('l');
  $dbs = cms_verbinden('s');
  // Stunden laden
  $tagvon = mktime(0,0,0,$vonM,$vonT,$vonJ);
  $vonbmin = null;
  $vonbstd = null;
  $vonemin = null;
  $vonestd = null;
  $tagbis = mktime(0,0,0,$bisM,$bisT,$bisJ);
  $bisbmin = null;
  $bisbstd = null;
  $bisemin = null;
  $bisestd = null;
  $sql = $dbs->prepare("SELECT beginns, beginnm, endes, endem FROM schulstunden WHERE id = ? AND zeitraum IN (SELECT id FROM zeitraeume WHERE ? BETWEEN beginn AND ende);");
  $sql->bind_param("ii", $vonS, $tagvon);
  if ($sql->execute()) {
    $sql->bind_result($vonbstd, $vonbmin, $vonestd, $vonemin);
    $sql->fetch();
  }
  $sql->bind_param("ii", $bisS, $tagbis);
  if ($sql->execute()) {
    $sql->bind_result($bisbstd, $bisbmin, $bisestd, $bisemin);
    $sql->fetch();
  }
  $sql->close();
  if (($vonbmin === null) || ($bisemin === null)) {$fehler = true;}

  if (!$fehler) {
    $von = mktime($vonbstd, $vonbmin, 0, $vonM, $vonT,$vonJ);
    $bis = mktime($bisestd, $bisemin, 0, $bisM, $bisT,$bisJ);

    if ($von >= $bis) {$zeitfehler = true;}

    if ($art == 'l') {$sql = "SELECT COUNT(*) FROM lehrer WHERE id = ?";}
    if ($art == 'r') {$sql = "SELECT COUNT(*) FROM raeume WHERE id = ?";}
    if ($art == 'k') {$sql = "SELECT COUNT(*) FROM klassen WHERE klassen.id = ? AND schuljahr IN (SELECT id FROM schuljahre WHERE ? BETWEEN schuljahre.beginn AND schuljahre.ende)";}
    if ($art == 's') {$sql = "SELECT COUNT(*) FROM stufen WHERE stufen.id = ? AND schuljahr IN (SELECT id FROM schuljahre WHERE ? BETWEEN schuljahre.beginn AND schuljahre.ende)";}
    $sql = $dbs->prepare($sql);
    if (($art == 'k') || ($art == 's')) {$sql->bind_param("ii", $ziel, $von);}
    else {$sql->bind_param("i", $ziel);}
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  if (!$fehler) {
    // Prüfen, ob zu dieser Zeit bereits eine Ausplanung besteht
    if ($art == 'l') {$sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanunglehrer WHERE lehrer = ? AND ((? BETWEEN von AND bis) OR (? BETWEEN von AND bis) OR (? <= von AND ? >= bis))");}
    if ($art == 's') {$sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungstufen WHERE stufe = ? AND ((? BETWEEN von AND bis) OR (? BETWEEN von AND bis) OR (? <= von AND ? >= bis))");}
    if ($art == 'k') {$sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungklassen WHERE klasse = ? AND ((? BETWEEN von AND bis) OR (? BETWEEN von AND bis) OR (? <= von AND ? >= bis))");}
    if ($art == 'r') {$sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungraeume WHERE raum = ? AND ((? BETWEEN von AND bis) OR (? BETWEEN von AND bis) OR (? <= von AND ? >= bis))");}
    $sql->bind_param("iiiii", $ziel, $von, $bis, $von, $bis);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 0) {$doppeltfehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }


  if ($zeitfehler) {
    $code = "ZEITRAUM";
  }
  else if ($doppeltfehler) {
    $code = "DOPPELT";
  }
  else if (!$fehler) {
    if ($folge == 'e') {$anzeigen = 1;}
    else {$anzeigen = 0;}

    $id = "";
    if ($art == 'l') {
      $id = cms_generiere_kleinste_id('ausplanunglehrer');
      $sql = $dbl->prepare("UPDATE ausplanunglehrer SET lehrer = ?, grund = ?, von = ?, bis = ?, zusatz = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
      $sql->bind_param("isiisi", $ziel, $grund, $von, $bis, $zusatz, $id);
      $sql->execute();
      $sql->close();

      if ($folge != 'k') {
        // Eintragen der Folge
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET vplanart = 'e', vplananzeigen = ? WHERE tlehrer = ? AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?))");
        $sql->bind_param("iiiiiiii", $anzeigen, $ziel, $von, $bis, $von, $bis, $von, $bis);
        $sql->execute();
        $sql->close();

        $VORMERKUNGEN = array();
        $sql = $dbs->prepare("SELECT id, tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSELL') FROM unterricht WHERE tlehrer = ? AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)");
        $sql->bind_param("iiiiiii", $ziel, $von, $bis, $von, $bis, $von, $bis);
        if ($sql->execute()) {
          $sql->bind_result($uid, $ukurs, $ubeginn, $uende, $ulehrer, $uraum, $uvplanbem);
          while ($sql->fetch()) {
            $V = array();
            $V['id'] = $uid;
            $V['kurs'] = $ukurs;
            $V['beginn'] = $ubeginn;
            $V['ende'] = $uende;
            $V['lehrer'] = $ulehrer;
            $V['raum'] = $uraum;
            $V['bem'] = $uvplanbem;
            array_push($VORMERKUNGEN, $V);
          }
        }
        $sql->close();
        foreach ($VORMERKUNGEN as $V) {
          $id = cms_generiere_kleinste_id('unterrichtkonflikt', 's');
          $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanart = 'e', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
          $sql->bind_param("iiiiiiisi", $V['id'], $V['kurs'], $V['beginn'], $V['ende'], $V['lehrer'], $V['raum'], $anzeigen, $V['bem'], $id);
          $sql->execute();
        }
      }
    }
    else if ($art == 'r') {
      $id = cms_generiere_kleinste_id('ausplanungraeume');
      $sql = $dbl->prepare("UPDATE ausplanungraeume SET raum = ?, grund = ?, von = ?, bis = ?, zusatz = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
      $sql->bind_param("isiisi", $ziel, $grund, $von, $bis, $zusatz, $id);
      $sql->execute();
      $sql->close();

      if ($folge != 'k') {
        // Eintragen der Folge
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET vplanart = 'e', vplananzeigen = ? WHERE traum = ? AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?))");
        $sql->bind_param("iiiiiiii", $anzeigen, $ziel, $von, $bis, $von, $bis, $von, $bis);
        $sql->execute();
        $sql->close();

        $VORMERKUNGEN = array();
        $sql = $dbs->prepare("SELECT id, tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSELL') FROM unterricht WHERE traum = ? AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)");
        $sql->bind_param("iiiiiii", $ziel, $von, $bis, $von, $bis, $von, $bis);
        if ($sql->execute()) {
          $sql->bind_result($uid, $ukurs, $ubeginn, $uende, $ulehrer, $uraum, $uvplanbem);
          while ($sql->fetch()) {
            $V = array();
            $V['id'] = $uid;
            $V['kurs'] = $ukurs;
            $V['beginn'] = $ubeginn;
            $V['ende'] = $uende;
            $V['lehrer'] = $ulehrer;
            $V['raum'] = $uraum;
            $V['bem'] = $uvplanbem;
            array_push($VORMERKUNGEN, $V);
          }
        }
        $sql->close();
        foreach ($VORMERKUNGEN as $V) {
          $id = cms_generiere_kleinste_id('unterrichtkonflikt', 's');
          $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanart = 'e', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
          $sql->bind_param("iiiiiiisi", $V['id'], $V['kurs'], $V['beginn'], $V['ende'], $V['lehrer'], $V['raum'], $anzeigen, $V['bem'], $id);
          $sql->execute();
        }
      }
    }
    else if ($art == 'k') {
      $id = cms_generiere_kleinste_id('ausplanungklassen');
      $sql = $dbl->prepare("UPDATE ausplanungklassen SET klasse = ?, grund = ?, von = ?, bis = ?, zusatz = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
      $sql->bind_param("isiisi", $ziel, $grund, $von, $bis, $zusatz, $id);
      $sql->execute();
      $sql->close();

      if ($folge != 'k') {
        // Eintragen der Folge
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET vplanart = 'e', vplananzeigen = ? WHERE tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND tkurs NOT IN (SELECT kurs FROM kurseklassen WHERE klasse != ?) AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?))");
        $sql->bind_param("iiiiiiiii", $anzeigen, $ziel, $ziel, $von, $bis, $von, $bis, $von, $bis);
        $sql->execute();
        $sql->close();

        $VORMERKUNGEN = array();
        $sql = $dbs->prepare("SELECT id, tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSELL') FROM unterricht WHERE tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND tkurs NOT IN (SELECT kurs FROM kurseklassen WHERE klasse != ?) AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)");
        $sql->bind_param("iiiiiiii", $ziel, $ziel, $von, $bis, $von, $bis, $von, $bis);
        if ($sql->execute()) {
          $sql->bind_result($uid, $ukurs, $ubeginn, $uende, $ulehrer, $uraum, $uvplanbem);
          while ($sql->fetch()) {
            $V = array();
            $V['id'] = $uid;
            $V['kurs'] = $ukurs;
            $V['beginn'] = $ubeginn;
            $V['ende'] = $uende;
            $V['lehrer'] = $ulehrer;
            $V['raum'] = $uraum;
            $V['bem'] = $uvplanbem;
            array_push($VORMERKUNGEN, $V);
          }
        }
        $sql->close();
        foreach ($VORMERKUNGEN as $V) {
          $id = cms_generiere_kleinste_id('unterrichtkonflikt', 's');
          $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanart = 'e', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
          $sql->bind_param("iiiiiiisi", $V['id'], $V['kurs'], $V['beginn'], $V['ende'], $V['lehrer'], $V['raum'], $anzeigen, $V['bem'], $id);
          $sql->execute();
        }
      }
    }
    else if ($art == 's') {
      $id = cms_generiere_kleinste_id('ausplanungstufen');
      $sql = $dbl->prepare("UPDATE ausplanungstufen SET stufe = ?, grund = ?, von = ?, bis = ?, zusatz = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
      $sql->bind_param("isiisi", $ziel, $grund, $von, $bis, $zusatz, $id);
      $sql->execute();
      $sql->close();

      if ($folge != 'k') {
        // Eintragen der Folge
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET vplanart = 'e', vplananzeigen = ? WHERE tkurs IN (SELECT id FROM kurse WHERE stufe = ?) AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?))");
        $sql->bind_param("iiiiiiii", $anzeigen, $ziel, $von, $bis, $von, $bis, $von, $bis);
        $sql->execute();
        $sql->close();

        $VORMERKUNGEN = array();
        $sql = $dbs->prepare("SELECT id, tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSELL') FROM unterricht WHERE tkurs IN (SELECT id FROM kurse WHERE stufe = ?) AND ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)");
        $sql->bind_param("iiiiiii", $ziel, $von, $bis, $von, $bis, $von, $bis);
        if ($sql->execute()) {
          $sql->bind_result($uid, $ukurs, $ubeginn, $uende, $ulehrer, $uraum, $uvplanbem);
          while ($sql->fetch()) {
            $V = array();
            $V['id'] = $uid;
            $V['kurs'] = $ukurs;
            $V['beginn'] = $ubeginn;
            $V['ende'] = $uende;
            $V['lehrer'] = $ulehrer;
            $V['raum'] = $uraum;
            $V['bem'] = $uvplanbem;
            array_push($VORMERKUNGEN, $V);
          }
        }
        $sql->close();
        foreach ($VORMERKUNGEN as $V) {
          $id = cms_generiere_kleinste_id('unterrichtkonflikt', 's');
          $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanart = 'e', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL') WHERE id = ?");
          $sql->bind_param("iiiiiiisi", $V['id'], $V['kurs'], $V['beginn'], $V['ende'], $V['lehrer'], $V['raum'], $anzeigen, $V['bem'], $id);
          $sql->execute();
        }
      }
    }

    $code = "ERFOLG";
  }
  else {$code = "FEHLER";}

  if (($code == "ERFOLG") || ($code == "ZEITRAUM") || ($code == "DOPPELT")) {
    cms_lehrerdb_header(true);
    echo $code;
  }
  else {
    cms_lehrerdb_header(false);
    echo $code;
  }
  cms_trennen($dbs);
  cms_trennen($dbl);
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
