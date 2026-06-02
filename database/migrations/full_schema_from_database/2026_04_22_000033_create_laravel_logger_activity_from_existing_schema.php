<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('laravel_logger_activity')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `laravel_logger_activity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `userType` varchar(255) NOT NULL,
  `userId` bigint(20) unsigned DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `ipAddress` varchar(255) NOT NULL,
  `userAgent` text NOT NULL,
  `locale` varchar(255) NOT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `methodType` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_logger_activity');
    }
};
