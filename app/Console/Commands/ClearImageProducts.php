<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class ClearImageProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:image-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Images Of Products';

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
            $exclude = 'default_url_product.png';
            $filesForDelete = array_filter(glob("public/images/products/*"), function ($file) use ($exclude) {
                return false === strpos($file, $exclude);
            });
            $file = new Filesystem;
            $file->delete($filesForDelete);
        }
    }
}
