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
	$CMS_VERSION = trim(file_get_contents("version/version"));
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

		if($CMS_URL[0] == "Drucken") {
      include_once("php/drucken/drucken.php");
			die();
		}

		// Fallback bei ungültigen URLs
    if (!in_array($CMS_URL[0], array("Website", "Schulhof", "Problembehebung", "Intern", "App"))) {
			$CMS_URL = array();
			$CMS_URL[0] = "Website";
			$CMS_URL[1] = "Fehler";
			$CMS_URL[2] = "404";
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
				if (!in_array($CMS_URL[1], array("Fehler", "Seiten", "Blog", "Galerien", "Termine", "Voranmeldung", "Ferien", "Datenschutz", "Impressum", "Feedback"))) {
					$CMS_URL = array();
					$CMS_URL[0] = "Website";
					$CMS_URL[1] = "Fehler";
					$CMS_URL[2] = "404";
				}
				else if (isset($CMS_URL[2])) {
					if (!in_array($CMS_URL[2], array("Seiten", "Aktuell", "301", "302", "403", "404", "500"))) {
						$CMS_URL = array();
						$CMS_URL[0] = "Website";
						$CMS_URL[1] = "Fehler";
						$CMS_URL[2] = "404";
					}
				}
			}
		}
	}
	else {
		$CMS_URL = array();
		$CMS_URL[0] = "Website";
		$CMS_URL[1] = "Fehler";
		$CMS_URL[2] = "404";
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

	$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
	$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');

	$CMS_SEITENTITEL = "";
	if (count($CMS_URL) > 0) {
		if ($CMS_URL[0] == "Website") {
			if (count($CMS_URL) > 1) {
				if ($CMS_URL[1] == "Fehler") {$CMS_SEITENTITEL = "Fehler";}
				else if ($CMS_URL[1] == "Termine") {$CMS_SEITENTITEL = "Termine";}
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
	<?php echo "<link type=\"image/png\" href=\"dateien/schulspezifisch/favicon.ico\" rel=\"shortcut icon\">";?>
	<title><?php echo $CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort']." • ".$CMS_SEITENTITEL;?></title>

	<?php echo "<base href=\"$CMS_BASE\">";
		$hellhash 	= substr(md5(filemtime("css/hell.css")), 0, 7);
		$dunkelhash = substr(md5(filemtime("css/dunkel.css")), 0, 7);
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/hell.css?cb=$hellhash\">";
		if ($CMS_EINSTELLUNGEN['Website Darkmode'] == 1) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/dunkel.css?cb=$dunkelhash\">";
		}

		function js($js) {
			global $CMS_VERSION;
			$cb = $CMS_VERSION;
			if(file_exists("$js")) {
				$cb = substr(md5(filemtime("$js")), 0, 7);
			}
			return "<script src=\"$js?cb=$cb\"></script>";
		}

		echo js("js/jquery.js");

    //<!-- Einbindung der JavaScripts -->

    echo js("js/allgemein/anfragen.js");
    echo js("js/allgemein/generieren.js");
    echo js("js/allgemein/reiter.js");
    echo js("js/allgemein/zeigen.js");
    echo js("js/allgemein/link.js");
    echo js("js/allgemein/toggle.js");
    echo js("js/allgemein/blende.js");
    echo js("js/allgemein/check.js");
    echo js("js/allgemein/download.js");
    echo js("js/allgemein/suche.js");
    echo js("js/allgemein/contextmenue.js");
    echo js("js/schulhof/nutzerkonto/anmelden.js");
    echo js("js/website/zugehoerig.js");
    echo js("js/website/wechselbilder.js");
		echo js("js/website/voranmeldung.js");
		echo js("js/website/feedback.js");
		echo js("js/website/kontaktformular.js");

		echo js("js/website/newsletter.js");

		// Skripte, die nur für Angemeldete notwendig sind
		if ($CMS_ANGEMELDET) {
			$code = "";
			$code .= js("js/summernote/summernote.js");
			$code .= js("js/chartJS/moment.js");
			$code .= js("js/chartJS/chart.js");

			$code .= js("js/schulhof/nutzerkonto/profildaten.js");
			$code .= js("js/schulhof/nutzerkonto/postfach.js");
			$code .= js("js/schulhof/kalender.js");
			$code .= js("js/schulhof/verwaltung/personen.js");
			$code .= js("js/schulhof/verwaltung/schulanmeldung.js");
			$code .= js("js/schulhof/verwaltung/personensuche.js");
			$code .= js("js/schulhof/verwaltung/rollen.js");
			$code .= js("js/schulhof/verwaltung/schuljahre.js");
			$code .= js("js/schulhof/verwaltung/raeume.js");
			$code .= js("js/schulhof/verwaltung/leihgeraete.js");
			$code .= js("js/schulhof/verwaltung/zulaessig.js");
			$code .= js("js/schulhof/verwaltung/schuldetails.js");
			$code .= js("js/schulhof/verwaltung/faecher.js");
			$code .= js("js/schulhof/verwaltung/notifikationen.js");
			$code .= js("js/schulhof/verwaltung/geraete.js");
			$code .= js("js/schulhof/verwaltung/blockierung.js");
			$code .= js("js/schulhof/verwaltung/buchung.js");
			$code .= js("js/schulhof/verwaltung/allgemeineeinstellungen.js");
			$code .= js("js/schulhof/verwaltung/schuldetails.js");
			$code .= js("js/schulhof/verwaltung/schulnetze.js");
			$code .= js("js/schulhof/verwaltung/stundenplanung.js");
			$code .= js("js/schulhof/verwaltung/vertretungsplanung.js");
			$code .= js("js/schulhof/verwaltung/gruppen.js");
			$code .= js("js/schulhof/verwaltung/ferien.js");
			$code .= js("js/schulhof/verwaltung/hausmeister.js");
			$code .= js("js/schulhof/verwaltung/dauerbrenner.js");
			$code .= js("js/schulhof/verwaltung/pinnwaende.js");
			$code .= js("js/schulhof/verwaltung/zeitraeume.js");
			$code .= js("js/schulhof/verwaltung/profile.js");
			$code .= js("js/schulhof/verwaltung/schienen.js");
			$code .= js("js/schulhof/verwaltung/schuljahrfabrik.js");
			$code .= js("js/schulhof/verwaltung/import.js");
			$code .= js("js/schulhof/website/style.js");
			$code .= js("js/schulhof/website/zuordnung.js");
			$code .= js("js/schulhof/website/termine.js");
			$code .= js("js/schulhof/website/blogeintraege.js");
			$code .= js("js/schulhof/website/seiten.js");
			$code .= js("js/schulhof/website/hauptnavigationen.js");
			$code .= js("js/schulhof/dateien.js");
			$code .= js("js/schulhof/gruppen.js");
			$code .= js("js/schulhof/downloads.js");
			$code .= js("js/schulhof/beschluesse.js");
			$code .= js("js/schulhof/listen.js");
			$code .= js("js/schulhof/nutzerkonto/ebedarf.js");
			$code .= js("js/lehrerzimmer/lehrernetz.js");
			$code .= js("js/lehrerzimmer/tagebuch.js");

			$code .= js("js/website/bearbeiten.js");
			$code .= js("js/website/editor.js");
			$code .= js("js/website/downloads.js");
			$code .= js("js/website/boxen.js");
			$code .= js("js/website/eventuebersicht.js");
			$code .= js("js/website/diashow.js");

			$code .= js("js/schulhof/besucherstatistik.js");
			$code .= js("js/schulhof/feedback.js");
			$code .= js("js/schulhof/website/galerien.js");
			$code .= js("js/schulhof/verwaltung/auffaelliges.js");
			$code .= js("js/schulhof/verwaltung/newsletter.js");
			$code .= js("js/schulhof/website/auszeichnungen.js");
			$code .= js("js/schulhof/verwaltung/rechtebaum.js");
			$code .= js("js/schulhof/verwaltung/bedingte_rechte.js");
			$code .= js("js/schulhof/verwaltung/speicherplatz.js");
			$code .= js("js/schulhof/verwaltung/update.js");
			echo $code;
			$code = "";
		}
		?>

    <script><?php
			$CMS_ONLOAD_EXTERN_EVENTS = "";
			echo "var CMS_DOMAIN = '".$CMS_WICHTIG['Schule Domain']."';\n";
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
        echo "var CMS_MAX_DATEI = ".$CMS_EINSTELLUNGEN['Maximale Dateigröße'].";\n";
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
		if (!Array.prototype.last){
    	Array.prototype.last = function(){
        return this[this.length - 1];
    	};
		};
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
