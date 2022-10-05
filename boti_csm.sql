-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2022 at 01:54 PM
-- Server version: 10.3.32-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boti_csm`
--

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('number','varchar','text','html','date','datetime') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`id`, `label`, `alias`, `type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'Nom du site', 'sitename', 'varchar', 'BOTI SCHOOL', NULL, '2022-03-21 19:59:42'),
(2, 'Téléphone', 'tel', 'varchar', '06 25 96 19 72', NULL, '2022-03-16 14:40:13'),
(3, 'Téléphone 2', 'tel2', 'varchar', '06 69 65 37 69', NULL, NULL),
(7, 'Email', 'email', 'varchar', 'contact@boti.education', NULL, NULL),
(8, 'Adresse', 'adresse', 'varchar', ' Mohammed V Centre, 103, Rue Ait Baamrane,  Casablanca ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_02_134517_add_picture_user', 2),
(6, '2022_03_07_160454_add_modules', 3),
(7, '2022_03_12_100837_add_table_theme', 4),
(8, '2022_03_12_100844_add_table_schools', 4),
(9, '2022_03_16_143426_ajout_configs', 5),
(10, '2022_03_18_085033_update_schools', 6),
(11, '2022_03_18_085100_add_school_contacts', 6),
(12, '2022_03_18_085212_add_school_tickets', 6),
(13, '2022_03_18_085240_add_school_intervention', 7);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `label`, `alias`, `icone`, `user_by`, `created_at`, `updated_at`) VALUES
(1, 'Paramétrage', 'param-trage', NULL, 1, '2022-03-07 15:54:27', '2022-03-07 15:54:27'),
(2, 'Test', 'test', NULL, 1, '2022-03-12 11:20:35', '2022-03-12 11:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `module_themes`
--

CREATE TABLE `module_themes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `theme_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_themes`
--

INSERT INTO `module_themes` (`id`, `module_id`, `theme_id`, `user_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `Label`, `Alias`) VALUES
(1, 'Admin', 'admin'),
(3, 'Account Manager', 'account_manager');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pics` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `files` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effectif` int(11) DEFAULT NULL,
  `cycles` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `presentation` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_links` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `localisation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_year_boti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_manager` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `effectif_maternelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effectif_primaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effectif_college` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effectif_lycee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `logo`, `banner`, `pics`, `files`, `effectif`, `cycles`, `city`, `presentation`, `social_links`, `adresse`, `localisation`, `start_year`, `first_year_boti`, `link`, `account_manager`, `created_at`, `updated_at`, `effectif_maternelle`, `effectif_primaire`, `effectif_college`, `effectif_lycee`) VALUES
(2, 'Greenwood School', '2-greenwood-school.png', '2-greenwood-school-banner.png', NULL, NULL, 50, 'maternelle,primaire,college', 'Bouskoura', 'test', '{\"facebook\":\"fb\",\"instagram\":\"ig\",\"youtube\":\"yt\",\"website\":\"web\"}', 'test', NULL, '2020', '2021', 'https://greenwoodschool.ma/', 2, '2022-03-14 16:40:35', '2022-03-19 13:47:06', '3', '6', '7', '10'),
(3, 'Alexander Fleming', '3-alexander-fleming.jpg', '3-alexander-fleming-banner.jpg', NULL, NULL, NULL, 'maternelle,primaire', 'Casablanca', 'Alexander Fleming, l\'école innovante.', '{\"facebook\":\"\",\"instagram\":\"\",\"youtube\":\"\",\"website\":\"\"}', 'CIL', NULL, '2019', '2020', NULL, 2, '2022-03-21 11:12:48', '2022-03-21 11:14:18', '220', '430', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `school_contacts`
--

CREATE TABLE `school_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `function` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `receive_reports` tinyint(1) DEFAULT NULL,
  `edit_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_contacts`
--

