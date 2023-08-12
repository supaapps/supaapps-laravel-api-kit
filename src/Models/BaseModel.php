<?php

namespace Supaapps\Supalara\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public bool $isDeletable = true;
    public bool $readOnly = false;
    public bool $shouldPaginate = false;

    public function reloadRelations()
    {
        return $this->load($this->with);
    }

    public function getModelName()
    {
        return class_basename($this);
    }
}
