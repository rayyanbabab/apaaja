<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class BackfillItemKode extends Command
{
    protected $signature   = 'artilia:backfill-kode';
    protected $description = 'Generate missing item codes (kode) using ITM-{id} format';

    public function handle(): void
    {
        $items = Item::whereNull('kode')->orWhere('kode', '')->get();

        if ($items->isEmpty()) {
            $this->info('✅ All items already have a kode. Nothing to do.');
            return;
        }

        $bar = $this->output->createProgressBar($items->count());
        $bar->start();

        foreach ($items as $item) {
            $item->update(['kode' => 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT)]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Done. {$items->count()} item(s) updated.");
    }
}
