<?php

namespace App\Repositories;

use App\Helpers\ColectionPaginate;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Repositories\CrudRepository;

class PostRepository extends CrudRepository  implements PostRepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Post $model)
    {
        $this->model = $model;
    }



    // get a all  record in the database
    public function all($queries = [])
    {
        $length = isset($queries['length']) ? $queries['length'] : 10;
        $deleted = isset($queries['deleted']) ? $queries['deleted'] : false;
        $data = $this->model
        ->with('tags')
        ->where('user_id' , Auth()->user()->id)
        ->orderBy('pinned' , 'DESC');

        if($deleted){
            $data->onlyTrashed();
        }

        return $data->paginate($length)
            ->withQueryString();
    }


    // remove record from the database

    public function findInAll($id)
    {
        return $this->model->where('user_id' , Auth()->user()->id)->withTrashed()->findOrFail($id);
    }

    // remove record from the database

    public function find($id)
    {
        return $this->model->where('user_id' , Auth()->user()->id)->findOrFail($id);
    }


    // create a new record in the database
    public function create(array $data)
    {

        $data['cover'] = $this->uploadFile('cover' , $data);
        $data['user_id'] = Auth()->user()->id;
        $model = $this->model->create($data);
        $model->tags()->sync($data['tags']);
        return $model;
    }


    // update record in the database
    public function update(array $data, $id)
    {
        if(isset($data['cover'])){
            $data['cover'] = $this->uploadFile('cover' , $data);
        }

        $record = $this->find($id);
        $record->tags()->sync($data['tags']);
        $record->update($data);

        return $record;
    }




}
