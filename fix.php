<?php

// $personen = array("Lehrer", "Verwaltungsangestellte", "Schüler", "Eltern", "Externe");
// $gruppen = array("Gremien", "Fachschaften", "Klassen", "Kurse", "Stufen", "Arbeitsgemeinschaften", "Arbeitskreise", "Fahrten", "Wettbewerbe", "Ereignisse", "Sonstige Gruppen");
// $raenge = array("Vorsitzende", "Aufsicht", "Mitglieder");

// foreach ($gruppen as $g) {
//  	$gk = strtolower($g);
//  	$gk = str_replace(" ", "", $gk);
  //echo "ALTER TABLE `$gk"."mitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;";
  //echo "ALTER TABLE $gk"."mitglieder DROP FOREIGN KEY chatbannvon".$gk."mitglieder;<br>";
  // echo "ALTER TABLE `$gk"."mitglieder` DROP INDEX `chatbannvon".$gk."mitglieder`;<br>";

//
// 	echo "ALTER TABLE `$gk` CHANGE `bezeichnung` `bezeichnung` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `icon` `icon` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `sichtbar` `sichtbar` INT(1) UNSIGNED NOT NULL DEFAULT '0', CHANGE `chataktiv` `chataktiv` INT(1) UNSIGNED NOT NULL DEFAULT '0';<br>";
// 	echo "ALTER TABLE `$gk"."blogeintraegeintern` CHANGE `gruppe` `gruppe` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT '0', CHANGE `genehmigt` `genehmigt` INT(1) NOT NULL DEFAULT '0', CHANGE `aktiv` `aktiv` INT(1) NOT NULL DEFAULT '0', CHANGE `notifikationen` `notifikationen` INT(1) NOT NULL DEFAULT '0', CHANGE `text` `text` LONGBLOB NULL DEFAULT NULL, CHANGE `vorschau` `vorschau` LONGBLOB NULL DEFAULT NULL, CHANGE `autor` `autor` VARBINARY(5000) NULL DEFAULT NULL;<br>";
// 	echo "ALTER TABLE `$gk"."blogeintragbeschluesse` CHANGE `blogeintrag` `blogeintrag` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `langfristig` `langfristig` VARBINARY(50) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL, CHANGE `pro` `pro` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `contra` `contra` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `enthaltung` `enthaltung` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
// 	echo "ALTER TABLE `$gk"."blogeintragdownloads` CHANGE `blogeintrag` `blogeintrag` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `pfad` `pfad` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL, CHANGE `dateiname` `dateiname` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `dateigroesse` `dateigroesse` INT(1) UNSIGNED NOT NULL DEFAULT '1';<br>";
// 	echo "ALTER TABLE `$gk"."chat` CHANGE `gruppe` `gruppe` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `person` `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `datum` `datum` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `inhalt` `inhalt` LONGBLOB NULL DEFAULT NULL, CHANGE `loeschstatus` `loeschstatus` INT(1) UNSIGNED NULL DEFAULT NULL, CHANGE `fertig` `fertig` INT(1) NULL DEFAULT NULL;<br>";
// 	echo "ALTER TABLE `$gk"."termineintern` CHANGE `gruppe` `gruppe` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `ort` `ort` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `mehrtaegigt` `mehrtaegigt` INT(1) NULL DEFAULT NULL, CHANGE `uhrzeitbt` `uhrzeitbt` INT(1) NULL DEFAULT NULL, CHANGE `uhrzeitet` `uhrzeitet` INT(1) NULL DEFAULT NULL, CHANGE `ortt` `ortt` INT(1) NULL DEFAULT NULL, CHANGE `genehmigt` `genehmigt` INT(1) NOT NULL DEFAULT '0', CHANGE `aktiv` `aktiv` INT(1) NOT NULL DEFAULT '0', CHANGE `notifikationen` `notifikationen` INT(1) NOT NULL DEFAULT '0', CHANGE `text` `text` LONGBLOB NULL DEFAULT NULL;<br>";
// 	echo "ALTER TABLE `$gk"."termineinterndownloads` CHANGE `termin` `termin` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `pfad` `pfad` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL, CHANGE `dateiname` `dateiname` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `dateigroesse` `dateigroesse` INT(1) UNSIGNED NOT NULL DEFAULT '1';<br><br>";
//}

