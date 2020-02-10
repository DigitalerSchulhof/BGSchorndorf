<?php
$code = "";

if ($CMS_ANGEMELDET) {
	$code .= "<h2>Hauptmenü</h2>";
	$code .= cms_appmenue();
}
else {
	$meldung = "<h4>Nicht angemeldet</h4><p>Dieses Nutzerkonto ist im Moment nicht angemeldet. Das kann mehrere Gründe haben:</p>";
	$meldung .= "<ul><li>Die Abmeldung wurde aktiv herbeigeführt. Es ist ein Neustart der App erforderlich.</li>";
	$meldung .= "<li>Die App war zu lange inaktiv und eine Abmeldung ist aus Sicherheitsgründen erfolgt. Es ist ein Neustart der App erfoderlich.</li>";
	$meldung .= "<li>Zwischenzeitlich gab es eine Anmeldung mit diesem Konto auf einem anderen Gerät. Es ist ein Neustart der App erfoderlich.<br><b>Achtung!</b> Andere Sitzungen werden dadurch beendet!</li>";
	$meldung .= "<li>Die im Profil eingegebenen Benutzerdaten sind nicht korrekt.</li></ul>";
	$code .= "<div id=\"cms_appmeldung\">";
	$code .= cms_meldung("info", $meldung);
	$code .= "</div>";
}

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
