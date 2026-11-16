-- TaskFlow Database Schema & Seed Data
-- Production-ready schema for MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS `rtih_task_tracker`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `rtih_task_tracker`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- Users table
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tasks table
CREATE TABLE `tasks` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `status` ENUM('Pending', 'In Progress', 'Completed') NOT NULL DEFAULT 'Pending',
    `priority` ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
    `due_date` DATE NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tasks_user_id` (`user_id`),
    KEY `idx_tasks_user_status` (`user_id`, `status`),
    KEY `idx_tasks_user_title` (`user_id`, `title`),
    CONSTRAINT `fk_tasks_user` FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Data (Password for all demo accounts: password123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Alex Rivera', 'alex@example.com', '$2y$10$3GqbOwo/8M5lWsZ8hb5eOuS.e5Rd2UQfSfdSJHLaRobPkm/6hmd9y', NOW()),
(2, 'Sarah Chen', 'sarah@example.com', '$2y$10$3GqbOwo/8M5lWsZ8hb5eOuS.e5Rd2UQfSfdSJHLaRobPkm/6hmd9y', NOW());

-- Tasks for Alex Rivera
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(1, 1, 'Finalize database schema migration and sprint backlog', 'Sync with engineering leads to groom priority items, estimate story points, and set release dates.', 'Completed', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 1, 'Refactor auth middleware and token validation', 'Migrate legacy token checks to constant-time comparison and add rate limiting headers to prevent abuse.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 1, 'Review cloud infrastructure cost breakdown', 'Analyze compute and storage utilization across AWS staging instances to identify idle resources.', 'In Progress', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 4 DAY), NOW()),
(4, 1, 'Prepare technical slide deck for stakeholders', 'Summarize key deliverables, system performance benchmarks, and next quarter roadmap initiatives.', 'Pending', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 6 DAY), NOW()),
(5, 1, 'Update developer setup documentation in README', 'Ensure local Docker compose files and environment sample files are documented with step-by-step commands.', 'Pending', 'Low', DATE_ADD(CURRENT_DATE(), INTERVAL 8 DAY), NOW());

-- Tasks for Sarah Chen (Demonstrates multi-tenant isolation)
INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `due_date`, `created_at`) VALUES
(6, 2, 'Audit UI accessibility and WCAG color contrast', 'Run Lighthouse and Axe DevTools on core application flows to ensure AA level compliance on high-traffic pages.', 'In Progress', 'High', DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), NOW()),
(7, 2, 'Customer satisfaction survey analysis', 'Compile feedback from the November cohort and extract top 3 requested feature improvements.', 'Completed', 'Medium', DATE_ADD(CURRENT_DATE(), INTERVAL 5 DAY), NOW());
