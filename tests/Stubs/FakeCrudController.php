<?php

namespace Tests\Stubs;

use Supaapps\Supalara\Controllers\BaseCrudController;
use Supaapps\Supalara\Controllers\CrudTraits\CrudIndexTrait;

class FakeCrudController extends BaseCrudController
{
    use CrudIndexTrait;
}
