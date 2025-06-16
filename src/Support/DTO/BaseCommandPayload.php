<?php

namespace Support\DTO;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class BaseCommandPayload
{
    public Model $model;
    public Request $request;
    public function __construct($model, $request)
    {
        $this->model = $model;
        $this->request = $request;
    }



    public function model(): Model
    {
        return $this->model;
    }

    public function request(): Request
    {
        return $this->request;
    }
}
