<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('repeat_cases')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `repeat_cases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repeat_cases_user_id_foreign` (`user_id`),
  KEY `repeat_cases_order_id_foreign` (`order_id`),
  CONSTRAINT `repeat_cases_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `repeat_cases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('repeat_cases');
    }
};
