<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p><?php
// Nach Updates prüfen
if(cms_r("technik.server.update")) {
	$GitHub_base = "https://api.github.com/repos/oxydon/BGSchorndorf";
	$basis_verzeichnis = dirname(__FILE__)."/../../../..";

	if(!file_exists("$basis_verzeichnis/version/version")) {
		echo cms_meldung("fehler", "<h4>Ungültige Version</h4><p>Bitte den Administrator benachrichtigen!</p>");
	} else {
		$version = trim(file_get_contents("$basis_verzeichnis/version/version"));

		// Versionsverlauf von GitHub holen
		$curl = curl_init();
		$curlConfig = array(
			CURLOPT_URL             => "$GitHub_base/releases/latest",
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_HTTPHEADER      => array(
				"Content-Type: application/json",
				"Authorization: token ".$CMS_EINSTELLUNGEN['Netze GitHub'],
				"User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
				"Accept: application/vnd.github.v3+json",
			)
		);
		curl_setopt_array($curl, $curlConfig);
		$neuste = curl_exec($curl);
		curl_close($curl);

		if(($neuste = json_decode($neuste, true)) === null || !count($neuste) || @$neuste["documentation_url"]/* Fehler mit API */)
			echo cms_meldung_fehler();
		else {
			$neusteversion = $neuste["name"];

			if(version_compare($neusteversion, $version, "gt")) {
				echo cms_meldung("erfolg", "<h4>Neue Version</h4><p>Es ist eine neue Version für den Digitalen Schulhof verfügbar: <b>".$neusteversion."</b></p>");
				echo "<span class=\"cms_button_wichtig\" onclick=\"cms_link('Schulhof/Verwaltung/Update')\">Schulhof aktualisieren</span> ";
			}
		}
	}
}

// Das ist neu

$sql = "INSERT INTO updatenews (person, gesehen) VALUES (?, 1) ON DUPLICATE KEY UPDATE person=person";
$sql = $dbs->prepare($sql);
$sql->bind_param("i", $CMS_BENUTZERID);
$sql->execute();
$num = $sql->affected_rows;
$sql->close();

include_once(dirname(__FILE__)."/../../../allgemein/funktionen/yaml.php");
use Async\Yaml;
if($num > 0) {
	$aeltere = "";
	$versionen = Yaml::loader(dirname(__FILE__)."/../../../../version/versionen.yml")["version"];
	$version = array_values($versionen)[0];

	$meldung  = "<h4>".$version["version"]."</h4>";
	$meldung .= "<h6>Das ist neu:</h6>";
	$meldung .= "<ul>";
	foreach($version["neuerungen"] as $n) {
		$meldung .= "<li>$n</li>";
	}
	$meldung .= "</ul>";
	echo cms_meldung("info", $meldung);
}

?><?php
echo "<h1>Willkommen $CMS_BENUTZERVORNAME $CMS_BENUTZERNACHNAME!</h1>";

// eBedarf-Umfrage
// $ausgefuellt = false;
// $sql = $dbs->prepare("SELECT COUNT(*), bedarf FROM ebestellung WHERE id = ?");
// $sql->bind_param("i", $CMS_BENUTZERID);
// if ($sql->execute()) {
// 	$sql->bind_result($test, $bestellbedarf);
// 	if ($sql->fetch()) {
// 		if ($test != 0) {$ausgefuellt = true;}
// 	}
// }
// $sql->close();
//
//
// $bestellende = mktime (23, 59, 59, 5, 6, 2020);
// if ((!$ausgefuellt) && (time() <= $bestellende)) {
// 	$meldung = "<h4>Sammelbestellung für Notebooks oder Tablets</h4>";
// 	if ($CMS_BENUTZERART == 's') {
// 		$meldung .= "<p>Bestelle hier ein Notebook oder Tablet oder melde hier bei finanziellen Schwierigkeiten Gerätebedarf an!</p>";
// 	}
// 	else {
// 		$meldung .= "<p>Bestellen Sie hier ein Notebook oder Tablet oder melden Sie hier bei finanziellen Schwierigkeiten Gerätebedarf an!</p>";
// 	}
// 	$meldung .= "<p><a href=\"Schulhof/Nutzerkonto/Bestellung\" class=\"cms_button\">Zur Bestellung / Leihe ...</a></p>";
// 	echo cms_meldung("warnung", $meldung);
// }