// echo "ALTER TABLE `klassen` CHANGE `stundenplanextern` `stundenplanextern` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `stufenbezextern` `stufenbezextern` VARBINARY(500) NULL DEFAULT NULL, CHANGE `klassenbezextern` `klassenbezextern` VARBINARY(500) NULL DEFAULT NULL, CHANGE `stufe` `stufe` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `kurse` CHANGE `kurzbezeichnung` `kurzbezeichnung` VARBINARY(500) NULL DEFAULT NULL, CHANGE `fach` `fach` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `kursbezextern` `kursbezextern` VARBINARY(500) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `stufen` CHANGE `reihenfolge` `reihenfolge` INT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `tagebuch` `tagebuch` INT(1) UNSIGNED NULL DEFAULT '0', CHANGE `gfs` `gfs` INT(1) UNSIGNED NULL DEFAULT '0';<br><br>";
// echo "-------------------------------------------<br><br>";
//
//
//
// echo "ALTER TABLE `blogeintraege` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `datum` `datum` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `genehmigt` `genehmigt` INT(1) NOT NULL DEFAULT '0', CHANGE `aktiv` `aktiv` INT(1) NOT NULL DEFAULT '0', CHANGE `oeffentlichkeit` `oeffentlichkeit` INT(1) NOT NULL DEFAULT '0', CHANGE `notifikationen` `notifikationen` INT(1) NOT NULL DEFAULT '0', CHANGE `text` `text` LONGBLOB NULL DEFAULT NULL, CHANGE `vorschau` `vorschau` LONGBLOB NULL DEFAULT NULL, CHANGE `vorschaubild` `vorschaubild` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `autor` `autor` VARBINARY(5000) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `blogeintragdownloads` CHANGE `blogeintrag` `blogeintrag` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `pfad` `pfad` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL, CHANGE `dateiname` `dateiname` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `dateigroesse` `dateigroesse` INT(1) UNSIGNED NOT NULL DEFAULT '1';<br><br>";
// echo "ALTER TABLE `boxen` CHANGE `boxaussen` `boxaussen` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0', CHANGE `titelalt` `titelalt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `titelaktuell` `titelaktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `titelneu` `titelneu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `inhaltalt` `inhaltalt` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `inhaltaktuell` `inhaltaktuell` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `inhaltneu` `inhaltneu` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `stylealt` `stylealt` INT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `styleaktuell` `styleaktuell` INT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `styleneu` `styleneu` INT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `boxenaussen` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0', CHANGE `ausrichtungalt` `ausrichtungalt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `ausrichtungaktuell` `ausrichtungaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `ausrichtungneu` `ausrichtungneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `breitealt` `breitealt` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `breiteaktuell` `breiteaktuell` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `breiteneu` `breiteneu` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `dauerbrenner` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `inhalt` `inhalt` LONGBLOB NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `downloads` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `pfadalt` `pfadalt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `pfadaktuell` `pfadaktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `pfadneu` `pfadneu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `titelalt` `titelalt` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `titelaktuell` `titelaktuell` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `titelneu` `titelneu` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibungalt` `beschreibungalt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibungaktuell` `beschreibungaktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibungneu` `beschreibungneu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `dateinamealt` `dateinamealt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '1', CHANGE `dateinameaktuell` `dateinameaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '1', CHANGE `dateinameneu` `dateinameneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '1', CHANGE `dateigroessealt` `dateigroessealt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1', CHANGE `dateigroesseaktuell` `dateigroesseaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '1', CHANGE `dateigroesseneu` `dateigroesseneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1';<br><br>";
// echo "ALTER TABLE `editoren` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `alt` `alt` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `aktuell` `aktuell` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `neu` `neu` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `eventuebersichten` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `terminealt` `terminealt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0', CHANGE `termineaktuell` `termineaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `termineneu` `termineneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `termineanzahlalt` `termineanzahlalt` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `termineanzahlaktuell` `termineanzahlaktuell` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `termineanzahlneu` `termineanzahlneu` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `blogalt` `blogalt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `blogaktuell` `blogaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `blogneu` `blogneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `bloganzahlalt` `bloganzahlalt` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `bloganzahlaktuell` `bloganzahlaktuell` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `bloganzahlneu` `bloganzahlneu` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `galeriealt` `galeriealt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `galerieaktuell` `galerieaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `galerieneu` `galerieneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `galerieanzahlalt` `galerieanzahlalt` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `galerieanzahlaktuell` `galerieanzahlaktuell` INT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `galerieanzahlneu` `galerieanzahlneu` INT(255) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `faecher` CHANGE `schuljahr` `schuljahr` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `kuerzel` `kuerzel` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `farbe` `farbe` INT(2) UNSIGNED NULL DEFAULT NULL, CHANGE `icon` `icon` VARBINARY(500) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `feedback` CHANGE `name` `name` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `feedback` `feedback` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `zeitstempel` `zeitstempel` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `fehlermeldungen` CHANGE `ersteller` `ersteller` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `url` `url` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `header` `header` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `session` `session` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `notizen` `notizen` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `zeitstempel` `zeitstempel` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `status` `status` INT(1) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `ferien` CHANGE `bezeichnung` `bezeichnung` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `art` `art` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `mehrtaegigt` `mehrtaegigt` INT(1) NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `galerien` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `datum` `datum` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `genehmigt` `genehmigt` INT(1) NOT NULL DEFAULT '0', CHANGE `aktiv` `aktiv` INT(1) NOT NULL DEFAULT '0', CHANGE `oeffentlichkeit` `oeffentlichkeit` INT(1) NOT NULL DEFAULT '0', CHANGE `notifikationen` `notifikationen` INT(1) NOT NULL DEFAULT '0', CHANGE `beschreibung` `beschreibung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `vorschaubild` `vorschaubild` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `autor` `autor` VARBINARY(5000) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `galerienbilder` CHANGE `pfad` `pfad` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `hausmeisterauftraege` CHANGE `status` `status` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `titel` `titel` VARBINARY(1000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` BLOB NULL DEFAULT NULL, CHANGE `start` `start` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ziel` `ziel` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `erledigt` `erledigt` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `erledigtvon` `erledigtvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `kontaktformulare` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0', CHANGE `betreffalt` `betreffalt` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `betreffaktuell` `betreffaktuell` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `betreffneu` `betreffneu` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `kopiealt` `kopiealt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `kopieaktuell` `kopieaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `kopieneu` `kopieneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `anhangalt` `anhangalt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `anhangaktuell` `anhangaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `anhangneu` `anhangneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `kontaktformulareempfaenger` CHANGE `kontaktformular` `kontaktformular` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `namealt` `namealt` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `nameaktuell` `nameaktuell` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `nameneu` `nameneu` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibungalt` `beschreibungalt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibungaktuell` `beschreibungaktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibungneu` `beschreibungneu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `mailalt` `mailalt` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `mailaktuell` `mailaktuell` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `mailneu` `mailneu` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `leihen` CHANGE `bezeichnung` `bezeichnung` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `buchbar` `buchbar` INT(1) NOT NULL DEFAULT '0', CHANGE `verfuegbar` `verfuegbar` INT(1) NOT NULL DEFAULT '0', CHANGE `externverwaltbar` `externverwaltbar` INT(1) NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `leihenblockieren` CHANGE `wochentag` `wochentag` INT(1) NULL DEFAULT NULL, CHANGE `beginns` `beginns` VARBINARY(100) NULL DEFAULT NULL, CHANGE `beginnm` `beginnm` VARBINARY(100) NULL DEFAULT NULL, CHANGE `endes` `endes` VARBINARY(100) NULL DEFAULT NULL, CHANGE `endem` `endem` VARBINARY(100) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `leihenblockieren` CHANGE `standort` `standort` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `grund` `grund` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `wochentag` `wochentag` INT(1) UNSIGNED NULL DEFAULT NULL, CHANGE `beginns` `beginns` VARBINARY(100) NULL DEFAULT NULL, CHANGE `beginnm` `beginnm` VARBINARY(100) NULL DEFAULT NULL, CHANGE `endes` `endes` VARBINARY(100) NULL DEFAULT NULL, CHANGE `endem` `endem` VARBINARY(100) NULL DEFAULT NULL, CHANGE `ferien` `ferien` INT(1) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `leihenbuchen` CHANGE `standort` `standort` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `person` `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `grund` `grund` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `leihengeraete` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `standort` `standort` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `statusnr` `statusnr` INT(1) NOT NULL DEFAULT '0', CHANGE `meldung` `meldung` LONGBLOB NULL DEFAULT NULL, CHANGE `kommentar` `kommentar` LONGBLOB NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `navigationen` CHANGE `art` `art` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `ebene` `ebene` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `styles` `styles` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `klassen` `klassen` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `notifikationen` CHANGE `person` `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `zeit` `zeit` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `gruppe` `gruppe` VARBINARY(1000) NULL DEFAULT NULL, CHANGE `gruppenid` `gruppenid` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `status` `status` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `art` `art` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `vorschau` `vorschau` BLOB NULL DEFAULT NULL, CHANGE `link` `link` VARBINARY(5000) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `personen` CHANGE `art` `art` VARBINARY(50) NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `nachname` `nachname` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `vorname` `vorname` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `geschlecht` `geschlecht` VARBINARY(50) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `pinnwaende` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `pinnwandanschlag` CHANGE `pinnwand` `pinnwand` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `inhalt` `inhalt` LONGBLOB NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `profile` CHANGE `schuljahr` `schuljahr` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `art` `art` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `stufe` `stufe` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `raeume` CHANGE `bezeichnung` `bezeichnung` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `stundenplan` `stundenplan` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `buchbar` `buchbar` INT(1) NULL DEFAULT '0', CHANGE `verfuegbar` `verfuegbar` INT(1) NULL DEFAULT '0', CHANGE `externverwaltbar` `externverwaltbar` INT(1) NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `raeumeblockieren` CHANGE `standort` `standort` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `grund` `grund` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `wochentag` `wochentag` INT(1) UNSIGNED NULL DEFAULT NULL, CHANGE `beginns` `beginns` VARBINARY(100) NULL DEFAULT NULL, CHANGE `beginnm` `beginnm` VARBINARY(100) NULL DEFAULT NULL, CHANGE `endes` `endes` VARBINARY(100) NULL DEFAULT NULL, CHANGE `endem` `endem` VARBINARY(100) NULL DEFAULT NULL, CHANGE `ferien` `ferien` INT(1) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `raeumebuchen` CHANGE `standort` `standort` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `person` `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `grund` `grund` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `raeumegeraete` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `standort` `standort` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `statusnr` `statusnr` INT(1) NULL DEFAULT '0', CHANGE `meldung` `meldung` LONGBLOB NULL DEFAULT NULL, CHANGE `kommentar` `kommentar` LONGBLOB NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `regelunterricht` CHANGE `schulstunde` `schulstunde` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `tag` `tag` INT(1) UNSIGNED NULL DEFAULT NULL, CHANGE `rythmus` `rythmus` INT(2) UNSIGNED NULL DEFAULT NULL, CHANGE `kurs` `kurs` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `lehrer` `lehrer` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `raum` `raum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `rollen` CHANGE `bezeichnung` `bezeichnung` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `personenart` `personenart` VARBINARY(50) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `schuljahre` CHANGE `bezeichnung` `bezeichnung` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `schulstunden` CHANGE `zeitraum` `zeitraum` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `beginns` `beginns` INT(2) UNSIGNED NULL DEFAULT '0', CHANGE `beginnm` `beginnm` INT(2) UNSIGNED NULL DEFAULT '0', CHANGE `endes` `endes` INT(2) UNSIGNED NULL DEFAULT '0', CHANGE `endem` `endem` INT(2) UNSIGNED NULL DEFAULT '0';<br><br>";
// echo "ALTER TABLE `seiten` CHANGE `art` `art` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 's', CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `sidebar` `sidebar` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1', CHANGE `status` `status` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'i', CHANGE `styles` `styles` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `klassen` `klassen` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `spalten` CHANGE `seite` `seite` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `zeile` `zeile` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `termine` CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `ort` `ort` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `mehrtaegigt` `mehrtaegigt` INT(1) NOT NULL DEFAULT '0', CHANGE `uhrzeitbt` `uhrzeitbt` INT(1) NOT NULL DEFAULT '0', CHANGE `uhrzeitet` `uhrzeitet` INT(1) NOT NULL DEFAULT '0', CHANGE `ortt` `ortt` INT(1) NOT NULL DEFAULT '0', CHANGE `genehmigt` `genehmigt` INT(1) NOT NULL DEFAULT '0', CHANGE `aktiv` `aktiv` INT(1) NULL DEFAULT '0', CHANGE `notifikationen` `notifikationen` INT(1) NOT NULL DEFAULT '0', CHANGE `text` `text` LONGBLOB NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `terminedownloads` CHANGE `termin` `termin` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `pfad` `pfad` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beschreibung` `beschreibung` LONGBLOB NULL DEFAULT NULL, CHANGE `dateiname` `dateiname` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `dateigroesse` `dateigroesse` INT(1) UNSIGNED NOT NULL DEFAULT '1';<br><br>";
// echo "ALTER TABLE `voranmeldung_eltern` CHANGE `schueler` `schueler` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `nummer` `nummer` VARBINARY(100) NULL DEFAULT NULL, CHANGE `vorname` `vorname` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `nachname` `nachname` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `geschlecht` `geschlecht` VARBINARY(50) NULL DEFAULT NULL, CHANGE `sorgerecht` `sorgerecht` VARBINARY(50) NULL DEFAULT NULL, CHANGE `briefe` `briefe` VARBINARY(50) NULL DEFAULT NULL, CHANGE `strasse` `strasse` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `hausnummer` `hausnummer` VARBINARY(100) NULL DEFAULT NULL, CHANGE `plz` `plz` VARBINARY(100) NULL DEFAULT NULL, CHANGE `ort` `ort` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `teilort` `teilort` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `telefon1` `telefon1` VARBINARY(500) NULL DEFAULT NULL, CHANGE `telefon2` `telefon2` VARBINARY(500) NULL DEFAULT NULL, CHANGE `handy` `handy` VARBINARY(500) NULL DEFAULT NULL, CHANGE `mail` `mail` VARBINARY(2000) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `voranmeldung_schueler` CHANGE `vorname` `vorname` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `rufname` `rufname` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `nachname` `nachname` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `geburtsdatum` `geburtsdatum` VARBINARY(500) NULL DEFAULT NULL, CHANGE `geburtsort` `geburtsort` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `geburtsland` `geburtsland` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `muttersprache` `muttersprache` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `verkehrssprache` `verkehrssprache` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `geschlecht` `geschlecht` VARBINARY(50) NULL DEFAULT NULL, CHANGE `religion` `religion` VARBINARY(500) NULL DEFAULT NULL, CHANGE `religionsunterricht` `religionsunterricht` VARBINARY(500) NULL DEFAULT NULL, CHANGE `staatsangehoerigkeit` `staatsangehoerigkeit` VARBINARY(1000) NULL DEFAULT NULL, CHANGE `zstaatsangehoerigkeit` `zstaatsangehoerigkeit` VARBINARY(1000) NULL DEFAULT NULL, CHANGE `strasse` `strasse` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `hausnummer` `hausnummer` VARBINARY(100) NULL DEFAULT NULL, CHANGE `plz` `plz` VARBINARY(100) NULL DEFAULT NULL, CHANGE `ort` `ort` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `teilort` `teilort` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `telefon1` `telefon1` VARBINARY(500) NULL DEFAULT NULL, CHANGE `telefon2` `telefon2` VARBINARY(500) NULL DEFAULT NULL, CHANGE `handy1` `handy1` VARBINARY(500) NULL DEFAULT NULL, CHANGE `handy2` `handy2` VARBINARY(500) NULL DEFAULT NULL, CHANGE `mail` `mail` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `einschulung` `einschulung` VARBINARY(500) NULL DEFAULT NULL, CHANGE `vorigeschule` `vorigeschule` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `vorigeklasse` `vorigeklasse` VARBINARY(50) NULL DEFAULT NULL, CHANGE `kuenftigesprofil` `kuenftigesprofil` VARBINARY(1000) NULL DEFAULT NULL, CHANGE `akzeptiert` `akzeptiert` VARBINARY(100) NULL DEFAULT NULL, CHANGE `eingegangen` `eingegangen` VARBINARY(500) NULL DEFAULT NULL, CHANGE `idvon` `idvon` BIGINT(255) NULL DEFAULT NULL, CHANGE `idzeit` `idzeit` BIGINT(255) NULL DEFAULT NULL;<br><br>";
// echo "ALTER TABLE `zeitraeume` CHANGE `schuljahr` `schuljahr` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `aktiv` `aktiv` INT(1) UNSIGNED NOT NULL DEFAULT '0', CHANGE `mo` `mo` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `di` `di` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `mi` `mi` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `do` `do` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `fr` `fr` INT(1) UNSIGNED NOT NULL DEFAULT '1', CHANGE `sa` `sa` INT(1) UNSIGNED NOT NULL DEFAULT '0', CHANGE `so` `so` INT(1) UNSIGNED NOT NULL DEFAULT '0', CHANGE `rythmen` `rythmen` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br><br>";
//
//
// echo "CREATE TABLE `unterricht` (
//   `id` bigint(255) UNSIGNED NOT NULL,
//   `pkurs` bigint(255) UNSIGNED DEFAULT NULL,
//   `pbeginn` bigint(255) UNSIGNED DEFAULT NULL,
//   `pende` bigint(255) UNSIGNED DEFAULT NULL,
//   `plehrer` bigint(255) UNSIGNED DEFAULT NULL,
//   `praum` bigint(255) UNSIGNED DEFAULT NULL,
//   `tkurs` bigint(255) UNSIGNED DEFAULT NULL,
//   `tbeginn` bigint(255) UNSIGNED DEFAULT NULL,
//   `tende` bigint(255) UNSIGNED DEFAULT NULL,
//   `tlehrer` bigint(255) UNSIGNED DEFAULT NULL,
//   `traum` bigint(255) UNSIGNED DEFAULT NULL,
//   `vplananzeigen` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
//   `vplanart` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
//   `vplanbemerkung` varbinary(5000) DEFAULT NULL,
//   `idvon` bigint(255) UNSIGNED DEFAULT NULL,
//   `idzeit` bigint(255) UNSIGNED DEFAULT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;<br><br>";
//
// echo "CREATE TABLE `unterrichtkonflikt` (
//   `id` bigint(255) UNSIGNED NOT NULL,
//   `altid` bigint(255) UNSIGNED DEFAULT NULL,
//   `tkurs` bigint(255) UNSIGNED DEFAULT NULL,
//   `tbeginn` bigint(255) UNSIGNED DEFAULT NULL,
//   `tende` bigint(255) UNSIGNED DEFAULT NULL,
//   `tlehrer` bigint(255) UNSIGNED DEFAULT NULL,
//   `traum` bigint(255) UNSIGNED DEFAULT NULL,
//   `vplananzeigen` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
//   `vplanart` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
//   `vplanbemerkung` varbinary(5000) DEFAULT NULL,
//   `idvon` bigint(255) UNSIGNED NOT NULL,
//   `idzeit` bigint(255) UNSIGNED NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;<br><br>";
//
// echo "ALTER TABLE `unterricht`
//   ADD PRIMARY KEY (`id`),
//   ADD KEY `unterrichtkurs` (`tkurs`),
//   ADD KEY `unterrichtlehrer` (`tlehrer`),
//   ADD KEY `unterrichtraum` (`traum`);<br><br>";
//
// echo "ALTER TABLE `unterrichtkonflikt`
//   ADD PRIMARY KEY (`id`),
//   ADD KEY `unterrichtkonfliktkurs` (`tkurs`),
//   ADD KEY `unterrichtkonfliktlehrer` (`tlehrer`),
//   ADD KEY `unterrichtkonfliktraum` (`traum`),
//   ADD KEY `unterrichtkonfliktunterricht` (`altid`);<br><br>";
//
// echo "ALTER TABLE `unterricht`
//   ADD CONSTRAINT `unterrichtkonfliktkurs` FOREIGN KEY (`tkurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//   ADD CONSTRAINT `unterrichtkonfliktlehrer` FOREIGN KEY (`tlehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//   ADD CONSTRAINT `unterrichtkonfliktraum` FOREIGN KEY (`traum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//   ADD CONSTRAINT `unterrichtkurs` FOREIGN KEY (`tkurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//   ADD CONSTRAINT `unterrichtlehrer` FOREIGN KEY (`tlehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
//   ADD CONSTRAINT `unterrichtraum` FOREIGN KEY (`traum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;<br><br>";
//
// echo "ALTER TABLE `unterrichtkonflikt`
//   ADD CONSTRAINT `unterrichtkonfliktunterricht` FOREIGN KEY (`altid`) REFERENCES `unterricht` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;<br><br>";
//
//
// echo "-------------------------------------------<br><br>";


