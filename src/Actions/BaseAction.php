<?php

namespace Supaapps\Supalara\Actions;

use Supaapps\Supalara\Models\SlActionRecord;

abstract class BaseAction implements ActionInterface
{
    public int $actionId = 0;
    public string $modelName = '';

    public function getActionId()
    {
        return $this->actionId;
    }

    public static function create(
        ...$args
    ): SlActionRecord {
        $new = new static(...$args);
        return $new->save();
    }

    public function save(): SlActionRecord
    {
        return SlActionRecord::create([
           'sl_action_type_id' => $this->getActionId(),
           'payload' => $this->jsonSerialize(),
           'model' => $this->modelName,
        ]);
    }
}
