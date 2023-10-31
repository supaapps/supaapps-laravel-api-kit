<?php

namespace Supaapps\LaravelApiKit\Actions;

use Supaapps\LaravelApiKit\Models\SlActionType;

class ActionModelCreate extends BaseAction
{
    public int $actionId = SlActionType::ACT_MODEL_C;

    /**
     * @var int
     */
    private int $modelId;


    public string $modelName = '';

    public function __construct($model)
    {
        $this->modelId = $model->id;
        $this->modelName = $model->getModelName();
    }

    /**
     * @return false|string
     */
    public function jsonSerialize()
    {
        return json_encode([
            'modelId' => $this->modelId,
        ]);
    }
}
