<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = cms_r("lehrerzimmer.zugriff");
if (!$zugriff) {
	echo cms_meldung_berechtigung();
}
else {
?>

<h1>Sie befinden sich im falschen Netz ...</h1>
<h2>... aber mit einem VPN-Tunnel nicht mehr lange!</h2>

<?php
echo cms_meldung('firewall', '<h4>Firewall</h4><p>Das Datenschutzgesetz schreibt vor, dass bestimmte Daten nicht auf Servern liegen dürfen, auf die auch Schüler oder Eltern Zugriff haben. Deshalb ist diese Einschränkung leider notwendig. Sie können dennoch auch von zu Hause auf die Daten im Lehrerzimmer zugreifen. Wie Ihnen das gelingt, wird im Folgenden beschrieben.</p>');

}
?>

</div>

<?php
if ($zugriff) {
$vpninfo = array();
$vpninfo['adresse'] = "";
$vpninfo['benutzer'] = "";
$vpninfo['passwort'] = "";
$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') AS inhalt FROM vpn");
if ($sql->execute()) {
	$sql->bind_result($vpnbez, $vpnwert);
	while ($sql->fetch()) {
		$vpninfo[$vpnbez] = $vpnwert;
	}
}
$sql->close();

$CMS_HOSTINGPARTNERIN = "NN";
$CMS_LN_DA = "NN/";

$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') FROM allgemeineeinstellungen WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
$information = "Netze Lehrerserver";
$sql->bind_param("s", $information);
$sql->bind_result($CMS_LN_DA);
$sql->execute(); $sql->fetch();
$information = "Hosting Lehrernetz";
$sql->bind_param("s", $information);
$sql->bind_result($CMS_HOSTINGPARTNERIN);
$sql->execute(); $sql->fetch();

$os = cms_welches_betriebssystem();

$code = "";
$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">";
$code .= "<h3>Schritt I (einmalig pro Gerät):<br>VPN-Client herunterladen, installieren und konfigurieren</h3>";

$code .= "<p>Die verwendete Software ist für alle Plattformen kostenlos und Open Source.</p>";

$code.= "<h4>Stationäre Geräte</h4>";
// Windows
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "Windows") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_win\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_herunterladen_win')\">Anleitung für Windows einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_herunterladen_win\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_win\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_win')\">Anleitung für Windows ausblenden</span></p>";
$code .= "<h3>Windows</h3>";
$code .= "<ol>";
$code .= "<li>Öffnen Sie das Online-Portal der $CMS_HOSTINGPARTNERIN unter <a href=\"".$vpninfo['adresse']."\" target=\"_blank\">".$vpninfo['adresse'].".</a></li>";
$code .= "<li>Sollte der Verbindung nicht automatisch vertraut werden, machen Sie eine Ausnahme. (Erweitert » Ausnahmeregel)</li>";
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table>";
$code .= cms_meldung('warnung', '<h4>Keinesfalls das Passwort ändern!</h4><p>Nach der Anmeldung könnten Sie das Passwort ändern. Bitte tun Sie das nicht. Alle Kollegen teilen sich einen Zugang. Wenn Sie das Kennwort ändern, werfen Sie damit alle anderen aus dem digitalen Lehrerzimmer!</p>');
$code .= "</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnmenue.png');
$code .= "<li><p>In der oberen Menüzeile klicken Sie bitte auf <br>Fernzugriff</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/windows/installieren.png');
$code .= "<li><p>Nun erscheint eine Tabelle mit vier Einträgen. Wählen Sie bitte <b>den obersten</b> Eintrag. Nun wird eine Installationsdatei *.exe heruntergeladen, die bereits die gesammte Konfiguration des VPN-Programms enthält. Melden Sie sich im Online-Portal der $CMS_HOSTINGPARTNERIN ab und installieren Sie dieses Programm. Gegebenenfalls müssen Sie vorher zustimmen, dass Sie diesem Programm vertrauen.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Sobald das Programm fertig installiert ist, ist bereits alles eingerichtet, und Sie können bei Schritt II weiterlesen.</li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_win\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_win')\">Anleitung für Windows ausblenden</span></p>";
$code .= "</div>";

