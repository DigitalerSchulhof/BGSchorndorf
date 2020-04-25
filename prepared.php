<div style="white-space: pre"><?php
  echo $dbs = mysqli_connect("85.214.148.205", "entwickler", "4NTW1CKLER_MYSQL", "entwickler_schulhof");

foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    echo "CREATE TABLE `$gk"."newsletter` (
      `gruppe` bigint(255) unsigned NOT NULL,
      `newsletter` bigint(255) unsigned NOT NULL,
      KEY `gruppen$gk"."newsletter` (`gruppe`),
      KEY `newsletter$gk"."newsletter` (`newsletter`),
      CONSTRAINT `newsletter$gk"."newsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
      CONSTRAINT `gruppen$gk"."newsletter` FOREIGN KEY (`gruppe`) REFERENCES `$gk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    echo "ALTER TABLE `$gk"."chat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;";
  }

?></div>