include_once('php/schulhof/seiten/termine/termineausgeben.php');
// Prfüfen, ob ein neues Schuljahr zur Verfügung steht
$jetzt = time();
$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre WHERE beginn <= ? AND ende >= ?");
$sql->bind_param("ii", $jetzt, $jetzt);
if ($sql->execute()) {
	$sql->bind_result($sjid, $sjbez);
	if ($sql->fetch()) {
		if ($sjid != $CMS_BENUTZERSCHULJAHR) {
			$button = "<span class=\"cms_button\" onclick=\"cms_schulhof_nutzerkonto_schuljahr_einstellen($sjid)\">$sjbez</span>";
			echo cms_meldung('warnung', '<p>Es ist nicht das aktuelle Schuljahr ausgewählt. Damit die Gruppen und Ansprechpartner aktuell sind, muss das Schuljahr <b>'.$sjbez.'</b> ausgewählt werden:</p><p>'.$button.'</p>');
		}
	}
}
$sql->close();

/* Direkt nach der Anmeldung letzten Login ausgeben */
if (isset($_SESSION['LETZTENLOGINANZEIGEN'])) {$letzterlogin = $_SESSION['LETZTENLOGINANZEIGEN'];} else {$letzterlogin = '-';}

if ($letzterlogin != '-') {
	if ($letzterlogin != 0) {
		$tag = date ("d", $letzterlogin);
		$monat = date ("m", $letzterlogin);
		$jahr = date ("Y", $letzterlogin);
		$zeit = date ("H:i", $letzterlogin);
		echo "<p>Die letzte Anmeldung erfolgte am <b>$tag. ".cms_monatsnamekomplett($monat)." $jahr um $zeit Uhr</b>. Falsch? <a href=\"Schulhof/Nutzerkonto/Mein_Profil/Identitätsdiebstahl\"><b>Identitätsdiebstahl melden</b> und Passwort ändern!</a></p>";

		$_SESSION['LETZTENLOGINANZEIGEN'] = '-';
	}
}

if (isset($_SESSION['PASSWORTTIMEOUT'])) {
	if ($_SESSION['PASSWORTTIMEOUT'] != '0') {
		echo cms_meldung('info', "<h4>Begrenzte Gültigkeit des Passwortes</h4><p>Das aktuelle Passwort ist nur für begrenzte Zeit gültig. Es sollte unverzüglich geändert werden, um eine künftige Anmeldung zu gewährleisten.</p><p><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Mein_Profil/Passwort_ändern\">Passwort ändern</a></p>");
	}
}

$neuigkeiten = "";

// Prüfen, ob Tagebücher zu füllen sind
$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM (SELECT DISTINCT kurse.id FROM kurse JOIN stufen ON kurse.stufe = stufen.id JOIN unterricht ON unterricht.tkurs = kurse.id WHERE kurse.schuljahr = ? AND stufen.tagebuch = 1 AND tlehrer = ?) AS x");
$sql->bind_param("ii", $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID);
$tbkurs = 0;
if ($sql->execute()) {
	$sql->bind_result($tbkurs);
	$sql->fetch();
}
$sql->close();
if ($tbkurs > 0) {
	$zusatzklasse = "";
	$zusatzlink = "";
	if ($CMS_IMLN) {
		$zusatzlink = " onclick=\"cms_link('Schulhof/Nutzerkonto/Tagebuch')\"";
	}
	else {
		$zusatzklasse = " cms_neuigkeit_ln";
	}
	$neuigkeiten .= "<li class=\"cms_neuigkeit cms_neuigkeit_ganz$zusatzklasse\" id=\"cms_tagebuchneuigkeit\"$zusatzlink><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/tagebuch.png\"></span>";
	$neuigkeiten .= "<span class=\"cms_neuigkeit_inhalt\" id=\"cms_tagebuchneuigkeit_inhalt\"><h4>Tagebucheinträge</h4>";
	if ($CMS_IMLN) {
		$neuigkeiten .= "<p>Offene Einträge suchen ...</p>";
		$neuigkeiten .= cms_ladeicon();
	}
	else {$neuigkeiten .= "<p>Prüfung in diesem Netz nicht möglich.</p>";}
	$neuigkeiten .= "</span></li>";
	if ($CMS_IMLN) {
		$neuigkeiten .= "<script>cms_tagebuchmeldung_laden();</script>";
	}
}

