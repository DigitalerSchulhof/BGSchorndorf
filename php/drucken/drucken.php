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
	include_once("php/website/funktionen/datenschutz.php");
	include_once("php/website/funktionen/geraet.php");
	include_once("php/schulhof/funktionen/dateisystem.php");
	$CMS_VERSION = trim(file_get_contents("version/version"));
	$CMS_ANGEMELDET = cms_angemeldet();
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
	}

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
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="format-detection" content="address=no">
	<meta name="format-detection" content="date=no">
	<meta name="format-detection" content="email=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo "<link type=\"image/png\" href=\"res/logos/$CMS_FAVICON\" rel=\"shortcut icon\">"; ?>
	<title>&nbsp;</title>

	<?php echo "<base href=\"$CMS_BASE\">";
		// <!-- Einbindung der Stylesheets -->
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/drucken.css?v=".substr(md5("css/drucken.css"), 0, 7)."\">";
	?>
</head>
<body>
	<div class="cms_druckseite">
		<?php
		$dbs = cms_verbinden('s');
		$druckfehler = true;
		function cms_druckkopf() {
			global $CMS_WICHTIG, $CMS_LOGODRUCK;
			$code = "<div class=\"cms_druckkopf\">";
				$code .= "<span class=\"cms_logo\">";
					$code .= "<img class=\"cms_logo_bild\" src=\"res/logos/$CMS_LOGODRUCK\">";
					$code .= "<span class=\"cms_logo_schrift\">";
						$code .= "<span class=\"cms_logo_o\">".$CMS_WICHTIG['Schulname']."</span>";
						$code .= "<span class=\"cms_logo_u\">".$CMS_WICHTIG['Schule Ort']."</span>";
					$code .= "</span><div class=\"cms_clear\">";
					$code .= "</div>";
				$code .= "</span>";
			$code .= "</div>";
			return $code;
		}

		$code = "";
		array_shift($CMS_URL);
		$CMS_URLGANZ = implode('/', $CMS_URL);

		if (isset($_SESSION['DRUCKANSICHT'])) {
			if (($_SESSION['DRUCKANSICHT'] == 'Schulanmeldung') && ($CMS_ANGEMELDET) && cms_r("schulhof.organisation.schulanmeldung.akzeptieren")) {
				$code .= cms_druckkopf();
				include("schulanmeldung.php");
			}
			else if (($_SESSION['DRUCKANSICHT'] == 'Vertretungsplan') && (isset($_SESSION['DRUCKVPLANDATUMV'])) && (isset($_SESSION['DRUCKVPLANDATUMB']))) {
				$code .= cms_druckkopf();
				include("vertretungsplan.php");
			}
		}
		else {
			include_once("php/allgemein/funktionen/brotkrumen.php");
			$CMS_MONATELINK = "(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)";

			if (preg_match("/^Website\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{1,2}\/$CMS_LINKMUSTER/", $CMS_URLGANZ)) {
				include('blog.php');
			}

			if (preg_match("/^Schulhof\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{1,2}\/$CMS_LINKMUSTER/", $CMS_URLGANZ)) {
				include('blog.php');
			}

			if (preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)) {
				include('blog.php');
			}
		}

		if ($druckfehler) {
			$code .= "<p><i>Es wurde kein Dokument gewählt!</i></p>";
		}

		echo $code;
		cms_trennen($dbs);
		unset($_SESSION['DRUCKANSICHT']);
	?>
	</div>
</body>
</html>
