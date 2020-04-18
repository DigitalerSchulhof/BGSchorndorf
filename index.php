<?php
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/website/seiten/seitenauswertung.php");
	include_once('php/website/seiten/navigationen/navigationen.php');
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/schulhof/funktionen/config.php");
	include_once("php/schulhof/funktionen/check.php");
	include_once("php/schulhof/funktionen/meldungen.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/website/funktionen/datenschutz.php");
	include_once("php/website/funktionen/geraet.php");
	include_once("php/schulhof/funktionen/dateisystem.php");
	include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
	include_once("php/schulhof/seiten/website/besucherstatistiken/auswerten.php");
	include_once("php/allgemein/funktionen/captcha.php");
	include_once("php/allgemein/funktionen/rechte/rechte.php");

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
	//$CMS_VERSION = rand(0,1000000);
	$CMS_VERSION = "0.5.8";
	$CMS_WECHSELBILDER = 0;
	$CMS_DIASHOWZEIT = 7000;

	if (isset($_SESSION['GERAET'])) {$CMS_GERAET = $_SESSION['GERAET'];}
	else {
		$CMS_GERAET = cms_welches_geraet();
		if (isset($_SESSION['DSGVO_FENSTERWEG'])) {
			if ($_SESSION['DSGVO_FENSTERWEG']) {$_SESSION['GERAET'] = $CMS_GERAET;}
		}
	}
	$dbs = cms_verbinden('s');

	// Welche Seite ist gesucht?
	// Variablen laden
	if (isset($_GET['URL'])) {
		$CMS_URL = explode('/', $_GET['URL']);
		$CMS_URLGANZ = implode('/', $CMS_URL);

		// Weiterleitungen
		$sql = "SELECT AES_DECRYPT(zu, '$CMS_SCHLUESSEL') FROM weiterleiten WHERE von = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
		$sql = $dbs->prepare($sql);
		$r = "/$CMS_URLGANZ";
		$sql->bind_param("s", $r);
		$sql->bind_result($ziel);
		if($sql->execute() && $sql->fetch()) {
			header("Location: $ziel");
			die;
		}

		// Fallback bei ungültigen URLs
		if (($CMS_URL[0] != "Website") && ($CMS_URL[0] != "Schulhof") && ($CMS_URL[0] != "Problembehebung") && ($CMS_URL[0] != "Intern") && ($CMS_URL[0] != "App")) {
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

		// Schulhof Zugriff verhindern
		if ((!$CMS_ANGEMELDET) && ($CMS_URL[0] == "Schulhof") && (($CMS_URL[1] != "Anmeldung") && ($CMS_URL[1] != "Passwort_vergessen") && ($CMS_URL[1] != "Registrieren")))   {
			$CMS_URL = array();
		  $CMS_URL[0] = "Schulhof";
		  $CMS_URL[1] = "Anmeldung";
		}
		// App-Zugriff verhindern
		if ((!$CMS_ANGEMELDET) && ($CMS_URL[0] == "App"))   {
			$CMS_URL = array();
		  $CMS_URL[0] = "App";
		}
		// Ungültige Website URL Anfänge
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
		$CMS_BENUTZERFEHLER = !cms_check_sessionvars();

		// Timeout verlängern, da der Nutzer aktiv war
		if ($_SESSION['SESSIONTIMEOUT'] > time()) {
			cms_timeout_verlaengern();
		}

		// Rechte des Benutzers laden

	}

	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

	$CMS_SEITENTITEL = "";
	if (count($CMS_URL) > 0) {
		if ($CMS_URL[0] == "Website") {
			if (count($CMS_URL) > 1) {
				if ($CMS_URL[1] == "Termine") {$CMS_SEITENTITEL = "Termine";}
				else if ($CMS_URL[1] == "Ferien") {$CMS_SEITENTITEL = "Ferien";}
				else if ($CMS_URL[1] == "Blog") {$CMS_SEITENTITEL = "Blog";}
				else if ($CMS_URL[1] == "Galerien") {$CMS_SEITENTITEL = "Galerien";}
				else {
					if (count($CMS_URL) > 3) {
						$CMS_SEITENTITEL = cms_linkzutext($CMS_URL[count($CMS_URL)-1]);
					}
					else {
						$CMS_SEITENTITEL = "Website";
					}
				}
			}
			else {$CMS_SEITENTITEL = "Startseite";}
		}
		else {
			$CMS_SEITENTITEL = "Schulhof";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="format-detection" content="address=no">
	<meta name="format-detection" content="date=no">
	<meta name="format-detection" content="email=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo "<link type=\"image/png\" href=\"res/logos/$CMS_FAVICON\" rel=\"shortcut icon\">";?>
	<title><?php echo $CMS_SCHULE." ".$CMS_ORT." • ".$CMS_SEITENTITEL;?></title>

	<?php echo "<base href=\"$CMS_BASE\">";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/hell.css?v=$CMS_VERSION\">";
		if ($CMS_EINSTELLUNGEN['Website Darkmode'] == 1) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/dunkel.css?v=$CMS_VERSION\">";
		}

    //<!-- Einbindung der JavaScripts -->
		echo "<script src=\"js/jquery.js?v=$CMS_VERSION\"></script>";

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
    echo "<script src=\"js/allgemein/contextmenue.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/schulhof/nutzerkonto/anmelden.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/website/zugehoerig.js?v=$CMS_VERSION\"></script>";
    echo "<script src=\"js/website/wechselbilder.js?v=$CMS_VERSION\"></script>";
		echo "<script src=\"js/website/voranmeldung.js?v=$CMS_VERSION\"></script>";
		echo "<script src=\"js/website/feedback.js?v=$CMS_VERSION\"></script>";
		echo "<script src=\"js/website/kontaktformular.js?v=$CMS_VERSION\"></script>";

		echo "<script src=\"js/website/newsletter.js?v=$CMS_VERSION\"></script>";

		// Skripte, die nur für Angemeldete notwendig sind
		if ($CMS_ANGEMELDET) {
			$code = "";
			$code .= "<script src=\"js/summernote/summernote.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/chartJS/moment.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/chartJS/chart.js?v=$CMS_VERSION\"></script>";

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
			$code .= "<script src=\"js/schulhof/verwaltung/hausmeister.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/dauerbrenner.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/pinnwaende.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/zeitraeume.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/profile.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schienen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/schuljahrfabrik.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/import.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/style.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/zuordnung.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/termine.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/blogeintraege.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/seiten.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/hauptnavigationen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/dateien.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/gruppen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/downloads.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/beschluesse.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/listen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/lehrerzimmer/lehrernetz.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/lehrerzimmer/tagebuch.js?v=$CMS_VERSION\"></script>";

			$code .= "<script src=\"js/website/bearbeiten.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/editor.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/downloads.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/boxen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/eventuebersicht.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/website/diashow.js?v=$CMS_VERSION\"></script>";

			$code .= "<script src=\"js/schulhof/besucherstatistik.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/feedback.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/galerien.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/auffaelliges.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/newsletter.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/website/auszeichnungen.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/rechtebaum.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/bedingte_rechte.js?v=$CMS_VERSION\"></script>";
			$code .= "<script src=\"js/schulhof/verwaltung/speicherplatz.js?v=$CMS_VERSION\"></script>";

			$code .= "<script src=\"js/schulhof/notentabelle.js?v=$CMS_VERSION\"></script>";
			echo $code;
			$code = "";
		}
		?>

    <script><?php
			$CMS_ONLOAD_EXTERN_EVENTS = "";
			echo "var CMS_DOMAIN = '".$CMS_DOMAIN."';\n";
			echo "var CMS_DIASHOWZEIT = $CMS_DIASHOWZEIT;\n";
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
					echo "var CMS_NETZCHECK = setInterval('cms_netzcheck()', 30000);\n";
				}
        echo "var CMS_BENUTZERNAME = '".$_SESSION['BENUTZERNAME']."';\n";
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
				if ($CMS_URL[0] != 'App') {
					echo "var CMS_BEARBEITUNGSART = window.setInterval('cms_timeout_aktualisieren(1)', 30000);\n";
					$CMS_ONLOAD_EVENTS = "cms_timeout_aktualisieren(1);";
				}
				else {
					echo "var CMS_BEARBEITUNGSART = window.setInterval('cms_timeout_aktualisieren(2)', 30000);\n";
					$CMS_ONLOAD_EVENTS = "cms_timeout_aktualisieren(2);";
				}
        if ($CMS_IMLN) {
					echo "CMS_IMLN = true;\n";
        }
				echo "var CMS_GRUPPEN = ['Gremien','Fachschaften','Klassen','Kurse','Stufen','Arbeitsgemeinschaften','Arbeitskreise','Fahrten','Wettbewerbe','Ereignisse','Sonstige Gruppen'];";
	    }

			// Eigene jQuery-Funktionen
    ?>
		jQuery.fn.extend({
			setClass: function(c, v) {
				return v ? $(this).addClass(c) : $(this).removeClass(c);
			}
		});
    </script>
