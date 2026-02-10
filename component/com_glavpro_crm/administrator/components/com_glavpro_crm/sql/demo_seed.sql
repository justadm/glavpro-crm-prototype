INSERT INTO `#__glavpro_companies` (`name`, `stage_code`, `created_at`, `updated_at`)
VALUES ('Demo Company', 'Ice', NOW(), NOW());

SET @company_id = LAST_INSERT_ID();

INSERT INTO `#__glavpro_crm_events` (`company_id`, `event_type`, `payload`, `created_at`)
VALUES
(@company_id, 'attempt_contact', '{}', NOW());
