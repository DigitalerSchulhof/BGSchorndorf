-- Änderungen sind in GitHub nachverfolgbar
--
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

CREATE TABLE `wnewsletter` (
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
 CONSTRAINT `newslettertypenaktuell` FOREIGN KEY (`typaktuell`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenalt` FOREIGN KEY (`typalt`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenneu` FOREIGN KEY (`typneu`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `newsletterempfaenger` (
 `ìd` bigint(255) NOT NULL,
 `name` varbinary(5000) NOT NULL,
 `email` varbinary(5000) NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 `token` varbinary(5000) NOT NULL,
 `idvon` bigint(255) unsigned DEFAULT NULL,
 `idzeit` bigint(255) unsigned DEFAULT NULL,
 KEY `newsletterempfaengernewsletter` (`newsletter`),
 CONSTRAINT `newsletterempfaengernewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `gremiennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppengremiennewsletter` (`gruppe`),
 KEY `newslettergremiennewsletter` (`newsletter`),
 CONSTRAINT `newslettergremiennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppengremiennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `fachschaftennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenfachschaftennewsletter` (`gruppe`),
 KEY `newsletterfachschaftennewsletter` (`newsletter`),
 CONSTRAINT `newsletterfachschaftennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenfachschaftennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `klassennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenklassennewsletter` (`gruppe`),
 KEY `newsletterklassennewsletter` (`newsletter`),
 CONSTRAINT `newsletterklassennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenklassennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `kursenewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenkursenewsletter` (`gruppe`),
 KEY `newsletterkursenewsletter` (`newsletter`),
 CONSTRAINT `newsletterkursenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenkursenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `stufennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenstufennewsletter` (`gruppe`),
 KEY `newsletterstufennewsletter` (`newsletter`),
 CONSTRAINT `newsletterstufennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenstufennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `arbeitsgemeinschaftennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenarbeitsgemeinschaftennewsletter` (`gruppe`),
 KEY `newsletterarbeitsgemeinschaftennewsletter` (`newsletter`),
 CONSTRAINT `newsletterarbeitsgemeinschaftennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenarbeitsgemeinschaftennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `arbeitskreisenewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenarbeitskreisenewsletter` (`gruppe`),
 KEY `newsletterarbeitskreisenewsletter` (`newsletter`),
 CONSTRAINT `newsletterarbeitskreisenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenarbeitskreisenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `fahrtennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenfahrtennewsletter` (`gruppe`),
 KEY `newsletterfahrtennewsletter` (`newsletter`),
 CONSTRAINT `newsletterfahrtennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenfahrtennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `wettbewerbenewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenwettbewerbenewsletter` (`gruppe`),
 KEY `newsletterwettbewerbenewsletter` (`newsletter`),
 CONSTRAINT `newsletterwettbewerbenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenwettbewerbenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ereignissenewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppenereignissenewsletter` (`gruppe`),
 KEY `newsletterereignissenewsletter` (`newsletter`),
 CONSTRAINT `newsletterereignissenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenereignissenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sonstigegruppennewsletter` (
 `gruppe` bigint(255) unsigned NOT NULL,
 `newsletter` bigint(255) unsigned NOT NULL,
 KEY `gruppensonstigegruppennewsletter` (`gruppe`),
 KEY `newslettersonstigegruppennewsletter` (`newsletter`),
 CONSTRAINT `newslettersonstigegruppennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppensonstigegruppennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
