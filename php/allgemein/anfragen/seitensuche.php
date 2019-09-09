<?php
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/seiten/navigationen/navigationen.php");
include_once("../../website/seiten/seitenauswertung.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['suchbegriff'])) {$suchbegriff = $_POST['suchbegriff'];} else {echo "FEHLER"; exit;}

if (strlen($suchbegriff) < 2) {
	echo "<p class=\"cms_notiz\">Der Suchbegriff ist zu kurz.</p>";
}
else {
	$ergebnisse = "";
	$ergebnisseseite = "";
	$rohdaten = array();
	$minebene = 100;
	$maxebene = 0;
	$dbs = cms_verbinden('s');
	$suchbegriff = cms_texttrafo_e_db_ohnetag($suchbegriff);
	$sql = $dbs->prepare("SELECT id, bezeichnung, beschreibung, LEVENSHTEIN(bezeichnung, ?) as distanz FROM seiten WHERE (status = 'a' OR status = 's') ORDER BY distanz ASC, bezeichnung ASC LIMIT 10");
	$sql->bind_param("s", $suchbegriff);

	if ($sql->execute()) {
    $sql->bind_result($sid, $sbezeichnung, $sbeschreibung, $x);
    while($sql->fetch()) {
      $pfad = cms_seitenpfad_id_erzeugen($dbs, $sid);
      $ebenenzahl = count($pfad);
      if ((!isset($rohdaten[$ebenenzahl]['bezeichnung'])) || (!is_array($rohdaten[$ebenenzahl]['bezeichnung']))) {
        $rohdaten[$ebenenzahl]['bezeichnung'] = array();
        $rohdaten[$ebenenzahl]['beschreibung'] = array();
        $rohdaten[$ebenenzahl]['pfad'] = array();
      }
      array_push($rohdaten[$ebenenzahl]['bezeichnung'], $sbezeichnung);
      array_push($rohdaten[$ebenenzahl]['beschreibung'], $sbeschreibung);
      array_push($rohdaten[$ebenenzahl]['pfad'], cms_seitenpfadlink_erzeugen($pfad));
      $minebene = min($minebene, $ebenenzahl);
      $maxebene = max($maxebene, $ebenenzahl);
    }
  }
  $sql->close();

	for ($i = $minebene; $i <= $maxebene; $i++) {
		if (isset($rohdaten[$i]['bezeichnung']) && is_array($rohdaten[$i]['bezeichnung'])) {
			for ($j = 0; $j < count($rohdaten[$i]['bezeichnung']); $j++) {
				$anzeigepfad = str_replace('_', ' ', str_replace('/', ' / ', $rohdaten[$i]['pfad'][$j]));
				$ergebnisseseite .= "<li><a href=\"Website/Seiten/Aktuell/".$rohdaten[$i]['pfad'][$j]."\"><p class=\"cms_notiz\">$anzeigepfad</p><p>";
					$ergebnisseseite .= "<b>".$rohdaten[$i]['bezeichnung'][$j]."</b>";
					if (strlen($rohdaten[$i]['beschreibung'][$j]) > 0) {$ergebnisseseite .= "<br>".$rohdaten[$i]['beschreibung'][$j];}
				$ergebnisseseite .= "</p></a></li>";
			}
		}
	}

	$ergebnisseblog = "";
	$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum FROM blogeintraege WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4) AS x WHERE bezeichnung LIKE ? ORDER BY datum DESC, bezeichnung ASC");
	$sql->bind_param("s", $suchbegriff);
	if ($sql->execute()) {
		$sql->bind_result($sbezeichnung, $sdatum);
		while ($sql->fetch()) {
			$bezlink = str_replace(' ', '_', $sbezeichnung);
			$pfad = 'Blog/'.date('Y', $sdatum).'/'.cms_monatsnamekomplett(date('n', $sdatum)).'/'.date('d', $sdatum).'/'.$bezlink;
			$anzeigepfad = str_replace('_', ' ', str_replace('/', ' / ', $pfad));
			$ergebnisseblog .= "<li><a href=\"Website/$pfad\"><p class=\"cms_notiz\">$anzeigepfad</p><p>";
				$ergebnisseblog .= "<b>".$sbezeichnung."</b>";
			$ergebnisseblog .= "</p></a></li>";
		}
	}
	$sql->close();

	// TERMINE
	$ergebnissetermine = "";
	$terminerohdaten['bezeichnung'] = array();
	$terminerohdaten['pfad'] = array();

	$sql = $dbs->prepare("SELECT * FROM ((SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn AS datum, 't' AS art FROM termine WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4) UNION (SELECT bezeichnung, beginn AS datum, 'f' AS art FROM ferien)) AS x WHERE bezeichnung LIKE ? ORDER BY datum DESC, bezeichnung ASC");
	$sql->bind_param("s", $suchbegriff);
	if ($sql->execute()) {
		$sql->bind_result($sbezeichnung, $sdatum, $sart);
		while ($sql->fetch()) {
			$bezlink = str_replace(' ', '_', $sbezeichnung);
			if ($sart == 't') {
				$pfad = 'Termine/'.date('Y', $sdatum).'/'.cms_monatsnamekomplett(date('n', $sdatum)).'/'.date('d', $sdatum).'/'.$bezlink;
			}
			else {
				$pfad = 'Ferien/'.date('Y', $sdatum);
			}
			$anzeigepfad = str_replace('_', ' ', str_replace('/', ' / ', $pfad));
			$ergebnissetermine .= "<li><a href=\"Website/$pfad\"><p class=\"cms_notiz\">$anzeigepfad</p><p>";
				$ergebnissetermine .= "<b>".$sbezeichnung."</b>";
			$ergebnissetermine .= "</p></a></li>";
		}
	}
	$sql->close();

	cms_trennen($dbs);

	if (strlen($ergebnisseseite) > 0) {$ergebnisseseite = "<h3>Seiten</h3><ul>".$ergebnisseseite."</ul>";}
	if (strlen($ergebnisseblog) > 0) {$ergebnisseblog = "<h3>Blogeinträge</h3><ul>".$ergebnisseblog."</ul>";}
	if (strlen($ergebnissetermine) > 0) {$ergebnissetermine = "<h3>Termine</h3><ul>".$ergebnissetermine."</ul>";}
	$ergebnisse .= $ergebnisseseite;
	$ergebnisse .= $ergebnisseblog;
	$ergebnisse .= $ergebnissetermine;

	if (strlen($ergebnisse) > 0) {
		echo $ergebnisse;
	}
	else {
		echo "<p class=\"cms_notiz\">Die Suche war leider ohne Ergebnis.</p>";
	}
}
?>
