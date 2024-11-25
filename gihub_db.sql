-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2024 at 05:44 AM
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
-- Database: `gihub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `project_name` varchar(100) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) NOT NULL,
  `external_link` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `project_members` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `user_id`, `project_name`, `upload_date`, `file_path`, `external_link`, `description`, `photo_path`, `project_members`) VALUES
(1, 1, 'myprooo', '2024-11-13 02:22:00', 'uploads/1/Untitled.zip', NULL, NULL, NULL, ''),
(2, 1, 'newmm', '2024-11-13 02:24:22', 'uploads/1/Untitled.zip', NULL, NULL, NULL, ''),
(3, 1, 'newmm', '2024-11-13 02:27:28', 'uploads/1/Untitled.zip', NULL, NULL, NULL, ''),
(4, 1, 'newmm', '2024-11-13 02:27:33', 'uploads/1/Untitled.zip', NULL, NULL, NULL, ''),
(5, 1, 'newmm', '2024-11-13 02:27:49', 'uploads/1/Untitled.zip', NULL, NULL, NULL, ''),
(17, 3, 'Book Borrowing System', '2024-11-21 08:47:00', 'uploads/files/6742a10f75248_expend4bles.(2023).eng.1cd.(9748927).zip', 'http://localhost/booksystem/', 'This is a simple book borrowing', 'uploads/photos/6742a10f74c6f_Screenshot (184).png', 'Kevin Agliparia, Wishnie Jane Mino'),
(19, 3, 'Home Service', '2024-11-22 12:14:08', 'uploads/files/6742a0c8a1954_the.last.voyage.of.the.demeter.(2023).eng.1cd.(9695821).zip', 'http://localhost/homeservices/', 'A Simple Book Borrowing System\r\n', 'uploads/photos/6742a0c8a14f9_Screenshot (186).png', 'Aj Arns,Allen Mae'),
(26, 3, 'Online-Library-Management', '2024-11-23 09:04:38', 'uploads/files/3/67419aa6e4397_Online-Library-Management-System-PHP.zip', 'http://localhost/Online-Library-Management/library/', 'A Simple Library System A Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library SystemA Simple Library System', 'uploads/photos/3/67419aa6e3e8e_Screenshot (175).png', 'Clark Melvin Tigoy, Criztel Marize Benan'),
(27, 3, 'sample', '2024-11-24 14:23:34', 'uploads/files/3/674336e6de4b3_Bleach-vs-One-Piece-13.zip', 'https://example.com/', 'This an Example', 'uploads/photos/3/674336e6ddf2a_Picture1.jpg', 'Array'),
(28, 3, 'rentalmanagementtrial', '2024-11-24 14:27:38', 'uploads/files/3/674337da3451a_expend4bles.(2023).eng.1cd.(9748927).zip', 'http://localhost/phpmyadmin/index.php', 'ghghghhg', 'uploads/photos/3/674337da340e4_Screenshot (181).png', 'Array');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'user1', '$2y$10$a1hS9CmcA0Y4jIY01dEvTebygVlPNi5Mluzns3rqK.4Z1WlHJkP5i', 'student'),
(3, 'agliparia.kevin@gmail.com', '$2y$10$1OFJh98sYAz5pCP51E3zAuegutRkEWhgXIQM2c.Q3CDKQFWQfwnXS', 'student'),
(4, 'test@gmail.com', '$2y$10$QeUW2SmNsu5s4IWrOzRy..IVB8poQja2q7pTc648naOKpMxJ/EJlG', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
