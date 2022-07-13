CREATE TABLE `logs` (
  `id` INT NOT NULL AUTO_INCREMENT primary key,
  `level` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` MEDIUMTEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `context` MEDIUMTEXT COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` DATETIME DEFAULT current_timestamp()
);

CREATE TABLE `services` (
  `id` INT NOT NULL AUTO_INCREMENT primary key,
  `service` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
);

CREATE TABLE `subscriptions` (
    `uuid` VARCHAR(50) DEFAULT (uuid()) not null primary key,
    `msisdn` VARCHAR(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `service_id` INT DEFAULT NULL,
    `charged_at` DATETIME DEFAULT NULL,
    `canceled_at` DATETIME DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT current_timestamp(),
    `updated_at` DATETIME DEFAULT current_timestamp(),
    UNIQUE KEY `unique` (`msisdn`,`service_id`),
    KEY `msisdn_id` (`msisdn`),
    KEY `service_id` (`service_id`)
);

CREATE VIEW view_subscriptions AS
SELECT
    subscriptions.`uuid`,
    subscriptions.`msisdn`,
    subscriptions.`charged_at`,
    subscriptions.`canceled_at`,
    subscriptions.`is_active`,
    subscriptions.`created_at`,
    subscriptions.`updated_at`,
    services.id AS service_id,
    services.service
FROM
    subscriptions
        JOIN services
            ON services.id = subscriptions.service_id
;


INSERT INTO `services` (`id`, `service`) VALUES
(1, 'netflix'),
(2, 'youtube')
;