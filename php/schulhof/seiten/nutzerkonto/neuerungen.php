<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p><?php
// Nach Updates prüfen
if(cms_r("technik.server.update")) {
	if($CMS_EINSTELLUNGEN["Netze Ofizielle Version"]) {
		$Updater_base = "https://update.digitaler-schulhof.de";
	} else {
		$Updater_base = "https://api.github.com/repos/{$CMS_EINSTELLUNGEN['Netze GitHub Benutzer']}/{$CMS_EINSTELLUNGEN['Netze GitHub Repository']}";
	}
	$basis_verzeichnis = dirname(__FILE__)."/../../../..";

	if(!file_exists("$basis_verzeichnis/version/version")) {
		echo cms_meldung("fehler", "<h4>Ungültige Version</h4><p>Bitte den Administrator benachrichtigen!</p>");
	} else {
		$version = trim(file_get_contents("$basis_verzeichnis/version/version"));

		// Versionsverlauf von GitHub holen
		$curl = curl_init();
		$curlConfig = array(
			CURLOPT_URL             => "$Updater_base/releases/latest",
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_HTTPHEADER      => array(
				"Content-Type: application/json",
				"Authorization: token ".$CMS_EINSTELLUNGEN['Netze GitHub OAuth'],
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
?>
<h1>Neuerungen</h1>
<?php
	include_once(dirname(__FILE__)."/../../../allgemein/funktionen/yaml.php");
	use Async\Yaml;

	$aeltere = "";
	$versionen = Yaml::loader(dirname(__FILE__)."/../../../../version/versionen.yml")["version"];

	$version = function ($v, $version, $sichtbar = 0) {
		$code = "<h4>".$version["version"]." - ".$version["tag"]."</h4>";
		$code .= "<ul>";
			foreach($version["neuerungen"] as $n)
				$code .= "<li>$n</li>";
		$code .= "</ul>";
		return cms_toggleeinblenden_generieren ("cms_neuerungenverlaufknopf_$v", "Neuerungen in Version <b>".$version["version"]."</b> einblenden", "Neuerungen in Version <b>".$version["version"]."</b> ausblenden", $code, $sichtbar);
	};


	echo $version(array_keys($versionen)[0], array_shift($versionen), 1);

	foreach($versionen as $k => $v) {
		echo $version($k, $v);
	}
?>
