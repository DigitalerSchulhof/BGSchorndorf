<?php
	include_once(__DIR__."/../funktionen/yaml.php");
	use Async\Yaml;

	if(is_null($CMS_WIKI)) {

		$wikilinks = file_get_contents(__DIR__."/../../../version/wikilinks.yml");

		$t = function ($url, $typ, $urlganz) {
			switch ($typ) {
				case "regex":
					if(preg_match($url, $urlganz) === 1) {
						return true;
					}
					break;
				case "url":
					return $url == $urlganz;
			}
			return false;
		};

		$wikilinks = YAML::loader($wikilinks);
		if($wikilinks && $wikilinks["seiten"] && is_array($wikilinks["seiten"])) {
			foreach($wikilinks["seiten"] as $seite) {
				$url = $seite["url"];
				$typ = $seite["typ"];
				$wiki = $seite["wiki"];
				if(is_array($url)) {
					foreach($url as $u) {
						if($t($u, $typ, $CMS_URLGANZ)) {
							$CMS_WIKI = $wiki;
							break;
						}
					}
				} else {
					if($t($url, $typ, $CMS_URLGANZ)) {
						$CMS_WIKI = $wiki;
					}
				}
			}
		}
	}

	if(!is_null($CMS_WIKI) && $CMS_WIKI !== false) {
		echo "<span id=\"cms_wiki\" class=\"cms_aktion_klein\" onclick=\"cms_link('$CMS_WIKI', true)\" style=\"padding: 2px;\"><span class=\"cms_hinweis cms_hinweis_links\" style=\"bottom: 45px;\">Hilfe</span><img src=\"res/icons/gross/wiki.png\"></span>";
	}
?>
