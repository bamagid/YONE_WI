<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cette commande me permet de faire des tests sans changer manuellement le .env';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    
    }
}