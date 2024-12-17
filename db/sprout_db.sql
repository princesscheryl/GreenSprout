-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 17, 2024 at 03:52 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sprout_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gardening_tips`
--

CREATE TABLE `gardening_tips` (
  `tip_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `category` enum('General','Seasonal','Plant-Specific','Pest Control','Disease') DEFAULT NULL,
  `skill_level` enum('Beginner','Intermediate','Advanced') DEFAULT NULL,
  `season` enum('Spring','Summer','Fall','Winter','All-Season') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `growth_tracking`
--

CREATE TABLE `growth_tracking` (
  `tracking_id` int(11) NOT NULL,
  `user_plant_id` int(11) NOT NULL,
  `recorded_date` datetime DEFAULT current_timestamp(),
  `height` decimal(5,2) DEFAULT NULL,
  `has_new_leaves` tinyint(1) DEFAULT 0,
  `has_flowers` tinyint(1) DEFAULT 0,
  `has_fruits` tinyint(1) DEFAULT 0,
  `wetness_level` enum('Dry','Moist','Wet') DEFAULT NULL,
  `is_wilting` tinyint(1) DEFAULT 0,
  `is_flaccid` tinyint(1) DEFAULT 0,
  `photo_url` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `growth_tracking`
--

INSERT INTO `growth_tracking` (`tracking_id`, `user_plant_id`, `recorded_date`, `height`, `has_new_leaves`, `has_flowers`, `has_fruits`, `wetness_level`, `is_wilting`, `is_flaccid`, `photo_url`, `notes`) VALUES
(1, 2, '2024-12-16 21:22:30', 2.50, 0, 1, 0, 'Moist', 0, 1, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `planting_schedules`
--

CREATE TABLE `planting_schedules` (
  `schedule_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plant_name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `current_stage` enum('Seed','Sprout','Seedling','Growing','Mature') DEFAULT 'Seed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planting_schedules`
--

INSERT INTO `planting_schedules` (`schedule_id`, `user_id`, `plant_name`, `start_date`, `current_stage`, `notes`, `created_at`) VALUES
(6, 3, 'monstera', '2024-12-13', 'Mature', 'hello', '2024-12-13 21:31:07'),
(7, 3, 'Monstera', '2024-12-13', 'Mature', 'new seed', '2024-12-13 21:50:52');

-- --------------------------------------------------------

--
-- Table structure for table `plant_catalog`
--

CREATE TABLE `plant_catalog` (
  `plant_id` int(11) NOT NULL,
  `common_name` varchar(100) NOT NULL,
  `scientific_name` varchar(100) DEFAULT NULL,
  `skill_level` enum('Beginner','Intermediate','Advanced') NOT NULL,
  `water_needs` enum('Low','Moderate','High') NOT NULL,
  `sunlight_needs` enum('Low','Indirect','Direct') NOT NULL,
  `temperature_min` decimal(4,1) DEFAULT NULL,
  `temperature_max` decimal(4,1) DEFAULT NULL,
  `humidity_needs` enum('Low','Moderate','High') NOT NULL,
  `description` text DEFAULT NULL,
  `care_instructions` text DEFAULT NULL,
  `seasonal_info` text DEFAULT NULL,
  `best_season` enum('Spring','Summer','Fall','Winter','All-Season') DEFAULT 'All-Season'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plant_reminders`
--

CREATE TABLE `plant_reminders` (
  `reminder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `care_type` varchar(50) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `likes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `image_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tasks`
--

CREATE TABLE `schedule_tasks` (
  `task_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `task_type` enum('Transfer','Water','Fertilize','Prune','Other') NOT NULL,
  `due_date` date NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `notification_sent` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule_tasks`
--

INSERT INTO `schedule_tasks` (`task_id`, `schedule_id`, `task_type`, `due_date`, `completed`, `notification_sent`, `notes`, `completed_at`) VALUES
(1, 6, 'Fertilize', '2024-12-13', 1, 0, 'hu', NULL),
(2, 7, 'Fertilize', '2024-12-14', 1, 0, 'none', '2024-12-13 21:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `seed_catalog`
--

CREATE TABLE `seed_catalog` (
  `seed_id` int(11) NOT NULL,
  `plant_id` int(11) NOT NULL,
  `seed_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `characteristics` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `weather_unit` enum('celsius','fahrenheit') DEFAULT 'celsius',
  `current_streak` int(11) DEFAULT 0,
  `longest_streak` int(11) DEFAULT 0,
  `last_tracking_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `skill_level` enum('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
  `is_active` tinyint(1) DEFAULT 1,
  `role` enum('user','admin','super_admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password_hash`, `city`, `country_code`, `weather_unit`, `current_streak`, `longest_streak`, `last_tracking_date`, `created_at`, `last_login`, `profile_picture`, `skill_level`, `is_active`, `role`) VALUES
(2, NULL, NULL, 'dede_a12', 'dede12@gmail.com', '$2y$10$rjhSse42t/t3Lm41WTKOoOUKhIhTf744shqP.LWNJvKQHv/mjZEay', NULL, NULL, 'celsius', 1, 0, NULL, '2024-12-11 15:24:47', '2024-12-11 19:55:20', NULL, 'Beginner', 1, 'user'),
(3, 'Cheryl', 'Donkor', 'cherryc123', 'princess10@gmail.com', '$2y$10$zdO4K68xxHx5/c0PRfKvKee439mWfwzqX94sD7.HigNtMLzCgyshy', NULL, NULL, 'celsius', 0, 0, NULL, '2024-12-13 01:32:15', NULL, NULL, 'Beginner', 1, 'user'),
(4, 'Cluade', 'Josh', 'joshyk2', 'joshua17@gmail.com', '$2y$10$a.byE88oFnXGRPQJY1k0QeriC6eizoWAHy4hYmIucFl1libxwV8gm', NULL, NULL, 'celsius', 0, 0, NULL, '2024-12-15 22:43:13', NULL, NULL, 'Beginner', 1, 'user'),
(6, 'Princess', 'Donkor', 'superadmin', 'superadmin@gmail.com', '$2y$10$BnNsSwsSEBtExLjr00scvOSt2rAkmsA4.Pfjj2Mu91.apWsYtVK9O', NULL, NULL, 'celsius', 0, 0, NULL, '2024-12-17 00:58:10', NULL, NULL, 'Beginner', 1, 'super_admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_plants`
--

CREATE TABLE `user_plants` (
  `user_plant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `plant_type` varchar(50) DEFAULT NULL,
  `watering_frequency` int(11) DEFAULT NULL,
  `nickname` varchar(100) NOT NULL,
  `planted_date` datetime DEFAULT current_timestamp(),
  `last_watered` datetime DEFAULT NULL,
  `last_fertilized` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_plants`
--

INSERT INTO `user_plants` (`user_plant_id`, `user_id`, `image_url`, `plant_type`, `watering_frequency`, `nickname`, `planted_date`, `last_watered`, `last_fertilized`, `notes`, `status`) VALUES
(1, 2, NULL, 'Indoor', 2, 'Monstera', '2024-12-12 19:49:52', '2024-12-12 19:49:52', '2024-12-13 00:58:13', NULL, 'Active'),
(2, 3, NULL, 'Indoor', 7, 'Monstera', '2024-12-16 00:00:00', '2024-12-16 20:52:05', '2024-12-16 22:00:06', NULL, 'Active'),
(3, 3, NULL, 'Indoor', 2, 'Monstera', '2024-12-16 00:00:00', NULL, NULL, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_streaks`
--

CREATE TABLE `user_streaks` (
  `streak_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `streak_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_streaks`
--

INSERT INTO `user_streaks` (`streak_id`, `user_id`, `streak_date`) VALUES
(1, 2, '2024-12-13'),
(3, 3, '2024-12-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `gardening_tips`
--
ALTER TABLE `gardening_tips`
  ADD PRIMARY KEY (`tip_id`);

--
-- Indexes for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  ADD PRIMARY KEY (`tracking_id`),
  ADD KEY `user_plant_id` (`user_plant_id`);

--
-- Indexes for table `planting_schedules`
--
ALTER TABLE `planting_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `plant_catalog`
--
ALTER TABLE `plant_catalog`
  ADD PRIMARY KEY (`plant_id`);

--
-- Indexes for table `plant_reminders`
--
ALTER TABLE `plant_reminders`
  ADD PRIMARY KEY (`reminder_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `schedule_tasks`
--
ALTER TABLE `schedule_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `seed_catalog`
--
ALTER TABLE `seed_catalog`
  ADD PRIMARY KEY (`seed_id`),
  ADD KEY `plant_id` (`plant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_plants`
--
ALTER TABLE `user_plants`
  ADD PRIMARY KEY (`user_plant_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_streaks`
--
ALTER TABLE `user_streaks`
  ADD PRIMARY KEY (`streak_id`),
  ADD UNIQUE KEY `unique_user_date` (`user_id`,`streak_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gardening_tips`
--
ALTER TABLE `gardening_tips`
  MODIFY `tip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `planting_schedules`
--
ALTER TABLE `planting_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `plant_catalog`
--
ALTER TABLE `plant_catalog`
  MODIFY `plant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plant_reminders`
--
ALTER TABLE `plant_reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_tasks`
--
ALTER TABLE `schedule_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seed_catalog`
--
ALTER TABLE `seed_catalog`
  MODIFY `seed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_plants`
--
ALTER TABLE `user_plants`
  MODIFY `user_plant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_streaks`
--
ALTER TABLE `user_streaks`
  MODIFY `streak_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`comment_id`);

--
-- Constraints for table `growth_tracking`
--
ALTER TABLE `growth_tracking`
  ADD CONSTRAINT `growth_tracking_ibfk_1` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plants` (`user_plant_id`) ON DELETE CASCADE;

--
-- Constraints for table `planting_schedules`
--
ALTER TABLE `planting_schedules`
  ADD CONSTRAINT `planting_schedules_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `plant_reminders`
--
ALTER TABLE `plant_reminders`
  ADD CONSTRAINT `plant_reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`);

--
-- Constraints for table `schedule_tasks`
--
ALTER TABLE `schedule_tasks`
  ADD CONSTRAINT `schedule_tasks_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `planting_schedules` (`schedule_id`);

--
-- Constraints for table `seed_catalog`
--
ALTER TABLE `seed_catalog`
  ADD CONSTRAINT `seed_catalog_ibfk_1` FOREIGN KEY (`plant_id`) REFERENCES `plant_catalog` (`plant_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_plants`
--
ALTER TABLE `user_plants`
  ADD CONSTRAINT `user_plants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_streaks`
--
ALTER TABLE `user_streaks`
  ADD CONSTRAINT `user_streaks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
