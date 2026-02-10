CREATE TABLE IF NOT EXISTS `#__glavpro_companies` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `stage_code` VARCHAR(32) NOT NULL DEFAULT 'Ice',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_stage_code` (`stage_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `#__glavpro_crm_events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` INT UNSIGNED NOT NULL,
  `event_type` VARCHAR(64) NOT NULL,
  `payload` JSON NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_company_event_time` (`company_id`, `event_type`, `created_at`),
  CONSTRAINT `fk_events_company` FOREIGN KEY (`company_id`) REFERENCES `#__glavpro_companies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
