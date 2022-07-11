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