// Prüfen, ob neue Nachrichten vorhanden sind
$db = cms_verbinden('ü');
$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $CMS_DBP_DB.posteingang_$CMS_BENUTZERID WHERE gelesen = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') AND papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') AND empfaenger = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($ungelesen);
	if ($sql->fetch()) {
		if ($ungelesen > 0) {
			$neuigkeiten .= "<li class=\"cms_neuigkeit cms_neuigkeit_ganz\" onclick=\"cms_link('Schulhof/Nutzerkonto/Postfach')\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/nachricht.png\"></span>";
			$neuigkeiten .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Neue Nachrichten</h4>";
			if ($ungelesen == 1) {$neuigkeiten .= "<p><b>1</b> neue Nachricht</p>";}
			else {$neuigkeiten .= "<p><b>$ungelesen</b> neue Nachrichten</p>";}
			$neuigkeiten .= "</span></li>";
		}
	}
}
$sql->close();
cms_trennen($db);

// Notifikationen ausgeben
$notifikationen = "<li class=\"cms_neuigkeit cms_neuigkeit_ganz\" onclick=\"cms_link('Schulhof/Nutzerkonto/Neuigkeiten')\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/neuigkeit.png\"></span>";
$notifikationen .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Neue Inhalte</h4>";
$notifikationenda = false;
$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, IF(art = 'o', 'd', art) as gart FROM notifikationen WHERE person = ? GROUP BY gart");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($not, $art);
	while ($sql->fetch()) {
		if ($not > 0) {
			$notifikationenda = true;
			if ($not == 1) {
				if ($art == 'b') {$notifikationen .= "<p><b>$not</b> Blogeintrag</p>";}
				if ($art == 't') {$notifikationen .= "<p><b>$not</b> Termin</p>";}
				if ($art == 'g') {$notifikationen .= "<p><b>$not</b> Galerie</p>";}
				if ($art == 'a') {$notifikationen .= "<p><b>$not</b> Hausmeisterauftrag</p>";}
				if ($art == 'd') {$notifikationen .= "<p><b>$not</b> Dateiänderung</p>";}
				if ($art == 'o') {$notifikationen .= "<p><b>$not</b> Dateiänderung</p>";}
			}
			else {
				if ($art == 'b') {$notifikationen .= "<p><b>$not</b> Blogeinträge</p>";}
				if ($art == 't') {$notifikationen .= "<p><b>$not</b> Termine</p>";}
				if ($art == 'g') {$notifikationen .= "<p><b>$not</b> Galerien</p>";}
				if ($art == 'a') {$notifikationen .= "<p><b>$not</b> Hausmeisteraufträge</p>";}
				if ($art == 'd') {$notifikationen .= "<p><b>$not</b> Dateiänderungen</p>";}
				if ($art == 'o') {$notifikationen .= "<p><b>$not</b> Dateiänderungen</p>";}
			}
		}
	}
}
$sql->close();
$notifikationen .= "</span></li>";
if ($notifikationenda) {$neuigkeiten .= $notifikationen;}


