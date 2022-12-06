<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RandomuserStoredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'randomuser:stored';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove trashed form 30 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $response = Http::get(' https://randomuser.me/api/');
        dd($response);
        return Command::SUCCESS;
    }
}
