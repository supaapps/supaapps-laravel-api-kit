<?php

namespace Supaapps\Supalara\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Supaapps\Supalara\Models\Category;

class SlActionRecord extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'sl_action_type_id',
        'payload',
        'model',
    ];

    protected $with = [
        'actionType',
    ];

    protected $appends = [
        'modelData',
    ];

    public function actionType()
    {
        return $this->belongsTo(SlActionType::class);
    }

    public function getModelDataAttribute()
    {
        if (is_null($this->model) || !isset($this->payload['modelId'])) {
            return null;
        }

        $modelClass = '\\App\\Models\\' . $this->model;
        $model = $modelClass::find($this->payload['modelId']);
        return is_null($model) ? null : $model;
    }

    public function getPayloadAttribute()
    {
        return isset($this->attributes['payload'])
            ? json_decode($this->attributes['payload'], JSON_PRETTY_PRINT)
            : null;
    }
}