// Aufgaben ausgeben
$aufgaben = "<li class=\"cms_neuigkeit\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/aufgaben.png\"></span>";
$aufgaben .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Aufgaben</h4>";
$aufgabenda = false;
$sql = "";
if (cms_r("schulhof.techink.geräte.verwalten")) {
	$sql = $dbs->prepare("SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM leihengeraete WHERE statusnr > 0) UNION ALL (SELECT COUNT(*) AS anzahl FROM raeumegeraete WHERE statusnr > 0)) AS x");
  if ($sql->execute()) {
    $sql->bind_result($anzahldefekt);
    $sql->fetch();
  }
	$sql->close();

	$sql = $dbs->prepare("SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM leihengeraete WHERE statusnr = 1 OR statusnr = 5) UNION ALL (SELECT COUNT(*) AS anzahl FROM raeumegeraete WHERE statusnr = 1 OR statusnr = 5)) AS x");
	if ($sql->execute()) {
		$sql->bind_result($auf);
		if ($sql->fetch()) {
			if ($auf > 0) {
				$aufgabenda = true;
				if ($anzahldefekt == 1) {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Geräte_verwalten\"><b>$auf</b>/$anzahldefekt Gerätemeldung</a></p>";}
				else {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Geräte_verwalten\"><b>$auf</b>/$anzahldefekt Gerätemeldungen</a></p>";}
			}
		}
	}
	$sql->close();
}
if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.identitätsdiebstahl")) {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM identitaetsdiebstahl");
  if ($sql->execute()) {
		$sql->bind_result($auf);
		if ($sql->fetch()) {
			if ($auf > 0) {
				$aufgabenda = true;
				if ($auf == 1) {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Identitätsdiebstähle_behandeln\"><b>$auf</b> Identitätsdiebstahl</a></p>";}
				else {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Identitätsdiebstähle_behandeln\"><b>$auf</b> Identitätsdiebstähle</a></p>";}
			}
		}
  }
  $sql->close();
}
if (cms_r("schulhof.verwaltung.nutzerkonten.anlegen")) {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM nutzerregistrierung");
  if ($sql->execute()) {
		$sql->bind_result($auf);
		if ($sql->fetch()) {
			if ($auf > 0) {
				$aufgabenda = true;
				if ($auf == 1) {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Registrierungen\"><b>$auf</b> Registrierungen</a></p>";}
				else {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Registrierungen\"><b>$auf</b> Registrierungen</a></p>";}
			}
		}
  }
  $sql->close();
}
if (cms_r("schulhof.technik.hausmeisteraufträge.[|sehen,markieren]")) {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM hausmeisterauftraege WHERE status != 'e'");
  if ($sql->execute()) {
		$sql->bind_result($auf);
		if ($sql->fetch()) {
			if ($auf > 0) {
				$aufgabenda = true;
				if ($auf == 1) {$aufgaben .= "<p><a href=\"Schulhof/Hausmeister/Aufträge\"><b>$auf</b> Hausmeisterauftrag</a></p>";}
				else {$aufgaben .= "<p><a href=\"Schulhof/Hausmeister/Aufträge\"><b>$auf</b> Hausmeisteraufträge</a></p>";}
			}
		}
  }
  $sql->close();
}
if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM auffaelliges WHERE status = 0");
  if ($sql->execute()) {
		$sql->bind_result($auf);
		if ($sql->fetch()) {
			if ($auf > 0) {
				$aufgabenda = true;
				if ($auf == 1) {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Auffälliges\"><b>$auf</b> Auffälligkeit</a></p>";}
				else {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Auffälliges\"><b>$auf</b> Auffälligkeiten</a></p>";}
			}
		}
  }
  $sql->close();
}
if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.chatmeldungen")) {
	$sql = "";
  foreach($CMS_GRUPPEN as $i => $g) {
    $gk = cms_textzudb($g);
    $sql .= " SELECT COUNT(*) AS anzahl FROM $gk"."chatmeldungen UNION";
  }
  $sql = substr($sql, 0, -5);
  $sql = $dbs->prepare("SELECT SUM(anzahl) AS anzahl FROM ($sql) AS x");
  if ($sql->execute()) {
		$sql->bind_result($auf);
		if ($sql->fetch()) {
			if ($auf > 0) {
				$aufgabenda = true;
				if ($auf == 1) {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Chatmeldungen\"><b>$auf</b> Chatmeldungen</a></p>";}
				else {$aufgaben .= "<p><a href=\"Schulhof/Aufgaben/Chatmeldungen\"><b>$auf</b> Chatmeldungen</a></p>";}
			}
		}
  }
  $sql->close();
}
$aufgaben .= "</span></li>";
if ($aufgabenda) {$neuigkeiten .= $aufgaben;}



