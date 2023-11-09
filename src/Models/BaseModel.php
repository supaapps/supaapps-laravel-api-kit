<?php

namespace Supaapps\LaravelApiKit\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function reloadRelations()
    {
        return $this->load($this->with);
    }

    public function getModelName()
    {
        return class_basename($this);
    }
}
