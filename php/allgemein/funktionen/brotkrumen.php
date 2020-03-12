<?php
function cms_brotkrumen($url, $favorisieren = true) {
	global $CMS_SEITENDETAILS, $CMS_BENUTZERID, $CMS_SCHLUESSEL;

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

	if($favorisieren && cms_angemeldet() && $url[0] == "Schulhof") {
		$fid = "";
		$favorisieren = "";
		$dbs = cms_verbinden("s");
		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM favoritseiten WHERE person = ? AND url = AES_ENCRYPT(?, '$CMS_SCHLUESSEL');";
		$sql = $dbs->prepare($sql);
		$jurl = join("/", $url);
		$sql->bind_param("is", $CMS_BENUTZERID, $jurl);
		$sql->execute();
		$sql->bind_result($fid, $bez);
		$favorit = true;
		if(!$sql->fetch()) {
			$favorit = false;
			$bez = $url[count($url)-1];	// pop ohne ändern
		}

		if ($favorit) {
			$favoritwert = 1;
			$icon = "res/icons/klein/favorit.png";
			$klasse = "cms_favorit";
		}
		else {
			$favoritwert = 0;
			$icon = "res/icons/klein/favorisieren.png";
			$klasse = "";
		}
		$favorisieren = "<span class=\"cms_favorisieren\"><img id=\"cms_steite_favorit_icon\" onclick=\"cms_favorisieren('$fid', '".join('/', $url)."')\" src=\"$icon\"><input type=\"hidden\" value=\"$favoritwert\" name=\"cms_seite_favorit\" id=\"cms_seite_favorit\"></span>";
		$code .= $favorisieren;
	}

	// Weiterleitung einrichten
	if(cms_r("website.weiterleiten")) {
		$code .= "<span class=\"cms_neue_weiterleitung\"><img onclick=\"cms_neue_weiterleitung('/".join('/', $url)."')\" src=\"res/icons/klein/weiterleiten.png\"></span>";
	}

	return substr($code, 3);
}
?>
