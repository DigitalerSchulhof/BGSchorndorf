<?php

include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

cms_rechte_laden(3, false);
$zugriff = cms_r("schulhof.gruppen.[|klassen,kurse].[|anlegen,bearbeiten] || schulhof.organisation.räume.[|anlegen,bearbeiten] || schulhof.verwaltung.lehrer.kürzel"));
var_dump($zugriff);

// namespace Zieletest {
//   cms_rechte_laden(3, false);
//   include_once('../../schulhof/anfragen/ziele.php');
//
//   function cms_angemeldet() {
//     return true;
//   }
//
//   $_POST = array(
//     "fenster"         => "j",
//     "einwilligungA"   => "j",
//     "einwilligungB"   => "j",
//     ""
//   );
//
//   $ziel = 1;
//
//   echo "Einbinden: {$CMS_ZIELE[$ziel]} ($ziel)\n";
//   include_once("../../../{$CMS_ZIELE[$ziel]}");
//   echo "\n";
// }

?>
