<?php
include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
// Kennung laden
$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanL', '$CMS_SCHLUESSEL')");
if ($sql->execute()) {
	$sql->bind_result($kennung);
	$sql->fetch();
}
$sql->close();

$code .= "<h1>Vertretungsplan Lehreransicht</h1>";
$code .= cms_vertretungsplan_komplettansicht($dbs, 'l', $_SESSION['DRUCKVPLANDATUMV'], $_SESSION['DRUCKVPLANDATUMB'], '1', '');

$code .= "<h1>Vertretungsplan Schüleransicht</h1>";
$code .= cms_vertretungsplan_komplettansicht($dbs, 's', $_SESSION['DRUCKVPLANDATUMV'], $_SESSION['DRUCKVPLANDATUMB'], '1', '');

$druckfehler = false;
?>
