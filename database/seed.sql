-- ============================================================
-- Sörpong Bajnokság - Alap adatok (seed)
-- Futtatás: mysql -u <user> -p <adatbazis> < seed.sql
-- ============================================================
-- FONTOS: Jelszavak élesben való deploy előtt változtasd meg!
-- Admin jelszó: admin1234
-- Csapat jelszó: csapat1234
-- ============================================================

SET NAMES utf8mb4;

-- Admin felhasználó
INSERT INTO `users` (`name`, `email`, `password`, `is_admin`, `created_at`, `updated_at`) VALUES
('Admin', 'admin@sorpong.hu', '$2y$12$3NkzOvLLvoHIInHJUxX2TOZl9Wo.PqgOINcSqoyOnJisqK.Wsql3G', 1, NOW(), NOW());

-- Minta csapatok
INSERT INTO `teams` (`name`, `email`, `password`, `contact_name`, `created_at`, `updated_at`) VALUES
('Sörtámadók',    'sortamadok@email.hu',    '$2y$12$XIqjoN3o9gDJrSPZszxxneyzaV5753rhLQOjAWZoUUdPOONAA0JVW', 'Kiss Péter',    NOW(), NOW()),
('Pong Királyok', 'pongkiralyok@email.hu',  '$2y$12$XIqjoN3o9gDJrSPZszxxneyzaV5753rhLQOjAWZoUUdPOONAA0JVW', 'Nagy Gábor',    NOW(), NOW()),
('Habos Bajnokok','habosbajnokok@email.hu', '$2y$12$XIqjoN3o9gDJrSPZszxxneyzaV5753rhLQOjAWZoUUdPOONAA0JVW', 'Tóth Balázs',   NOW(), NOW()),
('Üveges Hősök',  'uvegeshosok@email.hu',   '$2y$12$XIqjoN3o9gDJrSPZszxxneyzaV5753rhLQOjAWZoUUdPOONAA0JVW', 'Varga Dávid',   NOW(), NOW()),
('Sörös Legendák','soroslegendak@email.hu', '$2y$12$XIqjoN3o9gDJrSPZszxxneyzaV5753rhLQOjAWZoUUdPOONAA0JVW', 'Kovács Zsolt',  NOW(), NOW()),
('Pohár Mesterei','poharmesterei@email.hu', '$2y$12$XIqjoN3o9gDJrSPZszxxneyzaV5753rhLQOjAWZoUUdPOONAA0JVW', 'Szabó Bence',   NOW(), NOW());

-- Minta esemény (ma + 14 nap)
INSERT INTO `events` (`name`, `description`, `location`, `event_date`, `registration_deadline`, `max_teams`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(
    'Tavasz Kupa 2025',
    'Az első tavaszi sörpong bajnokság! Legyél ott, és mutasd meg ki a legjobb!',
    'Budapest, Söröző utca 1.',
    DATE_ADD(NOW(), INTERVAL 14 DAY),
    DATE_ADD(NOW(), INTERVAL 7 DAY),
    16,
    'registration_open',
    1,
    NOW(),
    NOW()
);
