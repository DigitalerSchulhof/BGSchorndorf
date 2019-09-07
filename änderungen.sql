-- Format:
--
-- <SQL Änderungen>

CREATE TABLE `newslettertypen` (
  `id` bigint(255) unsigned NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `idvon` bigint(255) DEFAULT NULL,
  `idzeit` bigint(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `newsletter` (
 `id` bigint(255) unsigned NOT NULL,
 `spalte` bigint(255) unsigned NOT NULL,
 `position` bigint(255) unsigned NOT NULL,
 `aktiv` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `bezeichnungalt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `bezeichnungaktuell` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `bezeichnungneu` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `beschreibungalt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `beschreibungaktuell` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `beschreibungneu` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
 `typalt` bigint(255) unsigned NOT NULL,
 `typaktuell` bigint(255) unsigned NOT NULL,
 `typneu` bigint(255) unsigned NOT NULL,
 `idvon` bigint(255) unsigned DEFAULT NULL,
 `idzeit` bigint(255) unsigned DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `newsletterspalten` (`spalte`),
 KEY `newslettertypenalt` (`typalt`),
 KEY `newslettertypenaktuell` (`typaktuell`),
 KEY `newslettertypenneu` (`typneu`),
 CONSTRAINT `newsletterspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenaktuell` FOREIGN KEY (`typaktuell`) REFERENCES `newsletter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenalt` FOREIGN KEY (`typalt`) REFERENCES `newsletter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenneu` FOREIGN KEY (`typneu`) REFERENCES `newsletter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1
