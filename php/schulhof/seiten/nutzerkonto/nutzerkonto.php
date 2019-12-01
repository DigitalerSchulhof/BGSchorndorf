<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php

echo "<h1>Willkommen $CMS_BENUTZERVORNAME $CMS_BENUTZERNACHNAME!</h1>";

include_once('php/schulhof/seiten/termine/termineausgeben.php');
include_once('php/schulhof/seiten/notifikationen/notifikationen.php');

// Prfüfen, ob ein neues Schuljahr zur Verfügung steht
$dbs = cms_verbinden('s');
$jetzt = time();
$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre WHERE beginn <= $jetzt AND ende >= $jetzt";
if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
	if ($daten = $anfrage->fetch_assoc()) {
		if ($daten['id'] != $CMS_BENUTZERSCHULJAHR) {
			$button = "<span class=\"cms_button\" onclick=\"cms_schulhof_nutzerkonto_schuljahr_einstellen(".$daten['id'].")\">".$daten['bezeichnung']."</span>";
			echo cms_meldung('warnung', '<p>Es ist nicht das aktuelle Schuljahr ausgewählt. Damit die Gruppen und Ansprechpartner aktuell sind, muss das Schuljahr <b>'.$daten['bezeichnung'].'</b> ausgewählt werden:</p><p>'.$button.'</p>');
		}
	}
	$anfrage->free();
}

/* Direkt nach der Anmeldung letzten Login ausgeben */
if (isset($_SESSION['LETZTENLOGINANZEIGEN'])) {$letzterlogin = $_SESSION['LETZTENLOGINANZEIGEN'];} else {$letzterlogin = '-';}

if ($letzterlogin != '-') {
	if ($letzterlogin != 0) {
		$tag = date ("d", $letzterlogin);
		$monat = date ("m", $letzterlogin);
		$jahr = date ("Y", $letzterlogin);
		$zeit = date ("H:i", $letzterlogin);
		echo "<p>Die letzte Anmeldung erfolgte am <b>$tag. ".cms_monatsnamekomplett($monat)." $jahr um $zeit Uhr</b>. Falsch? <a href=\"Schulhof/Nutzerkonto/Mein_Profil/Identitätsdiebstahl\"><b>Identitätsdiebstahl melden</b> und Passwort ändern!</a></p>";

		$_SESSION['LETZTENLOGINANZEIGEN'] = '-';
	}
}

if (isset($_SESSION['PASSWORTTIMEOUT'])) {
	if ($_SESSION['PASSWORTTIMEOUT'] != '0') {
		echo cms_meldung('info', "<h4>Begrenzte Gültigkeit des Passwortes</h4><p>Das aktuelle Passwort ist nur für begrenzte Zeit gültig. Es sollte unverzüglich geändert werden, um eine künftige Anmeldung zu gewährleisten.</p><p><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Mein_Profil/Passwort_ändern\">Passwort ändern</a></p>");
	}
}



$neuigkeiten = "";

// Ungelesene Nachrichten auslesen
$db = cms_verbinden('ü');
$sql = "$CMS_DBP_DB.posteingang_$CMS_BENUTZERID.id AS id, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, zeit, erstellt";
$sql = "SELECT $sql FROM $CMS_DBP_DB.posteingang_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON $CMS_DBS_DB.personen.id = $CMS_DBS_DB.nutzerkonten.id WHERE gelesen = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') AND papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') AND empfaenger = $CMS_BENUTZERID";
if ($anfrage = $db->query($sql)) {	// Safe weil keine Eingabe
	while ($daten = $anfrage->fetch_assoc()) {
		if ($daten['zeit'] > $daten['erstellt']) {$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);}
		else {$anzeigename = "Nutzerkonto existiert nicht mehr";}
		$nachricht = explode(' ', $daten['nachricht']);
		if (count($nachricht) > 20) {$nachricht = array_splice($nachricht,0,20);}
		$nachricht = strip_tags(implode(' ', $nachricht));
		$betreffevent = cms_texttrafo_e_event($daten['betreff']);
		$tag = cms_tagname(date ("w", $daten['zeit']));
		$datum = date ("d.m.Y", $daten['zeit']);
		$uhrzeit = date ("H:i", $daten['zeit']);
		$lesen = "cms_postfach_nachricht_lesen('eingang', '".$anzeigename."', '".$betreffevent."', '".$datum."', '".$uhrzeit."', '".$daten['id']."')";
		$neuigkeiten .= "<li class=\"cms_neuigkeit cms_postneuigkeit\" onclick=\"$lesen\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/nachricht.png\"></span>";
		$neuigkeiten .= "<span class=\"cms_neuigkeit_inhalt\"><h4>Postfach<br>Neue Nachricht</h4>";
		$neuigkeiten .= "<p>".$daten['betreff']."</p><p class=\"cms_neuigkeit_vorschau\">$tag $datum um $uhrzeit von $anzeigename</p>";
		$neuigkeiten .= "<p class=\"cms_neuigkeit_vorschau\">".$nachricht."</p>";
		$neuigkeiten .= "</span></li>";
	}
	$anfrage->free();
}
if (strlen($neuigkeiten) > 0) {$neuigkeiten = "<ul class=\"cms_neuigkeiten\">$neuigkeiten</ul>";}
cms_trennen($db);

