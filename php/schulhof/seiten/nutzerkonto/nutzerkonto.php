<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php

echo "<h1>".s("schulhof.seite.nutzerkonto.willkommen", array("%name%" => cms_generiere_anzeigename($CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL)))."</h1>";
include_once('php/schulhof/seiten/termine/termineausgeben.php');
include_once('php/schulhof/seiten/notifikationen/notifikationen.php');

// Prfüfen, ob ein neues Schuljahr zur Verfügung steht

$dbs = cms_verbinden('s');
$jetzt = time();
$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre WHERE beginn <= $jetzt AND ende >= $jetzt";
if ($anfrage = $dbs->query($sql)) {
	if ($daten = $anfrage->fetch_assoc()) {
		if ($daten['id'] != $CMS_BENUTZERSCHULJAHR) {
			$button = "<span class=\"cms_button\" onclick=\"cms_schulhof_nutzerkonto_schuljahr_einstellen(".$daten['id'].")\">".s("schulhof.seite.nutzerkonto.meldung.schuljahr.aktion.aendern", array("%schuljahr%" => $daten['bezeichnung']))."</span>";
			echo cms_meldung("warnung", "schulhof.seite.nutzerkonto.meldung.schuljahr", array("%schuljahr%" => $daten['bezeichnung'], "%aendern%" => $button));
		}
	}
	$anfrage->free();
}

/* Direkt nach der Anmeldung letzten Login ausgeben */
if (isset($_SESSION['LETZTENLOGINANZEIGEN'])) {$letzterlogin = $_SESSION['LETZTENLOGINANZEIGEN'];} else {$letzterlogin = '-';}

if ($letzterlogin != '-') {
	if ($letzterlogin != 0) {
		echo "<p>".s("schulhof.seite.nutzerkonto.letzterlogin", array_merge(array("%identitätsdiebstahllink%" => u("schulhof.seiten.nutzerkonto.identitaetsdiebstahl")), cms_zeitarray($letzterlogin)));
		$_SESSION['LETZTENLOGINANZEIGEN'] = '-';
	}
}

if (isset($_SESSION['PASSWORTTIMEOUT'])) {
	if ($_SESSION['PASSWORTTIMEOUT'] != '0') {
		echo cms_meldung('info', "schulhof.seite.nutzerkonto.meldung.passworttimeout", array("%passwortaendernlink%" => u("schulhof.seiten.nutzerkonto.passwort")));
	}
}



$neuigkeiten = "";