</head>


<?php
	$seitenzusatzklasse = " cms_seite_normal";
	if ($CMS_URL[0] == 'App') {
		$CMS_GERAET = "H";
		$seitenzusatzklasse = " cms_seite_app";
	}

	echo "<body class=\"cms_optimierung_".$CMS_GERAET.$seitenzusatzklasse."\">";
?>


	<?php
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
						$sql = $dbs->prepare("SELECT * FROM seiten WHERE zuordnung = ? ORDER BY position ASC");
						$sql->bind_param("s", $CMS_SEITENDETAILS['id']);
						if ($sql->execute()) {
							$sql->bind_result($sid, $sart, $sposition, $szuordnung, $sbezeichnung, $sbeschreibung, $ssidebar, $sstatus, $sstyles, $sklassen, $sidvon, $sidzeit);
							while ((!$weitergeleitet) && ($sql->fetch())) {
								if ($sart != 'm') {
									$weitergeleitet = true;
									$CMS_SEITENDETAILS['id'] = $sid;
									$CMS_SEITENDETAILS['art'] = $sart;
									$CMS_SEITENDETAILS['position'] = $sposition;
									$CMS_SEITENDETAILS['zuordnung'] = $szuordnung;
									$CMS_SEITENDETAILS['bezeichnung'] = $sbezeichnung;
									$CMS_SEITENDETAILS['beschreibung'] = $sbeschreibung;
									$CMS_SEITENDETAILS['sidebar'] = $ssidebar;
									$CMS_SEITENDETAILS['status'] = $sstatus;
									$CMS_SEITENDETAILS['styles'] = $sstyles;
									$CMS_SEITENDETAILS['klassen'] = $sklassen;
									$CMS_SEITENDETAILS['idvon'] = $sidvon;
									$CMS_SEITENDETAILS['idzeit'] = $sidzeit;
									$seitenpfad = cms_seitenpfad_id_erzeugen($dbs, $sid);
									$CMS_URL = cms_seitenerweiterung_anfuegen(cms_seitenpfadlink_erzeugen($seitenpfad));
								}
							}
						}
						$sql->close();
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
			$sql = $dbs->prepare("SELECT * FROM seiten WHERE art = ? ORDER BY position ASC");
			$sql->bind_param("s", $art);
			if ($sql->execute()) {
				$sql->bind_result($sid, $sart, $sposition, $szuordnung, $sbezeichnung, $sbeschreibung, $ssidebar, $sstatus, $sstyles, $sklassen, $sidvon, $sidzeit);
				if ($sql->fetch()) {
					$CMS_SEITENDETAILS['id'] = $sid;
					$CMS_SEITENDETAILS['art'] = $sart;
					$CMS_SEITENDETAILS['position'] = $sposition;
					$CMS_SEITENDETAILS['zuordnung'] = $szuordnung;
					$CMS_SEITENDETAILS['bezeichnung'] = $sbezeichnung;
					$CMS_SEITENDETAILS['beschreibung'] = $sbeschreibung;
					$CMS_SEITENDETAILS['sidebar'] = $ssidebar;
					$CMS_SEITENDETAILS['status'] = $sstatus;
					$CMS_SEITENDETAILS['styles'] = $sstyles;
					$CMS_SEITENDETAILS['klassen'] = $sklassen;
					$CMS_SEITENDETAILS['idvon'] = $sidvon;
					$CMS_SEITENDETAILS['idzeit'] = $sidzeit;
					$seitenpfad = cms_seitenpfad_id_erzeugen($dbs, $sid);
				}
			}
			$sql->close();
			if (!isset($CMS_URL[2])) {$CMS_URL[2] = date('Y');}
		}
		$CMS_URLGANZ = implode('/', $CMS_URL);

		include_once("php/allgemein/seiten/kopfzeile.php");

		if ($CMS_URL[0] != 'App') {echo '<div id="cms_platzhalter_bild"></div>';}

		if ($CMS_URL[0] == "Website") {
			$code = "";
			// Alle Titelbilder
			$verzeichnis = scandir('dateien/titelbilder');
			array_splice($verzeichnis, 0, 2);
			$inhalte = array();
			foreach ($verzeichnis as $b) {
				if ((is_file('dateien/titelbilder/'.$b)) && (getimagesize('dateien/titelbilder/'.$b))) {
					array_push($inhalte, "<img src=\"dateien/titelbilder/".$b."\">");
				}
			}

			$code .= cms_wechselbilder_generieren($inhalte, "cms_hauptbild_");
			echo $code;
		}

		if (($CMS_ANGEMELDET) && ($CMS_URL[0] == 'Website')) {
			if (cms_r("website.elemente.%ELEMENTE%.*")) {
				include_once("php/website/seiten/bearbeiten.php");
			}
		}

	$CMS_AKTIONSSCHICHT = "";
	$CMS_AKTIONSSCHICHTINHALT = "";
	?>

	<div id="cms_hauptteil_o">
	<div id="cms_hauptteil_m">
		<div id="cms_hauptteil_i">
			<div id="cms_debug"></div>
			<?php
			if ($CMS_ANGEMELDET) {
				// NOTFALLZUSTAND PRÜFEN
				if (($CMS_EINSTELLUNGEN['Tagebuch Notfallzustand']) && (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 's'))) {
					$code = "<div class=\"cms_spalte_i\"><div class=\"cms_neuigkeit_notfall\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/alarm.png\"></span>";
					$code .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Notfallzustand</h4><p>Bitte <b>bewahren Sie Ruhe</b> und verlassen Sie <b>umgehend</b> das Gebäude!!</p>";
					if ($CMS_BENUTZERART == 'l') {
						$personen = "";
						$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM notfallzustand JOIN personen ON notfallzustand.schueler = personen.id WHERE notfallzustand.lehrer = ?) AS x ORDER BY nachname, vorname, titel");
						$sql->bind_param("i", $CMS_BENUTZERID);
						if ($sql->execute()) {
							$sql->bind_result($vor, $nach, $tit);
							while ($sql->fetch()) {
								$personen .= "<li>".cms_generiere_anzeigename($vor, $nach, $tit)."</li>";
							}
						}
						$sql->close();

						if (strlen($personen) > 0) {
							$code .= "<p>Bitte stellen Sie die Anwesenheit der folgenden Schülerinnen und Schüler fest!<br>Veranlassen Sie eine <b>Meldung</b> über die <b>Vollständigkeit</b> der Gruppe oder die <b>Abwesenheit</b> einzelner Schülerinnen und Schüler bei der <b>Einsatzleitung</b>:</p><ul>$personen</ul>";
						}
					}

					$code .= "</span></div></div>";
					echo $code;
					$code = "";
				}
			}

			include_once("php/allgemein/seiten/seitensteuerung.php");

			if ($CMS_URL[0] == 'App') {
				if ($CMS_ANGEMELDET) {echo "<p><input type=\"hidden\" name=\"cms_appAngemeldet\" id=\"cms_appAngemeldet\" value=\"ja\"></p>";}
				else {echo "<p><input type=\"hidden\" name=\"cms_appAngemeldet\" id=\"cms_appAngemeldet\" value=\"\"></p>";}
			}
			?>
		</div>
	</div>
	</div>

	<?php
		include_once("php/allgemein/seiten/fusszeile.php");
		include_once("php/allgemein/seiten/blende.php");
		if ($CMS_URL[0] != 'App') {
			include_once("php/allgemein/seiten/aktionsschicht.php");
		}
		cms_trennen($dbs);
	?>
	<div id="contextmenue"></div>

	<div id="cms_javascript">
		<?php
		if ($CMS_ANGEMELDET) {
			echo "<script type=\"text/javascript\">";
				echo "window.onload = function () {".$CMS_ONLOAD_EVENTS."\n";
				if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {echo "cms_netzcheck();\n";}
				echo "};";
			echo "</script>";
		}
		else if (($CMS_URL[0] == 'Website') || ($CMS_URL[0] == "Intern")) {
			echo "<script type=\"text/javascript\">";
				echo "window.onload = function () {".$CMS_ONLOAD_EXTERN_EVENTS."};";
			echo "</script>";
		}
		if ($CMS_URL[0] != 'Intern') {
			cms_erfasse_click();
		}
		?>
	</div>

</body>
</html>