// MacOS
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "MacOS") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_mac\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_herunterladen_mac')\">Anleitung für MacOS einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_herunterladen_mac\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_mac\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_mac')\">Anleitung für MacOS ausblenden</span></p>";
$code .= "<h3>MacOS</h3>";
$code .= "<ol>";
$code .= "<li>Öffnen Sie das Online-Portal der $CMS_HOSTINGPARTNERIN unter <a href=\"".$vpninfo['adresse']."\" target=\"_blank\">".$vpninfo['adresse'].".</a></li>";
$code .= "<li>Sollte der Verbindung nicht automatisch vertraut werden, machen Sie eine Ausnahme. (Erweitert » Ausnahmeregel)</li>";
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table>";
$code .= cms_meldung('warnung', '<h4>Keinesfalls das Passwort ändern!</h4><p>Nach der Anmeldung könnten Sie das Passwort ändern. Bitte tun Sie das nicht. Alle Kollegen teilen sich einen Zugang. Wenn Sie das Kennwort ändern, werfen Sie damit alle anderen aus dem digitalen Lehrerzimmer!</p>');
$code .= "</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnmenue.png');
$code .= "<li><p>In der oberen Menüzeile klicken Sie bitte auf <br>Fernzugriff</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Nun erscheint eine Tabelle mit vier Einträgen. Wählen Sie bitte <b>den dritten</b> Eintrag. Nun wird eine Konfigurationsdatei mit der Endung *.ovpn heruntergeladen. Melden Sie sich im Online-Portal der $CMS_HOSTINGPARTNERIN ab.</li>";
$code .= "<li>Für MacOS wird leider keine Software mitgeliefert. Die App <b>Tunnelblick</b> ist geeignet. Weitere sind bestimmt ebenfalls möglich. Laden Sie also bitte eine geeignete App (z.B. <a href=\"https://tunnelblick.net/downloads.html\">Tunnelblick</a> - nicht im App Store erhältlich) herunter. Im Folgenden wird davon ausgegangen, dass Tunnelblick verwendet wird.</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/konfig.png');
$code .= "<li><p>Starten Sie Tunnelblick. Auf der linken Seite finden Sie eine Übersicht aller VPN-Konfigurationen. In diesem Fall ist sie leer. Ziehen Sie die heruntergeladene *.ovpn-Datei per Drag'n'Drop in dieses Feld.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/konfigerlauben.png');
$code .= "<li><p>MacOS fordert Sie auf, die Erlaubnis zur Erstellung der VPN-Konfiguration zu erteilen. Bitte folgen Sie dieser Aufforderung durch Eingabe Ihres Gerätepassworts.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/konfigfertig.png');
$code .= "<li><p>Nachdem der Vorgang abgeschlossen ist, werden Sie oben rechts am Bildrand darüber informiert. Tunnelblick ist eingerichtet und bereit für Schritt II.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_mac\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_mac')\">Anleitung für MacOS ausblenden</span></p>";
$code .= "</div>";

