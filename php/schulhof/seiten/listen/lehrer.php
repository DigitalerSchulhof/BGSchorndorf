<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Liste der Lehrer</h1>";

$liste = "";

include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
$dbs = cms_verbinden();
$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
$anzahl = 0;
$sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, nutzerkonten.id AS nutzerkonto FROM personen JOIN lehrer ON personen.id = lehrer.id LEFT JOIN nutzerkonten ON nutzerkonten.id = personen.id WHERE art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')) AS lehrer ORDER BY nachname, vorname, kuerzel";
if ($anfrage = $dbs->query($sql)) {
	while ($daten = $anfrage->fetch_assoc()) {
    $id = $daten['id'];
		$vorname = $daten['vorname'];
		$nachname = $daten['nachname'];
		$titel = $daten['titel'];
		$kuerzel = $daten['kuerzel'];
		$nutzerkonto = $daten['nutzerkonto'];

    $liste .= "<tr>";
    $liste .= "<td><img src=\"res/icons/klein/lehrer.png\"></td>";
    $liste .= "<td>".$daten['titel']."</td>";
    $liste .= "<td>".$daten['vorname']."</td>";
    $liste .= "<td>".$daten['nachname']."</td>";
    $liste .= "<td>".$daten['kuerzel']."</td>";
		$link = "";
		if (!is_null($nutzerkonto) && (in_array($id, $POSTEMPFAENGERPOOL))) {
			$link = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $id)\">Nachricht schreiben</span>";
		}
		else {
			$anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
			$link = "<span class=\"cms_button_passiv\">Nachricht schreiben</span>";
		}
    $liste .= "<td>$link</td>";
    $liste .= "</tr>";
		$anzahl++;
	}
	$anfrage->free();
}
cms_trennen($dbs);

if (strlen($liste) == 0) {
  $liste = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
}

$code .= "<table class=\"cms_liste\">";
  $code .= "<thead>";
    $code .= "<tr><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Kürzel</th><th>Kontakt</th></tr>";
  $code .= "</thead>";
  $code .= "<tbody>";
  $code .= $liste;
  $code .= "</tbody>";
$code .= "</table>";
if ($anzahl != 1) {$endung = "en";} else {$endung = "";}
$code .= "<p class=\"cms_notiz\">$anzahl Person$endung in dieser Liste</p>";

$code .= "</div>";



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
