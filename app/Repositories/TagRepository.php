<?php

namespace App\Repositories;

use App\Helpers\ColectionPaginate;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use App\Repositories\CrudRepository;

class TagRepository extends CrudRepository  implements TagRepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Tag $model)
    {
        $this->model = $model;
    }



}
