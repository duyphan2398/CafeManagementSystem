<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearReceipts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:receipts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Receipts PDF';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Are you sure ?')) {
            $this->info('Processing...');
            $file = new Filesystem;
            $file->cleanDirectory('storage/app/public/export/pdf/paid');
            $this->info('Successfully !');
        }
    }
}
