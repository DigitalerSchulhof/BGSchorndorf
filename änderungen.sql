-- Format:
--
-- <Commit Hash, seit dem die SQL notwendig ist>:
-- <SQL Änderungen>
ALTER TABLE `rythmisierung` CHANGE `jahr` `beginn` BIGINT(255) UNSIGNED NOT NULL;
