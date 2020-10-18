<?php
	header("Content-Type: application/manifest+json");
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/schulhof/funktionen/config.php");
	include_once("php/schulhof/funktionen/check.php");
	include_once("php/schulhof/funktionen/meldungen.php");
	include_once("php/schulhof/funktionen/generieren.php");

	$DBS = cms_verbinden('s');
	$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');
?>
{
  "name": "<?php echo $CMS_WICHTIG["Schulname"];?>",
  "short_name": "<?php echo $CMS_WICHTIG["Schulname"];?>",
  "start_url": "/",
  "display": "standalone",
  "background_color": "<?php $anf = $DBS->prepare("SELECT wert FROM style WHERE name = 'cms_style_h_haupt_thema1'");$anf->bind_result($thema1);$anf->execute();$anf->fetch(); echo $thema1; ?>",
  "description": "Der Digitaler Schulhof des <?php echo $CMS_WICHTIG["Schulname Genitiv"];?>",
  "icons": [{
    "src": "dateien/schulspezifisch/favicon/48.png",
    "sizes": "48x48",
    "type": "image/png"
  }, {
    "src": "dateien/schulspezifisch/favicon/72.png",
    "sizes": "72x72",
    "type": "image/png"
  }, {
    "src": "dateien/schulspezifisch/favicon/96.png",
    "sizes": "96x96",
    "type": "image/png"
  }, {
    "src": "dateien/schulspezifisch/favicon/144.png",
    "sizes": "144x144",
    "type": "image/png"
  },<?php /* {
    "src": "@TODO: 168",
    "sizes": "168x168",
    "type": "image/png"
  }, */?>{
    "src": "dateien/schulspezifisch/favicon/192.png",
    "sizes": "192x192",
    "type": "image/png"
  }],
  "related_applications": [{
    "platform": "play",
    "url": "https://play.google.com/store/apps/details?id=com.dsh.digitalerschulhof",
    "id": "com.dsh.digitalerschulhof"
  },
  {
    "platform": "itunes",
    "url": "https://apps.apple.com/de/app/digitaler-schulhof/id1500912100",
    "id": "1500912100"
  }],
  "shortcuts": [{
    "name": "Website öffnen",
    "short_name": "Website",
    "description": "Öffnet direkt die Website",
    "url": "/",
    "icons": [{
      "src": "dateien/schulspezifisch/favicon/96.png",
      "sizes": "96x96",
      "purpose": "any"
    }]
  },
  {
    "name": "Schulhof öffnen",
    "short_name": "Schulhof",
    "description": "Öffnet direkt den Schulhof",
    "url": "/Schulhof",
    "icons": [{
      "src": "dateien/schulspezifisch/favicon/96.png",
      "sizes": "96x96",
      "purpose": "any"
    }]
  },
  {
    "name": "Stundenplan öffnen",
    "short_name": "Stundenplan",
    "description": "Öffnet direkt den Stundenplan",
    "url": "/Schulhof/Nutzerkonto/Mein_Stundenplan",
    "icons": [{
      "src": "dateien/schulspezifisch/favicon/96.png",
      "sizes": "96x96",
      "purpose": "any"
    }]
  }]
}