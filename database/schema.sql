-- ============================================================
-- Sörpong Bajnokság - Adatbázis séma (MySQL)
-- Generált: 2025
-- Futtatás: mysql -u <user> -p <adatbazis> < schema.sql
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- users (admin felhasználók)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(255)    NOT NULL,
    `email`             VARCHAR(255)    NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP       NULL DEFAULT NULL,
    `password`          VARCHAR(255)    NOT NULL,
    `is_admin`          TINYINT(1)      NOT NULL DEFAULT 0,
    `remember_token`    VARCHAR(100)    NULL DEFAULT NULL,
    `created_at`        TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- teams (regisztrált csapatok)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `teams` (
    `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                 VARCHAR(255)    NOT NULL UNIQUE,
    `email`                VARCHAR(255)    NOT NULL UNIQUE,
    `password`             VARCHAR(255)    NOT NULL,
    `contact_name`         VARCHAR(255)    NULL DEFAULT NULL,
    `contact_phone`        VARCHAR(50)     NULL DEFAULT NULL,
    `total_events`         INT             NOT NULL DEFAULT 0,
    `total_wins`           INT             NOT NULL DEFAULT 0,
    `total_losses`         INT             NOT NULL DEFAULT 0,
    `total_draws`          INT             NOT NULL DEFAULT 0,
    `total_cups_scored`    INT             NOT NULL DEFAULT 0,
    `total_cups_conceded`  INT             NOT NULL DEFAULT 0,
    `remember_token`       VARCHAR(100)    NULL DEFAULT NULL,
    `created_at`           TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`           TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- events (versenyek / események)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
    `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`                  VARCHAR(255)    NOT NULL,
    `description`           TEXT            NULL DEFAULT NULL,
    `location`              VARCHAR(255)    NULL DEFAULT NULL,
    `event_date`            DATETIME        NOT NULL,
    `registration_deadline` DATETIME        NULL DEFAULT NULL,
    `max_teams`             INT             NOT NULL,
    `tables_count`          INT             NOT NULL DEFAULT 1,
    `status`                VARCHAR(50)     NOT NULL DEFAULT 'registration_open'
                            COMMENT 'registration_open | registration_closed | group_stage | knockout_stage | finished',
    `created_by`            BIGINT UNSIGNED NOT NULL,
    `created_at`            TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`            TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_events_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- event_registrations (nevezések)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_registrations` (
    `id`                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `event_id`              BIGINT UNSIGNED NOT NULL,
    `team_id`               BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'NULL = vendég nevezés',
    `guest_team_name`       VARCHAR(255)    NULL DEFAULT NULL,
    `guest_contact_name`    VARCHAR(255)    NULL DEFAULT NULL,
    `guest_contact_email`   VARCHAR(255)    NULL DEFAULT NULL,
    `guest_contact_phone`   VARCHAR(50)     NULL DEFAULT NULL,
    `status`                VARCHAR(20)     NOT NULL DEFAULT 'confirmed'
                            COMMENT 'pending | confirmed | rejected',
    `created_at`            TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`            TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_reg_event`  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reg_team`   FOREIGN KEY (`team_id`)  REFERENCES `teams`  (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- groups (csoportok az eseményen belül)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `groups` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `event_id`   BIGINT UNSIGNED NOT NULL,
    `name`       VARCHAR(255)    NOT NULL COMMENT 'pl. A csoport, B csoport',
    `order`      INT             NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP       NULL DEFAULT NULL,
    `updated_at` TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_groups_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- group_teams (csapat a csoportban + csoport tabella)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `group_teams` (
    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `group_id`        BIGINT UNSIGNED NOT NULL,
    `registration_id` BIGINT UNSIGNED NOT NULL,
    `points`          INT             NOT NULL DEFAULT 0,
    `wins`            INT             NOT NULL DEFAULT 0,
    `draws`           INT             NOT NULL DEFAULT 0,
    `losses`          INT             NOT NULL DEFAULT 0,
    `cups_scored`     INT             NOT NULL DEFAULT 0,
    `cups_conceded`   INT             NOT NULL DEFAULT 0,
    `cup_diff`        INT             NOT NULL DEFAULT 0,
    `played`          INT             NOT NULL DEFAULT 0,
    `created_at`      TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`      TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_gt_group`    FOREIGN KEY (`group_id`)        REFERENCES `groups`             (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_gt_reg`      FOREIGN KEY (`registration_id`) REFERENCES `event_registrations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- rounds (fordulók csoporton belül)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rounds` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `group_id`     BIGINT UNSIGNED NOT NULL,
    `round_number` INT             NOT NULL,
    `created_at`   TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_rounds_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- matches (mérkőzések csoportkörben)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `matches` (
    `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `round_id`             BIGINT UNSIGNED NOT NULL,
    `home_registration_id` BIGINT UNSIGNED NOT NULL,
    `away_registration_id` BIGINT UNSIGNED NOT NULL,
    `home_score`           INT             NULL DEFAULT NULL COMMENT '0-10',
    `away_score`           INT             NULL DEFAULT NULL COMMENT '0-10',
    `result`               VARCHAR(20)     NULL DEFAULT NULL
                           COMMENT 'NULL | home_win | away_win | draw',
    `is_played`            TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`           TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`           TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_match_round` FOREIGN KEY (`round_id`)             REFERENCES `rounds`             (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_match_home`  FOREIGN KEY (`home_registration_id`) REFERENCES `event_registrations`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_match_away`  FOREIGN KEY (`away_registration_id`) REFERENCES `event_registrations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- knockout_matches (egyenes kieséses mérkőzések)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `knockout_matches` (
    `id`                     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `event_id`               BIGINT UNSIGNED NOT NULL,
    `round`                  INT             NOT NULL
                             COMMENT '2=döntő, 4=elődöntő, 8=negyeddöntő stb.',
    `match_number`           INT             NOT NULL,
    `home_registration_id`   BIGINT UNSIGNED NULL DEFAULT NULL,
    `away_registration_id`   BIGINT UNSIGNED NULL DEFAULT NULL,
    `home_score`             INT             NULL DEFAULT NULL,
    `away_score`             INT             NULL DEFAULT NULL,
    `winner_registration_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `is_played`              TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`             TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`             TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_ko_event`   FOREIGN KEY (`event_id`)               REFERENCES `events`             (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ko_home`    FOREIGN KEY (`home_registration_id`)    REFERENCES `event_registrations`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_ko_away`    FOREIGN KEY (`away_registration_id`)    REFERENCES `event_registrations`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_ko_winner`  FOREIGN KEY (`winner_registration_id`)  REFERENCES `event_registrations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Laravel rendszer táblák
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cache` (
    `key`        VARCHAR(255) NOT NULL,
    `value`      MEDIUMTEXT   NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
    `key`        VARCHAR(255) NOT NULL,
    `owner`      VARCHAR(255) NOT NULL,
    `expiration` INT          NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
    `id`            VARCHAR(255)    NOT NULL,
    `user_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address`    VARCHAR(45)     NULL DEFAULT NULL,
    `user_agent`    TEXT            NULL DEFAULT NULL,
    `payload`       LONGTEXT        NOT NULL,
    `last_activity` INT             NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue`        VARCHAR(255)    NOT NULL,
    `payload`      LONGTEXT        NOT NULL,
    `attempts`     TINYINT UNSIGNED NOT NULL,
    `reserved_at`  INT UNSIGNED    NULL DEFAULT NULL,
    `available_at` INT UNSIGNED    NOT NULL,
    `created_at`   INT UNSIGNED    NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
    `id`             VARCHAR(255) NOT NULL,
    `name`           VARCHAR(255) NOT NULL,
    `total_jobs`     INT          NOT NULL,
    `pending_jobs`   INT          NOT NULL,
    `failed_jobs`    INT          NOT NULL,
    `failed_job_ids` LONGTEXT     NOT NULL,
    `options`        MEDIUMTEXT   NULL DEFAULT NULL,
    `cancelled_at`   INT          NULL DEFAULT NULL,
    `created_at`     INT          NOT NULL,
    `finished_at`    INT          NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid`       VARCHAR(255)    NOT NULL UNIQUE,
    `connection` TEXT            NOT NULL,
    `queue`      TEXT            NOT NULL,
    `payload`    LONGTEXT        NOT NULL,
    `exception`  LONGTEXT        NOT NULL,
    `failed_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
    `email`      VARCHAR(255) NOT NULL,
    `token`      VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP    NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
    `id`        INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255)    NOT NULL,
    `batch`     INT             NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
