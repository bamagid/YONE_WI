<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DockerizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dockerize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cette commande me permet d\'avoir la configuration du .env pour dockeriser';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