// Ungelesene Nachrichten auslesen
$db = cms_verbinden('ü');
$sql = "$CMS_DBP_DB.posteingang_$CMS_BENUTZERID.id AS id, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, zeit, erstellt";
$sql = "SELECT $sql FROM $CMS_DBP_DB.posteingang_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON $CMS_DBS_DB.personen.id = $CMS_DBS_DB.nutzerkonten.id WHERE gelesen = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') AND papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') AND empfaenger = $CMS_BENUTZERID";
if ($anfrage = $db->query($sql)) {
	while ($daten = $anfrage->fetch_assoc()) {
		if ($daten['zeit'] > $daten['erstellt']) {$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);}
		else {$anzeigename = s("schulhof.seite.nutzerkonto.notifikation.nachricht.absendergeloescht");}
		$nachricht = explode(' ', $daten['nachricht']);
		if (count($nachricht) > 20) {$nachricht = array_splice($nachricht,0,20);}
		$nachricht = strip_tags(implode(' ', $nachricht));
		$betreffevent = cms_texttrafo_e_event($daten['betreff']);
		$tag = cms_tagname(date ("w", $daten['zeit']));
		$datum = date ("d.m.Y", $daten['zeit']);
		$uhrzeit = date ("H:i", $daten['zeit']);
		$lesen = "cms_postfach_nachricht_lesen('eingang', '".$anzeigename."', '".$betreffevent."', '".$datum."', '".$uhrzeit."', '".$daten['id']."')";
		$neuigkeiten .= "<li class=\"cms_neuigkeit cms_postneuigkeit\" onclick=\"$lesen\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/icons/gross/nachricht.png\"></span>";
		$neuigkeiten .= "<span class=\"cms_neuigkeit_inhalt\"><h4>".s("schulhof.seite.nutzerkonto.notifikation.nachricht.kopf")."</h4>";
		$neuigkeiten .= "<p>".$daten['betreff']."</p><p class=\"cms_neuigkeit_vorschau\">$tag $datum um $uhrzeit von $anzeigename</p>";
		$neuigkeiten .= "<p>".$daten['betreff']."</p><p class=\"cms_neuigkeit_vorschau\">".s("schulhof.seite.nutzerkonto.notifikation.nachricht.absender", array_merge(array("%absender%" => $anzeigename), cms_zeitarray($daten["zeit"])))."</p>";
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
	$notifikationen .= "<p><span class=\"cms_button_nein\" onclick=\"cms_notifikationen_loeschen()\">".s("schulhof.seite.nutzerkonto.notifikation.schliessen")."</span></p>";
}
$neuigkeiten .= $notifikationen;
if (strlen($neuigkeiten) > 0) {echo "<h2>".s("schulhof.seite.nutzerkonto.notifikation.ueberschrift")."</h2>$neuigkeiten";}
?>
</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">
<?php
if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanexternpersoenlich.php');
	$vplan = cms_vertretungsplan_extern_persoenlich();
	echo "<h2>".s("schulhof.seite.nutzerkonto.vertretungsplan.ueberschrift")."</h2>";
	if (strlen($vplan) > 0) {
		echo $vplan;
	}
	else {echo "<p class=\"cms_notiz\">".s("schulhof.seite.nutzerkonto.vertretungsplan.leer")."</p>";}
}
else {
	include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplanausgeben.php');
	$vplanheute = cms_vertretungsplan_heute();
	$vplannext = cms_vertretungsplan_naechsterschultag();

	echo "<h2>".s("schulhof.seite.nutzerkonto.vertretungsplan.ueberschrift")."</h2>";
	if ((strlen($vplanheute) > 0) || (strlen($vplannext) > 0)) {
		echo $vplanheute.$vplannext;
	}
	else {echo "<p class=\"cms_notiz\">".s("schulhof.seite.nutzerkonto.vertretungsplan.leer")."</p>";}
}
?>
</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">
<?php
echo "<h2>".s("schulhof.seite.nutzerkonto.termine.ueberschrift")."</h2>";
$termine = "";
include_once('php/schulhof/seiten/termine/termineausgeben.php');
$termine = cms_nachste_termine_ausgeben($_SESSION['BENUTZERUEBERSICHTANZAHL']);
if (strlen($termine) == 0) {
	echo "<p class=\"cms_notiz\">".s("schulhof.seite.nutzerkonto.termine.leer")."</p>";
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
echo "<h2>".s("schulhof.seite.nutzerkonto.mitgliedschaften.ueberschrift")."</h2>";
include_once('php/schulhof/seiten/gruppen/mitgliedschaftenausgeben.php');
foreach ($CMS_GRUPPEN as $g) {
	$gruppencode = cms_gruppen_mitgliedschaften_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
	if (strlen($gruppencode) > 0) {echo "<h3>$g</h3>".str_replace('<ul>', '<ul class="cms_aktionen_liste">', $gruppencode);}
}
?>

</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
$fav = "";
if ($CMS_BENUTZERART == 'l' || $CMS_BENUTZERART == 's') {
		$fav .= "<li><a class=\"cms_button\" href=\"".u("schulhof.seiten.nutzerkonto.stundenplan")."\">".s("schulhof.seite.nutzerkonto.favoriten.stundenplan")."</a></li> ";
}
$fav .= "<li><a class=\"cms_button\" href=\"Schulhof/Termine\">".s("schulhof.seite.nutzerkonto.favoriten.stundenplan")."</a></li> ";
$fav .= "<li><a class=\"cms_button\" href=\"".u("schulhof.seiten.nutzerkonto.postfach.posteingang")."\">".s("schulhof.seite.nutzerkonto.favoriten.posteingang")."</a></li> ";

if ($CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen'] || $CMS_EINSTELLUNGEN['Fehlermeldung aktiv'] ||
    (($CMS_RECHTE['Planung']['Räume sehen'] || $CMS_RECHTE['Planung']['Leihgeräte sehen']) && ($CMS_RECHTE['Technik']['Geräte-Probleme melden']))) {
	$fav .= "<li><a class=\"cms_button\" href=\"".u("schulhof.seiten.nutzerkonto.problememelden")."\">".s("schulhof.seite.nutzerkonto.favoriten.probleme")."</a></li> ";
}

if (strlen($fav) > 0) {echo "<h2>".s("schulhof.seite.nutzerkonto.favoriten.ueberschrift")."</h2><ul class=\"cms_aktionen_liste\">$fav</ul>";}

?>

<div id="cms_aktivitaet_out_profil"><div id="cms_aktivitaet_in_profil"></div></div>
<p class="cms_notiz" id="cms_aktivitaet_text_profil"><?php echo s("schulhof.seite.nutzerkonto.timeout.berechnung");?></p>
<ul class="cms_aktionen_liste">
	<li><span class="cms_button_ja" onclick="cms_timeout_verlaengern()"><?php echo s("schulhof.seite.nutzerkonto.timeout.verlaengern");?></span></li>
	<li><span class="cms_button_nein" onclick="cms_abmelden_frage();"><?php echo s("schulhof.seite.nutzerkonto.timeout.abmelden");?></span></li>
</ul>
<?php
$aktionen = "";
if ($CMS_RECHTE['Website']['Termine anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_termin('".implode('/', $CMS_URL)."')\">".s("schulhof.seite.nutzerkonto.aktion.neu.termin")."</span></li> ";
}
if ($CMS_RECHTE['Website']['Blogeinträge anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_blogeintrag('".implode('/', $CMS_URL)."')\">".s("schulhof.seite.nutzerkonto.aktion.neu.blogeintrag")."</span></li> ";
}
if ($CMS_RECHTE['Website']['Galerien anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neue_galerie('".implode('/', $CMS_URL)."')\">".s("schulhof.seite.nutzerkonto.aktion.neu.galerie")."</span></li> ";
}
/*if ($CMS_RECHTE['Persönlich']['Termine anlegen']) {
	$aktionen .= "<li><span class=\"cms_button_ja\" onclick=\"cms_neuer_persoenlicher_termin('".implode('/', $CMS_URL)."')\">+ Neuer persönlicher Termin</span></li> ";
}*/
if (strlen($aktionen) > 0) {
	echo "<h2>".s("schulhof.seite.nutzerkonto.aktion.ueberschrift")."</h2><ul class=\"cms_aktionen_liste\">$aktionen</ul>";
}

$sonderrollencodeverwaltung = cms_sonderrollen_generieren();
if (strlen($sonderrollencodeverwaltung) != 0) {
	$sonderrollencode = "<h2>".s("schulhof.seite.nutzerkonto.aufgaben.ueberschrift")."</h2>";
	$sonderrollencode .= "<ul class=\"cms_aktionen_liste\">".$sonderrollencodeverwaltung."</ul>";
	echo $sonderrollencode;
}

if ($CMS_RECHTE['Persönlich']['Notizen anlegen']) {
	$code = "<h2>".s("schulhof.seite.nutzerkonto.notizen.ueberschrift")."</h2>";
	$notizen = "";
	$sql = "SELECT AES_DECRYPT(notizen, '$CMS_SCHLUESSEL') AS notizen FROM nutzerkonten WHERE id = $CMS_BENUTZERID";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$notizen = $daten['notizen'];
		}
		$anfrage->free();
	}

	if (strlen($notizen) == 0) {$zusatzklasse = " cms_notizzettelleer";} else {$zusatzklasse = "";}
	$code .= "<p><textarea id=\"cms_persoenlichenotizen\" class=\"cms_notizzettel$zusatzklasse\">$notizen</textarea></p>";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_persoenliche_notizen_speichern()\">".s("schulhof.seite.nutzerkonto.notizen.aktion.speichern")."</span> <a class=\"cms_button_nein\" href=\"".u("schulhof.seiten.nutzerkonto.nutzerkonto")."\">".s("schulhof.seite.nutzerkonto.notizen.aktion.abbrechen")."</a></p>";
	echo $code;
}
?>

</div>
</div>

<div class="cms_clear"></div>
