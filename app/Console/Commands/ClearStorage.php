<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear All PDF files and Product images';

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
        $flag = $this->confirm('Are you sure to clear all ?..... ');
        if ($flag || $this->confirm('Are you sure with clearing image of products?..... ')) {
            $this->info('Clearing image of products.....');
            $exclude = 'default_url_product.png';
            $filesForDelete = array_filter(glob("public/images/products/*"), function ($file) use ($exclude) {
                return false === strpos($file, $exclude);
            });
            $file = new Filesystem;
            $file->delete($filesForDelete);
            $this->info('Cleared image of products.....');
        }

        if ($flag || $this->confirm('Are you sure with clearing pdf files??')) {
            $this->info('Clearing pdf files.....');
            $file = new Filesystem;
            $file->cleanDirectory('public/export/pdf/bill');
            $file->cleanDirectory('public/export/pdf/paid');
            $file->cleanDirectory('public/export/pdf/order');
            $file->cleanDirectory('public/export/pdf/material');
            $this->info('Cleared pdf files.....');
        }
        //Done
        $this->info('Done!');
    }
}