// Linux
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "Linux") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_tux\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_herunterladen_tux')\">Anleitung für Linux einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_herunterladen_tux\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_tux\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_tux')\">Anleitung für Linux ausblenden</span></p>";
$code .= "<h3>Linux</h3>";
$code .= "<ol>";
$code .= "<li>Öffnen Sie das Online-Portal der $CMS_HOSTINGPARTNERIN unter <a href=\"".$vpninfo['adresse']."\" target=\"_blank\">".$vpninfo['adresse'].".</a></li>";
$code .= "<li>Sollte der Verbindung nicht automatisch vertraut werden, machen Sie eine Ausnahme. (Erweitert » Ausnahmeregel)</li>";
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table>";
$code .= cms_meldung('warnung', '<h4>Keinesfalls das Passwort ändern!</h4><p>Nach der Anmeldung könnten Sie das Passwort ändern. Bitte tun Sie das nicht. Alle Kollegen teilen sich einen Zugang. Wenn Sie das Kennwort ändern, werfen Sie damit alle anderen aus dem digitlen Lehrerzimmer!</p>');
$code .= "</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnmenue.png');
$code .= "<li><p>In der oberen Menüzeile klicken Sie bitte auf <br>Fernzugriff</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Nun erscheint eine Tabelle mit vier Einträgen. Wählen Sie bitte <b>den dritten</b> Eintrag. Nun wird eine Konfigurationsdatei mit der Endung *.ovpn heruntergeladen. Speichern Sie diese Datei an einem Ort, an dem Sie sie wiederfinden! Melden Sie sich im Online-Portal der $CMS_HOSTINGPARTNERIN ab.</li>";
$code .= "<li><p>Für Linux wird leider keine Software mitgeliefert. Führen Sie zunächst im Terminal die folgende Anweisung aus:</p><p class=\"cms_konsole\">sudo apt-get install openvpn</p></li>";
$code .= "<li><p>OpenVPN ist nun installiert und bereit für Schritt II.</p></li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_tux\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_tux')\">Anleitung für Linux ausblenden</span></p>";
$code .= "</div>";



$code.= "<h4>Mobile Geräte</h4>";
// iOS
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "iOS") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_ios\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_herunterladen_ios')\">Anleitung für iOS einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_herunterladen_ios\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_aui\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_ios')\">Anleitung für iOS ausblenden</span></p>";$code .= "<h3>iOS</h3>";
$code .= "<ol>";
$code .= "<li>Öffnen Sie das Online-Portal der $CMS_HOSTINGPARTNERIN unter <a href=\"".$vpninfo['adresse']."\" target=\"_blank\">".$vpninfo['adresse'].".</a></li>";
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table>";
$code .= cms_meldung('warnung', '<h4>Keinesfalls das Passwort ändern!</h4><p>Nach der Anmeldung könnten Sie das Passwort ändern. Bitte tun Sie das nicht. Alle Kollegen teilen sich einen Zugang. Wenn Sie das Kennwort ändern, werfen Sie damit alle anderen aus dem Schulhof!</p>');
$code .= "</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnmenue.png');
$code .= "<li><p>In der oberen Menüzeile klicken Sie bitte auf <br>Fernzugriff</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnauswahl.png');
$code .= "<li><p>Zunächst wird eine App benötigt, mit der der Fernzugriff erfolgen kann. Um eine geeignete App zu installieren, wählen Sie bitte im <b>vierten Eintrag</b> den Link <b>App Store</b> und installieren Sie die App, zu der Sie geführt werden (OpenVPN Connect).</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Anschließend wählen Sie bitte im vierten Eintrag im Online-Portal der $CMS_HOSTINGPARTNERIN <b>Intallieren</b>. Nun wird eine Konfigurationsdatei heruntergeladen. Diese Datei öffnen Sie mit der eben installieren App.</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/konfig.png');
$code .= "<li><p>Im nächsten Schritt werden Sie gefragt, ob die Konfigurationsdatei ihren VPN-Verbindungen hinzugefügt werden soll. Bestätigen Sie das mit dem <b>grünen +</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/konfigerlauben.png');
$code .= "<li><p>Daraufhin erscheint eine Meldung, in der Sie erlauben müssen, dass die Verbindung hinzugefügt wird. Bitte geben Sie Ihre Erlaubnis (Allow), entweder durch Eingabe des Gerätepassworts oder durch Ihren Fingerabdruck.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/konfigfertig.png');
$code .= "<li><p>Die VPN-Verbindung wurde eingerichtet. Um die Verbindung herzustellen lesen Sie bitte bei Schritt II weiter.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_ios\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_ios')\">Anleitung für iOS ausblenden</span></p>";
$code .= "</div>";

