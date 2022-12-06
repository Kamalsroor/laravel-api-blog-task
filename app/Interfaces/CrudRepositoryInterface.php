<?php

namespace App\Interfaces;

interface CrudRepositoryInterface
{
    public function all(array $queries);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function findInAll($id);

    public function findTrashed($id);

    public function find($id);

    public function UpdateActive($model, $active);

    public function load($relations);

    public function destroy($model);

    public function restore($model);

    public function max($column);

    public function forceDelete($model);

    public function uploadFile($column , array $data);

}
