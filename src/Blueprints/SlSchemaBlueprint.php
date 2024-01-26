<?php

namespace Supaapps\LaravelApiKit\Blueprints;

use Illuminate\Database\Schema\Blueprint;

/**
 * @deprecated version
 */
class SlSchemaBlueprint extends Blueprint
{
    public function auditIds()
    {
        $this->unsignedBigInteger('created_by_id')->nullable();
        $this->unsignedBigInteger('updated_by_id')->nullable();
    }
}