// Notifikationen ausgeben
$notifikationen = cms_notifikationen_ausgeben($dbs, $CMS_BENUTZERID);
if (strlen($notifikationen) > 0) {
	$notifikationen = "<ul class=\"cms_neuigkeiten\">$notifikationen</ul>";
	$notifikationen .= "<p><span class=\"cms_button_nein\" onclick=\"cms_notifikationen_loeschen()\">Alle Neuigkeiten schließen</span></p>";
}
$neuigkeiten .= $notifikationen;
if (strlen($neuigkeiten) > 0) {echo "<h2>Neuigkeiten</h2>$neuigkeiten";}
?>
</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">
<?php
if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternpersoenlich.php');
	$vplan = cms_vertretungsplan_extern_persoenlich();
	echo "<h2>Vertretungsplan</h2>";
	if (strlen($vplan) > 0) {
		echo $vplan;
	}
	else {echo "<p class=\"cms_notiz\">Aktuell keine Vertretungen.</p>";}
}
else {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
	$vplan = cms_vertretungsplan_persoenlich($dbs);

	echo "<h2>Mein Tag</h2>";
	if ((strlen($vplan) > 0) || (strlen($vplan) > 0)) {
		echo $vplan;
	}
	else {echo "<p class=\"cms_notiz\">Aktuell keine Vertretungen.</p>";}
}
?>
</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">
<h2>Anstehende Termine</h2>
<?php
$termine = "";
include_once('php/schulhof/seiten/termine/termineausgeben.php');
$termine = cms_nachste_termine_ausgeben($_SESSION['BENUTZERUEBERSICHTANZAHL']);
if (strlen($termine) == 0) {
	echo "<p class=\"cms_notiz\">Aktuell keine Termine</p>";
}
else {
	echo "<ul class=\"cms_terminuebersicht\">".$termine."</ul>";
}
?>

</div>
</div>



<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
$code = "<h2>Mitgliedschaften</h2>";
include_once('php/schulhof/seiten/gruppen/mitgliedschaftenausgeben.php');
foreach ($CMS_GRUPPEN as $g) {
	$gruppencode = cms_gruppen_mitgliedschaften_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
	if (strlen($gruppencode) > 0) {$code .= "<h3>$g</h3>".str_replace('<ul>', '<ul class="cms_aktionen_liste">', $gruppencode);}
}
echo $code;
?>

</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(url, '$CMS_SCHLUESSEL'), AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM favoritseiten WHERE person = ?) AS x ORDER BY id";
$sql = $dbs->prepare($sql);
$sql->bind_param("i", $CMS_BENUTZERID);
$sql->execute();
$sql->bind_result($fid, $furl, $fbez);
$fav = "";
while($sql->fetch()) {
	$fav .= "<li><a class=\"cms_button\" href=\"$furl\">".$fbez."</a></li> ";
}
if (strlen($fav) > 0) {echo "<h2>Favoriten</h2><ul class=\"cms_aktionen_liste\">$fav</ul>";}

?>

<div id="cms_aktivitaet_out_profil"><div id="cms_aktivitaet_in_profil"></div></div>
<p class="cms_notiz" id="cms_aktivitaet_text_profil">Berechnung läuft ...</p>
<ul class="cms_aktionen_liste">
	<li><span class="cms_button_ja" onclick="cms_timeout_verlaengern()">Verlängern</span></li>
	<li><span class="cms_button_nein" onclick="cms_abmelden_frage();">Abmelden</span></li>
</ul>
<?php
$aktionen = "";
if ($CMS_RECHTE['Website']['Termine anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_termin('".implode('/', $CMS_URL)."')\">+ Neuer öffentlicher Termin</span></li> ";
}
if ($CMS_RECHTE['Website']['Blogeinträge anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_blogeintrag('".implode('/', $CMS_URL)."')\">+ Neuer öffentlicher Blogeintrag</span></li> ";
}
if ($CMS_RECHTE['Website']['Galerien anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neue_galerie('".implode('/', $CMS_URL)."')\">+ Neue öffentliche Galerie</span></li> ";
}
/*if ($CMS_RECHTE['Persönlich']['Termine anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_persoenlicher_termin('".implode('/', $CMS_URL)."')\">+ Neuer persönlicher Termin</span></li> ";
}*/
if (strlen($aktionen) > 0) {
	echo "<h2>Aktionen</h2><ul class=\"cms_aktionen_liste\">$aktionen</ul>";
}

$sonderrollencodeverwaltung = cms_sonderrollen_generieren();
if (strlen($sonderrollencodeverwaltung) != 0) {
	$sonderrollencode = "<h2>Aufgaben</h2>";
	$sonderrollencode .= "<ul class=\"cms_aktionen_liste\">".$sonderrollencodeverwaltung."</ul>";
	echo $sonderrollencode;
}

if ($CMS_RECHTE['Persönlich']['Notizen anlegen']) {
	$code = "<h2>Notizen</h2>";
	$notizen = "";
	$sql = "SELECT AES_DECRYPT(notizen, '$CMS_SCHLUESSEL') AS notizen FROM nutzerkonten WHERE id = $CMS_BENUTZERID";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			$notizen = $daten['notizen'];
		}
		$anfrage->free();
	}

	if (strlen($notizen) == 0) {$zusatzklasse = " cms_notizzettelleer";} else {$zusatzklasse = "";}
	$code .= "<p><textarea id=\"cms_persoenlichenotizen\" class=\"cms_notizzettel$zusatzklasse\">$notizen</textarea></p>";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_persoenliche_notizen_speichern()\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></p>";
	echo $code;
}
?>

</div>
</div>

<div class="cms_clear"></div>