// Android
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "Android") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_and\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_herunterladen_and')\">Anleitung für Android einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_herunterladen_and\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_and\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_and')\">Anleitung für Android ausblenden</span></p>";$code .= "<h3>Android</h3>";
$code .= "<ol>";
$code .= "<li>Öffnen Sie das Online-Portal der $CMS_HOSTINGPARTNERIN unter <a href=\"".$vpninfo['adresse']."\" target=\"_blank\">".$vpninfo['adresse'].".</a></li>";
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table>";
$code .= cms_meldung('warnung', '<h4>Keinesfalls das Passwort ändern!</h4><p>Nach der Anmeldung könnten Sie das Passwort ändern. Bitte tun Sie das nicht. Alle Kollegen teilen sich einen Zugang. Wenn Sie das Kennwort ändern, werfen Sie damit alle anderen aus dem Schulhof!</p>');
$code .= "</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnmenue.png');
$code .= "<li><p>In der oberen Menüzeile klicken Sie bitte auf <br>Fernzugriff</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/vpnauswahl.png');
$code .= "<li><p>Zunächst wird eine App benötigt, mit der der Fernzugriff erfolgen kann. Um eine geeignete App zu installieren, wählen Sie bitte im <b>vierten Eintrag</b> den Link <b>Google Play</b> und installieren Sie die App, zu der Sie geführt werden (OpenVPN für Android).</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/installieren.png');
$code .= "<p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/konfigimportieren.png');
$code .= "<li><p>Anschließend wählen Sie bitte im vierten Eintrag im Online-Portal der $CMS_HOSTINGPARTNERIN <b>Intallieren</b>. Nun wird eine Konfigurationsdatei heruntergeladen. Diese Datei öffnen Sie direkt nach dem Download und ein etwas kryptisches Fenster erscheint, das Sie dennoch einfach mit dem Haken oben rechts bestätigen können. Damit wird die Konfigurationsdatei hinzugefügt.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Die VPN-Verbindung wurde eingerichtet. Um die Verbindung herzustellen lesen Sie bitte bei Schritt II weiter.</li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_herunterladen_and\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_herunterladen_and')\">Anleitung für Android ausblenden</span></p>";
$code .= "</div>";

$code .= cms_meldung('warnung', '<h4>Kennwort darf nicht gespeichert werden!</h4><p>Bitte bachten Sie, dass das Kennwort für den Zugang ins Lehrernetz mit dem VPN-Programm nicht auf Rechnern gespeichert werden darf, die teilweise oder ganz privat genutzt werden.</p>');

$code .= "</div></div>";





// SCHRITT II
$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">";
$code .= "<h3>Schritt II (bei jeder Benutzung):<br>VPN-Client starten</h3>";

$code.= "<h4>Stationäre Geräte</h4>";
// Windows
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "Windows") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_win\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_nutzen_win')\">Anleitung für Windows einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_nutzen_win\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_win\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_win')\">Anleitung für Windows ausblenden</span></p>";
$code .= "<h3>Windows</h3>";
$code .= "<ol>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/windows/verbinden.png');
$code .= "<li><p>In der Windowstaskleiste befindet sich eine Ampel. Möglicherweise ist diese Ampel auch ausgeblendet, wie im Bild. Klicken Sie in diesem Fall zunächst auf den Pfeil nach oben, um weitere Symbole in der Taskleiste anzuzeigen. Um die VPN-Verbindung zu aktivieren klicken Sie bitte mit der rechten Maustaste auf die Ampel und wählen <b>Verbinden</b>.<p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/windows/anmelden.png');
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/windows/verbunden.png');
$code .= "<li><p>Nun beginnt der Einwählvorgang. Er ist abgeschlossen, wenn die Ampel auf grün umschaltet. Den aktuellen Status können Sie verfolgen indem Sie mit dem Mauszeiger über dem Ampelsymbol verweilen.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Um die Verbindung wieder zu trennen, klicken Sie in der Taskleiste auf das Ample-Icon und wählen <b>Trennen</b> aus.</li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_win\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_win')\">Anleitung für Windows ausblenden</span></p>";
$code .= "</div>";

