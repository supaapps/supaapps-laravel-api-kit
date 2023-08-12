<?php

namespace Supaapps\Supalara\Blueprints;

use Illuminate\Database\Schema\Blueprint;

class SlSchemaBlueprint extends Blueprint
{
    public function auditIds()
    {
        $this->unsignedBigInteger('created_by_id')->nullable();
        $this->unsignedBigInteger('updated_by_id')->nullable();
    }
}
