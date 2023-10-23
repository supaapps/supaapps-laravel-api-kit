<?php

namespace Supaapps\Supalara\Actions;

use Supaapps\Supalara\Models\SlActionRecord;

interface ActionInterface extends \JsonSerializable
{
    public static function create(...$args): SlActionRecord;
}