// MacOS
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "MacOS") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_mac\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_nutzen_mac')\">Anleitung für MacOS einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_nutzen_mac\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_mac\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_mac')\">Anleitung für MacOS ausblenden</span></p>";
$code .= "<h3>MacOS</h3>";
$code .= "<ol>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/aktivieren.png');
$code .= "<li><p>Starten Sie Tunnelblick aus dem Launchpad oder klicken Sie oben rechts in der Menüleiste auf das Tunnelblick-Symbol.<p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/legitimieren.png');
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/aktiv.png');
$code .= "<li><p>Nun beginnt der Einwählvorgang. Er ist abgeschlossen, wenn sich oben rechts auf dem Desktop folgendes Bild zeigt:</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/macos/meldung.png');
$code .= "<li><p>Es ist möglich, dass eine Warnmeldung erscheint, die wie die Folgende aussieht. Sie kann ignoriert und weggeklickt werden, ebenso wie eine Meldung die besagt, dass die Verbindung möglicherweise wegen unerwünschtem Code nicht hergestellt werden kann.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Um die Verbindung wieder zu trennen, klicken Sie in der Menüzeile auf das Tunnelblick-Icon, wählen das lehrer-bg-Profil aus, und trennen es.</li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_mac\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_mac')\">Anleitung für MacOS ausblenden</span></p>";
$code .= "</div>";

// Linux
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "Linux") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_tux\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_nutzen_tux')\">Anleitung für Linux einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_nutzen_tux\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_tux\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_tux')\">Anleitung für Linux ausblenden</span></p>";
$code .= "<h3>Linux</h3>";
$code .= "<ol>";
$code .= "<li><p>Starten Sie den Terminal und geben Sie den folgenden Befehl ein (Achtung, Sie benötigen den Pfad zur heruntergeladenen Datei aus Schritt I):</p><p class=\"cms_konsole\">sudo openvpn --config PFAD_ZUR_DATEI.ovpn</p></li>";
$code .= "<li><p>Daraufhin werden Sie aufgefordert Ihr Passwort als Systemadministrator und dann die Zugangsdaten zum VPN-Netz einzugeben:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table><p class=\"cms_konsole\"><b>Enter Auth Username:</b> ********<br><b>Enter Auth Password:</b> ********</p></li>";
$code .= "<li><p>Die Verbindung wird nun hergestellt.</p><p class=\"cms_konsole\">Initialization Sequence Completed</p></li>";
$code .= "<li><p>Um die Verbindung wieder zu trennen, beenden Sie den aktuellen Prozess im Terminal mit <span class=\"cms_taste\">Strg</span> + <span class=\"cms_taste\">C</span>.</p><p>Alternativ kann auch in einem anderen Terminal-Tab oder -Fenster der folgende Befehl ausgeführt werden:</p><p class=\"cms_konsole\">sudo killall openvpn</p></li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_tux\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_tux')\">Anleitung für Linux ausblenden</span></p>";
$code .= "</div>";


$code.= "<h4>Mobile Geräte</h4>";
// iOS
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "iOS") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_ios\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_nutzen_ios')\">Anleitung für iOS einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_nutzen_ios\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_aui\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_ios')\">Anleitung für iOS ausblenden</span></p>";$code .= "<h3>iOS</h3>";
$code .= "<ol>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/appicon.png');
$code .= "<li><p>Öffnen Sie die OpenVPN-App</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/verbinden.png');
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/verbindungerlauben.png');
$code .= "<li><p>Erlauben Sie, dass der Einwahlvorgang beginnt (Yes).</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/ios/verbunden.png');
$code .= "<li><p>Wenn Sie verbunden sind, erscheint das folgende Bild:</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p><p>Nun können Sie wieder in den Browser zurückkehren und weitersurfen.</p></li>";
$code .= "<li>Um die Verbindung wieder zu trennen, öffnen Sie die App erneut. Tippen Sie auf den Schieber unter <b>Connected</b> und die Verbindung wird getrennt.</li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_ios\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_ios')\">Anleitung für iOS ausblenden</span></p>";
$code .= "</div>";

