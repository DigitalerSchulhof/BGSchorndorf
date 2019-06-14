<?php
function cms_brotkrumen($url) {
	global $CMS_SEITENDETAILS;

	$code = "";
	$link = "";

	if ($url[0] == "Website") {
		$link .= "/Website";
		$code .= " / <a class=\"cms_link\" href=\"".substr($link, 1)."\">Startseite</a>";
		if (isset($CMS_SEITENDETAILS['art'])) {
			if ($CMS_SEITENDETAILS['art'] != 's') {
				$beginn = 2;
				if (isset($url[1])) {
					if (($url[1] == "Termine") || ($url[1] == "Blog") || ($url[1] == "Galerien") || ($url[1] == "Voranmeldung")) {
						$beginn = 1;
					}
					else {
						$link .= "/".$url[1];
					}
				}
				for ($i=$beginn; $i < count($url); $i++) {
					$link .= "/".$url[$i];
					$u = str_replace("_", " ", $url[$i]);
					$code .= " / <a class=\"cms_link\" href=\"".substr($link, 1)."\">".$u."</a>";
				}
			}
			// Normale Seite
			else {
				for ($i=1; $i < 4; $i++) {$link .= "/".$url[$i];}
				for ($i=4; $i < count($url); $i++) {
					$link .= "/".$url[$i];
					$u = str_replace("_", " ", $url[$i]);
					if (($i == 0) && ($u == "Website")) {$u = "Startseite";}
					$code .= " / <a class=\"cms_link\" href=\"".substr($link, 1)."\">".$u."</a>";
				}
			}
		}
		// Ferienkalender
		else if ($url[1] == "Ferien") {
			for ($i=1; $i < count($url); $i++) {
				$link .= "/".$url[$i];
				$u = str_replace("_", " ", $url[$i]);
				if (($i == 0) && ($u == "Website")) {$u = "Startseite";}
				$code .= " / <a class=\"cms_link\" href=\"".substr($link, 1)."\">".$u."</a>";
			}
		}
	}
	else {
		for ($i=0; $i < count($url); $i++) {
			$link .= "/".$url[$i];
			$u = str_replace("_", " ", $url[$i]);
			if (($i == 0) && ($u == "Website")) {$u = "Startseite";}
			$code .= " / <a class=\"cms_link\" href=\"".substr($link, 1)."\">".$u."</a>";
		}
	}

	return substr($code, 3);
}
?>
