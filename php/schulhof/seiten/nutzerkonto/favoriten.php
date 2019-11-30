<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Favoriten</h1>
<?php
$tabellencode = "";
$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(url, '$CMS_SCHLUESSEL') AS url FROM favoritseiten WHERE person = ?) AS x ORDER BY bezeichnung, url");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($fid, $fbez, $furl);
	while ($sql->fetch()) {
		$tabellencode .= "<tr><td><img src=\"res/icons/klein/favorit.png\"></td>";
		$tabellencode .= "<td><input type=\"text\" name=\"cms_favoriten_bezeichnung_$fid\" id=\"cms_favoriten_bezeichnung_$fid\" value=\"$fbez\"></td>";
		$tabellencode .= "<td><span class=\"cms_button\" onclick=\"cms_favorit_benennen('$fid')\">Umbenennen</span></td>";
		$tabellencode .= "<td>$furl</td></tr>";
	}
}
$sql->close();

$code = "<table class=\"cms_liste\">";
	$code .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Zielseite</th></tr>";
	if (strlen($tabellencode) > 0) {$code .= $tabellencode;}
	else {$code .= "<tr><td colspan=\"4\" class=\"cms_notiz\">– keine Favoriten angelegt –</td></tr>";}
$code .= "</table>";


echo $code;

?>

</div>

<div class="cms_clear"></div>
