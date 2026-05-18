CREATE DATABASE IF NOT EXISTS `netdesk` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `netdesk`;

DROP TABLE IF EXISTS `ticket_notes`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `devices`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(160) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','technician') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tickets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_code` VARCHAR(40) NOT NULL UNIQUE,
  `title` VARCHAR(180) NOT NULL,
  `category` ENUM('Network','Hardware','Software','Printer','Account','Other') NOT NULL DEFAULT 'Other',
  `priority` ENUM('Low','Medium','High') NOT NULL DEFAULT 'Low',
  `reporter_name` VARCHAR(120) NOT NULL,
  `reporter_email` VARCHAR(160) NULL,
  `description` TEXT NOT NULL,
  `status` ENUM('Open','In Progress','Resolved','Closed') NOT NULL DEFAULT 'Open',
  `created_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ticket_status` (`status`),
  KEY `idx_ticket_category` (`category`),
  KEY `idx_ticket_priority` (`priority`),
  CONSTRAINT `fk_ticket_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ticket_notes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NULL,
  `note` TEXT NOT NULL,
  `old_status` VARCHAR(30) NULL,
  `new_status` VARCHAR(30) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_note_ticket` (`ticket_id`),
  CONSTRAINT `fk_note_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_note_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `devices` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_name` VARCHAR(160) NOT NULL,
  `device_type` ENUM('Router','Switch','Access Point','Laptop','PC','Printer','Server','Other') NOT NULL DEFAULT 'Other',
  `brand` VARCHAR(100) NULL,
  `model` VARCHAR(100) NULL,
  `serial_number` VARCHAR(120) NULL,
  `ip_address` VARCHAR(45) NULL,
  `mac_address` VARCHAR(32) NULL,
  `location` VARCHAR(160) NULL,
  `status` ENUM('Active','Maintenance','Broken','Retired') NOT NULL DEFAULT 'Active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_device_type` (`device_type`),
  KEY `idx_device_status` (`status`),
  KEY `idx_device_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin NetDesk', 'admin@netdesk.local', '$2y$12$xXLhutr20u1mw4ExFlMEGeJn1Ww1nQ0K.ZiDYWS556rtvGUGOqV0q', 'admin');

INSERT INTO `tickets` (`ticket_code`, `title`, `category`, `priority`, `reporter_name`, `reporter_email`, `description`, `status`, `created_by`) VALUES
('NTD-20260518-001', 'Computer lab WiFi keeps disconnecting', 'Network', 'High', 'Computer Lab Student', 'lab@example.com', 'The computer lab WiFi often disconnects when many devices are connected.', 'In Progress', 1),
('NTD-20260518-002', 'Administration printer cannot print', 'Printer', 'Medium', 'Administration Staff', 'tu@example.com', 'The printer is detected as online, but documents stay in the queue and do not print.', 'Open', 1),
('NTD-20260518-003', 'Admin laptop is slow during startup', 'Hardware', 'Low', 'School Admin', NULL, 'The laptop takes a long time to start and often freezes.', 'Resolved', 1);

INSERT INTO `ticket_notes` (`ticket_id`, `user_id`, `note`, `old_status`, `new_status`) VALUES
(1, 1, 'Initial check: the access point may be overloaded. Channel settings and connected clients will be reviewed.', 'Open', 'In Progress'),
(3, 1, 'Cleaned startup apps and checked storage. The device is now more responsive.', 'Open', 'Resolved');

INSERT INTO `devices` (`device_name`, `device_type`, `brand`, `model`, `serial_number`, `ip_address`, `mac_address`, `location`, `status`, `notes`) VALUES
('Main Router', 'Router', 'MikroTik', 'RB750Gr3', 'MTK-001', '192.168.1.1', 'AA:BB:CC:DD:EE:01', 'Server Room', 'Active', 'Main gateway for the school network.'),
('Computer Lab AP', 'Access Point', 'TP-Link', 'EAP225', 'TPL-AP-002', '192.168.1.20', 'AA:BB:CC:DD:EE:02', 'Computer Lab', 'Maintenance', 'Channel settings and access point placement need to be checked.'),
('Administration Printer', 'Printer', 'Epson', 'L3210', 'EPS-003', '192.168.1.45', NULL, 'Administration Office', 'Active', 'Administration printer.'),
('Admin Laptop 01', 'Laptop', 'Lenovo', 'ThinkPad', 'LNV-004', NULL, NULL, 'Admin Room', 'Active', 'Used for administrative tasks.'),
('Lab Switch', 'Switch', 'D-Link', 'DES-1024D', 'DLK-005', NULL, 'AA:BB:CC:DD:EE:05', 'Computer Lab', 'Active', 'Computer lab switch.');
