<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{





    public function stats()
    {


        $data['users_count'] = Cache::get('user_count' , User::count()) ;
        $data['posts_count'] = Cache::get('posts_count' , Post::count());
        $data['user_dont_have_posts'] = Cache::get('user_dont_have_posts' , User::doesntHave('posts')->count());

        return response()->success('successfully.' ,$data);
    }



}
