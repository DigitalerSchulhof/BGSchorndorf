<?php
function cms_brotkrumen($url, $aktionen = true) {
	global $CMS_SEITENDETAILS, $CMS_GRUPPEN, $CMS_BENUTZERID, $CMS_SCHLUESSEL, $CMS_LINKMUSTER, $CMS_URLGANZ;

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

	if($aktionen) {
		if(cms_angemeldet() && $url[0] == "Schulhof") {
			$fid = "";
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
				$text = "Favorit entfernen";
			}
			else {
				$favoritwert = 0;
				$icon = "res/icons/klein/favorisieren.png";
				$klasse = "";
				$text = "Seite Favorisieren";
			}
			$favorisieren = "<span class=\"cms_aktionsicon\"><span class=\"cms_hinweis cms_hinweis_unten\">$text</span><img id=\"cms_seite_favorit_icon\" onclick=\"cms_favorisieren('$fid', '".join('/', $url)."')\" src=\"$icon\"><input type=\"hidden\" value=\"$favoritwert\" name=\"cms_seite_favorit\" id=\"cms_seite_favorit\"></span>";
			$code .= $favorisieren;
		}

		$CMS_MONATELINK = "(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)";
		if (cms_angemeldet() &&
		(
			preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)		||
			preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)
		) && count($url) == 10) {
			$dbs = cms_verbinden("s");
			$schuljahr = cms_linkzutext($url[2]);
			$g = cms_linkzutext($url[3]);
			$gk = cms_textzudb($g);
			$gbez = cms_linkzutext($url[4]);
			$gruppenid = "";
			$fehler = false;

			// Prüfen, ob diese Gruppe existiert
			if (in_array($g, $CMS_GRUPPEN)) {
				if ($schuljahr == "Schuljahrübergreifend") {
					$sql = $dbs->prepare("SELECT id, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IS NULL");
				  $sql->bind_param("s", $gbez);
				}
				else {
					$sql = $dbs->prepare("SELECT id, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
				  $sql->bind_param("ss", $gbez, $schuljahr);
				}
				// Schuljahr finden
			  if ($sql->execute()) {
			    $sql->bind_result($gruppenid, $anzahl);
			    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
					else {$fehler = true;}
			  }
			  else {$fehler = true;}
			  $sql->close();
			}
			else {$fehler = true;}

			$blogquery = "IS NULL";
			$terminquery = "IS NULL";
			$art = '';
			$artikelid;

			if(!$fehler) {
				if(preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)) {
					// Blogeintrag prüfen
					$art = 'b';
					$blogquery = "= ?";

					$jahr = $url[6];
					$monat = cms_monatnamezuzahl($url[7]);
					$tag = $url[8];
					$blogeintragbez = cms_linkzutext($url[9]);
					$datum = mktime(0, 0, 0, $monat, $tag, $jahr);
					$aktiv = false;
					$gefunden = false;

					$sql = $dbs->prepare("SELECT id, aktiv FROM {$gk}blogeintraegeintern WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ?;");
					$sql->bind_param("si", $blogeintragbez, $datum);
					if ($sql->execute()) {
				    $sql->bind_result($artikelid, $aktiv);
				    if ($sql->fetch()) {
							$gefunden = true;
						}
						else {$fehler = true;}
				  }
				  else {$fehler = true;}
				  $sql->close();

					if($gefunden) {
						$gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gruppenid);
						$gefunden = $gefunden && $gruppenrecht['sichtbar'] && ($aktiv || $gruppenrecht['blogeintraege']);
					}
					$fehler |= !$gefunden;

				} else if(preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)) {
					// Termin prüfen
					$art = 't';
					$terminquery = "= ?";

					$jahr = $url[6];
					$monat = cms_monatnamezuzahl($url[7]);
					$tag = $url[8];
					$terminbez = cms_linkzutext($url[9]);
					$datumb = mktime(0, 0, 0, $monat, $tag, $jahr);
					$datume = mktime(0, 0, 0, $monat, $tag+1, $jahr)-1;
					$gruppe = cms_linkzutext($url[3]);
					$aktiv = false;
					$gefunden = false;

					$sql = $dbs->prepare("SELECT id, aktiv FROM {$gk}termineintern WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND (beginn BETWEEN ? AND ?);");
					$sql->bind_param("sii", $terminbez, $datumb, $datume);
					if ($sql->execute()) {
						$sql->bind_result($artikelid, $aktiv);
						if ($sql->fetch()) {$gefunden = true;}
						else {$fehler = true;}
					}
					else {$fehler = true;}
					$sql->close();

					if($gefunden) {
						$gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gruppenid);
						$gefunden = $gefunden && $gruppenrecht['sichtbar'] && ($aktiv || $gruppenrecht['termine']);
					}
					$fehler |= !$gefunden;

				}
			}

			$fehler = $fehler || is_null($artikelid);
			if(!$fehler) {
				$sql = "SELECT 1 FROM {$gk}todoartikel WHERE person = ? AND blogeintrag $blogquery AND termin $terminquery";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("ii", $CMS_BENUTZERID, $artikelid);
				$sql->bind_result($todo);
				$sql->execute();
				$todo = ($sql->fetch());

				if ($todo) {
					$todowert = 1;
					$icon = "res/icons/klein/todo_erledigen.png";
					$klasse = "cms_favorit";
					$text = "ToDo entfernen";
				}
				else {
					$todowert = 0;
					$icon = "res/icons/klein/todo_neu.png";
					$klasse = "";
					$text = "Als ToDo markieren";
				}

				$todo = "<span class=\"cms_aktionsicon\"><span class=\"cms_hinweis cms_hinweis_unten\">$text</span><img id=\"cms_seite_todo_icon\" onclick=\"cms_seite_todo('$g', '$gruppenid', '$art', '$artikelid')\" src=\"$icon\"><input type=\"hidden\" value=\"$todowert\" name=\"cms_seite_todo\" id=\"cms_seite_todo\"></span>";
				$code .= $todo;
			}
		}

		// Weiterleitung einrichten
		if(cms_r("website.weiterleiten")) {
			$code .= "<span class=\"cms_aktionsicon\"><span class=\"cms_hinweis cms_hinweis_unten\">Neue Weiterleitung</span><img onclick=\"cms_neue_weiterleitung('/".join('/', $url)."')\" src=\"res/icons/klein/weiterleiten.png\"></span>";
		}

		if (preg_match("/^Schulhof\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{1,2}\/$CMS_LINKMUSTER/", $CMS_URLGANZ) ||
				preg_match("/^Schulhof\/Gruppen\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ) ||

				preg_match("/^Website\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)
			) {
				$code .= "<span class=\"cms_aktionsicon\"><span class=\"cms_hinweis cms_hinweis_unten\">Seite Drucken</span><img onclick=\"cms_drucken('Drucken/".join('/', $url)."')\" src=\"res/icons/klein/drucken.png\"></span>";
		}
	}

	return substr($code, 3);
}
?>
