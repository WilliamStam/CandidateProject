CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `log` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `context` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
);

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
);


CREATE TABLE `subscriptions` (
  `uuid` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msisdn` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `charged_at` datetime DEFAULT NULL,
  `canceled_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
);


ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `msisdn_id` (`msisdn`),
  ADD KEY `service_id` (`service_id`);

ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


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