<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class RemoveOldPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trashed:posts';

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

        $posts = Post::onlyTrashed()->where('deleted_at', '<', now()->subDays(30)->endOfDay())->get();
        foreach ($posts as $post) {
            $post->forceDelete();
        }
        return Command::SUCCESS;
    }
}
