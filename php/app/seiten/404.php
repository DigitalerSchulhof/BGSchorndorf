<?php
$code = "";

if (strpos($_SERVER['HTTP_USER_AGENT'], "AppAndroid") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
  $code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden. Neuerungen umfassen vereinfachte Navigation, Darkmode, direktes Öffnen von Seiten, erhöhte Stabilität und mehr!<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

$code .= cms_meldung('info', '<h4>Dienst nicht verfügbar</h4><p>Dieser Dienst steht nicht zur Verfügung.</p>');

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