// Genehmigungen ausgeben
$genehmigungen = "<li class=\"cms_neuigkeit\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/genehmigungen.png\"></span>";
$genehmigungen .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Genehmigungen</h4>";
$genehmigungenda = false;
$sql = "";
if (cms_r("artikel.genehmigen.blogeinträge")) {$sql .= " UNION (SELECT COUNT(*) AS anzahl, 'öffentlich' AS art FROM blogeintraege WHERE genehmigt = 0)";}
foreach ($CMS_GRUPPEN as $g) {
	$gk = cms_textzudb($g);
	if(cms_r("schulhof.gruppen.$gk.artikel.blogeinträge.genehmigen")) {
		$sql .= " UNION (SELECT COUNT(*) AS anzahl, '$gk' AS art FROM $gk"."blogeintraegeintern WHERE genehmigt = 0)";
	}
}
if (strlen($sql) > 0) {
	$sql = substr($sql, 7);
	$sql = $dbs->prepare("SELECT SUM(anzahl) AS anzahl FROM ($sql) AS x");
	if ($sql->execute()) {
		$sql->bind_result($gen);
		if ($sql->fetch()) {
			if ($gen > 0) {
				$genehmigungenda = true;
				if ($gen == 1) {$genehmigungen .= "<p><a href=\"Schulhof/Aufgaben/Blogeinträge_genehmigen\"><b>$gen</b> Blogeintrag</a></p>";}
				else {$genehmigungen .= "<p><a href=\"Schulhof/Aufgaben/Blogeinträge_genehmigen\"><b>$gen</b> Blogeinträge</a></p>";}
			}
		}
	}
	$sql->close();
}

$sql = "";
if (cms_r("artikel.genehmigen.termine")) {$sql .= " UNION (SELECT COUNT(*) AS anzahl, 'öffentlich' AS art FROM termine WHERE genehmigt = 0)";}
foreach ($CMS_GRUPPEN as $g) {
	$gk = cms_textzudb($g);
	if(cms_r("schulhof.gruppen.$gk.artikel.termine.genehmigen")) {
		$sql .= " UNION (SELECT COUNT(*) AS anzahl, '$gk' AS art FROM $gk"."termineintern WHERE genehmigt = 0)";
	}
}
if (strlen($sql) > 0) {
	$sql = substr($sql, 7);
	$sql = $dbs->prepare("SELECT SUM(anzahl) AS anzahl FROM ($sql) AS x");
	if ($sql->execute()) {
		$sql->bind_result($gen);
		if ($sql->fetch()) {
			if ($gen > 0) {
				$genehmigungenda = true;
				if ($gen == 1) {$genehmigungen .= "<p><a href=\"Schulhof/Aufgaben/Termine_genehmigen\"><b>$gen</b> Termin</a></p>";}
				else {$genehmigungen .= "<p><a href=\"Schulhof/Aufgaben/Termine_genehmigen\"><b>$gen</b> Termine</a></p>";}
			}
		}
	}
	$sql->close();
}

$sql = "";
if (cms_r("artikel.genehmigen.galerien")) {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM galerien WHERE genehmigt = 0");
  if ($sql->execute()) {
    $sql->bind_result($gen);
    if ($sql->fetch()) {
			if ($gen > 0) {
				$genehmigungenda = true;
				if ($gen == 1) {$genehmigungen .= "<p><a href=\"Schulhof/Aufgaben/Galerien_genehmigen\"><b>$gen</b> Galerie</a></p>";}
				else {$genehmigungen .= "<p><a href=\"Schulhof/Aufgaben/Galerien_genehmigen\"><b>$gen</b> Galerien</a></p>";}
			}
    }
  }
  $sql->close();
}
$genehmigungen .= "</span></li>";
if ($genehmigungenda) {$neuigkeiten .= $genehmigungen;}


// Favoriten ausgeben
$favoriten = "<li class=\"cms_neuigkeit\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/favoriten.png\"></span>";
$favoriten .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Favoriten</h4>";
$favoritenda = false;
$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(url, '$CMS_SCHLUESSEL'), AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM favoritseiten WHERE person = ?) AS x ORDER BY id");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($fid, $furl, $fbez);
	while ($sql->fetch()) {
		$favoritenda = true;
		$favoriten .= "<p><a href=\"$furl\">".$fbez."</a></p>";
	}
}
$sql->close();
if ($favoritenda) {$neuigkeiten .= $favoriten;}

if (strlen($neuigkeiten) > 0) {echo "<ul class=\"cms_neuigkeiten\">$neuigkeiten</ul>";}