// Android
$stylebutton = "display: block;";
$stylefeld = "display: none;";
if ($os == "Android") {
	$stylebutton = "display: none;";
	$stylefeld = "display: block;";
}
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_and\" style=\"$stylebutton\"><span class=\"cms_button\" onclick=\"cms_einblendebox_ein('vpn_nutzen_and')\">Anleitung für Android einblenden</span></p>";
$code .= "<div class=\"cms_einblendebox\" id=\"cms_einblendebox_vpn_nutzen_and\" style=\"$stylefeld\">";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_and\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_and')\">Anleitung für Android ausblenden</span></p>";$code .= "<h3>Android</h3>";
$code .= "<ol>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/appicon.png');
$code .= "<li><p>Öffnen Sie die OpenVPN-App</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/verbindungsanfrage.png');
$code .= "<li><p>Wählen Sie das lehrer-bg-VPN-Profil aus, indem Sie darauf tippen. Bestätigen Sie die auftauchende Meldung.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/verbinden.png');
$code .= "<li><p>Melden Sie sich mit den folgenden Zugangsdaten an:</p><table class=\"cms_liste\"><tr><th>Benutzername:</th><td>".$vpninfo['benutzer']."</td></tr><tr><th>Passwort: </th><td>".$vpninfo['passwort']."</td></tr></table><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/verbunden.png');
$code .= "<li><p>Nun wird ein Statusfenster mit viel Text angezeigt. Wichtig ist nur die erste Zeile. <b>Verbunden: SUCCESS</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "<li>Kehren Sie nun in Ihren Browser zurück, um weiter zu surfen.</li>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/trennen1.png');
$code .= "<li><p>Zur Abmeldung vom VPN-Netz öffnen Sie die OpenVPN-App erneut, tippen ggf. auf das lehrer-bg-Profil und dort oben rechts auf die drei Punkte. Wählen Sie <b>VPN-Verbindung trennen</b>,</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/trennen2.png');
$code .= "<p>bestätigen Sie das Trennen</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p>";
$bilderdaten = cms_generiere_bilddaten('dateien/schulhof/vpn/bilder/android/trennen3.png');
$code .= "<p>und schließlich ändert sich die erste Zeile des Verbindungsprotokolls in <b>No process running</b>.</p><p class=\"cms_bild\"><img src=\"$bilderdaten\"></p></li>";
$code .= "</ol>";
$code .= "<p id=\"cms_einblendeknopf_vpn_nutzen_and\"><span class=\"cms_button\" onclick=\"cms_einblendebox_aus('vpn_nutzen_and')\">Anleitung für Android ausblenden</span></p>";
$code .= "</div>";

$code .= "<p>Nachdem sich der Belken unten gelb gefärbt hat, kann auf die Lehrerdaten zugegriffen werden. Klicken Sie dazu einfach auf den gewünschten Link und die gesicherten Inhalte werden angezeigt.</p>";

$code .= "</div></div>";





$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">";
$code .= "<h3>Evtl. Schritt III (je nach Browser und Einstellung):</h3>";
$code .= "<h4>Mit VPN eingewählt, aber der untere Balken in diesem Fenster wird nicht gelb?</h4>";
$code .= "<p>Wir arbeiten mit der Stadt gerade daran, dass dies nicht vorkommt. Bis auf Weiteres lässt sich das Problem aber relativ einfach lösen. Klicken Sie auf den nachfolgenden Link und akzeptieren Sie das Zertifikat.</p>";
$code .= "<p><a class=\"cms_button\" target=\"_blank\" href=\"$CMS_LN_DA"."index.php\">zum Zertifikiat</a></p>";
$code .= "<p>Es ist gut möglich, dass ihr Browser dieses Zertifikat für nicht vertraueswürdig hält, obwohl es das ist, da es nicht international registriert wurde, sondern »nur« von der $CMS_HOSTINGPARTNERIN erstellt wurde.</p>";

$code .= "</div></div>";

echo $code;
}
?>

<div class="cms_clear"></div>
