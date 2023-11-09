<?php

namespace Supaapps\LaravelApiKit\Actions;

use Supaapps\LaravelApiKit\Models\SlActionType;

class ActionModelUpdate extends BaseAction
{
    public int $actionId = SlActionType::ACT_MODEL_U;


    /**
     * @var int
     */
    private int $modelId;
    private array $original;
    private array $changes;

    public string $modelName = '';


    public function __construct($model)
    {
        $this->modelId = $model->id;
        $this->modelName = $model->getModelName();
        $this->original = $model->getOriginal();
        $this->changes = $model->getChanges();
    }

    /**
     * @return false|string
     */
    public function jsonSerialize()
    {
        return json_encode([
            'modelId' => $this->modelId,
            'original' => $this->original,
            'changes' => $this->changes,
        ]);
    }
}