$todo = "<ul class=\"cms_neuigkeiten\"><li style=\"width: 100% !important\" class=\"cms_neuigkeit\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/todo.png\"></span>";
$todo .= "<span class=\"cms_neuigkeit_inhalt\"><h4>ToDo</h4>";
$todob = "";
$todot = "";
$tododa = false;
$sql = "";
foreach($CMS_GRUPPEN as $g) {
	$gk = cms_textzudb($g);
	$sql .= "(SELECT 'b', IFNULL(AES_DECRYPT(s.bezeichnung, '$CMS_SCHLUESSEL'), 'Schuljahrübergreifend'), '$g', AES_DECRYPT(g.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(a.bezeichnung, '$CMS_SCHLUESSEL') as abez, a.datum FROM {$gk}blogeintraegeintern as a JOIN {$gk}todoartikel as t ON t.blogeintrag = a.id JOIN $gk as g ON g.id = a.gruppe LEFT JOIN schuljahre as s ON s.id = g.schuljahr WHERE person = $CMS_BENUTZERID ORDER BY abez) UNION ";
	$sql .= "(SELECT 't', IFNULL(AES_DECRYPT(s.bezeichnung, '$CMS_SCHLUESSEL'), 'Schuljahrübergreifend'), '$g', AES_DECRYPT(g.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(a.bezeichnung, '$CMS_SCHLUESSEL') as abez, a.beginn FROM {$gk}termineintern as a JOIN {$gk}todoartikel as t ON t.termin = a.id JOIN $gk as g ON g.id = a.gruppe LEFT JOIN schuljahre as s ON s.id = g.schuljahr WHERE person = $CMS_BENUTZERID ORDER BY abez) UNION ";
}
$sql = substr($sql, 0, -6);
$sql = $dbs->prepare($sql);
if ($sql->execute()) {
	$sql->bind_result($a, $sbez, $g, $gbez, $abez, $adat);
	while ($sql->fetch()) {
		$tododa = true;
		$sbez = cms_textzulink($sbez);
		$monatsname = cms_monatsnamekomplett(date('m', $adat));
		$jahr = date('Y', $adat);
		$tag = date('d', $adat);


		if($a == "b") {
			$link = "Schulhof/Gruppen/$sbez/".cms_textzulink($g)."/".cms_textzulink($gbez)."/Blog/$jahr/$monatsname/$tag/".cms_textzulink($abez);
			$todob .= "<p><a href=\"$link\">($g » $gbez) $abez</a></p>";
		}
		if($a == "t") {
			$link = "Schulhof/Gruppen/$sbez/".cms_textzulink($g)."/".cms_textzulink($gbez)."/Termine/$jahr/$monatsname/$tag/".cms_textzulink($abez);
			$todot .= "<p><a href=\"$link\">($g » $gbez) $abez</a></p>";
		}
	}
	$ueberschr = strlen($todob) > 0 && strlen($todot) > 0;
	if(strlen($todob)) {
		if($ueberschr) {
			$todo .= "<h6>Blogeinträge:</h6>";
		}
		$todo .= $todob;
	}
	if(strlen($todot)) {
		if($ueberschr) {
			$todo .= "<h6>Termine:</h6>";
		}
		$todo .= $todot;
	}
}
$sql->close();

$todo .= "</span></li></ul>";

if($tododa) {
	echo $todo;
}

?>
</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">
<?php
if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternpersoenlich.php');
	$vplan = cms_vertretungsplan_extern_persoenlich();
	echo "<h2>Vertretungsplan</h2>";
	if (strlen($vplan) > 0) {
		echo $vplan;
	}
	else {echo "<p class=\"cms_notiz\">Aktuell Keine Vertretungen</p>";}
}
else {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
	$vplan = cms_vertretungsplan_persoenlich($dbs);

	echo "<h2>Mein Tag</h2>";
	if ((strlen($vplan) > 0) || (strlen($vplan) > 0)) {
		echo $vplan;
	}
	else {echo "<p class=\"cms_notiz\">Aktuell Keine Vertretungen</p>";}
}
?>
</div>
</div>


<div class="cms_spalte_2">
<div class="cms_spalte_i">
<h2>Aktuelles</h2>

<ul class="cms_reitermenue">
	<li><span id="cms_reiter_aktuelles_0" class="cms_reiter_aktiv" onclick="cms_reiter('aktuelles', 0,4)">Termine</span></li>
	<li><span id="cms_reiter_aktuelles_1" class="cms_reiter" onclick="cms_reiter('aktuelles', 1,4)">Blogs</span></li>
	<li><span id="cms_reiter_aktuelles_2" class="cms_reiter" onclick="cms_reiter('aktuelles', 2,4)">Gruppen</span></li>
