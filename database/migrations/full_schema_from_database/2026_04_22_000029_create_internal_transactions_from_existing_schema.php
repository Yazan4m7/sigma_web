<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('internal_transactions')) {
            return;
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `internal_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` bigint(20) unsigned DEFAULT NULL,
  `to_user` bigint(20) unsigned DEFAULT NULL,
  `from_doc` bigint(20) unsigned DEFAULT NULL,
  `amount` double NOT NULL,
  `date` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `is_collected` tinyint(1) NOT NULL DEFAULT 0,
  `to_bank` int(11) DEFAULT NULL,
  `payment_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1074 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_transactions');
    }
};
