<?php

namespace Supaapps\Supalara\Actions;

use Supaapps\Supalara\Models\SlActionType;

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
