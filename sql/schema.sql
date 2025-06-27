-- USERS TABLE
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(10) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(125) NOT NULL,
  `type` ENUM('admin','user') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- LOCATIONS TABLE
CREATE TABLE `locations` (
  `location_id` INT NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(100) NOT NULL,
  `total_stations` INT UNSIGNED NOT NULL,
  `cost_per_hour` DECIMAL(3,2) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `idx_locations_description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SESSIONS TABLE
CREATE TABLE `sessions` (
  `session_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `location_id` INT NOT NULL,
  `check_in` DATETIME NOT NULL,
  `check_out` DATETIME DEFAULT NULL,
  `cost` DECIMAL(9,2) DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `idx_sessions_user` (`user_id`),
  KEY `idx_sessions_location` (`location_id`),
  CONSTRAINT `fk_sessions_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
      ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sessions_location`
    FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
