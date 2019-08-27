<?php
/*$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM ferien WHERE ? BETWEEN beginn AND ende ORDER BY bezeichnung ASC");
$sql->bind_param("i", intval($buchungstag));
if ($sql->execute()) {
  $sql->bind_result($anzahl);
  if ($sql->fetch()) {if ($anzahl > 0) {$ferien[$i] = true;}}
}
$sql->close();*/

$gruppen = array("Gremien", "Fachschaften", "Klassen", "Kurse", "Stufen", "Arbeitsgemeinschaften", "Arbeitskreise", "Fahrten", "Wettbewerbe", "Ereignisse", "Sonstige Gruppen");

$sql = "";
foreach ($gruppen as $g) {
  $gk = strtolower(str_replace(' ', '', $g));
  $sql .= "ALTER TABLE `$gk"."chat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);<br>";
  $sql .= "ALTER TABLE `$gk"."chat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;<br>";
}

echo $sql;

/*
ALTER TABLE `gremienchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `gremienchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `fachschaftenchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `fachschaftenchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `klassenchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `klassenchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `kursechat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `kursechat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `stufenchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `stufenchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `arbeitsgemeinschaftenchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `arbeitsgemeinschaftenchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `arbeitskreisechat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `arbeitskreisechat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `fahrtenchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `fahrtenchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `wettbewerbechat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `wettbewerbechat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `ereignissechat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `ereignissechat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
ALTER TABLE `sonstigegruppenchat` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `sonstigegruppenchat` ADD `idvon` BIGINT(255) UNSIGNED NULL AFTER `gemeldetam`, ADD `idzeit` BIGINT(255) UNSIGNED NULL AFTER `idvon`;
*/
?>
