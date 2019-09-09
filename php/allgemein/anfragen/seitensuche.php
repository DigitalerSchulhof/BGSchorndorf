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
	$limit = 5;

	$kx = -1;
	$kk = "seite";

	$ergebnisse = "";
	$ergebnisseseite = "";
	$dbs = cms_verbinden('s');
	$suchbegriff = cms_texttrafo_e_db_ohnetag($suchbegriff);
	$sql = $dbs->prepare("SELECT * FROM (SELECT id, status, bezeichnung, beschreibung, LEVENSHTEIN(bezeichnung, ?) as distanz FROM seiten) as x WHERE (status = 'a' OR status = 's') ORDER BY distanz ASC, bezeichnung ASC LIMIT $limit");
	$sql->bind_param("s", $suchbegriff);

	if ($sql->execute()) {
    $sql->bind_result($sid, $st, $sbezeichnung, $sbeschreibung, $x);
    while($sql->fetch()) {
      $pfad = cms_seitenpfad_id_erzeugen($dbs, $sid);
			$pfad = cms_seitenpfadlink_erzeugen($pfad);

			$anzeigepfad = str_replace('_', ' ', str_replace('/', ' / ', $pfad));
			$ergebnisseseite .= "<li><a href=\"Website/Seiten/Aktuell/".$pfad."\"><p class=\"cms_notiz\">$anzeigepfad</p><p>";
				$ergebnisseseite .= "<b>".$sbezeichnung."</b>";
				if (strlen($sbeschreibung) > 0) {$ergebnisseseite .= "<br>".$sbeschreibung;}
			$ergebnisseseite .= "</p></a></li>";
    }
  }
  $sql->close();

	$ergebnisseblog = "";
	$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, LEVENSHTEIN(AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), ?) as distanz FROM blogeintraege WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4) AS x ORDER BY distanz ASC, datum DESC, bezeichnung ASC LIMIT $limit");
	$sql->bind_param("s", $suchbegriff);
	if ($sql->execute()) {
		$sql->bind_result($sbezeichnung, $sdatum, $x);
		while ($sql->fetch()) {
			if($kx == -1) $kx = $x;
			if($kx > $x) {
				$kx = $x;
				$kk = "blog";
			}
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

	$sql = $dbs->prepare("SELECT * FROM ((SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn as datum, LEVENSHTEIN(AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), ?) as distanz, 't' AS art FROM termine WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4) UNION (SELECT bezeichnung, beginn AS datum, LEVENSHTEIN(bezeichnung, ?) as distanz, 'f' AS art FROM ferien)) AS x ORDER BY distanz ASC, datum DESC, bezeichnung ASC LIMIT $limit");
	$sql->bind_param("ss", $suchbegriff, $suchbegriff);
	if ($sql->execute()) {
		$sql->bind_result($sbezeichnung, $sdatum, $x, $sart);
		while ($sql->fetch()) {
			if($kx > $x) {
				$kx = $x;
				$kk = "termine";
			}
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


	$ergebnissegalerien = "";
	$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, LEVENSHTEIN(AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), ?) as distanz FROM galerien WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4) AS x ORDER BY distanz ASC, datum DESC, bezeichnung ASC LIMIT $limit");
	$sql->bind_param("s", $suchbegriff);
	if ($sql->execute()) {
		$sql->bind_result($sbezeichnung, $sdatum, $x);
		while ($sql->fetch()) {
			if($kx > $x) {
				$kx = $x;
				$kk = "galerien";
			}
			$bezlink = str_replace(' ', '_', $sbezeichnung);
			$pfad = 'Blog/'.date('Y', $sdatum).'/'.cms_monatsnamekomplett(date('n', $sdatum)).'/'.date('d', $sdatum).'/'.$bezlink;
			$anzeigepfad = str_replace('_', ' ', str_replace('/', ' / ', $pfad));
			$ergebnissegalerien .= "<li><a href=\"Website/$pfad\"><p class=\"cms_notiz\">$anzeigepfad</p><p>";
				$ergebnissegalerien .= "<b>".$sbezeichnung."</b>";
			$ergebnissegalerien .= "</p></a></li>";
		}
	}
	$sql->close();

	cms_trennen($dbs);

	if (strlen($ergebnisseseite) > 0) {$ergebnisseseite = "<h3>Seiten</h3><ul>".$ergebnisseseite."</ul>";}
	if (strlen($ergebnisseblog) > 0) {$ergebnisseblog = "<h3>Blogeinträge</h3><ul>".$ergebnisseblog."</ul>";}
	if (strlen($ergebnissetermine) > 0) {$ergebnissetermine = "<h3>Termine</h3><ul>".$ergebnissetermine."</ul>";}
	if (strlen($ergebnissegalerien) > 0) {$ergebnissegalerien = "<h3>Galerien</h3><ul>".$ergebnissegalerien."</ul>";}

	// Bestes Ergebnis zuerst
	$kk = "ergebnisse$kk";
	$ergebnisse .= $$kk;
	$$kk = "";

	$ergebnisse .= $ergebnisseseite;
	$ergebnisse .= $ergebnisseblog;
	$ergebnisse .= $ergebnissetermine;
	$ergebnisse .= $ergebnissegalerien;

	if (strlen($ergebnisse) > 0) {
		echo $ergebnisse;
	}
	else {
		echo "<p class=\"cms_notiz\">Die Suche war leider ohne Ergebnis.</p>";
	}
}
?>