<?php
	$sonderrollencodeverwaltung = cms_sonderrollen_generieren();

	if(strlen($sonderrollencodeverwaltung) > 0) {
		echo "<li><span id=\"cms_reiter_aktuelles_3\" class=\"cms_reiter\" onclick=\"cms_reiter('aktuelles', 3,4)\">Aufgaben</span></li>";
	}
 ?>
	<li><span id="cms_reiter_aktuelles_4" class="cms_reiter" onclick="cms_reiter('aktuelles', 4,4)">Notizen</span></li>
</ul>

<div class="cms_reitermenue_o" id="cms_reiterfenster_aktuelles_0" style="display: block;">
	<div class="cms_reitermenue_i">
		<?php
		$termine = "";
		include_once('php/schulhof/seiten/termine/termineausgeben.php');
		$termine = cms_nachste_termine_ausgeben($_SESSION['BENUTZERUEBERSICHTANZAHL']);
		if (strlen($termine) == 0) {
			echo "<p class=\"cms_notiz\">Aktuell keine Termine</p>";
		}
		else {
			echo "<ul class=\"cms_terminuebersicht\">".$termine."</ul>";
		}
		echo "<p><a class=\"cms_button\" href=\"Schulhof/Termine\">Kalender</a></p>";
		?>
	</div>
</div>


<div class="cms_reitermenue_o" id="cms_reiterfenster_aktuelles_1">
	<div class="cms_reitermenue_i">
		<?php
		$blogeintraege = "";
		include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
		$blogeintraege = cms_letzte_blogeintraege_ausgeben($_SESSION['BENUTZERUEBERSICHTANZAHL'], $dbs, 'liste', $CMS_URLGANZ);
		if (strlen($blogeintraege) == 0) {
			echo "<p class=\"cms_notiz\">Aktuell keine Blogeinträge</p>";
		}
		else {
			echo "<ul class=\"cms_bloguebersicht_liste\">".$blogeintraege."</ul>";
		}
		echo "<p><a class=\"cms_button\" href=\"Schulhof/Blog\">Blog</a></p>";
		?>
	</div>
</div>

<div class="cms_reitermenue_o" id="cms_reiterfenster_aktuelles_2">
	<div class="cms_reitermenue_i">
		<?php
		$code = "";
		include_once('php/schulhof/seiten/gruppen/mitgliedschaftenausgeben.php');
		foreach ($CMS_GRUPPEN as $g) {
			$gruppencode = cms_gruppen_mitgliedschaften_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
			if (strlen($gruppencode) > 0) {$code .= "<h3>$g</h3>".str_replace('<ul>', '<ul class="cms_aktionen_liste">', $gruppencode);}
		}
		echo $code;
		?>
	</div>
</div>

<div class="cms_reitermenue_o" id="cms_reiterfenster_aktuelles_3">
	<div class="cms_reitermenue_i">
		<?php
		if (strlen($sonderrollencodeverwaltung) != 0) {
			$sonderrollencode = "";
			$sonderrollencode .= "<ul class=\"cms_aktionen_liste\">".$sonderrollencodeverwaltung."</ul>";
			echo $sonderrollencode;
		}
		?>
	</div>
</div>

<div class="cms_reitermenue_o" id="cms_reiterfenster_aktuelles_4">
	<div class="cms_reitermenue_i">
		<?php
		if (cms_r("schulhof.nutzerkonto.notizen")) {
			$code = "";
			$notizen = "";
			$sql = $dbs->prepare("SELECT AES_DECRYPT(notizen, '$CMS_SCHLUESSEL') AS notizen FROM nutzerkonten WHERE id = $CMS_BENUTZERID");
			if ($sql->execute()) {
				$sql->bind_result($notizen);
				$sql->fetch();
			}
			$sql->close();

			if (strlen($notizen) == 0) {$zusatzklasse = " cms_notizzettelleer";} else {$zusatzklasse = "";}
			$code .= "<p><textarea id=\"cms_persoenlichenotizen\" class=\"cms_notizzettel$zusatzklasse\">$notizen</textarea></p>";
			$code .= "<p><span class=\"cms_button\" onclick=\"cms_persoenliche_notizen_speichern()\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></p>";
			echo $code;
		}
		?>
	</div>
</div>

</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">


