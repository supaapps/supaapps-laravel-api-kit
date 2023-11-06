<?php

namespace Supaapps\LaravelApiKit\Actions;

use Supaapps\LaravelApiKit\Models\SlActionRecord;

interface ActionInterface extends \JsonSerializable
{
    public static function create(...$args): SlActionRecord;
}
