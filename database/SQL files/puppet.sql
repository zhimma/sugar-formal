SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `puppet_analysis_cells` (
  `id` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `column_index` int(11) NOT NULL,
  `row_index` int(11) NOT NULL,
  `val` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `num` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `puppet_analysis_columns` (
  `id` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `column_index` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `puppet_analysis_rows` (
  `id` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `row_index` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `puppet_analysis_cells`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `puppet_analysis_columns`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `puppet_analysis_rows`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `puppet_analysis_cells`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `puppet_analysis_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `puppet_analysis_rows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
