<?php

namespace App\Repositories;

use App\Helpers\ColectionPaginate;
use App\Interfaces\CrudRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CrudRepository implements CrudRepositoryInterface
{
    // model property on class instances
    protected $model;


    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    // get a all  record in the database
    public function all($queries = [])
    {
        $length = isset($queries['length']) ? $queries['length'] : 10;
        $deleted = isset($queries['deleted']) ? $queries['deleted'] : false;


        return $this->model
            ->paginate($length)
            ->withQueryString();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // update record in the database
    public function update(array $data, $id)
    {

        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    // remove record from the database

    public function findInAll($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    // remove record from the database

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    // remove record from the database

    public function findTrashed($id)
    {
        return $this->model->onlyTrashed()->findOrFail($id);
    }

    // show the record with the given id

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    // Get the associated model

    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Set the associated model
    public function UpdateOrder($model, $data)
    {
        foreach ($data as $value) {
            $model->find($value['id'])->update(['order_id' => $value['order_id']]);
        }
        return true;
    }

    // Set the associated model
    public function UpdateActive($model, $active)
    {
        $model->update(['active' => $active]);
        return $model;
    }

    // Set the associated model
    public function duplicate($model)
    {
        $model->replicate();
        return $model;
    }

    // Set the associated model
    public function destroy($model)
    {
        $model->delete();
        return $model;
    }

    // Set the associated model
    public function restore($model)
    {
        $model->restore();
        return $model;
    }

    // Set the associated model
    public function forceDelete($model)
    {
        $model->forceDelete();
        return $model;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    // Eager load database relationships
    public function load($relations)
    {
        return $this->model->load($relations);
    }

    // Eager load database relationships
    public function max($column)
    {
        return $this->model->max($column);
    }


    // Eager load database relationships
    public function uploadFile($column , $data)
    {

        $path = null;
        if(isset($data[$column]) && is_file($data[$column])){
            $file = $data[$column];

            $namewithextension = $file->getClientOriginalName(); //Name with extension 'filename.jpg'
            $name = explode('.', $namewithextension)[0]; // Filename 'filename'
            $extension = $file->getClientOriginalExtension(); //Extension 'jpg'
            $uploadname = $name . '_' . time() . '.' . $extension;


            $path = "posts/".$uploadname;

            Storage::disk('public')->put($path, file_get_contents($data[$column]));
        }

        return $path;
    }



}
