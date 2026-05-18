-- Create content translations table
CREATE TABLE `content_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `translatable_type` varchar(255) NOT NULL COMMENT 'Model class name (e.g., App\Models\ModulesData)',
  `translatable_id` bigint(20) unsigned NOT NULL COMMENT 'ID of the translatable model',
  `locale` varchar(5) NOT NULL COMMENT 'Language code (en, ar, es)',
  `field_name` varchar(100) NOT NULL COMMENT 'Field name to translate (title, content, meta_description, etc.)',
  `field_value` longtext COMMENT 'Translated content',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_translations_unique` (`translatable_type`,`translatable_id`,`locale`,`field_name`),
  KEY `content_translations_locale_index` (`locale`),
  KEY `content_translations_translatable_index` (`translatable_type`,`translatable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores translations for dynamic content like hotels, tours, CMS pages';

-- Create menu translations table (for menu items)
CREATE TABLE `menu_translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `locale` varchar(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_translations_menu_locale_unique` (`menu_id`,`locale`),
  KEY `menu_translations_locale_index` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores translations for menu items';
