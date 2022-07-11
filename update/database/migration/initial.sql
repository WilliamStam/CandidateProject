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

INSERT INTO `services` (`id`, `service`) VALUES
(1, 'netflix'),
(2, 'youtube');

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

CREATE TABLE `view_subscriptions` (
`uuid` varchar(40)
,`msisdn` varchar(11)
,`charged_at` datetime
,`canceled_at` datetime
,`is_active` tinyint(1)
,`created_at` datetime
,`updated_at` datetime
,`service_id` int(11)
,`service` varchar(100)
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

