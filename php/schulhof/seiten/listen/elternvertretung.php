<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Liste der Elternvertretungen</h1>";

$zugriff = $CMS_RECHTE['Personen']['Elternvertreter sehen'];

if ($zugriff) {
	$dbs = cms_verbinden();
	$liste = "";

	include_once('php/schulhof/seiten/listen/elternvertretungerzeugen.php');
	include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
	$code .= cms_listen_elternvertreter_ausgeben($dbs);

	cms_trennen($dbs);
}
else {
	$code .= cms_meldung_berechtigung();
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
