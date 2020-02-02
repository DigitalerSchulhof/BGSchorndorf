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
	session_start();
	//$CMS_VERSION = rand(0,1000000);
	$CMS_VERSION = "0.5.74";
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

		// Rechte des Benutzers laden
		$CMS_RECHTE = cms_rechte_laden();
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
	<title>App – <?php echo $CMS_SCHULE." ".$CMS_ORT;?></title>

	<?php echo "<base href=\"$CMS_BASE\">";
	// <!-- Einbindung der Stylesheets -->
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/fonts.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/app.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ladeicon.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/text.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/meldungen.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/reiter.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/stundenplan.css?v=$CMS_VERSION\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/stundenplanung.css?v=$CMS_VERSION\">";
	echo "<script src=\"js/allgemein/check.js?v=$CMS_VERSION\"></script>";
	echo "<script src=\"js/allgemein/anfragen.js?v=$CMS_VERSION\"></script>";
	echo "<script src=\"js/allgemein/reiter.js?v=$CMS_VERSION\"></script>";
	echo "<script src=\"js/allgemein/zeigen.js?v=$CMS_VERSION\"></script>";
	echo "<script src=\"js/allgemein/blende.js?v=$CMS_VERSION\"></script>";
	echo "<script src=\"js/schulhof/app.js?v=$CMS_VERSION\"></script>";
	?>
	<script><?php
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
			if ($CMS_IMLN) {
				echo "CMS_IMLN = true;\n";
			}
			echo "var CMS_GRUPPEN = ['Gremien','Fachschaften','Klassen','Kurse','Stufen','Arbeitsgemeinschaften','Arbeitskreise','Fahrten','Wettbewerbe','Ereignisse','Sonstige Gruppen'];";
		}
	?>
	</script>
</head>
<body>
	<div class="cms_appseite">
		<?php
		$dbs = cms_verbinden('s');
		$fehler = false;
		$code = "<div id=\"cms_appkopf\">";
			$code .= "<span id=\"cms_logo\">";
				$code .= "<img id=\"cms_logo_bild\" src=\"res/logos/$CMS_LOGO\">";
				$code .= "<span id=\"cms_logo_schrift\">";
					$code .= "<span id=\"cms_logo_o\">$CMS_SCHULE</span>";
					$code .= "<span id=\"cms_logo_u\">$CMS_ORT</span>";
				$code .= "</span><div class=\"cms_clear\">";
				$code .= "</div>";
			$code .= "</span>";
		$code .= "</div>";

		$code .= "<div id=\"cms_appinhalt\">";
		$code .= "<div class=\"cms_zentriert\">".cms_ladeicon()."</div>";
		$code .= "<p class=\"cms_zentriert\">Der Inhalt wird geladen...</p>";
		$code .= "</div>";

		echo $code;
		cms_trennen($dbs);
		?>
	</div>
</body>
</html>
