CREATE DATABASE IF NOT EXISTS `mafullu` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mafullu`;

CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `price_usd` decimal(10,2) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `preview_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sales_count` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `usage_limit` int unsigned DEFAULT NULL,
  `times_redeemed` int unsigned NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_email` varchar(255) NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount_usd` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_usd` decimal(10,2) NOT NULL,
  `crypto_currency` varchar(255) NOT NULL,
  `crypto_amount` decimal(18,8) NOT NULL,
  `crypto_rate_used` decimal(18,8) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `screenshot_path` varchar(255) DEFAULT NULL,
  `download_token` char(36) DEFAULT NULL,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_download_token_unique` (`download_token`),
  KEY `orders_product_id_foreign` (`product_id`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `download_attempts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `was_successful` tinyint(1) NOT NULL DEFAULT 0,
  `failure_reason` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `download_attempts_order_id_foreign` (`order_id`),
  CONSTRAINT `download_attempts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

