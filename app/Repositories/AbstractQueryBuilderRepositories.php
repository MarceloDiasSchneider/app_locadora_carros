<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstractQueryBuilderRepositories
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function filterIds($ids)
    {
        $ids = explode(',', $ids);
        $this->model = $this->model->whereIn('id', $ids);
    }

    public function filterFieldExactly($field, $values)
    {
        $values = explode(',', $values);
        $this->model = $this->model->whereIn($field, $values);
    }

    public function filterFieldLike($field, $value)
    {
        $this->model = $this->model->where($field, 'like', '%'.$value.'%');
    }

    public function filterFields($fields)
    {
        $this->model = $this->model->selectRaw($fields);
    }

    public function filterRelationsFields($method, $fields, $requiredId)
    {
        $this->model = $this->model->with("$method:$requiredId,$fields");
    }

    public function getResult()
    {
        return $this->model->get();
    }
}
?>
