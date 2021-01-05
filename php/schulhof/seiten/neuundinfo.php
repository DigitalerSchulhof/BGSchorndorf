<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = '<ul class="cms_systemvoraussetzung">';
		$code .= '<li><img src="res/icons/gross/version.png"><br><b>Version '.$CMS_VERSION.'</b></li>';
		$code .= '<li><img src="res/icons/gross/cookies.png"><br>Cookies aktiv?</li>';
		$code .= '<li><img src="res/icons/gross/javascript.png"><br>JavaScript aktiv?</li>';
		$code .= '<li><img src="res/icons/gross/multinutzer.png"><br>Nur ein Nutzer pro Browser zur selben Zeit!</li>';
		$code .= '<li></li>';
		$code .= '<li id="cms_browsertest"><img src="res/icons/gross/warnung.png"><br>Der Browser unterstützt womöglich nicht alle Funktionen.</li>';
	$code .= '</ul>';

	$code .= "<script>cms_check_browserunterstuetzung();</script>";
	echo $code;
	?>
</div>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$zusatztext = "";
	$sql = $dbs->prepare("SELECT wert FROM master WHERE inhalt = 'Anmelden'");
	if ($sql->execute()) {
		$sql->bind_result($zusatztext);
		$sql->fetch();
	}
	$sql->close();
	if (strlen($zusatztext) > 0) {
		echo $zusatztext;
	}
	?>
</div>
</div>

<div class="cms_clear"></div>


<div class="cms_spalte_i">
	<h2>Neuerungen</h2>
	<?php
		include_once(dirname(__FILE__)."/../../allgemein/funktionen/yaml.php");
		use Async\Yaml;

		$aeltere = "";
		$versionen = Yaml::loader(dirname(__FILE__)."/../../../version/versionen.yml")["version"];

		$version = function ($v, $version, $sichtbar = 0) {
			$code = "<h4>".$version["version"]." - ".$version["tag"]."</h4>";
			$code .= "<ul>";
				foreach($version["neuerungen"] as $n) {
					$n = preg_replace_callback("/\[(W|E)\]/", function($m) {return "<span class=\"cms_notiz\">{$m[0]}</span>";}, $n);
					$code .= "<li style=\"line-height: 1.45em\">$n</li>";
				}
			$code .= "</ul>";
			return cms_toggleeinblenden_generieren ("cms_neuerungenverlaufknopf_$v", "Neuerungen in Version <b>".$version["version"]."</b> einblenden", "Neuerungen in Version <b>".$version["version"]."</b> ausblenden", $code, $sichtbar);
		};


		echo $version(array_keys($versionen)[0], array_shift($versionen), 1);

		foreach($versionen as $k => $v) {
			$aeltere .= $version($k, $v);
		}

		echo cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_aeltere', "Neuerungen älterer Versionen einblenden", "Neuerungen älterer Versionen ausblenden", $aeltere, 0);
	?>
	</div>
</div>
