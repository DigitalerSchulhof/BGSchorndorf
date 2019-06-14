<?php
$ordnergroesse = cms_dateisystem_ordner_info("dateien/schulhof/personen/$CMS_BENUTZERID/postfach");

if ($CMS_BENUTZERART == 's') {$POSTLIMIT = 100*1024*1024;}
else if ($CMS_BENUTZERART == 'l') {$POSTLIMIT = 1024*1024*1024;}
else if ($CMS_BENUTZERART == 'v') {$POSTLIMIT = 1024*1024*1024;}
else if ($CMS_BENUTZERART == 'e') {$POSTLIMIT = 10*1024*1024;}
else {$POSTLIMIT = 0;}

$tabellen[0] = "posteingang_$CMS_BENUTZERID";
$tabellen[1] = "postausgang_$CMS_BENUTZERID";
$tabellen[2] = "postentwurf_$CMS_BENUTZERID";
$tabellen[3] = "postgetaggedeingang_$CMS_BENUTZERID";
$tabellen[4] = "postgetaggedausgang_$CMS_BENUTZERID";
$tabellen[5] = "postgetaggedentwurf_$CMS_BENUTZERID";
$tabellen[6] = "posttags_$CMS_BENUTZERID";

$dbgroesse = cms_db_tabellengroesse($CMS_DBP_DB, $tabellen);


$POSTBELEGT = $ordnergroesse['groesse'] + $dbgroesse;

$POSTPROZENT = min($POSTBELEGT / $POSTLIMIT*100, 100);

echo "<div class=\"cms_anteilbalken_aussen\"><div class=\"cms_anteilbalken_innen\" style=\"width: $POSTPROZENT%;\"></div></div>";
if ($POSTPROZENT == 100) {
	echo cms_meldung('fehler', '<h4>Limit erreicht</h4><p>Das Speicherlimit wurde erreicht. Es können keine neuen Nachrichten mehr versendet werden. Sollte dieser Zustand länger bestehen, werden alte Nachrichten aus allen Ordnern gelöscht, bis wieder 50% des Speicherlatzes zur Verfügung steht!</p>');
}
else {
	echo "<p class=\"cms_anteilbalken_notiz\">".cms_groesse_umrechnen($POSTBELEGT)." von ".cms_groesse_umrechnen($POSTLIMIT)." belegt - ".cms_groesse_umrechnen($POSTLIMIT-$POSTBELEGT)." verfügbar</p>";
}

?>

<ul class="cms_uebersicht">
	<li>
		<a class="cms_uebersicht_postfach_schreiben" onclick="cms_schulhof_postfach_nachricht_vorbereiten ('neu', '', '')">
			<h3>Neue Nachricht</h3>
			<p>Neue Nachricht verfassen</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_postfach_eingang" href="Schulhof/Nutzerkonto/Postfach/Posteingang">
			<h3>Posteingang</h3>
			<?php
			// Zählen wie viele Nachrichten vorhanden
			$dbp = cms_verbinden('p');

			$sql = "SELECT AES_DECRYPT(gelesen, '$CMS_SCHLUESSEL') AS gelesen, COUNT(gelesen) AS anzahl FROM posteingang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-' GROUP BY gelesen;";

			//echo $sql;

			$anzahl['-'] = 0;
			$anzahl[1] = 0;

			if ($anfrage = $dbp->query($sql)) {
				while ($daten = $anfrage->fetch_assoc()) {
					$anzahl[$daten['gelesen']] = $daten['anzahl'];
				}
				$anfrage -> free();
			}

			$gesamt = $anzahl['-'] + $anzahl[1];

			$text = "Nachrichten";
			if ($gesamt == 1) {
				$text = "Nachricht";
			}

			echo "<p><b>".$anzahl['-']." neue</b> von $gesamt $text</p>";
			?>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_postfach_entwurf" href="Schulhof/Nutzerkonto/Postfach/Entwürfe">
			<h3>Entwürfe</h3>
			<?php

			$sql = "SELECT COUNT(*) AS anzahl FROM postentwurf_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-'";

			$anzahl = 0;

			if ($anfrage = $dbp->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()){
					$anzahl = $daten['anzahl'];
				}
				$anfrage -> free();
			}

			$text = "Nachrichten";
			if ($anzahl == 1) {
				$text = "Nachricht";
			}

			echo "<p>$anzahl $text</p>";
			?>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_postfach_ausgang" href="Schulhof/Nutzerkonto/Postfach/Postausgang">
			<h3>Postausgang</h3>
			<?php

			$sql = "SELECT COUNT(*) AS anzahl FROM postausgang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-'";

			$anzahl = 0;

			if ($anfrage = $dbp->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()){
					$anzahl = $daten['anzahl'];
				}
				$anfrage -> free();
			}

			$text = "Nachrichten";
			if ($anzahl == 1) {
				$text = "Nachricht";
			}

			echo "<p>$anzahl $text</p>";
			?>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_postfach_papierkorb" href="Schulhof/Nutzerkonto/Postfach/Papierkorb">
			<h3>Papierkorb</h3>

			<?php

			$sql = "SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM posteingang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '1') UNION ALL (SELECT COUNT(*) AS anzahl FROM postentwurf_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '1') UNION ALL ";
			$sql.= "(SELECT COUNT(*) AS anzahl FROM postausgang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '1')) AS nachrichtenpapierkorb";

			$anzahl = 0;

			if ($anfrage = $dbp->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()){
					$anzahl = $daten['anzahl'];
				}
				$anfrage -> free();
			}

			$text = "Nachrichten";
			if ($anzahl == 1) {
				$text = "Nachricht";
			}

			echo "<p>$anzahl $text</p>";

			cms_trennen($dbp);
			?>
		</a>
	</li>
</ul>

<h3>Einstellungen</h3>
<ul class="cms_uebersicht">
	<li>
		<a class="cms_uebersicht_postfach_tags" href="Schulhof/Nutzerkonto/Postfach/Tags">
			<h3>Tags</h3>
			<p>Nachrichten nach Themen gruppieren</p>
		</a>
	</li>
	<!--<li>
		<a class="cms_uebersicht_postfach_listen" href=".">
			<h3>Maillisten</h3>
			<p>Personen in Listen gruppieren</p>
		</a>
	</li>-->
	<li>
		<a class="cms_uebersicht_postfach_signatur" href="Schulhof/Nutzerkonto/Postfach/Signatur">
			<h3>Signatur</h3>
			<p>Unter jeder Mail</p>
		</a>
	</li>
	<li>
		<a class="cms_uebersicht_postfach_einstellungen" href="Schulhof/Nutzerkonto/Einstellungen">
			<h3>Einstellungen</h3>
			<p>Benachrichtigungen, Löschfristen</p>
		</a>
	</li>
</ul>
