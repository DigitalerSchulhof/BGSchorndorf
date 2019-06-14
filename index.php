<!DOCTYPE html>
<?php
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/website/seiten/seitenauswertung.php");
	include_once('php/website/seiten/navigationen/navigationen.php');
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/schulhof/funktionen/config.php");
	include_once("php/schulhof/funktionen/check.php");
	include_once("php/schulhof/funktionen/meldungen.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/lehrerzimmer/seiten/gesicherteteile.php");
	include_once("php/website/funktionen/datenschutz.php");
	include_once("php/website/funktionen/geraet.php");
	include_once("php/schulhof/funktionen/dateisystem.php");
	include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
	include_once("php/schulhof/seiten/website/besucherstatistiken/auswerten.php");

	session_start();

	$CMS_ANGEMELDET = cms_angemeldet();
	$CMS_LINKMUSTER = "[\.\-a-zA-Z0-9äöüßÄÖÜ()_]+";

	if (isset($_SESSION['IMLN'])) {
		if ($_SESSION['IMLN'] == 1) {
			$CMS_IMLN = true;
		}
		else {
			$CMS_IMLN = false;
		}
	}
	else {
		$CMS_IMLN = false;
	}
	$CMS_IMVN = false;
	$CMS_IMNB = false;
	$CMS_VERSION = rand(0,1000000);
	//$CMS_VERSION = "0.5.2";
	$TITELBILDERJS = "";
	$CMS_STUNDENDAUER = 45;

	if (isset($_SESSION['GERAET'])) {$CMS_GERAET = $_SESSION['GERAET'];}
	else {
		$CMS_GERAET = cms_welches_geraet();
		if (isset($_SESSION['DSGVO_COOKIESAKZEPTIERT'])) {
			if ($_SESSION['DSGVO_COOKIESAKZEPTIERT']) {$_SESSION['GERAET'] = $CMS_GERAET;}
		}
	}

	// Welche Seite ist gesucht?
	// Variablen laden
	if (isset($_GET['URL'])) {
		$CMS_URL = explode('/', $_GET['URL']);
		$CMS_URLGANZ = implode('/', $CMS_URL);
		if (($CMS_URL[0] != "Website") && ($CMS_URL[0] != "Schulhof") && ($CMS_URL[0] != "Problembehebung") && ($CMS_URL[0] != "Intern")) {
			$CMS_URL = array();
			$CMS_URL[0] = "Website";
		}
		else if (($CMS_URL[0] == "Schulhof") && (!isset($CMS_URL[1]))) {
			$CMS_URL = array();
			$CMS_URL[0] = "Schulhof";
			$CMS_URL[1] = "Nutzerkonto";
		}
		else if (preg_match("/^Website(\/(Seiten|Bearbeiten)(\/(Aktuell|Neu|Alt)){0,1}){0,1}$/", $CMS_URLGANZ)) {
			$CMS_URL = array();
			$CMS_URL[0] = "Website";
		}

		if ((!$CMS_ANGEMELDET) && ($CMS_URL[0] == "Schulhof") && (($CMS_URL[1] != "Anmeldung") && ($CMS_URL[1] != "Passwort_vergessen")))   {
			$CMS_URL = array();
		  $CMS_URL[0] = "Schulhof";
		  $CMS_URL[1] = "Anmeldung";
		}
		if ((!$CMS_ANGEMELDET) && ($CMS_URL[0] == "Website")) {
			if (isset($CMS_URL[1])) {
				if (($CMS_URL[1] != "Seiten") && ($CMS_URL[1] != 'Blog') && ($CMS_URL[1] != 'Galerien') && ($CMS_URL[1] != 'Termine') && ($CMS_URL[1] != 'Voranmeldung') &&
				    ($CMS_URL[1] != 'Ferien') && ($CMS_URL[1] != 'Datenschutz') && ($CMS_URL[1] != 'Impressum') && ($CMS_URL[1] != 'Feedback')) {
					$CMS_URL = array();
					$CMS_URL[0] = "Website";
				}
				else if (isset($CMS_URL[2])) {
					if (($CMS_URL[1] == "Seiten") && ($CMS_URL[2] != 'Aktuell')) {
						$CMS_URL = array();
						$CMS_URL[0] = "Website";
					}
				}
			}
		}
	}
	else {
		$CMS_URL = array();
		$CMS_URL[0] = 'Website';
	}
	$CMS_URLGANZ = implode('/', $CMS_URL);

	$CMS_JAHR = date("Y");

	// Prüfen, ob ein Nutzer angemeldet ist
	if ($CMS_ANGEMELDET) {

		// Nutzerdaten laden
		$CMS_BENUTZERNAME = $_SESSION['BENUTZERNAME'];
		$CMS_SESSIONID = $_SESSION['SESSIONID'];
		$CMS_SESSIONTIMEOUT = $_SESSION['SESSIONTIMEOUT'];
		$CMS_SESSIONAKTIVITAET = $_SESSION['SESSIONAKTIVITAET'];
		$CMS_BENUTZERUEBERSICHTANZAHL = $_SESSION['BENUTZERUEBERSICHTANZAHL'];
		$CMS_BENUTZERTITEL = $_SESSION['BENUTZERTITEL'];
		$CMS_BENUTZERVORNAME = $_SESSION['BENUTZERVORNAME'];
		$CMS_BENUTZERNACHNAME = $_SESSION['BENUTZERNACHNAME'];
		$CMS_BENUTZERID = $_SESSION['BENUTZERID'];
		$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
		$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];


		// Timeout verlängern, da der Nutzer aktiv war
		if ($_SESSION['SESSIONTIMEOUT'] > time()) {
			cms_timeout_verlaengern();
		}

		// Rechte des Benutzers laden
		$CMS_RECHTE = cms_rechte_laden();
	}

	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="descriptopn" content="Das Burg-Gymnasium ist ein allgemeinbildendes Gymnasium im Herzen der Stadt Schorndorf.">
	<meta name="format-detection" content="address=no">
	<meta name="format-detection" content="date=no">
	<meta name="format-detection" content="email=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="image/png" href="res/logos/bglogo.png" rel="shortcut icon">
	<title><?php echo $CMS_SCHULE." ".$CMS_ORT;?></title>

	<?php echo "<base href=\"$CMS_BASE\">";

		// <!-- Einbindung der Stylesheets -->
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/fonts.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/seite.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/navigationen.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/buttons.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/links.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/text.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/spezialfaelle.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/tabellen.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/formulare.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/meldungen.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/blende.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/buchung.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/reiter.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/termine.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/kalender.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/blogeintraege.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/icons.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/dateisystem.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/gruppen.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/hinweise.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/sitemap.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/seitenwahl.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/bearbeiten.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/website.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/stundenplan.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/vertretungsplan.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/neuigkeiten.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/responsive.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/summernote.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/voranmeldung.css?v=$CMS_VERSION\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/galerien.css?v=$CMS_VERSION\">";

    //<!-- Einbindung der JavaScripts -->
    echo "<script src=\"js/allgemein/anfragen.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/generieren.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/reiter.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/zeigen.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/link.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/toggle.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/blende.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/check.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/download.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/allgemein/suche.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/schulhof/nutzerkonto/anmelden.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/website/zugehoerig.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/website/titelbilder.js?v=$CMS_VERSION\"></script>";
		echo "<script src=\"js/website/voranmeldung.js?v=$CMS_VERSION\"></script>";
		echo "<script src=\"js/website/feedback.js?v=$CMS_VERSION\"></script>";

		// Skripte, die nur für angemeldete notwendig sind
		if ($CMS_ANGEMELDET) {
			$code = "";
			$code .= "<script src=\"js/schulhof/nutzerkonto/profildaten.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/nutzerkonto/postfach.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/kalender.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/personen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schulanmeldung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/personensuche.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/rollen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schuljahre.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/raeume.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/leihgeraete.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/zulaessig.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schuldetails.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/faecher.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/notifikationen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/geraete.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/blockierung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/buchung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/allgemeineeinstellungen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schuldetails.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schulnetze.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/stundenplanung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/vertretungsplanung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/gruppen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/ferien.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/zuordnung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/termine.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/blogeintraege.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/seiten.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/hauptnavigationen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/lehrerzimmer/lehrernetz.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/dateien.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/gruppen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/downloads.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/beschluesse.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/listen.js?v=$CMS_VERSION\"></script>";

			$code .= "<script src=\"js/website/bearbeiten.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/editor.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/downloads.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/boxen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/eventuebersicht.js?v=$CMS_VERSION\"></script>";

			$code .= "<script src=\"js/summernote/jquery.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/summernote/summernote.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/chartJS/moment.js?v=$CMS_VERSION\"></script>";	//B
			$code .= "<script src=\"js/chartJS/chart.js?v=$CMS_VERSION\"></script>";	//B
			$code .= "<script src=\"js/schulhof/besucherstatistik.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/feedback.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/galerien.js?v=$CMS_VERSION\"></script>";
			echo $code;
		}
		?>

    <script><?php
			$CMS_ONLOAD_EXTERN_EVENTS = "";
			echo "var CMS_DOMAIN = '".$CMS_DOMAIN."';\n";
	    if ($CMS_ANGEMELDET) {
				if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {
					echo "var CMS_LN_DA = '".$CMS_LN_DA."';\n";
					if (isset($_SESSION['IMLN'])) {
						if ($_SESSION['IMLN'] == 1) {
			        echo "var CMS_IMLN = true;\n";
						}
						else {
			        echo "var CMS_IMLN = false;\n";
						}
					}
					else {
						echo "var CMS_IMLN = false;\n";
					}
					echo "var CMS_NETZCHECK = setInterval('cms_netzcheck()', 3000);\n";
				}
        echo "var CMS_BENUTZERNAME = '".$_SESSION['BENUTZERNAME']."';\n";
		    echo "var CMS_SCHULSTUNDENDAUER = $CMS_STUNDENDAUER;\n";
        $iv = substr($CMS_SESSIONID, 0, 16);
				$nutzerid = openssl_encrypt ($CMS_BENUTZERID, 'aes128', $iv, 0, $iv);
				echo "var CMS_BENUTZERID = '".$nutzerid."';\n";
        echo "var CMS_SESSIONID = '".$_SESSION['SESSIONID']."';\n";
        echo "var CMS_SESSIONTIMEOUT = ".$_SESSION['SESSIONTIMEOUT'].";\n";
        echo "var CMS_SESSIONAKTIVITAET = ".$_SESSION['SESSIONAKTIVITAET'].";\n";
        echo "var CMS_BENUTZERTITEL = '".$_SESSION['BENUTZERTITEL']."';\n";
        echo "var CMS_BENUTZERVORNAME = '".$_SESSION['BENUTZERVORNAME']."';\n";
        echo "var CMS_BENUTZERNACHNAME = '".$_SESSION['BENUTZERNACHNAME']."';\n";
        echo "var CMS_BENUTZERART = '".$_SESSION['BENUTZERART']."';\n";
        echo "var CMS_MAX_DATEI = ".$CMS_MAX_DATEI.";\n";
        echo "var CMS_BEARBEITUNGSART = window.setInterval('cms_timeout_aktualisieren()', 2000);\n";
				$CMS_ONLOAD_EVENTS = "cms_timeout_aktualisieren();";
        if ($CMS_IMLN) {
	        $dbsschluessel = openssl_encrypt ($CMS_SCHLUESSEL, 'aes128', $iv, 0, $iv);
					$dbshost = openssl_encrypt ($CMS_DBS_HOST, 'aes128', $iv, 0, $iv);
					$dbsuser = openssl_encrypt ($CMS_DBS_USER, 'aes128', $iv, 0, $iv);
					$dbspass = openssl_encrypt ($CMS_DBS_PASS, 'aes128', $iv, 0, $iv);
					$dbsdb = openssl_encrypt ($CMS_DBS_DB, 'aes128', $iv, 0, $iv);
					echo "var CMS_DBS_HOST = '".$dbshost."';\n";
					echo "var CMS_DBS_USER = '".$dbsuser."';\n";
					echo "var CMS_DBS_PASS = '".$dbspass."';\n";
					echo "var CMS_DBS_DB = '".$dbsdb."';\n";
					echo "var CMS_DBS_SCHLUESSEL = '".$dbsschluessel."';\n";
					echo "CMS_IMLN = true;\n";
        }
				echo "var CMS_GRUPPEN = ['Gremien','Fachschaften','Klassen','Kurse','Stufen','Arbeitsgemeinschaften','Arbeitskreise','Fahrten','Wettbewerbe','Ereignisse','Sonstige Gruppen'];";
	    }
    ?>
    </script>
