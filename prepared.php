<div style="white-space: pre">
<?php
  include("php/schulhof/funktionen/generieren.php");
  include("php/schulhof/anfragen/verwaltung/gruppen/initial.php");

  foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    echo "CREATE TABLE `$gk"."newsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppen$gk"."newsletter` (`gruppe`),
 KEY `newsletter$gk"."newsletter` (`newsletter`),
 CONSTRAINT `newsletter$gk"."newsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppen$gk"."newsletter` FOREIGN KEY (`gruppe`) REFERENCES `$gk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

";
  }

?>
</div>
