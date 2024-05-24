<?php

namespace App\Console\Commands;

use App\Models\Table;
use Illuminate\Console\Command;

class RegulCategoryIdTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:regul-category-id-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = Table::all();

        foreach ($tables as $table) {
            $table->category_id = $table->game->category_id ?? 0;

            $table->save();
        }
    }
}