</head>


<?php
	echo "<body class=\"cms_optimierung_".$CMS_GERAET."\">";
	echo "<p style=\"display: none;\">Das Burg-Gymnasium ist ein allgemeinbildendes Gymnasium im Herzen der Stadt Schorndorf. Wir sind Mitglied des Netzwerks der UNESCO-Projektschulen. Neben den Sprachlichen Profilen Russisch und Französisch als dritte Fremdsprachen bieten wir im naturwissenschaftlichenbereich die Fächer NWT (Naturwissenschaft und Technik) und IMP (Informatik Mathematik Pysik) an.</p>";
?>


	<?php
		$dbs = cms_verbinden('s');
		// Startseite laden
		if ($CMS_URLGANZ == "Website") {
			$CMS_SEITENDETAILS = cms_startseitendetails_erzeugen($dbs);
			$seitenpfad = cms_seitenpfad_id_erzeugen($dbs, $CMS_SEITENDETAILS['id']);
			$CMS_URL = cms_seitenerweiterung_anfuegen(cms_seitenpfadlink_erzeugen($seitenpfad));
		}
		else if (preg_match("/^Website\/(Seiten|Bearbeiten)\/(Aktuell|Neu|Alt)(\/$CMS_LINKMUSTER)*$/", $CMS_URLGANZ)) {
			$CMS_SEITENDETAILS = cms_seitendetails_erzeugen($dbs, array_slice($CMS_URL,3));
			// Falls auf dieser Seite eine Weiterleitung besteht
			if (isset($CMS_SEITENDETAILS['art'])) {
				if ($CMS_SEITENDETAILS) {
					if (($CMS_SEITENDETAILS['art'] == 'm') && ($CMS_EINSTELLUNGEN['Menüseiten weiterleiten'] == 1) && ($CMS_URL[1] != "Bearbeiten")) {
						$weitergeleitet = false;
						$sql = "SELECT * FROM seiten WHERE zuordnung = '".$CMS_SEITENDETAILS['id']."' ORDER BY position ASC";
						if ($anfrage = $dbs->query($sql)) {
							while ((!$weitergeleitet) && ($daten = $anfrage->fetch_assoc())) {
								if ($daten['art'] != 'm') {
									$weitergeleitet = true;
									$CMS_SEITENDETAILS = $daten;
									$seitenpfad = cms_seitenpfad_id_erzeugen($dbs, $daten['id']);
									$CMS_URL = cms_seitenerweiterung_anfuegen(cms_seitenpfadlink_erzeugen($seitenpfad));
								}
							}
							$anfrage->free();
						}
					}
					else if (($CMS_SEITENDETAILS['art'] == 't') || ($CMS_SEITENDETAILS['art'] == 'b') || ($CMS_SEITENDETAILS['art'] == 'g')) {
						$CMS_URL = array();
						$CMS_URL[0] = "Website";
						if ($CMS_SEITENDETAILS['art'] == 't') {$CMS_URL[1] = 'Termine';}
						if ($CMS_SEITENDETAILS['art'] == 'b') {$CMS_URL[1] = 'Blog';}
						if ($CMS_SEITENDETAILS['art'] == 'g') {$CMS_URL[1] = 'Galerien';}
						$jetzt = time();
						$CMS_URL[2] = date('Y', $jetzt);
						$CMS_URL[3] = cms_monatsnamekomplett(date('n', $jetzt));
					}
					$CMS_SEITE = $CMS_SEITENDETAILS['bezeichnung'];
				}
			}
		}

		if (preg_match("/^Website\/(Termine|Blog|Galerien).*$/", $CMS_URLGANZ)) {
			if ($CMS_URL[1] == 'Termine') {$art = 't';}
			else if ($CMS_URL[1] == 'Blog') {$art = 'b';}
			else if ($CMS_URL[1] == 'Galerien') {$art = 'g';}
			$sql = "SELECT * FROM seiten WHERE art = '$art' ORDER BY position ASC";
			if ($anfrage = $dbs->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()) {
					$CMS_SEITENDETAILS = $daten;
					$seitenpfad = cms_seitenpfad_id_erzeugen($dbs, $daten['id']);
					//$CMS_URL = cms_seitenerweiterung_anfuegen(cms_seitenpfadlink_erzeugen($seitenpfad));
					//print_r($CMS_URL);
				}
				$anfrage->free();
			}
			if (!isset($CMS_URL[2])) {$CMS_URL[2] = date('Y');}
		}
		$CMS_URLGANZ = implode('/', $CMS_URL);

		include_once("php/allgemein/seiten/kopfzeile.php");
		echo '<div id="cms_platzhalter_bild"></div>';
		if ($CMS_URL[0] == "Website") {
			$bildercode = "";
			$code = "";

			// Alle Titelbilder
			$verzeichnis = scandir('dateien/titelbilder');
			array_splice($verzeichnis, 0, 2);
			$bilder = array();
			foreach ($verzeichnis as $b) {
				if ((is_file('dateien/titelbilder/'.$b)) && (getimagesize('dateien/titelbilder/'.$b))) {
					array_push($bilder, $b);
				}
			}
			$wahlknoepfe = "";
			if (count($bilder) > 1) {
				$TITELBILDERJS = "cms_titelbilder_starten();";
				$bildercode .= "<li style=\"opacity: 1;\" id=\"cms_hauptbilder_0\"><img src=\"dateien/titelbilder/".$bilder[0]."\"></li>";
				$wahlknoepfe .= "<span id=\"cms_hauptbilder_knopf_0\" class=\"cms_titelbild_knopf_aktiv\" onclick=\"cms_titelbild_zeigen('0')\"></span> ";
			}
			for ($i=1; $i<count($bilder); $i++) {
				$bildercode .= "<li style=\"opacity: 0;\" id=\"cms_hauptbilder_$i\"><img src=\"dateien/titelbilder/".$bilder[$i]."\"></li>";
				$wahlknoepfe .= "<span id=\"cms_hauptbilder_knopf_$i\" class=\"cms_titelbild_knopf\" onclick=\"cms_titelbild_zeigen('$i')\"></span> ";
			}
			if (strlen($bildercode) > 0) {
				$code .= '<div id="cms_hauptbild_o">';
					$code .= '<ul id="cms_hauptbilder_m">';
					$code .= $bildercode;
					$code .= '</ul>';
					$code .= "<div class=\"cms_clear\"></div>";
					$code .= "<input type=\"hidden\" id=\"cms_titelbilder_anzahl\" id=\"cms_titelbilder_azahl\" value=\"".(count($bilder))."\">";
					$code .= "<input type=\"hidden\" id=\"cms_titelbilder_angezeigt\" id=\"cms_titelbilder_angezeigt\" value=\"0\">";
					$code .= '<span class="cms_hauptbilder_voriges" onclick="cms_titelbild_voriges()"></span><span class="cms_hauptbilder_naechstes" onclick="cms_titelbild_naechstes()"></span>';
					$code .= "<p class=\"cms_titelbilder_wahl\">$wahlknoepfe</p>";
				$code .= '</div>';
			}
			echo $code;
		}

		if (($CMS_ANGEMELDET) && ($CMS_URL[0] == 'Website')) {
			if ($CMS_RECHTE['Website']['Inhalte anlegen'] || $CMS_RECHTE['Website']['Inhalte bearbeiten'] || $CMS_RECHTE['Website']['Inhalte löschen']) {
				include_once("php/website/seiten/bearbeiten.php");
			}
		}
	?>

	<div id="cms_hauptteil_o">
	<div id="cms_hauptteil_m">
		<div id="cms_hauptteil_i">
			<div id="cms_debug"></div>
			<?php
			include_once("php/allgemein/seiten/seitensteuerung.php");
			?>
		</div>
	</div>
	</div>

	<?php
		include_once("php/allgemein/seiten/fusszeile.php");
		include_once("php/allgemein/seiten/blende.php");
		cms_trennen($dbs);
	?>

	<div id="cms_javascript">
		<?php
		if ($CMS_ANGEMELDET) {
			echo "<script type=\"text/javascript\">";
				echo "window.onload = function () {".$TITELBILDERJS.$CMS_ONLOAD_EVENTS."\n";
				if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {echo "cms_netzcheck();\n";}
				echo "};";
			echo "</script>";
		}
		else if (($CMS_URL[0] == 'Website') || ($CMS_URL[0] == "Intern")) {
			echo "<script type=\"text/javascript\">";
				echo "window.onload = function () {".$TITELBILDERJS.$CMS_ONLOAD_EXTERN_EVENTS."};";
			echo "</script>";
		}
		cms_erfasse_click();

		?>
	</div>

</body>
</html>