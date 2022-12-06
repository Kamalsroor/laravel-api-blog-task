<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{


    public PostRepositoryInterface $BaseRepository;
    protected $model;

    public function __construct(PostRepositoryInterface $BaseRepository , Post $post)
    {
        $this->BaseRepository = $BaseRepository;
        $this->model = $post;
    }

    public function index(Request $request)
    {
        $queries = ['deleted','length'];

        return PostResource::collection(
            $this->BaseRepository->all($request->only($queries))
        );
    }


    public function show($id)
    {
        return new PostResource(
            $this->BaseRepository->find($id)->load('tags')
        );
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = $this->BaseRepository->create($request->validated());
        Cache::remember('posts_count', 86400, function() {
            return Post::count();
         });
        Cache::remember('user_dont_have_posts', 86400, function() {
            return User::doesntHave('posts')->count();
         });
        return response()->success('success' , new PostResource($post));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request , $id)
    {
        $post = $this->BaseRepository->update($request->validated() , $id);
        $post = $this->BaseRepository->find($id)->load('tags');
        return response()->success('update successfully' ,  new PostResource($post));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->BaseRepository->findInAll($id);

        $this->BaseRepository->destroy($post);
        Cache::remember('posts_count', 86400, function() {
            return Post::count();
         });
        Cache::remember('user_dont_have_posts', 86400, function() {
            return User::doesntHave('posts')->count();
         });
        return response()->success('deleted successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\ServiceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $this->BaseRepository->restore($this->BaseRepository->findTrashed($id));
        Cache::remember('posts_count', 86400, function() {
            return Post::count();
         });
        Cache::remember('user_dont_have_posts', 86400, function() {
            return User::doesntHave('posts')->count();
         });

        return response()->success('restored successfully');
    }


}
