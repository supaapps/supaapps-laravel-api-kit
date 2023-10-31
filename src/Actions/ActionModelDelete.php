<?php

namespace Supaapps\LaravelApiKit\Actions;

use Supaapps\LaravelApiKit\Models\SlActionType;

class ActionModelDelete extends BaseAction
{
    public int $actionId = SlActionType::ACT_MODEL_D;

    /**
     * @var array
     */
    private array $model;

    public string $modelName = '';

    public function __construct($model)
    {
        $this->model = $model->toArray();
        $this->modelName = $model->getModelName();
    }

    /**
     * @return false|string
     */
    public function jsonSerialize()
    {
        return json_encode([
            'model' => $this->model,
        ]);
    }
}