INSERT INTO `school_contacts` (`id`, `school_id`, `name`, `last_name`, `function`, `phone`, `email`, `picture`, `enabled`, `order`, `receive_reports`, `edit_history`, `created_at`, `updated_at`) VALUES
(1, 2, 'Haddou', 'Sophia', 'Account Manager', '0625961972', 'sophia@blinkagency', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'Semoud', 'Ahmed', 'dev', '0625663516', 'ahmed@boti.education', '2-Semoud-Ahmed.jpg', 1, NULL, NULL, NULL, '2022-03-19 17:14:42', '2022-03-19 17:14:42'),
(3, 3, 'Dekkak', 'Aymen', 'Directeur', '0684082898', 'a.dekkak@alexanderfleming.com', NULL, 1, NULL, NULL, NULL, '2022-03-21 11:14:56', '2022-03-28 17:13:44'),
(4, 2, 'Haddou', 'Sophia', 'Account Manager', '0625961972', 'sophia@blinkagency', NULL, 1, NULL, NULL, NULL, '2022-03-28 17:09:28', '2022-03-28 17:09:28');

-- --------------------------------------------------------

--
-- Table structure for table `school_interventions`
--

CREATE TABLE `school_interventions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `collaborateurs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edit_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_interventions`
--

INSERT INTO `school_interventions` (`id`, `school_id`, `contact_id`, `responsable_id`, `collaborateurs`, `label`, `details`, `date`, `duration`, `type`, `nature`, `channel`, `state`, `state_history`, `edit_history`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 1, NULL, 'test', NULL, '2022-03-20 00:00:00', 1, 'preventive', 'formation', 'online', 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-19 13:18:00\"}]', NULL, NULL, '2022-03-19 12:18:00', '2022-03-19 12:18:00'),
(2, 2, NULL, 1, NULL, 'Formation Evaluations & notes', 'Former le responsable pédagogique', '1970-01-01 00:00:00', 20, 'preventive', 'formation', 'online', 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-21 12:10:16\"}]', NULL, NULL, '2022-03-21 11:10:16', '2022-03-21 11:10:16'),
(3, 3, NULL, 1, NULL, 'Point de cadrage Charles (Charkouni)', NULL, '2022-03-10 00:00:00', 1, 'preventive', 'formation', 'online', 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-21 20:45:28\"}]', NULL, NULL, '2022-03-21 19:45:28', '2022-03-21 19:45:28');

-- --------------------------------------------------------

--
-- Table structure for table `school_tickets`
--

CREATE TABLE `school_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `collaborateurs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `files` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edit_history` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_tickets`
--

INSERT INTO `school_tickets` (`id`, `school_id`, `contact_id`, `responsable_id`, `collaborateurs`, `label`, `details`, `date`, `channel`, `nature`, `genre`, `priority`, `files`, `state`, `state_history`, `edit_history`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 1, NULL, 'test', 'ceci est un test', '2022-03-19 17:46:53', 'whatsapp', 'demande', 'technique', 'high', NULL, 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-19 17:46:53\"}]', NULL, NULL, '2022-03-19 16:46:53', '2022-03-19 16:46:53'),
(2, 2, NULL, 1, NULL, 'Validation des absences', 'Erreur lors de la validation des absences par jour (primaire)', '2022-03-21 12:08:22', 'whatsapp', 'incident', 'technique', 'high', '[\"2-validation-des-absences-1.png\"]', 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-21 12:08:22\"}]', NULL, NULL, '2022-03-21 11:08:22', '2022-03-21 11:08:22'),
(3, 3, NULL, 1, NULL, 'Remise des dossiers', NULL, '2022-03-21 20:43:59', 'whatsapp', 'demande', 'technique', 'high', NULL, 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-21 20:43:59\"}]', NULL, NULL, '2022-03-21 19:43:59', '2022-03-21 19:43:59'),
(4, 2, NULL, 1, NULL, 'Activer le paiement du mois 9 de la promotion 22/23', NULL, '2022-03-22 11:31:45', 'phone', 'demande', 'technique', 'high', NULL, 'nouveau', '[{\"etat\":\"nouveau\",\"date\":\"2022-03-22 11:31:45\"}]', NULL, NULL, '2022-03-22 10:31:45', '2022-03-22 10:31:45');

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `label`, `alias`, `user_by`, `created_at`, `updated_at`) VALUES
(1, 'test1', 'test1', 1, '2022-03-12 10:39:00', '2022-03-12 10:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `nom`, `prenom`, `email`, `password`, `telephone`, `picture`, `fonction`, `adresse`, `enabled`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Semoud', 'Ahmed', 'admin', '$2y$10$qsON5kb2Pddskgx2a1471OPAmP2as.OEJjYXCtoIRBKB9YgDKw/au', '0600000000', '1-semoud-ahmed.jpg', 'Manager', 'adresse', '1', NULL, NULL, NULL, '2022-03-21 09:34:07'),
(2, 3, 'Haddou', 'Sophia', 'sophia', '$2y$10$NeCM8yZaUQWwRaYi7kx60O530ABcuATDCHsxO5O5AhwwpwetEcmt.', '0625961972', '2-Haddou-Sophia.jpg', 'Account Manager', 'adresse', '1', NULL, NULL, NULL, '2022-03-14 12:37:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_alias_unique` (`alias`),
  ADD KEY `modules_user_by_foreign` (`user_by`);

