-- ---------------------------------------------------------------------------
-- Door change history + Selected Options check
--
-- Run this once per environment (phpMyAdmin, MySQL Workbench, or the CLI).
-- Safe to run more than once - every statement is IF NOT EXISTS, so it will
-- not touch tables that already exist and will not delete any data.
--
-- No existing table is modified. These three are new.
-- ---------------------------------------------------------------------------


-- 1. Door change history -----------------------------------------------------
-- One row per field changed on a door: what it was, what it became, who did
-- it and when. Written automatically whenever a door is updated.

CREATE TABLE IF NOT EXISTS `door_change_logs` (
  `id`           bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id`      bigint(20) unsigned DEFAULT NULL,
  `quotation_id` bigint(20) unsigned DEFAULT NULL,
  `version_id`   bigint(20) unsigned DEFAULT NULL,
  `door_type`    varchar(255) DEFAULT NULL,
  `action`       varchar(20) NOT NULL DEFAULT 'updated',
  `field`        varchar(255) DEFAULT NULL,
  `label`        varchar(255) DEFAULT NULL,
  `old_value`    text DEFAULT NULL,
  `new_value`    text DEFAULT NULL,
  `changed_by`   bigint(20) unsigned DEFAULT NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dcl_quote_version_idx` (`quotation_id`,`version_id`),
  KEY `dcl_item_idx` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2. Selected Options add / remove log ---------------------------------------
-- What was added to or removed from Selected Options. `created_at` is when the
-- Options Check page DETECTED the change, not when someone made it.

CREATE TABLE IF NOT EXISTS `selected_option_logs` (
  `id`           bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id`     bigint(20) unsigned DEFAULT NULL,
  `option_type`  varchar(255) DEFAULT NULL,
  `option_key`   varchar(255) DEFAULT NULL,
  `option_label` varchar(255) DEFAULT NULL,
  `action`       varchar(20) DEFAULT NULL,
  `detected_by`  bigint(20) unsigned DEFAULT NULL,
  `created_at`   timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sol_owner_type_idx` (`owner_id`,`option_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 3. Selected Options snapshot -----------------------------------------------
-- The baseline the log above is compared against. Filled automatically the
-- first time the Options Check page is opened. Leave it empty.

CREATE TABLE IF NOT EXISTS `selected_option_snapshots` (
  `id`          bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id`    bigint(20) unsigned DEFAULT NULL,
  `option_type` varchar(255) DEFAULT NULL,
  `option_key`  varchar(255) DEFAULT NULL,
  `created_at`  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sos_owner_type_idx` (`owner_id`,`option_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
