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
		$favorisieren = "";
		$dbs = cms_verbinden("s");
		$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM favoritseiten WHERE person = ? AND url = AES_ENCRYPT(?, '$CMS_SCHLUESSEL');";
		$sql = $dbs->prepare($sql);
		$jurl = join("/", $url);
		$sql->bind_param("is", $CMS_BENUTZERID, $jurl);
		$sql->execute();
		$sql->bind_result($bez);
		$favorit = true;
		if(!$sql->fetch()) {
			$favorit = false;
			$bez = $url[count($url)-1];	// pop ohne ändern
		}
		$favorisieren = "<span class=\"cms_favorisieren\"><img onclick=\"cms_favorisieren('".join('/', $url)."')\" src=\"res/icons/klein/".($favorit ? "favorit" : "favorisieren").".png\" class=\"".($favorit ? "favorit" : "")."\">"
		 							 ." <span class=\"cms_favorit_bezeichnung\"><input type=\"text\" value=\"$bez\" onkeyup=\"cms_stopschreiben(this, function() {cms_favorit_benennen('".join('/', $url)."')})\"></input></span></span>";
		$code .= $favorisieren;
	}

	return substr($code, 3);
}
?>