--
-- Indexes for table `module_themes`
--
ALTER TABLE `module_themes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_themes_module_id_foreign` (`module_id`),
  ADD KEY `module_themes_theme_id_foreign` (`theme_id`),
  ADD KEY `module_themes_user_by_foreign` (`user_by`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_alias_unique` (`Alias`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schools_account_manager_foreign` (`account_manager`);

--
-- Indexes for table `school_contacts`
--
ALTER TABLE `school_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_contacts_school_id_foreign` (`school_id`);

--
-- Indexes for table `school_interventions`
--
ALTER TABLE `school_interventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_interventions_school_id_foreign` (`school_id`),
  ADD KEY `school_interventions_contact_id_foreign` (`contact_id`),
  ADD KEY `school_interventions_responsable_id_foreign` (`responsable_id`);

--
-- Indexes for table `school_tickets`
--
ALTER TABLE `school_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_tickets_school_id_foreign` (`school_id`),
  ADD KEY `school_tickets_contact_id_foreign` (`contact_id`),
  ADD KEY `school_tickets_responsable_id_foreign` (`responsable_id`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `themes_alias_unique` (`alias`),
  ADD KEY `themes_user_by_foreign` (`user_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `module_themes`
--
ALTER TABLE `module_themes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `school_contacts`
--
ALTER TABLE `school_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `school_interventions`
--
ALTER TABLE `school_interventions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `school_tickets`
--
ALTER TABLE `school_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_user_by_foreign` FOREIGN KEY (`user_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `module_themes`
--
ALTER TABLE `module_themes`
  ADD CONSTRAINT `module_themes_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `module_themes_theme_id_foreign` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`),
  ADD CONSTRAINT `module_themes_user_by_foreign` FOREIGN KEY (`user_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `schools`
--
ALTER TABLE `schools`
  ADD CONSTRAINT `schools_account_manager_foreign` FOREIGN KEY (`account_manager`) REFERENCES `users` (`id`);

--
-- Constraints for table `school_contacts`
--
ALTER TABLE `school_contacts`
  ADD CONSTRAINT `school_contacts_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `school_interventions`
--
ALTER TABLE `school_interventions`
  ADD CONSTRAINT `school_interventions_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `school_contacts` (`id`),
  ADD CONSTRAINT `school_interventions_responsable_id_foreign` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `school_interventions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `school_tickets`
--
ALTER TABLE `school_tickets`
  ADD CONSTRAINT `school_tickets_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `school_contacts` (`id`),
  ADD CONSTRAINT `school_tickets_responsable_id_foreign` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `school_tickets_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `themes`
--
ALTER TABLE `themes`
  ADD CONSTRAINT `themes_user_by_foreign` FOREIGN KEY (`user_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
