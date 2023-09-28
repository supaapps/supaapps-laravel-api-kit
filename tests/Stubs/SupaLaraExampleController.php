<?php

namespace Tests\Stubs;

use Supaapps\Supalara\Controllers\BaseCrudController;
use Supaapps\Supalara\Controllers\CrudTraits\AllCrudTraits;

class SupaLaraExampleController extends BaseCrudController
{
    use AllCrudTraits;

    public string $model = SupaLaraExampleModel::class;
}
