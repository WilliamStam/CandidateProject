CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `context` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
);

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT primary key,
  `service` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
);


CREATE TABLE `subscriptions` (
    `uuid` varchar(36) DEFAULT (uuid_to_bin(uuid())) not null primary key,
    `msisdn` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `service_id` int(11) DEFAULT NULL,
    `charged_at` datetime DEFAULT NULL,
    `canceled_at` datetime DEFAULT NULL,
    `is_active` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT current_timestamp(),
    `updated_at` datetime DEFAULT current_timestamp(),
    UNIQUE KEY `unique` (`msisdn`,`service_id`),
    KEY `msisdn_id` (`msisdn`),
    KEY `service_id` (`service_id`)
);




INSERT INTO `services` (`id`, `service`) VALUES
(1, 'netflix'),
(2, 'youtube')
;



CREATE OR REPLACE VIEW VIEW_subscriptions AS
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