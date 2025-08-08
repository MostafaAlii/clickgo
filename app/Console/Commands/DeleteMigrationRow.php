<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class DeleteMigrationRow extends Command
{
    protected $signature = 'migration:delete-row {table} {id*}';
    protected $description = 'احذف صف أو أكتر من أي جدول عن طريق الـ ID';

    public function handle()
    {
        $table = $this->argument('table');
        $ids = $this->argument('id');

        $deleted = DB::table($table)->whereIn('id', $ids)->delete();

        $this->info("$deleted row(s) deleted from $table.");
    }
}
