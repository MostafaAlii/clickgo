<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class DeleteTables  extends Command
{
    protected $signature = 'tables:delete {tables*} {--force : تأكيد الحذف بدون سؤال}';
    protected $description = 'مسح جدول أو أكثر مع البيانات بالكامل';

    public function handle()
    {
        $tables = $this->argument('tables');
        $force = $this->option('force');

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                $this->error("❌ الجدول {$table} غير موجود.");
                continue;
            }

            if (!$force) {
                if (!$this->confirm("⚠ هل أنت متأكد أنك تريد حذف الجدول {$table} بالكامل؟")) {
                    $this->info("⏩ تم تخطي الجدول {$table}.");
                    continue;
                }
            }

            // حذف الجدول
            Schema::dropIfExists($table);

            $this->info("✅ تم حذف الجدول {$table} بنجاح.");
        }

        return Command::SUCCESS;
    }
}