// ALTER TABLE `nutzerkonten` CHANGE `benutzername` `benutzername` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `passwort` `passwort` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `passworttimeout` `passworttimeout` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `salt` `salt` VARBINARY(100) NULL DEFAULT NULL, CHANGE `sessionid` `sessionid` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL, CHANGE `sessiontimeout` `sessiontimeout` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `email` `email` VARBINARY(3000) NULL DEFAULT NULL, CHANGE `letzteanmeldung` `letzteanmeldung` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `vorletzteanmeldung` `vorletzteanmeldung` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `erstellt` `erstellt` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `notizen` `notizen` LONGBLOB NULL DEFAULT NULL, CHANGE `letztenotifikation` `letztenotifikation` BIGINT(255) UNSIGNED NULL DEFAULT NULL;


// include_once('php/allgemein/funktionen/sql.php');
// include_once("php/schulhof/funktionen/config.php");
// $dbs = cms_verbinden('s');
// echo "<table>";
// $sql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), zweitid FROM personen WHERE zweitid IS NOT NULL");
// if ($sql->execute()) {
//   $nr = 1;
//   $sql->bind_result($vor, $nach, $zid);
//   while ($sql->fetch()) {
//     echo "<tr><td>$nr</td><td>$vor</td><td>$nach</td><td>$zid</td></tr>";
//     $nr++;
//   }
// }
// echo "</table>";
//  		echo "ALTER TABLE `postausgang_$nid` CHANGE `absender` `absender` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `empfaenger` `empfaenger` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '', CHANGE `zeit` `zeit` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `betreff` `betreff` VARBINARY(5000) DEFAULT NULL, CHANGE `nachricht` `nachricht` LONGBLOB DEFAULT NULL, CHANGE `papierkorb` `papierkorb` VARBINARY(50) DEFAULT NULL, CHANGE `papierkorbseit` `papierkorbseit` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
//  		echo "ALTER TABLE `posteingang_$nid` CHANGE `absender` `absender` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `empfaenger` `empfaenger` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `alle` `alle` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '', CHANGE `zeit` `zeit` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `betreff` `betreff` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `nachricht` `nachricht` LONGBLOB NULL DEFAULT NULL, CHANGE `gelesen` `gelesen` VARBINARY(50) NULL DEFAULT NULL, CHANGE `papierkorb` `papierkorb` VARBINARY(50) NULL DEFAULT NULL, CHANGE `papierkorbseit` `papierkorbseit` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
// 		echo "ALTER TABLE `postentwurf_$nid` CHANGE `absender` `absender` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `empfaenger` `empfaenger` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '', CHANGE `zeit` `zeit` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `betreff` `betreff` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `nachricht` `nachricht` LONGBLOB NULL DEFAULT NULL, CHANGE `papierkorb` `papierkorb` VARBINARY(50) NULL DEFAULT NULL, CHANGE `papierkorbseit` `papierkorbseit` BIGINT(255) UNSIGNED NULL DEFAULT NULL;<br>";
// 		echo "ALTER TABLE `posttags_$nid` CHANGE `person` `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `titel` `titel` VARBINARY(2000) NULL DEFAULT NULL, CHANGE `farbe` `farbe` INT(2) NOT NULL DEFAULT '0';<br>";
// 		echo "ALTER TABLE `termine_$nid` CHANGE `person` `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `bezeichnung` `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `ort` `ort` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ende` `ende` BIGINT(255) UNSIGNED NOT NULL DEFAULT '0', CHANGE `mehrtaegigt` `mehrtaegigt` VARBINARY(50) NULL DEFAULT NULL, CHANGE `uhrzeitbt` `uhrzeitbt` VARBINARY(50) NULL DEFAULT NULL, CHANGE `uhrzeitet` `uhrzeitet` VARBINARY(50) NULL DEFAULT NULL, CHANGE `ortt` `ortt` VARBINARY(50) NULL DEFAULT NULL, CHANGE `text` `text` LONGBLOB NULL DEFAULT NULL;<br><br>";
// 	}
// }
// cms_trennen($dbs);
?>