<h2>Mein Konto</h2>
<?php
$sql = "SELECT AES_DECRYPT(gelesen, '$CMS_SCHLUESSEL') AS gelesen, COUNT(gelesen) AS anzahl FROM posteingang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-' GROUP BY gelesen;";
$anzahl['-'] = 0;
$anzahl[1] = 0;
$dbp = cms_verbinden('p');
$sql = $dbp->prepare($sql);
if ($sql->execute()) {
	$sql->bind_result($gelesen, $anzgelesen);
	while ($sql->fetch()) {$anzahl[$gelesen] = $anzgelesen;}
}
$sql->close();
cms_trennen($dbp);
$gesamt = $anzahl['-'] + $anzahl[1];
$meldezahl = "";
if ($anzahl['-'] > 0) {
	$meldezahl = "<span class=\"cms_meldezahl cms_meldezahl_wichtig\"><b>".$anzahl['-']."</b> / $gesamt</span>";
}
else if ($gesamt > 0) {
	$meldezahl = "<span class=\"cms_meldezahl\">$gesamt</span>";
}
$code = "<ul class=\"cms_aktionen_liste\">";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Mein_Profil\">Profildaten</a></li> ";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Postfach\">Postfach $meldezahl</a></li> ";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Favoriten\">Favoriten</a></li> ";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Einstellungen\">Einstellungen</a></li> ";
	// if (($ausgefuellt) && (time() <= $bestellende)) {
	// 	$code .= "<li><a href=\"Schulhof/Nutzerkonto/Bestellung\" class=\"cms_button cms_button_wichtig\">Bestellung / Leihe ändern</a></li>";
	// }
	// if (($ausgefuellt) && (time() > $bestellende) && (($bestellbedarf == '1') || ($bestellbedarf == '2'))) {
	// 	$code .= "<li><a href=\"Schulhof/Nutzerkonto/Bestellung\" class=\"cms_button cms_button_wichtig\">Bestellung / Leihe einsehen</a></li>";
	// }
$code .= "</ul>";
echo $code;
?>
<div id="cms_aktivitaet_out_profil"><div id="cms_aktivitaet_in_profil"></div></div>
<p class="cms_notiz" id="cms_aktivitaet_text_profil">Berechnung läuft ...</p>
<ul class="cms_aktionen_liste">
	<li><span class="cms_button_ja" onclick="cms_timeout_verlaengern()">Verlängern</span></li>
	<li><span class="cms_button_nein" onclick="cms_abmelden_frage();">Abmelden</span></li>
</ul>

<?php
$aktionen = "";
if (cms_r("lehrerzimmer.tagebuch.notfallzustand")) {
	if ($CMS_IMLN) {
		if ($CMS_EINSTELLUNGEN['Tagebuch Notfallzustand'] == '0') {
			$aktionen .= "<li><span class=\"cms_button_wichtig\" onclick=\"cms_notfallzustand_anzeigen('1')\">Notfallzustand ausrufen</span></li> ";
		}
		else {
			$aktionen .= "<li><span class=\"cms_button_wichtig\" onclick=\"cms_notfallzustand_anzeigen('0')\">Notfallzustand aufheben</span></li> ";
		}
	}
	else {
		if ($CMS_EINSTELLUNGEN['Tagebuch Notfallzustand'] == '0') {
			$aktionen .= "<li><span class=\"cms_button_gesichert\">Notfallzustand ausrufen</span></li> ";
		}
		else {
			$aktionen .= "<li><span class=\"cms_button_gesichert\">Notfallzustand aufheben</span></li> ";
		}
	}
}
if (cms_r("artikel.%ARTIKELSTUFEN%.termine.anlegen")) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_termin('".implode('/', $CMS_URL)."')\">+ Neuer öffentlicher Termin</span></li> ";
}
if (cms_r("artikel.%ARTIKELSTUFEN%.blogeinträge.anlegen")) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_blogeintrag('".implode('/', $CMS_URL)."')\">+ Neuer öffentlicher Blogeintrag</span></li> ";
}
if (cms_r("artikel.galerien.anlegen")) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neue_galerie('".implode('/', $CMS_URL)."')\">+ Neue öffentliche Galerie</span></li> ";
}
if (strlen($aktionen) > 0) {
	echo "<h2>Aktionen</h2><ul class=\"cms_aktionen_liste\">$aktionen</ul>";
}

?>

</div>
</div>

<div class="cms_clear"></div>
