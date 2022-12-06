<?php

namespace App\Console\Commands;

use App\Models\User;
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

        $response = Http::get('https://randomuser.me/api/');
        if($response->successful()){
            $data = $response->collect();
            $user =$data['results'] ;
            $name = $user['name']['first'] . ' ' . $user['name']['last'] ;
            $phone = $user['phone'];
            $password = bcrypt('password');

            $user = User::create([
                'name' => $name,
                'phone' => $phone,
                'password' => $password,
            ]);
        }
        return Command::SUCCESS;
    }
}
