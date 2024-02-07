<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NormalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cette commande me permet d\'avoir la configuration du .env pour travailler avec la base de données en local';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        passthru('sh laravel.sh');
    }
}
