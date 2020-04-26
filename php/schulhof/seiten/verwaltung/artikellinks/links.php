<?php
function cms_artikellinkelemente($dbs, $art, $id, $gruppe = '-', $gruppenid = '-') {
  global $CMS_SCHLUESSEL;
  $code = "";

  $gk = cms_textzudb($gruppe);

  // Vorhandene artikellinks laden
  $sql = "";
  $links = array();
  if ($id != '-') {
    if ($art == 'termine') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(link, '$CMS_SCHLUESSEL') AS link, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM terminlinks WHERE termin = ?) AS x ORDER BY id";
    }
    else if ($art == 'blogeintraege') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(link, '$CMS_SCHLUESSEL') AS link, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM blogeintraglinks WHERE blogeintrag = ?) AS x ORDER BY id";
    }
    else if ($art == 'blogintern') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(link, '$CMS_SCHLUESSEL') AS link, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM $gk"."blogeintraglinks WHERE blogeintrag = ?) AS x ORDER BY id";
    }
    else if ($art == 'terminintern') {
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(link, '$CMS_SCHLUESSEL') AS link, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM $gk"."termineinternlinks WHERE termin = ?) AS x ORDER BY id";
    }

    if (strlen($sql) > 0) {
      $sql = $dbs->prepare($sql);
      $sql->bind_param("i", $id);
      if ($sql->execute()) {
				$sql->bind_result($lid, $llink, $ltitel, $lbeschr);
				while ($sql->fetch()) {
					$L = array();
					$L['id'] = $lid;
					$L['link'] = $llink;
					$L['titel'] = $ltitel;
					$L['beschreibung'] = $lbeschr;
					array_push($links, $L);
				}
      }
      $sql->close();
    }
  }


  $code .= "<div id=\"cms_artikellinks\">";
  $anzahl = 0;
  $ids = "";
  foreach ($links as $l) {
    $lid = $l['id'];
    $code .= "<table class=\"cms_formular\" id=\"cms_artikellink_$lid\">";
      $code .= "<tr><th>Linkziel:</th><td colspan=\"4\"><input type=\"text\" name=\"cms_artikellink_link_".$lid."\" id=\"cms_artikellink_link_".$lid."\" placeholder=\"https://seite.de/Link/Zum/Ziel\" value=\"".$l['link']."\"></td></tr>";
      $code .= "<tr><th>Titel:</th><td colspan=\"4\"><input type=\"text\" name=\"cms_artikellink_titel_".$lid."\" id=\"cms_artikellink_titel_".$lid."\" value=\"".$l['titel']."\"></td></tr>";
      $code .= "<tr><th>Beschreibung:</th><td colspan=\"4\"><textarea name=\"cms_artikellink_beschreibung_".$lid."\" id=\"cms_artikellink_beschreibung_".$lid."\">".$l['beschreibung']."</textarea></td></tr>";

      $code .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_artikellink_entfernen('$lid');\">Link löschen</span></td></tr>";
    $code .= "</table>";
    $anzahl++;
    $ids .= "|".$lid;
  }
  $code .= "</div>";
	$code .= "<input type=\"hidden\" id=\"cms_artikellinks_anzahl\" name=\"cms_artikellinks_anzahl\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_artikellinks_nr\"     name=\"cms_artikellinks_nr\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_artikellinks_ids\"    name=\"cms_artikellinks_ids\" value=\"$ids\">";

  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_artikellink();\">+ Neuer Link</span>";

  return $code;
}

function cms_artikellink_ausgeben($l) {
	$code = "";
	$link = $l['link'];
	$titel = $l['titel'];
	$beschreibung = $l['beschreibung'];

	$code .= "<a href=\"$link\" class=\"cms_artikellink_anzeige\" target=\"_blank\" style=\"background-image: url('res/icons/gross/link.png');\">";
		$code .= "<h4>$titel</h4>";
		if (strlen($beschreibung) > 0) {$code .= "<p>$beschreibung</p>";}
		$info = $link;
		if (strlen($info) > 0) {
			$code .= "<p class=\"cms_notiz\">".$info."</p>";
		}
	$code .= "</a>";
	return $code;
}
?>
