<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearBills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:bills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Bills In Storage';

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
            $file->cleanDirectory('storage/app/public/export/pdf/bill');
            $this->info('Successfully !');
        }
    }
}
